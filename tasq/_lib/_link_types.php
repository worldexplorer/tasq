<?

function select_product($product_table = "product", $product_value = "_global", $pgroup_table = "pgroup",
	$pgroup_inproduct_field = "pgroup") {

	global $entity, $id, $cms_dbc;
	$ret = "";

	$options = options_pgroup($product_value, $product_table, $pgroup_table, $pgroup_inproduct_field, 1, 1);
	$ret = select($product_table, $options);

	return $ret;
}

function options_pgroup($product_value,
	$product_table = "product", $pgroup_table = "pgroup", $pgroup_inproduct_field = "pgroup",
	$parent_id = 0, $level = 1) {
	
	global $cms_dbc;
	$options = "";

	$query = "select id, ident, published from $pgroup_table where parent_id=$parent_id order by " . get_entity_orderby($pgroup_table);
	$query = add_sql_table_prefix($query);

	$result = mysql_query($query, $cms_dbc)
			or die("OPTIONS_SQL_TREE failed:<br>$query:<br>" . mysql_error($cms_dbc));
	$num_rows = mysql_num_rows($result);

	while ($row = mysql_fetch_array($result)) {
		$row["spaces"] = spaces_bylevel($level);
		$row["option_color"] = OPTIONS_COLOR_GRAY;

		$tpl = <<< EOT
<option value=0 style="color: #OPTION_COLOR#">#SPACES# #IDENT#</option>
EOT;
		$options .= hash_by_tpl ($row, $tpl);

		$options .= options_pgroup($product_value, $product_table, $pgroup_table, $pgroup_inproduct_field, $row["id"], $level + 1);
		$options .= options_product($product_value, $product_table, $pgroup_table, $pgroup_inproduct_field, $row["id"], $level + 1);
	}

	return $options;
}

function options_product($product_value, $product_table, $pgroup_table, $pgroup_inproduct_field,
	$pgroup_id, $level) {

	global $cms_dbc;

	$options = "";
	
	$query = "select id, ident, published from $product_table where $pgroup_inproduct_field=$pgroup_id order by " . get_entity_orderby($product_table);
	$query = add_sql_table_prefix($query);

	$result = mysql_query($query, $cms_dbc)
			or die("SELECT PRODUCT_VALUE failed:<br>$query:<br>" . mysql_error($cms_dbc));
	$size = mysql_num_rows($result);

	while ($row = mysql_fetch_array($result)) {
		$row["spaces"] = spaces_bylevel($level);
		$row["option_color"] =  ($row["published"] == '1') ? OPTIONS_COLOR_BLACK : OPTIONS_COLOR_GRAY;
		$row["selected"] = ($row["id"] == $product_value) ?  "selected" : "";

		$tpl = <<< EOT
<option value="#ID#" style="color: #OPTION_COLOR#" #SELECTED#>#SPACES# #IDENT#</option>
EOT;
		$options .= hash_by_tpl ($row, $tpl);
	}

	return $options;
}










function options_multipgroup($parent_id = 0, $level = 1, 
	$m2m_table, $m2m_master_field, $m2m_master_value,
	$product_table = "product",  $product_pgroup_field = "pgroup", 
	$pgroup_table = "pgroup", $pgroup_field = "ident",
	$m2m_slave_field = "product") {


	global $cms_dbc;
	$options = "";

	$query = "select id, $pgroup_field from $pgroup_table where parent_id=$parent_id order by " . get_entity_orderby($pgroup_table);
	$query = add_sql_table_prefix($query);

	$result = mysql_query($query, $cms_dbc)
			or die("OPTIONS_SQL_TREE failed:<br>$query:<br>" . mysql_error($cms_dbc));
	$num_rows = mysql_num_rows($result);

	while ($row = mysql_fetch_array($result)) {
		$pgroup_id = $row["id"];
		$pgroup_ident = $row[$pgroup_field];
		
		$spaces = "";
		for ($j=1; $j < $level; $j++) $spaces .= OPTIONS_ONE_SPACE;

		$options .= "<option value=0 style='color: " . OPTIONS_COLOR_GRAY . "'>" . $spaces . $pgroup_ident . "</option>\n";
		$options .= options_multipgroup($pgroup_id, $level + 1, $m2m_table, $m2m_master_field, $m2m_master_value);
		$options .= options_multiproduct($pgroup_id, $level + 1, $m2m_table, $m2m_master_field, $m2m_master_value);
	}

	return $options;
}

function options_multisupplier($level = 1, $m2m_table, $m2m_master_field, $m2m_master_value,
	$product_table = "product",  $product_supplier_field = "supplier", 
	$supplier_table = "supplier", $supplier_field = "ident",
	$m2m_slave_field = "product") {


	global $cms_dbc;
	$options = "";

	$query = "select id, $supplier_field from $supplier_table where deleted=0 order by " . get_entity_orderby($supplier_table);
	$query = add_sql_table_prefix($query);

	$result = mysql_query($query, $cms_dbc)
			or die("OPTIONS_MULTISUPPLIER failed:<br>$query:<br>" . mysql_error($cms_dbc));
	$num_rows = mysql_num_rows($result);

	while ($row = mysql_fetch_array($result)) {
		$supplier_id = $row["id"];
		$supplier_ident = $row[$supplier_field];
		
		$spaces = "";
		for ($j=1; $j < $level; $j++) $spaces .= OPTIONS_ONE_SPACE;

		$options .= "<option value=0 style='color: " . OPTIONS_COLOR_GRAY . "'>" . $spaces . $supplier_ident . "</option>\n";
		$options .= options_multiproduct($supplier_id, $level + 1, $m2m_table, $m2m_master_field, $m2m_master_value,
			"product", "supplier", "product");
	}

	return $options;
}



function options_multiproduct($pgroup_value, $level,
	$m2m_table, $m2m_master_field, $m2m_master_value,
	$product_table = "product", $product_pgroup_field = "pgroup", $m2m_slave_field = "product") {


	global $cms_dbc;

	$options = "";
	
	$query = "select id, ident, published from $product_table where $product_pgroup_field=$pgroup_value order by " . get_entity_orderby($product_table);
	$query = add_sql_table_prefix($query);

	$result = mysql_query($query, $cms_dbc)
			or die("SELECT PRODUCT_VALUE failed:<br>$query:<br>" . mysql_error($cms_dbc));
	$size = mysql_num_rows($result);

	while ($row = mysql_fetch_array($result)) {
		$product_id = $row["id"];
		$product_ident = $row["ident"];
		$product_published = $row["published"];

		$selected = "";
		$published = ($product_published == '1') ? "" : "style='color: " . OPTIONS_COLOR_GRAY . "'";

		$query2 = "select m2m.id from $m2m_table m2m where $m2m_slave_field = $product_id"
				. " and $m2m_master_field = $m2m_master_value and m2m.deleted=0";
		$query2 = add_sql_table_prefix($query2);
		$result2 = mysql_query($query2, $cms_dbc)
				or die("SELECT PRODUCT_OPTION failed:<br>$query2:<br>" . mysql_error($cms_dbc));
		if (mysql_num_rows($result2) > 0) $selected = "selected";
	
		$spaces = "";
		for ($j=1; $j < $level; $j++) $spaces .= OPTIONS_ONE_SPACE;

		$options .= "<option value='$product_id' $selected $published>" . $spaces . $product_ident . "</option>\n";
	}
	
	return $options;
}


