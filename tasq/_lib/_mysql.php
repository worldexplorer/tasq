<?

$cms_dbc = "CMS_DBC NOT INITIALIZED PROPERLY";

$old_display_errors = ini_get("display_errors");
ini_set("display_errors", 1);
$cms_dbc = mysql_connect($mysql_info["host"], $mysql_info["login"], $mysql_info["passwd"]);
ini_set("display_errors", $old_display_errors);
//echo "[$cms_dbc]";

if ($cms_dbc == "") die("cms_dbc: Cant mysql_connect()");
mysql_select_db($mysql_info['db'], $cms_dbc) or die("cms_dbc: Cant mysql_select_db()<br>" . mysql_error($cms_dbc));

#mysql_query("SET NAMES 'cp1251'");
#mysql_query("SET CHARACTER SET cp1251");
#mysql_query("SET SQL_BIG_SELECTS=1");


function parents_back($current_id, $entity) {
	$ret = array();

	$depth_limit = 15;
	for ($i = 1; $i <= $depth_limit; $i++) {
		$current_id = select_field("parent_id", array("id" => $current_id), $entity);
		if ($current_id != 0) {
			array_push($ret, $current_id);
		} else {
			break;
		}
	}
	
	return $ret;
}

function select_published($field = "ident", $fields_cond = 0, $entity = "_global:entity") {
	global $id, $order_dir, $debug_query, $cms_dbc;
	$ret = "";

	$entity = absorb_variable($entity);
//	$entity = prefixed_entity ($entity);

	$select_cond = "published='1'";
	if (is_array($fields_cond)) {
		foreach ($fields_cond as $key => $value) {
			if ($select_cond != "") $select_cond .= " and ";
			$select_cond .= "$key='$value'";
		}
	} else {
		if (isset($id)) $select_cond = "id=$id";
	}
	
	$query = "select $field from $entity where $select_cond";
	$query = add_sql_table_prefix($query);
	if ($debug_query == 1) echo "<br>SELECT_PUBLISHED[$query]<br>";
	$result = mysql_query($query, $cms_dbc) or die("SELECT UNIQUE failed:<br>$query<br>" . mysql_error($cms_dbc));
	if (mysql_num_rows($result) > 0) {
		$row = mysql_fetch_row($result);
		$ret = $row[0];
	}
	
	return $ret;
}

function select_field($field = "ident", $fields_cond = 0, $entity = "_global:entity") {
	global $id, $order_dir, $debug_query, $cms_dbc;
	$ret = "";

	$entity = absorb_variable($entity);
//	$entity = prefixed_entity ($entity);

	$select_cond = "";
	if (is_array($fields_cond)) {
/*		foreach ($fields_cond as $key => $value) {
			if ($select_cond != "") $select_cond .= " and ";
			if (substr($key, -1, 1) == "~") {
				$key = substr($key, 0, -1);
				$select_cond .= "$key LIKE '$value'";
			} else {
				$select_cond .= "$key='$value'";
			}
		}
*/
		$select_cond = sqlcond_fromhash($fields_cond);
	} else {
		if (isset($id)) $select_cond = "id=$id";
	}
	
	if ($select_cond != "") $select_cond = " where $select_cond";
	$query = "select $field from $entity $select_cond order by " . get_entity_orderby($entity) . " limit 1";
	$query = add_sql_table_prefix($query);
	$result = mysql_query($query, $cms_dbc) or die("SELECT FIELD failed:<br>$query<br>" . mysql_error($cms_dbc));
	if ($debug_query == 1) echo "<br>SELECT FIELD[$query]:" . mysql_num_rows($result) . "<br>";
	if (mysql_num_rows($result) > 0) {
		$row = mysql_fetch_row($result);
		$ret = $row[0];
//		$ret = stripslashes($ret);
	}
	
	return $ret;
}

function select_fieldarray($field = "ident", $fixed_hash = 0, $entity = "_global:entity") {
	global $id, $order_dir, $debug_query, $cms_dbc;
	$ret = array();

	$entity = absorb_variable($entity);
//	$entity = prefixed_entity ($entity);

	$select_cond = sqlcond_fromhash($fixed_hash);

	$query = "select $field from $entity where $select_cond order by ". get_entity_orderby($entity);
	$query = add_sql_table_prefix($query);
	$result = mysql_query($query, $cms_dbc) or die("SELECT SELECT_FIELDARRAY failed:<br>$query<br>" . mysql_error($cms_dbc));
	if ($debug_query == 1) echo "<br>SELECT SELECT_FIELDARRAY[$query]:" . mysql_num_rows($result) . "<br>";
	while ($row = mysql_fetch_row($result)) {
		$ret[] = $row[0];
	}
	
	return $ret;
}

function select_fieldlistarray($field_list = "ident", $fixed_hash = 0, $entity = "_global:entity") {
	global $id, $order_dir, $debug_query, $cms_dbc;
	$ret = array();

	$entity = absorb_variable($entity);
//	$entity = prefixed_entity($entity);

	$select_cond = sqlcond_fromhash($fixed_hash, "", " where ");

	$query = "select $field_list from $entity $select_cond order by " . get_entity_orderby($entity);
	$query = add_sql_table_prefix($query);
	$result = mysql_query($query, $cms_dbc) or die("SELECT SELECT_FIELDARRAY failed:<br>$query<br>" . mysql_error($cms_dbc));
	if ($debug_query == 1) echo "<br>SELECT SELECT_FIELDARRAY[$query]:" . mysql_num_rows($result) . "<br>";
	$rows_total = mysql_num_rows($result);
	
	$i = 0;
	while ($row = mysql_fetch_assoc($result)) {
		$row["entity"] = $entity;
		$row["i"] = ++$i;
		$row["rows_total"] = $rows_total;
		$ret[] = $row;
	}
	
	return $ret;
}

function select_queryarray($query, $entity = "_global:entity", $cms_dbc = "_global:cms_dbc") {
	global $debug_query, $cms_dbc;
	$ret = array();

	$entity = absorb_variable($entity);
//	$entity = prefixed_entity ($entity);
	$cms_dbc = absorb_variable($cms_dbc);

	$query = add_sql_table_prefix($query);
	$result = mysql_query($query, $cms_dbc) or die("SELECT_QUERYARRAY failed:<br>$query<br>" . mysql_error($cms_dbc));
	if ($debug_query == 1) echo "<br>SELECT SELECT_QUERYARRAY[$query]:" . mysql_num_rows($result) . "<br>";
	$rows_total = mysql_num_rows($result);
	
	$i = 0;
	while ($row = mysql_fetch_assoc($result)) {
		$row["i"] = ++$i;
		$row["rows_total"] = $rows_total;
		if (!isset($row["entity"])) $row["entity"] = $entity;
		$ret[] = $row;
	}
	
	return $ret;
}

function flatten_queryrarray($raw_queryarray) {
	$ret = array();

	foreach($raw_queryarray as $row) {
		$ret[$row["hashkey"]] = $row["value"];
	}

	return $ret;
}

function collect_rows_unique ($rows_collecting, $rows_collected) {
	foreach ($rows_collecting as $row_collecting) {
		if (!in_array($row_collecting, $rows_collected)) {
//			$rows_collected = array_merge($rows_collected, $row_collecting);
			$rows_collected[] = $row_collecting;
//			echo "hadnt:";
//			pre ($row_collecting);
		} else {
//			echo "had already:";
//			pre ($row_collecting);
		}
	}
	return $rows_collected;
}


function select_entity_row($fixed_hash = 0, $entity = "_global:entity") {
	global $id, $debug_query, $cms_dbc;
	$ret = array();
	
	$entity = absorb_variable($entity);
//	$entity = prefixed_entity ($entity);

	$select_cond = "";
	if (is_array($fixed_hash)) {
		$select_cond = sqlcond_fromhash($fixed_hash);
	} else {
		if (isset($id)) $select_cond = "id=$id";
	}	

	if ($select_cond != "") {
		$query = "select * from $entity where $select_cond order by " . get_entity_orderby($entity);
		$query = add_sql_table_prefix($query);
		if ($debug_query == 1) echo "<br>SELECT_ENTITY_ROW[$query]<br>";
		$result = mysql_query($query, $cms_dbc) or die("SELECT_ENTITY_ROW failed:<br>$query<br>" . mysql_error($cms_dbc));
		if (mysql_num_rows($result) > 0) {
			$ret = mysql_fetch_array($result, MYSQL_ASSOC);
			$ret["entity"] = $entity;
		}
	}
	return $ret;
}



function insert ($fields = array(), $entity = "_global:entity", $cms_dbc = "_global:cms_dbc") {
	global $order_fields, $debug_query, $updatemanorder_whileinsert;
	$field_names = "";
	$field_values = "";

	$entity = absorb_variable($entity);
//	$entity = prefixed_entity ($entity);
	$cms_dbc = absorb_variable($cms_dbc);
	
	if (is_array($fields) && sizeof($fields) > 0) {
		foreach ($fields as $key => $value) {
			if ($field_names != "") $field_names .= ", ";
			if ($field_values != "") $field_values .= ", ";
			
			$field_names .= "$key";
//			if (is_numeric($value) || ($value == "CURRENT_TIMESTAMP")) {
//			if (is_numeric($value) || (strpos($value, "CURRENT_TIMESTAMP") !== false)) {
			if (strpos($value, "CURRENT_TIMESTAMP") !== false) {
				$field_values .= "$value";
			} else {
				$field_values .= "'" . addslashes($value) . "'";
			}
		}
		
		$query = "insert into $entity ($field_names) values ($field_values)";
		$query = add_sql_table_prefix($query);
		if (mysql_query($query, $cms_dbc)) {
			$id = mysql_insert_id($cms_dbc);
			if ($debug_query == 1) echo "<br>INSERT[$query]:$id<br>";
			if ($updatemanorder_whileinsert == 1) {
//				$order_field = $order_fields[0];
//				foreach ($order_fields as $order_field) {
					$order_field = "manorder";
					$query = "update $entity set $order_field=$id where id=$id";
					$query = add_sql_table_prefix($query);
					mysql_query($query, $cms_dbc)
						or die("UPDATE ORDER $field failed:<br> $query<br>" . mysql_error($cms_dbc));
					if ($debug_query == 1) echo "<br>UPDATE[$query]:" . mysql_affected_rows($cms_dbc) . "<br>";
//				}
			}
		} else {
			die("INSERT failed:<br> $query<br>" . mysql_error($cms_dbc));
		}
	}
		
	return $id;
}

function update ($fields, $fields_cond = 0, $entity = "_global:entity", $cms_dbc = "_global:cms_dbc") {
	global $id, $debug_query;
	$ret = "";
	$fields_update = "";
	$update_cond = "";
	
	$entity = absorb_variable($entity);
//	$entity = prefixed_entity ($entity);
	$cms_dbc = absorb_variable($cms_dbc);

	if (is_array($fields) && sizeof($fields) > 0) {
		foreach ($fields as $key => $value) {
			if ($fields_update != "") $fields_update .= ", ";
			
//			if (is_numeric($value) || ($value == "CURRENT_TIMESTAMP") || ($value == "LAST_INSERT_ID()")) {
			if (is_numeric($value) || (strpos($value, "CURRENT_TIMESTAMP") !== false) || ($value == "LAST_INSERT_ID()")) {
				$fields_update .= "$key=$value";
			} else {
				if (is_array($value)) {
//					echo "$key is array(" . pr ($value) . ")<br>";
					continue;
				}
				$fields_update .= "$key='" . addslashes($value) . "'";
			}
		}
		
		if (is_array($fields_cond)) {
			foreach ($fields_cond as $key => $value) {
				if ($update_cond != "") $update_cond .= " and ";
				$update_cond .= "$key='$value'";
			}
		} else {
			if (isset($id)) $update_cond = "id=$id";
		}	
		
		$query = "update $entity set $fields_update where $update_cond";
		$query = add_sql_table_prefix($query);
		if ($debug_query == 1) echo "<br>UPDATE[$query]";
		mysql_query($query, $cms_dbc) or die("UPDATE failed:<br>$query<br>" . mysql_error($cms_dbc));
		$ret = mysql_affected_rows($cms_dbc);
		if ($debug_query == 1) echo ":[$ret]<br>";
	}

	return $ret;
}

