<?

$composite_inputtype = "multiselect";
$composite_inputtype = "multicheckbox";

function multicompositecontent ($compositetype = "supplierbypgroup",
	$m2m_table = "m2m_sreptask_compositecontent", $composite = array("supplier", "pgroup"),
	$parent_id = 1, $html_table_width = 600,
	$pgroup_table = "pgroup", $product_table = "product", $supplier_table = "supplier",
	$pgroup_levels_display = array(), $level1_column_cnt = 1) {


	global $entity, $id, $cms_dbc, $composite_inputtype, $m2m_sameentity_displaymode_default;
	$ret = "";
	$options = "";

	if (isset($id)) {
		$m2m_fixed = array($entity => $id);

		switch ($compositetype) {
			case "supplierbypgroup_nav":
			case "supplierbypgroup":
				$options = optionscompositecontent_pgroupfromroot(
						$m2m_table, $m2m_fixed, $composite, "supplier", 1, $parent_id,
						$pgroup_table, $product_table, $supplier_table,
						$pgroup_levels_display, $level1_column_cnt);
				break;
			
			case "supplierbypgroup_ro":
				$options = optionscompositecontent_flat_ro($m2m_table, $m2m_fixed, $composite, $parent_id);
				break;
			
			case "productbysupplierbypgroup":
				$options = optionscompositecontent_pgroupfromroot(
						$m2m_table, $m2m_fixed, $composite, "productbysupplier", 1, $parent_id,
						$pgroup_table, $product_table, $supplier_table,
						$pgroup_levels_display, $level1_column_cnt);
				break;
			
			case "productbypgroup":
				$options = optionscompositecontent_pgroupfromroot(
						$m2m_table, $m2m_fixed, $composite, $product_table, 0, $parent_id,
						$pgroup_table, $product_table, $supplier_table,
						$pgroup_levels_display, $level1_column_cnt);
				break;

			case "pgrouptree":
				$options = optionscompositecontent_pgroupfromroot(
						$m2m_table, $m2m_fixed, $composite, $product_table, 0, $parent_id,
						$pgroup_table, $product_table, $supplier_table,
						$pgroup_levels_display, $level1_column_cnt, 0);
				break;

			default:
				echo "multicompositecontent: compositetype error [$compositetype]";
				break;
		}
	}
	
	$it_name = composite_itname($composite);

	switch ($composite_inputtype) {
		case "multiselect":
			$size = 30;
			$ret = "<select multiple size=$size name='{$it_name}[]'>$options</select>";
		
			$ret = "<table cellspacing=0 cellpadding=0><tr valign=middle><td>"
				. $ret
				. "</td><td>&nbsp;</td><td>"
				. "<font color=" . OPTIONS_COLOR_GRAY . ">Для выделения<br>можно использовать<br>клавиши Shift и Ctrl</font>"
				. "</td></tr></table>";
			break;
			
		case "multicheckbox":
			if ($html_table_width != 0) $html_table_width = " width='$html_table_width'";
			$ret = "<table cellpadding=0 cellspacing=0 style='border:1px solid " . OPTIONS_COLOR_GRAY . "' $html_table_width><tr valign=top><td style='padding-left: 1em; padding-right: 1em;'>$options</td></tr></table>";
			break;
			
		default:
			$ret = "multicompositecontent(): error defining composite_inputtype [$composite_inputtype]";
			break;
	}
	return $ret;
}

function optionscompositecontent_flat_ro($m2m_table, $m2m_fixed, $composite, $parent_id) {
	$ret = "";

//	pre($m2m_table);
//	pre($m2m_fixed);
//	pre($composite);
//	pre($parent_id);

	$formname = composite_itname($composite);

	$gray = OPTIONS_COLOR_GRAY;
	$tpl_ms_wraparound = <<< EOT
<table cellspacing=0 cellpadding=0><tr valign=middle><td>
<select multiple size=#SIZE# name='{$formname}[]' #DISABLED#>#ITEMS#</select>
</td><td>&nbsp;</td><td>
<font color=$gray>Для выделения<br>можно использовать<br>клавиши Shift и Ctrl</font>
</td></tr></table>

EOT;

	$tpl = "<tr valign=middle><td><input type=checkbox name='{$formname}[]' id='{$formname}_#ID#' value='#ID#' checked disabled></td><td><label for='{$formname}_#ID#'>#IDENT#</label></td></tr>";

	$subject = "";
	$flist = "";
	$join_tables = "";
	$join_cond = "";
	$orderby = "";

	foreach($composite as $value) {
		if ($join_tables != "") $join_tables .= ", ";
		$join_tables .= "$value $value";

		if ($join_cond != "") $join_cond .= " and ";
		$join_cond .= "m2m.$value = $value.id";

		if ($flist != "") $flist .= ", ";
		$flist .= "$value.ident as $value";

		if ($subject != "") $subject .= ", ' / ', ";
		$subject .= "$value.ident";
		
		if ($orderby != "") $orderby .= ", ";
		$orderby .= "$value." . get_entity_orderby($value);
	}
	
	$subject = "concat(" . $subject . ")";

	$query = "select m2m.id as id, $subject as ident"
			. " from $m2m_table m2m, $join_tables"
			. " where deleted=0 and " . sqlcond_fromhash($m2m_fixed)
			. " and $join_cond"
			. " order by $orderby";

	$ret = query_by_tpl($query, $tpl);

	return $ret;
}


