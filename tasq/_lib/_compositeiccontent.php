<?

function multicompositeiccontent_updateall($m2m_table, $fixed_hash, $absorbing_fixedhash, $icwhose_id) {
	global $sheet_present_in_db, $sheet_had_errors, $obligatory_field;

	$absorbed_fixedhash = absorb_fixedhash($absorbing_fixedhash);
//	pre("multicompositeiccontent_updateall");
//	pre($absorbed_fixedhash);

	$query = "select ic.id, ic.ident, ic.ictype, ic.icdict, ic.param1, ic.param2, ic.$obligatory_field as obligatory"
		. " , t.hashkey as ictype_hashkey"
		. " from ic ic"
		. " inner join ictype t on ic.ictype=t.id"
		. " where ic.deleted=0 and ic.published=1 and t.published=1 and t.deleted=0"
		. " and ic.icwhose=" . $icwhose_id
		. " order by ic." . get_entity_orderfield("ic");
	$ic_rows = select_queryarray($query);
//	pre($ic_rows);

	$sheet_present_in_db = 0;
	$sheet_present_in_db_fixedhash = array_merge($fixed_hash, $absorbed_fixedhash);
	foreach ($ic_rows as $ic_row) {
		if ($ic_row["ictype_hashkey"] == "AHREF") continue;

		$sheet_present_in_db_fixedhash["ic"] = $ic_row["id"];
//		pre($sheet_present_in_db_fixedhash);
		$iccontent_indb_count = select_field("count(id)", $sheet_present_in_db_fixedhash, $m2m_table);
		if ($iccontent_indb_count > 0) $sheet_present_in_db++;
	}
//	echo $sheet_present_in_db;

	foreach ($ic_rows as $ic) {
//		pre($ic);
		$ic_output = "";
		$ictype_hashkey = $ic["ictype_hashkey"];

		$composite = $fixed_hash;
		$composite["ic"] = $ic["id"];

		$it_name = "mcicc_";
//		$it_name .= iccomposite_itname(array_keys($composite)) . ":";
		$it_name .= iccomposite_itvalue(array_keys($composite), $composite);

		multicompositeiccontent_update($m2m_table, $composite, $ic, $it_name, $absorbed_fixedhash);

		$it_value = select_field("iccontent", array_merge($composite, $absorbed_fixedhash), $m2m_table);
		$sheet_row_had_errors = 0;
		switch($ictype_hashkey) {
			case "AHREF":
				break;
			
			case "SELECT":
			case "ICSELECT":
			case "ICRADIO":
				if ($ic["obligatory"] == 1 && $it_value == 0) $sheet_row_had_errors = 1;

				break;
				
			case "ICMULTISELECT":
			case "ICMULTICHECKBOX":
				$icdict = $ic["icdict"];
				$icdict_hashkey = select_field("hashkey", array("id" => $icdict), "icdict");
				
				switch ($icdict_hashkey) {
					case "PGROUPTREE_SUPPLIERCLICKABLE":
//						pre ("multicompositeiccontent_updateall: checking obligatory[$obligatory_field] of IC.ICTYPE=[$ictype_hashkey]");
						break;
				
					case "PGROUPTREE_PGROUPCLICKABLE":
						pre ("multicompositeiccontent_updateall: checking obligatory[$obligatory_field] of IC.ICTYPE=[$ictype_hashkey]");
						break;
				
					default:
						$it_value_dbrawarray = select_fieldarray("iccontent"
							, array_merge($composite, $absorbed_fixedhash, array("deleted" => 0))
							, $m2m_table);

						$it_value_dbarray = array();
						foreach ($it_value_dbrawarray as $it_value_dbvalue) $it_value_dbarray[] = $it_value_dbvalue;
//						pre($it_value_dbarray);
						if ($ic["obligatory"] == 1 && count($it_value_dbarray) == 0) $sheet_row_had_errors = 1;
				}

				break;

			default:
				if ($ic["obligatory"] == 1 && $it_value == "") $sheet_row_had_errors = 1;
		}

		if ($sheet_row_had_errors == 1 && $sheet_present_in_db > 0) {
			$sheet_had_errors = 1;
		}
	}
}

function multicompositeiccontentro($m2m_table, $fixed_hash, $absorbing_fixedhash, $icwhose_id) {
	return multicompositeiccontent($m2m_table, $fixed_hash, $absorbing_fixedhash, $icwhose_id, 1);
}


