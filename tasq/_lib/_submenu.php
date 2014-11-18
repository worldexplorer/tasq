<!-- BEGIN _submenu.php -->
<?


switch ($entity) {
/*
	case "shop":
		$query_submenu = "select cs.id, concat(c.ident, ' | ', cs.ident) as ident"
			. " from chainstore cs, city c"
			. " where cs.city=c.id"
			. " and cs.deleted=0 and c.deleted=0"
			. " order by c." . get_entity_orderby("city") . ", cs." . get_entity_orderby("chainstore")
			;

		$dependant_entity = $entity_fixed_list[$entity][0];
		$txt = $entity_list[$dependant_entity];
		$tpl = "<span style='#spanstyle#'>$txt:</span> #select_tag#<br>";
		submenu_by_query($query_submenu, "chainstore", $tpl);
		
		break;

	case "product":
//		if (in_array($entity, $no_submenu_list)) break;
//		if ($spanstyle == "") $spanstyle = "width:10em;";
//		submenu_for_entity($entity);

		switch (basename($_SERVER["SCRIPT_NAME"])) {
			case "product-client.php":
				$spanstyle = "width:6em;";
				
				if (strpos($_SERVER["SCRIPT_NAME"], "manager") === false) {
					submenu_for_entity($entity, "product-client.php");
				} else {

$dependant_entity = "client";
$fixed_deeper_local["manager"] = $manager;

$query = "select id, ident, published"
	. " from " . $dependant_entity
	. " where deleted=0 " . sqlcond_fromhash($fixed_deeper_local, "", " and ", "", TABLE_PREFIX)
	. " order by " . get_entity_orderby($dependant_entity);

$forcezero_option = " - $msg_submenu_all " . strtolower ($entity_list[$dependant_entity]) . " - ";
$options = options_sql($query, get_number("client"), $forcezero_option, 1);


$txt = $entity_list[$dependant_entity];
$tpl = "<span style='#spanstyle#'>$txt:</span> #select_tag#<br>";
$href_basepage = "product-client.php";
$submenu_hash = submenu_by_options($options, $dependant_entity, $tpl, $href_basepage);
$_submenu_hash[$entity] = $submenu_hash;
rawsubmenu_fromhash($submenu_hash);

				}
				
				
				if (isset($entity_fixed_list_backup)) $entity_fixed_list = $entity_fixed_list_backup;
				break;
				
			case "product-dealer.php":
				$spanstyle = "width:6em;";
				submenu_for_entity($entity, "product-dealer.php");
				break;
				
			default:
				if (in_array($entity, $no_submenu_list)) break;
				$spanstyle = "width:6em;";
				submenu_for_entity($entity);
		}

		break;

	case "cdialog":
		if (strpos($_SERVER["SCRIPT_NAME"], "manager") === false) {
			submenu_for_entity($entity);
		} else {

$dependant_entity = "client";
$fixed_deeper_local["manager"] = $manager;

$query = "select id, ident, published"
	. " from " . $dependant_entity
	. " where deleted=0 " . sqlcond_fromhash($fixed_deeper_local, "", " and ", "", TABLE_PREFIX)
	. " order by " . get_entity_orderby($dependant_entity);

$forcezero_option = " - $msg_submenu_all " . strtolower ($entity_list[$dependant_entity]) . " - ";
$options = options_sql($query, get_number("client"), $forcezero_option, 1);


$txt = $entity_list[$dependant_entity];
$tpl = "<span style='#spanstyle#'>$txt:</span> #select_tag#<br>";
$href_basepage = "product-client.php";
$submenu_hash = submenu_by_options($options, $dependant_entity, $tpl, $href_basepage);
$_submenu_hash[$entity] = $submenu_hash;
rawsubmenu_fromhash($submenu_hash);

		}

		break;
*/



	case "img":
		if ($spanstyle == "") $spanstyle = "width:7em;";

		$query_entity = "select owner_entity as id, owner_entity as ident from img where deleted=0 group by owner_entity";
		submenu_by_query ($query_entity, "owner_entity"
//			, "<span style='#spanstyle#'>Родитель:</span> #select_tag#", " - все изображения - ", 1
			, "<tr><td align=right style='padding-right:1ex'>Родитель:</td><td> #select_tag#</td></tr>", " - все изображения - ", 1
			, "_global");

/*		$options = options_sql($query_entity, $dst_entity, " - все - ", 1);
		$submenu_hash = submenu_by_options($options, "dst_entity");
		$_submenu_hash["dst_entity"] = $submenu_hash;
		rawsubmenu_fromhash($submenu_hash);
*/

		if (isset($owner_entity) && $owner_entity != "" && $owner_entity != "0") {
			$query_entity_id = "select owner.id, owner.ident, owner.published"
				. " from img i"
				. " inner join $owner_entity owner on i.owner_entity='$owner_entity' and i.owner_entity_id=owner.id"
				. " where i.deleted=0 and owner.deleted=0"
				. " group by owner.id"
				. " order by owner." . get_entity_orderby($owner_entity)
				;
			submenu_by_query ($query_entity_id, "owner_entity_id"
//				, "<br><span style='#spanstyle#'>Элемент:</span> #select_tag#", " - все изображения - ", 1
				, "<tr><td align=right style='padding-right:1ex'>Элемент:</td><td> #select_tag#</td></tr>", " - все изображения - ", 1
				, "_global", array("owner_entity" => $owner_entity));
		}
		break;

	case "stats":
		$select_from = select_date("from", $from_datehash);
		$select_till = select_date("till", $till_datehash);

		$_submenu = <<< EOT
<table>
<form>
<input type=hidden name=mode value=update>
<tr><td>Дата старта заданий: С</td><td>$select_from</td></tr>
<tr><td>Дата старта заданий: ПО</td><td>$select_till</td></tr>
<tr><td><label for="datefork">Применить это условие</label></td><td>$cb_datefork</td></tr>
<tr><td></td><td align=center><input type=submit value="Сформировать отчёт"></td></tr>
</form>
</table>
EOT;
		break;

	default:
//		pre($entity);
//		pre($no_submenu_list);
//		echo "in_array($entity, $no_submenu_list) = " . in_array($entity, $no_submenu_list);

		if (!in_array($entity, $no_submenu_list)) submenu_for_entity($entity);
		break;

}