function multi_product($table, $value = "_global", $m2m_table = "_global") {
	global $entity, $id, $cms_dbc;
	global $msg_bo_select_ctrl_shift;
	
	$ret = "";
	$options = "";

	if (isset($id)) {
//		$m2m_table = "m2m_" . $entity . "_product";
		$m2m_master_field = $entity;
		$m2m_master_value = $id;

		$options = options_multipgroup(1, 1, $m2m_table, $m2m_master_field, $m2m_master_value);
	}
	
	$size = 10;
	$ret = "<select multiple size=$size name='{$table}[]'>$options</select>";

	$ret = "<table cellspacing=0 cellpadding=0><tr valign=middle><td>"
		. $ret
		. "</td><td>&nbsp;</td><td>"
		. "<font color=" . OPTIONS_COLOR_GRAY . ">$msg_bo_select_ctrl_shift</font>"
		. "</td></tr></table>";

	return $ret;
}

function multi_product_supplier($table, $value = "_global", $m2m_table = "_global") {
	global $entity, $id, $cms_dbc;
	global $msg_bo_select_ctrl_shift;

	$ret = "";
	$options = "";

	if (isset($id)) {
//		$m2m_table = "m2m_" . $entity . "_product";
		$m2m_master_field = $entity;
		$m2m_master_value = $id;

		$options = options_multisupplier(1, $m2m_table, $m2m_master_field, $m2m_master_value);
	}
	
	$size = 20;
	$ret = "<select multiple size=$size name='{$table}[]'>$options</select>";

	$ret = "<table cellspacing=0 cellpadding=0><tr valign=middle><td>"
		. $ret
		. "</td><td>&nbsp;</td><td>"
		. "<font color=" . OPTIONS_COLOR_GRAY . ">$msg_bo_select_ctrl_shift</font>"
		. "</td></tr></table>";

	return $ret;
}

function m2mtf_parent($dict_table, $value = "_global", $m2m_table = "_global", $parent_dict_table = "ptstype") {
	global $entity, $id, $m2m_it, $cms_dbc, $input_size;
	$ret = "";

	if (isset($id)) {
		$query = "select dp.id as dp_id, dp.ident as dp_ident"
				. ", d.id, d.ident as ident, concat(dp.ident, ' | ',  d.ident) as ident2, d.published"
				. ", m2m.id as m2m_id, m2m.content"
			. " from $dict_table d, $parent_dict_table dp "
			. " left outer join $m2m_table m2m on m2m.$entity=$id and m2m.$dict_table=d.id"
			. " where d.$parent_dict_table=dp.id"
			. " and d.deleted=0 and dp.deleted=0"
			. " order by dp." . get_entity_orderby($parent_dict_table) . ", d." . get_entity_orderby($dict_table);
		$query = add_sql_table_prefix($query);
		$result = mysql_query($query, $cms_dbc) or die("m2mtf_parent(): SELECT DICTIONARY failed:<br>$query:<br>" . mysql_error($cms_dbc));

		$i = 1;
		$bgcolor = ($i % 2) ? OPTIONS_COLOR_WHITE : OPTIONS_COLOR_LIGHTBLUE;

		$dictparent_id = 0;
		$dictparent_content = "";
		$prev_dictparent_id = 0;
		$prev_dictparent_ident = 0;
		$prev_dictparent_content = "";

		while ($row = mysql_fetch_assoc($result)) {
			$dict_id = $row["id"];
			$dict_ident = $row["ident"];
			$dictparent_id = $row["dp_id"];
			$dictparent_ident = $row["dp_ident"];
			$dict_published = $row["published"];
			$m2m_content = $row["content"];

			$color = ($dict_published == 0) ? COLOR_DISABLED : COLOR_ENABLED;
			
			$tf_size = $input_size["text_insidelayer"];

			if ($prev_dictparent_id == 0) $prev_dictparent_id = $dictparent_id;
			if ($prev_dictparent_ident == "") $prev_dictparent_ident = $dictparent_ident;

			if ($prev_dictparent_id != $dictparent_id) {
				$ret .= <<< EOT
<tr bgcolor=$bgcolor><th colspan=2><b>$prev_dictparent_ident</b></th></tr>
$prev_dictparent_content
<tr><td colspan=2 height=20></td></tr>
EOT;

				$prev_dictparent_id = $dictparent_id;
				$prev_dictparent_ident = $dictparent_ident;

				$prev_dictparent_content = "";
				$i++;
				$bgcolor = ($i % 2) ? OPTIONS_COLOR_WHITE : OPTIONS_COLOR_LIGHTBLUE;
			}

			$prev_dictparent_content .= <<< EOT
<tr bgcolor=$bgcolor>
	<td align="left" nowrap><font color="$color">$dict_ident</font></td>
	<td><input type="textfield" size="$tf_size" name="${dict_table}[$dict_id]" value="$m2m_content"></td>
</tr>
EOT;
		}

if ($prev_dictparent_content != "") {
		$ret .= <<< EOT
<tr bgcolor=$bgcolor><th colspan=2><b>$prev_dictparent_ident</b></th></tr>
$prev_dictparent_content
<tr><td colspan=2 height=20></td></tr>
EOT;
}

	}

	return $ret;
}

function m2mtf_controlled($dict_table, $value = "_global", $m2m_table = "_global"
		, $controlling_m2m_table = "m2m_pgroup_pprop", $controlling_fixed_hash = array ("pgroup" => "_global:pgroup", "deleted" => 0)) {
	global $entity, $id, $m2m_it, $cms_dbc, $input_size, $tpl_controlled;
	$ret = "";

	if (!isset($tpl_controlled)) {
		$tpl_controlled = <<< EOT
<tr>
	<td align="left" nowrap><font color="#dict_color#">#dict_ident#</font></td>
	<td><textarea cols="#tf_size#" rows=3 name="#dict_table#[#dict_id#]">#m2m_content#</textarea></td>
</tr>
EOT;
	}

	$controlling_fixed_hash = absorb_fixedhash($controlling_fixed_hash);

// mysql 5 seems dont like such type of joins
	$query = "select d.id, d.ident, d.published, m2m.content as m2m_content"
		. " from $dict_table d, $controlling_m2m_table m2mc "
		. " left outer join $m2m_table m2m on m2m.$dict_table=d.id"
		. " and m2m.$entity=$id"
		. " where d.published=1 and d.deleted=0"
		. " and m2mc.$dict_table=d.id and " . sqlcond_fromhash($controlling_fixed_hash, "m2mc")
		. " order by d." . get_entity_orderby($dict_table);
	
	$query = "select d.id, d.ident, d.published, m2m.content as m2m_content"
		. " from $dict_table d"
		. " inner join $controlling_m2m_table m2mc on m2mc.$dict_table=d.id and " . sqlcond_fromhash($controlling_fixed_hash, "m2mc")
		. " left join $m2m_table m2m on m2m.$dict_table=d.id"
		. " and m2m.$entity=$id"
		. " where d.published=1 and d.deleted=0"
		. " order by d." . get_entity_orderby($dict_table);
	
//	echo $query;

	$query = add_sql_table_prefix($query);
	$result = mysql_query($query, $cms_dbc) or die("SELECT M2M_CONTROLLED failed:<br>$query:<br>" . mysql_error($cms_dbc));
	$size = mysql_num_rows($result);

	while ($row = mysql_fetch_assoc($result)) {
		$row["dict_id"] = $row["id"];
		$row["dict_ident"] = $row["ident"];
		$row["dict_published"] = $row["published"];
		$row["dict_color"] = ($row["published"] == 1) ? COLOR_ENABLED : COLOR_DISABLED;
		$row["dict_table"] = $dict_table;
		$row["tf_size"] = $input_size["text_insidelayer"];

		$ret .= hash_by_tpl($row, $tpl_controlled);
	}

	return $ret;
}

