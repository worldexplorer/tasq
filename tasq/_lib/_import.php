<?


//$log_infile = 0;
//$log_indb = 0;
$import_fromzip = 0;


if (!isset($log_infile)) $log_infile = 1;
if (!isset($log_indb)) $log_indb = 1;
if (!isset($import_ident)) $import_ident = "[import_ident] IS NOT SET";
if (!isset($import_fromcsv)) $import_fromcsv = 1;

	$headers_total = 0;
	$ignored_total = 0;
	$separator_total = 0;
	$pgroups_total = 0;
	$products_total = 0;
	$products_inpgroup = 0;
	$errors_total = 0;
	$errors_linenos = "";
	$rows_total = 0;
	$productcnt_became_not_available = 0;



$errormsg = "";
$importlog = "";
$fetchlog = "";
$import_query = "";

$dbupdate_hash = array (
	0 => "тестовый режим",
	1 => "рабочий режим"
	);

$dbupdate = get_number("dbupdate");
//if ($mode != "update" && $dbupdate == 0) $dbupdate = 1;
$rb_dbupdate = boolean_hash("dbupdate", $dbupdate, $dbupdate_hash);

$start_id = get_number("start_id");
if ($start_id == 0) $start_id = 1;
$stop_id = get_number("stop_id");

$start_lineno = get_number("start_lineno");
if ($start_lineno == 0) $start_lineno = 1;
$stop_lineno = get_number("stop_lineno");

$lastid = 0;
$serno_limit = get_number("serno_limit");
$serno = 0;

if (!isset($_REQUEST["cont"])) $_REQUEST["cont"] = "on";
$cont = (get_string("cont") == "on") ? 1 : 0;
$cb_continous = checkbox("cont", $cont);


ini_set("max_execution_time", 600);
$max_execution_time = ini_get("max_execution_time");
$start_execution_time = time();
$stop_execution_time = $start_execution_time + $max_execution_time - 5;
$last_execution_time = $stop_execution_time - time();

$rb_dbupdate = boolean_hash("dbupdate", $dbupdate, $dbupdate_hash);
$cb_continous = checkbox("cont", $cont);

$start_import = 0;
$stats_total = "";
$now_ts_hr = ts2human($today_ts_datetime);

if ($mode == "update" && $import_fromcsv == 1) {
	if (isset($_FILES["csv"]) && is_uploaded_file($_FILES['csv']['tmp_name'])) {
		$importlog .= "Получен файл [" . $_FILES['csv']['name'] . "], " . $_FILES['csv']['size']. " байт<br>";
		switch ($_FILES['csv']['type']) {
			case "application/octet-stream":
			case "application/vnd.ms-excel":
			case "text/csv":					// Firefox uploads with correct mime-type
				$importlog .=  "\n";
				$importlog .= "Начинаем импорт... <br>";
				$start_import = 1;
	//			move_uploaded_file($_FILES['csv']['tmp_name'],
	//			$importpath . $_FILES['csv']['name']);
				break;

			default:
				$errormsg .= "Неверный формат файла: [" . $_FILES['csv']['type'] . "]<br>";
				logf("\n\n$alertmsg\n");
		}
	} else {
		$importlog_id = get_number("importlog_id");
		$importlog_file1 = select_field("file1", array("id" => $importlog_id), "importlog");
		$importlog_file1_relpath = $upload_relpath . "importlog/$importlog_id/$importlog_file1";
		$importlog_file1_abspath = $upload_abspath . "importlog/$importlog_id/$importlog_file1";
		$prevous_import_reminder = 
			(file_exists($importlog_file1_abspath))
			? "<div>Файл от предыдущего импорта <a href='$importlog_file1_relpath'>" . $importlog_file1 . "</a><br><br></div>"
			: "<div>Файл от предыдущего импорта не существует <a href='$importlog_file1_relpath'>" . $importlog_file1 . "</a><br><br></div>";

		if (file_exists($importlog_file1_abspath)) {
			$start_import = 1;
		} else {
			$alertmsg .= "Вы не указали CSV-файл.";
			logf("\n\n$alertmsg\n");
		}
	}
}


if ($mode == "update" && $import_fromzip == 1) {
	if (isset($_FILES["zip"]) && is_uploaded_file($_FILES['zip']['tmp_name'])) {
		$importlog .= "Получен файл [" . $_FILES['zip']['name'] . "], " . $_FILES['zip']['size']. " байт<br>";
		if ($_FILES['zip']['type'] == "application/x-zip-compressed") {
			$importlog .=  "\n";
			$importlog .= "Начинаем импорт... <br>";
			$start_import = 1;

//			$import_ident .= " " . $_FILES["zip"]["tmp_name"];
//			move_uploaded_file($_FILES['zip']['tmp_name'],
//			$importpath . $_FILES['zip']['name']);
		} else {
			$errormsg .= "Неверный формат файла: [" . $_FILES['zip']['type'] . "]<br>";
			logf("\n\n$alertmsg\n");
		}
	} else {
		$alertmsg .= "Вы не указали ZIP-файл (mobile.de_export.zip)";
		logf("\n\n$alertmsg\n");
	}
}