function delete ($fields_cond = 0, $entity = "_global:entity", $cms_dbc = "_global:cms_dbc") {
	global $debug_query;
	$delete_cond = "";
	
	$entity = absorb_variable($entity);
//	$entity = prefixed_entity ($entity);
	$cms_dbc = absorb_variable($cms_dbc);

	if (is_array($fields_cond)) {
/*
		foreach ($fields_cond as $key => $value) {
			if ($delete_cond != "") $delete_cond .= " and ";
			$delete_cond .= "$key='$value'";
		}
*/

		$delete_cond = sqlcond_fromhash($fields_cond);

	} else {
		if (isset($id)) $delete_cond = " and id=$id";
	}
	
	$entity_has_deleted_field = entity_has_deleted_field($entity);

	$query = "delete from $entity where $delete_cond";
	if ($entity_has_deleted_field == 1) {
		$query = "update $entity set deleted=1 where $delete_cond";
	}

	$query = add_sql_table_prefix($query);
	mysql_query($query, $cms_dbc) or die("DELETE failed:<br>$query<br>" . mysql_error($cms_dbc));
	if ($debug_query == 1) echo "<br>DELETE[$query]:" . mysql_affected_rows($cms_dbc). "<br>";
	
	return mysql_affected_rows($cms_dbc);
}

function entity_present_in_db($entity) {
	global $mysql_info;
	$ret = 0;

	static $dbtables = array();
	if (count($dbtables) == 0) {
//mysql_list_tables() is deprecated
//		$dbtables_result = mysql_list_tables($mysql_info["db"]);
//		for ($i = 0; $i < mysql_num_rows($dbtables_result); $i++) {
//		    $dbtables[] =  mysql_tablename($dbtables_result, $i);
//		}

		$dbtables_result = mysql_query("SHOW TABLES");
		for ($i = 0; $i < mysql_num_rows($dbtables_result); $i++) {
		    $dbtables[] =  mysql_tablename($dbtables_result, $i);
		}

//		pre($dbtables);
	}
	if (TABLE_PREFIX != "" && strpos($entity, TABLE_PREFIX) !== 0) $entity = TABLE_PREFIX . $entity;
//	pre($dbtables);
//	pre($entity);

	if (in_array($entity, $dbtables)) $ret = 1;
	
	return $ret;
}

function entity_has_deleted_field($entity) {
	global $cms_dbc;

	$ret = 0;

//	if (TABLE_PREFIX != "" && strpos($entity, TABLE_PREFIX) !== 0) $entity = TABLE_PREFIX . $entity;
	$ret = entity_has_field($entity, "deleted");
	
	return $ret;
}

function entity_has_field($entity, $field, $create_absent = 0, $createdfield_copyfrom = "") {
	global $mysql_info, $non_prefixed_fields;
	$ret = 0;

//	pre(TABLE_PREFIX . $entity);
// delete() passes here already prefixed, and rows are deleted instead of update deleted=1
//	if (!entity_present_in_db(TABLE_PREFIX . $entity)) return $ret;
//	if (!entity_present_in_db($entity)) return $ret;

	if (TABLE_PREFIX != "" && strpos($entity, TABLE_PREFIX) !== 0) $entity = TABLE_PREFIX . $entity;
	if (!entity_present_in_db($entity)) return $ret;
	
	static $entity_dbfields_array = array();

	if (!isset($entity_dbfields_array[$entity])) {
//		$entity_dbfields_result = mysql_list_fields($mysql_info["db"], TABLE_PREFIX . $entity);
		$entity_dbfields_result = mysql_list_fields($mysql_info["db"], $entity);
	
		for ($i = 0; $i < mysql_num_fields($entity_dbfields_result); $i++) {
		    $entity_dbfields_array[$entity][] =  mysql_field_name($entity_dbfields_result, $i);
	//		pre(mysql_field_name($entity_dbfields_result, $i));
		}
	}
//	pre($entity_dbfields_array);

	if (in_array($field, $entity_dbfields_array[$entity])) {
		$ret = 1;
//		pre("in_array($field, " . pr($entity_dbfields_array[$entity])) . ")";
	} else {
//		pre("!in_array($field, " . pr($entity_dbfields_array[$entity])) . "), checking prefixed";
		if (!in_array($field, $non_prefixed_fields)) {
			$field_prefixed = TABLE_PREFIX . $field;
			if (in_array($field_prefixed, $entity_dbfields_array[$entity])) $ret = 1;
//			pre("in_array($field_prefixed, " . pr($entity_dbfields_array[$entity])) . ") = $ret";
		}

//		if (!in_array($field, $non_prefixed_fields)) $field = TABLE_PREFIX . $field;
//		if (in_array($field, $entity_dbfields_array[$entity])) $ret = 1;

	}
	
	if ($ret == 0 && $create_absent == 1) {
		if ($createdfield_copyfrom != "" && !entity_has_field($entity, $createdfield_copyfrom)) {
			pre("entity_has_field($entity, $field, $create_absent): NO FIELD createdfield_copyfrom=[$createdfield_copyfrom], new field=[$field] not created");
		} else {
			$query_addfield = "alter table " . $entity . " add $field "
					. get_dbcolumn_definition($entity, $createdfield_copyfrom);
//			pre($query_addfield);

			$result = mysql_query($query_addfield);
			if ($result) {
				if ($createdfield_copyfrom != "") {
					$query_copyfrom = "update $entity set $field=$createdfield_copyfrom";
//					pre($query_copyfrom);
					$result = mysql_query($query_copyfrom)
							or die("UPDATE failed:<br>$query_copyfrom<br>" . mysql_error());
					$updated = mysql_affected_rows();
					if ($updated == 0) {
				    	pre("entity_has_field($entity, $field, create_absent, from=[$createdfield_copyfrom]: query_copyfrom=[$query_copyfrom] failed:" . mysql_error());
					} else {
				    	pre("entity_has_field($entity, $field, create_absent, from=[$createdfield_copyfrom]: copied=[$updated] rows");
					}
				} else {
			    	pre("entity_has_field($entity, $field, create_absent, from=[$createdfield_copyfrom]: created new field, no column to copy from");
				}
			} else {
		    	pre("entity_has_field($entity, $field, create_absent, from=[$createdfield_copyfrom]: query_addfield=[$query_addfield] failed:" . mysql_error());
				return $ret;
			}

		}
	}
	
//	pre($entity_dbfields_array);
	
	return $ret;
}


function get_dbcolumn_definition($entity, $field) {
	$ret = "";

	$query = "show columns from $entity like '$field'";
//	pre($query);

	$result = mysql_query($query);
	if (!$result) {
    	pre("get_dbcolumn_definition($entity, $field): [$query] failed:" . mysql_error());
		return $ret;
	}
	if (mysql_num_rows($result) > 0) {
	    $row = mysql_fetch_assoc($result);
//        pre($row);
        if ($row["Field"] == $field) {
        	$ret = strtoupper($row["Type"])
        		. (($row["Null"] != "NO") ? " NOT NULL" : "")
        		. (($row["Default"] != "") ? " DEFAULT '" . $row["Default"] . "'" : "")
//        		. ($row["Key"] != "UNI") ? " KEY" : ""
        		;
	    }
	}
	
	return $ret;
}



function select_root_field($field, $parent_field = "parent_id", $child_field = "id", $entity = "_global:entity", $id = "_global:id") {
	global $cms_dbc;

	$entity = absorb_variable($entity);
//	$entity = prefixed_entity ($entity);
	$id = absorb_variable($id);

	$up = $id;
	
	$parent_id = select_field("$parent_field", array($child_field => $up), $entity);
	
	while ($parent_id > 1) {
		$up = $parent_id;
		$parent_id = select_field("$parent_field", array($child_field => $up), $entity);
	}
	
	$ret = select_field($field, array($child_field => $up), $entity);
	
	return $ret;

}

function select_root_tree($entity, $id, $init_static = 1) {
	static $ret = array();
	
	if ($init_static == 1) $ret = array();

	$ret[] = $id;

	$query = "select parent_id from $entity where id=$id order by manorder";
	$query = add_sql_table_prefix($query);
	$result = mysql_query($query) or die("SELECT_ROOT_TREE failed:<br>$query<br>" . mysql_error());
	if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$parent_id = $row["parent_id"];

		if ($parent_id == 1) {
//			$ret[] = $parent_id;
		} else {
			select_root_tree($entity, $parent_id, 0);
		}
	}
	
	return $ret;
}

function select_root_tree_content($entity, $id, $init_static = 1) {
	static $chain = array();
	static $hash = array();
	
	if ($init_static == 1) $chain = array();
	if ($init_static == 1) $hash = array();

	$chain[] = $id;

	$query = "select * from $entity where id=$id order by manorder";
	$query = add_sql_table_prefix($query);
	$result = mysql_query($query) or die("SELECT_ROOT_TREE_CONTENT failed:<br>$query<br>" . mysql_error());
	if ($row = mysql_fetch_assoc($result)) {
		$row["entity"] = $entity;
		$parent_id = $row["parent_id"];
		$hash[$id] = $row;

		if ($parent_id == 1) {
//			$ret[] = $parent_id;
		} else {
			select_root_tree_content($entity, $parent_id, 0);
		}
	}
	
	return $hash;
}

function select_parent($field, $entity = "_global:entity", $id = "_global:id") {
	global $cms_dbc;
	$ret = 0;
	
	$entity = absorb_variable($entity);
//	$entity = prefixed_entity ($entity);
	$id = absorb_variable($id);

	$query = "select $field from $entity where id=$id";
	$query = add_sql_table_prefix($query);
	$result = mysql_query($query, $cms_dbc) or die("SELECT PARENT failed:<br>$query<br>" . mysql_error($cms_dbc));
	if (mysql_num_rows($result) > 0) {
		$row = mysql_fetch_row($result);
		$ret = $row[0];
	}
	
	return $ret;
}

function select_first_child($field, $entity = "_global:entity", $id = "_global:id") {
	global $entity, $id, $cms_dbc;
	$ret = 0;
	
	$entity = absorb_variable($entity);
//	$entity = prefixed_entity ($entity);
	$id = absorb_variable($id);

	$query = "select $field from $entity where parent_id=$id order by manorder";
	$query = add_sql_table_prefix($query);
	$result = mysql_query($query, $cms_dbc) or die("SELECT CHILD failed:<br>$query<br>" . mysql_error($cms_dbc));
	if (mysql_num_rows($result) > 0) {
		$row = mysql_fetch_row($result);
		$ret = $row[0];
	}
	
	return $ret;
}

function wrap_array2sql($cond_array) {
	$ret = "";
	
	if (is_array($cond_array) && sizeof($cond_array) > 0) {
		foreach ($cond_array as $key => $value) {
			if ($ret != "") $ret .= " and ";
			$ret .= "$key='$value'";
		}
	}
	
	return $ret;
}