function m2mtf_notempty_cnt($dict_table,
	$fixed_hash = array ("_global:entity" => "_global:id"), $m2m_table = "_global") {

	global $entity, $id, $m2m_it, $cms_dbc, $input_size;
	$ret = 0;
	
	$fixed_hash = absorb_fixedhash($fixed_hash);
	$m2m_cond = sqlcond_fromhash($fixed_hash, "m2m");
	if ($m2m_cond != "") $m2m_cond = " and $m2m_cond";
	
	$query = "select count(d.id) as cnt"
		. " from $dict_table d, $m2m_table m2m"
		. " where d.published = '1' and m2m.content != '' and m2m.$dict_table = d.id $m2m_cond";
	$count_qarray = select_queryarray($query);
	$ret = $count_qarray[0]["cnt"];

	return $ret;
}
	
function m2mtf($dict_table,
	$fixed_hash = array ("_global:entity" => "_global:id"), $m2m_table = "_global", $item_tpl = "", $table_tpl = "") {
	
	$ret = "";
	
	$ret = m2mcontent_display($dict_table, $fixed_hash, $m2m_table, "NONE", 1, "textfield", $item_tpl);

	if ($table_tpl == "") $table_tpl = "#TABLE_ROWS#";

	$ret = hash_by_tpl(array("table_rows" => $ret), $table_tpl);

	return $ret;
}

function m2mtfro($dict_table,
	$fixed_hash = array("_global:entity" => "_global:id"), $m2m_table = "_global", $item_tpl = "") {

//	global $db_values_array;
//	$db_values_array_saved = $db_values_array;
//	$db_values_array = array();
	$ret = m2mcontent_display($dict_table, $fixed_hash, $m2m_table, "NONE", 1, "textfieldro", $item_tpl);
//	$db_values_array = $db_values_array_saved;
	return $ret;
}

function m2mcontent_display($dict_table
	, $fixed_hash = array ("_global:entity" => "_global:id"), $m2m_table = "_global"
	, $default = "NONE", $m2mcb_colcnt = 1, $inputtype = "textfield"
	, $item_tpl = "") {

	global $m2m_it, $cms_dbc, $input_size;
	global $no_jsv, $focus_itname;
	global $backrow_bgcolor, $backrow_tpl, $backrow_not_obligatory_sign;

	global $mode, $mcicc_copyform;
	$mcicc_copyform_tpl = "<input type='hidden' name='#IT_NAME#' value='#IT_VALUE#'>\n";

	$ret = "";

/*
	if ($item_tpl == "") {
		$item_tpl = <<< EOT
<tr>
	<td align="right" nowrap>#OBLIGATORY_SIGN##IDENT#</td>
	<td>#IT_OUTPUT#</td>
</tr>
EOT;
	}
*/

	if ($item_tpl == "") $item_tpl = $backrow_tpl;

	$fixed_hash = absorb_fixedhash($fixed_hash);
//	pre($fixed_hash);

//	$jsv_body = "";

	$query = "select d.*, m2m.content as m2m_content"
		. " from $dict_table d"
		. " left outer join $m2m_table m2m on m2m.$dict_table=d.id"
		. " and " . sqlcond_fromhash($fixed_hash, "m2m") . " and m2m.published=1"
		. " where d.published=1 and d.deleted=0"
		. " order by d." . get_entity_orderby($dict_table)
		;

	if ($inputtype == "textfieldro") {
		$query = "select d.*, m2m.content as m2m_content"
			. " from $dict_table d"
			. " inner join $m2m_table m2m on m2m.$dict_table=d.id"
			. " and " . sqlcond_fromhash($fixed_hash, "m2m") . " and m2m.published=1"
			. " where d.published=1 and d.deleted=0"
			. " order by d." . get_entity_orderby($dict_table)
			. ", m2m.manorder asc"
			;
	}

	$query = add_sql_table_prefix($query);
	$result = mysql_query($query, $cms_dbc) or die("SELECT M2MCONTENT_DISPLAY failed:<br>$query:<br>" . mysql_error($cms_dbc));
	$rows_total = mysql_num_rows($result);

	for ($i=0; $row = mysql_fetch_assoc($result); $i++) {
		$row["rows_total"] = $rows_total;
		$row["i"] = $i;

		$row["it_txt"] = $row["ident"];
		$row["it_name"] = $dict_table . "[" . $row["id"] . "]";
		$row["it_wrapped"] = $inputtype($row["it_name"], $row["m2m_content"], "", array());
		$row["obligatory_sign"] = $backrow_not_obligatory_sign;
		$row["sheet_row_bgcolor"] = $backrow_bgcolor;

		if ($no_jsv == 0) {
			if (isset($row["jsvalidator"])) {
				$jsv = $row["jsvalidator"];
				$jsv_row = select_entity_row(array("id" => $jsv), "jsvalidator");
				$jsv_hashkey = $jsv_row["hashkey"];
				
				$row["obligatory_sign"] = jsv_addvalidation($jsv_hashkey, $row["it_name"], $row["ident"]);
			}
		}

		if ($focus_itname == "") $focus_itname = $row["it_name"];

		if (isset($mcicc_copyform)) {
			$row["it_value"] = $row["m2m_content"];
			$mcicc_copyform .= hash_by_tpl($row, $mcicc_copyform_tpl);
		}

		if ($mode == "copy") {
//			echo $row["it_name"];
//			echo $row["m2m_content"] = get_string($row["it_name"]);
			$prop_array = get_array($dict_table);
			$row["m2m_content"] = $prop_array[$row["id"]];
			$row["it_wrapped"] = $inputtype($row["it_name"], $row["m2m_content"], "", array());
		}

		$ret .= hash_by_tpl($row, $item_tpl);
	}

	return $ret;
}



