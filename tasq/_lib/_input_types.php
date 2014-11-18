<?



$formula_debug = 0;
$formula_log = "";


function formula($name, $value = 0, $formula = "", $hashkey_self = "", $ic_rows, $graycomment = "", $tag_attr = "") {
	global $input_size, $layer_inside, $formula_debug, $formula_log;

	
	$tf_size = $input_size["number"];
	if ($layer_inside == 1) $tf_size = $input_size["number_insidelayer"];

	$value = formula_interpreter($hashkey_self, $ic_rows);
	if ($value == "") $value = 0;
	$value = htmlspecialchars($value, ENT_QUOTES);

//	$ret = "<input type=text size=$tf_size name='$name' value=\"$value\" $tag_attr disabled>";

//	$new_tf_size = $tf_size-4;
//	$ret = "<input type=text size=$new_tf_size name='$name' value=\"$value\" $tag_attr style='font-weight:bold'>";

	$ret = "<div style='border: 1px solid gray; width:11em; padding:2px; margin-right:1ex; float: left'><b>$value</b></div>";

	if ($graycomment != "") $ret .= "&nbsp;&nbsp;&nbsp;&nbsp;<font style='color: " . OPTIONS_COLOR_GRAY . "'>$graycomment</font>";
//	$ret .= "&nbsp;&nbsp;&nbsp;&nbsp;<a href='' title='$formula'>edit manually</a>";

	if ($formula_debug == 1 && $formula_log != "") $ret .= "<br><pre>$formula_log</pre>";

	return $ret;
}

function formula_interpreter($hashkey_self, $ic_rows) {
	global $formula_debug, $formula_log;

	$ret = "";
	
	$formula_log = "";
	$var_stack = array();
	
	foreach ($ic_rows as $ic) {
		if ($ic["ictype_hashkey"] != "NUMBER") continue;
		$var_stack[$ic["hashkey"]] = floatval($ic["iccontent"]);
		if ($ic["hashkey"] == $hashkey_self) break;
	}
//	$formula_log = pr($var_stack) . "\n";

	foreach ($ic_rows as $ic) {
		if ($ic["ictype_hashkey"] == "FORMULA") {
			$regexp = "~([+*/-])?\s*(\S+)\s*~is";
			$matches = array();
			preg_match_all($regexp, $ic["param1"], $matches);
//			pre($matches);

			if (!isset($matches[1])) {
					$formula_log .= "no matches for regexp[$regexp] in formula[" . $ic["param1"] . "]\n";
					continue;
			}
			
			
			$formula_log .= $ic["hashkey"] . ":\n";

			$tmp_value = 0;
			for ($i=0; $i<count($matches[1]); $i++) {
				$operator	= $matches[1][$i];
				$var		= $matches[2][$i];

				if (!isset($var_stack[$var])) {
					$formula_log .= "variable[$var] not found in stack[" . pr($var_stack) . "]"
						. " for operator[$operator] formula[" . $ic["param1"] . "]\n";
					continue;
				}
				
				switch ($operator) {
					case "-":
						$tmp_value -= $var_stack[$var];
						break;

					case "*":
						$tmp_value *= $var_stack[$var];
						break;

					case "/":
						$tmp_value /= $var_stack[$var];
						break;

					default:
						$tmp_value += $var_stack[$var];
						break;
				
				}

				$formula_log .= "\t[$operator] $var" . "[" . $var_stack[$var] . "] = $tmp_value\n";
			}
			
			$var_stack[$ic["hashkey"]] = $tmp_value;
			$formula_log .= "\t$tmp_value\n";
		}

		if ($ic["hashkey"] == $hashkey_self) break;
	}
	
	$ret = $var_stack[$hashkey_self];

	$formula_log .= "\n";
	$formula_log .= pr($var_stack) . "\n";

	return $ret;
}


function depend($name, $value, $default, $param1) {
	global $entity, $id;
	global $msg_bo_depend_of_that_group;
	
	$ret = "";
	
	$masterdepend_entity = masterdepend_entity();
	$masterdepend_entity_msg_h = isset($entity_list[$masterdepend_entity])
									 ? $entity_list[$masterdepend_entity] : "";

	$hash = array(
		"masterdepend_entity" => $masterdepend_entity,
		"masterdepend_entity_msg_h" => $masterdepend_entity_msg_h,
		"disabled" => ($id == 0) ? "disabled" : ""
		);

	$tpl = "<a href='#masterdepend_entity#.php?$entity=$id' #DISABLED#>#masterdepend_entity_msg_h# $msg_bo_depend_of_that_group</a>";
	if ($default != "") $tpl = $default;
	
	$ret = hash_by_tpl($hash, $tpl);
}

function htmlspecialchars_noamp($value, $quote_style = ENT_NOQUOTES, $br2nl = 0) {
	$ret = $value;
	
	switch ($quote_style) {
		case ENT_COMPAT:
			$ret = str_replace('"', "&quot;", $ret);
			break;
	
		case ENT_QUOTES:
			$ret = str_replace('"', "&quot;", $ret);
			$ret = str_replace("'", "&#039", $ret);
			break;
	
		case ENT_NOQUOTES:
			break;
	}

	if ($br2nl == 1) {
		$ret = str_replace("<br>", "\n", $ret);
		$ret = str_replace("<br />", "\n", $ret);
		$ret = preg_replace("/^\n*/", "", $ret);
		$ret = preg_replace("/\n*$/", "", $ret);
	}

	
	$ret = str_replace("<", "&lt;", $ret);
	$ret = str_replace(">", "&gt;", $ret);
	
	return $ret;
}

function freetext_600($name, $value, $default = "", $graycomment = "") {
	return freetext($name, $value, $default, $graycomment, 600, 600);
}

function freetext_450($name, $value, $default = "", $graycomment = "") {
	return freetext($name, $value, $default, $graycomment, 450, 600);
}

function freetext_200($name, $value, $default = "", $graycomment = "") {
	return freetext($name, $value, $default, $graycomment, 200, 600);
}

function freetext($name, $value, $default = "", $graycomment = "", $height = 350, $width = 600) {
	global $FTB_Style, $FTB_version, $FTB_DesignModeCss, $FTB_HtmlModeCss;
	$ret = "";

	$FTB_tpl_fname = $_SERVER["DOCUMENT_ROOT"] . "/_FTB" . $FTB_version . "/FTB-template.html";
	$MCE_tpl_fname = $_SERVER["DOCUMENT_ROOT"] . "/_MCE/MCE-template-item.html";

	if (file_exists($MCE_tpl_fname)) {
		$ret = freetext_MCE($name, $value, $default, $height, $width);
	}
	
	if (file_exists($FTB_tpl_fname) && $ret != "") {
		$ret = freetext_FTB($name, $value, $default, $height, $width);
	}
	
	if ($graycomment != "") $ret .= "<br><font style='color: " . OPTIONS_COLOR_GRAY . "'>$graycomment</font>";

		return $ret;
}