function select_entity_prevnext($fixed_hash = array(),
		$entity = "_global:entity", $current_id = "_global:id",
		$tpl_hash = array(
			"prev" => "&laquo;&nbsp;<a href='#SCRIPT_NAME#?id=#ID#&#FIXED_SUFFIX#' title='#IDENT#'>предыдущий элемент</a> (#CNT#)",
			"prev_empty" => "",
			"next" => "(#CNT#) <a href='#SCRIPT_NAME#?id=#ID#&#FIXED_SUFFIX#' title='#IDENT#'>следующий элемент</a>&nbsp;&raquo;",
			"next_empty" => "",

			"query_prev" => "",
			"query_next" => "",
//			"query_prev" => "select id, ident from " . TABLE_PREFIX . "#ENTITY# where #ORDER_FIELD# < '#ORDER_VALUE#' #FIXED_COND# order by #ORDER_FIELD# desc",
//			"query_next" => "select id, ident from " . TABLE_PREFIX . "#ENTITY# where #ORDER_FIELD# > '#ORDER_VALUE#' #FIXED_COND# order by #ORDER_FIELD# asc",
			),
		$href_fixed_hash = array(),
		$addslashes = 1
		) {
	global $cms_dbc, $debug_query, $entity_manorder_list;

	$result_hash = array("prev" => "[next ahref generation error]", "next" => "[prev ahref generation error]");
	
//	if ($entity == "_global") $entity_ = $entity;
//	if ($current_id_ == "_global") $current_id_ = $id;

	$entity = absorb_variable($entity);
//	$entity = prefixed_entity ($entity);
	$current_id = absorb_variable($current_id);

//	if (count($href_fixed_hash) > 0) $href_fixed_hash = $fixed_hash;
	$fixed_suffix = hrefsuffix_fromhash($href_fixed_hash, "&");

	if (entity_has_deleted_field($entity)) $fixed_hash["deleted"] = 0;

//	while product-edit.php, got an error
//select * from webie_product where manorder < '1' and webie_pgroup='2' and webie_supplier='1' order by manorder desc
//	$fixed_cond = sqlcond_fromhash($fixed_hash, "", "", "and",  TABLE_PREFIX);

	$fixed_cond = sqlcond_fromhash($fixed_hash, "", "", "and");

	$order_field = get_entity_orderfield($entity);
	$order_dir = get_entity_orderdir($entity);
	$order_value = select_field($order_field, array("id" => $current_id), $entity);
	if ($addslashes == 1) $order_value = addslashes($order_value);

	if ($fixed_cond != "") $fixed_cond = " and " . $fixed_cond;
	$query_prev = "select * from $entity where $order_field < '$order_value' $fixed_cond order by $order_field desc";
	$query_next = "select * from $entity where $order_field > '$order_value' $fixed_cond order by $order_field asc";

	$hash_4query = array (
		"entity" => $entity,
		"order_field" => $order_field,
		"order_value" => $order_value,
		"fixed_cond" => $fixed_cond,
		"order_field" => $order_field,
	);
	
	if (isset($tpl_hash["query_prev"]) && $tpl_hash["query_prev"] != "") $query_prev = hash_by_tpl($hash_4query, $tpl_hash["query_prev"]);
	if (isset($tpl_hash["query_next"]) && $tpl_hash["query_next"] != "") $query_next = hash_by_tpl($hash_4query, $tpl_hash["query_next"]);

	if ($order_dir == "desc") {
		$query_tmp = $query_prev;
		$query_prev = $query_next;
		$query_next = $query_tmp;
	}

	if ($query_prev != "" && $query_next != "") {
//		echo "query_prev=[$query_prev] ";
//		echo "query_next=[$query_next] ";

		$query_prev = add_sql_table_prefix($query_prev);
		if ($debug_query == 1) echo "<br>SELECT_ENTITY_PREVNEXT: PREV[$query_prev]<br>";
		$result = mysql_query($query_prev) or die("SELECT PREV failed:<br> $query_prev<br>" . mysql_error());
		$cnt = mysql_num_rows($result);
		if ($cnt > 0) {
			$row = mysql_fetch_assoc($result);
			$row["cnt"] = $cnt;
			$row["script_name"] = $_SERVER["SCRIPT_NAME"];
			$row["fixed_suffix"] = $fixed_suffix;
			$row["ident"] = strip_tags($row["ident"]);
			$row["ident"] = htmlspecialchars($row["ident"], ENT_QUOTES);

			$tpl = $tpl_hash["prev"];
		} else {
			$row = array();
			$tpl = $tpl_hash["prev_empty"];
		}
		$result_hash["prev"] = hash_by_tpl($row, $tpl);
	

		$query_next = add_sql_table_prefix($query_next);
		if ($debug_query == 1) echo "<br>SELECT_ENTITY_PREVNEXT: NEXT[$query_next]<br>";
		$result = mysql_query($query_next) or die("SELECT NEXT failed:<br> $query_next<br>" . mysql_error());
		$cnt = mysql_num_rows($result);
		if ($cnt > 0) {
			$row = mysql_fetch_assoc($result);
			$row["cnt"] = $cnt;
			$row["script_name"] = $_SERVER["SCRIPT_NAME"];
			$row["fixed_suffix"] = $fixed_suffix;
			$row["ident"] = strip_tags($row["ident"]);
			$row["ident"] = htmlspecialchars($row["ident"], ENT_QUOTES);

			$tpl = $tpl_hash["next"];
		} else {
			$row = array();
			$tpl = $tpl_hash["next_empty"];
		}
		$result_hash["next"] = hash_by_tpl($row, $tpl);
	}
	
	return $result_hash;
}

function select_first_published($field = "id", $fixed_hash = array(), $entity = "_global:entity") {
	$ret = "";
	
	$fixed_hash["published"] = 1;
	$ret = select_first($field, $fixed_hash, $entity);

	return $ret;
}

function select_first($field = "id", $fixed_hash = array(), $entity = "_global:entity") {
	global $debug_query, $cms_dbc;
	$ret = "";
	
	$entity = absorb_variable($entity);
//	$entity = prefixed_entity ($entity);

	$fixed_cond = sqlcond_fromhash($fixed_hash);
	if ($fixed_cond != "") $fixed_cond = " where $fixed_cond";

	$query = "select * from $entity $fixed_cond order by " . get_entity_orderby($entity);
	$query = add_sql_table_prefix($query);
	if ($debug_query == 1) echo "<br>SELECT_FIRST_PUBLISHED[$query]<br>";
	$result = mysql_query($query, $cms_dbc) or die("SELECT_FIRST failed:<br>$query<br>" . mysql_error($cms_dbc));
	if (mysql_num_rows($result) > 0) {
		$row = mysql_fetch_assoc($result);
		$ret = $row[$field];
//		$ret = stripslashes($ret);
	}

	return $ret;
}

function hash_by_tpl ($row, $tpl, $entity = "_global:entity", $wrap_breaks = 1, $call_dogs = 1) {
	global $entity, $row_callback, $date_fmt, $slashes_ok0_strip1, $in_backoffice;

	static $rows_stack = array();

	$ret = "";

	$entity = absorb_variable($entity);
//	pre($entity);
//	$entity = TABLE_PREFIX . $entity;
//	if (strpos($entity, TABLE_PREFIX) === false) $entity = TABLE_PREFIX . $entity;

	if (is_array($row)
//			&& ((strpos($tpl, "#") !== false) || (strpos($tpl, "@") !== false))
			) {
		$ret = $tpl;

		foreach ($row as $field => $value) {
			if (is_array($value)) continue;		// только первый уровень вложенных массивов - наш

			// turn off for textarea not to eat typed \
			if ($slashes_ok0_strip1 == 1) $value = stripslashes($value);

			if ($wrap_breaks == 1) {
				$value = possible_html($value);
			} else {
				// when filling FreetextFileTemplate with data, does not insert breaks
			}
	
			if (!isset($date_fmt)) $date_fmt = "%a %d-%b-%Y %R:%M";
			if (substr(strtolower($field), 0, 4) == "date" && $in_backoffice == 0) {
				$value = ts2human($value); // used for timestamp and datetime, formatted as filled
//				$value = strftime($date_fmt, $value);
			}
	
			$ret = str_replace("#".strtoupper($field)."#", $value, $ret);
			$ret = str_replace("#".strtolower($field)."#", $value, $ret);
//			$ret = preg_replace("/#(\w*)#/i", $value, $ret);
		}
	
		$ret = str_replace("#ENTITY#", $entity, $ret);
		$ret = str_replace("#HTTP_HOST#", $_SERVER["HTTP_HOST"], $ret);
//		$ret = preg_replace("/#ENTITY#/i", $entity, $ret);
	
		$stack_depth = array_push($rows_stack, $row_callback);
//		echo " pushed [stack_depth = $stack_depth] ";
	
		$row_callback = $row;
		
		if ($call_dogs == 1) {
//			pre($row);
			$ret = preg_replace_callback("/@(\w*)@/",
				create_function(
					'$matches',
					'global $row_callback; if (function_exists($matches[1])) return $matches[1]($row_callback);'
					),
				$ret);
		}
	
		$row_callback = array_pop($rows_stack);
//		echo " popped [stack_depth = " . sizeof($stack_depth) . "] ";
	
//		$ret = preg_replace_callback("/@(\S*)@/", "row_callback_wrapper", $ret);

	}

	return $ret;
}

function query_by_tpl($query, $tpl, $tpl_current = "_same", $entity = "_global:entity", $current = "_global:id", $hash_concat = array()) {
	global 	$cms_dbc, $rows_total;
	$ret = "";

	if ($tpl_current == "_same") $tpl_current = $tpl;
	$entity = absorb_variable($entity);
//	$entity = prefixed_entity ($entity);
	$current = absorb_variable($current);

	$query = add_sql_table_prefix($query);
	$result = mysql_query($query, $cms_dbc) or die("SELECT QUERY_BY_TPL failed:<br>$query<br>" . mysql_error($cms_dbc));
	$rows_total = mysql_num_rows($result);

	$i = 0;
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$item_tmp = $tpl;

		if (isset($row["id"])) {
			if ($row["id"] == $current) $item_tmp = $tpl_current;
		}

		$row["rows_total"] = $rows_total;
		$row["i"] = ++$i;
		if (!isset($row["entity"])) $row["entity"] = $entity;
//		echo "[" . $row ["i"] . "|" . $row["rows_total"] . "] ";

		$row = array_merge($row, $hash_concat);
//		pre($row);
		$item_tmp = hash_by_tpl($row, $item_tmp, $entity);

		$ret .= $item_tmp;
	}
	
	return $ret;
}


function entity_list_tpl($tpl, $tpl_current = "_same", $entity = "_global:entity", $current = "_global:id"
		, $fixed_hash = array(), $hash_concat = array()
		, $rows_per_page = "_global:rows_per_page", $list_url = "", $hrefsuffix_hash = array()) {

	global $debug_query;
	global $no_pager, $no_pager_list, $limit_sql, $rows_total, $pager_HTML;
	$ret = "";
	
	if ($tpl_current == "_same") $tpl_current = $tpl;
	$entity = absorb_variable($entity);
//	$entity = prefixed_entity ($entity);
	$current = absorb_variable($current);

// future personal $rows_per_page require saving&resotring global $rows_per_page or pager() rewrite...
// hard to use with so much parameters
	$rows_per_page = absorb_variable($rows_per_page);
	$list_url = absorb_variable($list_url);

	$visible_mask = array("published" => 1, "deleted" => 0);
	$select_cond = sqlcond_fromhash(array_merge($visible_mask, $fixed_hash));

	$list_query = "select * from $entity where $select_cond order by " . get_entity_orderby($entity);
	if ($debug_query == 1) echo "<br>SELECT ENTITY_LIST_TPL[$list_query]<br>";

	$restore_from_backup = 0;		// if query was limited query_by_tpl will set $rows_total = $rows_per_page
	$rows_total_backup = 0;

	if ($no_pager == 0) {
		if (!in_array($entity, $no_pager_list)) {
			$list_query_cnt = "select count(id) as cnt from $entity where $select_cond";
			$list_query_cnt = add_sql_table_prefix($list_query_cnt);
			$result = mysql_query($list_query_cnt) or die("SELECT ENTITY_LIST_TPL_CNT failed:<br>$list_query_cnt<br>" . mysql_error());
			$row = mysql_fetch_array($result);
			$rows_total = $row["cnt"];
		
			if ($rows_total > $rows_per_page) {
				if ($list_url == "") $list_url = $_SERVER["SCRIPT_NAME"] . "?" . hrefsuffix_fromhash($hrefsuffix_hash);
				$pager_HTML = pager($list_url, $rows_total);
				$list_query .= $limit_sql;
				$rows_total_backup = $rows_total;
				$restore_from_backup = 1;
			}
		}
	} else {
		if ($rows_per_page > 0) $list_query .= " limit $rows_per_page";
	}

	if ($debug_query == 1) echo "<br>SELECT ENTITY_LIST_TPL[$list_query]<br>";
	$ret = query_by_tpl($list_query, $tpl, $tpl_current, $entity, $current, $hash_concat);
	if ($restore_from_backup == 1) $rows_total = $rows_total_backup;

	return $ret;
}