if ($no_search == 0) {
	$_submenu_searchpage = hash_by_tpl(array("entity" => $entity), $_submenu_searchpage);

	$_submenu .= <<< EOT
<!--table cellpadding=0 cellspacing=0-->

<form action="$_submenu_searchpage" name="form_submenu" id="form_submenu" >
<!--input type=hidden name="pg" value=0-->
$fixed_hiddens

<tr>
	<!--td><span style="$spanstyle" title="">Поиск:</span> <input type=text size=17 name=q value="$q"></td-->
	<!--td width=5></td-->
	<td align=right style="padding-right:1ex">$msg_submenu_search</td>
	<td><input type=text size=17 name=q value="$q">
	<input type=submit value="$msg_submenu_find"></td>
</tr>
</form>
<!--/table-->
EOT;
}


$_submenu = <<< EOT
<table cellpadding=0 cellspacing=0>
$_submenu
</table>
EOT;


function submenu_by_options($options, $field_current
		, $tpl = "#select_tag#", $href_basepage = "_global", $href_fixedhash = array(), $select_attr = "") {

	global $entity, $spanstyle, $entity_list;

	$ret = array(
		"submenu_form" => "",
		"submenu_select" => ""
		);

	if ($href_basepage == "_global") $href_basepage = "$entity.php";
	$href_suffix = hrefsuffix_fromhash($href_fixedhash);
	$hidden_fixed = hidden_fromhash($href_fixedhash);
	
	$ret["submenu_forms"] = <<<EOT
<form name="submenu_{$field_current}" id="submenu_{$field_current}" action="$href_basepage">
$hidden_fixed
<input type=hidden name="{$field_current}" value=0>
<input type=hidden name="pg" value=0>
</form>
<script>
function other_{$field_current}(id) {
//	submenu_form = document.all("submenu_{$field_current}");
	submenu_form = MM_findObj("submenu_{$field_current}");
	submenu_form.{$field_current}.value = id
//	alert(submenu_form.{$field_current}.value)
	submenu_form.submit()
}
</script>
EOT;

	$select_hash["spanstyle"] = $spanstyle;
	$select_hash["select_tag"] = "<select onChange='other_{$field_current}(this.value)' $select_attr>$options</select>";

	if ($tpl == "_default") $tpl = "<span style='#spanstyle#'>" . $entity_list[$field_current] . ":</span> #select_tag#<br>";
	$ret["submenu_select"] = hash_by_tpl($select_hash, $tpl);

	return $ret;
}


