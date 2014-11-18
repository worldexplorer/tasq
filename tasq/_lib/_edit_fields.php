<!-- BEGIN _edit_fields.php -->

<?
if (!isset($add_suffix)) $add_suffix = "";
if (!isset($back2list_suffix)) $back2list_suffix = "";
if (!isset($f5_suffix)) $f5_suffix = "";

if ($fixed_suffix != "") {
	if ($add_suffix != "") $add_suffix .= "&";
	$add_suffix .= $fixed_suffix;

	if ($back2list_suffix != "") $back2list_suffix .= "&";
	$back2list_suffix .= $fixed_suffix;

	if ($f5_suffix != "") $f5_suffix .= "&";
	$f5_suffix .= $fixed_suffix;
}

if (!isset($add_href)) {
	$add_href = $entity . "-edit.php";
	if ($add_suffix != "") $add_href = $add_href . "?" . $add_suffix;
}

?>

<table cellspacing=1 cellpadding=3 align=center width=100%>

<? if ($no_topline == 0) { ?>
<tr><td colspan=2>
	<table cellpadding=0 cellspacing=0 border=0 align=center>
	<tr valign=top>

<? if ($_submenu != "") { ?>
		<td style="padding-left: 1em; padding-right: 1em;"><?=$_submenu?></td>
		<td style="padding-left: 1em; padding-right: 1em; padding-top: 0.5em" align="right">
<? } else { ?>
		<td align="center">
<? } ?>

<? if ($no_add == 0 && isset($add_href)) { ?>
<? if (!in_array($entity, $no_addentity_list)) { ?>
			<a href="<?=$add_href?>"><?=$msg_bo_add?> <?=$add_msg?></a>
<? } else { ?>
			<span style="color: #AAAAAA"><?=$msg_bo_add_unable?></span>
<? } ?>
<? } ?>
		</td>
	</tr>
	</table>
	</td>
</tr>
<? } ?>

<!--tr><td colspan=2 align=center-->


<!--form method=get-->
<form enctype="multipart/form-data" method="post" id="form_edit" name="form_edit">
	<input type=hidden name=id value='<?=$id?>'>
	<input type=hidden name=mode value=update>
	<input type=hidden name=layer_opened_nr value="<?=$layer_opened_nr?>">
	<input type=hidden name=no_freetext value="<?=$no_freetext?>">
	<input type=hidden name=as_freetext value="<?=$as_freetext?>">
<?
if ($no_savebutton == 0) {
	$local_fixed = array();
	$passive_its = array("ahref", "table_ro");
	foreach ($fixed_hash as $dbfield => $it_control) {
		if (isset($entity_fields[$dbfield]) || in_array($it_control, $passive_its)) {
			// local_fixed without doubles and dumbs
		} else {
			$local_fixed[$dbfield] = $it_control;
		}
	}
	$local_fixed_hiddens = hidden_fromhash($local_fixed);
	echo $local_fixed_hiddens;
}
?>

<?
if ($backtolist_href == "") {
	$backtolist_href = $entity . ".php";
	if ($back2list_suffix != "") $backtolist_href .= "?" . $back2list_suffix;
	$backtolist_href = "&nbsp;&nbsp;&nbsp;&nbsp;<a href='$backtolist_href'>$msg_bo_backtolist</a>";
}

if ($no_f5 == 0) {
	$prev_center_next = "<a href='" . $_SERVER["SCRIPT_NAME"] . "?id=$id";
	if ($f5_suffix != "") $prev_center_next .= "&" . $f5_suffix;

	$prev_center_next .= "' title='$msg_bo_F5_title'>F5</a>";
}

if ($no_backtolist == 0) $prev_center_next .= $backtolist_href;

//	(in_array($entity, $no_prevnext_list) && ($no_prevnext == 1))

if ($id > 0) {
	if ($no_prevnext == 0) {
		if (!in_array($entity, $no_prevnext_list) || ($no_prevnext == 1)) {

			if ($href_prev == "" && $href_next == "") {
				$fixed_hash = fixedhash_fromfixed($fixed_fields, select_entity_row());
				if ($prevnext_published == 1) $fixed_hash["published"] = 1;

				$tpl_hash = array(
					"prev" => "&laquo;&nbsp;<a href='#SCRIPT_NAME#?id=#ID#&#FIXED_SUFFIX#' title='#IDENT#'>$msg_bo_previous_element</a> (#CNT#)",
					"prev_empty" => "",
					"next" => "(#CNT#) <a href='#SCRIPT_NAME#?id=#ID#&#FIXED_SUFFIX#' title='#IDENT#'>$msg_bo_next_element</a>&nbsp;&raquo;",
					"next_empty" => ""
					);

				$href_prevnext_hash = select_entity_prevnext($fixed_hash, $entity, $id, $tpl_hash, $fixed_hash);
				//pre ($href_prevnext_hash);
			
				$href_prev = $href_prevnext_hash["prev"];
				$href_next = $href_prevnext_hash["next"];
			}
		}
	}
}
?>

<? if ($no_topline == 0) { ?>
<tr><td colspan=2 align=center>
<table cellspacing=0 cellpadding=0 width=100% align=center>
	<td width=30% align=left nowrap><?=$href_prev?></td>
	<td align=center><?=$prev_center_next?></td>
	<td width=30% align=right nowrap><?=$href_next?></td>
</table>
</td></tr>
<? } ?>