function hash_list_tpl($tpl, $tpl_current = "_same", $rows_array, $current = "_global:id") {
	global $id;
	$ret = "";

	if ($tpl_current == "_same") $tpl_current = $tpl;
	$current = absorb_variable($current);

	foreach ($rows_array as $row) {
		$item_tmp = $tpl;

		if (isset($row["id"])) {
			if ($row["id"] == $current) $item_tmp = $tpl_current;
		}

		$ret .= hash_by_tpl($row, $item_tmp, "_hashsource");
	}
	
	return $ret;
}

function entity_tpl($tpl, $entity = "_global:entity", $fixed_hash = array(), $hash_concat = array()) {
	global $cms_dbc;
	$ret = "";
	
	$entity = absorb_variable($entity);
//	$entity = prefixed_entity ($entity);
	$current = absorb_variable("_global:id");
	
	if (is_array($fixed_hash) && count($fixed_hash) > 0) {
		$visible_mask = array("deleted" => 0, "published" => 1);
		$select_cond = sqlcond_fromhash(array_merge($visible_mask, $fixed_hash));
	} else {
		$select_cond = sqlcond_fromhash(array("id" => $current));
	}
	
	if ($select_cond != "") $select_cond = "where $select_cond";

	$query = "select * from $entity $select_cond";
	$query = add_sql_table_prefix($query);
	$result = mysql_query($query, $cms_dbc) or die("SELECT ENTITY_TPL failed:<br>$query<br>" . mysql_error($cms_dbc));
	$rows_total = mysql_num_rows($result);

	if ($rows_total > 0) {
		$row = mysql_fetch_assoc($result);
		$row["rows_total"] = $rows_total;
		$row["entity"] = $entity;

		$ret = hash_by_tpl(array_merge($row, $hash_concat), $tpl, $entity);
	}

	return $ret;
}



function tree_tpl($tpl, $tpl_current = "_same", $entity = "_global:entity", $current = "_global:id"
	, $parent_id = 1, $fixed_hash = array()
	, $level = 1, $level_stop = 0, $down_wrapper_tpl = "#DOWN_CONTENT#", $query_tpl = ""
	, $cms_dbc = "_global:cms_dbc") {

	global $debug_query;
	$ret = "";

	$cms_dbc = absorb_variable($cms_dbc);

//	echo "level=[$level] level_stop=[$level_stop] parent_id=[$parent_id] current=[$current]<br>";
	if ($tpl_current == "_same") $tpl_current = $tpl;
	$entity = absorb_variable($entity);
//	$entity = prefixed_entity ($entity);
	if (is_string($current)) $current = absorb_variable($current);

	$visible_mask = array("deleted" => 0);
	$select_cond = sqlcond_fromhash(array_merge($visible_mask, $fixed_hash));

	if ($query_tpl == "") {
		$query = "select * from $entity where parent_id=$parent_id and $select_cond order by "
			. get_entity_orderby($entity);
	} else {
		$query = hash_by_tpl(array("parent_id" => $parent_id), $query_tpl);
	}

	$query = add_sql_table_prefix($query);
	if ($debug_query == 1) echo "<br>SELECT TREE_TPL[$query]<br>";
	$result = mysql_query($query, $cms_dbc) or die("TREE_TPL failed:<br>$query:<br>" . mysql_error($cms_dbc));
	$rows_total = mysql_num_rows($result);

	$i = 0;
	while($row = mysql_fetch_assoc($result)) {
		$down_id = (isset($row["id"])) ? $row["id"] : 0;

		$row["level"] = $level;
		$row["rows_total"] = $rows_total;
		$row["i"] = ++$i;
		$row["entity"] = $entity;
		$row["query_tpl"] = $query_tpl;
		$row["tpl"] = $tpl;
		$row["tpl_current"] = $tpl_current;

		if ($level_stop == 0 || $level_stop > $level) {
			$down_content = tree_tpl($tpl, $tpl_current, $entity, $current,
							   $down_id, $fixed_hash,
							   $level+1, $level_stop, $down_wrapper_tpl, $query_tpl);
//	$down_content = "asdf";
			if ($down_content != "" && $down_wrapper_tpl != "") {
				$down_hash = array("down_content" => $down_content);
				$row = array_merge($row, $down_hash);
				$down_content = hash_by_tpl($row, $down_wrapper_tpl);
			}

			$down_hash = array("down_content" => $down_content);
			$row = array_merge($row, $down_hash);
//			$ret = hash_by_tpl($down_hash, $ret);
		}


		if (is_array($current)) {
//			pre($current);
			$item_tpl = (in_array($down_id, $current)) ? $tpl_current : $tpl;
		} else {
//			pre("down_id=[$down_id] == current=[$current]");
			$item_tpl = ($down_id == $current) ? $tpl_current : $tpl;
		}
		
		$ret .= hash_by_tpl($row, $item_tpl);
	}

	return $ret;
}

function multi_update($table, $value_arr, $m2m_table) {
	global $entity, $id, $debug_query, $cms_dbc;
	$ret = "";
	
	if (is_array($value_arr)) {
		$arr_deleted = array();
		$arr_copy = $value_arr;

		$query = "select $table, id, deleted from $m2m_table where $entity=$id";
		$query = add_sql_table_prefix($query);
		$result = mysql_query($query, $cms_dbc) or die("SELECT MULTI_UPDATE failed:<br>$query<br>" . mysql_error($cms_dbc));
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$slave = $row[$table];

//			echo "$m2m_table [$entity=$id]: $table = $slave<br>";
			if (in_array($slave, $arr_copy)) {
				$arr_index = array_search($slave, $arr_copy);
//				echo "unset $table = $slave : $arr_index<br>";
				unset($arr_copy[$arr_index]);

				if ($row["deleted"] == 1) $arr_deleted[] = $row["id"];
			}  else {
//				echo "deleting $table = $slave<br>";
//				$debug_query = 1;
				delete (array($entity => $id, $table => $slave), $m2m_table);
//				$debug_query = 0;
			}
		}
		
//		print_r ($arr_copy);
		
		foreach ($arr_copy as $value) {
//			echo "arr_copy[] = $value<br>";
//			$debug_query = 1;
			insert (array($entity => $id, $table => $value, "date_created" => "CURRENT_TIMESTAMP"), $m2m_table);
//			$debug_query = 0;
		}

		foreach ($arr_deleted as $value) {
//			echo "arr_deleted[] = $value<br>";
//			$debug_query = 1;
			update (array("deleted" => 0), array("id" => $value), $m2m_table);
//			$debug_query = 0;
		}

	}
}

function m2mtf_update($dict_table, $value_arr, $m2m_table) {
	global $entity, $id, $debug_query, $cms_dbc;
	$ret = "";
	
//	$debug_query = 1;
	if (is_array($value_arr)) {
		foreach($value_arr as $dict_id => $m2m_content) {

//			if ($m2m_content == "") {
//				delete (array($entity => $id, $dict_table => $dict_id), $m2m_table);
//			} else {

				$m2m_row = select_entity_row(array($entity => $id, $dict_table => $dict_id), $m2m_table);
//				pre($m2m_row);
				if (count($m2m_row) == 0) {
					$insert_array = array(
						$entity => $id,
						$dict_table => $dict_id,
						"date_created" => "CURRENT_TIMESTAMP"
						);
					$m2m_row_id = insert ($insert_array, $m2m_table);
				}

				$m2m_row = select_entity_row(array($entity => $id, $dict_table => $dict_id), $m2m_table);
//				print_r($m2m_row);
				$m2m_row_content = $m2m_row["content"];
				$m2m_row_id = $m2m_row["id"];

//				update (array("content" => $m2m_content, "site_updated" => 1), array("id" => $m2m_row_id), $m2m_table);
				update (array("content" => $m2m_content), array("id" => $m2m_row_id), $m2m_table);
//			}
		}
	}
}

function pager($url, $rows_total, $addsuffix0_bytpl1 = 0) {
	global $limit_sql, $rows_per_page, $pages_per_frame, $pg, $no_pg999999;
	global $msg_pager_disabled, $msg_pager_open, $msg_pager_page, $msg_pager_from, $msg_pager_previous, $msg_pager_next, $msg_pager_all, $msg_pager_nth;
	
	$ret = "";

	if ($rows_total == 0) return $ret;
	if ($rows_per_page == 0) return $ret;

	if ($rows_total > 10000 && $pages_per_frame == 0) {
		$ret = "[<font color=" . OPTIONS_COLOR_GRAY . ">$msg_pager_disabled; rows_total=[$rows_total]</font>]";
		$limit_sql = " limit $rows_per_page";
		return $ret;
	}

	$offset = $pg * $rows_per_page;
	$limit_sql = " limit $offset, $rows_per_page";
	if ($pg == 999999) $limit_sql = "";
	
	$pages_total = ($rows_total / $rows_per_page);
	$pages_total = ((integer) ceil ($rows_total / $rows_per_page));
//echo "pages_total=[$pages_total]<br>";

	$frame_start = 0;
	$frame_end = $pages_total;
	$frame_prev_html = "";
	$frame_next_html = "";

	$sign_url = (strpos($url, "?") === false) ? "?" : "&";
	
	if ($pages_per_frame > 0) {
		$frames_total = (integer) ceil ($pages_total / $pages_per_frame);
	
		$frame_start = ((integer) floor($pg/$pages_per_frame)) * $pages_per_frame;
		if ($pg == 999999) $frame_start = 0;
		
		$frame_end = $frame_start + $pages_per_frame;
		if ($frame_end > $pages_total) $frame_end = $pages_total;

//echo "frames_total=[$frames_total]<br>";
//echo "frame_start=[$frame_start]<br>";
//echo "frame_end=[$frame_end]<br>";
		
	
		for ($i = $frame_start; $i < $frame_end ; $i++) {
			if ($i > $pg * $rows_per_page) break;
//			$ret .= "<a href=$url&pg=$i>" . ($i+1) . "</a> ";
		}

		if ($frame_start > 0) {
			$left_beyond_frame = $frame_start-1;
			$howmuch_beyond = $left_beyond_frame;
			if ($addsuffix0_bytpl1 == 0) {
				$frame_prev_html = "<a href='{$url}{$sign_url}pg={$left_beyond_frame}' title='$msg_pager_open " .  ($left_beyond_frame+1) . "$msg_pager_nth $msg_pager_page\n$msg_pager_from " . ($howmuch_beyond+1) . " $msg_pager_previous'> &laquo; </a>|";
			} else {
				$hash = array("pg" => $i);
				$tpl = "<a href='{$url}' title='$msg_pager_open " .  ($left_beyond_frame+1) . "$msg_pager_nth $msg_pager_page\n$msg_pager_from " . ($howmuch_beyond+1) . " $msg_pager_previous'> &laquo; </a>|";
				$frame_prev_html = hash_by_tpl($hash, $tpl);
			}
		}
	
		if ($frame_end < $pages_total) {
			$right_beyond_frame = $frame_end;
			$howmuch_beyond = $pages_total - $right_beyond_frame;
			if ($addsuffix0_bytpl1 == 0) {
				$frame_next_html = "|<a href='{$url}{$sign_url}pg={$right_beyond_frame}' title='$msg_pager_open " .  ($right_beyond_frame+1) . "$msg_pager_nth $msg_pager_page\n$msg_pager_from $howmuch_beyond $msg_pager_next'> &raquo; </a>";
			} else {
				$hash = array("pg" => $i);
				$tpl = "|<a href='{$url}' title='$msg_pager_open " .  ($right_beyond_frame+1) . "$msg_pager_nth $msg_pager_page\n$msg_pager_from $howmuch_beyond $msg_pager_next'> &raquo; </a>";
				$frame_prev_html = hash_by_tpl($hash, $tpl);
			}
		}
	
	}
	
	for ($i = $frame_start; $i < $frame_end ; $i++) {
		$pg_no = $i+1;
		$a_class = "";
		if ($pg == $i){
			$pg_no = "<b>$pg_no</b>";
			$a_class = "class='sel'";
		}
		if ($addsuffix0_bytpl1 == 0) {
			$ret .= "<a href={$url}{$sign_url}pg={$i} " . $a_class . "> " . $pg_no . " </a>";
		} else {
			$hash = array("pg" => $i);
			$tpl = "<a href={$url} " . $a_class . "> " . $pg_no . " </a>";;
			$ret .= hash_by_tpl($hash, $tpl);
		}
//		$ret = "<font color=#000000>".$ret."</font>";
		if ($i != $frame_end - 1) $ret .= "|";
	}
	

	if ($ret != "") {
		$ret = "[" . $frame_prev_html . $ret . $frame_next_html . "]";
	}

//	$ret .= "<br>Страниц: $pages_total";
//	if ($pages_total == 1) $ret = "";
//	$ret .= "<br>Всего элементов: $rows_total";

	if ($pages_total > 1 && $no_pg999999 == 0) {
		if ($addsuffix0_bytpl1 == 0) {
			$ret .= " &nbsp;<a href='{$url}{$sign_url}pg=999999'>";
		} else {
			$hash = array("pg" => 999999);
			$tpl = " &nbsp;<a href='{$url}'>";
			$ret .= hash_by_tpl($hash, $tpl);
		}
		$ret .= ($pg == 999999) ? "<b>" : "";
		$ret .= "$msg_pager_all";
		$ret .= "&nbsp;$rows_total";
		$ret .= ($pg == 999999) ? "</b>" : "";
		$ret .= "</a>";
	}



	return $ret;
}