function m2mtfethalon($dict_table
	, $fixed_hash = array("_global:entity" => "_global:id"), $m2m_tablehash
	, $item_tpl = "", $table_tpl = "") {

	global $m2m_it, $cms_dbc;
	global $msg_bo_field;
	
	$ret = "";

	if ($item_tpl == "") {
		$item_tpl = <<< EOT
<tr>
	<td align="left" nowrap>#IDENT#</td>
	<td align="left" nowrap>#RO_OUTPUT#</td>
	<td align="left" nowrap>#RW_OUTPUT#</td>
</tr>

EOT;
	}

	if ($table_tpl == "") {
		$table_tpl = <<< EOT
<table cellspacing="3" cellpadding="0">
<tr>
	<th>$msg_bo_field</th>
	<th>#RO_HEADER#</th>
	<th>#RW_HEADER#</th>
</tr>
#TABLE_ROWS#
</table>

EOT;
	}

	$fixed_hash = absorb_fixedhash($fixed_hash);
//	print_r($fixed_hash);

/*
	$m2m_tablehash = array(
			"m2m_customer_cprop" => array("по данным ALION", "textfieldro", "content"),
			"m2m_customer_cprop_site" => array("по мнению представителя", "textfield", "content")
			)
*/

	$tablenames = array_keys($m2m_tablehash);
//	pre($tablenames);

	$ro_table = $tablenames[0];
	$rw_table = $tablenames[1];
	
	$query = "select d.*"
		. ", m2mro." . $m2m_tablehash[$ro_table][2] . " as m2mro_content"
		. ", m2mrw." . $m2m_tablehash[$rw_table][2] . " as m2mrw_content"
		. " from $dict_table d"
		. " left outer join $ro_table m2mro on m2mro.$dict_table=d.id"
			. " and " . sqlcond_fromhash($fixed_hash, "m2mro") . " and m2mro.published=1"
		. " left outer join $rw_table m2mrw on m2mrw.$dict_table=d.id"
			. " and " . sqlcond_fromhash($fixed_hash, "m2mrw") . " and m2mrw.published=1"
		. " where d.published=1 and d.deleted=0"
		. " order by d." . get_entity_orderby($dict_table)
		;

	$query = add_sql_table_prefix($query);
	$result = mysql_query($query, $cms_dbc) or die("SELECT M2MTFETHALON failed:<br>$query:<br>" . mysql_error($cms_dbc));
	$rows_total = mysql_num_rows($result);

	$table_rows = "";
	for ($i=0; $row = mysql_fetch_assoc($result); $i++) {
		$row["rows_total"] = $rows_total;
		$row["i"] = $i;

		$row["it_name"] = $dict_table . "[" . $row["id"] . "]";

		$ro_inputtype = $m2m_tablehash[$ro_table][1];
		$row["ro_output"] = $ro_inputtype($row["it_name"], $row["m2mro_content"], "", array());

		$rw_inputtype = $m2m_tablehash[$rw_table][1];
		$row["rw_output"] = $rw_inputtype($row["it_name"], $row["m2mrw_content"]);
		
		$table_rows .= hash_by_tpl($row, $item_tpl);
	}
	
	$table_hash = array(
		  "ro_header" => $m2m_tablehash[$ro_table][0]
		, "rw_header" => $m2m_tablehash[$rw_table][0]
		, "table_rows" => $table_rows
		);
	
	$ret = hash_by_tpl($table_hash, $table_tpl);

/*
	$query = "select id, ident from $dict_table where published='1' order by " . get_entity_orderby($dict_table);
	$result = mysql_query($query, $cms_dbc) or die("SELECT M2MTFETHALON failed:<br>$query:<br>" . mysql_error($cms_dbc));

	while ($row = mysql_fetch_assoc($result)) {
		$dict_id = $row["id"];
		$dict_ident = $row["ident"];

		$m2m_fixed_hash = array_merge($fixed_hash, array($dict_table => $dict_id));

		$ret_row = "";
		foreach($m2m_tablehash as $m2m_table => $m2m_specifichash) {
			$m2m_inputtype = $m2m_specifichash[1];
			$m2m_contentfield = $m2m_specifichash[2];
			
			$m2m_content = select_field($m2m_contentfield, $m2m_fixed_hash, $m2m_table);
			$m2m_content_asinputtype = $m2m_inputtype("${dict_table}[$dict_id]", $m2m_content, "");
			$ret_row .= "<td>" . $m2m_content_asinputtype . "</td>";
		}
		
		$ret .= <<< EOT
<tr>
	<td align="left" nowrap>$dict_ident</td>
	$ret_row
</tr>
EOT;
	}

	$header = "";
	foreach($m2m_tablehash as $m2m_table => $m2m_specifichash) {
		$header .= "<th>" . $m2m_specifichash[0] . "</th>";
	}

	$ret = "<tr><th>Поле</th>$header</tr>$ret";
*/


	return $ret;
}


function talist($o2m_table, $ta_field = "content") {
	global $entity, $id, $cms_dbc;
	global $msg_bo_edit;
	
	$ret = "";

	$tpl = <<< EOT
<tr valign=top>
	<td width=30 align=right><img src="/upload/phicon/#PHICON_ID#/#PHICON_FILE#" alt="#PHICON_IDENT#"></td>
	<td><b>#IDENT#</b><br>#$ta_field#</td>
</tr>
EOT;

	$query = "select h.id, h.content, h.ident, i.id as phicon_id, i.icon as phicon_file, i.ident as phicon_ident"
		. " from $o2m_table h, phicon i"
		. " where h.phicon=i.id"
		. " and $entity=$id";
	$ret = query_by_tpl($query, $tpl);
	
	$ret = <<< EOT
$ret
<tr><td colspan=2 align=center><a href="$o2m_table.php?$entity=$id">$msg_bo_edit</a></td></tr>
EOT;
	
	return $ret;
}

function m2mcb($dict_table, $value = "_global", $m2m_table = "_global", $default = "NONE", $item_tpl = "") {
//function m2mcb($dict_table, $value = "_global", $m2m_table = "_global", $item_tpl = "") {
	global $entity, $id, $m2mcb_colcnt, $cms_dbc, $in_backoffice;
	global $msg_bo_it_change, $msg_bo_it_add, $msg_tag_shortcut;
	
	$ret = "<tr>";

	if ($item_tpl == "") {
		$item_tpl = <<< EOT
<td><input type='checkbox' name='#DICT_TABLE#[]' value='#ID#' #CHECKED# id='#DICT_TABLE#_#I#'></td>
<td nowrap><label for='#DICT_TABLE#_#I#'>#IDENT#</label></td>
<td width=10></td>
EOT;
	}

	$query = "select d.id, d.ident, m2m.id as m2m_id"
		. " from $dict_table d"
		. " left outer join $m2m_table m2m on m2m.$entity=$id and m2m.$dict_table=d.id"
		. " where d.published=1"
		. " order by d." . get_entity_orderby($dict_table);

	$query = "select d.id, d.ident, m2m.id as m2m_id"
		. " from $dict_table d"
		. " left outer join $m2m_table m2m on m2m.$entity=$id and m2m.$dict_table=d.id and m2m.deleted=0"
		. " where d.published=1 and d.deleted=0"
		. " order by d." . get_entity_orderby($dict_table);

	$query = add_sql_table_prefix($query);
	$result = mysql_query($query, $cms_dbc)
		or die("SELECT MULTI_VALUE failed:<br>$query:<br>" . mysql_error($cms_dbc));
	$size = mysql_num_rows($result);

	$i = 1;
	while ($row = mysql_fetch_assoc($result)) {
		$dict_id = $row["id"];
		$m2m_id = $row["m2m_id"];

		$row["checked"] = ((int) $m2m_id == 0) ? "" : "checked";
		
		if ($id == 0) {
			if (is_string($default)) {
				switch ($default) {
					case "ANY":
						$row["checked"] = "checked";
						break;

					case "NONE":
					default:
						break;
				}
			}
			if (is_array($default) && in_array($dict_id, $default)) {
				$row["checked"] = "checked";
			}
		}

		$row["dict_table"] = $dict_table;
		$row["i"] = $i;

		$ret .= hash_by_tpl($row, $item_tpl);

		if ($m2mcb_colcnt == 0) {
			$ret = "$ret</tr><tr>";
		} else {

			if ($i % $m2mcb_colcnt == 0) {
//					echo "i[$i] ";
				$ret = "$ret</tr><tr aa>";
			}
		}

		$i++;

	}


	$ret .= "</tr>";
	

	$backoffice_specific = "";

	if ($in_backoffice == 1) {
		$backoffice_specific = <<< EOT
	<tr>
		<td align=right>
			<a href="$dict_table.php" target="_blank">$msg_tag_shortcut $msg_bo_it_change</a> &nbsp;&nbsp;&nbsp;<a href="$dict_table-edit.php" target="_blank">$msg_tag_shortcut $msg_bo_it_add</a>&nbsp;&nbsp;
		</td>
	</tr>
EOT;
	}

	$options_color_gray = OPTIONS_COLOR_GRAY;
	$ret = <<< EOT
	<table cellspacing="0" cellpadding="2" border="0" style="border: 1px solid $options_color_gray">
	<tr>
		<td>
			<table cellspacing="2" cellpadding="0">
			$ret
			</table>
		</td>
	</tr>
	$backoffice_specific
	</table>
EOT;

	return $ret;
}


