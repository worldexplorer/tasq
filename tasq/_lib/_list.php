<!-- BEGIN _list.php -->

<?

$tdshift = 0;
$table_has_editables = 0;
$table_editables = array("checkbox", "number");

/*
function product_fill_itnames_jsarray($row) {
	global $itnames_jsarray;

	if ($itnames_jsarray != "") $itnames_jsarray .= ", ";
	$itnames_jsarray .= '"' . hash_by_tpl($row, "product_#ID#") . '"';
}
function product_it($row) {
	$ret = "";

	$tpl = <<< EOT
<input type=text class="qnty" name="product_#ID#" id="product_#ID#" onfocus="it_onfocus()" onkeyup="it_onkeyup()" onkeydown="it_onkeydown()" value="#BASKET_QNTY#">
EOT;

	$tpl = <<< EOT
<input type=text class="qnty" name="product_#ID#" id="product_#ID#" value="#BASKET_QNTY#">
EOT;

	$ret = hash_by_tpl($row, $tpl);
	
	return $ret;
}
*/




if ($header_tpl == "" && $item_tpl == "") {

	if (in_array($entity, $no_delentity_list)) {
		foreach ($table_columns as $table_column => $column_params) {
			$column_type = (isset($column_params[1])) ? $column_params[1] : "";
			if ($column_type == "checkboxdel") {
				unset ($table_columns[$table_column]);
			}
		}
	}

	foreach ($table_columns as $table_column => $column_params) {
//		pre ($column_params);
		if (count($column_params) == 0) continue;

		$header_txt = (isset($column_params[0])) ? $column_params[0] : "";
		$column_type = (isset($column_params[1])) ? $column_params[1] : "";
		$param1 = (isset($column_params[2])) ? $column_params[2] : "";
		$param2 = (isset($column_params[3])) ? $column_params[3] : "";
		$param3 = (isset($column_params[4])) ? $column_params[4] : "";
	
		if ($column_type == "cnt") {
			if ($table_column == $entity) {
				if ($header_txt == "") $header_txt = ucfirst($msg_bo_subitems);
				if ($param1 == "") $param1 = ucfirst($msg_bo_subitems) . ": ";
			} else {
				if ($header_txt == "" && isset($entity_list[$entity])) $header_txt = $entity_list[$table_column];
				if ($param1 == "" && isset($entity_list[$entity])) $param1 = $entity_list[$table_column] . ": ";
			}
		}


		$ident_pos = strpos(makestrict($table_column), "ident");
		if ($ident_pos !== false && $ident_pos == 0) {
			if ($header_txt == "" && isset($entity_list[$entity])) $header_txt = $entity_list[$entity];
//			if ($header_txt == "" && isset($entity_list_single[$entity])) $header_txt = $entity_list_single[$entity];
		}


		$table_column_strict = makestrict($table_column);

// $msg_fields["customer-published-list"] >> $msg_fields["customer-published"]
// >> $msg_fields["published-list"] >> $msg_fields["published"] >> 

		if ($header_txt == "" && isset($msg_fields[$entity . "-" . $table_column . "-list"])) $header_txt = $msg_fields[$entity . "-" . $table_column . "-list"];
		if ($header_txt == "" && isset($msg_fields[$entity . "-" . $table_column_strict . "-list"])) $header_txt = $msg_fields[$entity . "-" . $table_column_strict . "-list"];
		if ($header_txt == "" && isset($msg_fields[$entity . "-" . $table_column])) $header_txt = $msg_fields[$entity . "-" . $table_column];
		if ($header_txt == "" && isset($msg_fields[$entity . "-" . $table_column_strict])) $header_txt = $msg_fields[$entity . "-" . $table_column_strict];


		if ($header_txt == "" && isset($msg_fields[$table_column . "-list"])) $header_txt = $msg_fields[$table_column . "-list"];
		if ($header_txt == "" && isset($msg_fields[$table_column])) $header_txt = $msg_fields[$table_column];


// $table_columns["poduct_ident"]:
		if ($header_txt == "" && isset($entity_list_single[$table_column_strict])) $header_txt = $entity_list_single[$table_column_strict];


// $table_columns["cgroup_ident"]:
// >> $msg_fields["cgroup-list"] >> $msg_fields["cgroup"] >> $msg_fields["published"] >> 

		$group_pos = strpos($table_column_strict, "group");
		if ($header_txt == "" && $group_pos !== false) {
//			echo "entity_list[" . makestrict($table_column) . "] = [" . $entity_list[makestrict($table_column)] . "]";
			if ($header_txt == "" && isset($msg_fields[$table_column_strict . "-list"]))  $header_txt = $msg_fields[$table_column_strict . "-list"];
			if ($header_txt == "" && isset($msg_fields[$table_column_strict])) $header_txt = $msg_fields[$msg_fields[$table_column_strict]];
			if ($header_txt == "" && isset($entity_list[$table_column_strict])) $header_txt = $entity_list[$table_column_strict];
			if ($header_txt == "" && isset($entity_list_single[$table_column_strict])) $header_txt = $entity_list_single[$table_column_strict];
		}


		$header_tmp = "<th width=10>" . $header_txt . "</th>";
		$item_tmp = "<td align=center class='wh' width=10>#" . $table_column . "#</td>";
	
		if ($column_type == "sernoupdown" && strpos(get_entity_orderfield($entity), "manorder") === false) $column_type = "serno";
		$updown_suffix = $fixed_suffix;

		if ($pg > 0) {
			if ($updown_suffix != "") $updown_suffix .= "&";
			$updown_suffix .= "pg=$pg";
		}
		if ($updown_suffix != "") $updown_suffix = "&". $updown_suffix;


		switch ($column_type) {
			case "sernoupdown":
				$header_tmp = "<th colspan=2 style='width:9ex'>" . $header_txt . "</th>";
				if ($in_backoffice_readonly == 1) {
			$item_tmp = <<< EOT
<td align=center width=10>#I#</td>
<td width=24>
	<table cellpadding=0 cellspacing=2>
	<tr>
	<td><a href="javascript:alert('$in_backoffice_readonly_msg')"><img src="img/down.gif" width=10 height=6 border=0></a></td>
	<td><a href="javascript:alert('$in_backoffice_readonly_msg')"><img src="img/up.gif" width=10 height=6 border=0></a></td>
	</tr>
	</table>
</td>

EOT;
				} else {
					$item_tmp = <<< EOT
<td align=center width=10>#I#</td>
<td width=24>
	<table cellpadding=0 cellspacing=2>
	<tr>
	<td><a href="$manorder_move_page_tpl.php?action=down&id=#ID#{$updown_suffix}"><img src="img/down.gif" width=10 height=6 border=0></a></td>
	<td><a href="$manorder_move_page_tpl.php?action=up&id=#ID#{$updown_suffix}"><img src="img/up.gif" width=10 height=6 border=0></a></td>
	</tr>
	</table>
</td>

EOT;
				}

				$tdshift++;
				break;
	
			case "serno":
				$header_tmp = "<th width=20>" . $header_txt . "</th>";
				$item_tmp = <<< EOT
<td align=center>#I#</td>

EOT;
				break;
	
			case "hrefedit":
				$header_tmp = "<th>" . $header_txt . "</th>";
				$item_tmp = <<< EOT
<td>@popup_face@ <a href="#ENTITY#-edit.php?id=#ID#{$updown_suffix}">#$table_column#</a></td>

EOT;
				break;
	
			case "view":
			case "groupconcat":
/*				$style_width = ($param1 != "")
					? "style='width:$param1'"
					: "style='width:" . (floor(strlen($header_txt) * 1.2)+2) . "ex'"
					;
*/
				$style_width = ($param1 != "") ? "style='width:$param1'" : "";

				$header_tmp = "<th $style_width>$header_txt</th>";
				$item_tmp = <<< EOT
<td>#$table_column#</td>

EOT;
				break;

			case "viewcenter":
				$style_width = ($param1 != "") ? "style='width:$param1'" : "";

				$header_tmp = "<th $style_width>$header_txt</th>";
				$item_tmp = <<< EOT
<td align=center>#$table_column#</td>

EOT;
				break;

			case "viewnowrap":
				$header_tmp = "<th>" . $header_txt . "</th>";
				$item_tmp = <<< EOT
<td nowrap>#$table_column#</td>

EOT;
				break;

			case "ahref":
				$ahref_width = ($param2 != "") ? "style='width:$param2'" : "";
				$header_tmp = "<th>" . $header_txt . "</th>";
				$item_tmp = <<< EOT
<td $ahref_width>$param1</td>

EOT;
				break;
	
			case "ahrefcenter":
				$ahref_width = ($param2 != "") ? "style='width:$param2'" : "";
				$header_tmp = "<th>" . $header_txt . "</th>";
				$item_tmp = <<< EOT
<td align=center $ahref_width>$param1</td>

EOT;
				break;

			case "ahrefnowrap":
				$header_tmp = "<th>" . $header_txt . "</th>";
				$item_tmp = <<< EOT
<td nowrap>$param1</td>

EOT;
				break;
	
			case "timestamp":
				$header_tmp = "<th style='width:11em'>" . $header_txt . "</th>";
				$item_tmp = <<< EOT
<td align=center>#$table_column#</td>

EOT;
				break;
	
			case "datetime":
				$header_tmp = "<th style='width:11em'>" . $header_txt . "</th>";
				$item_tmp = <<< EOT
<td align=center>#$table_column#</td>

EOT;
				break;
	
			case "date":
				$header_tmp = "<th style='width:6em'>" . $header_txt . "</th>";
				$item_tmp = <<< EOT
<td align=center>#$table_column#</td>

EOT;
				break;
	
			case "checkboxro":
				$header_tmp = "<th style='width:1em'>" . $header_txt . "</th>";
				$item_tmp = <<< EOT
<td align=center><input type=checkbox #{$table_column}_checked# disabled></td>

EOT;
//<!--td align=center><img src="img/#{$table_column}_checked_imgsrc#" width="14" height="14"></td-->
				break;
	
			case "checkboxdel":
				$header_tmp = "<th style='width:1em'>" . $header_txt . "</th>";
				$item_tmp = <<< EOT
<td align=center><input type=checkbox name='del_#ID#'></td>

EOT;
				break;
	
			case "recursive":
				$header_tmp = "<th style='width:8em'>$header_txt</th>";
				if ($param1 == "") $param1 = "подгрупп: ";
				$param1 = "<a href='#ENTITY#.php?parent_id=#ID#'>$param1@recursive_cnt@</a>";

				$item_tmp = <<< EOT
<td align=center>$param1</td>
EOT;
				break;
	
			case "depend":
				$header_tmp = "<th style='width:8em'>$header_txt</th>";
				if ($param1 == "") $param1 = "@masterdepend_entity_hr@: ";
				$param1 = "<center><a href='@masterdepend_entity@.php?#ENTITY#=#ID#'>$param1@masterdepend_cnt@</a></center>";

				$item_tmp = <<< EOT
<td align=center>$param1</td>
EOT;
				break;

			case "cnt":
				if ($header_txt == "") $header_txt = $entity_list[$table_column];
				if ($param1 == "") $param1 = strtolower($entity_list[$table_column]) . ": ";

				$style_width = ($param2 != "")
					? "style='width:$param2'"
					: "style='width:" . (floor(strlen($param1) * 1.6)+2) . "ex'"
					;

				$header_tmp = "<th $style_width>$header_txt</th>";
				$hrefto = ($entity == $table_column)
					? "$table_column.php?parent_id=#ID#"
					: "$table_column.php?#ENTITY#=#ID#"
					;
				$table_column_upper = strtoupper($table_column);
				$item_tmp = <<< EOT
<td align=center><a href="$hrefto">$param1 #${table_column_upper}_CNT#</a></td>
EOT;
				break;



	


			case "checkbox":
				$header_tmp = "<th style='width:1em'>" . $header_txt . "</th>";
				$item_tmp = <<< EOT
<td align=center><input type=checkbox class="checkbox" name="{$table_column}_#ID#" #{$table_column}_checked#></td>

EOT;
				break;

			case "number":
				$td_tpl = ($param1 != "") ? "$param1" : "#INPUT#";
				$td_width = ($param2 != "") ? "style='width:$param2'" : "";
				$input_width = ($param3 != "") ? "style='width:$param3'" : "";
				$header_tmp = "<th $td_width>" . $header_txt . "</th>";

				$input_tpl = <<< EOT
<input type=text class="number" $input_width name="{$table_column}_#ID#" value="#{$table_column}#">
EOT;
				$td_content = hash_by_tpl(array("input" => $input_tpl), $td_tpl);
				$item_tmp = <<< EOT
<td>$td_content</td>

EOT;

				break;

			case "textfield":
				$td_tpl = ($param1 != "") ? "$param1" : "#INPUT#";
				$td_width = ($param2 != "") ? "style='width:$param2'" : "";
				$input_width = ($param3 != "") ? "style='width:$param3'" : "";
				$header_tmp = "<th $td_width>" . $header_txt . "</th>";

				$input_tpl = <<< EOT
<input type=text class="textfield" $input_width name="{$table_column}_#ID#" value="#{$table_column}#">
EOT;
				$td_content = hash_by_tpl(array("input" => $input_tpl), $td_tpl);
				$item_tmp = <<< EOT
<td>$td_content</td>

EOT;

				break;

			default:
				break;
		}
		
		$header_tpl .= $header_tmp;
		$item_tpl .= $item_tmp;
//		echo "[$table_column]";

		if (in_array($column_type, $table_editables)) $table_has_editables = 1;
	}
	
	if ($header_tpl == "") {
		$header_tpl = <<< EOT
<th width=40 colspan=2>№</th>
<th width=120 nowrap>Last update</th>
<th width=100%>$entity_msg_h</th>
<th width=10>Опубл</th>
<th width=70>Удалить</th>
EOT;
	}
	
	if ($item_tpl == "") {
		$item_tpl = <<< EOT
<td align=center class='wh'>#I#</td>
<td class='wh'>#UPDOWN#</td>
<td align=center class='wh' width=120>#DATE_UPDATED#</td>
<td align=left class='wh' width=100%>#IDENT_EDIT#</td>
<td align=center class='wh'>#PBOX#</td>
<td align=center class='wh' width='70'>#DBOX#</td>
EOT;
	}
	
	$header_tpl = "<tr>" . $header_tpl . "</tr>";
	$item_tpl = "<tr>" . $item_tpl . "</tr>";
}