function mkupdatefields_fromform($entity_fields, $id = "_global:id", $entity = "_global:entity") {
		global $multiflatcontent_mayupdate, $errormsg, $alertmsg, $msg_tag_shortcut;
		global $strip_from_freetext, $no_freetext, $freetext2textarea, $non_prefixed_fields;
		global $selective_update_onedit;
		global $msg_bo_cant_be_parent_of_youself;
		global $msg_bo_icwhose_not_defined, $msg_bo_it_add;

		$sql_fields = "";
		$id = absorb_variable($id);
		$entity = absorb_variable($entity);
		
		foreach ($entity_fields as $name => $entity_field) {
// helps for skipping view and simple
// but multicompositeiccontent starts also with ~...
//			if (preg_match("/~.*/", $name)) continue;

			$input_type = $entity_field[1];
			$input_type_strict = $input_type;

			if ($no_freetext == 1 && $input_type_strict == "freetext") {
				$input_type = $freetext2textarea[$input_type];
				$input_type_strict = makestrict($input_type);
			}

			$pos = strpos($input_type, "_");
			$len = strlen($input_type);
			if ($pos > 0) $input_type_strict = substr($input_type, 0, $pos);
	
			$form_value = "";
			if (isset($_REQUEST[$name])) {
				$form_value = $_REQUEST[$name];
//				print_r($form_value);
			} else {
				if ($selective_update_onedit == 1) continue;
			}

/*
			if ($form_value == "") {
				echo "skipping [$name]: input is empty<br>";
				continue;
			}
*/

//			$form_value = $$name;
//			$form_value = str_replace("'", "\\'", $form_value);
//			$form_value = str_replace('"', "&quot;", $form_value);
//			$form_value = addslashes($form_value);
//			echo "[$name/$input_type_strict] = [$form_value]<br>";
	
			$sql_field = "";
			$skip_update = 0;

			switch ($input_type_strict) {
				case "boolean":
				case "hidden":
				case "textarea":
				case "number":
				case "textfield":
					$form_value = addslashes($form_value);

					$tbu_name = $name . "_before_update";
					if (function_exists($tbu_name)) $form_value = $tbu_name($form_value);
/*					if ($form_value == "" && $name == "ident") {
						$skip_update = 1;
						$errormsg .= "<b>Название не может быть пустым</b><br>";
					}
*/
					if ($input_type_strict == "number") $form_value = floatval($form_value);
					if ($skip_update == 0) $sql_field .= "$name='$form_value'";

					break;

				case "freetext":
					$form_value = addslashes($form_value);
					$form_value = str_replace($strip_from_freetext, "", $form_value);

					if ($skip_update == 0) $sql_field .= "$name='$form_value'";
					break;
	
				case "checkbox":
					if ($form_value == "on") {
						$sql_field .= "$name='1'";
					}
					if ($form_value == "") {
						$sql_field .= "$name='0'";
					}
					break;
	
				case "select":
				case "radio":
				case "tristate":
					if (
						($input_type == "select_table_tree" || $input_type == "select_table_tree_root")
						&& (
							($form_value == $id && $name == $entity)
							|| ($form_value == $id && $name == "parent_id")
							)
						) {
//						echo "$input_type";
						$errormsg .= "$msg_bo_cant_be_parent_of_youself";
						continue;
					}

					$tmp_field = "$name='$form_value'";
//					if (!in_array($name, $non_prefixed_fields)) $tmp_field = TABLE_PREFIX . $tmp_field;
					$sql_field .= $tmp_field;
					break;

				case "o2m":
					o2m_update($name, $form_value, $entity_field[2]);
					break;

				case "familyicwhose":
					familyicwhose_update();
					break;

				case "ic":
					ic_update($entity_field[2]);
					break;

				case "multi":
				case "m2mcb":
//					echo "multi_update($name, $form_value, $entity_field[2])";
					if ($form_value == "") $form_value = array();
					multi_update($name, $form_value, $entity_field[2]);
					break;

				case "m2mtf":
				case "m2mta":
//					echo "m2mtf_update($name, $form_value, $entity_field[2])";
					m2mtf_update($name, $form_value, $entity_field[2]);
					break;

				case "m2mtfethalon":
					$m2m_tablehash = $entity_field[2];
					$m2m_updatabletable = "";
					
					foreach($m2m_tablehash as $m2m_table => $m2m_specifichash) {
						$m2m_inputtype = $m2m_specifichash[1];
						if ($m2m_inputtype == "textfield") {
							$m2m_updatabletable = $m2m_table;
							break;
						}
					}
//					pre ($form_value);

//					echo "m2mtf_update($name, $form_value, $m2m_updatabletable)";
					m2mtf_update($name, $form_value, $m2m_updatabletable);
					break;

				case "multiflatcontent":
					if ($multiflatcontent_mayupdate == 1) {
//						echo "multiflatcontent($name, $form_value, $entity_field[2])";
						if ($form_value == "") $form_value = array();
						multiflatcontent_update($name, $form_value, $entity_field[2]);
					}
					break;

				case "multicompositecontent":
					$composite = $entity_field[3];

					$it_name = "";
					foreach($composite as $value) {
						if ($it_name != "") $it_name .= "_";
						$it_name .= $value;
					}


					if (isset($_REQUEST[$it_name])) {
						$form_value = $_REQUEST[$it_name];
					}

					if ($form_value == "") $form_value = array();
//					echo "multicompositecontent($entity_field[2], $form_value, $composite)";
					multicompositecontent_update($entity_field[2], $form_value, $composite);
					break;


				case "multicompositebidirect":
					$composite = $entity_field[3];
					$m2m_table = $entity_field[2];

					$it_name = $m2m_table;
					foreach($composite as $value) {
						if ($it_name != "") $it_name .= "_";
						$it_name .= $value;
					}
					$it_name .= "_to";


					if (isset($_REQUEST[$it_name])) {
						$form_value = $_REQUEST[$it_name];
					}

					if ($form_value == "") $form_value = array();
					
/*					pre("multicompositebidirect_update($entity_field[2]"
						. ", form_value=[" . pr($form_value) . "]"
						. ", it_name=[$it_name]"
						. ", composite=[" . pr($composite) . "]"
						. ", entity_field=[" . pr($entity_field) . "]"
						);
*/

					multicompositebidirect_update($entity_field[2], $form_value, $composite);
					break;


				case "multicompositeiccontent":
//					$icwhose_row = select_entity_row(array("hashkey" => $entity_field[0]), "icwhose");
					$icwhose_row = select_entity_row(array("hashkey" => $entity_field[5]), "icwhose");
		//			pre($icwhose_row);
		
					if (!isset($icwhose_row["id"])) {
						$field_txt		= (isset($entity_field[0])) ? $entity_field[0] : "";
//						$errormsg .= "[$field_txt] $msg_bo_icwhose_not_defined [<a href='icwhose-edit.php?hashkey=$field_txt'>$msg_bo_it_add</a>]";
						$errormsg .= "[" . $entity_field[5] . "] $msg_bo_icwhose_not_defined"
							. " <a href='icwhose-edit.php?hashkey=" . $entity_field[5] . "' target=_blank>"
							. "$msg_tag_shortcut $msg_bo_it_add</a>";

//						pre($errormsg);
						break;
					}

					$icwhose_id = $icwhose_row["id"];
	
					$default		= (isset($entity_field[2])) ? $entity_field[2] : "m2m_{$entity}_iccontent";
					$param1			= (isset($entity_field[3])) ? $entity_field[3] : array();
					$param2			= (isset($entity_field[4])) ? $entity_field[4] : array($entity => "_global:id");

//					echo "multicompositeiccontent_updateall($default, $param1, $param2, $param3)";
//		function multicompositeiccontent_updateall($m2m_table, $fixed_hash, $absorbing_fixedhash, $icwhose_id) {
					multicompositeiccontent_updateall($default, $param1, $param2, $icwhose_id);
					break;

				case "notifier":
					$cbname = "cb_notify_$name";
					$notify_form_value = ${"notify_$name"};
					if (isset($$cbname) && $$cbname == "on") {
						if ($name == "photo_notified") {
							$GLOBALS["$name"] = "<a href="
								. "'http://cityflower.ru/upload/random/image/$notify_form_value'>"
								. "http://cityflower.ru/upload/random/image/$notify_form_value"
								. "</a>";
						}

						$notifier_to_form_name = "notifier_to_$name";
						$notifier_to_form_value = $$notifier_to_form_name;
						$notifier_to_ = $notifier_to_form_value;
						
//						echo "[$notifier_to_]";
						
						$is_sent = send_tpl($name, $notifier_to_);
						if ($is_sent) {
//							echo "[notify_$name] = [$notify_form_value]";
							$sql_field .= "$name='$notify_form_value'";
//							if ($sql_field != "" || $sql_field != ", ") $sql_fields .= $sql_field;
//							$debug_query = 1;
							update(array(
								$name => $notify_form_value,
								"${name}_to" => $notifier_to_), array("id" => $id));
						}
					}
					break;

				case "doubledate":
					$form_value = mktime (
						get_number("{$name}_hour"),
						get_number("{$name}_minute"),
						get_number("{$name}_second"),
						get_number("{$name}_month"),
						get_number("{$name}_day"),
						get_number("{$name}_year"));
					$sql_field .= "$name=$form_value";
					break;

				case "timestamp":
				case "datetime":
					$form_value = get_date($name);
					$sql_field .= "$name='$form_value'";
					break;

				case "ro":
				default:
					$sql_field = "";
			}
			
			if ($input_type_strict != "o2m"
				&& $input_type_strict != "multi"
				&& $input_type_strict != "notifier"
				&& $input_type_strict != "ahref"
//				&& $input_type_strict != "img_layer"
//				&& $input_type_strict != "imgtype_layer"
//				&& $skip_update == 0
				) {

				if ($sql_field != "") {
					if ($sql_fields != "") $sql_field = ", " . $sql_field ;
					$sql_fields .= $sql_field;
				}
			}
		}
	return $sql_fields;
}


function fixedhash_fromfixed($fixed_fields, $row) {
	$ret = array();

	foreach($fixed_fields as $key) {
		if (isset($row[$key])) $ret[$key] = $row[$key];
		if (isset($row[TABLE_PREFIX . $key])) $ret[$key] = $row[TABLE_PREFIX . $key];
	}

	return $ret;
}

