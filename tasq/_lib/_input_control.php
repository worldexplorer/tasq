<?

$ic_validate = 1;

function ic($m2m_entity_iccontent_tablename, $entity_ = "_global", $entity_id_ = "_global", $icwhose_hashkey = "_global"
		, $item_tpl = "") {
	global $cms_dbc, $entity, $id, $ic_validate;
	global $backrow_bgcolor, $backrow_tpl, $obligatory_field;
	$ret = "";

	if ($icwhose_hashkey == "_global") $icwhose_hashkey = $entity;
	if ($entity_ == "_global") $entity_ = $entity;
	if ($entity_id_ == "_global") $entity_id_ = $id;
	
	$icwhose_hashkey_len = strlen($icwhose_hashkey);
/*
	$query = "select ic.id as ic_id, icwhat.id as icwhat_id, icwhat.ident as icwhat_ident, icwhat.published as icwhat_published, LOWER(ictype.hashkey) as ictype"
		. " from ic ic, icwhose icwhose, icwhat icwhat, ictype ictype"
		. " where LOWER(LEFT(icwhose.hashkey, $entity_len)) = '$entity_'"
		. " and ic.icwhose=icwhose.id and ic.icwhat=icwhat.id and ic.ictype=ictype.id"
//		. " and icwhat.published=1"
		. " order by icwhat.manorder";
*/
	$query = "select ic.id as ic_id, ic.ident as ic_ident, ic.published as ic_published, ic.icdict as icdict_id, LOWER(ictype.hashkey) as ictype_hashkey, ic.$obligatory_field as obligatory, jsv.hashkey as jsv_hashkey, ic.param1 as param1"
		. " from ic ic, icwhose icwhose, ictype ictype"
		. " left outer join jsvalidator jsv on ic.jsvalidator=jsv.id"
		. " where LOWER(LEFT(icwhose.hashkey, $icwhose_hashkey_len)) = '$icwhose_hashkey'"
		. " and ic.icwhose=icwhose.id and ic.ictype=ictype.id"
		. " and ic.published=1 and ictype.published=1"
		. " order by ic." . get_entity_orderfield("ic");

	$result = mysql_query($query, $cms_dbc)
		or die("SELECT IC failed:<br>$query:<br>" . mysql_error($cms_dbc));
	$size = mysql_num_rows($result);

	while ($row = mysql_fetch_assoc($result)) {
//		pre ($row);
		$ic_id = $row["ic_id"];
		$ic_ident = $row["ic_ident"];
		$ic_published = $row["ic_published"];
		$ictype_hashkey = $row["ictype_hashkey"];
		$icdict_id = $row["icdict_id"];
		$obligatory = $row["obligatory"];
		$jsv_hashkey = $row["jsv_hashkey"];
		$param1 = $row["param1"];
		
		$color = COLOR_ENABLED;
		if ($ic_published == 0) $color = COLOR_DISABLED;
		
		$it_name = "ic_" . $ic_id;

		$value = select_field("iccontent",
				array(
					"entity" => $entity_,
					"entity_id" => $entity_id_,
					"ic" => $ic_id,
					"deleted" => 0
					),
				$m2m_entity_iccontent_tablename);

		$value_array = select_fieldarray("iccontent",
					array(
						"entity" => $entity_,
						"entity_id" => $entity_id_,
						"ic" => $ic_id,
						"deleted" => 0
						),
					$m2m_entity_iccontent_tablename);


		$default = "";

		switch ($ictype_hashkey) {
			case "icselect":
				$value = (int) $value;
				$row["it_wrapped"] = icselect($icdict_id, $value, $default);
				break;
				
			case "icmultiselect":
				$row["it_wrapped"] = icmulti($icdict_id, $value_array, "ms", $it_name, 0, array(), $param1);
				break;

			case "icmulticheckbox":
				$row["it_wrapped"] = icmulti($icdict_id, $value_array, "cb", $it_name, 0, array(), $param1);
				break;

			default:
				$row["it_wrapped"] = $ictype_hashkey($it_name, $value, $default);
				break;
		}
		
		$row["obligatory_sign"] = jsv_addvalidation($jsv_hashkey, $it_name, $ic_ident);
		$row["it_name"] = $it_name;
		$row["it_txt"] = $ic_ident;
		$row["sheet_row_bgcolor"] = $backrow_bgcolor;

/*
		$ret .= <<< EOT
<tr>
	<td align="right"><label for="$it_name"><font color=red>$obligatory_sign</font> <font color="$color">$ic_ident</font></label></td>
	<td>$ictype_html</td>
</tr>
EOT;
*/

//		pre ($row);
		if ($item_tpl == "") $item_tpl = $backrow_tpl;
//		echo "item_tpl=[$item_tpl]";

		$ret .= hash_by_tpl($row, $item_tpl);
	}

	return $ret;
}


