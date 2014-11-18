<?

$composite_inputtype = "multiselect";
$composite_inputtype = "multicheckbox";

function multicompositebidirect ($compositetype = "supplierbypgroup",
	$m2m_table = "m2m_sreptask_compositecontent", $composite = array("supplier", "pgroup"),
	$parent_id = 1, $html_table_width = 600,
	$pgroup_table = "pgroup", $product_table = "product", $supplier_table = "supplier",
	$pgroup_levels_display = array(), $level1_column_cnt = 1) {


	global $entity, $id, $cms_dbc, $composite_inputtype, $m2m_sameentity_displaymode_default, $m2m_sameentity_displaymode_nolinks, $debug_query;
	global $msg_bo_select_ctrl_shift;

	$ret = "";
	$options = "";
	
//	return "multicompositebidirect ";

	if (isset($id)) {
		$m2m_fixed = array($entity => $id);

		switch ($compositetype) {
			case "pgrouptreeproductselectable_1":
			case "pgrouptreeproductselectable_2":
			case "pgrouptreeproductselectable_3":
				$m2m_fixed = array($entity . "_from" => $id);

				if (!entity_present_in_db($m2m_table)) {
					pre("m2m_table=[$m2m_table] should present in DB, no fake-m2m mode for options_pgrouptreeproductselectable()");
					return $ret;
				}
		
				$m2m_dependtable = get_m2m_dependtable($product_table, $pgroup_table);
//				pre("entity[$product_table] dependant_entity[$pgroup_table] m2m_dependtable[$m2m_dependtable]");
		
				if ($m2m_dependtable == "") {
					pre("product <-> pgroup should be m2m-linked for this type (pgrouptreeproductselectable)");
					return $ret;
				}
		
// получить группы связанных товаров, чтобы выбирать для отрисовки только эти группы
				$pgroup_restricted_by_p2p_relation_hash = array();
		
// как минимум, должно быть $m2m_fixed["product_from"] = 1
				$m2m_fixed_cond = sqlcond_fromhash($m2m_fixed, "m2m", " and ");
				$query = "select pg.id, pg.ident, pg.published"
					. ", count(distinct pg_down.id) as pgroup_down_cnt, count(distinct p.id) as product_cnt"
					. " from $pgroup_table pg"
					. " right join $m2m_dependtable m2m_dep on m2m_dep.$pgroup_table=pg.id and m2m_dep.deleted=0"
					. " right join $product_table p on p.id=m2m_dep.$product_table and p.deleted=0"
					. " right join $m2m_table m2m on m2m.published=1 and m2m.deleted=0 and m2m.product_to=p.id $m2m_fixed_cond"

					. " left outer join $pgroup_table pg_down on pg_down.parent_id=pg.id and pg_down.deleted=0"
					. " where pg.deleted=0"
					. " group by p.id"
					. " order by pg." . get_entity_orderby($pgroup_table);
		
//				$query = add_sql_table_prefix($query);
//				$qa = select_queryarray($query);
//				pre($qa);
		
				$query = add_sql_table_prefix($query);
				if ($debug_query == 1) echo "<br>SELECT SELECT_MULTICOMPOSITEBIDIRECT[$query]<br>";
				$result = mysql_query($query, $cms_dbc)
						or die("OPTIONS_PGROUPTREEPRODUCTSELECTABLE failed:<br>$query:<br>" . mysql_error($cms_dbc));
		
				while ($row = mysql_fetch_array($result)) {
					$pgroup_restricted_by_p2p_relation_hash[$row["id"]] = $row["product_cnt"];
				}
//				pre($pgroup_restricted_by_p2p_relation_hash);
	
				$displaymode = get_string($m2m_table . "_displaymode");
				if ($displaymode == "" && count($pgroup_restricted_by_p2p_relation_hash) == 0) $displaymode = $m2m_sameentity_displaymode_nolinks;
				if ($displaymode == "") $displaymode = $m2m_sameentity_displaymode_default;
				if ($displaymode == "tree") {
					$options = options_pgrouptreeproductselectable (
						$m2m_table, $m2m_fixed, $composite, "supplier", 1, $parent_id,
						$pgroup_table, $product_table, $supplier_table,
						$pgroup_levels_display, $level1_column_cnt, $pgroup_restricted_by_p2p_relation_hash);
				} else {
					$options = options_pgroupflatproductselectable (
						$m2m_table, $m2m_fixed, $composite, "supplier", 1, $parent_id,
						$pgroup_table, $product_table, $supplier_table,
						$pgroup_levels_display, $level1_column_cnt, $pgroup_restricted_by_p2p_relation_hash);
				}

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
				. "<font color=" . OPTIONS_COLOR_GRAY . ">$msg_bo_select_ctrl_shift</font>"
				. "</td></tr></table>";
			break;
			
		case "multicheckbox":
			if ($html_table_width != 0) $html_table_width = " width='$html_table_width'";
			$ret = "<table cellpadding=0 cellspacing=0 style='border:1 solid " . OPTIONS_COLOR_GRAY . "' $html_table_width><tr valign=top><td style='padding-left: 1em; padding-right: 1em;'>$options</td></tr></table>";
			break;
			
		default:
			$ret = "multicompositecontent(): error defining composite_inputtype [$composite_inputtype]";
			break;
	}
	return $ret;
}




function options_pgrouptreeproductselectable($m2m_table, $m2m_fixed, $composite,
	$downtable = "", $level = 1, $parent_id = 0,
	$pgroup_table = "pgroup", $product_table = "product", $supplier_table = "supplier",
	$pgroup_levels_display = array(), $level1_column_cnt = 1, $pgroup_restricted_by_p2p_relation_hash = array()) {

	global $entity, $id, $cms_dbc, $composite_inputtype, $pgroup_parentchain;
	global $msg_bo_subgroup_qnty, $msg_bo_products_in_subgroup, $msg_bo_products_selected, $msg_bo_products_in_subgroup_selected, $msg_bo_products_in_subgroup, $msg_bo_go_product_editing;
	
	static $total_pgroup_cnt = 0, $product_frequency_hash = array(), $product_frequency_checked_hash = array();
//	static $layer_prefix = rand(100, 999);
	static $layer_serialno = 0;
	$layer_serialno_name = "";

	$ret = "";

//	pre("options_pgrouptreeproductselectable m2m_table=[" . pr($m2m_table) . "] m2m_fixed=[" . pr($m2m_fixed) . "] composite=[" . pr($composite) . "] downtable=[$downtable] level=[$level] parent_id=[$parent_id] pgroup_table=[$pgroup_table] product_table=[$product_table] supplier_table=[$supplier_table]  pgroup_levels_display=[" . pr($pgroup_levels_display) . "] level1_column_cnt=[$level1_column_cnt] pgroup_parentchain=[" . pr($pgroup_parentchain) . "] pgroup_restricted_by_p2p_relation_hash=[" . pr($pgroup_restricted_by_p2p_relation_hash) . "]");


	$ret = "";
	$overall_products_checked = 0;

	$m2m_fixed_cond = sqlcond_fromhash($m2m_fixed, "m2m", " and ");

	$query = "select pg.id, pg.ident, pg.published, count(pg_down.id) as pgroup_down_cnt, count(p.id) as product_cnt"
		. " from $pgroup_table pg"
		. " left outer join $product_table p on p.pgroup=pg.id and p.deleted=0"
		. " left outer join $pgroup_table pg_down on pg_down.parent_id=pg.id and pg_down.deleted=0"
		. " where pg.parent_id=$parent_id and pg.deleted=0"
		. " group by pg.id"
		. " order by pg." . get_entity_orderby($pgroup_table)
		. " limit 3"
		;


	$m2m_dependtable = get_m2m_dependtable($product_table, $pgroup_table);
//	pre("entity[$product_table] dependant_entity[$pgroup_table] m2m_dependtable[$m2m_dependtable]");

	if ($m2m_dependtable != "") {
		$query = "select pg.id, pg.ident, pg.published, count(distinct pg_down.id) as pgroup_down_cnt, count(distinct p.id) as product_cnt"
			. ", count(distinct m2m.id) as product_m2mlinked_cnt"
			. " from $pgroup_table pg"
			. " left outer join $pgroup_table pg_down on pg_down.parent_id=pg.id and pg_down.deleted=0"
			. " left join $m2m_dependtable m2m_dep on m2m_dep.$pgroup_table=pg.id and m2m_dep.deleted=0"
			. " left join $product_table p on p.id=m2m_dep.$product_table and p.deleted=0"
			. " left join $m2m_table m2m on m2m.published=1 and m2m.deleted=0 and m2m.product_to=p.id $m2m_fixed_cond"
			. " where pg.parent_id=$parent_id and pg.deleted=0"
			. " group by pg.id"
			. " order by pg." . get_entity_orderby($pgroup_table)
//			. " limit 5"
			;
	}

	$query = add_sql_table_prefix($query);
//	pre($query);
	$result = mysql_query($query, $cms_dbc)
			or die("OPTIONS_PGROUPTREEPRODUCTSELECTABLE failed:<br>$query:<br>" . mysql_error($cms_dbc));
	$num_rows = mysql_num_rows($result);

	for ($i=0; $row = mysql_fetch_array($result); $i++) {
//		pre($row);
		$pgroup_id = $row["id"];
		$row["option_color"] = ($row["published"] == 1) ? OPTIONS_COLOR_BLACK : OPTIONS_COLOR_GRAY;
		$row["option_value"] = $pgroup_id;
		$row["padding-left"] = $level * 2;

		$row["product_m2mlinked_cnt"] = isset($pgroup_restricted_by_p2p_relation_hash[$pgroup_id])
			? isset($pgroup_restricted_by_p2p_relation_hash[$pgroup_id]) : 0;

//		$ident_href = ($row["pgroup_down_cnt"] > 0 || $row["product_m2mlinked_cnt"] > 0)
		$ident_href = "#IDENT#";
		if ($row["pgroup_down_cnt"] > 0 || $row["product_cnt"] > 0) {
			$layer_serialno++;
			$layer_serialno_name = "layer_${m2m_table}_${layer_serialno}";

			$href_pgroup_onclick = <<< EOT
<a href="javascript:layer_onclick('layer_${m2m_table}_${layer_serialno}')">#IDENT#</a>
EOT;

			$ident_href = $href_pgroup_onclick;
		};

		$m2mlinked_label = ($row["product_m2mlinked_cnt"] > 0) ? "связанных товаров: #PRODUCT_M2MLINKED_CNT#" : "";

/*
		$tpl = <<< EOT
<div style="padding: 0px 0px 0px #PADDING-LEFT#em; clear:both" title="группа #ID# $m2mlinked_label">
	<div style="padding: 3px 10px 3px 3px; display: block; float:left">#PRODUCT_M2MLINKED_CNT#</div>
	<div style="padding: 3px 3px 3px 3px; float:left">$ident_href</div>
	<div style="padding: 3px #PADDING-LEFT#em 3px 3px; float:right; text-align:right; color:#AAAAAA">$m2mlinked_label</div>
</div>


EOT;
*/

		$row["pgroup_down_cnt_colorized"] = ($row["pgroup_down_cnt"] > 0)
				? "<font color=" . OPTIONS_COLOR_GRAY . ">" . $row["pgroup_down_cnt"] . "</font>"
				: "<font color=" . OPTIONS_COLOR_GRAY . ">" . $row["pgroup_down_cnt"] . "</font>";

		$row["product_cnt_colorized"] = ($row["product_cnt"] > 0)
				? "<font color=" . OPTIONS_COLOR_MAROON . ">" . $row["product_cnt"] . "</font>"
				: "<font color=" . OPTIONS_COLOR_GRAY . ">" . $row["product_cnt"] . "</font>";

		$row["product_m2mlinked_cnt_colorized"] = ($row["product_m2mlinked_cnt"] > 0)
				? "<font color=" . OPTIONS_COLOR_ORANGE . ">" . $row["product_m2mlinked_cnt"] . "</font>"
				: "<font color=" . OPTIONS_COLOR_GRAY . ">" . $row["product_m2mlinked_cnt"] . "</font>";


		$legend = <<< EOT
	<div style="padding: 3px #PADDING-LEFT#em 3px 3px; float:right; text-align:right; color:#AAAAAA">$msg_bo_subgroup_qnty: #PGROUP_DOWN_CNT#; $msg_bo_products_in_subgroup: #PRODUCT_CNT#; $msg_bo_products_selected: #PRODUCT_M2MLINKED_CNT#</div>

EOT;
		$legend = "";

		
		$tpl = <<< EOT
<div style="clear:both" title="$msg_bo_subgroup_qnty: #PGROUP_DOWN_CNT#; $msg_bo_products_in_subgroup: #PRODUCT_CNT#;
$msg_bo_products_in_subgroup_selected: #PRODUCT_M2MLINKED_CNT#">
	<div style="padding: 3px 10px 3px 3px; display: block; float:left">#PGROUP_DOWN_CNT_COLORIZED#:#PRODUCT_CNT_COLORIZED#:#PRODUCT_M2MLINKED_CNT_COLORIZED#</div>
	<div style="padding: 3px 3px 3px 3px; float:left">$ident_href</div>
	$legend
</div>


EOT;

		$ret .= hash_by_tpl($row, $tpl);

		$downtable_products = "";
		$pgroup_recursive = "";

//		if (in_array($pgroup_id, array_keys($pgroup_restricted_by_p2p_relation_hash))) {
//			$m2m_fixed_product = array($product_table . "_from" => $m2m_fixed[$product_table . "_from"]);
//			$downtable_products = optionscompositecontent_productbyfixed (
//					$m2m_table, $m2m_fixed_product, $composite, array($pgroup_table => $pgroup_id), $level + 1,
//					$pgroup_table, $product_table, $supplier_table, 1);
//		}


		if ($row["pgroup_down_cnt"] > 0) {
			$total_pgroup_cnt += $row["pgroup_down_cnt"];
			$pgroup_recursive = options_pgrouptreeproductselectable($m2m_table, $m2m_fixed, $composite,
							$downtable, $level + 1, $pgroup_id,
							$pgroup_table, $product_table, $supplier_table,
							$pgroup_levels_display, $level1_column_cnt, $pgroup_restricted_by_p2p_relation_hash);
		}		

		if ($row["product_cnt"] > 0) {
// как минимум, должно быть $m2m_fixed["product_from"] = 1
			$m2m_fixed_cond = sqlcond_fromhash($m2m_fixed, "m2m", " and ");
	
			$query_product = "select p.id, p.ident, p.article, p.published, m2m.id as m2m_id"
				. " from $product_table p"
				. " inner join $m2m_dependtable m2m_dep on m2m_dep.$product_table=p.id and m2m_dep.deleted=0"
				. " left join $m2m_table m2m on m2m.published=1 and m2m.deleted=0 and m2m.product_to=p.id $m2m_fixed_cond"
				. " where m2m_dep.$pgroup_table=$pgroup_id and p.deleted=0"
				. " group by p.id"
				. " order by p." . get_entity_orderby($product_table);
			$query_product = add_sql_table_prefix($query_product);
			$result_product = mysql_query($query_product, $cms_dbc)
					or die("SELECT OPTIONS_PGROUPTREEPRODUCTSELECTABLE PRODUCTBYFIXED failed:<br>$query:<br>" . mysql_error($cms_dbc));
	
			$fixed_hrefsuffix = "";
			$it_name = $m2m_table . "_" . $product_table . "_to";

			global $layers_total, $layer_inside;
			$openlayer = ($layer_inside == 1) ? "&layer_opened_nr=$layers_total" : "";
			$ahref_rarr_suffix = "{$m2m_table}_displaymode=tree{$openlayer}#layer_{$m2m_table}";

	
			$color_brown = OPTIONS_COLOR_BROWN;
			$tpl_product = <<< EOT
<div style="clear:both" title="product[id]=#ID#     #M2M_MESSAGE#">
	<div style="float:left">
		<input type="checkbox" name="{$it_name}[]" value="#OPTION_VALUE#" id="${product_table}#OPTION_VALUE#_${pgroup_table}#PGROUP#{$fixed_hrefsuffix}" #checked# #disabled#>
		<label for="${product_table}#OPTION_VALUE#_${pgroup_table}#PGROUP#{$fixed_hrefsuffix}" style="color: #OPTION_COLOR#"><font color="$color_brown">[#ARTICLE#]</font> #IDENT#</label>
	</div>
	<div style="float:right; text-align:right"><a href="${product_table}-edit.php?id=#ID#&{$ahref_rarr_suffix}" title="$msg_bo_go_product_editing
#IDENT#">&rarr;</a></div>
</div>

EOT;


			while ($row_product = mysql_fetch_array($result_product)) {
	//			pre($row_product);
				$row_product["option_color"] = ($row_product["published"] == "1") ? OPTIONS_COLOR_BLACK : OPTIONS_COLOR_GRAY;
				$row_product["option_color"] = ($row_product["id"] == $id) ? OPTIONS_COLOR_GRAY : $row_product["option_color"];
				$row_product["checked"] = ($row_product["m2m_id"] != "") ? "checked" : "";
				$row_product["disabled"] = ($row_product["m2m_id"] != "") ? "disabled" : "";
				$row_product[$product_table] = $row_product["id"];
				$row_product["option_value"] = composite_itvalue($composite, $row_product);
				$row_product["m2m_message"] = ($row_product["m2m_id"] > 0) ? "m2m_id=" . $row_product["m2m_id"] : "не выбран";
				$row_product["disabled"] = ($row_product["id"] == $id) ? "disabled" : "";
				$row_product["pgroup"] = $row["id"];

				$downtable_products .= hash_by_tpl($row_product, $tpl_product);

				$product_id = $row_product["id"];
				if ($row_product["m2m_id"] != "") {
					if (!isset($product_frequency_checked_hash[$product_id])) $product_frequency_checked_hash[$product_id] = 0;
					$product_frequency_checked_hash[$product_id]++;
				}
	
				if (!isset($product_frequency_hash[$product_id])) $product_frequency_hash[$product_id] = 0;
				$product_frequency_hash[$product_id]++;
			}

		}

		if ($pgroup_recursive != "" || $downtable_products != "") {
//			$style_display = (isset($pgroup_levels_display[$level])) ? "block" : "none";
			$style_display = "none";
//			$style_display = ($downtable_products != "" && $level > 1) ? "block" : "none";
			$padding_left_pgroup = 2;
			$padding_left_product = 2;

			$drill_down_data = <<< EOT
<div style="display: $style_display; clear: both" id="${layer_serialno_name}">
	<div style="padding-left: ${padding_left_pgroup}em; clear: both">
	$pgroup_recursive
	</div>

	<div style="padding-left: ${padding_left_product}em; clear: both">
	$downtable_products
	</div>
</div>

EOT;
			$ret .= $drill_down_data;
		}

	}

	$color_orange = OPTIONS_COLOR_ORANGE;
	$color_olive = OPTIONS_COLOR_OLIVE;

	if ($level == 1) {
		$total_product_cnt = 0;
		$unique_product_cnt = 0;
		foreach ($product_frequency_hash as $product_id => $pgroup_cnt) {
			$total_product_cnt += $pgroup_cnt;
		}
		$unique_product_cnt = count(array_keys($product_frequency_hash));
		
		$total_product_checked_cnt = 0;
		$unique_product_checked_cnt = 0;
		foreach ($product_frequency_checked_hash as $product_id => $pgroup_cnt) {
			$total_product_checked_cnt += $pgroup_cnt;
		}
		$unique_product_checked_cnt = count(array_keys($product_frequency_checked_hash));

		global $layers_total, $layer_inside;
		$openlayer = ($layer_inside == 1) ? "&layer_opened_nr=$layers_total" : "";
		$ahref_switch_displaymode = "{$entity}-edit.php?id={$id}&{$m2m_table}_displaymode=flat{$openlayer}#layer_{$m2m_table}";

		$ret = <<< EOT
<!--a name="$m2m_table"></a-->
<div style="padding: 8px 0px 10px 0px; clear:both">
	<div style="padding: 0px 10px 0px 3px; display: block; float:left" title="[уникальные товары] - это количество артикулов в ассортименте (т.е. чистая номенклатура)
[товары по всем группам] - это сумма всех товаров, присутствующих в каждой группе

из-за того, что один и тот же товар может присутствовать в нескольких группах одновременно, [уникальных товаров] меньше, чем сумма товаров по всему каталогу">Выбрано товаров: <font color="$color_olive">$unique_product_checked_cnt уникальных</font> <font color="$color_orange">($total_product_checked_cnt по всем группам)</font></div>
	<div style="padding: 0px 0em 0px 3px; float:right; text-align:right"><a href="$ahref_switch_displaymode" title="СОХРАНИТЕ ИЗМЕНИЯ, если Вы их производили на этой странице

при переключении внешнего вида (нажатии на данную ссылку) производится перечитывание сохранённых ранее данных, внесённых на этой странице">отвязать элементы</a> | <a href="javascript:layer_switch_enumerated('layer_${m2m_table}_', $layer_serialno)" title="если вы долго искали нужный элемент каталога и не нашли, то после клика каталог раскроется полностью и вы сможете поискать нужный артикул или название через Ctrl-F">раскрыть всё (Ctrl-F)</a></div>
</div>
<hr style="clear:both">

$ret

<hr style="clear:both">
<div style="padding: 0px 0px 20px 0px; clear:both" title="[уникальные товары] - это количество артикулов в ассортименте (т.е. чистая номенклатура)

[товары по всем группам] - это сумма всех товаров, присутствующих в каждой группе

из-за того, что один и тот же товар может присутствовать в нескольких группах одновременно, [уникальных товаров] меньше, чем сумма товаров по всему каталогу">
	<div style="padding: 0px 10px 0px 3px; display: block; float:left"><font color="$color_olive">Уникальных товаров: $unique_product_cnt</font></div>
	<div style="padding: 0px 10px 0px 3px; display: block; float:left"><font color="$color_orange">Товаров по всем группам: $total_product_cnt</font></div>
	<div style="padding: 0px 10px 0px 3px; display: block; float:left">Всего групп в каталоге: $total_pgroup_cnt</div>
</div>

EOT;
	}

	return $ret;
}


function options_pgroupflatproductselectable($m2m_table, $m2m_fixed, $composite,
	$downtable = "", $level = 1, $parent_id = 0,
	$pgroup_table = "pgroup", $product_table = "product", $supplier_table = "supplier",
	$pgroup_levels_display = array(), $level1_column_cnt = 1, $pgroup_restricted_by_p2p_relation_hash = array()) {

	global $entity, $id, $cms_dbc, $composite_inputtype, $pgroup_parentchain;
	global $msg_bo_go_product_editing, $msg_bo_not_selected;
	
	$ret = "";

//	pre("options_pgroupflatproductselectable m2m_table=[" . pr($m2m_table) . "] m2m_fixed=[" . pr($m2m_fixed) . "] composite=[" . pr($composite) . "] downtable=[$downtable] level=[$level] parent_id=[$parent_id] pgroup_table=[$pgroup_table] product_table=[$product_table] supplier_table=[$supplier_table]  pgroup_levels_display=[" . pr($pgroup_levels_display) . "] level1_column_cnt=[$level1_column_cnt] pgroup_parentchain=[" . pr($pgroup_parentchain) . "] pgroup_restricted_by_p2p_relation_hash=[" . pr($pgroup_restricted_by_p2p_relation_hash) . "]");
	

	$m2m_dependtable = get_m2m_dependtable($product_table, $pgroup_table);
// как минимум, должно быть $m2m_fixed["product_from"] = 1
	$m2m_fixed_cond = sqlcond_fromhash($m2m_fixed, "m2m", " and ");
	$query_product = "select p.*, pg.id as pgroup, pg.ident as pgroup_ident, pg.published as pgroup_published, m2m.id as m2m_id"
		. " from $product_table p"
		. " right join $m2m_dependtable m2m_dep on m2m_dep.$product_table=p.id and m2m_dep.deleted=0"
		. " right join $pgroup_table pg on m2m_dep.$pgroup_table=pg.id and pg.deleted=0"
		. " right join $m2m_table m2m on m2m.published=1 and m2m.deleted=0 and m2m.product_to=p.id $m2m_fixed_cond"

//		. " left outer join $pgroup_table pg_down on pg_down.parent_id=pg.id and pg_down.deleted=0"
		. " where pg.deleted=0"
		. " group by p.id"
		. " order by pg." . get_entity_orderby($product_table);

	$query_product = add_sql_table_prefix($query_product);
//	$qa = select_queryarray($query_product);
//	pre($qa);

	$result_product = mysql_query($query_product, $cms_dbc)
			or die("SELECT OPTIONS_PGROUPFLATPRODUCTSELECTABLE failed:<br>$query:<br>" . mysql_error($cms_dbc));

	$fixed_hrefsuffix = "";
	$it_name = $m2m_table . "_" . $product_table . "_to";
	$color_brown = OPTIONS_COLOR_BROWN;

	$tpl_product = <<< EOT
<div style="padding: 0px 10px 0px 0px; clear:both; border: 0px solid red" title="product[id]=#ID#     #M2M_MESSAGE#">
	<div style="padding: 0px 0px 0px 0px; float:left; border: 0px solid green">
		<input type="checkbox" name="{$it_name}[]" value="#OPTION_VALUE#" id="${product_table}_#OPTION_VALUE#_{$fixed_hrefsuffix}" #checked#>
		<label for="${product_table}_#OPTION_VALUE#_{$fixed_hrefsuffix}" style="color: #OPTION_COLOR#"><font color="$color_brown">[#ARTICLE#]</font> #IDENT#</label>
	</div>
	<div style="padding: 4px 0px 0px 0px; float:right; text-align:right; color:#AAAAAA; border: 0px solid blue">#PGROUP_IDENT#</div>
</div>

EOT;

	global $layers_total, $layer_inside;
	$openlayer = ($layer_inside == 1) ? "&layer_opened_nr=$layers_total" : "";
	$ahref_rarr_suffix = "{$m2m_table}_displaymode=tree{$openlayer}#layer_{$m2m_table}";
	$ahref_switch_displaymode = "{$entity}-edit.php?id={$id}&{$m2m_table}_displaymode=tree{$openlayer}#layer_{$m2m_table}";

	$tpl_product = <<< EOT
<tr valign=middle title="product[id]=#ID#     #M2M_MESSAGE#">
	<td><input type="checkbox" name="{$it_name}[]" value="#OPTION_VALUE#" id="${product_table}#OPTION_VALUE#_${pgroup_table}#PGROUP#{$fixed_hrefsuffix}" #checked# #disabled#></td>
	<td></td>
	<td style="color: $color_brown"><label for="${product_table}#OPTION_VALUE#_${pgroup_table}#PGROUP#{$fixed_hrefsuffix}">[#ARTICLE#]</td>
	<td></td>
	<td style="color: #OPTION_COLOR#"><label for="${product_table}#OPTION_VALUE#_${pgroup_table}#PGROUP#{$fixed_hrefsuffix}">#IDENT#</label></td>
	<td></td>
	<td style="color:#AAAAAA">#PGROUP_IDENT#</td>
	<td></td>
	<td align=right><a href="${product_table}-edit.php?id=#ID#&{$ahref_rarr_suffix}" title="$msg_bo_go_product_editing
#IDENT#">&rarr;</a></td>
</tr>

EOT;

	$unique_product_checked_cnt = 0;
	while ($row = mysql_fetch_array($result_product)) {
//		pre($row);
		$row["option_color"] = ($row["published"] == "1") ? OPTIONS_COLOR_BLACK : OPTIONS_COLOR_GRAY;
		$row["checked"] = ($row["m2m_id"] != "") ? "checked" : "";
		$row[$product_table] = $row["id"];
		$row["option_value"] = composite_itvalue($composite, $row);
		$row["m2m_message"] = ($row["m2m_id"] > 0) ? "m2m_id=" . $row["m2m_id"] : "не выбран";
//		$row["disabled"] = ($row["id"] == $id) ? "disabled" : "";
		$unique_product_checked_cnt++;

		$ret .= hash_by_tpl($row, $tpl_product);
	}

	$color_olive = OPTIONS_COLOR_OLIVE;
	$color_gray = OPTIONS_COLOR_GRAY;
	$ret = <<< EOT
<!--a name="$m2m_table"></a-->
<div style="padding: 8px 0px 10px 0px; clear:both">
	<div style="padding: 0px 10px 0px 3px; display: block; float:left" title="[уникальные товары] - это количество артикулов в ассортименте (т.е. чистая номенклатура)
[товары по всем группам] - это сумма всех товаров, присутствующих в каждой группе

из-за того, что один и тот же товар может присутствовать в нескольких группах одновременно, [уникальных товаров] меньше, чем сумма товаров по всему каталогу">Выбрано товаров: <font color="$color_olive">$unique_product_checked_cnt уникальных</font></div>
	<div style="padding: 0px 0em 0px 3px; float:right; text-align:right"><a href="$ahref_switch_displaymode" title="СОХРАНИТЕ ИЗМЕНИЯ, если Вы их производили на этой странице
	
при переключении внешнего вида (нажатии на данную ссылку) производится перечитывание сохранённых ранее данных, внесённых на этой странице">добавить из каталога</a> <font color="$color_gray">(долго прорисовывается дерево)</font></div>
</div>
<hr style="clear:both">

<table cellpadding=0 cellspacing=0 width=100%>
<tr height=0>
	<th></th>
	<th width=5></th>
	<th><!--Артикул--></th>
	<th width=5></th>
	<th><!--Наименование--></th>
	<th width=15></th>
	<th><!--Группа--></th>
</tr>
$ret
</table>

<div style="padding: 0px 0px 10px 0px; clear:both"></div>
EOT;

	return $ret;

}





function multicompositebidirect_update($m2m_table, $value_arr
	, $composite = array("product")										// used only first element
	, $absorbing_fixedhash = array("_global:entity" => "_global:id")	// not used at all yet in bidirect
	) {

	global $entity, $id, $debug_query, $cms_dbc, $errormsg, $m2m_bidirect_insert_backward;
	global $msg_bo_bidirect_reciplink_restored, $msg_bo_bidirect_reciplink_restore_failed, $msg_bo_bidirect_reciplink_was_absent, $msg_bo_bidirect_directlink_was_absent, $msg_bo_bidirect_directlink_add_failed, $msg_bo_bidirect_reversrlink_add_failed;

	
	$ret = "";

	$insert_backward = 1;
	if (isset($m2m_bidirect_insert_backward) && isset($m2m_bidirect_insert_backward[$m2m_table])) {
		$insert_backward = $m2m_bidirect_insert_backward[$m2m_table];
	}



//	pre("value_arr=[" . pr($value_arr) . "]");
	$value_arr = array_unique($value_arr);
//	pre("value_arr=[" . pr($value_arr) . "]");

//	$absorbed_fixedhash = absorb_fixedhash($absorbing_fixedhash);
	$absorbed_fixedhash = absorb_fixedhash();
//	pre($absorbed_fixedhash);

	$composite_first = "";
	$composite_db_fields = "";
	foreach($composite as $field_name) {
		if ($composite_db_fields != "") $composite_db_fields .= ", ";
		$composite_db_fields .= $field_name . "_to";
		if ($composite_first == "") $composite_first = $field_name . "_to";
		break;
	}
//	pre("composite_first=[" . $composite_first . "]");
	
	$query = "select id, deleted, $composite_db_fields from $m2m_table where ${entity}_from=$id";
	$query = add_sql_table_prefix($query);
	$result = mysql_query($query, $cms_dbc) or die("SELECT4DELETE MULTICOMPOSITEBIDIRECT_UPDATE failed:<br>$query<br>" . mysql_error($cms_dbc));
	while ($row = mysql_fetch_assoc($result)) {
//		pre($row);

		$m2m_id = $row["id"];
		$m2m_deleted = $row["deleted"];

		$composite_first_value = "";
		$composite_db_value = "";

		foreach($composite as $field_name) {
			if ($composite_db_value != "") $composite_db_value .= "_";
			$composite_db_value .= $row[$field_name . "_to"];
			$composite_first_value = $row[$field_name . "_to"];
			break;
		}

//		pre("composite_first_value=[" . $composite_first_value . "]");


//search key in form value that already exists in db
		$value_array_key = array_search($composite_db_value, $value_arr); 

		if ($value_array_key === FALSE) {
			if ($m2m_deleted == 0) {
//				pre ("deleting ${entity}_from=[$id], ${entity}_to=[$composite_first_value]");
				$update_hash = array("deleted" => 1);
				$deleted = update ($update_hash, array("${entity}_from" => $id, "${entity}_to" => $composite_first_value), $m2m_table);
				if ($deleted == 0) $errormsg .= "bidirect delete: $msg_bo_bidirect_directlink_was_absent $m2m_table(${entity}_from=[$composite_first_value], ${entity}_to=[$id])<br>";
	
				if ($insert_backward == 1) {
		//			pre ("deleting ${entity}_from=[$composite_first_value], ${entity}_to=[$id]");
					$deleted = update ($update_hash, array("${entity}_from" => $composite_first_value, "${entity}_to" => $id), $m2m_table);
					if ($deleted == 0
						&& select_field("id", array("${entity}_from" => $composite_first_value, "${entity}_to" => $id), $m2m_table) == 0
						) {
		//				$inserted = 0;
						$inserted = insert (array("${entity}_from" => $composite_first_value, "${entity}_to" => $id, "deleted" => 1), $m2m_table);
						if ($inserted > 0) {
							$errormsg .= "bidirect delete: $msg_bo_bidirect_reciplink_restored"
									. " $m2m_table(${entity}_from=[$composite_first_value], ${entity}_to=[$id], inserted=[$inserted])<br>";
						} else {
							$errormsg .= "bidirect delete: $msg_bo_bidirect_reciplink_restore_failed"
									. " $m2m_table(${entity}_from=[$composite_first_value], ${entity}_to=[$id], inserted=[$inserted])<br>";
						}
					}
				}
			}
		} else {
// selected in form and present in db, restore deleted
			if ($m2m_deleted == 1) {
				$update_hash = array("deleted" => 0);
//bidirect
//				pre ("restoring deleted ${entity}_from=[$id], ${entity}_to=[$composite_first_value]");
				$restored = update ($update_hash, array("${entity}_from" => $id, "${entity}_to" => $composite_first_value), $m2m_table);
				if ($restored == 0) $errormsg .= "bidirect restore: $msg_bo_bidirect_reciplink_was_absent $m2m_table(${entity}_from=[$id], ${entity}_to=[$composite_first_value])<br>";

				if ($insert_backward == 1) {
	//				pre ("restoring deleted ${entity}_from=[$composite_first_value], ${entity}_to=[$id]");
					$restored = update ($update_hash, array("${entity}_from" => $composite_first_value, "${entity}_to" => $id), $m2m_table);
					if ($restored == 0) {
	//					$inserted = 0;
						$inserted = insert (array("${entity}_from" => $composite_first_value, "${entity}_to" => $id, "deleted" => 0), $m2m_table);
						if ($inserted > 0) {
							$errormsg .= "bidirect restore: $msg_bo_bidirect_reciplink_restored"
									. " $m2m_table(${entity}_from=[$composite_first_value], ${entity}_to=[$id])<br>";
						} else {
							$errormsg .= "bidirect restore: $msg_bo_bidirect_reciplink_restore_failed"
									. " $m2m_table(${entity}_from=[$composite_first_value], ${entity}_to=[$id])<br>";
						}
					}
				}
			}
// no need to insert again value presented in db (a valid or restored value)
			unset($value_arr[$value_array_key]);
		}
	}

//	pre("value_arr=[" . pr($value_arr) . "]");
	
	foreach($value_arr as $form_value) {
		if ($form_value == "0") continue;
//		pre ("inserting ${entity}_from=[$id], ${entity}_to=[$form_value]");

		$composite_insert_value = split ("_", $form_value);
//		pre($composite_insert_value);
//		pre($composite);

		$composite_insert_hash = array();
		for($i=0; $i<sizeof($composite); $i++) {
			$composite_insert_hash[$composite[$i]] = $composite_insert_value[$i];
			$composite_first_value = $composite_insert_value[$i];
			break;	// merely first from list of possible composite values
		}
//		print_r($composite_insert_hash);

/*		$insert_base = array("date_created" => "CURRENT_TIMESTAMP");
		$insert_hash = array_merge($insert_base, $absorbed_fixedhash, $composite_insert_hash);
		echo "<pre>";
		print_r($insert_hash);
		echo "</pre>";
		insert($insert_hash, $m2m_table);
*/

		$insert_forward_hash = array (
			"${entity}_from" => $id,
			"${entity}_to" => $composite_first_value,
			"date_created" => "CURRENT_TIMESTAMP"
		);
//		pre($insert_forward_hash);

		$insert_backward_hash = array (
			"${entity}_from" => $composite_first_value,
			"${entity}_to" => $id,
			"date_created" => "CURRENT_TIMESTAMP"
		);
//		pre($insert_backward_hash);

		$inserted = insert($insert_forward_hash, $m2m_table);
		if (intval($inserted) == 0) {
			$errormsg .= "bidirect insert: $msg_bo_bidirect_directlink_add_failed"
					. " $m2m_table(${entity}_from=[$id], ${entity}_to=[$composite_first_value])<br>";
		}

/*		pre("insert_backward=[$insert_backward]"
			. " m2m_table=[$m2m_table]"
			. " m2m_bidirect_insert_backward=[" . pr($m2m_bidirect_insert_backward) . "]"
		);
*/

		if ($insert_backward == 1) {
			$inserted = insert($insert_backward_hash, $m2m_table);
			if (intval($inserted) == 0) {
				$errormsg .= "bidirect insert: $msg_bo_bidirect_reversrlink_add_failed"
						. " $m2m_table(${entity}_from=[$composite_first_value], ${entity}_to=[$id])<br>";
			}
		}

	}

//	pre("value_arr=[" . pr($value_arr) . "]");

}



?>