function sqlcond_fromhash($fixed_hash, $col_prefix = "", $startfrom = "", $conjunction = "and",  $table_prefix = "", $addslashes = 1) {
	global $non_prefixed_fields;

	$ret = "";

	if (is_array($fixed_hash) && count($fixed_hash) > 0) {
		foreach($fixed_hash as $fixed_field => $fixed_value) {
			if ($addslashes == 1) $fixed_value = addslashes($fixed_value);

			$fixed_value_pure = $fixed_value;
			if (is_numeric($fixed_value) || (strpos($fixed_value, "CURRENT_TIMESTAMP") !== false)) {
			} else {
				$fixed_value = "'$fixed_value'";
			}


//			if ($fixed_field != "published" && $fixed_field != "deleted" && $fixed_field != "parent_id") {
			$dotpos = strpos($fixed_field, ".");
//			pre("strpos($fixed_field, '.') = $dotpos");
			if ($dotpos === false) {
				if (!in_array($fixed_field, $non_prefixed_fields)) {
					$fixed_field = $table_prefix . $fixed_field;
				}
			}
			
			if ($ret != "") $ret .= " $conjunction ";
			if ($col_prefix != "") $ret .= $col_prefix . ".";

/* better to use "ident~" => "Audi" than "ident" => "~Audi"
			$first_char = substr($fixed_value, 0, 1);
			switch ($first_char) {
				case "~":
					$fixed_value = substr($fixed_value, 1);
					$ret .= "$fixed_field like '%$fixed_value%' ";
					break;

				default:
					$ret .= "$fixed_field='$fixed_value' ";
			}
*/

			$last_char = substr($fixed_field, -1, 1);
			switch ($last_char) {
				case "~":
					$fixed_field = substr($fixed_field, 0, -1);
					$ret .= "$fixed_field like '%$fixed_value_pure%' ";
					break;

				case "!":
					$fixed_field = substr($fixed_field, 0, -1);
					$ret .= "$fixed_field<>$fixed_value ";
					break;

				case ">":
					$fixed_field = substr($fixed_field, 0, -1);
					$ret .= "$fixed_field>$fixed_value ";
					break;

				case "<":
					$fixed_field = substr($fixed_field, 0, -1);
					$ret .= "$fixed_field<$fixed_value ";
					break;

				case "=":
					$fixed_field = substr($fixed_field, 0, -1);
					$last_char = substr($fixed_field, -1, 1);

					switch ($last_char) {
						case ">":
							$fixed_field = substr($fixed_field, 0, -1);
							$ret .= "$fixed_field>=$fixed_value ";
//							pre("$fixed_field>='$fixed_value'");
							break;
		
						case "<":
							$fixed_field = substr($fixed_field, 0, -1);
							$ret .= "$fixed_field<=$fixed_value ";
//							pre("$fixed_field<='$fixed_value'");
							break;
					}
					break;

				case ":":
					$fixed_field = substr($fixed_field, 0, -1);
					$ret .= "$fixed_field in ($fixed_value) ";
					break;


				default:
					$ret .= "$fixed_field=$fixed_value ";
			}

		}
	}
	
	if ($ret != "")	$ret = $startfrom . $ret;
	return $ret;
}

function sqlcond_like_fromlist($fixedlike_list, $liketo = "", $col_prefix = "", $startfrom = "", $finalize_with_braces = 1) {
	$ret = "";

	$fixedlike_hash = array();
	foreach ($fixedlike_list as $dbfield) $fixedlike_hash[$dbfield . "~"] = "$liketo";
	
	$ret = sqlcond_fromhash($fixedlike_hash, $col_prefix, "", "or");
	if ($ret != "" && $finalize_with_braces == 1) $ret = $startfrom . "(" . $ret . ")";

	return $ret;
}


function sqlin_fromarray($id_array, $col_prefix = "") {
	$ret = "";

	if (is_array($id_array) && count($id_array) > 0) {
		foreach($id_array as $id) {
			if ($ret != "") $ret .= ", ";
			if ($col_prefix != "") $ret .= $col_prefix . ".";
			$ret .= "$id ";
		}
	}

	return $ret;
}

function hrefsuffix_fromhash($fixed_hash, $startfrom = "") {
	$ret = "";

	if (is_array($fixed_hash) && count($fixed_hash) > 0) {
		foreach($fixed_hash as $fixed_field => $fixed_value) {
			if ($ret != "") $ret .= "&";
			$last_char = substr($fixed_field, -1, 1);
			while($last_char == "~" || $last_char == "<" || $last_char == ">" || $last_char == "=") {
				$fixed_field = substr($fixed_field, 0, -1);
				$last_char = substr($fixed_field, -1, 1);
			}
			$ret .= "$fixed_field=" . urlencode($fixed_value);
		}
	}
	
	if ($ret != "")	$ret = $startfrom . $ret;
	return $ret;
}

function hidden_fromhash($fixed_hash) {
	global $pg;
	$ret = "";

	if (is_array($fixed_hash) && count($fixed_hash) > 0) {
		foreach($fixed_hash as $fixed_field => $fixed_value) {
		$ret .= "<input type='hidden' name='$fixed_field' value='$fixed_value'>\n";
		}
	}
	
	if (!in_array("pg", array_keys($fixed_hash))) {
		if ($pg > 0) $ret .= "<input type='hidden' name='pg' value='$pg'>\n";
	}
	
	return $ret;
}



function multicompositepointer_multipleupdate($m2m_table, $fixed_hash, $pointervalue_array, $pointer = "iccontent"
		, $iccontent_tf1_syncarray = array()
		, $merged_inserthash = array(), $merged_updatehash = array()
		, $debug = 0, $dbupdate = 1, $iccontent_tf1_name = "iccontent_tf1"

// stupid here: what to update if pointervalue_array is ARRAY?
//		, $pointervalue_deleteinsert0_or_update1 = 0

		) {
	global $cms_dbc;

//	pre ($fixed_hash);
//	pre ($pointervalue_array);
//	pre ($iccontent_tf1_syncarray);
	
//	$query = "select id, deleted, $pointer from $m2m_table where " . sqlcond_fromhash($fixed_hash);
	$query = "select *, $pointer from $m2m_table where " . sqlcond_fromhash($fixed_hash);
	$query = add_sql_table_prefix($query);
	$result = mysql_query($query, $cms_dbc) or die("SELECT4DELETE MULTICOMPOSITEPOINTER[$pointer]_MULTIPLEUPDATE failed:<br>$query<br>" . mysql_error($cms_dbc));
	while ($row = mysql_fetch_assoc($result)) {
		$m2m_id = $row["id"];
		$m2m_pointervalue = $row[$pointer];
		$m2m_deleted = $row["deleted"];

		//search key in form value that already exists in db
		$value_array_key = array_search($m2m_pointervalue, $pointervalue_array); 

		if ($value_array_key === FALSE) {
			$affected = 0;
// not selected in form, should be deleted
//			if ($dbupdate == 1) update (array("deleted" => 1, "site_updated" => 1), array("id" => $m2m_id), $m2m_table);
			if ($dbupdate == 1) $affected = update (array("deleted" => 1), array("id" => $m2m_id), $m2m_table);
			if ($debug == 1) echo "deleted affected=[$affected] id=[$m2m_id] $pointer=[$m2m_pointervalue] for [" . sqlcond_fromhash($fixed_hash) . "]<br>";

			if (isset($iccontent_tf1_syncarray[$value_array_key])) {
				$affected = 0;
				if ($dbupdate == 1) $affected = update (array($iccontent_tf1_name => ""), array("id" => $m2m_id), $m2m_table);
				if ($debug == 1) echo "updated affected=[$affected] id=[$m2m_id] $iccontent_tf1_name=[] for [" . sqlcond_fromhash(array("id" => $m2m_id)) . "]";
			}
		} else {
// selected in form and present in db, restore deleted
			if ($m2m_deleted == 1) {
				$affected = 0;
//				if ($dbupdate == 1) update (array("deleted" => 0, "site_updated" => 1), array("id" => $m2m_id), $m2m_table);
				if ($dbupdate == 1) $affected = update (array("deleted" => 0), array("id" => $m2m_id), $m2m_table);
				if ($debug == 1) echo "restored affected=[$affected] deleted id=[$m2m_id] $pointer=[$m2m_pointervalue] for [" . sqlcond_fromhash($fixed_hash) . "]";
			}

			if (isset($iccontent_tf1_syncarray[$value_array_key]) && isset($row[$iccontent_tf1_name])
						&& $iccontent_tf1_syncarray[$value_array_key] != $row[$iccontent_tf1_name]) {
				$affected = 0;
				if ($dbupdate == 1) {
					$affected = update (array($iccontent_tf1_name => $iccontent_tf1_syncarray[$value_array_key])
						, array("id" => $m2m_id), $m2m_table);
				}
				if ($debug == 1) echo "updated affected=[$affected] $iccontent_tf1_name=[" . $iccontent_tf1_syncarray[$value_array_key] . "], [was_in_db]=[" . $row[$iccontent_tf1_name] . "] for [" . sqlcond_fromhash(array("id" => $m2m_id)) . "]";
			}

// no need to insert again value presented in db (a valid or restored value)
			unset($pointervalue_array[$value_array_key]);
		}
	
	}

	foreach($pointervalue_array as $pointervalue) {
		if ($pointervalue == "0") continue;

		$insert_base = array(
			$pointer => $pointervalue,
			"date_created" => "CURRENT_TIMESTAMP"
			);
		$insert_hash = array_merge($insert_base, $fixed_hash, $merged_inserthash);

//		pre($insert_hash);
		$m2m_id = 0;
		if ($dbupdate == 1) $m2m_id = insert($insert_hash, $m2m_table);
		if ($debug == 1) echo "inserted[id=$m2m_id] $pointer=[$pointervalue] with [" . sqlcond_fromhash($fixed_hash) . "]<br>";

		$value_array_key = array_search($pointervalue, $pointervalue_array); 

		if ($value_array_key !== FALSE) {
			if (isset($iccontent_tf1_syncarray[$value_array_key])) {
				$update_hash = array($iccontent_tf1_name => $iccontent_tf1_syncarray[$value_array_key]);
				$update_hash = array_merge($update_hash, $merged_updatehash);
				$affected = 0;
				if ($dbupdate == 1) $affected = update ($update_hash, array("id" => $m2m_id), $m2m_table);
				if ($debug == 1) echo "updated affected=[$affected] $iccontent_tf1_name= [" . $iccontent_tf1_syncarray[$value_array_key]
					. "] where id=[$m2m_id] in [$m2m_table]";
			}
		}
	}

//	print_r($pointervalue_array);

}


function multicompositepointer_singleupdate($m2m_table, $fixed_hash, $pointervalue, $pointer = "iccontent") {
	global $cms_dbc;

	$query = "select id, deleted, $pointer from $m2m_table where " . sqlcond_fromhash($fixed_hash);
	$query = add_sql_table_prefix($query);
	$result = mysql_query($query, $cms_dbc) or die("SELECT4DELETE MULTICOMPOSITEpointer_SINGLEUPDATE failed:<br>$query<br>" . mysql_error($cms_dbc));

	if (mysql_num_rows($result) > 1) {
		pre ("SELECT4DELETE MULTICOMPOSITEPOINTER[$pointer]_SINGLEUPDATE[$query] failed: seleted " . mysql_num_rows($result) . " rows, should be single");
		return;
	}

	if (mysql_num_rows($result) == 1) {
		$row = mysql_fetch_assoc($result);
		$m2m_id = $row["id"];
		$m2m_deleted = $row["deleted"];
		$m2m_pointervalue = $row[$pointer];
	
		$update_hash = array (
			$pointer => $pointervalue
			, "deleted" => 0
//			, "site_updated" => 1
		);

//		if ($m2m_pointervalue != $pointervalue) {
//			echo "updating $m2m_table:$m2m_id [$m2m_pointervalue]->[$pointervalue] ";
			update ($update_hash, array("id" => $m2m_id), $m2m_table);
//		}
	} else {
		$insert_base = array(
			$pointer => $pointervalue,
			"date_created" => "CURRENT_TIMESTAMP"
			);
		$insert_hash = array_merge($insert_base, $fixed_hash);

//		pre($insert_hash);
		$m2m_id = insert($insert_hash, $m2m_table);
//		echo "inserted $m2m_id [$pointervalue] ";
	}
}

function recursive_cnt($row) {
	global $entity;
	
	$ret = "";
	$ret = select_field("count(id)", array("parent_id" => $row["id"], "deleted" => 0), $entity);
	return $ret;
}