<?

$entity_dbfields_array = array();
$db_values_array = array();

if ($id > 0) {
	$entity_dbfields_result = mysql_list_fields($mysql_info["db"], TABLE_PREFIX . $entity);
	for ($i = 0; $i < mysql_num_fields($entity_dbfields_result); $i++) {
	    $entity_dbfields_array[] =  mysql_field_name($entity_dbfields_result, $i);
	}
	//print_r ($entity_dbfields_array);
	
	$sql_fields = "";
	
	foreach ($entity_fields as $name => $entity_field_params_array) {
		if (in_array($name, $entity_dbfields_array)) {
			if ($sql_fields != "") $sql_fields .= ", ";
			$sql_fields .= $name;
		}
	}
 	$sql_fields .= ", id";

 	if ($force_selectall_onedit == 1) $sql_fields = sqlin_fromarray($entity_dbfields_array);

	$query = "select $sql_fields from $entity where id=$id";
	$query = add_sql_table_prefix($query);
	$result = mysql_query($query) or die("SELECT ID failed:<br>$query<br>" . mysql_error());
	$db_values_array = mysql_fetch_assoc($result);
}

//pre($entity_dbfields_array);
//pre($db_values_array);

$columned_inside = 0;

foreach ($entity_fields as $name => $it_params) {
	$field_txt		= (isset($it_params[0])) ? $it_params[0] : "";
	$input_type		= (isset($it_params[1])) ? $it_params[1] : "";
	$default		= (isset($it_params[2])) ? $it_params[2] : "";
	$param1			= (isset($it_params[3])) ? $it_params[3] : "";
	$param2			= (isset($it_params[4])) ? $it_params[4] : "";
	$param3			= (isset($it_params[5])) ? $it_params[5] : "";
	$param4			= (isset($it_params[6])) ? $it_params[6] : "";
	$param5			= (isset($it_params[7])) ? $it_params[7] : "";
	$param6			= (isset($it_params[8])) ? $it_params[8] : "";
	$param7			= (isset($it_params[9])) ? $it_params[9] : "";
	$param8			= (isset($it_params[10])) ? $it_params[10] : "";


	$name_strict = makestrict($name);

//	$name_msg_search = 
// $msg_fields["customer-published-edit"] >> $msg_fields["customer-published"]
// >> $msg_fields["published-edit"] >> $msg_fields["published"] >> 

	$ident_pos = strpos(makestrict($name), "ident");
	if ($field_txt == "" && $ident_pos !== false && $ident_pos == 0) {
		if ($field_txt == "" && isset($entity_list_single[$entity])) $field_txt = $entity_list_single[$entity];
		if ($field_txt == "" && isset($entity_list[$entity])) $field_txt = $entity_list[$entity];
	}


	if ($field_txt == "" && isset($msg_fields[$entity . "-" . $name . "-edit"])) $field_txt = $msg_fields[$entity . "-" . $name . "-edit"];
	if ($field_txt == "" && isset($msg_fields[$entity . "-" . $name])) $field_txt = $msg_fields[$entity . "-" . $name];


	if ($field_txt == "" && isset($msg_fields[$name . "-edit"])) $field_txt = $msg_fields[$name . "-edit"];
	if ($field_txt == "" && isset($msg_fields[$name])) $field_txt = $msg_fields[$name];
	if ($field_txt == "" && isset($msg_fields[$name_strict])) $field_txt = $msg_fields[$name_strict];



// $entity_fields["cgroup"]:
// >> $msg_fields["cgroup-edit"] >> $msg_fields["cgroup"] >> $msg_fields["published"] >> 

	$group_pos = strpos($name_strict, "group");
	if ($field_txt == "" && $group_pos !== false) {
//		echo "entity_list[" . makestrict($name) . "] = [" . $entity_list[$name_strict] . "]";
		if ($field_txt == "" && isset($msg_fields[$name_strict . "-edit"]))  $field_txt = $msg_fields[$name_strict . "-edit"];
		if ($field_txt == "" && isset($msg_fields[$name_strict])) $field_txt = $msg_fields[$name_strict];

		if ($input_type == "m2mcb") {
			if ($field_txt == "" && isset($entity_list[$name_strict])) $field_txt = $entity_list[$name_strict];
		} else {
			if ($field_txt == "" && isset($entity_list_single[$name_strict])) $field_txt = $entity_list_single[$name_strict];
		}
	}

	if ($field_txt == "" && isset($entity_list_single[$name_strict])) $field_txt = $entity_list_single[$name_strict];


	if ($input_type == "layer_open" || $input_type == "layer_close") {
		$name_strict_notilde = makestrict($name, "-");
		$name_strict_notilde = preg_replace("/^~*/", "", $name_strict_notilde);
		if ($field_txt == "" && isset($msg_fields[$name_strict_notilde])) $field_txt = $msg_fields[$name_strict_notilde];
	}


	if (in_array($input_type, array("checkbox", "number", "textfield", "textarea", "image", "file", "upload"))
		|| strpos($input_type, "timestamp") == 0
		|| strpos($input_type, "datetime") == 0
		|| strpos($input_type, "select") == 0
		|| strpos($input_type, "swapdbfield") !== false
	) {
		if ($param1 == "" && isset($msg_fields[$entity . "-" . $name_strict . "-graycomment"])) $param1 = $msg_fields[$entity . "-" . $name_strict . "-graycomment"];
		if ($param1 == "" && isset($msg_fields[$name_strict . "-graycomment"])) $param1 = $msg_fields[$name_strict . "-graycomment"];

//	"answer_sent" => array ("", "checkbox", 0, ""),
		if ($param1 == "" && isset($msg_fields[$name . "-graycomment"])) $param1 = $msg_fields[$name . "-graycomment"];
		if ($param1 == "" && isset($msg_fields[$entity . "-" . $name . "-graycomment"])) $param1 = $msg_fields[$entity . "-" . $name . "-graycomment"];
	}


	$underscore_position = strpos($name, "_");
	if ($underscore_position !== false && $underscore_position < strlen($name)) {
		$name_strict_suffix = substr($name, $underscore_position+1);
		if ($field_txt == "" && isset($msg_fields["_" . $name_strict_suffix])) $field_txt = $msg_fields["_" . $name_strict_suffix];
		if ($param1 == "" && isset($msg_fields["_" . $name_strict_suffix . "-graycomment"])) $param1 = $msg_fields["_" . $name_strict_suffix . "-graycomment"];
	}


	$name_strict = makestrict($name);
	$input_type_strict = makestrict($input_type);

	$ctx_no_freetext_field = $name . "_no_freetext";
	$ctx_no_freetext_has_field = 0;
	$ctx_no_freetext_db = 0;
	$ctx_no_freetext_get = get_number($ctx_no_freetext_field);
	$ctx_no_freetext_switchlink = "";

	
	if ($input_type_strict == "freetext") {
		$ctx_no_freetext_has_field = entity_has_field($entity, $ctx_no_freetext_field);
		if ($ctx_no_freetext_has_field == 1) {
			$ctx_no_freetext_db = select_field($ctx_no_freetext_field);
		}
	}

	if ($input_type_strict == "freetext" && $ctx_no_freetext_has_field == 1) {
		if (isset($_REQUEST[$ctx_no_freetext_field])) {
			if ($ctx_no_freetext_db != $ctx_no_freetext_get) {
				$updated = update(array($ctx_no_freetext_field => $ctx_no_freetext_get));
				$errormsg .= "$msg_bo_updated[$updated] $ctx_no_freetext_field=$ctx_no_freetext_get $msg_bo_updated_for [$entity]:[$id]";
				$ctx_no_freetext_db = $ctx_no_freetext_get;
			}
		}

		$ctx_no_freetext_switchlink = ($ctx_no_freetext_db == 0)
			? "<div align=right><a href='$entity-edit.php?id=$id&$ctx_no_freetext_field=1' title='msg_bo_switch_to_textarea_tip'>$msg_bo_switch_to_textarea</a></div>"
			: "<div align=right><a href='$entity-edit.php?id=$id&$ctx_no_freetext_field=0' title='$msg_bo_switch_to_freetext_tip'>$msg_bo_switch_to_freetext</a></div>";
		
	}


	if ($input_type_strict == "freetext"
			&& ($no_freetext == 1 || $ctx_no_freetext_db == 1)
		) {
		$input_type = $freetext2textarea[$input_type];
		$input_type_strict = makestrict($input_type);
	}

// while editing existing record "faq-edit.php?id=2" we get row from mysql
	if (in_array($name, $entity_dbfields_array)) {
		$field_txt = hash_by_tpl($db_values_array, $field_txt);

		$value = $db_values_array[$name];		// if id>0 then get from db
//		$value = stripslashes($value);			// turned off for textarea not to eat typed \
//		echo "[$name]:[$value]:[$default] taken from [db_values_array]"
//			. "=<pre>" . pr($db_values_array) . "</pre>"
//			. "<br>";
	} else {
// when we clik on "add new FAQ" - no record from mysql, applying defaults
		switch($input_type_strict) {
			case "select":
			case "textfield":
			case "textarea":
			case "checkbox":
			case "freetext":
				$value = $default;
				break;

			case "boolean":
			default:
				$value = get_string($name);				// for default values before insert
				break;
		}

//		echo "[$name]:[$value]:[$default] taken from default=[$default] for $input_type_strict<br>";
		
		if ($value == "") {
			if ($name == "ident") $value = $ident_new;
			if ($name_strict == "date") {
				$value = $today_dt_datetime;
				if ($input_type_strict == "timestamp") $value = $today_ts_datetime;
				if ($input_type_strict == "timestampro") $value = "";
			}
		}
	}
	
	$back_row = "";
	switch ($input_type) {
		case "img_layer":
			$imgtype_tooltip = "";
//			$imgtype_row = select_entity_row(array("hashkey" => $field_txt), "imgtype");
			$imgtype_row = get_cached_imgtype($field_txt, "whole_row");
			$imgtype_id = $imgtype_row["id"];

			if (isset($imgtype_row["id"])) {
				if ($default != "") $imgtype_row["imglimit"] = $default;
				$back_row = img_layer($imgtype_row);
				$field_txt = $imgtype_row["ident"];
				if ($img_layer_imgcnt > 0) $field_txt .= " (" . $img_layer_imgcnt . ")";
				$imgtype_tooltip = hash_by_tpl($imgtype_row, $imgtype_row["content"]);
			} else {
				$back_row =  back_row("[$field_txt] $msg_bo_imgtype_not_defined [<a href='imgtype-edit.php?hashkey=$field_txt'>$msg_bo_it_add</a>]", "");
			}
			
			$ondblclick = <<< EOT
ondblclick="javascript:popup_blank('imgtype-edit.php?id=$imgtype_id')"
EOT;

			$layer_open_html = ($wrap_imglayer == 1) ? layer_open($name, $field_txt, $default, $imgtype_tooltip, $ondblclick) : "";
			$layer_close_html = ($wrap_imglayer == 1) ? layer_close($name, $field_txt, $default, $imgtype_tooltip) : "";
			$back_row = $layer_open_html . $back_row . $layer_close_html;

			break;

		case "imgtype_layer":
			$back_row = imgtype_layer($name, $field_txt, $default);
			break;

		case "m2mtf":
		case "m2mtfro":
		case "m2mtfethalon":
		case "m2mtf_parent":
//			echo "[$field_txt: $input_type($name, $entity|$id, $default, $param1)]: ";
			$layer_open_html = layer_open($name, $field_txt);
			$wide_open_html = wide_open($name, $field_txt);
			$back_row = $input_type($name, array ($entity => $id), $default, $param1);
			$wide_close_html = wide_close($name, $field_txt);
			$layer_close_html = layer_close($name);

			$back_row = $layer_open_html
//				. $wide_open_html
				. $back_row
//				. $wide_close_html
				. $layer_close_html;
			break;

		case "m2mtfethalon_wide":
//			echo "[$field_txt: m2mtfethalon($name, $value, $default, $param1, $param2)]: ";
			$wide_open_html = wide_open($name, $field_txt);
			$back_row = m2mtfethalon($name, array ($entity => $id), $default, $param1, $param2);
			$wide_close_html = wide_close($name, $field_txt);
			$back_row = $wide_open_html . $back_row . $wide_close_html;
			break;

		case "m2mtf_opened":
//			echo "[$field_txt: m2mtf($name, $value, $default, $param1, $param2)]: ";
			$back_row = m2mtf($name, array ($entity => $id), $default, $param1, $param2);
			break;

		case "ic":
			if ($param1 == "") $param1 = "_global";
//			echo "[$field_txt: $input_type($default, $entity, $id, $param1, $param2)]: ";
			$layer_open_html = layer_open($name, $field_txt, $layer_opened_nr);
			$back_row = ic($default, $entity, $id, $param1, $param2);
			$layer_close_html = layer_close($name, $field_txt);
			$back_row = $layer_open_html . $back_row . $layer_close_html;
			break;

		case "client_icwhose":
		case "familyicwhose":
//			echo "[$field_txt: $input_type($default, $entity, $id)]: ";
//			$layer_open_html = layer_open($name, $field_txt, $layer_opened_nr);
			$back_row = $input_type($default, $entity, $id);
//			$layer_close_html = layer_close($name, $field_txt);
//			$back_row = $layer_open_html . $back_row . $layer_close_html;
			break;

		case "talist":
			$back_row = $input_type($default);

			$layer_open_html = layer_open($name, $field_txt);
			$layer_close_html = layer_close($name, $field_txt);
			$back_row = $layer_open_html . $back_row . $layer_close_html;
			break;

		case "layer_open":
			$layer_inside = 1;
			if ($param3 == "") {
				$entity_fields_names = array_keys($entity_fields);
				$idx = array_search ($name, $entity_fields_names);
//				pre("name=[" . $name . "]");
//				pre("entity_fields_names=[" . pr($entity_fields_names) . "]");
//				pre($idx);
				if ($idx !== FALSE && count($entity_fields_names) >= ($idx+1)) {
					$name_next = $entity_fields_names[$idx+1];
//					pre("name_next=[" . $name_next . "]");

// what the fuck? it does not find the string!
//					pre("strpos([pgrouptreeproductselectable], $name_next)=[" . strpos("pgrouptreeproductselectable", $name_next) . "]");
//					if (strpos("pgrouptreeproductselectable", $name_next) !== FALSE) {
					if ($name_next == "pgrouptreeproductselectable_1"
						|| $name_next == "pgrouptreeproductselectable_2"
						|| $name_next == "pgrouptreeproductselectable_3"
							) {
						$it_params_next = $entity_fields[$name_next];
//						pre("it_params_next=[" . pr($it_params_next) . "]");
						if (isset($it_params_next[2])) {
							$it_params_m2m_table = $it_params_next[2];
							$param3 = "layer_" . $it_params_m2m_table;
						}
					} else {
//						pre("not found [pgrouptreeproductselectable] in [$name_next]");
					}
				}
			}
			$back_row = $input_type($name, $field_txt, $default, $param1, $param2, $param3);
			break;

		case "layer_close":
			$layer_inside = 0;
			$back_row = $input_type($name, $field_txt, $default);
			break;

		case "columned_open":
			$columned_inside = 1;
			$back_row = $input_type($name, $field_txt, $default);
			break;

		case "columned_close":
			$columned_inside = 0;
			$back_row = $input_type($name, $field_txt, $default);
			break;

		case "wide_open":
			$back_row = $input_type($name, $field_txt, $default);
			break;

		case "wide_close":
			$back_row = $input_type($name, $field_txt, $default);
			break;

/*
		case "datetime_bdate":

			$input_type_wrapped = $input_type($name, $value);
			$back_row = back_row($input_type_wrapped, $field_txt, $name);
			break;

*/

		case "multicompositebidirect":
//			echo "[$field_txt: $input_type($name, $default, $param1, $param2, $param3, $param4, $param5, $param6, $param7)]: ";
			if ($param8 != "") {
				$input_type_wrapped = $input_type($name, $default, $param1, $param2, $param3, $param4, $param5, $param6, $param7, $param8);
			} else if ($param7 != "") {
				$input_type_wrapped = $input_type($name, $default, $param1, $param2, $param3, $param4, $param5, $param6, $param7);
			} else if ($param6 != "") {
				$input_type_wrapped = $input_type($name, $default, $param1, $param2, $param3, $param4, $param5, $param6);
			} else if ($param5 != "") {
				$input_type_wrapped = $input_type($name, $default, $param1, $param2, $param3, $param4, $param5);
			} else if ($param4 != "") {
				$input_type_wrapped = $input_type($name, $default, $param1, $param2, $param3, $param4);
			} else if ($param3 != "") {
				$input_type_wrapped = $input_type($name, $default, $param1, $param2, $param3);
			} else {
				$input_type_wrapped = $input_type($name, $default, $param1, $param2);
			}
			$back_row = back_row($input_type_wrapped, $field_txt, $name);
			break;

		case "multicompositecontent":
//			echo "[$field_txt: $input_type($name, $default, $param1, $param2, $param3, $param4, $param5, $param6, $param7)]: ";
			if ($param8 != "") {
				$input_type_wrapped = $input_type($name, $default, $param1, $param2, $param3, $param4, $param5, $param6, $param7, $param8);
			} else if ($param7 != "") {
				$input_type_wrapped = $input_type($name, $default, $param1, $param2, $param3, $param4, $param5, $param6, $param7);
			} else if ($param6 != "") {
				$input_type_wrapped = $input_type($name, $default, $param1, $param2, $param3, $param4, $param5, $param6);
			} else if ($param5 != "") {
				$input_type_wrapped = $input_type($name, $default, $param1, $param2, $param3, $param4, $param5);
			} else if ($param4 != "") {
				$input_type_wrapped = $input_type($name, $default, $param1, $param2, $param3, $param4);
			} else if ($param3 != "") {
				$input_type_wrapped = $input_type($name, $default, $param1, $param2, $param3);
			} else {
				$input_type_wrapped = $input_type($name, $default, $param1, $param2);
			}
			$back_row = back_row($input_type_wrapped, $field_txt, $name);
			break;

		case "multicompositeiccontent":
		case "multicompositeiccontentro":
//			echo "[$field_txt: $input_type($default, $param1, $param2, $param3, $param4, $param5, $param6)]: ";

			$icwhose_row = select_entity_row(array("hashkey" => $param3), "icwhose");
//			pre($icwhose_row);

			if (!isset($icwhose_row["id"])) {
				$back_row =  back_row("[$param3] $msg_bo_icwhose_not_defined <a href='icwhose-edit.php?hashkey=$param3' target=_blank>$msg_tag_shortcut $msg_bo_it_add</a>", "");
			} else {
				$icwhose_id = $icwhose_row["id"];
				$field_txt = $icwhose_row["ident"];
				$icwhose_tooltip = $icwhose_row["brief"];
				$icwhose_tooltip = htmlspecialchars_noamp($icwhose_tooltip, ENT_NOQUOTES, 1);
				$icwhose_tooltip = strip_tags($icwhose_row["brief"]);

				$read_only = ($input_type == "multicompositeiccontentro") ? 1 : 0;


				if ($default == "") $default = "m2m_{$entity}_iccontent";
				if ($param1 == "") $param1 = array();
				if ($param2 == "") $param2 = array($entity => "_global:id");
				if ($param3 == "") $param3 = 0;
				if ($param4 == "") $param4 = "form_edit";
				
//multicompositeiccontent($m2m_table, $fixed_hash, $absorbing_fixedhash, $icwhose_id, $read_only = 0, $ignore_jsv_finally = 0, $form_name = "form_edit")

				$back_row = $input_type($default, $param1, $param2, $icwhose_id, $read_only, $param3, $param4);
	
				$back_row = <<< EOT

<!-- здесь закончился layer_open -->
<!-- открыть полностью "нашу" новую строку в БО colspan=2 -->
<tr><td colspan=2>

<!-- отрыть новую таблицу для анкеты -->
<table cellpadding=3 cellspacing=2>

$back_row

<!-- закрыть новую таблицу для анкеты  -->
</table>

<!-- закрыть бэкоффисную строку -->
</td></tr>

<!-- отсюда продолжается layer_close -->

EOT;

//				if ($param5 == "") $param5 = $msg_bo_it_add . " " . $new_entity_ident_list["ic"];
//				if ($param5 != "") {

				$tpl_change_add = <<< EOT
<div align=right>
<!-- изменить поля анкеты -->
	<a href="ic.php?icwhose=$icwhose_id" target="_blank">$msg_tag_shortcut $msg_bo_it_change</a>
<!-- добавить новые поля анкеты -->
	<a href="ic-edit.php?icwhose=$icwhose_id" target="_blank">$msg_tag_shortcut $msg_bo_it_add</a>
</div>

EOT;


					$new_ic_addhref = array (
						"it_wrapped" => ahref("", "", $tpl_change_add),
						"it_txt" => "",
						"it_name" => "addnew_$default",
						"sheet_row_bgcolor" => OPTIONS_COLOR_WHITE,
						"it_graycomment" => "",
						"it_graycomment_gray" => "",
						"obligatory_sign" => "",
					);
//					pre($new_ic_addhref);
	
					$ic_addhef = hash_by_tpl($new_ic_addhref, $backrow_tpl);
					$back_row = $back_row . $ic_addhef;
//				}
	
	
				$ondblclick = <<< EOT
ondblclick="javascript:popup_blank('icwhose-edit.php?id=$icwhose_id')"
EOT;

				$layer_open_html = ($field_txt != "") ? layer_open($name, $field_txt, $layer_opened_nr, $icwhose_tooltip, $ondblclick) : "";
				$layer_close_html = ($field_txt != "") ? layer_close($name, $field_txt) : "";
				$back_row = $layer_open_html . $back_row . $layer_close_html;

			}

			break;

/*
		case "ahref":
//			echo "[$field_txt: $input_type($name, $default)]: ";
			$input_type_wrapped = $input_type($name, $default);
			$back_row = back_row($input_type_wrapped, $field_txt, $name);
			break;
*/

		case "ahref_nullout":
//			echo "[$field_txt: $input_type($name, $value, $default)]: ";
			$input_type_wrapped = ahref($name, $value, $default);
			break;

		case "checkbox":
//			echo "[$field_txt: $input_type($name, $value, $default, $param1, $param2)]: ";
			$param2 = hash_by_tpl($db_values_array, $param2);
			$input_type_wrapped = checkbox($name, $value, $default, $param1, $param2);
			$back_row = back_row($input_type_wrapped, $field_txt, $name);
			break;

		case "m2mcb":
//			echo "[$field_txt: $input_type($name, $value, $default, $param1, $param2)]: ";
			$input_type_wrapped = $input_type($name, $value, $default, $param1, $param2);
			$back_row = back_row($input_type_wrapped, $field_txt, $name);
			break;

		case "radio_table":
//			echo "[$field_txt: $input_type($name, $value, $default)]: ";
			if ($default == "") $default = "ident";
			$input_type_wrapped = $input_type($name, $value, $default);
			$back_row = back_row($input_type_wrapped, $field_txt, $name);
			break;

		case "cnt":
//			echo "[$field_txt: $input_type($name, $value, $default)]: ";
//			$ahref_wrapped = $input_type($name, $value, $default);

			if ($default != "") {
				$db_values_array["master_entity"] = $entity_list[$name];
				$ahref_relatedto = hash_by_tpl($db_values_array, $default);
			} else {
				$ident_tmp = (isset($db_values_array["ident"])) ? $db_values_array["ident"] : $ident_new;
				$ahref_relatedto = $entity_list[$name] . " $msg_bo_updated_for [$ident_tmp]";
			}
			$ahref_wrapped = "<a href='$name.php?$entity=$id'>$ahref_relatedto</a>";
//			pre($ahref_wrapped);

			$field_txt = masterdepend_cnt($name);
//			$field_txt = "[$field_txt]";
			$back_row = back_row($ahref_wrapped, $field_txt, "~" . $name);
			break;

		case "select_table_tree":
		case "select_table_tree_root":
		
//			pre($_REQUEST);
//			echo "get_string($name) = $value<br>";
//			echo "name=[$name], name_strict=[$name_strict]<br>";
//			echo "field_txt=[$field_txt], input_type=[$input_type], default=[$default], param1=[$param1], param2=[$param2], param3=[$param3], param4=[$param4], param5=[$param5]<br>";



			$tree_table = ($default != "") ? $default : $entity;
			$tree_value = ($value == "") ? $param1 : $value;

			$tree_value = intval($tree_value);
			if ($tree_value == 0 && $name == "parent_id") $tree_value == 1;

		 	$tree_fixed_field = ($param2 != "") ? $param2 : "parent_id";
			$tree_from_parent = ($param3 != "") ? $param3 : 1;
			$tree_from_parent = ($input_type != "select_table_tree_root") ? $tree_from_parent : 0;
			$tree_tag_attr = ($param4 != "") ? $param4 : "";
		
//			echo "tree_table=[$tree_table], tree_value=[$tree_value], tree_fixed_field=[$tree_fixed_field], tree_from_parent=[$tree_from_parent], tree_tag_attr=[$tree_tag_attr]<br>";
//			echo "[$field_txt: $input_type($tree_table, $tree_value, $tree_fixed_field, $tree_from_parent, $tree_tag_attr)]<br><br>";

			$select_fixed_suffix = "";
			$select_fixed_suffix_and = "";
			$select_fixed_hash = array();

			if (isset($entity_fixed_list[$name])) {
//				pre($entity_fixed_list[$name]);
				foreach ($entity_fixed_list[$name] as $dependant_entity) {
					$current = get_number($dependant_entity);
					if ($current != 0) $select_fixed_hash[$dependant_entity] = $current;
				}
				$select_fixed_suffix = hrefsuffix_fromhash($select_fixed_hash, "?");
				$select_fixed_suffix_and = hrefsuffix_fromhash($select_fixed_hash, "&");
			}

			$input_type_wrapped = $input_type($tree_table, $tree_value, $tree_fixed_field, $tree_from_parent, $tree_tag_attr);
		
			$input_type_wrapped .= (($columned_inside == 1) ? "<br>" : "&nbsp;&nbsp;&nbsp;&nbsp;");
			if ($id > 0) {
				if ($value > 0) $input_type_wrapped .= "<a href=$tree_table-edit.php?id=$value{$select_fixed_suffix_and} target='_blank'>$msg_tag_shortcut $msg_bo_it_change</a>&nbsp;&nbsp;";
				$input_type_wrapped .= "<a href=$tree_table-edit.php{$select_fixed_suffix} target='_blank'>$msg_tag_shortcut $msg_bo_it_add</a>";
			} else {
				$input_type_wrapped .= "<a href=$tree_table.php{$select_fixed_suffix} target='_blank'>$msg_tag_shortcut $msg_bo_it_tolist</a>";
			}

			$back_row = back_row($input_type_wrapped, $field_txt, $name);
			break;

		case "select_soft":
		case "select_hard":
////	"aboutuser" => array ("Обозреватель", "select_hard", $default="tag_attr", $param1="gray_text?"),
//// default will be tag attr. probably be graytext 
//	"fgroup" => array ("", "select_soft", 2, "select FAQ category", "ident", "onselect=javascript:alert('aaa')"),
//	$name		$field_txt, $input_type, $default, $param1, $param2, $param3
//			echo "[$field_txt: $input_type($name, $value, $param1, $param2, $param3)]: ";

			$select_fixed_suffix = "";
			$select_fixed_suffix_and = "";
			$select_fixed_hash = array();

			if (isset($entity_fixed_list[$name])) {
//				pre($entity_fixed_list[$name]);
				foreach ($entity_fixed_list[$name] as $dependant_entity) {
					$current = get_number($dependant_entity);
					if ($current != 0) $select_fixed_hash[$dependant_entity] = $current;
				}
				$select_fixed_suffix = hrefsuffix_fromhash($select_fixed_hash, "?");
				$select_fixed_suffix_and = hrefsuffix_fromhash($select_fixed_hash, "&");
			}
			
			if ($param2 == "") $param2 = "ident";
			$input_type_wrapped = select_table_all($name, $value, $param2, $param3, $select_fixed_hash, "", 1, $param1);
//function select_table_all($table, $value = "_global", $sql_field = "ident", $tag_attr = ""
//		, $fixed_hash = array(), $forcezero_option = "", $forcezero_evenifwasselected = 0) {
//		, $graycomment = "") {

//			$input_type_wrapped .= "&nbsp;&nbsp;&nbsp;&nbsp;";
			$input_type_wrapped .= (($columned_inside == 1) ? "<br>" : "&nbsp;&nbsp;&nbsp;&nbsp;");
			if ($id > 0) {
				if ($value > 0) $input_type_wrapped .= "<a href=$name_strict-edit.php?id=$value{$select_fixed_suffix_and} target='_blank'>$msg_tag_shortcut $msg_bo_it_change</a>&nbsp;&nbsp;";
				$input_type_wrapped .= "<a href=$name_strict-edit.php{$select_fixed_suffix} target='_blank'>$msg_tag_shortcut $msg_bo_it_add</a>";
			} else {
				$input_type_wrapped .= "<a href=$name_strict.php{$select_fixed_suffix} target='_blank'>$msg_tag_shortcut $msg_bo_it_tolist</a>";
			}
			$back_row = back_row($input_type_wrapped, $field_txt, $name);
			if ($input_type == "select_hard") jsv_addvalidation("JSV_SELECT_SELECTED", $name, $field_txt, 0);
			break;

		case "freetext":
		case "freetext_200":
		case "freetext_450":
		case "freetext_600":
		case "textfield":
		case "textarea":
		case "textarea_3":
		case "textarea_10":
		case "textarea_18":
		case "textarea_24":

//			if ($input_type == "textfield") 
//				echo "<br>[CALL DOGS $field_txt: $input_type($name, $value, $default, $param1)]: ";
//			echo "<br>calling $input_type(name[$name], value[$value], default[$default], graycomment=[$param1], tag_attr=[$param2])";
			$input_type_wrapped = $input_type($name, $value, $default, $param1, $param2);
//			if ($input_type == "textfield")
//				pre($input_type_wrapped);
//			if ($ctx_no_freetext_switchlink != "") $input_type_wrapped .= $ctx_no_freetext_switchlink;

			$backrow_tpl_backup = $backrow_tpl;		// will sustitute tpl-default with tpl-graycomment-below
			$backrow_tpl = $backrow_tpl_message_under_it_wrapped;

			$backrow_tpl = str_replace("#MESSAGE_UNDER_IT_TXT#", $ctx_no_freetext_switchlink, $backrow_tpl);

/*			if ($param1 != "") {		//graycomment adds line to table row; instead of align=top I add <br> to field mnemonics
//				pre($backrow_tpl);
				$backrow_tpl = str_replace("#IT_TXT#", "#IT_TXT#<br><br>", $backrow_tpl);
//				pre($backrow_tpl);
//				$field_txt .= "<br>";
			}
*/
			$back_row = back_row($input_type_wrapped, $field_txt, $name, 0, 0);	
			$backrow_tpl = $backrow_tpl_backup;

			break;

		case "table_ro":
//			echo "[$field_txt: $input_type($name, $value, $default)]: ";
			$input_type_wrapped = $input_type($name, $value);
			$back_row = back_row($input_type_wrapped, $field_txt, $name, 0);
			break;

		default:
//			echo "[$field_txt: $input_type($name, $value, $default, $param1, $param2, $param3)]: ";
			$param1 = hash_by_tpl($db_values_array, $param1);
			$input_type_wrapped = $input_type($name, $value, $default, $param1, $param2, $param3);
			$back_row = back_row($input_type_wrapped, $field_txt, $name);
			if ($input_type_wrapped == "") {
				$back_row = "<!-- empty back row [$back_row] commented for ahref being stub for functions -->";
			}
			break;
	}


	if ($id > 0) {
		if (is_first_field_in_swapdbfields($name)) {
			$swapdbfield_control = swapdbfield_control($name, $value);
			$back_row = <<< EOT
			
<!-- is_first_field_in_swapdbfields($name) -->
<tr>
	<td style="padding:10px; border-right: 1px solid #eeeeee" align="right">$swapdbfield_control</td>
	<td>
		<table cellspacing=0 cellpadding=3 border=0>
$back_row
<!-- /is_first_field_in_swapdbfields($name) -->

EOT;
		}
	
		if (is_last_field_in_swapdbfields($name)) {
			$back_row = <<< EOT
			
<!-- is_last_field_in_swapdbfields($name) -->
$back_row
		</table>
	</td>
</tr>
<!-- /is_last_field_in_swapdbfields($name) -->

EOT;
		}
	}


// unable to save #HTTP_HOST# in mtpl... wrap it please in graycomments
//	$back_row = hash_by_tpl ($_SERVER, $back_row);

	echo $back_row;
}