function freetext_MCE($name, $value, $default = "", $height = 350, $width = 600) {
	global $freetext_fields;
	global $input_size, $layer_inside;

//	pre("freetext_MCE($name, $value, $default, $height = 350, $width = 600)");
//	pre("freetext_MCE($name, $default, $height = 350, $width = 600)");

	static $MCE_tpl = "";

	if ($height == "") $height = 350;
	
	$ta_size = $input_size["freetext"];
	if ($layer_inside == 1) $ta_size = $input_size["freetext_insidelayer"];

	$value = htmlspecialchars_noamp($value, ENT_QUOTES);

	$freetext_fields[] = $name;
//	pre($freetext_fields);
	
	if ($MCE_tpl == "") {
		$MCE_tpl_fname = $_SERVER["DOCUMENT_ROOT"] . "/_MCE/MCE-template-item.html";
		$fd = fopen ($MCE_tpl_fname, "r");
		$MCE_tpl = fread ($fd, filesize($MCE_tpl_fname));
		fclose ($fd);
//		pre($MCE_tpl);
	}
	

	$hash = array(
		"name" => $name,
		"value" => $value,
		"height" => $height,
		"width" => "100%",
		);
	$ret = hash_by_tpl($hash, $MCE_tpl, "", 0, 0);
	
	return $ret;
}


function freetext_MCE_jsinheader() {
	global $FTB_Style, $FTB_version, $FTB_DesignModeCss, $FTB_HtmlModeCss;
	global $freetext_fields, $tinymce_skipjs;

	if (count($freetext_fields) == 0 || $tinymce_skipjs == 1) return;
	if (strpos($_SERVER["REQUEST_URI"], "-edit.php") === false) return;

//	pre("freetext_MCE_jsinheader()");
//	pre($freetext_fields);

	$MCE_tpl_fname = $_SERVER["DOCUMENT_ROOT"] . "/_MCE/MCE-template.html";
	$fd = fopen ($MCE_tpl_fname, "r");
	$MCE_tpl = fread ($fd, filesize($MCE_tpl_fname));
	fclose ($fd);


	$freetext_names_comma = "";

	foreach ($freetext_fields as $field) {
		$freetext_names_comma .= ",$field";
	}
	
	$hash = array (
		"content_css" => $FTB_DesignModeCss,
		"freetext_names_comma" => $freetext_names_comma,
	);
	$ret = hash_by_tpl($hash, $MCE_tpl, "", 0, 0);
	
	echo $ret;
}


function freetext_FTB($name, $value, $default = "", $height = 350, $width = 600) {
	global $FTB_StartUpArray;
	global $FTB_Style, $FTB_version, $FTB_DesignModeCss, $FTB_HtmlModeCss;
	global $input_size, $layer_inside;

	static $FTB_tpl = "";
	
	if ($height == "") $height = 350;
	
	$ta_size = $input_size["freetext"];
	if ($layer_inside == 1) $ta_size = $input_size["freetext_insidelayer"];

//	$ta_size_iframe = $ta_size - 8;
	$ta_size_iframe = "100%";

	$value = htmlspecialchars_noamp($value, ENT_QUOTES);

	if ($FTB_StartUpArray <> "") $FTB_StartUpArray .= ", ";
	$FTB_StartUpArray .= "'$name'";

	if ($FTB_tpl == "") {
		$FTB_tpl_fname = $_SERVER["DOCUMENT_ROOT"] . "/_FTB" . $FTB_version . "/FTB-template.html";
		$fd = fopen ($FTB_tpl_fname, "r");
		$FTB_tpl = fread ($fd, filesize($FTB_tpl_fname));
		fclose ($fd);
	}
	
	if ($FTB_version != "207") $height = $height - 100;
	
	$hash = array(
		"ftb_designmodecss" => $FTB_DesignModeCss,
		"ftb_htmlmodecss" => $FTB_HtmlModeCss,
		"ftb_style" => $FTB_Style,
		"name" => $name,
		"value" => $value,
		"ta_size" => $ta_size,
		"ta_size_iframe" => $ta_size_iframe,

		"height" => $height . "px",
		"height-1" => $height - 1 . "px",

//		"width" => $width . "px",
//		"width-2" => $width - 2 . "px"
		"width" => "100%",
		"width-2" => "100%"
		);
	$ret = hash_by_tpl($hash, $FTB_tpl, "", 0, 0);
	
	return $ret;
}

function boolean_hash($name, $value = "_global", $opt_hash = array(0 => "no", 1 => "yes"), $onclick = "") {
	$ret = "";
	$gray = OPTIONS_COLOR_GRAY;

//	pre("boolean_hash($name, $value, " . pr($opt_hash) . ", $onclick");
	if ($value == "_global") $value = get_number($name);

	foreach($opt_hash as $key => $hashvalue) {
		$checked = ($key == $value) ? "checked" : "";
		
/*
		$ret .= <<< EOT
<div><input type="radio" name="$name" id="${name}_rb_$key" value="$key" $checked $onclick>
<label for="${name}_rb_$key">$hashvalue</label></div>
EOT;
*/
		$ret .= <<< EOT
<tr>
	<td><input type="radio" name="$name" id="${name}_rb_$key" value="$key" $checked $onclick></td>
	<td width=3></td>
	<td><label for="${name}_rb_$key">$hashvalue</label></td>
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
		</table>
	</td>
</tr>
</table>

EOT;
	}

	return $ret;
}

function tristate($name, $value = "_global", $default = "_global") {
	$ret = "";

 	global $tristate_opthash;
	$ret = select_hash($name, $value, $tristate_opthash);

	return $ret;
}



function getMinute_dd($minute) {
	$ret = "";
	$delta = 5;

	for ($i=0; $i<60; $i+=$delta) {
		$ret .= "<option value=$i";
		if ($minute >= $i-($delta/2)-1 && $minute <= $i+($delta/2) ) $ret .= " selected";
		$s_min = $i;
		if ($i == 0) $s_min = "00";
		if ($i == 5) $s_min = "05";
		$ret .= ">" . $s_min . "</option>";
	}
	return $ret . "\n";
}