function multiselect($name, $options, $size) {
	global $fields_horiz;
	if ($fields_horiz == 1) {
		$ret = "<select multi size=$size name='$name'>$options</select>";
	} else {
		$ret = "<select multi size=$size name='$name'>$options</select>";
	}
	
	return $ret;
}


/*
function select_table($table, $value = "_global", $sql_field = "ident", $tag_attr = "") {
	global $fixed_cond;
	$select = "";

//	$pos = strpos($table, "_");
//	$len = strlen($table);
//	if ($pos > 0) $table = substr($table, 0, $pos);

	if ($value == "_global") $value = get_number($table);

	$query = "select id, $sql_field from $table where published='1' $fixed_cond order by " . get_entity_orderby($table);
//echo $query;
	$options = options_sql($query, $value);
	$select = select($table, $options, $tag_attr);

	return $select;
}

function select_table_ro($table, $value = "_global", $sql_field = "ident", $tag_attr = "") {
	global $fixed_cond, $debug_query;
	$select = "";

//	$pos = strpos($table, "_");
//	$len = strlen($table);
//	if ($pos > 0) $table = substr($table, 0, $pos);

	if ($value == "_global") $value = get_number($table);

	$query = "select id, $sql_field from $table where published='1' $fixed_cond order by manorder";
	if ($debug_query == 1) echo "<br>SELECT_TABLE_RO: [$query]<br>";
	$options = options_sql($query, $value);
	$select = select($table, $options, $tag_attr);

	return $select;
}
*/

function table_ro($name, $value = 0, $sql_field = "ident") {
	global $msg_bo_empty;
	$ret = select_field($sql_field, array("id" => $value), makestrict($name));
	if ($ret == "") $ret = "$msg_bo_empty";
	return $ret;
}


function select_table($table, $value = "_global", $sql_field = "ident", $tag_attr = "") {
	return select_table_all($table, $value, $sql_field, $tag_attr, array("published" => 1));
}

function select_tablelink_all($table, $value = "_global", $sql_field = "ident", $tag_attr = "") {
	global $msg_bo_link_delete;
	return select_table_all($table, $value, $sql_field, $tag_attr, array(), "$msg_bo_link_delete");
}

function select_table_all($table, $value = "_global", $sql_field = "ident", $tag_attr = ""
		, $fixed_hash = array(), $forcezero_option = "", $forcezero_evenifwasselected = 0
		, $graycomment = "") {

	global $debug_query, $entity_m2mfixed_list, $db_values_array;
	static $cache = array();

	$ret = "";
	
	$table_strict = makestrict($table);
	if ($value == "_global") $value = get_number($table);

	$deleted_cond = "";
	if (entity_has_deleted_field($table_strict)) $deleted_cond = "and e.deleted=0";
		
	$cache_key = $table_strict . ":" . $value . ":" . $sql_field . ":" . pr($fixed_hash) . ":" . $forcezero_option . ":" . $forcezero_evenifwasselected;
//	pre("$cache_key");

	if (!isset($cache[$cache_key])) {
		$m2m_dependtable = get_m2m_dependtable($table_strict);

		if ($m2m_dependtable != "") {
			if (count($fixed_hash) == 0) {
				$array_dep = $entity_m2mfixed_list[$m2m_dependtable];
				foreach ($array_dep as $dep_table) {
					if ($dep_table != $table_strict && !isset($fixed_hash[$dep_table])) {
//						pre($db_values_array);
//						pre($dep_table);
						$fixed_value = isset($db_values_array[$dep_table]) ? $db_values_array[$dep_table] : 0;
						if ($fixed_value == 0) $fixed_value = get_number($dep_table);
						if ($fixed_value > 0) $fixed_hash[$dep_table] = $fixed_value;
					}
				}
//				pre($fixed_hash);
			}
			$query = "select e.id, e.$sql_field as ident, e.published"
				. " from $table_strict e"
				. " inner join $m2m_dependtable m2m on m2m.$table_strict=e.id "
					. sqlcond_fromhash($fixed_hash, "m2m", " and ", " and ")
					. " and m2m.published=1 and m2m.deleted=0"
				. " where 1=1 $deleted_cond "
				. " group by e.id"
				. " order by e." . get_entity_orderby($table_strict);

// 1 select e.id, e.ident as ident, e.published from cstore e inner join m2m_cstore_city m2m on m2m.cstore=e.id and m2m.published=1 and m2m.deleted=0 where 1=1 and e.deleted=0 group by e.id order by e.ident asc
// 2 select e.id, e.ident as ident, e.published from city e inner join m2m_cstore_city m2m on m2m.city=e.id and m2m.published=1 and m2m.deleted=0 where 1=1 and e.deleted=0 group by e.id order by e.ident asc 
// а 2 должно зависеть от m2m.cstore=[сеть выбранная ранее]

//	мешало в oregons shop-edit, "city" => array ("Город", "select_hard", "ident")
// shop -> cstore, cstore -> m2m -> city, cstore не содержит city
//	OPTIONS_SQL failed:
//	select id, ident as ident, published from oregons_cstore where 1=1 and deleted=0 and city='1' order by ident asc:
//	Unknown column 'city' in 'where clause'
//				. sqlcond_fromhash($fixed_hash, "", " and ", " and ")
// вообще ужас
//				. sqlcond_fromhash($fixed_hash, "", " and ", " and ", TABLE_PREFIX)

		} else {

			$query = "select e.id, e.$sql_field as ident, e.published"
				. " from $table_strict e"
				. " where 1=1 $deleted_cond "
				. sqlcond_fromhash($fixed_hash, "e", " and ", " and ")
//				. sqlcond_fromhash($fixed_hash, "", " and ", " and ", TABLE_PREFIX)
				. " order by e." . get_entity_orderby($table_strict);
		}
	
		$query = add_sql_table_prefix($query);
		if ($debug_query == 1) echo "<br>SELECT_TABLE_ALL: [$query]<br>";
		$options = options_sql($query, $value, $forcezero_option, $forcezero_evenifwasselected);
		$cache[$cache_key] = $options;
//		pre("set to cache $cache_key");
	} else {
		$options = $cache[$cache_key];
//		pre("got from cache $cache_key: " . pr($cache[$cache_key]));
	}
	
	$ret = select($table, $options, $tag_attr);

	if ($graycomment != "") $ret .= "&nbsp;&nbsp;&nbsp;&nbsp;<font style='color: " . OPTIONS_COLOR_GRAY . "'>$graycomment</font>";

	return $ret;
}