function multicompositeiccontent($m2m_table, $fixed_hash, $absorbing_fixedhash, $icwhose_id, $read_only = 0, $ignore_jsv_finally = 0, $form_name = "form_edit") {
	global $mode, $debug_query, $debug_session;
	global $sheet_present_in_db, $sheet_had_errors, $backrow_tpl, $in_backoffice;
	global $backrow_not_obligatory_sign, $backrow_obligatory_sign, $backrow_obligatory_nojs_sign, $backrow_obligatory_jsonly_sign, $obligatory_field;
	global $published_opthash, $published_opthash_standard, $published_opthash_colorized;

	global $mcicc_copyform;
	$mcicc_copyform_tpl = "<input type='hidden' name='#IT_NAME#' value='#IT_VALUE#'>\n";

	$ret = "";
	
	$absorbed_fixedhash = absorb_fixedhash($absorbing_fixedhash);
//	pre("multicompositeiccontent");

//	pre($fixed_hash);
//	pre($absorbed_fixedhash);
	
/*
	$ic_has_jsv = entity_has_field("ic", "jsvalidator");

	$query = "select ic.id, ic.ident, ic.ictype, ic.icdict, ic.param1, ic.param2, ic.$obligatory_field as obligatory, ic.graycomment"
		. " , t.hashkey as ictype_hashkey"
		. " from ic ic, ictype t"
		. " where ic.deleted=0 and ic.published=1 and t.published=1 and t.deleted=0"
		. " and ic.ictype=t.id"
		. " and ic.icwhose=" . $icwhose_id
		. " order by ic." . get_entity_orderfield("ic");

	if ($ic_has_jsv == 1) {
		$query = "select ic.id, ic.ident, ic.ictype, ic.icdict, ic.param1, ic.param2, ic.$obligatory_field as obligatory, ic.graycomment"
*/

		$published_field = ($in_backoffice == 1) ? "published_bo" : "published";
		$query = "select ic.*"
			. " , t.hashkey as ictype_hashkey, jsv.hashkey as jsv_hashkey"
			. " , icw.jsv_debug"
			. " , m2m.iccontent"
			. " from ic ic"
			. " inner join ictype t on ic.ictype=t.id"
			. " left outer join jsvalidator jsv on ic.jsvalidator=jsv.id"
			. " inner join icwhose icw on icw.id=" . $icwhose_id


// hoping nightmare will stop
//function sqlcond_fromhash($fixed_hash, $col_prefix = "", $startfrom = ""
//, $conjunction = "and",  $table_prefix = "", $addslashes = 1)

			. " left outer join $m2m_table m2m on m2m.ic=ic.id "
				. sqlcond_fromhash(array_merge($fixed_hash, $absorbed_fixedhash), "m2m", "and ")


			. " where ic.deleted=0 and ic.$published_field=1 and t.published=1 and t.deleted=0"
			. " and ic.icwhose=" . $icwhose_id
			. " group by ic.id"
			. " order by ic." . get_entity_orderfield("ic");
//	}


	$ic_rows = select_queryarray($query);
//	pre($ic_rows);


	$sheet_present_in_db = 0;
	$sheet_present_in_db_fixedhash = array_merge($fixed_hash, $absorbed_fixedhash);
	foreach ($ic_rows as $ic_row) {
		if ($ic_row["ictype_hashkey"] == "AHREF") continue;

		$sheet_present_in_db_fixedhash["ic"] = $ic_row["id"];
//		pre($sheet_present_in_db_fixedhash);
		$iccontent_indb_count = ($m2m_table != "m2m_nosave")
			? select_field("count(id)", $sheet_present_in_db_fixedhash, $m2m_table)
			: 0
			;

		if ($iccontent_indb_count > 0) $sheet_present_in_db++;
	}
//	echo $sheet_present_in_db;

	

	foreach ($ic_rows as $ic) {
		$ic["obligatory"] = $ic[$obligatory_field];		//$ic["obligatory_bo"] for backoffice and $ic["obligatory"] for face
//		pre($ic);
		$ic_output = "";
		$ictype_hashkey = $ic["ictype_hashkey"];
		$jsv_hashkey = $ic["jsv_hashkey"];

		$composite = $fixed_hash;
		$composite["ic"] = $ic["id"];

		$it_name = "mcicc_";
//		$it_name .= iccomposite_itname(array_keys($composite)) . ":";
		$it_name .= iccomposite_itvalue(array_keys($composite), $composite);

// updating
//		if ($mode == "update") {
//			multicompositeiccontent_update($m2m_table, $composite, $ic, $it_name, $absorbed_fixedhash);
//		}

// displaying
/*		$tpl = <<< EOT
<tr bgcolor="#SHEET_ROW_BGCOLOR#">
	<td align="right" nowrap>#OBLIGATORY_HTML#<font class="name"><label for="#IT_NAME#" class="name">#IT_TXT#</label></font></td>
	<td width="100%">#IT_WRAPPED#</td>
</tr>
EOT;

		$tpl = <<< EOT
<tr bgcolor="#SHEET_ROW_BGCOLOR#">
	<td align=right nowrap>#OBLIGATORY_SIGN#<font class="name"><label for="#IT_NAME#" class="name">#IT_TXT#</label></font></td>
	<td width=100%>#IT_WRAPPED#</td>
</tr>

EOT;
*/
		$tpl = $backrow_tpl;

//		$it_name .= "[]";
		if (isset($_SESSION["mcicc_hash"]) && isset($_SESSION["mcicc_hash"][$it_name])) {
			$it_value = $_SESSION["mcicc_hash"][$it_name][0];
			if ($debug_session) pre("_SESSION[mcicc_hash][$it_name]=[" . pr($it_value) . "]");
		} else {
			$debug_query = 0;
			if ($m2m_table == "m2m_nosave") {
				$it_value = isset($_REQUEST[$it_name]) ? $_REQUEST[$it_name][0] : "";
			} else {
//				$debug_query = 1;
// nightmare before left join
				$it_value = select_field("iccontent", array_merge($composite, $absorbed_fixedhash), $m2m_table);
//				$it_value = $ic["iccontent"];
//				$debug_query = 0;
			}


			$debug_query = 0;
		}
		if ($mode == "copy") $it_value = get_string($it_name);

		$sheet_row_had_errors = 0;
		$ic_output = "";
		switch($ictype_hashkey) {
			case "RAWHTML":
				$ic_output = $ic["param1"];
				break;
			
			case "AHREF":
				$ic_output = ahref($it_name, $ic, $ic["param1"]);
				break;
			
			case "SELECT":
				if ($mode == "copy") $it_value = get_arrayfirst($it_name);
				$it_name .= "[]";

				if ($it_value == "") $it_value = select_first("id", array("icdict" => $ic["icdict"]), "icdictcontent");
				$query = "select id, ident, published"
						. " from icdictcontent"
						. " where icdict=" . $ic["icdict"]
						. " order by manorder";

				$published_opthash_bak = $published_opthash;
				$published_opthash = $published_opthash_standard;

				if ($read_only == 0) {
					$ic_output = select_query($it_name, $it_value, $query);
				} else {
					$ic_output = select_field("ident", array("id" => $it_value), "icdictcontent");
				}

				$published_opthash = $published_opthash_bak;

				if ($ic["obligatory"] == 1 && $it_value == 0) $sheet_row_had_errors = 1;
				
				break;

			case "ICSELECT":
				if ($mode == "copy") $it_value = get_arrayfirst($it_name);
				$it_name .= "[]";

				$query = "select id, ident, published"
						. " from icdictcontent"
						. " where icdict=" . $ic["icdict"] . " and published=1"
						. " order by manorder";

				$published_opthash_bak = $published_opthash;
				$published_opthash = $published_opthash_standard;

				if ($read_only == 0) {
					$ic_output = select_query($it_name, $it_value, $query);
				} else {
					$ic_output = select_field("ident", array("id" => $it_value), "icdictcontent");
				}

				$published_opthash = $published_opthash_bak;

				if ($ic["obligatory"] == 1 && $it_value == 0) $sheet_row_had_errors = 1;

				break;

/*
			case "ICRADIO":
//				pre($ictype_hashkey);

				if ($mode == "copy") $it_value = get_arrayfirst($it_name);

				$ic_output = icradio($ic["icdict"], $it_value, $it_name, $read_only);

//				$ic_output = icmulti($ic["icdict"], $it_value, "icadio", $it_name, $read_only, $it_iccontent_tf1_dbarray, $icmulti_colcnt);

				if ($ic["obligatory"] == 1 && $it_value == 0) $sheet_row_had_errors = 1;

				break;
*/

			case "ICMULTISELECT":
			case "ICMULTICHECKBOX":
			case "ICRADIO":
//				$debug_query = 1;

				if (isset($_SESSION["mcicc_hash"]) && isset($_SESSION["mcicc_hash"][$it_name])) {
					$it_value_dbarray = $_SESSION["mcicc_hash"][$it_name];
					if ($debug_session) pre("_SESSION[mcicc_hash][$it_name]=[" . pr($it_value) . "]");
				} else {
					$debug_query = 0;
					$it_value_dbarray = ($m2m_table != "m2m_nosave")
						? select_fieldarray("iccontent"
								, array_merge($composite, $absorbed_fixedhash, array("deleted" => 0))
								, $m2m_table)
						: array()
						;
					$debug_query = 0;
				}

				if ($mode == "copy") $it_value_dbarray = get_array($it_name);
//				echo "$ictype_hashkey: $it_name " . pr($it_value_dbarray) . "<br>";

//				pre($it_value_dbarray);

				$it_iccontent_tf1_dbarray = array();

				foreach ($it_value_dbarray as $it_value_item) {
					$itname_tf1 = $it_name . "_" . $it_value_item . "_tf1";
//					$itname_tf1 = $it_name . "_" . $it_value_item . "_" . $ic["icdict"] . "_tf1";

					if (isset($_SESSION["mcicc_hash"][$itname_tf1])) {
						$it_tf1_value = $_SESSION["mcicc_hash"][$itname_tf1];
						if ($debug_session) pre("_SESSION[mcicc_hash][$itname_tf1]=[" . pr($it_tf1_value) . "]");

						$it_iccontent_tf1_dbarray[] = $_SESSION["mcicc_hash"][$itname_tf1];
					}
				}

				if (count($it_iccontent_tf1_dbarray) == 0) {
					if ($m2m_table != "m2m_nosave" && entity_has_field($m2m_table, "iccontent_tf1") == 1) {
						$debug_query = 0;
						$it_iccontent_tf1_dbarray = select_fieldarray("iccontent_tf1"
							, array_merge($composite, $absorbed_fixedhash, array("deleted" => 0))
							, $m2m_table);
						$debug_query = 0;
//						pre($it_iccontent_tf1_dbarray);
					}
				}

				if ($mode == "copy") {
					foreach ($it_value_dbarray as $it_value_item) {
						$it_iccontent_tf1_dbarray[] = get_string($it_name . "_" . $it_value_item . "_tf1");
					}
//					echo "$ictype_hashkey TF1: $it_name " . pr($it_iccontent_tf1_dbarray) . "<br>";
				}

//				$debug_query = 0;



				$icdict = $ic["icdict"];
				$icdict_hashkey = select_field("hashkey", array("id" => $icdict), "icdict");
				
				switch ($icdict_hashkey) {
					case "PGROUPTREE_SUPPLIERCLICKABLE":
						$ic_output = "ICMULTISELECT - PGROUPTREE_SUPPLIERCLICKABLE";
						if ($read_only == 0) {
							$ic_output = multicompositecontent("supplierbypgroup"
								, $m2m_table, array("pgroup", "supplier"), 1);
						} else {
//							$ic_output = "ICMULTISELECT - PGROUPTREE_SUPPLIERCLICKABLE $read_only";
							$ic_output = multicompositecontent("supplierbypgroup_ro", $m2m_table, array("pgroup", "supplier"), 1);
						}

						break;
				
					case "PGROUPTREE_PGROUPCLICKABLE":
						$ic_output = "ICMULTISELECT - PGROUPTREE_PGROUPCLICKABLE";
//						$ic_output = multicompositecontent("supplierbypgroup"
//							, $m2m_table, array("pgroup", "supplier"), 1);
						break;
				
					default:
//						$ic_output .= "ICMULTISELECT - SIMPLE DICTIONNARY";
//						$it_name .= "[]";	// icmulti handles itself
						$ic_output .= icmulti($icdict, $it_value_dbarray, $ictype_hashkey, $it_name, $read_only, $it_iccontent_tf1_dbarray, $ic["param1"]);

				}

				if ($ic["obligatory"] == 1 && count($it_value_dbarray) == 0) $sheet_row_had_errors = 1;

				break;

			case "TEXTAREA":
				if ($mode == "copy") $it_value = get_arrayfirst($it_name);
				$it_name .= "[]";

				if ($read_only == 0) {
					$ic_output = textarea($it_name, $it_value, $ic["param1"]);
				} else {
					$ic_output = ro($it_name, $it_value, $ic["param1"]);
				}
				if ($ic["obligatory"] == 1 && $it_value == "") $sheet_row_had_errors = 1;
				break;

			case "TEXTFIELD":
//				echo "$ictype_hashkey: $it_name $it_value<br>";
				if ($mode == "copy") $it_value = get_arrayfirst($it_name);
				$it_name .= "[]";

				if ($read_only == 0) {
					$ic_output = textfield($it_name, $it_value, $ic["param1"]);
				} else {
					$ic_output = ro($it_name, $it_value, $ic["param1"]);
				}
				if ($ic["obligatory"] == 1 && $it_value == "") $sheet_row_had_errors = 1;
				break;

			case "NUMBER":
//				echo "$ictype_hashkey: $it_name $it_value<br>";
				if ($mode == "copy") $it_value = get_arrayfirst($it_name);
				$it_name .= "[]";

				if ($read_only == 0) {
					$ic_output = number($it_name, $it_value, $ic["param1"]);
				} else {
					$ic_output = ro($it_name, $it_value, $ic["param1"]);
				}
				if ($ic["obligatory"] == 1 && $it_value == "") $sheet_row_had_errors = 1;
				break;

			case "TEXTAREA_SCROLL":
//				echo "$ictype_hashkey: $it_name $it_value<br>";
				$ic_output = textarea_scroll($ic);
				break;


			case "UPLOAD":
				if (isset($_FILES[$it_name])) {
					if (is_uploaded_file($_FILES[$it_name]["tmp_name"])) {
						$it_value = $_FILES[$it_name]["name"];
					}
				}
				$ic_output = $ictype_hashkey($it_name, $it_value, $ic["param1"]);
				break;

			case "FORMULA":
//				echo "$ictype_hashkey($it_name, $it_value, ${ic['param1']}, ${ic['hashkey']}, $ic_rows)<br>";
				$ic_output = $ictype_hashkey($it_name, $it_value, $ic["param1"], $ic["hashkey"], $ic_rows);
				break;

			case "DATE":
			case "TIMESTAMP_DATE":
//				echo "$ictype_hashkey($it_name, $it_value, " . $ic["param1"] . ")";
//				pre($ic);
				$ic_output = $ictype_hashkey($it_name, $it_value, $ic["param1"]);
				break;

			default:
				if ($mode == "copy") $it_value = get_arrayfirst($it_name);
				$it_name .= "[]";

//				echo "$ictype_hashkey($it_name, $it_value, " . $ic["param1"] . ")";
				$ic_output = $ictype_hashkey($it_name, $it_value, $ic["param1"]);

				if ($ic["obligatory"] == 1 && $it_value == "") $sheet_row_had_errors = 1;
		}

		if (isset($mcicc_copyform)) {
			switch($ictype_hashkey) {
				case "ICMULTISELECT":
				case "ICMULTICHECKBOX":
//					pre($it_iccontent_tf1_dbarray);
					foreach ($it_value_dbarray as $it_value_item) {
						$mcicc_copyform .= hash_by_tpl(
							array("it_name" => $it_name . "[]", "it_value" => $it_value_item)
							, $mcicc_copyform_tpl
							);
					}

					if (entity_has_field($m2m_table, "iccontent_tf1") == 1) {
						for ($i=0; $i<count($it_value_dbarray); $i++) {
							$mcicc_copyform .= hash_by_tpl(
								array("it_name" => $it_name . "_" . $it_value_dbarray[$i] . "_tf1"
									, "it_value" => $it_iccontent_tf1_dbarray[$i])
								, $mcicc_copyform_tpl
								);
						}
					}

					break;
	
				default:
					$mcicc_copyform .= hash_by_tpl(
						array("it_name" => $it_name, "it_value" => $it_value), $mcicc_copyform_tpl
						);
			}
		}


		$row = array();
		$row["id"] = $ic["id"];
		$row["it_txt"] = $ic["ident"];
		$row["it_hashkey"] = $ic["hashkey"];

		if ($read_only == 0) {
			if ($ic["obligatory"] == 1) {
				$row["obligatory_sign"] = ($jsv_hashkey == "") ? $backrow_obligatory_sign : $backrow_obligatory_nojs_sign;
			} else {
				$row["obligatory_sign"] = $backrow_not_obligatory_sign;
				if ($in_backoffice == 1) {
					$row["obligatory_sign"] = ($jsv_hashkey == "") ? $backrow_not_obligatory_sign : $backrow_obligatory_jsonly_sign;
				}
			}
			if ($ignore_jsv_finally == 0 && $ic["obligatory"] == 1) {
				$row["obligatory_sign"] = jsv_addvalidation($jsv_hashkey, $it_name, $ic["ident"], $ic["jsv_debug"], $form_name);
			}

		} else {
			$row["obligatory_sign"] = $backrow_not_obligatory_sign;
		}
		$row["it_name"] = $it_name;
		$row["it_wrapped"] = $ic_output;

		$row["it_graycomment"] 		= (isset($ic["graycomment"]) && $ic["graycomment"] != "")
			? $ic["graycomment"] : "";
		$row["it_graycomment_gray"]	= (isset($ic["graycomment"]) && $ic["graycomment"] != "")
			? "<font style='color: " . OPTIONS_COLOR_GRAY . "'>" . $ic["graycomment"] . "</font>"
			: "";



//	echo "$obligatory_field=[" . $ic["obligatory"] . "] it_name=[$it_name] it_value=[$it_value] sheet_row_had_errors=[$sheet_row_had_errors] sheet_present_in_db=[$sheet_present_in_db]<br>";

		$sheet_row_bgcolor = OPTIONS_COLOR_LIGHTBLUE;
		if ($sheet_row_had_errors == 1 && $sheet_present_in_db > 0) {
			$sheet_had_errors = 1;
			$sheet_row_bgcolor = OPTIONS_COLOR_YELLOWBG;
		}
/*	
		if ($sheet_row_had_errors == 1 && ($sheet_present_in_db == 0 && $mode == "update")) {
			$sheet_had_errors = 1;
			$sheet_row_bgcolor = OPTIONS_COLOR_ORANGE;
		}
*/
		$row["sheet_row_bgcolor"] = $sheet_row_bgcolor;
//		pre($ic);
//		pre($row);
//		pre($tpl);

		if ($ictype_hashkey == "RAWHTML") {
			$ret .= $ic_output;
		} else {
			$ret .= hash_by_tpl($row, $tpl);
		}
	}

//	echo "child: sheet_present_in_db=[$sheet_present_in_db] sheet_had_errors=[$sheet_had_errors]";
	return $ret;
}