function optionscompositecontent_productbyfixed($m2m_table, $m2m_fixed, $composite, $fixed_hash = array(), $level,
			$pgroup_table = "pgroup", $product_table = "product", $supplier_table = "supplier", $get_down_products = 1) {

	global $cms_dbc, $composite_inputtype;
	static $i = 0, $limit = 0;

	if ($limit > 0 && $i >= $limit) return;

	$m2m_fixed_cond = sqlcond_fromhash($m2m_fixed);
	$fixed_cond = sqlcond_fromhash($fixed_hash);
	$fixed_hrefsuffix = hrefsuffix_fromhash($fixed_hash);
	$fixed_hrefsuffix = str_replace("&", "_", $fixed_hrefsuffix);

	$options = "";
	$it_name = composite_itname($composite);
//	pre($composite);
	
	$query = "select *, id as $product_table"
		. " from $product_table"
		. " where $fixed_cond"
		. " order by " . get_entity_orderby($product_table);


	if (in_array($pgroup_table, array_keys($fixed_hash))) {
		$m2m_dependtable = get_m2m_dependtable($product_table, $pgroup_table);
	
		if ($m2m_dependtable != "") {
			$fixed_cond = sqlcond_fromhash($fixed_hash, "m2m", " and ");
			$query = "select p.*, p.id as $product_table"
				. " from $product_table p"
				. " inner join $m2m_dependtable m2m on m2m.$product_table=p.id $fixed_cond and m2m.deleted=0"
				. " group by p.id"
				. " order by p." . get_entity_orderby($product_table);
		}
	}
	
	$query = add_sql_table_prefix($query);
//	pre($query);
	$result = mysql_query($query, $cms_dbc)
			or die("SELECT OPTIONSCOMPOSITECONTENT_PRODUCTBYFIXED failed:<br>$query:<br>" . mysql_error($cms_dbc));
	$size = mysql_num_rows($result);

	while ($row = mysql_fetch_array($result)) {
//		pre($row);
		$row["option_color"] = ($row["published"] == '1') ? OPTIONS_COLOR_BLACK : OPTIONS_COLOR_GRAY;
		$row["selected"] = "";
		$row["checked"] = "";
		$row["spaces"] = spaces_bylevel($level);
		$row["padding-left"] = $level;
		$row["option_value"] = composite_itvalue($composite, $row);

		$tpl = "";
		switch ($composite_inputtype) {
			case "multiselect":
				$tpl = <<< EOT
<option value="#OPTION_VALUE#" #SELECTED# style="color: #OPTION_COLOR#">#SPACES# #IDENT#</option>

EOT;
				break;
				
			case "multicheckbox":
				$tpl = <<< EOT
<div style="padding-left: #PADDING-LEFT#em">
	<input type="checkbox" name="{$it_name}[]" value="#OPTION_VALUE#" id="${product_table}_#OPTION_VALUE#_{$fixed_hrefsuffix}" #checked#>
	<label for="${product_table}_#OPTION_VALUE#_{$fixed_hrefsuffix}" style="color: #OPTION_COLOR#">#IDENT#</label>
</div>

EOT;
				if ($m2m_table == "m2m_product_fake4navigation" || $get_down_products == 0) $tpl = "@tpl_product_fake4navigation@";
				break;
				
			default:
				$tpl = "optionscompositecontent_productbyfixed(): error defining composite_inputtype [$composite_inputtype]";
				break;
		}



		if ($m2m_table != "m2m_product_fake4navigation") {
			$joinon = "";
			foreach($composite as $value) {
				if (isset ($row[$value])) {		// ppgoption in product has product which is redundant?..
					if ($joinon != "") $joinon .= " and ";
					$joinon .= "$value=" . $row[$value];
				}
			}
	
			$query2 = "select m2m.id from $m2m_table m2m where deleted=0 and"
					. " $m2m_fixed_cond"
					. " and $joinon";
	
			$query2 = add_sql_table_prefix($query2);
			$result2 = mysql_query($query2, $cms_dbc)
					or die("SELECT PRODUCT_OPTION failed:<br>$query2:<br>" . mysql_error($cms_dbc));
			if (mysql_num_rows($result2) > 0) {
				$row["selected"] = "selected";
				$row["checked"] = "checked";
			}
		}

		$options .= hash_by_tpl($row, $tpl);
	
		$i++;
		if ($limit > 0 && $i >= $limit) break;
	}
	
//	pre($options);
	return $options;
}