function getHour_dd($hour) {
	$ret = "";

	$hours = array("00", "01", "02", "03", "04", "05",
						"06", "07", "08", "09", "10", "11",
						"12", "13", "14", "15", "16", "17",
						"18", "19", "20", "21", "22", "23");
	foreach ($hours as $i) {
		$ret .= "<option value=$i";
		if ($i == $hour) $ret .= " selected";
		$ret .= ">" . $i . "</option>";
	}
	return $ret . "\n";
}


function getDay_dd($day) {
	$ret = "";

	$was_selected = 0;
	for ($i=1; $i<32; $i++) {
		$selected = "";
		if ($i == $day) {
			$selected = "selected";
			$was_selected = 1;
		}

		$ret .= "<option value='$i' $selected>$i</option>";
	}

	$selected = ($was_selected == 0) ? "selected" : "";
	$ret = "<option value='0' $selected>&nbsp;</option>" . $ret;

	return $ret . "\n";
}

function getMonth_dd($month) {
	global $months_when;
	$ret = "";

	$was_selected = 0;
	for ($i=1; $i<count($months_when); $i++) {
		$selected = "";
		if ($i == $month) {
			$selected = "selected";
			$was_selected = 1;
		}

		$ret .= "<option value='$i' $selected>$months_when[$i]</option>";
	}

	$selected = ($was_selected == 0) ? "selected" : "";
	$ret = "<option value='0' $selected>&nbsp;</option>" . $ret;

	return $ret . "\n";
}

function getYear_dd($year, $year_high = 5, $year_low = 10) {
	$ret = "";

//	$fromyear = 1900;

	$today = getdate();
	$fromyear = $today['year'] + $year_high;
	$tillyear = $today['year'] - $year_low;

	if ($year_high > 1000) $fromyear = $year_high;
	if ($year_low > 1000) $tillyear = $year_low;

	$was_selected = 0;
	for ($i = $fromyear; $i >= $tillyear; $i--) {
		$selected = "";
		if ($i == $year) {
			$selected = "selected";
			$was_selected = 1;
		}

		$ret .= "<option value='$i' $selected>$i</option>";
	}

	$selected = ($was_selected == 0) ? "selected" : "";
	$ret = "<option value='0' $selected>&nbsp;</option>" . $ret;

	return $ret . "\n";
}

function getBYear_dd($year, $year_high = 10, $year_low = 80) {
	$ret = "";

	$today = getdate();

	$fromyear = $today['year'] - $year_high;
	$tillyear = $today['year'] - $year_low;

	if ($year_high > 1000) $fromyear = $year_high;
	if ($year_low > 1000) $tillyear = $year_low;

//	$fromyear = $today['year'] - 10;
//	$tillyear = $today['year'] - 80;
//	$tillyear = 1930;

	$was_selected = 0;
	for ($i = $fromyear; $i >= $tillyear; $i--) {
		$selected = "";
		if ($i == $year) {
			$selected = "selected";
			$was_selected = 1;
		}

		$ret .= "<option value='$i' $selected>$i</option>";
	}

	$selected = ($was_selected == 0) ? "selected" : "";
	$ret = "<option value='0' $selected>&nbsp;</option>" . $ret;

	return $ret . "\n";
}

function select_date($name
	, $date_hash = array (
		"year" => "0000",	"month" => "00",	"day" => "00",
		"hour" => "00",		"minute" => "00",	"second" => "00")
	, $range = "default", $length = "date"
	, $year_high = 0, $year_low = 0
	) {

	$ret = "";

	$options_day = getDay_dd($date_hash["day"]);
	$options_month = getMonth_dd($date_hash["month"]);
	switch ($range) {
		case "birthdate":
			if ($year_high > 0 && $year_low > 0) {
				$options_year = getBYear_dd($date_hash["year"], $year_high, $year_low);
			} else {
				$options_year = getBYear_dd($date_hash["year"]);
			}
			break;
		default:
			if ($year_high > 0 && $year_low > 0) {
				$options_year = getYear_dd($date_hash["year"], $year_high, $year_low);
			} else {
				$options_year = getYear_dd($date_hash["year"]);
			}
	}

	$ret = <<< EOT
<select name="{$name}_day">$options_day</select>
<select name="{$name}_month">$options_month</select>
<select name="{$name}_year">$options_year</select>
EOT;


	if ($length == "datetime") {
		$options_hour = getHour_dd($date_hash["hour"]);
		$options_minute = getMinute_dd($date_hash["minute"]);
		$options_second = getMinute_dd($date_hash["second"]);
		$ret .= <<< EOT
&nbsp;&nbsp;
<select name="{$name}_hour">$options_hour</select> :
<select name="{$name}_minute">$options_minute</select> :
<select name="{$name}_second">$options_second</select>
EOT;

	}

	return $ret;
}


function parse_timestamp($value) {
	$date_hash = array (
		"year" => "0000",	"month" => "00",	"day" => "00",
		"hour" => "00",		"minute" => "00",	"second" => "00");

	$matches = array();
	preg_match ("/(....)(..)(..)(..)(..)(..)/", $value, $matches);
//	print_r($matches);

	if (count($matches) > 0) {
		$date_hash["year"] = $matches[1];
		$date_hash["month"] = $matches[2];
		$date_hash["day"] = $matches[3];
		$date_hash["hour"] = $matches[4];
		$date_hash["minute"] = $matches[5];
		$date_hash["second"] = $matches[6];
		return $date_hash;

	}

	preg_match ("/(....)(..)(..)/", $value, $matches);
//	print_r($matches);

	if (count($matches) > 0) {
		$date_hash["year"] = $matches[1];
		$date_hash["month"] = $matches[2];
		$date_hash["day"] = $matches[3];
		return $date_hash;

	}
}