//echo "<pre>" . htmlentities($item_tpl) . "</pre>";

$item_rows = "";
$deleted = "";


//$entity_has_deleted_field = entity_has_deleted_field($entity);
//if ($entity_has_deleted_field == 1) 
$list_query_cond .= " and e.deleted=0 ";

//if ($list_url_suffix == "") $list_url_suffix = hrefsuffix_fromhash($fixed_hash, "?");

// to avoid duplicate joints from $entity_fixed_list and $table_columns
$joint_list_tmp = array();

// improved list_query with left joins to fixed tables (add slave tables entity is pointing to)
if (isset($entity_fixed_list[$entity])) {
	foreach ($entity_fixed_list[$entity] as $dependant_entity) {
		if ($dependant_entity == "parent_id") continue;

		if (in_array($dependant_entity, $joint_list_tmp)) {
			continue;
		} else {
			$joint_list_tmp[] = $dependant_entity;
//			pre(pr($entity_fixed_list[$entity]) . $joint_list_tmp);
		}
	
		$dependant_entity = makestrict($dependant_entity);
		
		$m2m_dependtable = get_m2m_dependtable($entity, $dependant_entity);
//		pre("entity[$entity] dependant_entity[$dependant_entity] m2m_dependtable[$m2m_dependtable]");
	
		if ($m2m_dependtable == "") {
	//		. " left join m2m_product_pgroup m2m on m2m.$entity=e.id and m2m.deleted=0"
	//		. " left join pgroup pg on pg.id=m2m.pgroup"
	
			$list_left_fields .= ", $dependant_entity.ident as ${dependant_entity}_ident";
			$list_left_joined_like_list[] = "$dependant_entity.ident";
	
			$list_left_o2mjoins .=
				" left join $dependant_entity $dependant_entity"
					. " on e.$dependant_entity=$dependant_entity.id";
	
		} else {

// товары через m2m, вывести имя первой группы для товара
// product.php: "pgroup_ident" => array("Группа", "view")

	//		. " left join pgroup pg on e.pgroup=pg.id"
	
			$list_left_fields .= ", $dependant_entity.ident as ${dependant_entity}_ident";
			$list_left_fields .= ", concat($dependant_entity.ident) as ${dependant_entity}_ident_list";
//			$list_left_fields .= ", group_concat(distinct $dependant_entity.ident order by $dependant_entity.ident separator '<br>') as ${dependant_entity}_ident";
//GROUP_CONCAT(DISTINCT pgroup.id, '=', pgroup.ident order by pgroup.manorder separator '~~') 
//1			$list_left_fields .= ", group_concat(distinct $dependant_entity.id, '=', $dependant_entity.ident order by $dependant_entity.ident separator '~~') as ${dependant_entity}_ident";

			$list_left_joined_like_list[] = "$dependant_entity.ident";

			$list_left_m2mjoins .=
				" left join $m2m_dependtable m2m_$dependant_entity"
					. " on m2m_$dependant_entity.$entity=e.id and m2m_$dependant_entity.deleted=0"
				. " left join $dependant_entity $dependant_entity"
					. " on $dependant_entity.id=m2m_$dependant_entity.$dependant_entity";
	
		}
	}
}