function tpl_product_fake4navigation($row) {
	$tpl = <<< EOT
<div style="padding-left: #PADDING-LEFT#em; padding-top:2 ; padding-bottom: 2"><nobr><a href="product-edit.php?id=#PRODUCT#&pgroup=#PGROUP#">#IDENT#</a></nobr></div>

EOT;
	if ($GLOBALS["entity"] == "product" && get_number("id") == $row["product"]) {
		$tpl = <<< EOT
<div style="padding-left: #PADDING-LEFT#em; padding-top:2 ; padding-bottom: 2"><nobr><a href="product-edit.php?id=#PRODUCT#&pgroup=#PGROUP#" class="cur">#IDENT#</b></nobr></div>

EOT;
	}
	
	$ret = hash_by_tpl($row, $tpl);

	return $ret;
}


function optionscompositecontent_pgroupfromroot($m2m_table, $m2m_fixed, $composite,
	$downtable = "", $level = 1, $parent_id = 0,
	$pgroup_table = "pgroup", $product_table = "product", $supplier_table = "supplier",
	$pgroup_levels_display = array(), $level1_column_cnt = 1, $get_down_products = 1) {

	global $cms_dbc, $composite_inputtype, $pgroup_parentchain;
	static $i = 0, $limit = 0;
	$options = "";


	$pgroup_istree = (strpos($pgroup_table, "group") === FALSE) ? 0 : 1;
//	pre("m2m_table=[" . pr($m2m_table) . "] m2m_fixed=[" . pr($m2m_fixed) . "] composite=[" . pr($composite) . "] downtable=[$downtable] level=[$level] parent_id=[$parent_id] pgroup_table=[$pgroup_table] product_table=[$product_table] supplier_table=[$supplier_table]  pgroup_levels_display=[" . pr($pgroup_levels_display) . "] level1_column_cnt=[$level1_column_cnt] pgroup_parentchain=[" . pr($pgroup_parentchain) . "]");


//	$query = "select id, ident, published from pgroup where parent_id=$parent_id and published=1 order by " . get_entity_orderby("pgroup");

	// выбирать m2m_ugroup_pgroup m2m.id чтобы определить checked
	$m2m_fixed_cond = "";
	$m2m_select_fields = "";
	$m2m_left_join = "";

//	$make_checkboxed_condition = in_array("pgroup", $composite);
	$make_checkboxed_condition = in_array("ugroup", array_keys($m2m_fixed));
	
	if (entity_present_in_db($m2m_table) && $make_checkboxed_condition) {
		$m2m_fixed_cond = sqlcond_fromhash($m2m_fixed, "m2m", " and ");
		$m2m_select_fields = ", m2m.id as m2m_id";
		$m2m_left_join = " left join $m2m_table m2m on m2m.$pgroup_table=pg.id and m2m.published=1 and m2m.deleted=0" . $m2m_fixed_cond;
	}
	

	if ($pgroup_istree == 1) {
		$query = "select id, ident, published from $pgroup_table"
				. $m2m_select_fields
				. $m2m_left_join
			. " where parent_id=$parent_id and deleted=0"
			. " order by " . get_entity_orderby($pgroup_table);

		$query = "select pg.id, pg.ident, pg.published, count(pg_down.id) as pgroup_down_cnt, count(p.id) as product_cnt"
				. $m2m_select_fields
			. " from $pgroup_table pg"
			. " left outer join $product_table p on p.pgroup=pg.id and p.deleted=0"
			. " left outer join $pgroup_table pg_down on pg_down.parent_id=pg.id and pg_down.deleted=0"
				. $m2m_left_join
			. " where pg.parent_id=$parent_id and pg.deleted=0"
			. " group by pg.id"
			. " order by pg." . get_entity_orderby($pgroup_table);

		$m2m_dependtable = get_m2m_dependtable($product_table, $pgroup_table);
//		pre("entity[$product_table] dependant_entity[$pgroup_table] m2m_dependtable[$m2m_dependtable]");

		if ($m2m_dependtable != "") {
			$query = "select pg.id, pg.ident, pg.published, count(distinct pg_down.id) as pgroup_down_cnt, count(distinct p.id) as product_cnt"
					. $m2m_select_fields
				. " from $pgroup_table pg"
				. " left join $m2m_dependtable m2m_dep on m2m_dep.$pgroup_table=pg.id and m2m_dep.deleted=0"
				. " left join $product_table p on p.id=m2m_dep.$product_table and p.deleted=0"
				. " left outer join $pgroup_table pg_down on pg_down.parent_id=pg.id and pg_down.deleted=0"
					. $m2m_left_join
				. " where pg.parent_id=$parent_id and pg.deleted=0"
				. " group by pg.id"
				. " order by pg." . get_entity_orderby($pgroup_table);
		}

	} else {
		$query = "select id, ident, published from $pgroup_table"
			. " where published=1 and deleted=0"
			. " order by " . get_entity_orderby($pgroup_table);
	}

	$query = add_sql_table_prefix($query);
//	pre($query);
	$result = mysql_query($query, $cms_dbc)
			or die("OPTIONSCOMPOSITECONTENT_PGROUP failed:<br>$query:<br>" . mysql_error($cms_dbc));
	$num_rows = mysql_num_rows($result);

	$it_name = $pgroup_table;

	for ($i=0; $row = mysql_fetch_array($result); $i++) {
//		pre($row);
//		$row["ident"] = preg_replace("/^(\d*\.*)(.*)$/", '$2', $row["ident"]);
		$row["spaces"] = spaces_bylevel($level);
		$row["option_color"] = ($row["published"] == 1) ? OPTIONS_COLOR_BLACK : OPTIONS_COLOR_GRAY;
		$option_value = $row["id"];
		$row["option_value"] = $option_value;
		$row["padding-left"] = $level;

		$pgroup_recursive = 1;

		$tpl = "";
		switch ($composite_inputtype) {
			case "multiselect":
				$tpl = <<< EOT
<option value=0 style="color: #OPTION_COLOR#">#SPACES# #IDENT#</option>

EOT;
				break;
				
			case "multicheckbox":
				$tpl = <<< EOT


<div style="padding-top: 3px; padding-bottom: 3px; padding-left: #PADDING-LEFT#em"><a href="javascript:pgroup_onclick('#OPTION_VALUE#');">#IDENT#</a></div>
EOT;

//analytica товары в узлах и в листьях - навигация в БО
/*
				$row["href_class"] = (get_number($pgroup_table) == $row["id"]) ? "class=cur" : "";
				$tpl = <<< EOT


<div style="padding-top: 3px; padding-bottom: 3px; padding-left: #PADDING-LEFT#em"><a href="product.php?pgroup=#OPTION_VALUE#" #HREF_CLASS#>#IDENT#</a>&nbsp;(#PRODUCT_CNT#)</div>
EOT;
*/
				$tpl = <<< EOT


<div style="padding-top: 3px; padding-bottom: 3px; padding-left: #PADDING-LEFT#em"><a href="javascript:pgroup_onclick('#OPTION_VALUE#')" #HREF_CLASS#>#IDENT#</a>&nbsp;<font color=darkgray>(#PGROUP_DOWN_CNT#)</font></div>
EOT;

				if (
//					$downtable == "m2m_product_fake4navigation"
					$get_down_products == 0
					&& isset($row["pgroup_down_cnt"]) && $row["pgroup_down_cnt"] == 0
					&& isset($row["product_cnt"]) && $row["product_cnt"] > 0
					) {

					$row["href_class"] = (get_number($pgroup_table) == $row["id"]) ? "class=cur" : "";
					$tpl = <<< EOT
<div style="padding-top: 3px; padding-bottom: 3px; padding-left: #PADDING-LEFT#em"><a href="$product_table.php?pgroup=#ID#" #HREF_CLASS#>#IDENT#</a>&nbsp;(#PRODUCT_CNT#)</div>

EOT;
				}

				if ($make_checkboxed_condition) {
					$row["padding-left"] *= 2;
					$ident_href = ($row["pgroup_down_cnt"] > 0)
						? "<a href='javascript:pgroup_onclick(#ID#)'>#IDENT#</a>"
						: "<label for='#OPTION_VALUE#'>#IDENT#</label>";
					$row["checked"] = (isset($row["m2m_id"]) && $row["m2m_id"] > 0) ? "checked" : "";

					$tpl = <<< EOT
<div style="padding-top: 0px; padding-bottom: 0px; padding-left: #PADDING-LEFT#em; clear:both" title="подгрупп: #PGROUP_DOWN_CNT#; товаров: #PRODUCT_CNT#">
	<div style="padding: 3px 10px 3px 3px; display: block; float:left"><a href="$pgroup_table.php?parent_id=#ID#" title="увидеть #PGROUP_DOWN_CNT# подгрупп">#PGROUP_DOWN_CNT#</a>:<a href="$product_table.php?$pgroup_table=#ID#" title="увидеть #PRODUCT_CNT# товаров">#PRODUCT_CNT#</a></div>
	<input type="checkbox" name="{$it_name}[]" value="#OPTION_VALUE#" id="#OPTION_VALUE#" #CHECKED# style="float:left">
	<div style="padding: 3px 3px 3px 3px; float:left">$ident_href</div>
	<div style="padding: 3px #PADDING-LEFT#em 3px 3px; float:right; text-align:right; color:#AAAAAA">подгрупп: #PGROUP_DOWN_CNT#; товаров: #PRODUCT_CNT#</div>
</div>


EOT;
				}


/*
// besttrade
				if ($make_checkboxed_condition) {
					$row["padding-left"] *= 2;
					$ident_href = ($row["pgroup_down_cnt"] > 0)
						? "<a href='javascript:pgroup_onclick(#ID#)'>#IDENT#</a>"
						: "<label for='#OPTION_VALUE#'>#IDENT#</label>";
					$row["checked"] = (isset($row["m2m_id"]) && $row["m2m_id"] > 0) ? "checked" : "";

					$tpl = <<< EOT
				} else {
					$row["padding-left"] *= 2;
					$ident_href = ($row["pgroup_down_cnt"] > 0)
						? "<a href='javascript:pgroup_onclick(#ID#)'>#IDENT#</a>"
						: "<label for='#OPTION_VALUE#'>#IDENT#</label>";
					$row["checked"] = (isset($row["m2m_id"]) && $row["m2m_id"] > 0) ? "checked" : "";

					$tpl = <<< EOT
<div style="padding-top: 0px; padding-bottom: 0px; padding-left: #PADDING-LEFT#em; clear:both" title="подгрупп: #PGROUP_DOWN_CNT#; товаров: #PRODUCT_CNT#">
	<div style="padding: 3px 10px 3px 3px; display: block; float:left"><a href="$pgroup_table.php?parent_id=#ID#" title="увидеть #PGROUP_DOWN_CNT# подгрупп">#PGROUP_DOWN_CNT#</a>:<a href="$product_table.php?$pgroup_table=#ID#" title="увидеть #PRODUCT_CNT# товаров">#PRODUCT_CNT#</a></div>
	<a href="javascript:pgroup_onclick('#OPTION_VALUE#')" #HREF_CLASS#>#IDENT# (#M2M_CHECKED_CNT#)</a>

	<div style="padding: 3px 3px 3px 3px; float:left">$ident_href</div>
	<div style="padding: 3px #PADDING-LEFT#em 3px 3px; float:right; text-align:right; color:#AAAAAA">подгрупп: #PGROUP_DOWN_CNT#; товаров: #PRODUCT_CNT#</div>
</div>


EOT;
				}

*/


/* overwrite something...				
				$tpl = <<< EOT
<div style="padding-top: 3px; padding-bottom: 3px; padding-left: #PADDING-LEFT#em"><a href="$product_table.php?pgroup=#ID#" #HREF_CLASS#>#IDENT#</a></div>

EOT;
*/
				break;

//				$pgroup_recursive = 0;
				break;
				
			default:
				$tpl = "optionscompositecontent_pgroupfromroot(): error defining composite_inputtype [$composite_inputtype]";
				break;
		}

		if ($pgroup_recursive == 1) {
//			$row["option_color"] = OPTIONS_COLOR_GRAY;
			$options .= hash_by_tpl($row, $tpl);

			$pgroup_recursive = "";
			$style_display = (isset($pgroup_levels_display[$level])) ? "block" : "none";
			
			if ($pgroup_istree == 1) {
				$pgroup_recursive = optionscompositecontent_pgroupfromroot($m2m_table, $m2m_fixed, $composite,
								$downtable, $level + 1, $row["id"],
								$pgroup_table, $product_table, $supplier_table,
								$pgroup_levels_display, $level1_column_cnt, $get_down_products);
	
				if (count($pgroup_parentchain) > 0) {
					if (in_array($option_value, $pgroup_parentchain)) $style_display = "block";
				} else {
					// для раскрытия веток дерева (работет только для m2m_ugroup_pgroup, может конечно мешать...)
					// для m2m_news_product надо ещё постараться с $pgroup_parentchain - см. выше
//if (isset($_POST[$it_name]) && !is_array($_POST[$it_name])) pre("!array($it_name): " . pr($_POST[$it_name]));

					if (isset($_POST[$it_name])) {
						if (is_array($_POST[$it_name])) {
							if (in_array($row["id"], $_POST[$it_name])) $style_display = "block";
						} else {
							if ($row["id"] == $_POST[$it_name]) $style_display = "block";
						}
					}

				}
			}

			if ($pgroup_recursive != "") {
				switch ($composite_inputtype) {
					case "multiselect":
						$options .= $pgroup_recursive;
						break;
						
					case "multicheckbox":
						$options .= <<< EOT
<div style="display: $style_display" id="pgroup_$option_value">$pgroup_recursive</div>

EOT;
						break;
				}
			}


			switch($downtable) {
				case "supplier":
//					$row["PGROUP_RECURSIVE"] = optionscompositecontent_supplierbypgroup(
//									$m2m_table, $m2m_fixed, $composite, "none", $level + 1, $row["id"],
//									$pgroup_table, $product_table, $supplier_table);

					$downtable_options = optionscompositecontent_supplierbypgroup(
									$m2m_table, $m2m_fixed, $composite, "none", $level + 1, $row["id"],
									$pgroup_table, $product_table, $supplier_table);
					if ($composite_inputtype == "multicheckbox") {
						$pgroup_id = $row["id"];
						$downtable_options = <<< EOT
<div style="display: $style_display" id="pgroup_{$pgroup_id}">
$downtable_options
</div>

EOT;
					}
					$options .= $downtable_options;
 					
					break;
	
				case "productbysupplier":
//					$row["PGROUP_RECURSIVE"] = optionscompositecontent_supplierbypgroup(
//									$m2m_table, $m2m_fixed, $composite, "productbysupplier", $level + 1, $row["id"],
//									$pgroup_table, $product_table, $supplier_table);

					$downtable_options = optionscompositecontent_supplierbypgroup(
									$m2m_table, $m2m_fixed, $composite, "productbysupplier", $level + 1, $row["id"],
									$pgroup_table, $product_table, $supplier_table);
					if ($composite_inputtype == "multicheckbox") {
						$pgroup_id = $row["id"];
						$downtable_options = <<< EOT
<div style="display: none" id="pgroup_{$pgroup_id}">

$downtable_options

</div>

EOT;
					}
					$options .= $downtable_options;
					break;
	
				case "product":
				case "pmodel":
				case "ppgoption":
					$downtable_options = "";
					if ($get_down_products == 1) {

/*
					$m2m_dependtable = get_m2m_dependtable($product_table, $pgroup_table);
					if ($m2m_dependtable != "") {
						$m2m_fixed[$pgroup_table] = $product_table;
					}
*/
//	pre("downtable=[$downtable] get_down_products=[$get_down_products] m2m_table=[$m2m_table] m2m_dependtable=[$m2m_dependtable] m2m_fixed=[" . pr($m2m_fixed) . "] ");
						$downtable_options = optionscompositecontent_productbyfixed (
									$m2m_table, $m2m_fixed, $composite, array($pgroup_table => $row["id"]), $level + 1,
										$pgroup_table, $product_table, $supplier_table, $get_down_products);
					}
						
					if ($composite_inputtype == "multicheckbox") {
						$pgroup_id = $row["id"];
						$id_name = ($m2m_table == "m2m_product_fake4navigation") ? "nav_pgroup_$pgroup_id" : "pgroup_$pgroup_id";
						$downtable_options = <<< EOT
<div style="display: $style_display" id="$id_name">

$downtable_options

</div>

EOT;
					}
					$options .= $downtable_options;
					break;
	
				default:
					echo "optionscompositecontent_pgroupfromroot: downtable error [$downtable]";
					break;
			}

			$i++;

			if ($level1_column_cnt > 1 && ($i % ($num_rows / ($level1_column_cnt -1))) == 0) {
				$options .= "</td><td style='padding-left: 1em; padding-right: 1em;'>";
			}

			if ($limit > 0 && $i >= $limit) break;
		} else {
			$options .= hash_by_tpl($row, $tpl);
		}

	}

	return $options;
}