function textarea_scroll($ic_row) {
	global $entity;
	$ret = "";

//	pre($ic_row);
	
	$cols = ($ic_row["param1"] > 0) ? $ic_row["param1"] : 90;
	$rows = ($ic_row["param2"] > 0) ? $ic_row["param2"] : 10;
	$content = ($ic_row["param3"] != "") ? $ic_row["param3"]
		: "asdf"
//		: hash_by_tpl("—одержимое редактируетс€ тут http://#HTTP_HOST#/backoffice/ic-edit.php?id=#ID#", $row)
		;

	$ret = <<< EOT
<textarea rows="$rows" cols="$cols">
$content
</textarea>
EOT;

	return $ret;
}

function icselect($icdict, $value = "_global", $default = "", $it_name = "", $tag_attr = "") {
	global $entity;
	$ret = "";

	$it_name = "ic_$icdict";
//	if ($value == "_global") $value = get_number($it_name);

	$query = "select id, ident"
		. " from icdictcontent "
		. " where icdict=$icdict and published=1"
		. " order by manorder";

	$options = options_sql($query, $value);
	$ret = select($it_name, $options, $tag_attr);

/*
	$backoffice_specific = "";

	if ($in_backoffice == 1) {
		$backoffice_specific = <<< EOT
<tr>
	<td align=right>
	<a href="icdictcontent.php?icdict=$icdict" target=_blank>изменить</a>
	&nbsp;&nbsp;&nbsp;<a href="icdictcontent-edit.php?icdict=$icdict" target=_blank>добавить</a>
	</td>
</tr>
EOT;
	}


	$ret = <<< EOT
<table cellspacing=0 cellpadding=5 style="background-color-disabled: #2f2f2f; border:1px solid $gray">
<tr valign=middle>
	<td>
$ret
	</td>
</tr>
$backoffice_specific
</table>
EOT;

*/

	return $ret;
}

function tf1_input($row) {
	$ret = "";

	if ($row["tf1_width"] > 0) {
		$tpl = "<input type=text class=text size='#TF1_WIDTH#' name='#TF1_NAME#' value='#TF1_CONTENT#' #DISABLED#>";
		$ret = hash_by_tpl($row, $tpl);
	}
	
	return $ret;
}

function tf1_incolumn($row) {
	$ret = "";

	if ($row["tf1_incolumn"] == 1) $ret = "</td><td>";
	
	return $ret;
}