// improved list_query with sum(id) on backhref tables (add master tables entity is depending on)
$entity_masterfor_list = get_entity_ismaster_for($entity);
//pre("entity_masterfor_list[" . pr($entity_masterfor_list) . "]");

foreach ($entity_masterfor_list as $entity_masterfor) {
	$m2m_dependtable = get_m2m_dependtable($entity, $entity_masterfor);
//	pre("entity[$entity] entity_masterfor[$entity_masterfor] m2m_dependtable[$m2m_dependtable]");

	if (in_array($entity_masterfor, $joint_list_tmp)) {
		continue;
	} else {
		$joint_list_tmp[] = $entity_masterfor;
//		pre($entity_masterfor . ":" . $joint_list_tmp);
	}

	if ($m2m_dependtable == "") {
		$list_left_fields .= ", count(distinct $entity_masterfor.id) as ${entity_masterfor}_cnt";

		if ($entity_masterfor == $entity) {
	//		. " left join pollvote pv on pv.$entity=e.id and pv.deleted=0 and pv.published=1"
	
			$list_left_o2mjoins .=
				" left join $entity_masterfor $entity_masterfor"
					. " on e.id=$entity_masterfor.parent_id and $entity_masterfor.deleted=0"
//					. " and $entity_masterfor.published=1"
					;
		} else {
	
			$list_left_o2mjoins .=
				" left join $entity_masterfor $entity_masterfor"
					. " on $entity_masterfor.$entity=e.id and $entity_masterfor.deleted=0"
//					. " and $entity_masterfor.published=1"
					;
		}
	} else {
// товары через m2m, вывести количество товаров для группы, два вызова аналогичны:
// pgroup.php: "~3" => array("Товаров", "depend", "товаров: ")
// pgroup.php: "product" => array("", "cnt")

		$list_left_fields .= ", count(distinct m2m_$entity_masterfor.id) as ${entity_masterfor}_cnt";

		$list_left_m2mjoins .=
			" left join $m2m_dependtable m2m_$entity_masterfor"
				. " on m2m_$entity_masterfor.$entity=e.id and m2m_$entity_masterfor.deleted=0"
			. " left join $entity_masterfor $entity_masterfor"
				. " on $entity_masterfor.id=m2m_$entity_masterfor.$entity_masterfor";


	}
	$list_left_m2mjoins_got_backhref = 1;
}