function master_dependencies($slave_entity) {
	global $entity_fixed_list;
	$ret = array();
	
	foreach ($entity_fixed_list as $master => $slave_array) {
		foreach ($slave_array as $slave) {
			if ($slave == $slave_entity) $ret[] = $master;
		}
	}
	
	return $ret;
}

function masterdepend_entity() {
	global $entity;
	
	$ret = "";

	$master_dependencies = master_dependencies($entity);
	if (isset($master_dependencies[0])) $ret = $master_dependencies[0];
	
	return $ret;
}

function masterdepend_entity_hr() {
	global $entity_list, $entity;
	
	$ret = "";

	$master_dependencies = master_dependencies($entity);
	if (isset($master_dependencies[0])) $ret = $entity_list[$master_dependencies[0]];
	
	return $ret;
}

function masterdepend_cnt($masterdepend_entity = "") {
	global $entity, $id;
	global $debug_query;
	
	$ret = "_E";
	
//	pre($masterdepend_entity);
//	echo is_array($masterdepend_entity) ? 'Array' : 'not an Array';


	if (!is_string($masterdepend_entity)) {
		$masterdepend_entity = masterdepend_entity();
	} else if (is_string($masterdepend_entity) && $masterdepend_entity == "") {
		$masterdepend_entity = masterdepend_entity();
	}
	$m2mfixed_dependtable = get_m2m_dependtable($entity, $masterdepend_entity);
//	$entity_fields_dependtable = get_m2m_dependtable($entity, $masterdepend_entity);

	if ($masterdepend_entity != "") {
		if ($id > 0) {
//SELECT FIELD failed:
//select count(id) from webie_product where webie_pgroup='2' and deleted='0' order by manorder asc limit 1
//Unknown column 'webie_pgroup' in 'where clause'
//			$ret = select_field("count(id)", array(TABLE_PREFIX . $entity => $id, "deleted" => 0), TABLE_PREFIX . $masterdepend_entity);

			if ($m2mfixed_dependtable == "") {
				$ret = select_field("count(id)", array($entity => $id, "deleted" => 0), TABLE_PREFIX . $masterdepend_entity);
			} else {
//				$debug_query = 1;
				$ret = select_field("count(id)", array($entity => $id, "deleted" => 0), TABLE_PREFIX . $m2mfixed_dependtable);
//				$debug_query = 0;
			}
		} else {
			$ret .= ":noID";
		}
	} else {
		$ret .= ":noDEP";
	}
	
	return $ret;
}

function resort_manorder_constant_hashkey($entity = "_global:entity", $fixed_hash = array()
		, $constant_hashkey = "", $field = "ident", $orderby = "ident asc") {
		
	$entity = absorb_variable($entity);
//	$entity = TABLE_PREFIX . $entity;
	
	if ($constant_hashkey == "") {
		$constant_hashkey = strtoupper($entity) . "_IDENT_STARTEDWITH";
	}
	
	$startedwith_txt = select_field("content", array("hashkey" => $constant_hashkey), "constant");
	$startedwith_array = preg_split ("/(\r)?\n/", $startedwith_txt, -1, PREG_SPLIT_NO_EMPTY);
//	pre($startedwith_array);
	
	return resort_manorder_array($entity, $fixed_hash, $startedwith_array, $field, $orderby);
}



function resort_manorder_array($entity = "_global:entity", $fixed_hash = array()
		, $startedwith_array = array(), $field = "ident", $orderby = "ident asc") {

	global $debug_query;

	$entity = absorb_variable($entity);
//	$entity = TABLE_PREFIX . $entity;
	
	$updated_cnt = 0;

	$i = 1;
	$neq_cond = "";

	$fixed_hash["deleted"] = 0;
	$fixed_cond = sqlcond_fromhash($fixed_hash);
	if ($fixed_cond != "") $fixed_cond = " and $fixed_cond";

	foreach ($startedwith_array as $value) {
		$query = "select id from $entity_ where $field = '$value' $fixed_cond";
		$query = add_sql_table_prefix($query);
		$qa = select_queryarray($query);
		foreach ($qa as $row) {
			$updated_cnt += update(array("manorder" => $i), array("id" => $row["id"]), $entity_);
			$i++;
		}
		
		if ($neq_cond != "") $neq_cond .= " and ";
		$neq_cond .= " $field <> '$value' ";
	}

	if ($neq_cond != "") $neq_cond = " and " . $neq_cond;
	$query = "select id from $entity_ where 1=1 $neq_cond $fixed_cond order by $orderby";
	$query = add_sql_table_prefix($query);
	if ($debug_query == 1) echo $query;
	
	$qa = select_queryarray($query);
	foreach ($qa as $row) {
		$updated_cnt += update(array("manorder" => $i), array("id" => $row["id"]), $entity_);
		$i++;
	}
	
	return $updated_cnt;
}

function cn_status($row) {
	global $upload_abspath, $cn, $cn_good;
	$ret = "";
	
	$cn_good = 0;

	switch ($row["dstype"]) {
		case "mysql":
			if ($row["host"] != ""
				&& $row["login"] != ""
				&& $row["passwd"] != "") {
			
				ini_set("display_errors", 1);
				$cn = mysql_connect (
					$row["host"],
					$row["login"],
					$row["passwd"]);
				ini_set("display_errors", 0);
		
				$host = $row["host"];
				
				if ($cn == "") {
		//			$ret .= mysql_error($cn);
					$ret .= "[$host] can't connect";
				} else {
					$ret .=  "[$host] connected";
			
					$db = $row["db"];
					if ($db != "") {
						if (mysql_select_db($db, $cn)) {
							$ret .= "; [$db] selected";
		
							$dbtable = $row["dbtable"];
							if ($dbtable != "") {
								$dbt_list = mysql_list_fields ($db, $dbtable, $cn);
								
								if ($dbt_list != "") {
									$cn_good = 1;
									$ret .= "; [$dbtable] exists";
								} else {
									$cn_good = 0;
									$ret .= "; " . mysql_error($cn);
								}
							} else {
								$cn_good = 1;
							}
							$ret .= "; " . mysql_error($cn);
						}
					} else {
						$ret .= "; db not set";
					}
				}
			}
			break;

		case "mssql":
			if ($row["host"] != ""
				&& $row["login"] != ""
				&& $row["passwd"] != "") {
			
				ini_set("display_errors", 1);
				$cn = mssql_connect (
					$row["host"],
					$row["login"],
					$row["passwd"]);
				ini_set("display_errors", 0);
		
				$host = $row["host"];
				
				if ($cn == "") {
		//			$ret .= mssql_error($cn);
					$ret .= "[$host] can't connect";
				} else {
					$ret .=  "[$host] connected";
			
					$db = $row["db"];
					if ($db != "") {
						if (mssql_select_db($db, $cn)) {
							$ret .= "; [$db] selected";
/*		
							$dbtable = $row["dbtable"];
							if ($dbtable != "") {
								$dbt_list = mssql_list_fields ($db, $dbtable, $cn);
								
								if ($dbt_list != "") {
									$cn_good = 1;
									$ret .= "; [$dbtable] exists";
								} else {
									$cn_good = 0;
									$ret .= "; " . mssql_get_last_message($cn);
								}
							} else {
								$cn_good = 1;
							}
							$ret .= "; " . mssql_get_last_message($cn);
*/
						}
					} else {
						$ret .= "; db not set";
					}
				}
			}
			break;

		case "csv":
		case "csv_simple":
		case "csv_tb":
			$csv_file = $upload_abspath . "importsource/" . $row["id"] . "/" . $row["file1"];
			if (file_exists($csv_file)) {
				$ret .= "file [" . $row["file1"] . "] exists";
				if (is_readable($csv_file)) {
					$ret .= "; readable";
					$cn_good = 1;
				}
			} else {
				$ret .= "file [" . $row["file1"] . "] does not exists";
			}
			break;

		case "external":
			break;

		default:
			echo "cn_status(): Unknown dstype: " . $row["dstype"];
			break;
	}	
//	print_r($row);
	return $ret;
}


function unhash_id($hashed, $entity, $field = "id") {
	$ret = select_field("id", array("md5($field)" => $hashed), $entity);
	$ret = is_numeric($ret) ? $ret : 0;
	return $ret;
}

function prefixed_entity ($entity = "_global:entity") {
	$entity = absorb_variable($entity);
	$ret = $entity;
	if (TABLE_PREFIX != "" && strpos($entity, TABLE_PREFIX) === false) $ret = TABLE_PREFIX . $ret;
	return $ret;
}


function add_sql_table_prefix($query) {
	$ret = $query;

//old catcher, works blindly for "Igor brought by trailer Chemicals from btr_Land-N-Sea"
	if (strpos($query, TABLE_PREFIX) !== false) return $query;

// but new one is not even better...
/*
	$search = array (
		"~\s+join\s+(?<=" . TABLE_PREFIX . ")(\S*)~i",
		"~\s+from\s+(?<=" . TABLE_PREFIX . ")(\S*)~i",
		"~\s+into\s+(?<=" . TABLE_PREFIX . ")(\S*)~i",
		"~^update\s+(?<=" . TABLE_PREFIX . ")(\S*)~i",
//		"~,\s*(?<!" . TABLE_PREFIX . ")(\S*)\S*(?!.)~Ui",
	);
//	pre($search);

	foreach ($search as $preg_search) {
		$times_matched = preg_match($preg_search, $query);
//		pre("times_matched = $times_matched");
		if ($times_matched > 0) {
			pre("add_sql_table_prefix() found [" . htmlentities($preg_search) . "] shall I break?");
			return $query;
		}
	}
*/

	$search = array (
		"~\s+join\s+(?<!" . TABLE_PREFIX . ")(\S*)~i",
		"~^(select\s+.*\s+)from\s+(?<!" . TABLE_PREFIX . ")(\S*)~i",
		"~^(insert\s+)into\s+(?<!" . TABLE_PREFIX . ")(\S*)~i",
		"~^update\s+(?<!" . TABLE_PREFIX . ")(\S*)~i",
//		"~,\s*(?<!" . TABLE_PREFIX . ")(\S*)\S*(?!.)~Ui",
	);
//	pre($search);

	$replace = array (
		" join " . TABLE_PREFIX . "\\1",
		"\\1 from " . TABLE_PREFIX . "\\2",
		"\\1 into " . TABLE_PREFIX . "\\2",
		" update " . TABLE_PREFIX . "\\1",
//		", " . TABLE_PREFIX . "\\1",
	);

	$ret = preg_replace($search, $replace, $query);
	$ret = add_sql_fields_multilanguage_suffix($ret);
	
	return $ret;
}



function add_sql_fields_multilanguage_suffix($query) {
	global $lang_current, $lang_database_default, $in_backoffice, $dbfields_language_independant, $dbfields_language_independant_strpos, $entity_list;
	
	$ret = $query;
	if ($lang_current == $lang_database_default) return $ret;
	if ($lang_current == $lang_database_default) return $ret;

/*
	$search = array (
		"~(select\s+)(.*)(\s+from.*)~i",
		"~(insert\s+)(.*)(\s+into.*)~i",
		"~(update.*set\s+)(.*)(\s+where.*)?~i",
	);
//	pre($search);
	foreach ($search as $search_tmp) {
		pre($search_tmp);
		$matches = array();
		preg_match($search_tmp, $ret, $matches);
		if (isset($matches[0]))	pre($matches);
	}
*/	


	$search_select = "~(select\s+)(.*)(\s+from\s+)(" . TABLE_PREFIX . "\S+)(\s+.*)~i";
	$search_insert = "~(insert\s+)(.*)(\s+into\s+)(" . TABLE_PREFIX . "\S+)(\s+.*)~i";
	$search_update = "~(update\s+)(" . TABLE_PREFIX . "\S+)(\s+.*).*set\s+)(.*)(\s+where.*)?~i";
	$separator_comma = "~(\s*,\s*)~i";


	$matches = array();
	preg_match($search_select, $ret, $matches);
	if (isset($matches[0]))	{
//		pre($matches);
		$table = $matches[4];
		$fields = $matches[2];
//		$fields_splitted = preg_split($separator_comma, $fields, -1, PREG_SPLIT_DELIM_CAPTURE);
		$fields_splitted = preg_split($separator_comma, $fields);
//		pre($fields_splitted);
		$fields_suffixed = "";
		foreach ($fields_splitted as $field) {
			$field_suffixed = $field;
			if (!in_array($field, $dbfields_language_independant)
				&& field_array_strposmatched_index($field, $dbfields_language_independant_strpos) == 0
				&& !in_array($field, array_keys($entity_list))
				) {
				$field_suffixed .= "_" . $lang_current;
				entity_has_field($table, $field_suffixed, 1, $field);
				$field_suffixed .= " as $field";
			}

			if ($fields_suffixed != "") $fields_suffixed .= ", ";
			$fields_suffixed .= $field_suffixed;
		}
		$ret = $matches[1] . $fields_suffixed . $matches[3] . $matches[4] . $matches[5];
//		pre($ret);
//		echo $ret;
	}

	return $ret;
}