function select_table_tree_root($table, $value = "_global", $fixed_field = "parent_id", $from_parent = 0
		, $tag_attr = "", $self_id = "_global:id"
		, $forcezero_option = "SELECTOR_EVERY", $forcezero_evenifwasselected = 0
		) {
	return select_table_tree($table, $value, $fixed_field, $from_parent, $tag_attr, $self_id, $forcezero_option, $forcezero_evenifwasselected);
}

function select_table_tree($table, $value = "_global", $fixed_field = "parent_id", $from_parent = 1
		, $tag_attr = "", $self_id = "_global:id"
		, $forcezero_option = "SELECTOR_EVERY", $forcezero_evenifwasselected = 0
		) {
	global $entity;
	$select = "";

	$self_id = absorb_variable($self_id);
	if ($value == "_global") $value = get_number($fixed_field);


	$table_strict = makestrict($table);
//	$table_strict = ($fixed_field == $entity) ? $fixed_field : );
//	$table_strict = ($fixed_field == "parent_id") ? $fixed_field : makestrict($table);

	$options = options_sql_tree($table_strict, $value, "ident", $from_parent, 1
		, $self_id, $forcezero_option, $forcezero_evenifwasselected);


	if ($table == $entity && $fixed_field == "parent_id") {
		$select = select("parent_id", $options, $tag_attr);
	} else {
		$select = select($table, $options, $tag_attr);
	}


	return $select;
}

function options_sql_tree($table, $value, $field = "ident", $parent_id = 0, $level = 1, $self_id = 0
		, $forcezero_option = "SELECTOR_EVERY", $forcezero_evenifwasselected = 0
		) {
	global $entity, $num_rows, $cms_dbc, $debug_query;
	global $msg_bo_selector_every;
	if ($forcezero_option == "SELECTOR_EVERY") $forcezero_option = $msg_bo_selector_every;
	
	static $was_selected = 0;

	$ret = "";

	$deleted_cond = "";
	if (entity_has_deleted_field($table)) $deleted_cond = "and deleted=0";

	$query = "select id, $field, published from $table where parent_id=$parent_id $deleted_cond order by " . get_entity_orderby($table);
	$query = add_sql_table_prefix($query);
	if ($debug_query == 1) echo "<br>OPTIONS_SQL_TREE: [$query]<br>";
	$result = mysql_query($query, $cms_dbc) or die("OPTIONS_SQL_TREE failed:<br>$query:<br>" . mysql_error($cms_dbc));
//	echo "$query<br>";
	$num_rows = mysql_num_rows($result);

	for ($i=1; $row = mysql_fetch_array($result); $i++) {
		$id = $row["id"];
		$ident = $row[$field];
		$published = $row["published"];
		
		$tab = "";
		for ($j=1; $j < $level; $j++) $tab = "&nbsp;&nbsp;&nbsp;" . $tab;

		$published = 1;
		if (isset($row["published"])) $published = $row["published"];
		$published_style = ($published == 1) ? "" : " style='color: " . OPTIONS_COLOR_GRAY . "'";
		$self_style = ($id == $self_id && $table == $entity) ? " style='color: " . OPTIONS_COLOR_AHREF . "'" : "";

		$selected = "";
		if ($id == $value) {
			$selected = "selected";
			$was_selected = 1;
		}

		$ret .= "<option value='$id' $published_style $self_style $selected>{$tab}{$ident}</option>\n";

		$ret .= options_sql_tree($table, $value, $field, $id, $level+1, $self_id
			, $forcezero_option, $forcezero_evenifwasselected
			);
	}

	if ($level == 1) {
//		if ($was_selected == 0) $ret = "<option value='0' selected>&nbsp;</option>\n" . $ret;

		$zero_option = ($forcezero_option != "") ? $forcezero_option : "&nbsp;";
		if (strlen(strip_tags($zero_option)) ==  strlen($zero_option)) {
			$zero_selected = ($was_selected == 1) ? "" : "selected";
			$zero_option = "<option value='0' $zero_selected>$zero_option</option>";
		}
	
		if ($was_selected == 0 || ($was_selected == 1 && $forcezero_evenifwasselected)) {
			$ret = $zero_option . $ret;
		}
	}

	return $ret;
}


function select_queryro($name, $value, $default, $tag_attr = "") {
	$options = options_sql($default, $value);
	return selectro($name, $options, $tag_attr);
}


function select_query($name, $value, $default, $tag_attr = "") {
	$options = options_sql($default, $value);
	return select($name, $options, $tag_attr);
}


function options_sql_VERYSLOW($query, $default, $forcezero_option = "", $forcezero_evenifwasselected = 0,
	$tpl = "<option value='#ID#' #PUBLISHED_STYLE# #SELECTED#>#IDENT#</option>\n"
	) {

	global $num_rows, $cms_dbc;
	$ret = "";

//	echo "[$cms_dbc] / [$query]";

	$query = add_sql_table_prefix($query);
	$result = mysql_query($query, $cms_dbc)
		or die("OPTIONS_SQL failed:<br>$query:<br>" . mysql_error($cms_dbc));
	$num_rows = mysql_num_rows($result);

	$i = 0;
	$was_selected = 0;

	while ($row = mysql_fetch_array($result)) {
		$row["i"] = $i++;
		$row["published_style"] = ($row["published"] == 1) ? "" : " style='color: " . OPTIONS_COLOR_GRAY. "'";
		$row["selected"] = ($row["id"] == $default) ? "selected" : "";
		$row["checked"] = ($row["id"] == $default) ? "checked" : "";
		if ($row["id"] == $default) $was_selected = 1;

		$ret .= hash_by_tpl($row, $tpl);
	}
	
	$zero_option = ($forcezero_option != "") ? $forcezero_option : "&nbsp;";
	if (strlen(strip_tags($zero_option)) ==  strlen($zero_option)) {
		$row = array (
			"id" => 0,
			"ident" => $zero_option,
			"selected" => "selected",
		);
		$zero_option = hash_by_tpl($row, $tpl);
	}

	if ($was_selected == 0 || ($was_selected == 1 && $forcezero_evenifwasselected)) {
		$ret = $zero_option . $ret;
	}

	return $ret;
}



function options_sql($query, $default, $forcezero_option = "", $forcezero_evenifwasselected = 0) {
	global $num_rows, $cms_dbc, $published_opthash;
	$ret = "";

//	echo "[$cms_dbc] / [$query]";

	$query = add_sql_table_prefix($query);
	$result = mysql_query($query, $cms_dbc) or die("OPTIONS_SQL failed:<br>$query:<br>"
			. mysql_error($cms_dbc));
	$num_rows = mysql_num_rows($result);

	$was_selected = 0;
	for ($i=1; $row = mysql_fetch_array($result); $i++) {
		$id = $row["id"];
		$ident = $row["ident"];

		$published = 1;
		if (isset($row["published"])) $published = $row["published"];
		$published_style = ($published == 1) ? "" : " style='color: " . OPTIONS_COLOR_GRAY. "'";

		$selected = "";
		if ($id == $default) {
			$selected = "selected";
			$was_selected = 1;
		}

		$ret .= "<option value='$id' $published_style $selected>$ident</option>";
/*
		$row["selected"] = "";
		if ($row["id"] == $default) {
			$row["selected"] = "selected";
			$was_selected = 1;
		}

		if (isset($published_opthash["published_0"]) && isset($published_opthash["published_1"])) {
			if (!isset($row["published"])) $row["published"] = 1;
			$popthash_hashkey = "published_" . $row["published"];
			$tpl = hash_by_tpl(get_defined_constants(), $published_opthash[$popthash_hashkey]);
			$ret .= hash_by_tpl($row, $tpl);
		} else {
			pre("options_sql(): published_opthash corrupted:");
			pre($published_opthash);
		}
*/
	}

/*
	if ($was_selected == 0) {
		$ret = "<option value='0' selected>&nbsp;</option>" . $ret;
	} else {
		if ($forcezero_option != "") {
			$ret = "<option value='0' style='color:" . OPTIONS_COLOR_ORANGE . "'>$forcezero_option</option>" . $ret;
		}
	}
	if ($was_selected == 0) {
		if ($forcezero_option != "") {
			$ret = "<option value='0' style='color:" . OPTIONS_COLOR_ORANGE . "'>$forcezero_option</option>" . $ret;
		} else {
			$ret = "<option value='0' selected>&nbsp;</option>" . $ret;
		}
	}
*/
	
	$zero_option = ($forcezero_option != "") ? $forcezero_option : "&nbsp;";
	if (strlen(strip_tags($zero_option)) ==  strlen($zero_option)) {
		$zero_selected = ($was_selected == 1) ? "" : "selected";
		$zero_option = "<option value='0' $zero_selected>$zero_option</option>";
	}

	if ($was_selected == 0 || ($was_selected == 1 && $forcezero_evenifwasselected)) {
		$ret = $zero_option . $ret;
	}

	return $ret;
}