function optionscompositecontent_supplierbypgroup($m2m_table, $m2m_fixed, $composite,
	$downtable = "", $level, $pgroup_id,
	$pgroup_table = "pgroup", $product_table = "product", $supplier_table = "supplier") {

	global $cms_dbc, $composite_inputtype;
	$options = "";

	static $i = 0, $limit = 0;
	if ($limit > 0 && ++$i >= $limit) return;

	$m2m_fixed_cond = sqlcond_fromhash($m2m_fixed);

	$it_name = composite_itname($composite);


	$query = "select pg.id as pgroup, s.id as supplier, s.ident, s.published"
		. " from $supplier_table s"
		. " inner join $pgroup_table pg on p.$pgroup_table=pg.id"
		. " inner join $product_table p on p.$supplier_table=s.id"
		. " where pg.id=$pgroup_id"
		. " group by s.id"
		. " order by s." . get_entity_orderby($supplier_table);

	if (get_number("p") == 1) {
	$query = "select s.id as supplier, p.pgroup as pgroup, s.ident, s.published, count(p.id) as pcnt"
		. " from product p"
		. " inner join supplier s on p.supplier=s.id"
//		. " left outer join on "
		. " where p.pgroup=$pgroup_id"
		. " group by s.id"
		. " order by s." . get_entity_orderby("supplier");
}
		
//	echo "$query<br>";

	$query = add_sql_table_prefix($query);
	$result = mysql_query($query, $cms_dbc)
			or die("SELECT OPTIONSCOMPOSITECONTENT_SUPPLIER failed:<br>$query:<br>" . mysql_error($cms_dbc));
	$size = mysql_num_rows($result);

	while ($row = mysql_fetch_array($result)) {
//		$row["ident"] .= " (". $row["pcnt"] . " продуктов)";
//		$is_concurent = $row["is_concurent"];
//		$is_concurent_txt = 

		$row["option_color"] = ($row["published"] == '1') ? OPTIONS_COLOR_AHREF : OPTIONS_COLOR_GRAY;

		if (isset($row["is_concurent"])) {
			$row["ident"] .= ($row["is_concurent"] == 1) ? " (конкурент)" : "";
			switch ($row["is_concurent"]) {
				case "0":
					$row["option_color"] = OPTIONS_COLOR_GREEN;
					break;
				case "1":
					$row["option_color"] = OPTIONS_COLOR_MAROON;
					break;
				default:
					$row["option_color"] = OPTIONS_COLOR_GRAY;
					break;
			}
		}

		$row["selected"] = "";
		$row["checked"] = "";
		$row["option_value"] = 0;
		$row["spaces"] = spaces_bylevel($level);
		$row["padding-left"] = $level;
		$row["supplier_content"] = "";

		$tpl = "";
		switch ($composite_inputtype) {
			case "multiselect":
				$tpl = "<option value='#OPTION_VALUE#' #SELECTED# #PUBLISHED#>#SPACES# #IDENT#</option>\n#SUPPLIER_CONTENT#";
				break;
				
			case "multicheckbox":

				pre("downtable=[$downtable]");
				if ($downtable == "none") {
//					echo "$m2m_table";
					if ($m2m_table == "m2m_product_fake4navigation") {
						$downtable = "downtable_fake4navigation";
						$fixed_href = "$pgroup_table=" . $row[$pgroup_table];

						$tpl = <<< EOT
<div style="padding-left: #PADDING-LEFT#em"><a href="$product_table.php?$fixed_href&$it_name=#OPTION_VALUE#" style="color: #OPTION_COLOR#">#IDENT#</a></div>

EOT;

						if ($options == "") {
							$tpl_anysupplier = <<< EOT
<div style="padding-left: #PADDING-LEFT#em"><a href="$product_table.php?$fixed_href&$it_name=0" style="color: #OPTION_COLOR#">--- все ---</a></div>

EOT;
							$row["option_color"] = (get_number($supplier_table) == 0 && get_number($pgroup_table) == $pgroup_id)
													? OPTIONS_COLOR_BROWN : OPTIONS_COLOR_GREEN;
							$options .= hash_by_tpl($row, $tpl_anysupplier);
						}

					} else {
						$tpl = <<< EOT
<div style="padding-top: 3px; padding-bottom: 3px; padding-left: #PADDING-LEFT#em">
	<input type="checkbox" name="{$it_name}[]" value="#OPTION_VALUE#" id="#OPTION_VALUE#" #CHECKED#>
	<label for="#OPTION_VALUE#" style="color: #OPTION_COLOR#">#IDENT#</label>
</div>

EOT;
					}
				} else {
					$tpl = <<< EOT
<div style="padding-top: 3px; padding-bottom: 3px; padding-left: #PADDING-LEFT#em">
	<a href="javascript:supplier_onclick('#OPTION_VALUE#');">#IDENT#</a>
</div>

<div style="display: none" id="supplier_#OPTION_VALUE#">#SUPPLIER_CONTENT#</div>

EOT;
				}
				
				break;
				
			default:
				$tpl = "optionscompositecontent_supplierbypgroup(): error defining composite_inputtype [$composite_inputtype]";
				break;
		}

		switch($downtable) {
			case "downtable_fake4navigation":
				$downtable = "none";
				$row["option_value"] = composite_itvalue($composite, $row);
				$row["option_color"] = (get_number($supplier_table) == $row[$supplier_table] && get_number($pgroup_table) == $pgroup_id)
					? OPTIONS_COLOR_BROWN : OPTIONS_COLOR_GREEN;

				$options .= hash_by_tpl($row, $tpl);
				$i++;
				if ($limit > 0 && $i >= $limit) break;
		
				break;


			case "none":
				$row["option_value"] = composite_itvalue($composite, $row);
				
				$joinon = "";
				foreach($composite as $value) {
					if ($joinon != "") $joinon .= " and ";
					$joinon .= "$value=" . $row[$value];
				}
		
				$query2 = "select m2m.id from $m2m_table m2m where deleted=0 and"
						. " $m2m_fixed_cond"
						. " and $joinon";
//				echo "$query2<br>";
		
				$query2 = add_sql_table_prefix($query2);
				$result2 = mysql_query($query2, $cms_dbc)
						or die("SELECT PRODUCT_SUPPLIER failed:<br>$query2:<br>" . mysql_error($cms_dbc));
				if (mysql_num_rows($result2) > 0) {
					$row["selected"] = "selected";
					$row["checked"] = "checked";
				}
			
				$options .= hash_by_tpl($row, $tpl);

				$i++;
				if ($limit > 0 && $i >= $limit) break;
		
				break;

			case "productbysupplier":
				$i++;
				if ($limit > 0 && $i >= $limit) break;

				$fixed_hash = array($supplier_table => $row[$supplier_table], $pgroup_table => $row[$pgroup_table]);

				$row["published"] = "style='color: " . OPTIONS_COLOR_GRAY . "'";
				$row["ident"] = "<font style='color: " . $row["option_color"] . "'>" . $row["ident"] . "</font>";


				if ($composite_inputtype == "multicheckbox") {
					$row["option_value"] = $row[$pgroup_table] . "_" . $row[$supplier_table];
				}
				$row["supplier_content"]= optionscompositecontent_productbyfixed(
						$m2m_table, $m2m_fixed, $composite, $fixed_hash, $level + 1);

				$options .= hash_by_tpl($row, $tpl);

				break;

			default:
				echo "";
				break;
		}

	}
	
	return $options;
}