function multicompositeiccontent_update($m2m_table, $fixed_hash, $ic, $it_name, $absorbed_fixedhash = array()) {
	$ret = "";

	$ictype_hashkey = $ic["ictype_hashkey"];
	if ($ictype_hashkey == "AHREF") return;

	$form_value = get_array($it_name);

	$composite = array_merge($fixed_hash, $absorbed_fixedhash);

//	pre ("multicompositeiccontent_update()");
//	pre ($fixed_hash);
//	pre ($ic);
//	pre ($it_name);
//	pre ($absorbed_fixedhash);
//	pre ($composite);
//	pre ($form_value);


	switch($ictype_hashkey) {
		case "ICMULTISELECT":
		case "ICMULTICHECKBOX":
		case "ICSELECT":
//			pre ("multicompositeiccontent_update: updating IC.ICTYPE=[$ictype_hashkey]");

			$icdict = $ic["icdict"];
			$icdict_hashkey = select_field("hashkey", array("id" => $icdict), "icdict");
			
			switch ($icdict_hashkey) {
				case "PGROUPTREE_SUPPLIERCLICKABLE":
//					pre ("multicompositeiccontent_update: updating IC.ICDICT_HASHKEY=[" . $icdict_hashkey . "]");
					
					$composite = array("pgroup", "supplier");
					$it_name = composite_itname($composite);
					$form_value = get_array($it_name);

					multicompositecontent_update($m2m_table, $form_value, $composite
						, array("ic" => $ic["id"], "srepinittask" => "_global:id", "srep" => "_global:srep"));
					break;
			
				case "PGROUPTREE_PGROUPCLICKABLE":
//					pre ("multicompositeiccontent_update: updating IC.ICDICT_HASHKEY=[" . $icdict_hashkey . "]");
//					multicompositepointer_multipleupdate($m2m_table, $composite, $form_value);
					break;
			
				default:
					$iccontent_tf1_formvalue_array  = array();
					foreach ($form_value as $dictcontent_id) {
						$param_name = "{$it_name}_{$dictcontent_id}_tf1";
						$iccontent_tf1_formvalue = get_string($param_name);
						$iccontent_tf1_formvalue_array[] = $iccontent_tf1_formvalue;
//						pre($param_name);
					}
//					pre($iccontent_tf1_formvalue_array);
					multicompositepointer_multipleupdate($m2m_table, $composite, $form_value
						, "iccontent", $iccontent_tf1_formvalue_array);
					break;
			}

			break;

		case "SELECT":
		case "ICSELECT":
		case "TRISTATE":
		case "NUMBER":
		case "NUMBER_ETHALON":
		case "TEXTFIELD":
		case "TEXTAREA":
		case "TEXTAREA_24":
		case "TEXTAREA_SCROLL":
//			echo "<pre>" . "[$it_name][$ictype_hashkey]: " . pr($form_value[0]) . "</pre>";
			multicompositepointer_singleupdate($m2m_table, $composite, $form_value[0]);
			break;

		case "TIMESTAMP_DATE":
			$debug_query = 1;
			$form_value_date = get_date($it_name);
			multicompositepointer_singleupdate($m2m_table, $composite, $form_value_date);
			$debug_query = 0;
			break;

		case "ICRADIO":
			if (count($form_value) > 0) {
//				multicompositepointer_singleupdate($m2m_table, $composite, $form_value[0]);
				$form_value_array_for_multi = array($form_value[0]);

				$iccontent_tf1_formvalue_array  = array();
				foreach ($form_value as $dictcontent_id) {
					$param_name = "{$it_name}_{$dictcontent_id}_tf1";
					$iccontent_tf1_formvalue = get_string($param_name);
					$iccontent_tf1_formvalue_array[] = $iccontent_tf1_formvalue;
//					pre($param_name);
				}
//				pre($iccontent_tf1_formvalue_array);
				multicompositepointer_multipleupdate($m2m_table, $composite, $form_value_array_for_multi
					, "iccontent", $iccontent_tf1_formvalue_array);


				break;
			}


		case "CHECKBOX":
			$form_checked = "";
			if (isset($form_value[0])) $form_checked = $form_value[0];
			$checked = ($form_checked == "on") ? 1 : 0;
			multicompositepointer_singleupdate($m2m_table, $composite, $checked);
			break;

		case "ICIMAGE":
//tranformation due to it_name contained "[]"; $_FILES[$it_name] became an array
			$fupload_array = $_FILES[$it_name];
			$fupload = array();
			foreach ($fupload_array as $key => $value) $fupload[$key] = $value[0];
			$fupload["it_name"] = $it_name;

			multicompositeiccontent_icimageupdate($m2m_table, $composite, $fupload);
			break;

		case "RAWHTML":
		case "FORMULA":
			break;

		default:
			pre ("multicompositeiccontent_update: no handler for IC.ICTYPE=[$ictype_hashkey]");
			break;
	}

}