function icmulti($icdict, $value_array = array(), $ic_inputtype = "icmulticheckbox", $it_name = "_global", $read_only = 0,
		$it_iccontent_tf1_dbarray = array(), $icmulti_colcnt = 1) {
	global $cms_dbc, $entity, $in_backoffice;
	global $msg_bo_it_change, $msg_bo_it_add, $msg_bo_select_ctrl_shift, $msg_tag_shortcut;
	
	$ret = "";

	if ($it_name == "_global") $it_name = "ic_$icdict";

	$query = "select id, ident, tf1_width, tf1_incolumn, label_style from icdictcontent where icdict=$icdict and published=1 order by " . get_entity_orderby("icdictcontent");
	$query = add_sql_table_prefix($query);
	$result = mysql_query($query, $cms_dbc)
		or die("SELECT MULTI_VALUE failed:<br>$query:<br>" . mysql_error($cms_dbc));
	$size = mysql_num_rows($result);

	$tpl_icmultiselect_item_rw = "<option value='#ID#' #SELECTED# #LABEL_STYLE#>#IDENT#</option>";

	$tpl_icmulticheckbox_item_rw = <<< EOT
	<td><input type=checkbox name='{$it_name}[]' id='{$it_name}_{$icdict}_#ID#' value='#ID#' #CHECKED# #DISABLED#></td>
	<td><label for='{$it_name}_{$icdict}_#ID#' #LABEL_STYLE#>#IDENT#</label>@tf1_incolumn@@tf1_input@</td>
	
EOT;

	$tpl_icradio_item_rw = <<<EOT
<tr valign=middle>
	<td><input type=radio name='{$it_name}[]' id='{$it_name}_{$icdict}_#ID#' value='#ID#' #CHECKED# #DISABLED#></td>
	<td><label for='{$it_name}_{$icdict}_#ID#' #LABEL_STYLE#>#IDENT#</label>@tf1_incolumn@@tf1_input@</td>
</tr>

EOT;


	$tpl_icmultiselect_item_ro = "<option value='#ID#' #SELECTED# #LABEL_STYLE#>#IDENT#</option>";
	$tpl_icmulticheckbox_item_ro = "<tr valign=middle><td><input type=checkbox name='{$it_name}[]' id='{$it_name}_{$icdict}_#ID#' value='#ID#' #CHECKED# #DISABLED#></td><td><label for='{$it_name}_{$icdict}_#ID#' #LABEL_STYLE#>#IDENT#</label>@tf1_incolumn@</td></tr>";

	$tpl_icradio_item_ro = <<<EOT
<tr valign=middle>
	<td><input type=radio name='{$it_name}[]' id='{$it_name}_{$icdict}_#ID#' value='#ID#' #CHECKED# #DISABLED#></td>
	<td><label for='{$it_name}_{$icdict}_#ID#' #LABEL_STYLE#>#IDENT#</label>@tf1_incolumn@@tf1_input@</td>
</tr>

EOT;

	$disabled = "";
	if ($read_only == 1) $disabled = " disabled";
	$gray = OPTIONS_COLOR_GRAY;


	$tpl_icmultiselect_wraparound_rw = $tpl_icmultiselect_wraparound_ro = <<< EOT
<table cellspacing=0 cellpadding=0>
<tr valign=middle>
	<td><select multiple size=#SIZE# name='{$it_name}[]' #DISABLED#>#ITEMS#</select></td>
	<td>&nbsp;</td>
	<td><font color=$gray>$msg_bo_select_ctrl_shift</font></td>
</tr>
</table>
EOT;

	$tpl_icmulticheckbox_wraparound_rw = $tpl_icmulticheckbox_wraparound_ro = $tpl_icradio_wraparound_rw = $tpl_icradio_wraparound_ro = <<< EOT
<table cellspacing=0 cellpadding=5 style="background-color-disabled: #2f2f2f; border:1px solid $gray">
<tr valign=middle>
	<td>
		<table cellspacing=0 cellpadding=0>
		
#ITEMS#

		</table>
	</td>
</tr>
</table>
EOT;


	if ($in_backoffice == 1) {
		$tpl_icmultiselect_wraparound_rw =$tpl_icmultiselect_wraparound_ro = <<< EOT
<table cellspacing=0 cellpadding=0>
<tr valign=top><td>
	<table cellspacing=0 cellpadding=0>
	<tr valign=middle>
		<td><select multiple size=#SIZE# name='{$it_name}[]' #DISABLED#>#ITEMS#</select></td>
		<td>&nbsp;</td>
		<td><font color=$gray>$msg_bo_select_ctrl_shift</font></td>
	</tr>
	</table>
</td>
<td width=10></td>
<td>
	<div><a href="icdictcontent.php?icdict=$icdict" target="_blank">$msg_tag_shortcut $msg_bo_it_change</a></div>
	<div><a href="icdictcontent-edit.php?icdict=$icdict" target="_blank">$msg_tag_shortcut $msg_bo_it_add</a></div>
</td>
</tr>
</table>
EOT;

		$tpl_icmulticheckbox_wraparound_rw = $tpl_icmulticheckbox_wraparound_ro = $tpl_icradio_wraparound_rw = $tpl_icradio_wraparound_ro = <<< EOT
<table cellspacing=0 cellpadding=0>
<tr valign=top><td>
	<table cellspacing=0 cellpadding=5 style="background-color-disabled: #2f2f2f; border:1px solid $gray">
	<tr valign=middle>
		<td>
			<table cellspacing=0 cellpadding=0>
			
	#ITEMS#
	
			</table>
		</td>
	</tr>
	</table>
</td>
<td width=10></td>
<td>
	<div><a href="icdictcontent.php?icdict=$icdict" target="_blank">$msg_tag_shortcut $msg_bo_it_change</a></div>
	<div><a href="icdictcontent-edit.php?icdict=$icdict" target="_blank">$msg_tag_shortcut $msg_bo_it_add</a></div>
</td>
</tr>
</table>
EOT;

	}	
	
	$ic_inputtype = strtolower($ic_inputtype);
	switch ($ic_inputtype) {
		case "icmultiselect":
			$tpl_item = ($read_only == 1) ? $tpl_icmultiselect_item_ro : $tpl_icmultiselect_item_rw ;
			$tpl_wraparound = ($read_only == 1) ? $tpl_icmultiselect_wraparound_ro : $tpl_icmultiselect_wraparound_rw;
			break;

		case "icmulticheckbox":
			$tpl_item = ($read_only == 1) ? $tpl_icmulticheckbox_item_ro : $tpl_icmulticheckbox_item_rw;
			$tpl_wraparound = ($read_only == 1) ? $tpl_icmulticheckbox_wraparound_ro : $tpl_icmulticheckbox_wraparound_rw;
			break;

		case "icradio":
			$tpl_item = ($read_only == 1) ? $tpl_icradio_item_ro : $tpl_icradio_item_rw ;
			$tpl_wraparound = ($read_only == 1) ? $tpl_icradio_wraparound_ro : $tpl_icradio_wraparound_rw;
			break;

		default:
			$tpl_item = "ITEM_ERROR";
			$tpl_wraparound = "WRAPAROUND_ERROR";
			break;
	}

	if ($icmulti_colcnt == 0) $icmulti_colcnt = 1;
//	pre($icmulti_colcnt);

	$items = "";
	$items_array = array();
	while ($row = mysql_fetch_assoc($result)) {
//		pre($row);
		$id_ = $row["id"];
		$ident_ = $row["ident"];
		$tf1_width = $row["tf1_width"];
		$tf1_incolumn = $row["tf1_incolumn"];
		$label_style = $row["label_style"];
		$label_style = ($label_style != "") ? "style='$label_style'" : "";

		$item_idx = array_search($id_, $value_array);
		if ($item_idx !== FALSE && $read_only == 1) continue;
		$selected = ($item_idx !== FALSE) ? " selected" : "";
		$checked = ($item_idx !== FALSE) ? " checked" : "";

		$tf1_content = ($tf1_width > 0 && $item_idx !== FALSE && isset($it_iccontent_tf1_dbarray[$item_idx])) ? $it_iccontent_tf1_dbarray[$item_idx] : "";


		$items_hash = array("id" => $id_, "ident" => $ident_, "selected" => $selected, "checked" => $checked
				, "tf1_id" => "{$it_name}_{$icdict}_{$id_}_tf1"
				, "tf1_name" => "{$it_name}_{$id_}_tf1"
				, "tf1_content" => $tf1_content
				, "tf1_width" => $tf1_width, "tf1_incolumn" => $tf1_incolumn
				, "label_style" => $label_style
			);
//		pre($items_hash);
//		$items .= hash_by_tpl($items_hash, $tpl_item);
		$items_array[] = hash_by_tpl($items_hash, $tpl_item);
	}

/*
	$items = make_table_from_array($items_array, $icmulti_colcnt
	, "\n\n~~tpl_td_begin~~\n"
	, "\n\n~~tpl_td_end~~"
	, "\n\n~~tpl_td_separator~~"
	, "\n\n~~tpl_tr_begin~~"
	, "\n\n~~tpl_tr_end~~"
	, "\n\n~~tpl_tr_separator~~"
	, "\n\n~~tpl_table_begin~~"
	, "\n\n~~tpl_table_end~~"
	);
*/

	$items = make_table_from_array($items_array, $icmulti_colcnt
	, ""
	, ""
	, "<td width=10></td>\n"
	, "<tr>\n"
	, "</tr>\n"
	, ""
	, ""
	, ""
	);

	$ret = hash_by_tpl(array("items" => $items, "size" => $size, "disabled" => $disabled), $tpl_wraparound);


	return $ret;

}