function parse_datetime($value) {
	$date_hash = array (
		"year" => "0000",	"month" => "00",	"day" => "00",
		"hour" => "00",		"minute" => "00",	"second" => "00");


	$matches = array();
	preg_match ("/(\d{4})\D*(\d{2})\D*(\d{2})\D*(\d{2})\D*(\d{2})\D*(\d{2})/U", $value, $matches);
//	print_r($matches);

	if (count($matches) > 0) {
		$date_hash["year"] = $matches[1];
		$date_hash["month"] = $matches[2];
		$date_hash["day"] = $matches[3];
		$date_hash["hour"] = $matches[4];
		$date_hash["minute"] = $matches[5];
		$date_hash["second"] = $matches[6];
		return $date_hash;

	}

	preg_match ("/(\d{2,4})\D*(\d{2})\D*(\d{4}|\d{2})$/U", $value, $matches);
//	print_r($matches);

	if (count($matches) > 0) {
		$day = $matches[1];
		$year = $matches[3];

//		pre(strlen($day) . ":" . strlen($year));
		if (strlen($day) == 4 && strlen($year) == 2) {
			$tmp = $day;
			$day = $year;
			$year = $tmp;
		}
		
		if ($year < 100) {
			$year += ($year > 50) ? 1900 : 2000;
		}

		$date_hash["year"] = $year;
		$date_hash["month"] = $matches[2];
		$date_hash["day"] = $day;
		return $date_hash;

	}

	preg_match ("/(\d{2})\D*(\d{2})/U", $value, $matches);
//	print_r($matches);

	if (count($matches) > 0) {
		$date_hash["year"] = strftime("%Y");
		$date_hash["day"] = $matches[1];
		$date_hash["month"] = $matches[2];
		return $date_hash;

	}

/*
	$matches = array();
	preg_match ("/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/", $value, $matches);
//	print_r($matches);

	if (count($matches) > 0) {
		$date_hash["year"] = $matches[1];
		$date_hash["month"] = $matches[2];
		$date_hash["day"] = $matches[3];
		$date_hash["hour"] = $matches[4];
		$date_hash["minute"] = $matches[5];
		$date_hash["second"] = $matches[6];
	}	
*/

	return $date_hash;
}

function timestamp_date($name, $value, $default, $graycomment = "", $tag_attr = "") {
	global $today_ts_datetime;

//	if ($value == $today_ts_datetime && $default != $today_ts_datetime && $default != "") $value = $default;
//	if ($value != "" && $default != $today_ts_datetime && $default != "") $value = $default;

//	$date_hash = parse_timestamp($value);
	$date_hash = parse_datetime($value);
//	pre($date_hash);

	$ret = select_date($name, $date_hash);
	if ($graycomment != "") $ret .= "&nbsp;&nbsp;&nbsp;&nbsp;<font style='color: " . OPTIONS_COLOR_GRAY . "'>$graycomment</font>";

	return $ret;
}

function timestamp_bdate($name, $value, $default, $graycomment = "", $tag_attr = "") {
	global $today_ts_datetime;
	if ($value == $today_ts_datetime && $default != $today_ts_datetime && $default != "") $value = $default;
	$date_hash = parse_timestamp($value);
	return select_date($name, $date_hash, "birthdate");
}

function timestamp($name, $value, $default, $graycomment = "", $tag_attr = "") {
	global $today_ts_datetime;
	if ($value == $today_ts_datetime && $default != $today_ts_datetime && $default != "") $value = $default;
	$date_hash = parse_timestamp($value);

	$ret = select_date($name, $date_hash, "default", "datetime");
	if ($graycomment != "") $ret .= "&nbsp;&nbsp;&nbsp;&nbsp;<font style='color: " . OPTIONS_COLOR_GRAY . "'>$graycomment</font>";

	return $ret;
}


function datetime_bdate($name, $value, $default, $graycomment = "", $tag_attr = "") {
	global $today_dt_datetime;
	if ($value == $today_dt_datetime && $default != $today_dt_datetime && $default != "") $value = $default;
	$date_hash = parse_datetime($value);
	return select_date($name, $date_hash, "birthdate");
}

function datetime_date($name, $value, $default, $graycomment = "", $tag_attr = "") {
	global $today_dt_datetime;
	if ($value == $today_dt_datetime && $default != $today_dt_datetime && $default != "") $value = $default;
	$date_hash = parse_datetime($value);
//	pre($date_hash);

	$ret = select_date($name, $date_hash, "");
	if ($graycomment != "") $ret .= "&nbsp;&nbsp;&nbsp;&nbsp;<font style='color: " . OPTIONS_COLOR_GRAY . "'>$graycomment</font>";

	return $ret;
}

function datetime($name, $value, $default, $graycomment = "", $tag_attr = "") {
	global $today_dt_datetime;
	if ($value == $today_dt_datetime && $default != $today_dt_datetime && $default != "") $value = $default;
	$date_hash = parse_datetime($value);
	return select_date($name, $date_hash, "default", "datetime");
}

function datehash_2uts_before0518($date_hash) {
	$ret = 0;
	
	if (!isset($date_hash["hour"]) && !isset($date_hash["minute"]) && !isset($date_hash["second"])) {
		$ret = mktime (0, 0, 0
					, $date_hash["month"], $date_hash["day"], $date_hash["year"]);
	} else {
		$ret = mktime ($date_hash["hour"], $date_hash["minute"], $date_hash["second"]
					, $date_hash["month"], $date_hash["day"], $date_hash["year"]);
	}

	return $ret;
}


function datehash_2uts($date_hash, $precision = "full") {
	$ret = 0;
	
	$hour = (isset($date_hash["hour"]) && $precision == "full") ? $date_hash["hour"] : 0;
	$minute = (isset($date_hash["minute"]) && $precision == "full") ? $date_hash["minute"] : 0;
	$second = (isset($date_hash["second"]) && $precision == "full") ? $date_hash["second"] : 0;

	$month = isset($date_hash["month"]) ? $date_hash["month"] : 0;
	$day = isset($date_hash["day"]) ? $date_hash["day"] : 0;
	$year = isset($date_hash["year"]) ? $date_hash["year"] : 0;

	if ($year > 1970) {
		$ret = mktime ($hour, $minute, $second, $month, $day, $year);
	}

	return $ret;
}


function doubledate($name, $value, $default) {
	$ret = "";

//	print_r(getdate($value));

	$options_day = getDay_dd(strftime("%d", $value));
	$options_month = getMonth_dd(strftime("%m", $value));
	$options_year = getYear_dd(strftime("%Y", $value));
	$options_hour = getHour_dd(strftime("%H", $value));
	$options_minute = getMinute_dd(strftime("%M", $value));
	$options_second = getMinute_dd(strftime("%S", $value));

	$ret = <<< EOT
<select name="{$name}_day">$options_day</select>
<select name="{$name}_month">$options_month</select>
<select name="{$name}_year">$options_year</select>
&nbsp;&nbsp;
<select name="{$name}_hour">$options_hour</select> :
<select name="{$name}_minute">$options_minute</select> :
<select name="{$name}_second">$options_second</select>
EOT;
	
	
	return $ret;
}