function field_array_strposmatched_index($field, $match_strpos_array = array()) {
	global $lang_current, $lang_database_default;
	$ret = 1;
	
	for ($i=0; $i<count($match_strpos_array); $i++) {
		$item2match = $match_strpos_array[$i];
		$matched = stripos($field, $item2match);
//		pre("field_array_strposmatched_index($field, " . pr($match_strpos_array) . "): matched=" . $matched+1);
		if ($matched !== FALSE) {
//			pre("$lang_current <> $lang_database_default: skipping [$field], matched to [$item2match]");
			$ret += $i;
			break;
		}
	}
	
	return $ret;
}


function swap_fieldgroup_within_row($src_array, $dst_array, $row) {
	global $errormsg;
	$ret = 0;
	
	if (!isset($row["id"])) {
		$errormsg = "swap_fieldgroup_within_row(): row does not contain ID field; no changes";
		return $ret;
	}

	if (count($src_array) != count($dst_array)) {
		$errormsg = "swap_fieldgroup_within_row(): count(src_array)[" . count($src_array) . "] != count(dst_array)[" . count($dst_array) . "]; no changes";
		return $ret;
	}

	if (isset($src_array[0]) && isset($dst_array[0]) && $src_array[0] == $dst_array[0]) {
		$errormsg = "swap_fieldgroup_within_row(): seems to swap group of fields with themselves; no changes";
		return $ret;
	}


	foreach ($src_array as $src_fname) {
		if (!isset($row[$src_fname])) {
			$errormsg = "swap_fieldgroup_within_row(): row does not contain field [$src_fname] (taken from src_array); no changes";
			return $ret;
		}
		$update_hash[$src_fname] = $row[$src_fname];
	}
	
	foreach ($dst_array as $dst_fname) {
		if (!isset($row[$dst_fname])) {
			$errormsg = "swap_fieldgroup_within_row(): row does not contain field [$dst_fname] (taken from dst_array); no changes";
			return $ret;
		}
	}

	foreach ($dst_array as $dst_fname) {
		if (!isset($row[$dst_fname])) {
			$errormsg = "swap_fieldgroup_within_row(): row does not contain field [$dst_fname] (taken from dst_array); no changes";
			return $ret;
		}
	}


	$update_hash = array();
	for ($i=0; $i<count($src_array); $i++) {
		$src_fname = $src_array[$i];
		$src_value = $row[$src_fname];

		$dst_fname = $dst_array[$i];
		$dst_value = $row[$dst_fname];

		$update_hash[$src_fname] = $dst_value;
		$update_hash[$dst_fname] = $src_value;
	}
	update($update_hash);

//	pre("swap_fieldgroup_within_row whants to perform update:  " . pr($update_hash));

}

function get_cached($hashkey, $db_field = "content", $db_table = "cached", $expiration_field = "date_expiration") {
	global $debug_query, $debug_cache, $freeze_cache, $today_datetime;
	$ret = "";
	
	$select_hash = array("hashkey" => $hashkey
			, "$expiration_field+0>" => "CURRENT_TIMESTAMP"
			, "published" => 1, "deleted" => 0);
	if ($freeze_cache == 1) {
		unset($select_hash["date_expiration>"]);
		if ($debug_cache == 1) pre("freeze_cache = 1, fetching $hashkey from cache despite expiration mark");
	}
	
	if ($db_field == "*") {
		$ret = array();
		$ret = select_entity_row($select_hash, $db_table);
		foreach(array_keys($ret) as $key) $ret[$key] = stripslashes($ret[$key]);
	} else {
		$ret = select_field($db_field, $select_hash, $db_table);
		$ret = stripslashes($ret);
	}
	
	return $ret;
}

function set_cached($hashkey, $content, $ident = "", $insert_expiration_minutes = 180
		, $db_field = "content", $db_table = "cached", $merge_updatehash = array()) {
	global $debug_query, $debug_cache, $freeze_cache, $today_datetime;

	$qa = select_fieldlistarray("*"
		, array("hashkey" => $hashkey, "deleted" => 0)		//"published" => 1, 
		, $db_table);

	$date_today_datehash = parse_datetime($today_datetime);
	$date_today_uts = datehash_2uts($date_today_datehash);

	if (isset($qa[0]) && $qa[0]["published"] == 1) {
		$row = $qa[0];

		$expiration_minutes = $row["expiration_minutes"];
		$date_expiration_uts = $date_today_uts + $expiration_minutes * 60;
		$date_expiration_ts = strftime("%Y%m%d%H%M%S", $date_expiration_uts); 

		$update_hash = array(
			$db_field => $content
			, "scriptname_updated" => $_SERVER["SCRIPT_NAME"]
//			, "date_expiration" => "DATE_ADD(CURDATE(),INTERVAL expiration_minutes MINUTES)"
			, "date_expiration" => $date_expiration_ts
//			, "date_updated" => "CURRENT_TIMESTAMP"
			, "date_published" => "CURRENT_TIMESTAMP"
			);
			
		if ($ident != "") $update_hash["ident"] = $ident;
		$update_hash = array_merge($update_hash, $merge_updatehash);

		$updated = update($update_hash, array("id" => $row["id"]), $db_table);

		if ($updated > 0 && $debug_cache == 1) {
			$qa = select_fieldlistarray("hashkey, expiration_minutes, date_expiration"
				, array("id" => $row["id"])
				, $db_table);
			unset($update_hash[$db_field]);
			pre(" update_hash=" . pr($update_hash) . "update_hashd=" . pr($qa[0])
				. " date_today_datehash=" . pr($date_today_datehash)
				. " date_today_uts=" . pr($date_today_uts)
				. " date_expiration_uts=" . pr($date_expiration_uts)
				);
		}
	} else {
		if ($ident == "") $ident = $hashkey;

		$date_expiration_uts = $date_today_uts + $insert_expiration_minutes;
		$date_expiration_ts = strftime("%Y%m%d%H%M%S", $date_expiration_uts); 

		$insert_hash = array(
			$db_field => $content
			, "ident" => $ident
			, "hashkey" => $hashkey
			, "expiration_minutes" => $insert_expiration_minutes
			, "date_expiration" => $date_expiration_ts
			, "scriptname_created" => $_SERVER["SCRIPT_NAME"]
//			, "date_updated" => "CURRENT_TIMESTAMP"
			, "date_published" => "CURRENT_TIMESTAMP"
			, "date_created" => "CURRENT_TIMESTAMP"
			);
		$inserted = insert($insert_hash, "cached");

		if ($inserted > 0 && $debug_cache == 1) {
			$qa = select_fieldlistarray("hashkey, expiration_minutes, date_expiration, date_updated"
				, array("id" => $inserted)
				, $db_table);
			pre("insert_hashed=[\n" . pr($insert_hash) . "\n] inserted_hash=[\n" . pr($qa[0]) . "\n");
		}
	}

}


function get_next_idrandom($table = "person", $fname = "idrandom", $size = 12) {
	$ret = 0;
	
	$idrandom_unique = 0;
	while ($idrandom_unique == 0) {
//		$idrandom = rand(100000000, 999999999);		//4294967295
		$idrandom = mt_rand(100000, 999999) . mt_rand(100000, 999999) . mt_rand(100000, 999999); //mysql:bigint?
		if (strlen($idrandom) > $size) $idrandom = substr($idrandom, 0, $size);
		
		$unique = check_uniquefield_dbtable($table, $fname, $idrandom);
		if ($unique == 1) {
			$ret = $idrandom;
			$idrandom_unique = 1;
		}
	}
	
	return $ret;
}

function check_uniquefield_dbtable($table = "person", $fname = "login", $value = "") {
	$ret = 0;
	
	$query = "select id from " . TABLE_PREFIX . "$table where $fname=$value";
	$result = mysql_query($query);
	if (mysql_num_rows($result) == 0) {
		$ret = 1;
	}
	
	return $ret;
}


function groupconcat_to_ahref($row, $fname
		, $tpl_single = ""
		, $tpl_multiple = ""
		, $separator = ", ", $truncate_limit = 20, $split_regex = "/~~/") {
		
	global $entity;

	$ret = "";

	$fname_strict = makestrict($fname);
	$item_basehash = array (
		"fname" => $fname,
		"fname_strict" => $fname_strict,
		"entity" => $entity,
	);
	
	if ($tpl_single != "" && $tpl_multiple == "") {
		$tpl_multiple = $tpl_single;
	}


	if ($tpl_single == "" && $tpl_multiple == "") {
		$tpl_single = "<a href='#ENTITY#.php?#FNAME_STRICT#=#ID#'>#IDENT#</a>";
		$tpl_multiple = "<a href='#ENTITY#.php?#FNAME_STRICT#=#ID#'>#IDENT_TRUNCATED#</a>";
	}

	$groups_splitted = preg_split($split_regex, $row[$fname]);
//	pre($groups_splitted);

	foreach ($groups_splitted as $onegroup) {
		$id_ident_splitted = array();
		preg_match("~(\d*)=(.*)~", $onegroup, $id_ident_splitted);
//		pre($id_ident_splitted);

		$item_hash = array();
		if (isset($id_ident_splitted[0])) {
			if ($ret != "") $ret .= $separator;
			
			$item_hash["id"] = $id_ident_splitted[1];
			$item_hash["ident"] = $id_ident_splitted[2];
			$item_hash["ident_truncated"] = firstletters_truncate($id_ident_splitted[2], $truncate_limit);
			
			$item_hash = array_merge($item_basehash, $item_hash);

			if (count($groups_splitted) <= 1) {
				$ret .= hash_by_tpl($item_hash, $tpl_multiple);
			} else {
				$ret .= hash_by_tpl($item_hash, $tpl_single);
			}
		} else {
			$ret .= $onegroup;
		}
	}

	return $ret;
}

function crc32_dbfields($row, $crc32_fields = array()) {
	$ret = 0;
	if (count($crc32_fields) == 0) return $ret;
	foreach (array_keys($row) as $row_field_name) {
		if (!in_array($row_field_name, $crc32_fields)) continue;
		$ret += crc32($row[$row_field_name]);
	}
	$ret = intval(sprintf("%u\n", $ret));
	return $ret;
}

function crc32_changed($row, $crc32_fields = array(), $table_name = "", $fixed_hash = array(), $update = 1, $crc32_field = "crc32"
		, $update_hash_merge = array()
		) {
	global $debug_query;
	$ret = 0;
	
//	pre($row);
//	pre($crc32_fields);

	$crc32_new = crc32_dbfields($row, $crc32_fields);
	if ($crc32_new == 0) {
		pre("crc32_dbfields() = 0");
		return -1;
	}

	$rows = select_fieldlistarray("id", $fixed_hash, $table_name);
	if (count($rows) != 1) {
		pre("crc32_changed($crc32_field, " . pr($fixed_hash) . ", $table_name) not unique: " . pr($rows));
		return -2;
	}

	$crc32_old = select_field($crc32_field, $fixed_hash, $table_name);
	if ($crc32_old != $crc32_new) {
		$ret = 1;
		if ($update == 1) {
//			$debug_query = 1;
			$update_hash = array($crc32_field => $crc32_new);
			$update_hash = array_merge($update_hash, $update_hash_merge);
			update($update_hash, $fixed_hash, $table_name);
//			$debug_query = 0;
		}
	}

	return $ret;
}

?>