function make_table_from_array($items_array = array(), $colcnt = 1
	, $tpl_td_begin = "<td>"
	, $tpl_td_end = "</td>"
	, $tpl_td_separator = "<td width=10><td>"
	, $tpl_tr_begin = "<tr valign=top>"
	, $tpl_tr_end = "</tr>"
	, $tpl_tr_separator = ""
	, $tpl_table_begin = "<table cellspacing=0 cellpadding=0 border=1>"
	, $tpl_table_end = "</table>"
		) {
	$ret = "";
	
	$items_by_column = array();
//	$largestcol_itemcnt = ceil(count($items_array) / $colcnt);		// oregons
	$largestcol_itemcnt = round(count($items_array) / $colcnt);		// sarges seems to be recent
//	pre("largestcol_itemcnt=$largestcol_itemcnt, colcnt=$colcnt, count(items_array)=" . count($items_array));
	
	for ($i=0; $i<$largestcol_itemcnt; $i++) {
		if (!isset($items_by_column[$i])) $items_by_column[$i] = "";

		for ($j=0; $j<$colcnt; $j++) {
//			$index_in_array = $i + $largestcol_itemcnt * $j;		// oregons
			$index_in_array = ($i * $colcnt) + $j;					// sarges seems to be recent

//			pre("i=$i j=$j items_array[$index_in_array] = " . $items_array[$index_in_array]);
			if (isset($items_array[$index_in_array])) {
				$items_by_column[$i] .= $tpl_td_begin . $items_array[$index_in_array] . $tpl_td_end . $tpl_td_separator;
			}
		}
	}
//	pre($items_by_column);

	foreach($items_by_column as $items_in_column) {
		$ret .= $tpl_tr_begin . $items_in_column . $tpl_tr_end;
	}
	
	$ret = $tpl_table_begin . $ret . $tpl_table_end;

//	pre("\n\n\n\n\n\n\n $ret \n\n\n\n\n\n");

	return $ret;
}