function iccomposite_itname($composite) {
	$it_name = "";
	foreach($composite as $value) {
		if ($it_name != "") $it_name .= "_";
		$it_name .= $value;
	}
	return $it_name;
}

function iccomposite_itvalue($composite, $row) {
	$option_value = "";
	foreach($composite as $value) {
		if ($option_value != "") $option_value .= "_";
		$option_value .= $row[$value];
	}
	if ($option_value == "") $option_value = 0;
	return $option_value;
}

/*
function iccomposite_fixedhash($composite, $row) {
	$ret = array();
	foreach($composite as $key) {
		$ret[$key] = $row[$key];
	}
	return $ret;
}
*/

function multicompositeiccontent_icimageupdate($m2m_table, $fixed_hash, $form_value) {
	global $debug_query, $win_tran, $fname_common;
//	$debug_query = 1;

//	pre ("multicompositeiccontent_imageupdate($m2m_table, $fixed_hash, $form_value)");
//	pre($fixed_hash);
//	pre($form_value);

//	$save_relpath = get_sreptask_shop_photoserno_relpath($fixed_hash);
//	$save_abspath = get_upload_abspath() . $save_relpath;

	if (isset($fixed_hash["sreptask"]) && isset($fixed_hash["photoserno"])) {
		$save_relpath = get_sreptask_shop_photoserno_relpath($fixed_hash);
	} else if (isset($fixed_hash["shop"])) {
		$save_relpath = get_shop_photoserno_relpath($fixed_hash, 1);
	}

	$save_abspath = get_upload_abspath() . $save_relpath;


	$del_value = "";

	$del_value_array = get_array("del_" . $form_value["it_name"]);
//	pre($del_value_array);
	if (count($del_value_array) > 0) $del_value = $del_value_array[0];
	
	if ($del_value == "on") {
		$iccontent = select_field("iccontent", $fixed_hash, $m2m_table);
		$iccontent_abspath = $save_abspath . $iccontent;

		if (is_file($iccontent_abspath)) {
			$unlinked = unlink($iccontent_abspath);
			if ($unlinked) update(array("iccontent" => ""), $fixed_hash, $m2m_table);
		}
	}

	if (is_uploaded_file($form_value["tmp_name"])) {
//		pre($fixed_hash);
//		pre($form_value);

		$create_directory = get_sreptask_shop_photoserno_relpath($fixed_hash, 1);

		$moved_name = $form_value["name"];

		$moved_name = $form_value["name"];
		$moved_name = strtr($moved_name, $win_tran);
		$moved_name = strtr($moved_name, $fname_common);

		$moved = move_uploaded_file($form_value["tmp_name"], $save_abspath . $moved_name);

		$m2m_id = select_field("id", $fixed_hash, $m2m_table);
		if ($m2m_id > 0) {
			update(array("iccontent" => $moved_name), array("id" => $m2m_id), $m2m_table);		
		} else {
			$insert_hash = array_merge($fixed_hash, array("iccontent" => $moved_name, "date_created" => "CURRENT_TIMESTAMP"));
			insert($insert_hash, $m2m_table);		
		}
	}
//	$debug_query = 0;
}