//pre(pr($joint_list_tmp));

// improved list_query with left joins to tables (add slave tables $table_columns is pointing to by means of "cnt")
foreach ($table_columns as $table_column => $column_params) {
	$column_type = (isset($column_params[1])) ? $column_params[1] : "";
	if ($column_type != "cnt") continue;
	
	$entity_masterfor = makestrict($table_column);
	
//	pre($entity_masterfor);
	if (in_array($entity_masterfor, $joint_list_tmp)) {
		continue;
	} else {
		$joint_list_tmp[] = $entity_masterfor;
	}

	$m2m_dependtable = get_m2m_dependtable($entity, $entity_masterfor);
//	pre("entity[$entity] entity_masterfor[$entity_masterfor] m2m_dependtable[$m2m_dependtable]");


	if ($m2m_dependtable == "") {
		$list_left_fields .= ", count(distinct $entity_masterfor.id) as ${entity_masterfor}_cnt";

		if ($entity_masterfor == $entity) {
			$list_left_o2mjoins .=
				" left join $entity_masterfor $entity_masterfor"
					. " on e.id=$entity_masterfor.parent_id and $entity_masterfor.deleted=0"
//					. " and $entity_masterfor.published=1"
					;
		} else {
	
			$list_left_o2mjoins .=
				" left join $entity_masterfor $entity_masterfor"
					. " on $entity_masterfor.$entity=e.id and $entity_masterfor.deleted=0"
//					. " and $entity_masterfor.published=1"
					;
		}
	} else {
		$list_left_fields .= ", count(distinct m2m_$entity_masterfor.id) as ${entity_masterfor}_cnt";

		$list_left_m2mjoins .=
			" left join $m2m_dependtable m2m_$entity_masterfor"
				. " on m2m_$entity_masterfor.$entity=e.id and m2m_$entity_masterfor.deleted=0"
			. " left join $entity_masterfor $entity_masterfor"
				. " on $entity_masterfor.id=m2m_$entity_masterfor.$entity_masterfor";
	}
}