function ic_update($m2m_entity_iccontent_tablename, $entity_ = "_global", $entity_id_ = "_global") {
	global $entity, $id, $debug_query, $cms_dbc;
	$ret = "";

	if ($entity_ == "_global") $entity_ = $entity;
	if ($entity_id_ == "_global") $entity_id_ = $id;
	
	$entity_len = strlen($entity_);

	$query = "select ic.id as ic_id, ic.ident as ic_ident, ic.published as ic_published, ic.icdict as icdict_id, LOWER(ictype.hashkey) as ictype_hashkey"
		. " from ic ic, icwhose icwhose, ictype ictype"
		. " where LOWER(LEFT(icwhose.hashkey, $entity_len)) = '$entity_'"
		. " and ic.icwhose=icwhose.id and ic.ictype=ictype.id"
		. " and ictype.published=1 and ic.published=1"
		. " order by ic.manorder";

	$result = mysql_query($query, $cms_dbc)
		or die("SELECT IC failed:<br>$query:<br>" . mysql_error($cms_dbc));
	$size = mysql_num_rows($result);

	while ($row = mysql_fetch_assoc($result)) {
		$ic_id = $row["ic_id"];
		$ic_ident = $row["ic_ident"];
		$ic_published = $row["ic_published"];
		$ictype_hashkey = $row["ictype_hashkey"];
		$icdict_id = $row["icdict_id"];
//		if ($row["ic_published"] == 0) continue;

		$it_name = "ic_$ic_id";

		$form_value_array = array();
		$form_value = "";

		if (isset($_REQUEST[$it_name])) {
			$raw_value_array = $_REQUEST[$it_name];
			if (is_array($raw_value_array)) {
				foreach($raw_value_array as $serno => $form_value) $form_value_array[] = $form_value;
//the same shit...
//pre ($raw_value_array);
//pre ($form_value_array);
			} else {
				$form_value = $raw_value_array;
			}
		}
		
		switch ($ictype_hashkey) {
			case "icmultiselect":
			case "icmulticheckbox":

				$m2m_value_array = select_fieldarray("iccontent",
							array(
								"entity" => $entity_,
								"entity_id" => $entity_id_,
								"ic" => $ic_id,
								"deleted" => 0
								),
							$m2m_entity_iccontent_tablename);
				
				$icdict_array = select_fieldarray("id",
							array(
								"published" => 1,
								"icdict" => $icdict_id
								),
							"icdictcontent");
				
//				pre ($m2m_value_array);
//				pre ($icdict_array);
				
				$insert_array = array();
				$delete_array = array();
				
				foreach($icdict_array as $dict_value) {
					if (!in_array($dict_value, $m2m_value_array)
						 && in_array($dict_value, $form_value_array)) {
							$insert_array[] = $dict_value;
					}
					
					if (in_array($dict_value, $m2m_value_array)
						&& !in_array($dict_value, $form_value_array)) {
							$delete_array[] = $dict_value;
					}
				}

				foreach($insert_array as $value) {
					$insert_hash = array (
						"entity" => $entity_,
						"entity_id" => $entity_id_,
						"ic" => $ic_id,
						"iccontent" => $value,
						"date_created" => "CURRENT_TIMESTAMP");
					insert ($insert_hash, $m2m_entity_iccontent_tablename);
//					echo "<pre>"; print_r ($insert_hash); echo "</pre>";
				}

				foreach($delete_array as $value) {
					$delete_hash = array(
						"entity" => $entity_,
						"entity_id" => $entity_id_,
						"ic" => $ic_id,
						"iccontent" => $value);
					delete ($delete_hash, $m2m_entity_iccontent_tablename);
//					update (array("deleted" => 1), $delete_hash, $m2m_entity_iccontent_tablename);
//					echo "<pre>"; print_r ($delete_hash); echo "</pre>";
				}
/*
				echo "<pre>";
				echo "[$it_name][$ictype_hashkey][$ic_ident][$icdict_id]\n";

				echo "form_value_array[";
				print_r ($form_value_array);
				echo "]";

				echo "m2m_value_array[";
				print_r ($m2m_value_array);
				echo "]";

				echo "icdict_array[";
				print_r ($icdict_array);
				echo "]";

				echo "insert_array[";
				print_r ($insert_array);
				echo "]";

				echo "delete_array[";
				print_r ($delete_array);
				echo "]";

				echo "</pre>";
*/
				break;

				
			case "icselect":
			case "icradio":
				$select_hash = array (
					"entity" => $entity_,
					"entity_id" => $entity_id_,
					"ic" => $ic_id);

				$insert_hash = array (
					"entity" => $entity_,
					"entity_id" => $entity_id_,
					"ic" => $ic_id,
					"iccontent" => $form_value,
					"date_created" => "CURRENT_TIMESTAMP");
					
//				$debug_query = 1;
				if (select_field("id", $select_hash, $m2m_entity_iccontent_tablename) != "") {
					update (array("iccontent" => $form_value),
							$select_hash, $m2m_entity_iccontent_tablename);
				} else {
					pre($insert_hash);
					insert ($insert_hash, $m2m_entity_iccontent_tablename);
				}
//				$debug_query = 0;

				break;

			default:
				break;
		}
	}
}

