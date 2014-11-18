<?

if ($in_silent_mode == 0) {
	echo "<!-- BEGIN _fixed.php included from _updown.php or _entity_edit.php -->\n";
}


if (!isset($fixed_getfirstfromdb)) {
	$fixed_getfirstfromdb = in_array($entity, $fixed_getfirstfromdb_array) ? 1 : 0;
}

$fixed_hash = array();

$o2mfixed_hash = array();
$m2mfixed_hash = array();
$m2mfixed_dependtable_first = "";

foreach($fixed_fields as $fixed_field) {
	$fixed_value = get_number($fixed_field);
	if ($id > 0 && $fixed_value == 0 && $fixed_getfirstfromdb == 1) {
		$fixed_value = select_field($fixed_field);
	}
	
//	if (isset($entity_m2mfixed_list[$fixed_field])) $fixed_value = 0;

	$m2m_dependtable = get_m2m_dependtable($entity, $fixed_field);
//	if ($m2m_dependtable != "") $fixed_value = 0;

	if ($fixed_value == "") {
		switch($fixed_field) {
			case "parent_id":
				$fixed_value = 1;
				//needed by select_table_tree_root (pgroup-edit.php) to set to 'root' if no other
				$_REQUEST[$fixed_field] = $fixed_value;
				break;

			case "family":
			case "family_serno":
				break;				// will reuse __fixed again when defined
				
			default:
				if ($fixed_getfirstfromdb == 1) {
					$visible_mask = array();
					if (entity_has_deleted_field($fixed_field)) $visible_mask = array("deleted" => 0);
					$fixed_value = select_first_published("id", $visible_mask, $fixed_field);

					if ($in_silent_mode == 0) {
						echo "<!-- fixed_getfirstfromdb: [$fixed_field][$fixed_getfirstfromdb] got[$fixed_value] -->\n";
					}

				}
				break;
		}
	}

	if ($fixed_value == 0 && $fixed_getfirstfromdb == 0) continue;
	if ($fixed_field == "parent_id" && $q != "") continue;

	if ($in_silent_mode == 0) {
		echo "<!-- fixed: [$fixed_field][$fixed_value] -->\n";
	}

//	$$fixed_field = $fixed_value;
//	where it's necessary?? in _sumbenu: submenu_by_options()
//	$_REQUEST[$fixed_field] = $fixed_value;

	$fixed_hash[$fixed_field] = $fixed_value;


//	if (isset($entity_m2mfixed_list[$fixed_field])) {
	if ($m2m_dependtable != "") {
		$m2mfixed_hash[$fixed_field] = $fixed_value;
		$m2mfixed_dependtable_first = "m2m_$fixed_field";
//		pre("m2mfixed_dependtable_first = $m2mfixed_dependtable_first");
	} else {
		$o2mfixed_hash[$fixed_field] = $fixed_value;
	}
}

//pre($fixed_hash);
//pre($o2mfixed_hash);
//pre($m2mfixed_hash);

//$fixed_cond = sqlcond_fromhash($fixed_hash, "", " and ");

$o2mfixed_cond = sqlcond_fromhash($o2mfixed_hash, "e", " and ");
$m2mfixed_cond = sqlcond_fromhash($m2mfixed_hash, "$m2mfixed_dependtable_first", " and ");

$fixed_suffix = hrefsuffix_fromhash($fixed_hash);
$fixed_hiddens = hidden_fromhash($fixed_hash);


/* implemented in _list.php and needed only there
if ($fixed_suffix != "") $fixed_suffix = "&". $fixed_suffix;
if ($pg > 0) {
	if ($fixed_suffix != "") $fixed_suffix .= "&";
	$fixed_suffix .= "pg=$pg";
}
if ($pg > 0) {
	$fixed_hiddens .= "<input type='hidden' name='pg' value='$pg'>\n";
}
*/

//pre("fixed_cond=[$fixed_cond]");
//pre("o2mfixed_cond=[$o2mfixed_cond]");
//pre("m2mfixed_cond=[$m2mfixed_cond]");

//	echo $fixed_suffix;
//	echo $fixed_hiddens;


if ($in_silent_mode == 0) {
	echo "<!-- END _fixed.php -->\n";
}


if (!isset($f5_suffix)) $f5_suffix = "";
$f5_near_save = "<a href='" . $_SERVER["SCRIPT_NAME"];
if ($fixed_suffix != "") $f5_near_save .= "?" . $fixed_suffix;
if ($f5_suffix != "") $f5_near_save .= "&" . $f5_suffix;
$f5_near_save .= "' title='msg_bo_F5_title'>F5</a>&nbsp;&nbsp;";




?>
