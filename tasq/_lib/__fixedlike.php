<!-- BEGIN _fixedlike.php -->
<?

if (isset($fixedlike_fields)) {
	foreach($fixedlike_fields as $fixed_field) {
		if (!isset($$fixed_field)) {
			$fixed_field = get_string($fixed_field);
		} else {
			$fixed_value = $$fixed_field;
		}

		if ($fixed_value != "") {
			$fixed_cond .= "and ";
			$fixed_cond .= "$fixed_field like '$fixed_value%' ";
		}
	}
//	echo $fixed_cond;

//	$fixed_suffix = "&";
	foreach($fixedlike_fields as $fixed_field) {
		$fixed_value = $$fixed_field;
		if ($fixed_value != "") {
//			if ($fixed_suffix != "") $fixed_suffix .= "&";
			$fixed_suffix .= "&";
			$fixed_suffix .= "$fixed_field=$fixed_value";
		}
	}

	if ($pg > 0) {
		if ($fixed_suffix != "") $fixed_suffix .= "&";
		$fixed_suffix .= "pg=$pg";
	}
//	echo $fixed_suffix;

//	$fixed_hiddens = "";
	foreach($fixedlike_fields as $fixed_field) {
		$fixed_value = $$fixed_field;
		$fixed_hiddens .= "<input type='hidden' name='$fixed_field' value='$fixed_value'>\n";
	}

	if ($pg > 0) {
		$fixed_hiddens .= "<input type='hidden' name='pg' value='$pg'>\n";
	}
//	echo $fixed_hiddens;
}
?>
<!-- END _fixedlike.php -->