function submenu_for_entity($entity, $href_basepage = "_global", $href_fixedhash = array()) {
	global $entity_list, $entity_fixed_list;
	global $_submenu, $_submenu_forms, $_submenu_hash, $spanstyle, $spanstyle_ex_multiplier, $_submenu_rowlimit;
	global $fixed_getfirstfromdb_array, $fixed_hash;
	global $msg_submenu_all, $msg_submenu_shown, $msg_submenu_shown_from;
	global $msg_bo_edit;

	if (!isset($entity_fixed_list[$entity])) return;
	
	$fixed_getfirstfromdb = in_array($entity, $fixed_getfirstfromdb_array) ? 1 : 0;

	$dependant_label_maxlength = 0;
	foreach ($entity_fixed_list[$entity] as $dependant_entity) {
		if ($dependant_entity == "parent_id") continue;

// m2m_person_friend.php has person_from and person_to, $dependant_entity = "person_from"
		$noneed_to_makestrict = entity_has_field($entity, $dependant_entity);
//		pre("_submenu: entity_has_field($entity, $dependant_entity) = $noneed_to_makestrict");
		if ($noneed_to_makestrict == 0) {
			$dependant_entity = makestrict($dependant_entity);
//			pre("_submenu: madestrict[$dependant_entity]");
		}
		$dependant_entity_strict = makestrict($dependant_entity);


//		pre("dependant_entity=[$dependant_entity]; dependant_entity_strict=[$dependant_entity_strict]; entity_list[$dependant_entity_strict]=[" . $entity_list[$dependant_entity_strict] . "]");
		$dependant_label_tmp = $entity_list[$dependant_entity_strict];
		$dependant_label_tmp_length = strlen($dependant_label_tmp);
		if ($dependant_label_maxlength < $dependant_label_tmp_length) $dependant_label_maxlength = $dependant_label_tmp_length;
	}
//	pre($dependant_label_maxlength);
	if ($spanstyle == "") $spanstyle = "width: " . intval($dependant_label_maxlength * $spanstyle_ex_multiplier) . "ex";

	$fixed_deeper = array();
	foreach ($entity_fixed_list[$entity] as $dependant_entity) {
//		pre($dependant_entity);

		$dependant_is_tree = 0;
		$has_parent_fixed = false;
		$is_group = false;
		
		if (isset($entity_fixed_list[$dependant_entity])) {
			$fixed_for_dependant = $entity_fixed_list[$dependant_entity];
//			pre($fixed_for_dependant);
			$has_parent_fixed = in_array("parent_id", $entity_fixed_list[$dependant_entity]);
		}

		$pos = strpos($dependant_entity, "group");
		if ($pos !== false) $is_group = true;

		if ($is_group == true || $has_parent_fixed == true) $dependant_is_tree = 1;


		if ($dependant_entity == "parent_id") {
			continue;
		} else {
			$dependant_entity = makestrict($dependant_entity);
		}
		
		$txt = $entity_list[$dependant_entity];

//		$current = isset($fixed_hash[$dependant_entity]) ? $fixed_hash[$dependant_entity] : 0;
		$current = get_number($dependant_entity);
//		if ($current == 0) {
//			$current = isset($GLOBALS[$dependant_entity])
//					? $GLOBALS[$dependant_entity] : get_number($dependant_entity);
//		}
		
		
		if ($dependant_is_tree == 0) {
			$fixed_deeper_local = array();
			foreach ($fixed_deeper as $deeper_key => $deeper_value) {
//				pre($fixed_deeper);
				if (entity_has_field($dependant_entity, $deeper_key) == 1) {
//				pre("entity_has_field($dependant_entity, $deeper_key)");
					$fixed_deeper_local[$deeper_key] = $deeper_value;
				}
			}
//			pre($fixed_deeper_local);

/*
			$fixed_deeper_effective = array();
			foreach ($fixed_deeper as $potential) {
				pre($potential);
				pre($dependant_entity);
				pre($entity_fixed_list[$dependant_entity]);
				if (isset($entity_fixed_list[$dependant_entity]) && in_array($potential, $entity_fixed_list[$dependant_entity])) $fixed_deeper_effective[] = $potential;
			}
*/

			$query = "select id, ident, published"
				. " from $dependant_entity"
				. " where deleted=0 " . sqlcond_fromhash($fixed_deeper_local, "", " and ", "")
				. " order by " . get_entity_orderby($dependant_entity)
				. " limit $_submenu_rowlimit"
				;
			$query = add_sql_table_prefix($query);
//			$options = options_sql($query, $current);
			
			
			$forcezero_option = ($fixed_getfirstfromdb == 0) ? " - $msg_submenu_all " . strtolower ($entity_list[$dependant_entity]) . " - " : "";
			$fixed_getfirstfromdb_dependant = in_array($dependant_entity, $fixed_getfirstfromdb_array) ? 1 : 0;
			$forcezero_evenifwasselected = ($fixed_getfirstfromdb_dependant == 0) ? 1 : 0;

			$options = options_sql($query, $current, $forcezero_option, $forcezero_evenifwasselected);


			$query_dependant_cnt = "select count(id) as total_cnt"
				. " from $dependant_entity"
				. " where deleted=0 " . sqlcond_fromhash($fixed_deeper_local, "", " and ", "")
				;
			$query_dependant_cnt = add_sql_table_prefix($query_dependant_cnt);
			$list_qa_cnt = select_queryarray($query_dependant_cnt);
//			pre($list_qa_cnt);
			$dependant_cnt = isset($list_qa_cnt[0]) ? $list_qa_cnt[0]["total_cnt"] : "??";


			if ($dependant_cnt > 0 && $dependant_cnt > $_submenu_rowlimit) {
				$options .= "<option style='color:gray; textalign:right'>$msg_submenu_shown $_submenu_rowlimit $msg_submenu_shown_from $dependant_cnt</option>";
			}
			
		} else {
//			echo "The string '$findme' was found in the string '$mystring' and exists at position $pos";
			$options = options_sql_tree($dependant_entity, $current, "ident", 1
				, 1, 0, " - $msg_submenu_all " . strtolower ($entity_list[$dependant_entity]) . " - ", 1
			);
		}

//		$tpl = "<span style='#spanstyle#'>$txt:</span> #select_tag#<br>";
		$tpl = "<tr><td align=right style='padding-right:1ex'> <a href='$dependant_entity.php'>$txt</a></td><td> #select_tag#</td></tr>";
		$submenu_hash = submenu_by_options($options, $dependant_entity, $tpl, $href_basepage, array_merge($href_fixedhash, $fixed_deeper));
		$_submenu_hash[$entity] = $submenu_hash;
		rawsubmenu_fromhash($submenu_hash);

		$fixed_deeper[$dependant_entity] = $current;
	}
}