if ($q != "" && count($list_left_joined_like_list) > 0) {
	$list_query_like_cond = 
		" and ( "
		. sqlcond_like_fromlist($fixedlike_list, $q, "e", " and ", 0)
		. " or "
		. sqlcond_like_fromlist($list_left_joined_like_list, $q, "", " and ", 0)
		. " ) "
		;
}


if ($list_query_cnt == "") {
//	$list_query_cnt = "select count(e.id) as cnt from $entity e where 1=1 $list_query_cond $list_query_like_cond";

//improved list_query with left joins to fixed tables
// searching in pgroup, with left joins to product, causes extra pages in pager...
	if ($list_left_m2mjoins != "") {
		if ($q != "") {
			if ($list_left_m2mjoins_got_backhref == 1) {
//				$list_query_cnt = "select count(e.id) as cnt"
				$list_query_cnt = "select count(distinct e.id) as cnt, '+m2mjoins +got_backhref' as case_debugging"
					. " from $entity e"
					. $list_left_o2mjoins

// cstatus in mauto shold include list_left_m2mjoins
					. $list_left_m2mjoins

					. $list_left_additional_joins
					. " where 1=1"
					. $list_query_cond
					. $list_query_like_cond
//					. " group by e.id"
					;
			} else {
//				$list_query_cnt = "select count(e.id) as cnt"
				$list_query_cnt = "select count(distinct e.id) as cnt, '+m2mjoins -got_backhref' as case_debugging"
					. " from $entity e"
					. $list_left_o2mjoins
					. $list_left_m2mjoins
					. $list_left_additional_joins
					. " where 1=1"
					. $list_query_cond
					. $list_query_like_cond
					. " group by e.id"
					;
			}
		} else {
			if ($list_left_m2mjoins_got_backhref == 1) {
//				$list_query_cnt = "select count(e.id) as cnt"
				$list_query_cnt = "select count(distinct e.id) as cnt, '-m2mjoins +got_backhref' as case_debugging"
					. " from $entity e"

// cstatus in mauto shold include list_left_m2mjoins
					. $list_left_m2mjoins

//					. $list_left_additional_joins
					. " where 1=1"
					. $list_query_cond
					. $list_query_like_cond
//					. " group by e.id"
					;
			} else {
//				$list_query_cnt = "select count(e.id) as cnt"
				$list_query_cnt = "select count(distinct e.id) as cnt, '-m2mjoins -got_backhref' as case_debugging"
					. " from $entity e"
					. $list_left_m2mjoins
					. $list_left_additional_joins
					. " where 1=1"
					. $list_query_cond
					. $list_query_like_cond
//					. " group by e.id"
					;
			}
		}
	} else {
//		$list_query_cnt = "select count(e.id) as cnt"
		$list_query_cnt = "select count(distinct e.id) as cnt, 'common' as case_debugging"
			. " from $entity e"
			. $list_left_m2mjoins

// added for cdialog in mauto
			. $list_left_o2mjoins

			. $list_left_additional_joins
			. " where 1=1"
			. $list_query_cond
			. $list_query_like_cond
//			. " group by e.id"
			;
	}
}