function layer_open($name, $field_txt, $default = 0, $title = "", $anchor_tdparam = "", $a_name = "") {
	global $layers_total, $layer_opened_nr, $layer_inside, $layer_ident;

	$layer_ident = $field_txt;
	$layer_inside = 1;
	$layers_total++;
	$default = $layers_total;

	$displayed = "none";
//	if ($default == 0) $displayed = $default;
	if ($default == $layer_opened_nr) $displayed = "block";
	
	$ret = <<< EOT
<!-- layer_open start -->
<tr><td colspan=2 $anchor_tdparam >
	<a href="javascript:layer_switch($default)" title="$title" name="$a_name"><img src="img/down.gif" width=10 height=6 border=0 alt="$title"></a>&nbsp;&nbsp;<a href="javascript:layer_switch($default)" title="$title">$field_txt</a>
	</td></tr>
<tr><td colspan=2 style="padding-left: 2">
	<div id="layer_$default" style="display:$displayed">
	<table cellspacing=0 cellpadding=3>
<!-- layer_open end -->
EOT;
	return $ret;
}

function layer_close($name, $field_txt = "_global", $default = 0, $title = "") {
	global $layers_total, $layer_inside, $layer_ident;

	$layer_inside = 0;
	if ($field_txt == "_global") $field_txt = $layer_ident;
	$layer_ident = "";

	$default = $layers_total;
	$ret = <<< EOT
<!-- layer_close start -->
		</table>
		<br>
		<a href="javascript:layer_switch($default)" title="$title"><img src="img/up.gif" width=10 height=6 border=0 alt="$title"></a>&nbsp;&nbsp;<a href="javascript:layer_switch($default)" title="$title">$field_txt</a>
		<br>
	</div></td></tr>
<!-- layer_close end -->
EOT;

	return $ret;
}


function columned_open($name, $field_txt, $default = 0, $title = "", $anchor_tdparam = "") {
	global $columned_inside;
	global $backrow_tpl, $backrow_tpl_backup, $backrow_columned_tpl;

	$columned_inside = 1;
	$backrow_tpl_backup = $backrow_tpl;
	$backrow_tpl = $backrow_columned_tpl;
	
	$ret = <<< EOT
<!--columned_open($name, $field_txt, $default, $title, $anchor_tdparam)-->
<tr>
	<td align=right>$field_txt</td>
	<td>
		<table cellpadding=0 cellspacing=0 border=0>
			<tr><td height=2></td>
			<tr valign=top>
EOT;

	return $ret;
}

function columned_close($name, $field_txt = "_global", $default = 0, $title = "") {
	global $columned_inside;
	global $backrow_tpl, $backrow_tpl_backup, $backrow_columned_tpl;

	$columned_inside = 0;
	$backrow_tpl = $backrow_tpl_backup;

	$ret = <<< EOT
			</tr>
			<tr><td height=5></td>
		</table>
	</td>
</tr>
<!--columned_close($name, $field_txt, $default, $title)-->
EOT;

	return $ret;
}


function columned_columndivider($name, $field_txt = "_global", $default = 0, $title = "") {
	global $columned_inside;
	$ret = "";
	
	if ($columned_inside == 1) $ret = <<< EOT
</td><td>
EOT;
	
	return $ret;
}

function wide_open($name, $field_txt, $default = 0, $title = "") {
	$ret = "<tr><td colspan=2>";
	return $ret;
}

function wide_close($name, $field_txt, $default = 0, $title = "") {
	$ret = "</td></tr>";
	return $ret;
}

function swapdbfield_control($name, $field_txt = "", $graycomment = "") {
	global $in_backoffice_readonly, $entity, $id;
	global $fixed_suffix, $f5_suffix, $swapdbfields_move_page_tpl;
	global $layers_total, $layer_inside;
	$ret = "";

	$hrefbase = hash_by_tpl(array("entity" => $entity, "id" => $id), $swapdbfields_move_page_tpl);
	if ($fixed_suffix != "") $hrefbase .= "&$fixed_suffix";
	if ($layer_inside == 1) $hrefbase .= "&layer_opened_nr=$layers_total";
	$hrefbase .= "&field=$name";
	

	if ($in_backoffice_readonly == 1) {
		$ret = <<< EOT
<table cellpadding=0 cellspacing=2>
<tr>
<td><a href="javascript:alert('$in_backoffice_readonly_msg')"><img src="img/down.gif" width=10 height=6 border=0></a></td>
<td><a href="javascript:alert('$in_backoffice_readonly_msg')"><img src="img/up.gif" width=10 height=6 border=0></a></td>
</tr>
</table>

EOT;

	} else {
		$ret = <<< EOT
<table cellpadding=0 cellspacing=2>
<tr>
<td><a href="$hrefbase&action=down"><img src="img/down.gif" width=10 height=6 border=0></a></td>
<td><a href="$hrefbase&action=up"><img src="img/up.gif" width=10 height=6 border=0></a></td>
</tr>
</table>

EOT;
	}
	
	return $ret;
}

function m2m_hrefs($m2m_entities, $m2m_fixed_fields) {
	global $id, $entity_list;
	global $msg_bo_linked_elements;
	
	$ret = "<nobr>$msg_bo_linked_elements:</nobr>";
	
	$m2m_fixed_suffix = "";
	foreach ($m2m_fixed_fields as $key => $value) {
		if ($m2m_fixed_suffix != "") $m2m_fixed_suffix .= "&";
		$m2m_fixed_suffix .= "m2m_fixed_fields[$key]=$value";
	}

	foreach ($m2m_entities as $key => $value) {
		$ret .= "<li><a href=_m2m.php?m2m_table=$value&entity=$key&$m2m_fixed_suffix>$entity_list[$key]</a>";
	}

	$ret = "<ul>$ret</ul>";
	return $ret;
}

/*
function back_row($input_type_wrapped, $field_txt, $name = "") { 
	global $layer_inside;

	$ret = "";

	if ($name != "") $field_txt = "<label for='$name' class='name'>$field_txt</label>";
	$ret = "<tr><td align=right nowrap><font class='name'>$field_txt</font></td>"
		. "<td width=100%>$input_type_wrapped</td></tr>\n";

	return $ret;
}
*/