function submenu_by_query ($query, $field_current
		, $tpl = "#select_tag#", $forcezero_option = "SELECTOR_EVERY", $forcezero_evenifwasselected = 1
		, $href_basepage = "_global", $href_fixedhash = array(), $select_attr = "") {

	global $entity;
	global $_submenu, $_submenu_forms, $_submenu_hash;
	global $msg_bo_selector_every;
	if ($forcezero_option == "SELECTOR_EVERY") $forcezero_option = $msg_bo_selector_every;
	
//	$current = get_string($field_current);
	$current = isset($GLOBALS[$field_current])
				? $GLOBALS[$field_current] : get_string($field_current);

	$query = add_sql_table_prefix($query);
	$options = options_sql($query, $current, $forcezero_option, $forcezero_evenifwasselected);
	
//	$tpl = "<span style='#spanstyle#'>$txt:</span> #select_tag#<br>";
	$submenu_hash = submenu_by_options($options, $field_current, $tpl, $href_basepage, $href_fixedhash, $select_attr);
	$_submenu_hash[$field_current] = $submenu_hash;
	rawsubmenu_fromhash($submenu_hash);

	return $submenu_hash["submenu_select"];
}

function rawsubmenu_fromhash($submenu_hash = array("submenu_form" => "", "submenu_select" => "")) {
	global $_submenu, $_submenu_forms;
	$_submenu .= $submenu_hash["submenu_select"];
	$_submenu_forms .= $submenu_hash["submenu_forms"];
}

/*
	$ret["submenu_forms"] = <<<EOT
<form name="submenu_{$field_current}" id="submenu_{$field_current}" action="$href_basepage">
$hidden_fixed
<input type=hidden name="{$field_current}" value=0>
</form>
<script>
function other_{$field_current}(id) {
//	href_suffix = "$href_suffix";
//	if (href_suffix != "") href_suffix = "&" + href_suffix
//	location.href = "$href_basepage?{$field_current}=" + id + href_suffix
	
//	submenu_form = document.getElementByID("submenu_{$field_current}");
	submenu_form = document.all("submenu_{$field_current}");
	submenu_form.{$field_current}.value = id
//	alert(submenu_form.{$field_current}.value)
	submenu_form.submit()
}
</script>
EOT;

*/

?>
<!-- END _submenu.php -->