if (!isset($list_url)) $list_url = $_SERVER["SCRIPT_NAME"] . $list_url_suffix;

$list_query_cnt = add_sql_table_prefix($list_query_cnt);
if ($debug_query == 1) echo "<br>LIST_QUERY_CNT[$list_query_cnt]<br>";

$result = mysql_query($list_query_cnt) or die("SELECT CNT failed:<br>$list_query_cnt<br>" . mysql_error());
$row = mysql_fetch_array($result);
$rows_total = $row["cnt"];

if (!isset($pager_HTML)) {
	$pager_HTML = "[<font color=" . OPTIONS_COLOR_GRAY . ">pager disabled directively</font>]";
}

if ($no_pager == 0) {
	if (!in_array($entity, $no_pager_list)) {
//		if ($rows_total > 5000) {
//			$pager_HTML = "[<font color=" . OPTIONS_COLOR_GRAY . ">pager disabled: too much rows_total to calculate, may hang on...</font>]";
//		} else {
			$pager_HTML = pager($list_url, $rows_total);
//		}
	}
} 

if ($list_query == "") {

	if (!in_array($entity, $no_entity_img_leftjoin)) {
		$list_left_additional_fields .= ", count(distinct img.id) as img_cnt";
		$list_left_o2mjoins .= " left join img img"
					. " on img.owner_entity='$entity' and img.owner_entity_id=e.id";
	}


	$list_query = "select e.*"
		. " from $entity e where 1=1 $list_query_cond $list_query_like_cond"
		. " order by e." . get_entity_orderby($entity)
		;

//improved list_query with left joins to fixed tables
	$list_query = "select e.* $list_left_fields $list_left_additional_fields"
		. " from $entity e"
		. $list_left_o2mjoins
		. $list_left_m2mjoins
		. $list_left_additional_joins
		. " where 1=1"
		. $list_query_cond
		. $list_query_like_cond
		. " group by e.id"
		. " order by e." . get_entity_orderby($entity)
		;
}


if ($no_pager == 0) {
	if (!in_array($entity, $no_pager_list)) {
		if ($pg != 999999) {
			$list_query .= $limit_sql;
		}
	}
}

$items_updated = 0;
$items_deleted = 0;

$list_query = add_sql_table_prefix($list_query);

// updating & deleting
if ($mode == "update") {
	if ($debug_query == 1) echo "<br>LIST_QUERY_FOR_UPDATE&DELETE[$list_query]<br>";
	$result = mysql_query($list_query) or die("SELECT LIST FOR_UPDATE&DELETE failed:<br>$list_query<br>" . mysql_error());
	
	for ($i=1; $row = mysql_fetch_assoc($result); $i++) {
		$id = $row["id"];

		if (get_number("save_pressed") == 1) {
			$update_hash = array();
			
			foreach ($table_columns as $table_column => $column_params) {
				$column_type = (isset($column_params[1])) ? $column_params[1] : "";
			
				if (!isset($row[$table_column])) {
			//		$errormsg .= "{$entity}[{$table_column}]: column is not set<br>";
			//		echo "{$entity}[{$table_column}]: column is not set<br>";
					continue;
				}
				
				$sumbitted_value = "";
				$it_name = $table_column . "_" . $id;

				switch ($column_type) {
					case "checkbox":
						$sumbitted_value = get_string($it_name);
						$sumbitted_value = ($sumbitted_value == "on") ? 1 : 0;
						$msg = "it_name=[$it_name] row[$table_column]=[" . $row[$table_column] . "] sumbitted_value=[$sumbitted_value]";
						if ($row[$table_column] != $sumbitted_value) {
							$update_hash[$table_column] = $sumbitted_value;
							$msg .= " will update";
						}
//						pre($msg);
						break;

					case "number":
						$sumbitted_value = get_number($it_name);
						$msg = "it_name=[$it_name] row[$table_column]=[" . $row[$table_column] . "] sumbitted_value=[$sumbitted_value]";
						if ($row[$table_column] != $sumbitted_value) {
							$update_hash[$table_column] = $sumbitted_value;
							$msg .= " will update";
						}
//						pre($msg);
						break;
		
					case "textfield":
						$sumbitted_value = get_string($it_name);
						$msg = "it_name=[$it_name] row[$table_column]=[" . $row[$table_column] . "] sumbitted_value=[$sumbitted_value]";
						if ($row[$table_column] != $sumbitted_value) {
							$update_hash[$table_column] = $sumbitted_value;
							$msg .= " will update";
						}
//						pre($msg);
						break;
		
					default:
						break;
				}
			}

			if (count($update_hash) > 0) {
				$entity_before_rowupdate_function = $entity . "_before_rowupdate";
				if (function_exists($entity_before_rowupdate_function)) $update_hash = $entity_before_rowupdate_function($row, $update_hash);

//				pre($update_hash);
				update($update_hash);
//				update($update_hash, array("id" => $id), $entity);
				$items_updated = 1;
			}

		}


		if (get_number("delete_pressed") == 1) {
			$del = "del_$id";
			if (get_string($del) == "on") {
				$ident = $row["ident"];
				delete (array("id" => $id));
				delete_img_forowner($entity, $id);
				
				$entity_after_delete_function = $entity . "_after_delete";
				if (function_exists($entity_after_delete_function)) $entity_after_delete_function($id);
			
				$deleted .= " [$ident]";
		//		$i--;
		//		$rows_total--;
		//		$skipitem = 1;
				$items_deleted = 1;
			}
		}

	}

}

