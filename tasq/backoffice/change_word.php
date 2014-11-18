<? require_once "../_lib/_init.php" ?>
<?

$log_infile = 0;
$log_indb = 0;
$import_fromzip = 0;
$import_ident = "Замена слова по всей БД";

require_once "_import.php";

$dbupdate = get_number("dbupdate");
//if ($mode != "update" && $dbupdate == 0) $dbupdate = 0;
$rb_dbupdate = boolean_hash("dbupdate", $dbupdate, $dbupdate_hash);

$kw_search = get_string("kw_search");
$kw_replace = get_string("kw_replace");

$process_log = "";
$found_cnt = 0;

$dbtable_limit = 0;
$skip_tables = array("sentlog");

$table_tags = array("~<td.*>~si", "~</td>~si", "~<tr.*>~si", "~</tr>~si", "~<table.*>~si", "~</table>~si");

if ($mode != "" && $kw_search != "" && $kw_replace != "") {


/*
	$dbtables = array();
	for ($i = 0; $i < mysql_num_rows($dbtables_result); $i++) {
	    $dbtables[] =  mysql_tablename($dbtables_result, $i);
	}
	
	//pre($dbtables);
	
	foreach ($dbtables as $dbtable) {
		    $entity_dbfields_array[] =  mysql_field_name($entity_dbfields_result, $i);
*/

	$dbtables_result = mysql_list_tables($mysql_info["db"]);
	for ($i = 0; $i < mysql_num_rows($dbtables_result); $i++) {
	    $dbtable =  mysql_tablename($dbtables_result, $i);
	    if (in_array($dbtable, $skip_tables)) continue;
//		pre($dbtable);
		
		$entity_dbfields_array = array();
		$entity_dbfields_result = mysql_list_fields($mysql_info["db"], $dbtable);
		for ($j = 0; $j < mysql_num_fields($entity_dbfields_result); $j++) {
			$type  = mysql_field_type($entity_dbfields_result, $j);
		    $name  = mysql_field_name($entity_dbfields_result, $j);
		    $len   = mysql_field_len($entity_dbfields_result, $j);
		    $flags = mysql_field_flags($entity_dbfields_result, $j);


//			pre($type . " " . $name . " " . $len . " " . $flags);
//			pre($name . "[" . $len . "]" . " " . $type);
//			if ($type != "string" || $type != "blob") continue;
			if ($name != "ident" && $name != "brief" && $name != "content" && $name != "comment" && $name != "announce") continue;

			$query = "select id, $name from $dbtable where $name like '%$kw_search%'";
			$qa = select_queryarray($query);
			foreach ($qa as $row) {
				$found_id = $row["id"];

				$found = "";
				$replaced = "";
				$take_before = 40;
				$take_after = 40;

				$checked_str = get_string("$dbtable:$found_id:$name");
				if ($checked_str == "" && $mode == "search") $checked_str = "on";
				$update_field = ($checked_str == "on") ? 1 : 0;
				$checked = ($update_field == "1") ? "checked" : "";

				$updated = "";

				$subject = $row[$name];
				$subject_filtered = preg_replace($table_tags, '', $subject);
				
				$matches = array();
				preg_match_all ("~$kw_search~i", $subject_filtered, $matches, PREG_OFFSET_CAPTURE);
//				pre($matches);
				
				foreach ($matches as $first_list) {
//					foreach ($first_list as $matched_hash) {
//					if (count($first_list) > 1) {
//						pre("$dbtable:$found_id");
//						pre($first_list);
//					}

					for ($k=0; $k<count($first_list); $k++) {
						$matched_hash = $first_list[$k];
						
						$found_k = $matched_hash[0];
						$pos = $matched_hash[1];
	
						$pos_start = ($pos-$take_before >= 0) ? $pos-$take_before : 0;
						$pos_len = ($pos+strlen($kw_search)+$take_after >= strlen($subject_filtered))
							? strlen($kw_search) : strlen($kw_search)+$take_after;
//						pre("$dbtable:$found_id pos=[" . $pos . "] pos_start=[" . $pos_start . "] pos_len=[" . $pos_len . "] pos+strlen(kw_search)+20=[" . ($pos+strlen($kw_search)+20) . "] strlen(subject)=[" . strlen($subject) . "]");

						$found_k = substr($subject_filtered, $pos_start, $take_before + $pos_len);
						$found .= $found_k;
						if ($k < count($first_list)-1) {
							$found .= "<hr>";
						}
					}
				}

				$subject = preg_replace("~$kw_search~", $kw_replace, $subject);
				$subject_filtered = preg_replace($table_tags, '', $subject);

				$matches = array();
				preg_match_all ("~$kw_replace~", $subject_filtered, $matches, PREG_OFFSET_CAPTURE);
//				pre($matches);

				foreach ($matches as $first_list) {
					for ($k=0; $k<count($first_list); $k++) {
						$matched_hash = $first_list[$k];
					
						$replaced_k = $matched_hash[0];
						$pos = $matched_hash[1];
	
						$pos_start = ($pos-$take_before >= 0) ? $pos-$take_before : 0;
						$pos_len = ($pos+strlen($kw_search)+$take_after >= strlen($subject))
							? strlen($kw_search) : strlen($kw_search)+$take_after;
						$replaced_k = substr($subject_filtered, $pos_start, $take_before + $pos_len);
						$replaced .= $replaced_k;
						if ($k < count($first_list)-1) {
							$replaced .= "<hr>";
						}
					}
				}

				if ($replaced != "") {
					if ($mode == "update" && $dbupdate == 1 && $update_field == 1) {
						$updated = update (array($name => $subject), array("id" => $found_id), $dbtable);
					}
				} else {
					$replaced = "<center><em>строчные/прописные</em></center>";
					$checked = "";
				}

				$bgcolor = ($updated > 0) ? OPTIONS_COLOR_GREEN : OPTIONS_COLOR_WHITE;

				$dbtable_noprefix = $dbtable;
				$lib_prefix = (LIB_PREFIX != "") ? LIB_PREFIX . "/" : "";
				if (defined("TABLE_PREFIX")) {
					$dbtable_noprefix = substr($dbtable, strlen(TABLE_PREFIX), strlen($dbtable));
//					pre("substr($dbtable, " . strlen(TABLE_PREFIX) . ", " . strlen($dbtable) . ")");
				}
				$process_log .= <<< EOT
<tr style="background-color: $bgcolor">
	<td><a href="/${lib_prefix}backoffice/$dbtable_noprefix-edit.php?id=$found_id" target=_blank>$dbtable:$found_id</a></td>
	<td>$name</td>
	<td>$found</td>
	<td>$replaced</td>
	<td align=center><input type=checkbox name="$dbtable:$found_id:$name" $checked> $updated</td>
</tr>
EOT;
				$found_cnt++;
			}

		}
//		break;
		if ($dbtable_limit > 0 && $i >= $dbtable_limit-1) break;

	}
}