// works throught icmulti, not needed
function icradio($icdict, $it_value, $it_name = "_global", $read_only = 0) {
	global $cms_dbc, $entity, $in_backoffice;
	global $msg_bo_it_change, $msg_bo_it_add;

	$ret = "";

	$query = "select id, ident, tf1_width, tf1_incolumn, label_style from icdictcontent where icdict=$icdict and published=1 order by manorder";
	$query = add_sql_table_prefix($query);
	$result = mysql_query($query, $cms_dbc)
		or die("SELECT MULTI_VALUE failed:<br>$query:<br>" . mysql_error($cms_dbc));
	$size = mysql_num_rows($result);

	$tpl_item_rw = <<<EOT
<tr valign=middle>
	<td><input type=radio name='{$it_name}[]' id='{$it_name}_{$icdict}_#ID#' value='#ID#' #CHECKED# #DISABLED#></td>
	<td><label for='{$it_name}_{$icdict}_#ID#' #LABEL_STYLE#>#IDENT#</label></td>
</tr>

EOT;

	$tpl_item_ro = <<<EOT
<tr valign=middle>
	<td><input type=radio name='{$it_name}[]' id='{$it_name}_{$icdict}_#ID#' value='#ID#' #CHECKED# #DISABLED#></td>
	<td><label for='{$it_name}_{$icdict}_#ID#' #LABEL_STYLE#>#IDENT#</label></td>
</tr>

EOT;

	$disabled = "";
	if ($read_only == 1) $disabled = " disabled";
	$gray = OPTIONS_COLOR_GRAY;


	$backoffice_specific = "";
/*
	if ($in_backoffice == 1) {
		$backoffice_specific = <<< EOT
<div style="float:left"><a href="icdictcontent.php?icdict=$icdict" target="_blank">$msg_bo_it_change</a></div><br>
<div style="float:left"><a href="icdictcontent-edit.php?icdict=$icdict" target="_blank">$msg_bo_it_add</a></div>
EOT;

		$backoffice_specific = <<< EOT
<tr>
	<td align=right>
	<a href="icdictcontent.php?icdict=$icdict" target="_blank">$msg_bo_it_change</a>
	&nbsp;&nbsp;&nbsp;<a href="icdictcontent-edit.php?icdict=$icdict" target="_blank">$msg_bo_it_add</a>
	</td>
</tr>
EOT;
	}
*/

	$tpl_wraparound = <<< EOT
<table cellspacing=0 cellpadding=5 style="background-color-disabled: #2f2f2f; border:1px solid $gray">
<tr valign=middle>
	<td>
		<table cellspacing=0 cellpadding=0>
		#ITEMS#
		</table>
	</td>
</tr>
$backoffice_specific
</table>
EOT;

	if ($in_backoffice == 1) {
		$tpl_wraparound = <<< EOT
<table cellspacing=0 cellpadding=0>
<tr valign=top><td>
	<table cellspacing=0 cellpadding=5 style="background-color-disabled: #2f2f2f; border:1px solid $gray">
	<tr valign=middle>
		<td>
			<table cellspacing=0 cellpadding=0>
			#ITEMS#
			</table>
		</td>
	</tr>
	</table>
</td>
<td width=10></td>
<td>
	<div><a href="icdictcontent.php?icdict=$icdict" target="_blank">$msg_bo_it_change</a></div>
	<div><a href="icdictcontent-edit.php?icdict=$icdict" target="_blank">$msg_bo_it_add</a></div>
</td>
</tr>
</table>
EOT;
	}

	$tpl_item = ($read_only == 1) ? $tpl_item_rw : $tpl_item_ro;


	$items = "";
	while ($row = mysql_fetch_assoc($result)) {
//		pre($row);
		
		$id_ = $row["id"];
		$ident_ = $row["ident"];
		$tf1_width = $row["tf1_width"];
		$tf1_incolumn = $row["tf1_incolumn"];
		$label_style = $row["label_style"];
		$label_style = ($label_style != "") ? "style='$label_style'" : "";

		$checked = ($id_ == $it_value) ? " checked" : "";

		$tf1_content = ($tf1_width > 0 && $item_idx !== FALSE) ? $it_iccontent_tf1_dbarray[$item_idx] : "";


		$items_hash = array("id" => $id_, "ident" => $ident_, "checked" => $checked
				, "tf1_name" => "{$it_name}_{$id_}_tf1", "tf1_content" => $tf1_content
				, "tf1_width" => $tf1_width, "tf1_incolumn" => $tf1_incolumn
				, "label_style" => $label_style
			);
//		pre($items_hash);
		$items .= hash_by_tpl($items_hash, $tpl_item);
	}
		
	$ret = hash_by_tpl(array("items" => $items, "size" => $size, "disabled" => $disabled), $tpl_wraparound);

	return $ret;
}

?>