function back_row($input_type_wrapped, $field_txt, $name = "", $call_dogs = 1, $wrap_internal = 1) { 
	global $layer_inside;
	global $backrow_tpl, $backrow_bgcolor;
	global $db_values_array;

	$ret = "";

//	if ($name != "") $field_txt = "<label for='$name' class='name'>$field_txt</label>";

	if (isset($db_values_array["id"]) && $wrap_internal == 1) {
		$input_type_wrapped = hash_by_tpl($db_values_array, $input_type_wrapped, "_global", 1, $call_dogs);
	}

	$backrow_hash = array(
		  "SHEET_ROW_BGCOLOR" => $backrow_bgcolor
		, "OBLIGATORY_SIGN" => ""
		, "IT_TXT" => $field_txt
		, "IT_NAME" => $name
		, "IT_WRAPPED" => $input_type_wrapped
		, "IT_GRAYCOMMENT" => ""
		, "IT_GRAYCOMMENT_GRAY" => ""
		);
	$ret = hash_by_tpl($backrow_hash, $backrow_tpl, "_global", 1, $call_dogs);

	return $ret;
}

function ro($name, $value, $default = "") {
	global $db_values_array;

	if ($default != "") $value = $default;
	$ret = $value;
//	$ret = hash_by_tpl ($db_values_array, $ret);
	if ($ret == "") $ret = "[пусто]";

	return $ret;
}


function timestampro($name, $value, $default = "", $graycomment = "") {
	$ret = "";

	$ret = ts2human($value);
	if ($graycomment != "") $ret .= "&nbsp;&nbsp;&nbsp;&nbsp;<font style='color: " . OPTIONS_COLOR_GRAY . "'>$graycomment</font>";

	return $ret;
}

function ahref($name, $value = "_global", $default = "[AHREF TEMPLATE ERROR]") {
	global $db_values_array;
	
	if (!is_array($value)) $value = $db_values_array;
//	print_r($value);

	$ret = hash_by_tpl ($value, $default);
	return $ret;
}

function textfield($name, $value, $default = "", $graycomment = "", $tag_attr = "") {
	global $input_size, $layer_inside;
	
	$tf_size = $input_size["text"];
	if ($layer_inside == 1) $tf_size = $input_size["text_insidelayer"];

//	if ($value == "") $value = $default;
	$value = htmlspecialchars($value, ENT_QUOTES);
	$ret = "<input type=text class='text w100' size=$tf_size name='$name' value=\"$value\" $tag_attr>";
	if ($graycomment != "") $ret .= "<br><font style='color: " . OPTIONS_COLOR_GRAY . "'>$graycomment</font>";

	return $ret;
}

function textfieldro($name, $value, $default = "", $db_values_array_ = "_global:db_values_array", $tag_attr = "") {
//	global $input_size, $layer_inside, $db_values_array;
	global $input_size, $layer_inside;
	
	if ($db_values_array_ == "_global:db_values_array" && isset($GLOBALS["db_values_array"])) {
		$db_values_array = absorb_variable($db_values_array_);
	} else {
		$db_values_array = array();
	}
//	pre($db_values_array);
	
	$tf_size = $input_size["text"];
	if ($layer_inside == 1) $tf_size = $input_size["text_insidelayer"];

	if ($value == "") $value = $default;
	$value = htmlspecialchars($value, ENT_QUOTES);

	$value = hash_by_tpl ($db_values_array, $value);
	if ($value == "") $value = "[пусто]";

//	$ret = "<input type=text class='text' size=$tf_size name='$name' value=\"$value\" disabled>";
//	$ret = "<input type=text class='text' size=$tf_size value=\"$value\" disabled $tag_attr>";
	$ret = "<input type=text class='text w100' size=$tf_size value=\"$value\" title=\"$value\" disabled $tag_attr>";
	return $ret;
}


function number($name, $value = 0, $default = "", $graycomment = "", $tag_attr = "") {
	global $input_size, $layer_inside;
	
	$tf_size = $input_size["number"];
	if ($layer_inside == 1) $tf_size = $input_size["number_insidelayer"];

	if ($value == "") $value = $default;
	$value = htmlspecialchars($value, ENT_QUOTES);
	$ret = "<input type=text size=$tf_size name='$name' value=\"$value\" $tag_attr>";
	if ($graycomment != "") $ret .= "&nbsp;&nbsp;&nbsp;&nbsp;<font style='color: " . OPTIONS_COLOR_GRAY . "'>$graycomment</font>";

//	trying to number had @src@ capability
//	$ret = hash_by_tpl ($value, $default);

	return $ret;
}

function numberro($name, $value = 0, $default = "", $graycomment = "", $tag_attr = "") {
	global $input_size, $layer_inside;
	
	$tf_size = $input_size["number"];
	if ($layer_inside == 1) $tf_size = $input_size["number_insidelayer"];

	if ($value == "") $value = $default;
	$value = htmlspecialchars($value, ENT_QUOTES);
	$ret = "<input type=text size=$tf_size name='$name' value=\"$value\" disabled $tag_attr>";
	if ($graycomment != "") $ret .= "&nbsp;&nbsp;&nbsp;&nbsp;<font style='color: " . OPTIONS_COLOR_GRAY . "'>$graycomment</font>";

	return $ret;
}


function textarea($name, $value, $default = "", $rows = "", $graycomment = "", $tag_attr = "") {
	global $input_size, $layer_inside;

	if ($rows == "") $rows = 6;
	
	$ta_size = $input_size["text"];
	if ($layer_inside == 1) $ta_size = $input_size["text_insidelayer"];

	$value = htmlspecialchars($value, ENT_QUOTES);
	$ret = "<textarea class='text w100' rows=$rows cols=$ta_size name='$name' $tag_attr>$value</textarea>";
	if ($graycomment != "") $ret .= "<br><font style='color: " . OPTIONS_COLOR_GRAY . "'>$graycomment</font>";

	return $ret;
}

function textarea_24($name, $value, $default = "", $graycomment = "", $tag_attr = "") {
	return textarea($name, $value, $default, 24, $graycomment, $tag_attr);
}

function textarea_18($name, $value, $default = "", $graycomment = "", $tag_attr = "") {
	return textarea($name, $value, $default, 18, $graycomment, $tag_attr);
}

function textarea_10($name, $value, $default = "", $graycomment = "", $tag_attr = "") {
	return textarea($name, $value, $default, 10, $graycomment, $tag_attr);
}

function textarea_3($name, $value, $default = "", $graycomment = "", $tag_attr = "") {
	return textarea($name, $value, $default, 3, $graycomment, $tag_attr);
}