function multicompositecontent_update($m2m_table, $value_arr, $composite = array("supplier", "pgroup"), $absorbing_fixedhash = array("_global:entity" => "_global:id")) {
	global $entity, $id, $debug_query, $cms_dbc;
	$ret = "";

//	pre($value_arr);

	$absorbed_fixedhash = absorb_fixedhash($absorbing_fixedhash);
//	pre($absorbed_fixedhash);

	$composite_db_fields = "";
	foreach($composite as $field_name) {
		if ($composite_db_fields != "") $composite_db_fields .= ", ";
		$composite_db_fields .= $field_name;
	}
	
	$query = "select id, deleted, $composite_db_fields from $m2m_table where $entity=$id";
	$query = add_sql_table_prefix($query);
	$result = mysql_query($query, $cms_dbc) or die("SELECT4DELETE MULTICOMPOSITECONTENT_UPDATE failed:<br>$query<br>" . mysql_error($cms_dbc));
	while ($row = mysql_fetch_assoc($result)) {
		$m2m_id = $row["id"];
		$m2m_deleted = $row["deleted"];

		$composite_db_value = "";
		foreach($composite as $field_name) {
			if ($composite_db_value != "") $composite_db_value .= "_";
			$composite_db_value .= $row[$field_name];
		}

		//search key in form value that already exists in db
		$value_array_key = array_search($composite_db_value, $value_arr); 

		if ($value_array_key === FALSE) {
//			echo "deleting $m2m_id [$composite_db_value] ";
// not selected in form, should be deleted
			$update_hash = array("deleted" => 1);
//			if (entity_has_field($m2m_table, "site_updated")) $update_hash["site_updated"] = 1;
			update ($update_hash, array("id" => $m2m_id), $m2m_table);
		} else {
// selected in form and present in db, restore deleted
			if ($m2m_deleted == 1) {
//				echo "restoring deleted $m2m_id [$composite_db_value] ";
				$update_hash = array("deleted" => 0);
//				if (entity_has_field($m2m_table, "site_updated")) $update_hash["site_updated"] = 1;
				update ($update_hash, array("id" => $m2m_id), $m2m_table);
			}
// no need to insert again value presented in db (a valid or restored value)
			unset($value_arr[$value_array_key]);
		}
	}
	
	foreach($value_arr as $form_value) {
		if ($form_value == "0") continue;
//		echo "inserting [$form_value] ";

		$composite_insert_value = split ("_", $form_value);

		$composite_insert_hash = array();
		for($i=0; $i<sizeof($composite); $i++) {
			$composite_insert_hash[$composite[$i]] = $composite_insert_value[$i];
		}
//		print_r($composite_insert_hash);

		$insert_base = array("date_created" => "CURRENT_TIMESTAMP");
		$insert_hash = array_merge($insert_base, $absorbed_fixedhash, $composite_insert_hash);
//		echo "<pre>";
//		print_r($insert_hash);
//		echo "</pre>";
		insert($insert_hash, $m2m_table);

	}

//	print_r($value_arr);

}



function composite_itname($composite) {
	$it_name = "";
	foreach($composite as $value) {
		if ($it_name != "") $it_name .= "_";
		$it_name .= $value;
	}
	return $it_name;
}

function composite_itvalue($composite, $row) {
	$option_value = "";
	foreach($composite as $value) {
		if (isset ($row[$value])) {		// ppgoption in product has product which is redundant?..
			if ($option_value != "") $option_value .= "_";
			$option_value .= $row[$value];
		}
	}
	if ($option_value == "") $option_value = 0;
	return $option_value;
}


?>