if ($id == 0) {
	$button_msg = $msg_bo_add;
	if (isset($new_entity_ident_list[$entity])) $button_msg .= " " . $add_entity_msg_list[$entity];
} else {
	$button_msg = $msg_bo_save;
	if (isset($entity_list_single_savebutton[$entity])) $button_msg .= " " . strtolower($entity_list_single_savebutton[$entity]);
}

?>


<?
if ($jsv_body != "" || $FTB_StartUpArray != "") {

	$submit_js = "document.form_edit.submit();";
//	$submit_js = "form_edit_element = document.getElementById('form_edit'); alert(form_edit_element); form_edit_element.submit();";

	if ($in_backoffice_readonly == 1) {
		$submit_js = "alert('$in_backoffice_readonly_msg')";
	}

// jsv rebuilt and moved to _top.php: jsv_flush_validation_functions_and_core()
/*	echo <<< EOT
<script>
function form_edit_submit() {
//	alert ("validations here");
	$jsv_body
	
//	layer_opened_nr = MM_findObj("layer_opened_nr")
//	if (layer_opened_nr != null) alert(layer_opened_nr.value)

	$submit_js
}
</script>

EOT;
*/

//FTB replaced to MCE, but FTB kept for extrairdinary cases
	if ($FTB_StartUpArray != "" && $FTB_version == "207") {
		echo <<< EOT
<!-- FTB 207 stuff, but is a flag whether form_edit_submit() is defined on the page //deprecated -->
  <script language="javascript">var FTB_StartUpArray =  new Array($FTB_StartUpArray)</script>
  <script language="JavaScript">FTB_StoredOnLoad = document.body.onload; document.body.onload = function() { FTB_InitializeAll(); if (FTB_StoredOnLoad != null) FTB_StoredOnLoad();}</script>
<!-- /FTB 207 stuff -->
EOT;
	}

// jsv rebuilt and moved to _top.php: jsv_flush_validation_functions_and_core()
//	if ($jsv_body != "") echo jsv_core();

	if ($savebutton_tag == "") {
		$savebutton_tag = "<input type=button onclick='form_edit_submit()' value='$button_msg'>";
	}
}

if ($savebutton_tag == "") {
	if ($in_backoffice_readonly == 1) {
		$savebutton_tag = "<input type=button onclick='javascript:alert(\"$in_backoffice_readonly_msg\")' value='$button_msg'>";
	} else {
		$savebutton_tag = "<input type=submit value='$button_msg'>";
	}
}


?>

<? if ($no_bottomline == 0) {?>
<tr><td colspan=2 align=center height=60>
	<? if ($no_savebutton == 0) { ?>
	<!--input type=submit value='<?=$button_msg?>'-->
	<?=$savebutton_tag?>
	<? } ?>
	
<? if ($no_backtolist == 0) { ?>
	<?=$backtolist_href?>
<? } ?>

	<br></td></tr>
<? } else { ?>
<?=$custom_bottomline?>
<? } ?>

</form>
</table>

<!-- END _edit_fields.php -->