function radio_table($table, $value = "_global", $sql_field = "ident", $tag_attr = "", $fixed_hash = array()) {
	global $num_rows, $cms_dbc, $radio_colcnt, $in_backoffice;
	global $msg_bo_it_change, $msg_bo_it_add, $msg_tag_shortcut;

	$ret = "<tr valign=middle>";
	
	$gray = OPTIONS_COLOR_GRAY;
	$table_strict = makestrict($table);

/*
	$tpl_item_rw = "<tr valign=middle><td><input type=radio name='$table' id='{$table}_#ID#' value='#ID#' #CHECKED# #DISABLED#></td><td><label for='{$table}_#ID#'>#IDENT#</label></td></tr>";
	$tpl_wraparound = <<< EOT
<table cellspacing=0 cellpadding=5 style="border:1px solid $gray"><tr valign=middle><td>
	<table cellspacing=0 cellpadding=0>
	#ITEMS#
	</table>
</td></tr></table>
EOT;
	$items = options_sql($query, $value, "", 0, $tpl_item_rw);
	$ret = hash_by_tpl(array("items" => $items), $tpl_wraparound);
*/

	$value = ($value == "_global") ? get_number($table) : intval($value);

	$query = "select id, $sql_field as ident, published"
		. " from $table_strict"
		. " where deleted=0"
		. sqlcond_fromhash($fixed_hash, "", " and ", " and ")
		. " order by " . get_entity_orderby($table_strict);


	$query = add_sql_table_prefix($query);
	$result = mysql_query($query, $cms_dbc) or die("RADIO_OPTIONS_SQL failed:<br>$query:<br>"
			. mysql_error($cms_dbc));
	$num_rows = mysql_num_rows($result);

	$was_selected = 0;
	for ($i=1; $row = mysql_fetch_array($result); $i++) {
		$id = $row["id"];
		$ident = $row["ident"];

		$published = 1;
		if (isset($row["published"])) $published = $row["published"];
		$published_style = ($published == 1) ? "" : " style='color: " . OPTIONS_COLOR_GRAY. "'";
		$checked = ($id == $value) ? "checked" : "";

		$ret .= "<td><input type=radio name='$table' id='{$table}_$id' value='$id' $checked $published_style></td><td><label for='{$table}_$id' $published_style>$ident</label></td><td width=10></td>
";

		if ($i % $radio_colcnt == 0) {
			$ret = "$ret</tr><tr valign=middle>";
		}

	}

	$ret .= "</tr>";
	
	$backoffice_specific = "";

	if ($in_backoffice == 1) {
		$backoffice_specific = <<< EOT
<tr>
	<td align=right><a href="$table_strict.php" target="_blank">$msg_tag_shortcut $msg_bo_it_change</a> &nbsp;&nbsp;&nbsp;<a href="$table_strict-edit.php" target="_blank"><img src="img/shortcut.gif" width=7 height=7 style='border:0px solid #eeeeee' align=absmiddle hspace=2 vspace=2> $msg_bo_it_add</a></td>
</tr>
EOT;
	}
	
	if ($ret != "") {
		$ret = <<< EOT
<table cellspacing=0 cellpadding=0>
<tr>
	<td>
		<table cellspacing=0 cellpadding=5 style="border:1px solid $gray">
		<tr valign=middle>
			<td>
				<table cellspacing=0 cellpadding=0>
				$ret
				</table>
			</td>
		</tr>
		$backoffice_specific
		</table>
	</td>
</tr>
</table>

EOT;
	}

	return $ret;
}

function o2m($table, $value = "_global", $o2m_table = "_global") {
	global $entity, $id, $cms_dbc;
	global $msg_bo_o2m_link_delete, $msg_bo_o2m_link_absent;

	$ret = "";
	
//	if ($value == "_global") $value = get_number($table);

	$o2m_value = 0;

	if (isset($id)) {
		$query = "select $table from $o2m_table where $entity = $id";
		$query = add_sql_table_prefix($query);
		$result = mysql_query($query, $cms_dbc) or die("SELECT O2M_VALUE failed:<br>$query:<br>" . mysql_error($cms_dbc));
		if (mysql_num_rows($result) > 0) {
			$row = mysql_fetch_row($result);
			$o2m_value = $row[0];
		}
	
		$query = "select id, ident from $table order by manorder";
		$options = options_sql($query, $o2m_value);
	}

	if ($o2m_value == 0) $options = "<option value=0 selected>$msg_bo_o2m_link_absent</option>" . $options;
	else $options = "<option value=0>$msg_bo_o2m_link_delete</option>" . $options;

	$ret = select($table, $options);

	return $ret;
}

function o2m_parent($table, $value = "_global", $o2m_table = "_global", $parent_entity_ = "_global") {
	global $entity, $id, $parent_entity;
	global $msg_bo_o2m_link_delete, $msg_bo_o2m_link_absent;

	$ret = "";

	if ($parent_entity_ == "_global") $parent_entity_ = $parent_entity;
	
//	if ($value == "_global") $value = get_number($table);

	$o2m_value = 0;

	if (isset($id)) {
		$query = "select $table from $o2m_table where $entity = $id";
		$query = add_sql_table_prefix($query);
		$result = mysql_query($query, $cms_dbc) or die("SELECT O2M_VALUE failed:<br>$query:<br>" . mysql_error($cms_dbc));
		if (mysql_num_rows($result) > 0) {
			$row = mysql_fetch_row($result);
			$o2m_value = $row[0];
		}
	
		$query = "select e.id, concat(p.ident, ' / ', e.ident) from $table e, $parent_entity_ p where e.$parent_entity_=p.id order by p." . get_entity_orderby($table_strict) . ", e." . get_entity_orderby($table_strict);
		$options = options_sql($query, $o2m_value);
	}

	if ($o2m_value == 0) $options = "<option value=0 selected>$msg_bo_o2m_link_absent</option>" . $options;
	else $options = "<option value=0>$msg_bo_o2m_link_delete</option>" . $options;

	$ret = select($table, $options);

	return $ret;
}