$script_absname = $_SERVER["SCRIPT_FILENAME"];
//$script_absname = "";


$log_fname = $tmp_path . basename($script_absname) . "-log.html";
$log_fhandle = "";


if ($log_infile == 1 && $mode == "update") {
	if ($log_fhandle = fopen($log_fname, "w") ) {
		$importlog .= "Opened log file ($log_fname)<br>";
		logf("<meta http-equiv='Content-Type' content='text/html; charset=windows-1251'>\n");
		logf("<style>body { font-family: Tahoma, Verdana, Arial; font-size: 66%;}</style>\n");
		logf("<pre>\n");
	} else {
		$errormsg .= "_L: Cannot open log file ($log_fname)<br>";
		echo $errormsg;
		exit;
	}
}

$log_href = "";

if (is_file($log_fname)) {
	$log_href = basename($log_fname);
	$log_href = "<a href='$tmp_relpath$log_href' target=_blank>$log_href</a>";
}

if ($log_href != "") {
	$log_href = <<< EOT

<div>
Лог предыдущего импорта:<br>
$log_href</div>
EOT;
}

function default_import_form() {
	global $start_id, $stop_id, $serno_limit, $import_ident
		, $last_execution_time, $stop_execution_time, $now_ts_hr
		, $rows_total, $serno, $rb_dbupdate, $log_href, $cb_continous;

	$last_execution_time = $stop_execution_time - time();

	$ret = <<< EOT

<table style="border: 1px solid gray" cellpadding=5 align=center width=500>
<form enctype="multipart/form-data" method=post>
<input type=hidden name=mode value=update>
<tr valign=top><td>Импорт [$import_ident]</td><td align=right>$now_ts_hr</td></tr>

<tr><td colspan=2>Excel -> Сохранить Как... -> "CSV, разделители - запятые"</td></tr>

<tr valign=top><td>
<span align=right style="width=100px;">Файл:</span>
<input type=file size=30 name=csv><br>

<!--span align=right style="width=100px;">Старт ID:</span>
<input type=textfield size=6 name=start_id value=$start_id><br>

<span align=right style="width=100px;">Стоп ID:</span>
<input type=textfield size=6 name=stop_id value=$stop_id><br-->

<span align=right style="width=100px;">Лимит строк:</span>
<input type=textfield size=6 name=serno_limit value=$serno_limit> (0 = без лимита)<br>

<br>

<span align=right style="width=100px;">Осталось времени:</span>
$last_execution_time секунд<br>

<span align=right style="width=100px;">Выбрано:</span>
$rows_total записей<br>

<span align=right style="width=100px;">Обработано:</span>
$serno записей<br>

</td><td>
$rb_dbupdate<br>
$log_href
<!--$cb_continous <label for=cont>Импорт с продолжением</label-->
</td></tr>
<tr><td colspan=2 align=center><input type=submit value="Импортировать"></td></tr>
</form>
</table>
EOT;
	
	return $ret;
}

function logf($str) {
	global $importlog, $log_infile, $log_fname, $log_fhandle;
	
	if ($log_infile == 1 && $log_fhandle != "") {
		$str_log = str_replace ("\n", "\r\n", $str);
		if (fwrite($log_fhandle, $str_log) === FALSE) {
			$errormsg .= "_L: Cannot write to log file ($log_fname [$log_fhandle])<br>";
		}
	}

	$importlog .= $str;
}



function limits_exceeded() {
	global $last_execution_time, $stop_execution_time, $serno, $serno_limit, $start_lineno, $stop_lineno, $stop_id, $lastid, $errormsg;
	$ret = 0;

	$last_execution_time = $stop_execution_time - time();
	
	$limitmsg = "";

	$serno++;
	if ($serno_limit> 0 && $serno >= $serno_limit) {
		$limitmsg = "Выход по лимиту строк: serno[{$serno}] >= serno_limit[{$serno_limit}]<br>";
		$ret = 1;
	}

	if ($stop_id > 0 && $lastid > $stop_id) {
		$limitmsg = "Выход по последнему ID: lastid[{$lastid}] >= stop_id[{$stop_id}]<br>";
		$ret = 1;
	}

	if ($stop_lineno > 0 && (($start_lineno + $serno + $serno_limit) >= $stop_lineno)) {
		$limitmsg = "Выход по номеру последней строки: (start_lineno[{$start_lineno}] + serno[{$serno}] + serno_limit[{$serno_limit}] >= stop_lineno[{$stop_lineno}]<br>";
		$ret = 1;
	}

	if ($last_execution_time <= 0) {
		$limitmsg = "Выход по времени выполнения: last_execution_time[{$last_execution_time}] <= 0<br>";
		$ret = 1;
	}

	if ($ret == 1) {
		logf("\n\n\n" . $limitmsg);
		$errormsg .= $limitmsg;
	}

	return $ret;
}