function checkbox($name, $value, $default = "", $graycomment = "", $tag_attr = "") {
	if ($value == "") $value = $default;
	if ($value == 1) $value = "checked";
	
	$ret = "<input type=checkbox name='$name' id='$name' $value $tag_attr>";
	if ($graycomment != "") $ret .= "&nbsp;&nbsp;&nbsp;&nbsp;<font style='color: " . OPTIONS_COLOR_GRAY . "'>$graycomment</font>";
	return $ret;
}


function selectro($name, $options, $tag_attr = "") {
	$ret = "<select name='$name' id='$name' $tag_attr disabled>$options</select>";
	return $ret;
}

function select($name, $options, $tag_attr = "") {
	$ret = "<select name='$name' id='$name' $tag_attr>$options</select>";
	return $ret;
}

function select_array($name, $default = "_global", $opt_array, $tag_attr = "") {
	$ret = "";

	if ($default == "_global") $default = get_number($name);

	$options = options_array($opt_array, $default);
	$ret = "<select name='$name' id='$name' $tag_attr>$options</select>";
	return $ret;
}


function select_hash($name, $default = "_global", $opt_hash, $tag_attr = "") {
	$ret = "";

	if ($default == "_global") $default = get_string($name);

	$options = options_hash($opt_hash, $default);
	$ret = "\n<select name='$name' id='$name' $tag_attr>$options\n</select>\n";
	return $ret;
}

/*
function select_hash($name, $opt_hash, $default) {
	$ret = "";
	$options = options_hash($opt_hash, $default);
	$ret = "<select name='$name'>$options</select>";
	return $ret;
}

function select_hash($name, $value, $opt_hash, $tag_attr = "") {
	$ret = "";
	$options = options_hash($opt_hash, $value);
	$ret = "<select name='$name'>$options</select>";
	return $ret;
}
*/

function select_byear($name, $value, $default) {
	$options = "";
//	if ($value = 1) $value = $default;
	$options = options_byear($value);
	return select($name, $options);
}

function options_byear($value, $add_firstoption_zero = 1) {
	$options = "";

	$fromyear = 1950;

	$today = getdate();
	$tillyear = $today['year'] + 1; 

	$was_selected = 0;
	for ($i = $tillyear; $i >= $fromyear; $i--) {
		$options .= "<option value='$i'";
		if ($i == (int) $value) {
			$options .= " selected";
			$was_selected = 1;
		}
		$options .= ">$i</option>";
	}

	$selected = ($was_selected == 0) ? "selected" : "";
	if ($add_firstoption_zero == 1) {
		$options = "<option value='0' $selected>&nbsp;</option>" . $options;
	}

	return $options;
}


function select_bmonth($name, $value, $default) {
	$options = "";

//	if ($value = 1) $value = $default;
	$options = options_bmonth($value);
	return select($name, $options);
}

function options_bmonth($value) {
	global $months;
	$options = "";

	for ($i=1; $i<count($months); $i++) {
		$options .= "<option value=$i";
		if ($i == (int) $value) $options .= " selected";
		$options .= ">$months[$i]</option>";
	}

	return $options;
}


function select_bday($name, $value, $default) {
	$options = "";

//	if ($value = 1) $value = $default;
	$options = options_bday($value);
	return select($name, $options);
}

function options_bday($value) {
	$options = "";

	for ($i = 1; $i <= 31 ; $i++) {
		$options .= "<option value='$i'";
		if ($i == (int) $value) $options .= " selected";
		$options .= ">$i</option>";
	}

	return $options;
}

function format_fsize ($size) {
	$pre = "";
	
	if ($size > 1024) {
		$size /= 1024;
		$size = intval ($size * 100) / 100;
		$pre = "K";
	}

	if ($size > 1024) {
		$size /= 1024;
		$size = intval ($size * 100) / 100;
		$pre = "M";
	}

	$ret = "$size {$pre}";
	return $ret;
}

function name_size($value, $abs_path = "_global", $print_value = 1) {
	global $input_size;
	global $upload_abspath, $entity, $id, $entity_path;
	global $msg_bo_img_file_size_bytes, $msg_bo_img_file_lost;

	$ret = "";
	$pre = "";
	$size = 0;

	if ($abs_path == "_global") $abs_path = $upload_abspath . "$entity/$id/";

	$file_path = $abs_path . $value;
//	echo "[$file_path]";

	if (file_exists($file_path)) {
		$size = filesize($file_path);
		$size_formatted = format_fsize($size);
		$ret = "{$size_formatted}$msg_bo_img_file_size_bytes";
	} else {
		$ret = "$msg_bo_img_file_lost";
	}
	
	if ($print_value == 1 && $ret != "") $ret = "$value $ret";
	
	return $ret;
}

function upload($name, $value) {
	global $input_size, $layer_inside;
	global $entity, $id, $ident_new, $msg_bo_file_delete_existing, $upload_relpath;

	$tf_size = $input_size["file"];
	if ($layer_inside == 1) $tf_size = $input_size["file_insidelayer"];

	$ret = "<input type=file class='file w100' size=$tf_size name='$name'>";

	if ($value != "" && $value != $ident_new) {
		$name_size = name_size($value);

		$ret .=  "<br><input type=checkbox name=del_$name id=del_$name>"
			. "<label for='del_$name'>$msg_bo_file_delete_existing</label> [$name_size]"
			. "&nbsp;&nbsp;&nbsp;<a href='$upload_relpath/$entity/$id/$value' target=_blank>открыть</a>";
	}

	return $ret;
}

function image($name, $value) {
	global $input_size, $layer_inside;
	global $upload_abspath, $upload_relpath, $entity, $id, $ident_new;
	global $msg_bo_img_delete_existing, $msg_bo_img_popup;

	$tf_size = $input_size["file"];
	if ($layer_inside == 1) $tf_size = $input_size["file_insidelayer"];

	$ret = "<input type=file class='file w100' size=$tf_size name='$name'>";
	$ctrl_html = "";

	if ($value != "" && $value != $ident_new) {
		$name_size = name_size($value);

		$ctrl_html = <<< EOT
<br><input type=checkbox name=del_$name id=del_$name>
<label for="del_$name">$msg_bo_img_delete_existing</label> [$name_size]
&nbsp;&nbsp;&nbsp;<a href="$upload_relpath/$entity/$id/$value">$msg_bo_img_popup</a>
EOT;

		$ctrl_html = <<< EOT
<br><input type=checkbox name="del_$name" id="del_$name">
<label for="del_$name">$msg_bo_img_delete_existing</label> [$name_size]
&nbsp;&nbsp;&nbsp;<a href="javascript:popup_entityimg('$entity', $id, '$name', 80, 60)">$msg_bo_img_popup</a>
EOT;
		$img_relpath = "$entity/$id/$value";

		if (is_file($upload_abspath . $img_relpath)) {
			$img_size = getimagesize($upload_abspath . $img_relpath);
			$width = $img_size[0];
			$height = $img_size[1];
	
			$ctrl_html = <<< EOT
<br><input type=checkbox name="del_$name" id="del_$name">
<label for="del_$name">$msg_bo_img_delete_existing</label> [$name_size]
&nbsp;&nbsp;&nbsp;<a href="javascript:popup_entityimg('$entity', $id, '$name', $width, $height)">$msg_bo_img_popup</a>
EOT;
		}
	}
	
	$ret .= $ctrl_html;

	return $ret;
}