function multiro($table, $value = "_global", $m2m_table = "_global") {
	global $entity, $id, $cms_dbc, $input_size;
	global $msg_bo_fyu;

	$ret = "";
	$options = "";

	$size = $input_size["multi"];
	if (isset($id)) {
		$query = "select e.ident"
			. " from $table e, $m2m_table m2m"
			. " where m2m.$table = e.id and m2m.$entity = $id"
			. " order by e." . get_entity_orderby($table);
		$query = add_sql_table_prefix($query);
		$result = mysql_query($query, $cms_dbc)
				or die("SELECT MULTI_VALUE failed:<br>$query:<br>" . mysql_error($cms_dbc));
		$size = mysql_num_rows($result);

		while ($row = mysql_fetch_assoc($result)) {
			$options .= "<option>" . $row["ident"] . "</option>";
		}

	}

	$ret = "<select multiple size=$size disabled>$options</select>";

	$ret = "<table cellspacing=0 cellpadding=0><tr valign=middle><td>"
		. $ret
		. "</td><td>&nbsp;</td><td>"
		. "<font color=" . OPTIONS_COLOR_GRAY . ">$msg_bo_fyu</font>"
		. "</td></tr></table>";

	return $ret;
}

function multi($table, $value = "_global", $m2m_table = "_global") {
	global $entity, $id, $cms_dbc, $input_size;
	global $msg_bo_select_ctrl_shift;
	
	$ret = "";
	$options = "";

	$size = $input_size["multi"];
	if (isset($id)) {
		$query = "select id, ident from $table order by " . get_entity_orderby($table);
		$query = add_sql_table_prefix($query);
		$result = mysql_query($query, $cms_dbc)
			or die("SELECT MULTI_VALUE failed:<br>$query:<br>" . mysql_error($cms_dbc));
		$size = mysql_num_rows($result);

		while ($row = mysql_fetch_row($result)) {
			$id_ = $row[0];
			$ident_ = $row[1];
			
			if ($ident_ == "root") {
				$size--;
				continue;
			}

			$options .= "<option value='$id_'";

			$query2 = "select m2m.id from $m2m_table m2m where $entity = $id and $table = $id_";
			$query2 = add_sql_table_prefix($query2);
			$result2 = mysql_query($query2, $cms_dbc) or die("SELECT MULTI_OPTION failed:<br>$query2:<br>"
					. mysql_error($cms_dbc));
			if (mysql_num_rows($result2) > 0) {
				$options .= " selected";
			}
		
			$options .= ">$ident_</option>";
		}
	}

	$ret = "<select multiple size=$size name='{$table}[]'>$options</select>";

	$ret = "<table cellspacing=0 cellpadding=0><tr valign=middle><td>"
		. $ret
		. "</td><td>&nbsp;</td><td>"
		. "<font color=" . OPTIONS_COLOR_GRAY . ">$msg_bo_select_ctrl_shift</font>"
		. "</td></tr></table>";

	return $ret;
}

function multi_parent($table, $value = "_global", $m2m_table = "_global", $parent_entity_ = "_global") {
	global $entity, $id, $parent_entity, $cms_dbc;
	global $msg_bo_select_ctrl_shift;
	
	$ret = "";
	$options = "";
	
	if ($parent_entity_ == "_global") $parent_entity_ = $parent_entity;

	$size = 5;
	if (isset($id)) {
		$query = "select e.id, concat(p.ident, ' / ', e.ident) from $table e, $parent_entity_ p where e.$parent_entity_=p.id order by p." . get_entity_orderby($parent_entity_) . ", e." . get_entity_orderby($table);
		$query = add_sql_table_prefix($query);
		$result = mysql_query($query, $cms_dbc) or die("SELECT MULTI_VALUE failed:<br>$query:<br>" . mysql_error($cms_dbc));
		$size = mysql_num_rows($result);

		while ($row = mysql_fetch_row($result)) {
			$id_ = $row[0];
			$ident_ = $row[1];
			
			if ($ident_ == "root") {
				$size--;
				continue;
			}

			$options .= "<option value='$id_'";

			$query2 = "select m2m.id from $m2m_table m2m where $entity = $id and $table = $id_";
			$query2 = add_sql_table_prefix($query2);
			$result2 = mysql_query($query2, $cms_dbc) or die("SELECT MULTI_OPTION failed:<br>$query2:<br>"
					. mysql_error($cms_dbc));
			if (mysql_num_rows($result2) > 0) {
				$options .= " selected";
			}
		
			$options .= ">$ident_</option>";
		}
	}

	$ret = "<select multiple size=$size name='{$table}[]'>$options</select>";

	$ret = "<table cellspacing=0 cellpadding=0><tr valign=middle><td>"
		. $ret
		. "</td><td>&nbsp;</td><td>"
		. "<font color=" . OPTIONS_COLOR_GRAY . ">$msg_bo_select_ctrl_shift</font>"
		. "</td></tr></table>";

	return $ret;
}

function checkbox_table($table, $value_array = "_global", $sql_field = "ident", $tag_attr = ""
		, $fixed_hash = array(), $checkevery_ifempty = 0, $checkevery_evenifwaschecked = 0) {

	global $debug_query, $cms_dbc;

	$ret = "";

	$table_strict = makestrict($table);
	if ($value_array == "_global") $value_array = get_array($table);
	
	$pubdel_cond = "";
	if (entity_has_deleted_field($table_strict)) $pubdel_cond = " and deleted=0";
	if (entity_has_field($table_strict, "published")) $pubdel_cond = " and published=1";

	$query = "select id, $sql_field as ident, published"
		. " from $table_strict"
		. " where 1=1 $pubdel_cond "
		. sqlcond_fromhash($fixed_hash, "", " and ", " and ")
		. " order by " . get_entity_orderby($table_strict);
	if ($debug_query == 1) echo "<br>CHECKBOX_TABLE: [$query]<br>";

	$query = add_sql_table_prefix($query);
	$result = mysql_query($query, $cms_dbc) or die("CHECKBOX_TABLE_SQL failed:<br>$query:<br>"
			. mysql_error($cms_dbc));
	$num_rows = mysql_num_rows($result);

	$was_checked = (count($value_array) > 0) ? 1 : 0;

	for ($i=1; $row = mysql_fetch_array($result); $i++) {
		$id = $row["id"];
		$ident = $row["ident"];

		$published = 1;
		if (isset($row["published"])) $published = $row["published"];
		$published_style = ($published == 1) ? "" : " style='color: " . OPTIONS_COLOR_GRAY. "'";

		$checked = "";
		if ($was_checked == 1) {
			$checked = in_array($id, $value_array) ? "checked" : "";
			if ($checkevery_evenifwaschecked == 1) $checked = "checked";
		} else {
			if ($checkevery_ifempty == 1) $checked = "checked";
		}

		$item = <<< EOT
<tr valign=middle>
	<td><input type="checkbox" name="{$table}[]" id="{$table}_{$id}" value="$id" $checked ></td>
	<td><label for="{$table}_{$id}" $published_style >$ident</label></td>
</tr>

EOT;

		$ret .= $item;
	}

	$ret = <<< EOT
<table cellspacing=0 cellpadding=0>
<tr>
	<td>
		<table cellspacing=0 cellpadding=5 style="border:1px solid #AAAAAA">
		<tr valign=middle>
			<td>
				<table cellspacing=0 cellpadding=0>
				$ret
				</table>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
EOT;

	return $ret;
}


?>