function trim_flatrow($row) {
	$ret = array();
	foreach ($row as $value) {
//		$value = addslashes(trim($value, " \""));
		$value = addslashes(trim($value, " "));
		$value = str_replace("\n", "\r\n", $value);		//Excel inserts \n, textarea will change to \r\n; next import matching will fail...
		$ret[] = $value;
	}

//	foreach ($row as $value) $ret[] = addslashes(trim($value, " \0xA2"));
//	pre ($ret);
	return $ret;
}



function resolve_dictionnaries($product_hash, $dict_list, $should_insert = 1) {
	global $dbupdate;

	foreach ($dict_list as $dict) {
		if (!isset($product_hash[$dict])) continue;
		$dict_strict = makestrict($dict);
		$value = $product_hash[$dict];
// NO!!!		$value = htmlentities($value, ENT_QUOTES);

//		$value = str_replace("'", "&#039", $value);
//		$value = htmlspecialchars($value, ENT_QUOTES);
	
		$select_hash = array("ident" => $value);
		
		$value_id = select_field("id", $select_hash, $dict_strict);
	

//		if ($value_id == "" && $dict == "supplier") $value_id = select_field("id", array("ident~" => $value), $dict);
	
	
		if (intval($value_id) == 0 && $should_insert == 1) {
			if ($dbupdate == 0) {
				$value_id = "inserting[$value]to[$dict_strict]";
			} else {
				$insert_hash = array_merge($select_hash,
					array("ident" => $value),
					array("published" => 1, "date_created" => "CURRENT_TIMESTAMP"));
				if ($dict_strict == "pgroup") $insert_hash["parent_id"] = 1;
				$value_id = insert($insert_hash, $dict_strict);
			}
		}
		
		$product_hash[$dict] = $value_id;
	}
	return $product_hash;
}


function testing_zip() {
	global $tmp_path, $importlog;

	$ret = 1;
	$fetchlog = "";

	$zip = zip_open($_FILES["zip"]["tmp_name"]);

	if ($zip) {
		while ($zip_entry = zip_read($zip)) {
			$zip_frelname = zip_entry_name($zip_entry);
			$zip_fsize = zip_entry_filesize($zip_entry);
	
			if ($zip_fsize == 0) continue;	//directory entry
	
			$fetchlog .= "<tr><td>$zip_frelname</td><td width=15></td>";
			
			if (zip_entry_open($zip, $zip_entry, "r")) {
				$zip_fcontent = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
				
				$tmp_absfname = tempnam($tmp_path, "php_unzip_");
	
				$fp = fopen($tmp_absfname, "w");
				if ($fp == FALSE) {
					$fetchlog .= "<td>... failed on fopen($tmpfname)</td></tr>\n";
					continue;
					$ret = 1;
				}
	
				fwrite($fp, $zip_fcontent);
				fclose($fp);
				
				$tmp_filesize = filesize($tmp_absfname);
				if($tmp_filesize != $zip_fsize) {
					$fetchlog .= "<td>... failed, zip_fsize=[$zip_fsize] tmp_filesize=[$tmp_filesize]</td></tr>\n";
					unlink($tmp_absfname);
					continue;
					$ret = 1;
				}
	
				$fetchlog .= "<td>... extracted ok</td><td width=15><td align=right>$tmp_filesize bytes</td></tr>\n";
				unlink($tmp_absfname);
	
				zip_entry_close($zip_entry);
			} else {
				$fetchlog .= "<td>... cant zip_entry_open()</td></tr>";
			}
		}
		zip_close($zip);
	} else {
		$importlog .= "zip_open(" . $_FILES["zip"]["tmp_name"] . ") failed<br>";
	}


	if ($fetchlog != "") {
		$importlog .= <<< EOT
<table cellpadding=0 cellspacing=5 style="border: 1 solid gray">
<tr><th>Testing ZIP contents</th></tr>
<tr><td>
	<table cellpadding=0 cellspacing=0>
	$fetchlog
	</table>
</td></tr>
</table>
EOT;
	}

	return $ret;
	
}


?>