function imagero($name, $value) {
	global $input_size, $layer_inside;
	global $upload_abspath, $upload_relpath, $entity, $id, $ident_new;
	global $msg_bo_img_not_uploaded, $msg_bo_img_popup;

	$tf_size = $input_size["file"];
	if ($layer_inside == 1) $tf_size = $input_size["file_insidelayer"];

	$ret = "$msg_bo_img_not_uploaded";
	$ctrl_html = "";

	if ($value != "" && $value != $ident_new) {
		$name_size = name_size($value);

		$ctrl_html = <<< EOT
[$name_size]&nbsp;&nbsp;&nbsp;<a href="$upload_relpath/$entity/$id/$value">$msg_bo_img_popup</a>
EOT;

		$img_relpath = "$entity/$id/$value";

		if (is_file($upload_abspath . $img_relpath)) {
			$img_size = getimagesize($upload_abspath . $img_relpath);
			$width = $img_size[0];
			$height = $img_size[1];
	
			$ctrl_html = <<< EOT
[$name_size]&nbsp;&nbsp;&nbsp;<a href="javascript:popup_entityimg('$entity', $id, '$name', $width, $height)">$msg_bo_img_popup</a>
EOT;

		$ctrl_html = <<< EOT
[{$width}х{$height}]<br>[$name_size]<br><img src="$upload_relpath/$entity/$id/$value" style="border:1 solid gray"><br><br>
EOT;
		}

		$ret = $ctrl_html;
	}
	
	return $ret;
}

function image_random($name, $value) {
	global $input_size;
	global $upload_abspath, $upload_relpath, $ident_new;
	global $msg_bo_img_delete_existing, $msg_bo_img_popup;

	$ret = "<input type=file class='file w100' size=" . $input_size["file"] . " name='$name'>";

	if ($value != "" && $value != $ident_new) {
		$name_size = name_size($value, $upload_abspath . "random/image/");

		$ret .=  "<br><input type=checkbox name=del_$name id=del_$name>"
			. "<label for='del_$name'>$msg_bo_img_delete_existing</label> [$name_size]"
			. "&nbsp;&nbsp;&nbsp;<a href='$upload_relpath/random/image/$value'>$msg_bo_img_popup</a>";
	}

	return $ret;
}

function notifier($name, $value, $depend_on) {
	global $input_size;
	global $entity, $id, $ident_new, $notifier_to;
	global $msg_bo_notification_send;

	$disabled = "disabled";
	$checked = "";

	$row = select_entity_row(array("id" => $id), $entity);
	$depend_value = $row[$depend_on];

	if ($depend_value != "" && $depend_value != $ident_new && $depend_value != $value) {
		$disabled = "";
	}
	
	$notifier_to_ = "ERROR";
	if ($value != "" && $value == $depend_value) {
		$disabled = "disabled";
		$checked = "checked=on";
		$notifier_to_name = $name . "_to";
		$notifier_to_ = $row[$notifier_to_name];
	} else {
		$notifier_to_ = $notifier_to;
	}
	
	$ret = "<input type=text class='text w100' size=" . $input_size["text"] . " name=notifier_to_$name value='$notifier_to_' $disabled><br>"
			. "<input type=hidden name=notify_$name value='$depend_value'>"
			. "<input type=checkbox name=cb_notify_$name id=cb_notify_$name $checked $disabled>"
			. "<label for='cb_notify_$name'>$msg_bo_notification_send</label>";

	return $ret;
}


function select_curyear($name, $value) {
	$options = "";
	if ($value == 0) $value = date("Y");
//	echo "[$value]";

	for ($i = 1990; $i <= 2010; $i++) {
		$options .= "<option value='$i'";
		if ($i == $value) $options .= " selected";
		$options .= ">$i</option>";
	}

	return select($name, $options);
}

function select_curmonth($name, $value) {
	if ($value == 0) $value = date("n");
//	echo "[$value]";
	return select_bmonth($name, $value);
}


function select_curday($name, $value) {
	if ($value == 0) $value = date("d");
//	echo "[$value]";
	return select_bday($name, $value);
}


function hidden($name, $value, $default) {
	if ($value == "") $value = $default;

	$ret = "<input type=hidden name=$name value='$value'>\n";
		
	return $ret;
}


function options_array($opt_array, $default) {
	$options = "";

	for ($i = 0; $i < count($opt_array); $i++) {
		$value = $opt_array[$i];
		$options .= "<option value='$value'";
		if ($i == $default) $options .= " selected";
		if ($value == $default) $options .= " selected";
		if ($value == 0) $value = "";
		$options .= ">$value</option>";
	}

	return $options;
}

function options_hash($opt_hash, $default) {
	$ret = "";

	$was_selected = 0;
	foreach ($opt_hash as $id => $ident) {
		$selected = "";
		if ($id == $default) {
			$selected = "selected";
			$was_selected = 1;
		}

		$published = 1;
		$published_style = ($published == 1) ? "" : " style='color: #AAAAAA'";

		$ret .= "\n<option value='$id' $published_style $selected>$ident</option>";
	}

	if ($was_selected == 0) $ret = "\n<option value='0' selected>&nbsp;</option>" . $ret;

	return $ret;
}

function options_numseq($from, $till, $default) {
	$options = "";

	for ($i = $from; $i <= $till; $i++) {
		$options .= "<option value='$i'";
		if ($i == $default) $options .= " selected";
		if ($i == 0) $value = "";
		$options .= ">$i</option>";
	}

	return $options;
}



function create_entity_path($entity, $id) {
	global $upload_abspath, $entity_root_path, $entity_path;

	if (!is_dir($entity_path)) {
		if (!is_dir($entity_root_path)) mkdir($entity_root_path, 0777);
		mkdir($entity_path, 0777);
	}
}


?>