function get_sreptask_shop_photoserno_relpath ($fixed_hash, $force_creation = 0) {
//	pre($fixed_hash);
	
	$sreptask_relpath = (isset($fixed_hash["sreptask"])) ? "sreptask_" . $fixed_hash["sreptask"] . "/" : "";
	$shop_relpath = (isset($fixed_hash["shop"])) ? "shop_" . $fixed_hash["shop"] . "/" : "";
	$photoserno_relpath = (isset($fixed_hash["photoserno"])) ? "photoserno_" . $fixed_hash["photoserno"] . "/" : "";

	if ($force_creation == 1) {
		create_path($sreptask_relpath);
		create_path($sreptask_relpath . $shop_relpath);
		create_path($sreptask_relpath . $shop_relpath . $photoserno_relpath);
	}
	
	$save_relpath = $sreptask_relpath . $shop_relpath . $photoserno_relpath;
	return $save_relpath;
}

function get_shop_photoserno_relpath ($fixed_hash, $force_creation = 0) {
	$shop_relroot = "shop/";
	$shop_relpath = $fixed_hash["shop"] . "/";
//	$photoserno_relpath = "photoserno_" . $fixed_hash["photoserno"] . "/";

	if ($force_creation == 1) {
		create_path($shop_relroot);
		create_path($shop_relroot . $shop_relpath);
//		create_path($shop_relroot . $shop_relpath . $photoserno_relpath);
	}

	$save_relpath = $shop_relroot . $shop_relpath;
//	$save_relpath .= $photoserno_relpath;
	return $save_relpath;
}