if ($mode != "" && ($kw_search == "" || $kw_replace == "")) {
	$errormsg = $alertmsg = "Заполните пожалуйста оба поля";
}


if ($mode != "" && $kw_search != "" && $kw_replace != "") {
	if ($process_log == "") {
		$process_log = "<tr><td colspan=5 style='padding:20' align=center><b>не найдено [$kw_search] во всех значимых полях БД сайта</b></td></tr>";
	} else {
		$process_log .= <<< EOT
	<tr><td colspan=5 align=right><input type=submit value="Заменить выделенные"></td></tr>
EOT;
	}
}

if ($process_log != "") {
	$process_log = <<< EOT
<table cellpadding=3 cellspacing=1 border=0 width=100% class=gw>
<form method=post>
<input type=hidden name=mode value=update>
<input type=hidden name=kw_search value="$kw_search">
<input type=hidden name=kw_replace value="$kw_replace">
<input type=hidden name=dbupdate value="$dbupdate">
<tr>
	<th>Таблица:ID</th>
	<th>Поле</th>
	<th>До</th>
	<th>После</th>
	<th>Заменить</th>
</tr>
$process_log
</form>
</table>
EOT;
}


$import_form = <<< EOT

<table style="border: 1px solid gray" cellpadding=5 cellspacing=2 border=0 width=100%>
<form method=get>
<input type=hidden name=mode value=search>
<tr valign=top><td colspan=2>$import_ident</td><td align=right>$now_ts_hr</td></tr>
<tr valign=top>
	<td>

	<table cellpadding=0 cellspacing=3 border=0 align=center style="border: 1px solid gray">
	<caption>желательно без пробелов</caption>
	<tr>
		<td align=right>Что ищем:</td>
		<td><input type="text" size=20 name=kw_search value="$kw_search"></td>
		<td rowspan=2>$rb_dbupdate</td>
	</tr>
	<tr>
		<td align=right>На что заменить:</td>
		<td><input type="text" size=20 name=kw_replace value="$kw_replace"></td>
	</tr>

	<tr><td colspan=3 align=center><input type=submit value="Искать"></td></tr>
	</table>

	</td>
</tr>

<tr><td height=20></td></tr>
</form>
<tr><td colspan=2>$process_log</td></tr>
<tr><td height=10></td></tr>
</table>

EOT;


$content = $import_form;

if ($start_import == 1 && $dbupdate == 1) logf($content);
?>


<? require_once "_top.php" ?>
<?=$content?>


<? require_once "_bottom.php" ?>