// _top.php is already included...
//if ($items_updated == 1) $errormsg .= " Информация сохранена";
//if ($items_deleted == 1) $errormsg .= " Элементы удалены";



// listing after update

$entity_before_select_function = $entity . "_before_select";
if (function_exists($entity_before_select_function)) $entity_before_select_function();

if ($debug_query == 1) echo "<br>LIST_QUERY[$list_query]<br>";
$result = mysql_query($list_query) or die("SELECT LIST failed:<br>$list_query<br>" . mysql_error());

for ($i=1; $row = mysql_fetch_assoc($result); $i++) {
//	require "_list_item.php";
	$id = $row["id"];
	$skipitem = 0;

	foreach ($table_columns as $table_column => $column_params) {
		$column_type = (isset($column_params[1])) ? $column_params[1] : "";
//		$column_type_strict = makestrict($column_type_strict);
		switch ($column_type) {
/*
			case "timestamp":
				$row[$table_column] = ts2human($row[$table_column]);
				break;
*/	
			case "date":
				$date_hash = parse_datetime($row[$table_column]);
				$uts = datehash_2uts($date_hash);
				$row[$table_column] = strftime($date_fmt, $uts);
				break;

			case "datetime":
				$date_hash = parse_datetime($row[$table_column]);
				$uts = datehash_2uts($date_hash);
				$row[$table_column] = strftime($datetime_fmt, $uts);
				break;
	
			case "checkbox":
			case "checkboxro":
				$row[$table_column . "_checked"] = ($row[$table_column] == 1) ? "checked" : "";
				$row[$table_column . "_checked_imgsrc"] = ($row[$table_column] == 1) ? "checked.gif" : "unchecked.gif";
				break;
	
			case "view":
			case "viewcenter":
			case "groupconcat":
				$groupconcat = "";
				$groups_splitted = preg_split("/~~/", $row[$table_column]);
//				pre($groups_splitted);
				$table_column_strict = makestrict($table_column);
				foreach ($groups_splitted as $onegroup) {
					$id_ident_splitted = array();
					preg_match("~(\d*)=(.*)~", $onegroup, $id_ident_splitted);
//					pre($id_ident_splitted);

					if (isset($id_ident_splitted[0])) {
						if ($groupconcat != "") $groupconcat .= ", ";

//						$groupconcat .= "<a href=$table_column_strict-edit.php?id="
//							. $id_ident_splitted[1] . " title='" . $id_ident_splitted[2] . "'>"
//							. $id_ident_splitted[2] . "</a>";

						if (count($groups_splitted) <= 1) {
							$groupconcat .= "<a href='$entity.php?$table_column_strict="
								. $id_ident_splitted[1] . "' title='" . $id_ident_splitted[2] . "'>"
								. firstletters_truncate($id_ident_splitted[2], 20) . "</a>";
						} else {
							$groupconcat .= "<a href='$entity.php?$table_column_strict="
								. $id_ident_splitted[1] . "' title='" . $id_ident_splitted[2] . "'>"
								. firstletters_truncate($id_ident_splitted[2], 30) . "</a>";
						}
					} else {
//						$groupconcat .= "<a href=$entity.php?$table_column_strict="
//							. $onegroup . ">" . $onegroup . "</a>";

						$groupconcat .= $onegroup;
					}
				}
				$row[$table_column] = $groupconcat;
				break;

			default:
				break;
		}
	}

	if ($pg != 999999) {
		$paged_i = $i + ($rows_per_page * $pg);
	} else {
		$paged_i = $i;
	}
	$row["i"] = $paged_i;
	
	//$row["group_recursive"] = "<a href=$entity.php?parent_id=" . $row["id"] . ">подгруппы</a>";
	
//	pre($row);
//	pre($item_tpl);
	$item_tmp = hash_by_tpl($row, $item_tpl);

//	mauto tried number to able use @src_popup@ in product-client.php
//	$item_tmp = hash_by_tpl($row, $item_tmp);
	
	if ($skipitem == 0) $item_rows .= $item_tmp;

	$entity_rowselect_function = $entity . "_rowselect";
	if (function_exists($entity_rowselect_function)) $entity_rowselect_function($row);
}