function create_path($dest_path) {
	$dest_abspath = get_upload_abspath() . $dest_path;
	if (!is_dir($dest_abspath)) mkdir($dest_abspath, 0777);
}

$upload_relpath = "/upload/";

function get_upload_abspath() {
	global $upload_relpath;
	$upload_abspath = $_SERVER["DOCUMENT_ROOT"] . $upload_relpath;
	return $upload_abspath;
}

function icimage($name, $value, $fixed_hash = array(), $icimage_input_tpl = "", $icimage_view_tpl = "") {
	global $input_size, $layer_inside;
	global $upload_relpath, $entity, $id;

	if (isset($fixed_hash["photoserno"])) {
		if ($icimage_input_tpl == "") {
			$icimage_input_tpl = <<< EOT
<input type="file" class="file" size="#TF_SIZE#" name="#IT_NAME#">
&nbsp;&nbsp;&nbsp;<input type=button value="сохранить" onclick="javascript:submit_image(#PHOTOSERNO#)" class='save' width='30' height='10'>
EOT;
		}
	
		if ($icimage_view_tpl == "") {
			$icimage_view_tpl = <<< EOT
<br><input type=checkbox name="del_#IT_NAME#" id="del_#IT_NAME#">
<label for="del_#IT_NAME#">удалить</label>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="javascript:popup_imgurl('#IMG_RELNAME_URLENCODED#', #IMG_WIDTH#, #IMG_HEIGHT#)">посмотреть</a>&nbsp;&nbsp;|&nbsp;&nbsp;[#NAME_SIZE#]
EOT;
		}
	} else {
		if ($icimage_input_tpl == "") {
			$icimage_input_tpl = <<< EOT
<input type="file" class="file" size="#TF_SIZE#" name="#IT_NAME#">
EOT;
		}
	
		if ($icimage_view_tpl == "") {
			$icimage_view_tpl = <<< EOT
<br><input type=checkbox name="del_#IT_NAME#" id="del_#IT_NAME#">
<label for="del_#IT_NAME#">удалить</label>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="javascript:popup_imgurl('#IMG_RELNAME_URLENCODED#', #IMG_WIDTH#, #IMG_HEIGHT#)">посмотреть</a>&nbsp;&nbsp;|&nbsp;&nbsp;[#NAME_SIZE#]
EOT;
		}
	}

	$tpl = $icimage_input_tpl;

	$save_relpath = "$entity/$id/";

	if (is_array($fixed_hash) && sizeof($fixed_hash) > 0) {
		$save_relpath = get_sreptask_shop_photoserno_relpath($fixed_hash);
	}

	$img_relpath = $upload_relpath . $save_relpath;
	$img_abspath = $_SERVER["DOCUMENT_ROOT"] . $upload_relpath . $save_relpath;

	$row = array(
		"it_name" => $name
		, "img_relpath" => $img_relpath
		, "img_relname" => $img_relpath . "картинка не залита"
		, "img_relname_urlencoded" => $img_relpath . "картинка не залита"
		, "img_abspath" => $img_abspath
		, "img_absname" => $img_abspath . "картинка не залита"
		, "name_size" => "файл потерян"
		, "img_width" => 0
		, "img_height" => 0
	);

	$row["tf_size"] = $input_size["file"];
//	if ($layer_inside == 1) $tf_size = $input_size["file_insidelayer"];
	
	if ($value != "") {
		$tpl .= $icimage_view_tpl;

		$row["img_relname"] = $row["img_relpath"] . $value;
		$row["img_absname"] = $row["img_abspath"] . $value;
		$row["name_size"] = name_size($value, $row["img_abspath"]);

		if (is_file($row["img_absname"])) {
			$img_size = getimagesize($row["img_absname"]);
			$row["img_width"] = $img_size[0];
			$row["img_height"] = $img_size[1];
			$row["img_relname_urlencoded"] = urlencode($row["img_relname"]);
		}
	}

	$row = array_merge($row, $fixed_hash);
//	pre($row);

	$ret = hash_by_tpl($row, $tpl);

	return $ret;
}


function icimage_ro($name, $value, $fixed_hash = array(), $icimage_input_tpl = "", $icimage_view_tpl = "") {
	$ret = "";

//	echo "called icimage_ro($name, $value, $fixed_hash, [$icimage_input_tpl], [$icimage_view_tpl])<br>";

	if ($value != "") {
		if ($icimage_input_tpl == "") $icimage_input_tpl = " ";

		if ($icimage_view_tpl == "") {
			$icimage_view_tpl = <<< EOT
<a href="javascript:popup_imgurl('#IMG_RELNAME_URLENCODED#', #IMG_WIDTH#, #IMG_HEIGHT#)">посмотреть</a>&nbsp;&nbsp;[#NAME_SIZE#]
EOT;
		}
	} else {
		if ($icimage_input_tpl == "") $icimage_input_tpl = " ";

		if ($icimage_view_tpl == "") {
			$icimage_view_tpl = <<< EOT
[отсутствует] [не печатается почему-то, лень разбираться]
EOT;
		}
//		echo $icimage_view_tpl;
	}

	$ret = icimage($name, $value, $fixed_hash, $icimage_input_tpl, $icimage_view_tpl);

	return $ret;
}

function icimage_src($name, $value, $fixed_hash = array()) {
	global $input_size, $layer_inside;
	global $upload_relpath, $entity, $id;

	$save_relpath = "$entity/$id/";

	if (is_array($fixed_hash) && sizeof($fixed_hash) > 0) {
		$save_relpath = get_sreptask_shop_photoserno_relpath($fixed_hash);
	}

	$save_abspath = get_upload_abspath() . $save_relpath;

	$tf_size = $input_size["file"];
//	if ($layer_inside == 1) $tf_size = $input_size["file_insidelayer"];

	$ret = "";

	if ($value != "") {
		$image_relpath = $upload_relpath . $save_relpath . $value;
		$name_size = name_size($value, $_SERVER["DOCUMENT_ROOT"] . $upload_relpath . $save_relpath);

/*		$ret .= <<< EOT
&nbsp;&nbsp;|&nbsp;&nbsp;
<input type=text size=5 value="<div><img src='$image_relpath' border=0></div>">
EOT;
*/
		$ret .= <<< EOT
<input type=text size=5 value="<div><img src='$image_relpath' border=0></div>"> [Ctrl-Click], [Ctrl-C], в текст новости, [Ctrl-V]
EOT;
	}

	return $ret;
}


function icimage_fsize($value, $fixed_hash = array()) {
	global $upload_relpath, $entity, $id;
	
	$ret = 0;

	$save_relpath = $upload_relpath . "$entity/$id/";

	if (is_array($fixed_hash) && sizeof($fixed_hash) > 0) {
		$save_relpath = get_sreptask_shop_photoserno_relpath($fixed_hash);
	}

	$save_abspath = get_upload_abspath() . $save_relpath;

	if ($value != "") {
		$file_abspath = $_SERVER["DOCUMENT_ROOT"] . $upload_relpath . $save_relpath . $value;
		if (file_exists($file_abspath)) $ret = filesize($file_abspath);
	}

	return $ret;
}


?>