$entity_after_select_function = $entity . "_after_select";
if (function_exists($entity_after_select_function)) $entity_after_select_function();



if (!isset($tdcnt)) {
	$tdcnt = count($table_columns) + $tdshift;
}

if ($item_rows == "") {
	$item_rows = "<tr><td align=center style='padding:20px' colspan=$tdcnt>$list_empty_msg</td></tr>";
}

$hiddens_default = "";
if (!isset($hiddens)) $hiddens = $hiddens_default;
?>

<?
if (!isset($add_suffix)) $add_suffix = "";
if ($fixed_suffix != "") {
//	if ($add_suffix != "") $add_suffix .= "&";
	$add_suffix = hrefsuffix_fromhash($fixed_hash);
}

if (!isset($add_href)) {
	$add_href = $entity . "-edit.php";
	if ($add_suffix != "") $add_href = $add_href . "?" . $add_suffix;
}

?>


<table cellspacing=1 cellpadding=2 align=center width=100% id="table_list_<?=$entity?>" class=list>

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

<form name="form_list_<?=$entity?>" id="form_list_<?=$entity?>">
<!-- для удаления после поиска -->
<input type=hidden name=q value="<?=$q?>">

<?= $fixed_hiddens ?>


<tr>
	<td colspan=2>
		<table cellspacing='1' cellpadding='3' width=100%  class='gw'>
		<?= $header_tpl ?>
		<?= $item_rows ?>
		</table>
	</td>
</tr>

<? if ($no_bottomline == 0) { ?>
<tr valign=top>
	<input type=hidden name="mode" value="update">
	<input type=hidden name="delete_pressed" value="0">
	<input type=hidden name="save_pressed" value="0">

	<td style="padding-top: 0.7em"><?=$pager_HTML?></td>
	<td align='right'>

<?=$custom_bottomline?>

<? if ($table_has_editables == 1 && $no_savebutton == 0) { ?>
		<?=$f5_near_save?>
		<input style="width: 7em" type=button value="<?=$msg_bo_save?>" onclick="javascript:onsave_<?=$entity?>()">
<? } ?>


<? if ($no_del == 0) { ?>
<? if (!in_array($entity, $no_delentity_list)) { ?>
		<input style="width: 6em" type=button value="<?=$msg_bo_delete?>" onclick="javascript:ondelete_<?=$entity?>()">
		<input type=checkbox id="cb_checkall_4delete" name="cb_checkall_4delete" onclick="javascript:checkall_4delete()" title="<?=$msg_bo_delete_all_tip?>">
			<label for="cb_checkall_4delete" title="<?=$msg_bo_delete_all_tip?>"><?=$msg_bo_delete_all?></label>
<? } else { ?>
		<input style="width: 100px;" type=button value="<?=$msg_bo_delete_unable?>" disabled>
<? } ?>
<? } ?>
		</td>
	</tr>
<? } ?>

</form>
</table>

<script>
function onsave_<?=$entity?>() {
<? if ($in_backoffice_readonly == 1) { ?>
	alert("<?=$in_backoffice_readonly_msg?>")
<? } else { ?>

	form_list_<?=$entity?>.save_pressed.value = 1
	form_list_<?=$entity?>.method = "post"
	form_list_<?=$entity?>.submit()

<? } ?>

}

function ondelete_<?=$entity?>() {
<? if ($in_backoffice_readonly == 1) { ?>
	alert("<?=$in_backoffice_readonly_msg?>")
<? } else { ?>

<? if ($no_del_warning == 0) { ?>
	sure = confirm("<?=$ondelete_jsmsg?>")
<? } else { ?>
	sure = true
<? } ?>

	if (sure == true) {
		form_list_<?=$entity?>.delete_pressed.value = 1
		form_list_<?=$entity?>.method = "post"
		form_list_<?=$entity?>.submit()
	}

<? } ?>

}

function checkall_4delete() {
	value_2bset = document.getElementById("cb_checkall_4delete").checked
//	alert(value_2bset)

	elems = getformelements_startingfrom("form_list_<?=$entity?>", "del_")
	for (i=0; i<elems.length; i++) {
//		alert(elems[i].name)
		elems[i].checked = (elems[i].checked == true) ? false : true
//		elems[i].checked = value_2bset
	}
}


</script>

<!-- END _list.php -->