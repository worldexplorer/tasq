<? require_once "_lib/_init.php" ?>
<?

function email($row) {
	$ret = "";

	if ($row["email"] != "") {
		$tpl = <<< EOT
<div>e-mail: <a href="mailto:#EMAIL#">#EMAIL#</a></div>
EOT;
		$ret = hash_by_tpl($row, $tpl);
	}
	
	return $ret;
}

function hrefto($row) {
	$ret = "";

	if ($row["hrefto"] != "") {
		$tpl = <<< EOT
<div style="clear:both">ссылка: <a href="#HREFTO#" target=_blank>#HREFTO#</a></div>
EOT;
		$ret = hash_by_tpl($row, $tpl);
	}
	
	return $ret;
}

function product_is_new($row) {
	$ret = "";

	$ret = ($row["is_new"] == 1) ? "<div class='product_new'>новинка</div>" : "";
	
	return $ret;
}

function prices_block($row) {
	$ret = "";
	
	$ret .= ($row["price_1"] > 0) ? "<div class=price>Цена: @formatted_price@ #PRICECOMMENT_1#</div>" : "";
//	$ret .= ($row["price_2"] > 0) ? "<div class=price>Цена2: @formatted_price_2@ #PRICECOMMENT_2#</div>" : "";
//	$ret .= ($row["price_3"] > 0) ? "<div class=price>Цена3: @formatted_price_3@ #PRICECOMMENT_3#</div>" : "";

	$ret = hash_by_tpl($row, $ret);
	return $ret;
}

function product_content_bad($row) {
	$ret = "";

	$tpl = <<< EOT
			<br>
			<div class="description">
				<!--b>Описание товара</b-->
				<p>#CONTENT#</p>
			</div>
EOT;

	if (strip_tags($row["content"]) != "") {
		$ret = hash_by_tpl($row, $tpl);
	}

	return $ret;
}

function other_frompgroup($row) {
	$ret = "";

	$tpl = <<< EOT
<li style="width:45%; margin-right: 10px; border:0px solid black; height: 85px; float: left">@product_first2@<a href="#ENTITY#.php?id=#ID#">#IDENT#</a><br>


EOT;

	$query = "select p.*, pg.ident as pgroup_ident"
		. " from product p"
		. " inner join m2m_product_pgroup m2m_src on m2m_src.product=" . $row["id"] . " and m2m_src.published=1 and m2m_src.deleted=0"
		. " inner join m2m_product_pgroup m2m_dst on m2m_dst.product=p.id and m2m_dst.pgroup=m2m_src.pgroup and m2m_dst.published=1 and m2m_dst.deleted=0"
		. " left outer join pgroup pg on m2m_dst.pgroup=pg.id and pg.published=1 and pg.deleted=0"
		. " where p.id <> " . $row["id"]
		. " and p.published=1 and p.deleted=0"
//		. " order by p." . get_entity_orderby("product")
		. " group by p.id"
		. " order by rand()"
		. " limit 10"
		;
	$ret = query_by_tpl($query, $tpl);
	
	if ($ret != "") {
/*
		$ret = <<< EOT
	<td class="v_t padtb padr smallpad" style="width: 27%">
		<div class="grey_rc">
			<h3>Другие товары</h3>
			<div class="white_rc">
				<ul>
				$ret
				</ul>
			</div>
		</div>
	</td>

EOT;
*/

		$pgroup_ident = strtolower($row["pgroup_ident"]);
		$pgroup = strtolower($row["pgroup"]);

		$ret = <<< EOT
			<!--h3>Другие $pgroup_ident</h3-->
			<h3>Возможно, Вас также заинтересуют:</h3>
			<div class="white_rc">
				<ul>
				$ret
				</ul>
				<a href="pgroup.php?id=$pgroup">Вернуться в полный список товаров группы "$pgroup_ident"</a>
			</div>

EOT;

	}
	
	return $ret;
}

function add_tab($tab_id, $tab_label, $tab_content) {
	global $tabs_controller, $tabs_content;
	
	if ($tab_content == "") return;
	if (strtolower($tabs_content) == "<p>&nbsp;</p>") return;
	
	$tab_content = str_replace("<ul>", "<ul class='productTab'>", $tab_content);
	$tab_content = str_replace("<li>", "<li class='productTab'>", $tab_content);
	
	$tabs_controller .= <<< EOT
	<div class="tabitem small" id="$tab_id"><b>$tab_label</b></div>
	
EOT;

	$tabs_content .= <<< EOT
	<div class="tab" id="{$tab_id}_ct">
		$tab_content
	</div>
	
EOT;
	
	return $tabs_content;
}

function product_award($row, $entity = "award") {
	$ret = "";

	$tpl = <<< EOT
<tr>
	<td valign="top"><a href="#HREFTO#" target=_blank><img src="img/icon/htm-shortcut.gif" height="16" width="16" hspace="4" vspace="2" align=absmiddle border="0"></a></td>
	<td class="productDesc"><a href="#HREFTO#" target=_blank>#IDENT#</a></td>
</tr>

EOT;

	
	$query = "select e.*"
		. " from $entity e"
		. " inner join m2m_product_$entity m2m on m2m.$entity=e.id and m2m.published=1 and m2m.deleted=0"
		. " where m2m.product=" . $row["id"]
		. " and e.published=1 and e.deleted=0"
		. " group by e.id"
		. " order by e." . get_entity_orderby($entity)
		;
	
	$qa = select_queryarray($query, $entity);
	foreach ($qa as $row) { 
//		pre($row);
//		$row["hrefto"] = name_size($row[""]);
//		$row["namesize"] = name_size($row[""]);
		$ret .= hash_by_tpl($row, $tpl, $entity);
	}
	
	if ($ret != "") {
		$ret = <<< EOT
<table>
	$ret	
</table>

EOT;
	}
	
//	echo $ret;
	return $ret;
}


function product_review($row, $entity = "review") {
	$ret = "";

	$tpl = <<< EOT
<tr>
	<td valign="top"><a href="#HREFTO#" target=_blank><img src="img/icon/htm-shortcut.gif" height="16" width="16" hspace="4" vspace="2" align=absmiddle border="0"></a></td>
	<td class="productDesc"><a href="#HREFTO#" target=_blank>#IDENT#</a></td>
</tr>

EOT;

	
	$query = "select e.*"
		. " from $entity e"
		. " inner join m2m_product_$entity m2m on m2m.$entity=e.id and m2m.published=1 and m2m.deleted=0"
		. " where m2m.product=" . $row["id"]
		. " and e.published=1 and e.deleted=0"
		. " group by e.id"
		. " order by e." . get_entity_orderby($entity)
		;
	
	$qa = select_queryarray($query, $entity);
	foreach ($qa as $row) { 
//		pre($row);
//		$row["hrefto"] = name_size($row[""]);
//		$row["namesize"] = name_size($row[""]);
		$ret .= hash_by_tpl($row, $tpl, $entity);
	}
	
	if ($ret != "") {
		$ret = <<< EOT
<table>
	$ret	
</table>

EOT;
	}
	
//	echo $ret;
	return $ret;
}

function product_instruction($row, $entity = "instruction") {
	$ret = "";

	$tpl = <<< EOT
<tr>
	<td valign="top"><a href="#HREFTO#" target=_blank><img src="img/icon/rar.gif" height="16" width="16" hspace="4" vspace="0" align=absmiddle border="0"></a></td>
	<td class="productDesc">
		<a href="#HREFTO#" target=_blank>#IDENT#</a>
		<div>#BRIEF#</div>
		<div>#FILES#</div>
	</td>
</tr>

EOT;
	
	$query = "select e.*"
		. " from $entity e"
		. " inner join m2m_product_$entity m2m on m2m.$entity=e.id and m2m.published=1 and m2m.deleted=0"
		. " where m2m.product=" . $row["id"]
		. " and e.published=1 and e.deleted=0"
		. " group by e.id"
		. " order by e." . get_entity_orderby($entity)
		;
	
	$qa = select_queryarray($query, $entity);
	foreach ($qa as $row) { 
//		pre($row);
		$row = entity_files5($row);
		if ($row["mainfile_hrefto"] != "") $row["hrefto"] = $row["mainfile_hrefto"];
		$ret .= hash_by_tpl($row, $tpl, $entity);
	}
	
	if ($ret != "") {
		$ret = <<< EOT
<table>
	$ret	
</table>

EOT;
	}
	
	return $ret;
}

function entity_files5($row, $tpl_file = "", $fields_finish_on = 5, $fields_start_from = 1) {
	global $upload_abspath, $upload_relpath;

	if ($tpl_file == "") {
		$tpl_file = <<< EOT
<div style="padding-left:3em">
	<a href="#RELNAME#" target=_blank><img src="img/icon/#EXTENSION#.gif" height="16" width="16" hspace="4" vspace="2" align=absmiddle border="0"></a>
	<a href="#RELNAME#" target=_blank>#NAME_SIZE#</a>
	<div style="padding-left:3em">#COMMENT#</div>
</div>
EOT;
	}

	$files = "";
	$entity_abspath = $upload_abspath . $row["entity"] . "/" . $row["id"] . "/";
	$entity_relpath = $upload_relpath . $row["entity"] . "/" . $row["id"] . "/";

	if (!isset($row["mainfile_hrefto"]) || $row["mainfile_hrefto"] == "") $row["mainfile_hrefto"] = "";


	for ($i=$fields_start_from; $i<=$fields_finish_on; $i++) {
		if (!$row["file$i"]) continue;

		$absname = $entity_abspath . $row["file$i"];
		$relname = $entity_relpath . $row["file$i"];
		$path_parts = pathinfo($relname);
		$extension_imgfile = "img/icon/" . $path_parts["extension"] . ".gif";
		$extension = file_exists($extension_imgfile) ? $path_parts["extension"] : "dat";

//		if (!file_exists($absname)) continue;
		
		$file_hash = array();
		$file_hash["relname"] = $relname;
		$file_hash["extension"] = $extension;
		$file_hash["name_size"] = name_size($row["file$i"], $entity_abspath);
		$file_hash["comment"] = $row["file{$i}_comment"];
		$files .= hash_by_tpl($file_hash, $tpl_file);

		if (!isset($row["mainfile_hrefto"]) || $row["mainfile_hrefto"] == "") {
			$row["mainfile_hrefto"] = $relname;
		}
	}

	$row["files"] = $files;
	return $row;
}

function entity_files5_outputonly($row, $tpl_file = "") {
	$row = entity_files5($row, $tpl_file, 5, 1);
	return $row["files"];
}

function entity_files4_outputonly($row, $tpl_file = "") {
	$row = entity_files5($row, $tpl_file, 5, 2);
	return $row["files"];
}

function file_first_relhref($row, $file_fieldnr = 1) {
	global $upload_abspath, $upload_relpath;

	$entity_relpath = $upload_relpath . $row["entity"] . "/" . $row["id"] . "/";
	return $entity_relpath . $row["file$file_fieldnr"];
}

function product_faq($row, $entity = "faq") {
	$ret = "";

	$tpl = <<< EOT
<tr>
	<td valign="top"><a href="#HREFTO#" target=_blank><img src="img/icon/folder.gif" height="16" width="16" hspace="4" vspace="0" align=absmiddle border="0"></a></td>
	<td class="productDesc">
		<b>#IDENT#</b>
		<blockquote>
			#CONTENT#
		</blockquote>
	</td>
</tr>

EOT;

	
	$query = "select e.*"
		. " from $entity e"
		. " inner join m2m_product_$entity m2m on m2m.$entity=e.id and m2m.published=1 and m2m.deleted=0"
		. " where m2m.product=" . $row["id"]
		. " and e.published=1 and e.deleted=0"
		. " group by e.id"
		. " order by e." . get_entity_orderby($entity)
		;
	
	$qa = select_queryarray($query, $entity);
	foreach ($qa as $row) { 
//		pre($row);
		$row["hrefto"] = "faq.php?product=" . $row["id"];
//		$row["namesize"] = name_size($row[""]);
		$ret .= hash_by_tpl($row, $tpl, $entity);
	}
	
	if ($ret != "") {
		$ret = <<< EOT
<table>
	$ret	
</table>

EOT;
	}
	
	return $ret;
}

function product_wtb($row, $entity = "cstore") {
	$ret = "";


	$tpl_ex = <<< EOT
<div class="image" style="width: #IMG_WIDTH#px; float: left; margin-right: 1ex; margin-bottom: 1ex">
<a href="#ENTITY#.php?id=#ID#"><img src="#IMG_RELPATH#" alt="#IDENT#" #IMG_WH# border="1" style="border: 1px solid gray"></a>
<p style="width: #IMG_WIDTH#px;"><a href="#ENTITY#.php?id=#ID#">#IDENT#</a></p>
</div>
EOT;

	$tpl_nex = <<< EOT
<div class="image" style="width: #IMG_WIDTH#px; float: left">
<table style="width: #IMG_WIDTH#; height: #IMG_HEIGHT#; border: 1px solid gray; vertical-align: bottom; padding:5; margin-right: 1ex; margin-bottom: 1ex; float: left" title="#IMG_NEX_DEBUGMSG#"><tr><td align=center><a href="#ENTITY#.php?id=#ID#">лого</a></td></tr></table>
<p style="width: #IMG_WIDTH#px;"><a href="#ENTITY#.php?id=#ID#">#IDENT#</a></p>
</div>
EOT;
	
	$tpl = <<< EOT
#LOGO#
EOT;

	
	$query = "select e.*"
		. " from $entity e"
		. " inner join m2m_product_$entity m2m on m2m.$entity=e.id and m2m.published=1 and m2m.deleted=0"
		. " where m2m.product=" . $row["id"]
		. " and e.published=1 and e.deleted=0"
		. " group by e.id"
		. " order by e." . get_entity_orderby($entity)
		;
	
	$qa = select_queryarray($query, $entity);
	foreach ($qa as $row) { 
//		pre($row);
		$row["logo"] = imgwrapped_or_autoresize($row, "IMG_CSTORE", "first2", $tpl_ex, $tpl_nex, 1, 1, 1);
		$ret .= $row["logo"];
//		$ret .= hash_by_tpl($row, $tpl, $entity);
	}
	
	if ($ret != "") {
		$ret = <<< EOT
<div>
	$ret
	<br clear=all>
</div>

EOT;
	}
	
	return $ret;
}


function product_iccontent_multi_houseoptions($row) {
	$ret = "";
	
	$tpl = <<< EOT
	<div class="pcard_options">
	<h5>Опции дома</h5>
		<ul class="noleftmargin">
		#ICCONTENT_WRAPPED#
		</ul>
	</div>
EOT;

	$ret = iccontent_multi_getcontent("ICMULTI_HOUSEOPTIONS", $tpl);
	return $ret;
}

function product_iccontent_multi_siteoptions($row) {
	$ret = "";
	
	$tpl = <<< EOT
	<div class="pcard_options">
	<h5>Опции участка</h5>
		<ul class="noleftmargin">
		#ICCONTENT_WRAPPED#
		</ul>
	</div>
EOT;

	$ret = iccontent_multi_getcontent("ICMULTI_SITEOPTIONS", $tpl);
	return $ret;
}

function product_iccontent_multi_infraoptions($row) {
	$ret = "";
	
	$tpl = <<< EOT
	<div class="pcard_options">
	<h5>Инфраструктура</h5>
		<ul class="noleftmargin">
		#ICCONTENT_WRAPPED#
		</ul>
	</div>
EOT;

	$ret = iccontent_multi_getcontent("ICMULTI_INFRAOPTIONS", $tpl);
	return $ret;
}

function iccontent_multi_getcontent($ic_hashkey, $tpl) {
	global $iccontent_multi_list;
	$ret = "";

	$ic = select_field("id", array("hashkey" => $ic_hashkey, "published" => 1, "deleted" => 0), "ic");
	$ic = intval($ic);
	if (isset($iccontent_multi_list[$ic])) {
//		pre($iccontent_multi_list);
		$icmulti_hash = $iccontent_multi_list[$ic];
		if (isset($icmulti_hash["id"])) {
			$ret = hash_by_tpl($icmulti_hash, $tpl);
		}
	}

	return $ret;
}

$iccontent_multi_list = array();
function product_iccontent_by_tpl($fixed_hash
			, $tpl = "<li><b>#IDENT#</b> #ICCONTENT#</li>"
			, $tpl_find_semicolumn = ": "
			, $tpl_replace_semicolumn = " "
			, $tpl_multi = "<li><b>#IDENT#</b> #ICCONTENT_WRAPPED#</li>"
			, $tpl_multi_item = "<a href='javascript:alert(\"сюда можно кликнуть и распечатаются другие товары содержащие такое же свойство\")'<span title='#ICDICT_CONTENT#'>#ICDICT_IDENT#</span></a>",
			$tpl_multi_item_separator = ", "
			, $m2m_table = "m2m_product_iccontent", $icwhose_id = 1, $icmulti_silent = 1) {

	global $iccontent_multi_list;
	$filled_iccontent_list = array();
	$ret = "";

	$query = "select ic.id as id, ic.ident as ident, ic.ictype as ictype, ic.icdict as icdict"
		. ", ic.param1 as param1, ic.param2 as param2"
		. ", icdc.ident as icdict_ident, icdc.content as icdict_content"
		. ", t.hashkey as ictype_hashkey, m2m.iccontent, m2m.iccontent_tf1"
		. " from ic ic"
		. " inner join ictype t on ic.ictype=t.id"
		. " inner join $m2m_table m2m on m2m.ic=ic.id"
//		. " left outer join icdictcontent icdc on m2m.ic=ic.id and iccontent=icdc.id and icdc.published=1 and icdc.deleted=0"
		. " left outer join icdict icd on ic.icdict=icd.id and icd.published=1 and icd.deleted=0"
		. " left outer join icdictcontent icdc on icdc.icdict=icd.id and m2m.iccontent=icdc.id and icdc.published=1 and icdc.deleted=0"
		. " where ic.deleted=0 and ic.published=1 and t.published=1 and t.deleted=0"
		. sqlcond_fromhash($fixed_hash, "", " and ")
		. " and ic.icwhose=" . $icwhose_id
		. " order by ic." . get_entity_orderfield("ic");
//	$ret = query_by_tpl($query, $tpl);

	$qa = select_queryarray($query);
//	pre($qa);

	$i = 0;
	foreach ($qa as $row) {
		$row["rows_total"] = count($qa);
		$row["i"] = ++$i;
		
		switch ($row["ictype_hashkey"]) {
			case "CHECKBOX":
				if ($row["iccontent"] == 1) $row["iccontent_wrapped"] = $row["ic_ident"];
				break;
			
			case "NUMBER":
			case "TEXTAREA":
			case "TEXTFIELD":
				if ($row["iccontent"] != "") {
//					pre($row);
					$matches = array();
//					preg_match("/(.*): (.*)/", $row["ident"], $matches);		//Размеры: [сюда ]м
//					pre($matches);
					preg_match("/(.*)$tpl_find_semicolumn(.*)/", $row["ident"], $matches);
					if (count($matches) == 3) {
						$row["iccontent_wrapped"] = $matches[1] . $tpl_replace_semicolumn . $row["iccontent"] . " " . $matches[2];
						$row["ident"] = $matches[1] . $tpl_replace_semicolumn;
						$row["iccontent"] = $row["iccontent"] . " " . $matches[2];
					} else {
						$row["iccontent_wrapped"] = $row["ident"] . $tpl_replace_semicolumn . $row["iccontent"];
						$row["ident"] = $row["ident"] . $tpl_replace_semicolumn;
					}
				}
				break;
			
			case "SELECT":
				if ($row["iccontent"] != "") {
					$row["iccontent_wrapped"] = $row["ident"] . ": " . select_field("ident",
						array("id" => $row["iccontent"], "published" => 1, "deleted" => 0),
						"icdictcontent");
				}
				break;
			
			case "ICSELECT":
				if ($row["icdict_ident"] != "") {
//					$row["iccontent"] = $row["icdict_ident"];
//					$row["iccontent"] = $row["icdict_ident"];
					$row["iccontent_wrapped"] = $row["ident"] . " " . $row["icdict_ident"];
				}
				break;
			
			case "ICMULTICHECKBOX":
			case "ICMULTISELECT":
			case "ICRADIO":
				$new_icmulti_hash = array (
					"id" => $row["id"],
					"ident" => $row["ident"],
					"icdictcontent" => array(),
					"iccontent_wrapped" => "",
					);
				$ic = $row["id"];

				$iccontent_multi_keys = array_keys($iccontent_multi_list);
				$icmulti_hash = in_array($ic, $iccontent_multi_keys) ? $iccontent_multi_list[$ic] : $new_icmulti_hash;
				if ($icmulti_hash["ident"] != $row["ident"]) $icmulti_hash = $new_icmulti_hash;

				$icmulti_hash["icdictcontent"][] = $row["icdict_ident"];
				if ($row["icdict_ident"] != "") {
					$matches = array();
//					preg_match("/(.*): (.*)/", $row["icdict_ident"], $matches);		//Размеры: [сюда ]м
					preg_match("/(.*)$tpl_find_semicolumn(.*)/", $row["icdict_ident"], $matches);
//					pre($matches);
					if (count($matches) == 3) {
						$row["icdict_ident"] = $matches[1] . $tpl_replace_semicolumn . $row["iccontent_tf1"] . $matches[2];
					}
				}
//				pre($row);


				$hash_adapted_for_tpl = array_merge($row, array("iccontent_wrapped" => $row["icdict_ident"]));
				if (count($icmulti_hash["icdictcontent"]) > 1) $icmulti_hash["iccontent_wrapped"] .= $tpl_multi_item_separator;
				$icmulti_hash["iccontent_wrapped"] .= hash_by_tpl($hash_adapted_for_tpl, $tpl_multi_item);
				
				$iccontent_multi_list[$ic] = $icmulti_hash;
//				pre($iccontent_multi_list);
				

				if ($icmulti_silent == 0) $row["iccontent_wrapped"] = $icmulti_hash["iccontent_wrapped"];
				break;
			
			default:
				break;
		}

		if (isset($row["iccontent_wrapped"])) $filled_iccontent_list[] = $row;
	}
		
	$i = 0;
//	pre($filled_iccontent_list);
	foreach ($filled_iccontent_list as $row) {
		$row["rows_total"] = count($filled_iccontent_list);
		$row["i"] = ++$i;
		$ret .= hash_by_tpl($row, $tpl);
//		pre($row);
	}

//	pre($iccontent_multi_list);

	foreach ($iccontent_multi_list as $row) {
		$row["rows_total"] = count($iccontent_multi_list);
		$row["i"] = ++$i;
		$ret .= hash_by_tpl($row, $tpl_multi);
	}


	return $ret;
}

function product_pgroup_iccontent_terminator($row) {
	return ($row["rows_total"] != $row["i"]) ? "<br>" : "";
}

function product_pgroup_iccontent_kievka($row) {
	$ret = "";

//	$tpl = "<nobr>#ICCONTENT_WRAPPED# @product_pgroup_iccontent_terminator@</nobr> ";
//	$fixed_hash = array("m2m.product" => $row["id"], "ic.hashkey" => "PPROP_ONINDEX");
	
	$tpl = "<nobr>#ICCONTENT_WRAPPED#</nobr> ";
	

	$fixed_hash = array("m2m.product" => $row["id"]);
	if ($row["pgroup"] == 2) { // участки: 
		$fixed_hash["ic.id:"] = "13,12";
		$ret = product_iccontent_by_tpl($fixed_hash, $tpl);
		$ret .= ($row["price_1"] > 0) ? hash_by_tpl($row, "<nobr>Цена: @formatted_price@ #PRICECOMMENT_1#</nobr>") : "<nobr>Цена не установлена";
	} else {
		$fixed_hash["ic.id:"] = "13,12,1";
		$ret = product_iccontent_by_tpl($fixed_hash, $tpl);
		$ret .= ($row["price_1"] > 0) ? hash_by_tpl($row, "<nobr>Цена: @formatted_price@ #PRICECOMMENT_1#</nobr>") : "<nobr>Цена не установлена";
	}

	return $ret;
}

function product_pgroup_iccontent($row) {
	$ret = "";

//	$tpl = "<nobr>#ICCONTENT_WRAPPED# @product_pgroup_iccontent_terminator@</nobr> ";
	$tpl = "<nobr>#ICCONTENT_WRAPPED#</nobr> @product_pgroup_iccontent_terminator@";

	$fixed_hash = array("m2m.product" => $row["id"], "ic.inbrief" => 1);
	
	$ret = product_iccontent_by_tpl($fixed_hash, $tpl);
//	$ret .= "<br><br>";
//	$ret .= ($row["price_1"] > 0) ? hash_by_tpl($row, "<nobr>Цена: @formatted_price@ #PRICECOMMENT_1#</nobr>") : "<nobr>Цена не установлена";

//	if ($ret != "") $ret .= "<br>";
//	$ret .= ($row["article"] != "") ? hash_by_tpl($row, "<nobr>Артикул: #ARTICLE#</nobr>") : "";

	if ($ret != "") $ret = "<p style='padding: 0 2em 0 0; margin: 0 2em 0 0; border-right:1px solid #e0e0e0'>$ret</p>";
	return $ret;
}

function product_iccontent($row) {
	$ret = "";
	
	$fixed_hash = array("m2m.product" => $row["id"]);

//	$tpl = "<li>#ICCONTENT_WRAPPED#</li>";
	$tpl = "<li><b>#IDENT#</b> #ICCONTENT#</li>";

	$ret = product_iccontent_by_tpl($fixed_hash, $tpl);
	
	if ($ret != "") $ret = "<ul class='noleftmargin'>$ret</ul>";
	
/*
	if ($ret != "") $ret = <<< EOT
<div class="pcard_options">
<h5>Характеристики</h5>
$ret
</div>
EOT;
*/

	return $ret;
}



function odd_newtr($row) {
	return ($row["i"]%2 == 0) ? "</tr><tr valign=top>" : "";
}

function even_anothertd($row) {
	return ($row["i"]%2 == 1) ? "<td width=2%></td>" : "";
}


function orderno_title($row) {
	return  " № " . $row["id"] . "&nbsp;&nbsp;&nbsp;" . date_published($row);
}




function resizable_or_iconextension_every($row) {
//	global $tpl_img_content;
//	$tpl_ex = $tpl_img_content;


	$tpl_ex = <<< EOT
<div class="image" style="width: #IMG_WIDTH#px;">
<a href="#IMG_POPUPHREF#"><img src="#IMG_RELPATH#" border="0" alt="#IDENT#" #IMG_WH#></a>
<p style="width: #IMG_WIDTH#px; height: 1em;"><a href="#IMG_POPUPHREF#">#IMG_TXT#</a></p>
</div>
EOT;

	$tpl_ex = <<< EOT
<div class="image" style="width: #IMG_WIDTH#px;">
<a href="#IMG_POPUPHREF#"><img src="#IMG_RELPATH#" border="0" alt="#IDENT#" #IMG_WH#></a>
<p style="width: #IMG_WIDTH#px;"><a href="#IMG_POPUPHREF#">#IMG_TXT#</a></p>
</div>
EOT;

	$ret = "";
//	pre($row);

	$saved_id = $row["id"];
	$saved_ident = $row["ident"];

	$row["id"] = $row["img_id"];

	$field_not_empty = "img";
	$img_fsize = img_fsize($row, $field_not_empty, 0);

	if ($img_fsize == "0&nbsp;б") {
		$field_not_empty = "img_big";
		$img_fsize = img_fsize($row, $field_not_empty, 0);
	}


//	if ($row["img_txt"] == "") {
//		$row["ident"] .= ", " . $row[$field_not_empty] . $img_fsize;
//	}
//	if ($row["img_txt"] == "")$row[$field_not_empty] .

	if ($row["img_big_txt"] != "") $row["img_txt"] = $row["img_big_txt"];
	$row["img_txt"] .= ", " . $img_fsize;
	$row["ident"] .= ": " . $row[$field_not_empty] . ", " . $img_fsize;
	
	$row["id"] = $saved_id;
//	$row["ident"] = $saved_ident;


	if ($row["IMG_RESIZABLE_BYEXTENSION"] == 1) {
		$tpl_ex = <<< EOT
<div class="image" style="width: #IMG_WIDTH#px;">
<a href="#IMG_POPUPHREF#"><img src="#IMG_RELPATH#" border="0" alt="#IDENT#" #IMG_WH#></a>
<p style="width: #IMG_WIDTH#px;"><a href="#IMG_POPUPHREF#">#IMG_TXT#</a> <a href="/upload/product/#ID#/img/#IMG_ID#/#IMG_BIG#" title="скачать #IMG_BIG#" target="_blank"><img src="/img/download.gif" alt="скачать #IMG_BIG#" style="border: 0" /></a></p>
</div>
EOT;
		$ret = hash_by_tpl($row, $tpl_ex);
	} else {

		$path_parts = pathinfo($row["IMG_RELPATH"]);
		$extension = strtolower($path_parts["extension"]);
	
		$relpath = "img/ico_";
		$extension_relfname = $relpath . $extension . "_70.gif";
	
//		pre("file_exists($extension_relfname)=" . file_exists($extension_relfname));
		$extension = file_exists($extension_relfname) ? $extension : "unknown";
		$extension_relfname = $relpath . $extension . "_70.gif";
		$row["IMG_RELPATH"] = $extension_relfname;
	
		$ret = hash_by_tpl($row, $tpl_ex);
	}

	return $ret;
}


function resizable_or_iconextension($row) {
	$tpl_ex = <<< EOT
<a href="#ENTITY#.php?id=#ID#"><img src='#IMG_RELPATH#' alt='#IDENT#' #IMG_WH# border=1 style="border: 1px solid #e5e5e5"></a>
EOT;

	$ret = "";
//	pre($row);

	if ($row["IMG_RESIZABLE_BYEXTENSION"] == 1) {
		$ret = hash_by_tpl($row, $tpl_ex);
	} else {

		$path_parts = pathinfo($row["IMG_RELPATH"]);
		$extension = strtolower($path_parts["extension"]);
	
		$relpath = "img/ico_";
		$extension_relfname = $relpath . $extension . "_70.gif";
	
//		pre("file_exists($extension_relfname)=" . file_exists($extension_relfname));
		$extension = (file_exists($extension_relfname) == 1) ? $extension : "unknown";
		$extension_relfname = $relpath . $extension . "_70.gif";
	
		$ret = <<< EOT
<a href="product.php?id={$row['id']}"><img src="$extension_relfname" {$row['IMG_WH']} hspace="4" vspace="2" align=absmiddle border="0"></a>
EOT;
	}

	return $ret;
}

function supplier_first($row) {
//	pre($row);
	$tpl_ex = <<< EOT
<div style="width: #IMG_WIDTH#;" class="image"><a href="javascript:popup_imgurl('#IMG_BIG_RELPATH#', #IMG_BIG_W#, #IMG_BIG_H#)"><img src='#IMG_RELPATH#' alt='#IDENT#' #IMG_WH#></a></div>
EOT;

	$tpl_ex = <<< EOT
<div style="width: #IMG_WIDTH#;" class="image"><a href="supplier.php?id=#ID#"><img src='#IMG_RELPATH#' alt='#IDENT#' #IMG_WH#></a></div>
EOT;

	$tpl_nex = <<< EOT
<div style="width: 120; height: 90; border: 1px solid gray; vertical-align: bottom; padding:5; margin:0 10 10 0; float=left" align=center title="#IMG_NEX_DEBUGMSG#"><br><br>изображение отсутствует</div>
EOT;

	return imgwrapped_or_autoresize($row, "IMG_SUPPLIER", "first", $tpl_ex, $tpl_nex, 1, 0);
}

function cstore_first($row) {
//	pre($row);

	$tpl_ex = <<< EOT
<div style="width: #IMG_WIDTH#;" class="image"><a href="#IMG_POPUPHREF#"><img src='#IMG_RELPATH#' alt='#IDENT#' #IMG_WH#></a></div>
EOT;

	$tpl_ex = <<< EOT
<div style="width: #IMG_WIDTH#;" class="image"><img src='#IMG_RELPATH#' alt='#IDENT#' #IMG_WH#></div>
EOT;

	if ($row["hrefto"] != "") {
		$hrefto = $row["hrefto"];

		$tpl_ex = <<< EOT
<div style="width: #IMG_WIDTH#;" class="image"><a href="$hrefto" target=_blank><img src='#IMG_RELPATH#' alt='#IDENT#' #IMG_WH#></a></div>
EOT;
	}

	$tpl_nex = <<< EOT
<div style="width: 120; height: 90; border: 1px solid gray; vertical-align: bottom; padding:5; margin:0 10 10 0; float=left" align=center title="#IMG_NEX_DEBUGMSG#"><br><br>изображение отсутствует</div>
EOT;

	return imgwrapped_or_autoresize($row, "IMG_CSTORE", "first", $tpl_ex, $tpl_nex, 1, 0);
}

function shop_every($row) {
//	pre($row);

	$tpl_ex = <<< EOT
<div style="width: #IMG_WIDTH#;" class="image"><a href="shop-print.php?id=#ID#" target=_blank><img src='#IMG_RELPATH#' alt='#IDENT#' #IMG_WH#></a></div>
EOT;

	$tpl_nex = <<< EOT
<div style="width: 120; height: 90; border: 1px solid gray; vertical-align: bottom; padding:5; margin:0 10 10 0; float:left" align=center title="#IMG_NEX_DEBUGMSG#"><br><br><a href="shop-print.php?id=#ID#" target=_blank>изображение отсутствует</a></div>
EOT;

//	return autoresize($row, "IMG_SHOPMAP", "first", $tpl_ex, $tpl_nex);
	return imgwrapped_or_autoresize($row, "IMG_SHOPMAP", "every", $tpl_ex, $tpl_nex, 1, 0);
}

function customer_first($row) {
	global $customer_me;
//	pre($row);

	$tpl_ex = <<< EOT
<div style="width: #IMG_WIDTH#px;" class="image"><a href="customer.php?id=#ID#" title="#IDENT#"><img src="#IMG_RELPATH#" alt="#IDENT#" #IMG_WH#></a></div><p><a href="customer.php?id=#ID#" title="#IDENT#">#IDENT_SHORT#</a></p>
EOT;

	$tpl_nex = <<< EOT
<div style="width: #IMG_WIDTH#; height: #IMG_HEIGHT#; border: 1px solid gray; vertical-align: bottom; padding:5; margin:0 10 10 0; float=left" align=center title="#IMG_NEX_DEBUGMSG#"><br><br>нет фотки</div>
EOT;

	$tpl_nex = <<< EOT
<table style="width: #IMG_WIDTH#px; height: #IMG_HEIGHT#px; border: 1px solid gray; vertical-align: bottom; padding:0px; margin: 1 2.2ex 1.2ex 1; float: left"><tr><td align=center><a href="customer.php?id=#ID#" title="#IMG_NEX_DEBUGMSG#">нет фотки</a></td></tr></table><p><a href="customer.php?id=#ID#" title="#IMG_NEX_DEBUGMSG#">#IDENT_SHORT#</a></p>
EOT;

	$tpl_nex_me = <<< EOT
<table style="width: #IMG_WIDTH#px; height: #IMG_HEIGHT#px; border: 1px solid gray; vertical-align: bottom; padding:0px; margin: 0 2ex 1ex 0; float: left"><tr><td align=center><a href="my_photo.php" title="#IMG_NEX_DEBUGMSG#">срочно залить фотку <br>!!!</a></td></tr></table><p><a href="my_photo.php" title="#IDENT_SHOTER#">#IDENT_SHORTER#</a></p>
EOT;

	$row["entity"] = "customer";
	if (isset($row["customer"])) $row["id"] = $row["customer"];

	$row["ident_short"] = (isset($row["customer_ident"]))
		? firstletters_truncate($row["customer_ident"], 10)
		: firstletters_truncate($row["ident"], 10)
		;

	$row["ident_shorter"] = (isset($row["customer_ident"]))
		? firstletters_truncate($row["customer_ident"], 7)
		: firstletters_truncate($row["ident"], 7)
		;
	
	if ($row["id"] == $customer_me) $tpl_nex = $tpl_nex_me;

//	return imgwrapped_or_autoresize($row, "IMG_CUSTOMER", "first", $tpl_ex, $tpl_nex, 1, 0);
	return autoresize($row, "IMG_CUSTOMER", "first", $tpl_ex, $tpl_nex, 1, 0);
}



function product_first3_onindex($row) {
//	pre($row);

	$tpl_ex = <<< EOT
<a href="#ENTITY#.php?id=#ID#"><img src='#IMG_RELPATH#' alt='#IDENT#' #IMG_WH# border=1></a>
EOT;

	$tpl_ex = <<< EOT
<div style="width: #IMG_WIDTH#;" class="image"><a href="product.php?id=#product#" title="#PRODUCT_IDENT#"><img src='#IMG_RELPATH#' alt='#PRODUCT_IDENT#' #IMG_WH#></a></div><!--p><a href="product.php?id=#product#" title="#PRODUCT_IDENT#">#PRODUCT_IDENT_SHORT#</a></p-->
EOT;

	$tpl_nex = <<< EOT
<table style="width: #IMG_WIDTH#px; height: #IMG_HEIGHT#px; border: 1px solid gray; vertical-align: bottom; padding:0px; margin:0px; float: left" title="#IMG_NEX_DEBUGMSG#"><tr><td align=center><a href="#ENTITY#.php?id=#ID#">нет фото</a></td></tr></table>
EOT;

	$tpl_nex = <<< EOT
<table style="width: #IMG_WIDTH#; height: #IMG_HEIGHT#; border: 1px solid gray; vertical-align: bottom; padding:0px; margin:0px; float: left"><tr><td align=center><a href="product.php?id=#PRODUCT#" title="#IMG_NEX_DEBUGMSG#">нет фотки</a></td></tr></table><!--p><a href="product.php?id=#PRODUCT#" title="#IMG_NEX_DEBUGMSG#">#PRODUCT_IDENT_SHORT#</a></p-->
EOT;



	$row["entity"] = "product";
	$row["id"] = $row["product"];
	$row["product_ident_short"] = firstletters_truncate($row["product_ident"], 10);

	return autoresize($row, "IMG_PRODUCT", "first3", $tpl_ex, $tpl_nex);
}



function product_first($row) {
//	pre($row);
	$tpl_ex = <<< EOT
<div style='width: #IMG_WIDTH#;'><a href="javascript:popup_imgurl('#IMG_BIG_RELPATH#', #IMG_BIG_W#, #IMG_BIG_H#)"><img src='#IMG_RELPATH#' alt='#IDENT#' #IMG_WH#></a></div>
EOT;

	$tpl_ex = <<< EOT
<div style="width: #IMG_WIDTH#;" class="image"><a href="#ENTITY#.php?id=#ID#"><img src='#IMG_RELPATH#' alt='#IDENT#' #IMG_WH#></a></div>
<div style="width: #IMG_WIDTH#;" align=right><a href="#ENTITY#.php?id=#ID#">ещё фото&raquo;</a></div>
EOT;

	$tpl_ex = <<< EOT
<div style="width: #IMG_WIDTH#;" class="image"><a href="#ENTITY#.php?id=#ID#"><img src='#IMG_RELPATH#' alt='#IDENT#' #IMG_WH#></a></div>
<div style="width: #IMG_WIDTH#;" align=right><a href="#ENTITY#.php?id=#ID#">подробнее&raquo;</a></div>
EOT;

	$tpl_nex = <<< EOT
<div style="width: 120; height: 90; border: 1px solid gray; vertical-align: bottom; padding:5; margin:0 10 10 0; float: left" align=center title="#IMG_NEX_DEBUGMSG#"><br><br>изображение отсутствует</div>
<div style="width: 120;" align=right><a href="#ENTITY#.php?id=#ID#">подробнее&raquo;</a></div>
EOT;

	$tpl_ex = <<< EOT
<a href="#ENTITY#.php?id=#ID#"><img src='#IMG_RELPATH#' alt='#IDENT#' #IMG_WH# border=1></a>
EOT;

	$tpl_nex = <<< EOT
<table style="width: #IMG_WIDTH#px; height: #IMG_HEIGHT#px; border: 1px solid gray; vertical-align: bottom; padding:0px; margin:0px; float: left" title="#IMG_NEX_DEBUGMSG#"><tr><td align=center><a href="#ENTITY#.php?id=#ID#">изображение отсутствует</a></td></tr></table>
EOT;

	$row["entity"] = "product";
	if (isset($row["product"])) $row["id"] = $row["product"];

	return autoresize($row, "IMG_PRODUCT", "first", $tpl_ex, $tpl_nex);
}

function brief_onindex($row) {
	$ret = "";

	$row["brief_onindex"] = firstwords_stripped($row, 20);

	$tpl = <<<EOT
<div>#brief_onindex#</div>
<div align=right><a href=product.php?id=#ID#>подробнее</a></div>

EOT;

	$ret = hash_by_tpl($row, $tpl);
	return $ret;
}


function product_first2($row) {
//	pre($row);
	$tpl_ex = <<< EOT
<div style="width: #IMG_WIDTH#;" class="image"><a href="javascript:layer_switch(#I#)"><img src='#IMG_RELPATH#' alt='#IDENT#' #IMG_WH# border=1></a></div><br clear=all>
<div style="width: #IMG_WIDTH#;">
	<!--a href="#ENTITY#.php?id=#ID#">#IDENT#</a-->
	<table cellpadding=0 cellspacing=0 width=#IMG_WIDTH#>
	<tr valign=top>
	    <td style="padding: 5px 0 0 0" width=10><a href="javascript:layer_switch(#I#)"><img src="img/down.gif" width=10 height=6 border=0 alt="открыть описание" class=noborder style="border:0"></a></div>
		<td style="padding: 0 0 0 5px"><b><a href="javascript:layer_switch(#I#)">#IDENT#</a></b></td>
	</tr>
	<tr><td colspan=2><div id="layer_#I#" style="display:none; padding:2 9 0 9; background-color: #e0e0e0">@brief_onindex@<hr></div></td></tr>
	</table>
</div>

EOT;

	$tpl_nex = <<< EOT
<table style="width: #IMG_WIDTH#; height: #IMG_HEIGHT#; border: 1px solid gray; vertical-align: top; padding:5; margin:0 10 10 0; float: left" title="#IMG_NEX_DEBUGMSG#"><tr><td align=center><a href="#ENTITY#.php?id=#ID#">фото</a></td></tr></table>
EOT;

	return autoresize($row, "IMG_PRODUCT", "first2", $tpl_ex, $tpl_nex);
}

function product_every($row) {
//	pre($row);
	$tpl_ex = <<< EOT
<div style="width: #IMG_WIDTH#;" class="image"><a href="javascript:popup_imgurl('#IMG_BIG_RELPATH#', #IMG_BIG_W#, #IMG_BIG_H#)"><img src='#IMG_RELPATH#' alt='#IDENT#' #IMG_WH#></a></div>
EOT;

	$tpl_nex = <<< EOT
<div style="width: 120; height: 90; border: 1px solid gray; vertical-align: bottom; padding:5; margin:0 10 10 0; float=left" align=center title="#IMG_NEX_DEBUGMSG#"><br><br>изображение отсутствует</div>
EOT;

//	return autoresize($row, "IMG_PRODUCT", "every", $tpl_ex, $tpl_nex);

	$tpl_ex = <<< EOT
<div style="width: #IMG_WIDTH#" class="image" style="float:none"><img src='#IMG_BIG_RELPATH#' alt='#IDENT#' #IMG_BIG_WH#></div>
EOT;

	$tpl_ex = <<< EOT
<div class="image" style="width: #IMG_WIDTH#px; float:left"><a href="javascript:popup_imgurl('#IMG_BIG_RELPATH#', #IMG_BIG_W#, #IMG_BIG_H#)"><img src='#IMG_RELPATH#' alt='#IDENT#' #IMG_WH#></a><p style="width: #IMG_WIDTH#px">#IMG_BIG_TXT#</p></div>
EOT;

	return imgwrapped_or_autoresize($row, "IMG_PRODUCT", "every", $tpl_ex, $tpl_nex, 1, 0);
}

function news_first($row) {
	$tpl_nex = "";
	$tpl_ex = <<< EOT
<div class="image nomargin"><a href="#ENTITY#.php?id=#ID#"><img src="#IMG_RELPATH#" alt="#IMG_TXT#" border="0" #IMG_WH# vspace=5></a><p style="width: #IMG_WIDTH#px">#IMG_BIG_TXT#</p></div>
EOT;

	$tpl_ex = <<< EOT
<div class="image nomargin"><a href="#ENTITY#.php?id=#ID#"><img src="#IMG_RELPATH#" alt="#IMG_TXT#" border="0" #IMG_WH# vspace=5></a></div>
EOT;

	$row["entity"] = "news";

	return imgwrapped_or_autoresize($row, "IMG_NEWS", "first", $tpl_ex, $tpl_nex);
}


function news_every($row) {
	global $tpl_img_content;
	
	$tpl_nex = "";
	$tpl_ex = <<< EOT
<div class=image><a href="#IMG_POPUPHREF#"><img src="#IMG_RELPATH#" alt="#IMG_TXT#" border="0" #IMG_WH#></a><p style="width: #IMG_WIDTH#px">#IMG_BIG_TXT#</p></div>
EOT;

//	$tpl_ex = $tpl_img_content;

	return imgwrapped_or_autoresize($row, "IMG_NEWS", "every", $tpl_ex, $tpl_nex);
}

function article_first($row) {
	$tpl_nex = "";
	$tpl_ex = <<< EOT
<div class="image nomargin"><a href="#ENTITY#.php?id=#ID#"><img src="#IMG_RELPATH#" alt="#IMG_TXT#" border="0" #IMG_WH# vspace=5></a><p style="width: #IMG_WIDTH#px">#IMG_BIG_TXT#</p></div>
EOT;

	return imgwrapped_or_autoresize($row, "IMG_CONTENT_LEFT", "first", $tpl_ex, $tpl_nex);
}


function article_every_left($row) {
	global $tpl_img_content;
	
	$tpl_nex = "";
	$tpl_ex = <<< EOT
<div class=image><a href="#IMG_POPUPHREF#"><img src="#IMG_RELPATH#" alt="#IMG_TXT#" border="0" #IMG_WH#></a><p style="width: #IMG_WIDTH#px">#IMG_BIG_TXT#</p></div>
EOT;

//	$tpl_ex = $tpl_img_content;

	return imgwrapped_or_autoresize($row, "IMG_CONTENT_LEFT", "every", $tpl_ex, $tpl_nex);
}

function article_every_right($row) {
	global $tpl_img_content;
	
	$tpl_nex = "";
	$tpl_ex = <<< EOT
<div class=image style="margin-left: 1em"><a href="#IMG_POPUPHREF#"><img src="#IMG_RELPATH#" alt="#IMG_TXT#" border="0" #IMG_WH#></a><p style="width: #IMG_WIDTH#px">#IMG_BIG_TXT#</p></div>
EOT;

//	$tpl_ex = $tpl_img_content;

	return imgwrapped_or_autoresize($row, "IMG_CONTENT_RIGHT", "every", $tpl_ex, $tpl_nex);
}



function shop_map($row) {
	$tpl_ex = "<div class=image style='width: #IMG_WIDTH#;'><a href=shop-print.php?id=#ID# target=_blank><img src='#IMG_RELPATH#' alt='#IDENT#' #IMG_WH#></a><div align=right><a href=shop-print.php?id=#ID# target=_blank>#IMG_BIG_TXT#</a></div></div>";
//	return autoresize($row, "IMG_SHOPMAP", "first", $tpl_ex);
	return autoresize($row, "IMG_SHOPMAP", "every", $tpl_ex);
}

function setcontext_item() {
	global $entity, $id, $bo_href, $href_mmenu_upper_level;

	$bo_href = "$entity-edit.php?id=$id";
//	if ($id == 0) redirect($href_mmenu_upper_level, 0);
}




function person_first($row) {
//	$tpl_ex = "<div class=image style='width: #IMG_WIDTH#;'><a href=#ENTITY#.php?id=#ID#><img src='#IMG_RELPATH#' alt='#BRAND_IDENT# #IDENT#' #IMG_WH#></a></div>";
	$tpl_ex = "<div class=image style='width: #IMG_WIDTH#;'><img src='#IMG_RELPATH#' alt='#IDENT#' #IMG_WH#></div>";
	return autoresize($row, "IMG_PERSON", "first", $tpl_ex);
}

function certificate_every($row) {
	$tpl_ex = <<< EOT
<a href="#IMG_POPUPHREF#"><img src="#IMG_RELPATH#" alt="#IMG_TXT#" border="0" #IMG_WH#></a>
EOT;
	$tpl_ex = <<< EOT
<div class="image" style="float: left; margin-right: 5px; margin-bottom: 5px;"><a href="#IMG_POPUPHREF#"><img src="#IMG_RELPATH#" alt="#IMG_TXT#" border="0" #IMG_WH# style="border: 1px solid #999999;"></a><!--p style="width: #IMG_WIDTH#px">#IMG_BIG_TXT#</p--></div>
EOT;
	$tpl_nex = "";

	return autoresize($row, "IMG_CERTIFICATE", "every", $tpl_ex, $tpl_nex);
}

function consultant_first($row) {
//	$tpl_ex = "<div class=image style='width: #IMG_WIDTH#;'><a href=#ENTITY#.php?id=#ID#><img src='#IMG_RELPATH#' alt='#BRAND_IDENT# #IDENT#' #IMG_WH#></a></div>";

	$tpl_nex = <<< EOT
<div class=image>
<a href="faq.php?consultant=#ID#">
<div style='width: 102; height: 112; border: 1px solid gray; vertical-align: bottom; text-align: center; padding:5' title='авторесайз: у элемента [consultant:2] не залито ни одной большой картинки [IMG_CONSULTANT]:[first]'><br><br>изображение недоступно</div>
</a>
<p style="width: #IMG_WIDTH#px">#POSITION#</p>
</div>
EOT;

	$tpl_ex = <<< EOT
<div class=image style='width: #IMG_WIDTH#;'><a href="faq.php?consultant=#ID#"><img src='#IMG_RELPATH#' alt='#IDENT#' #IMG_WH#></a><p style="width: #IMG_WIDTH#px">#POSITION#</p></div>
EOT;

	return autoresize($row, "IMG_CONSULTANT", "first", $tpl_ex, $tpl_nex);
}

function consultant_every($row) {
//	$tpl_ex = "<div class=image style='width: #IMG_WIDTH#;'><a href=#ENTITY#.php?id=#ID#><img src='#IMG_RELPATH#' alt='#BRAND_IDENT# #IDENT#' #IMG_WH#></a></div>";

	$tpl_nex = <<< EOT
<div class=image>
<div style='width: 102; height: 112; border: 1px solid gray; vertical-align: bottom; text-align: center; padding:5' title='авторесайз: у элемента [consultant:2] не залито ни одной большой картинки [IMG_CONSULTANT]:[first]'><br><br>изображение недоступно</div>
<p style="width: 102px">#IDENT#</p>
</div>
EOT;

	$tpl_ex = <<< EOT
<div class=image><a href="#IMG_POPUPHREF#"><img src="#IMG_RELPATH#" alt="#IMG_TXT#" border="0" #IMG_WH#></a><p style="width: #IMG_WIDTH#px">#IDENT#</p></div>
EOT;
	return autoresize($row, "IMG_CONSULTANT", "every", $tpl_ex, $tpl_nex);
}

function paperman_first($row) {
//	$tpl_ex = "<div class=image style='width: #IMG_WIDTH#;'><a href=#ENTITY#.php?id=#ID#><img src='#IMG_RELPATH#' alt='#BRAND_IDENT# #IDENT#' #IMG_WH#></a></div>";
	$tpl_ex = "<div class=image style='width: #IMG_WIDTH#;'><img src='#IMG_RELPATH#' alt='#IDENT#' #IMG_WH#></div>";
	return autoresize($row, "IMG_PERSON", "first", $tpl_ex);
}


function imgwrapped_or_autoresize($entity_row, $imgtype_hashkey, $autoresize_type, $tpl_ex, $tpl_nex, $check_exists = 1, $may_modify_tpl = 1, $imgwrap0_autoresize1 = 1) {
	global $debug_query;
	global $entity, $id;

	$ret = "";
	$imgtype_row = select_entity_row(array("hashkey" => $imgtype_hashkey), "imgtype");

	$entity_saved = $entity;
	$id_saved = $id;

	$entity = $entity_row["entity"];
	$id = $entity_row["id"];


	$img_wrapped = array();
	if ($imgwrap0_autoresize1 == 1) {
		if ($imgtype_row[$autoresize_type . "_autoresize_apply"] == 1) {
			$ret = autoresize($entity_row, $imgtype_hashkey, $autoresize_type, $tpl_ex, $tpl_nex);
		} else {
//			$debug_query = 1;
			if ($may_modify_tpl == 1) $tpl_ex = str_replace("#IDENT#", "#IMG_TXT#", $tpl_ex);
			$img_wrapped = prepare_img($tpl_ex, $imgtype_hashkey, $entity_row["id"], $entity_row["entity"], $check_exists, $may_modify_tpl);
//			$debug_query = 0;
//			pre($img_wrapped);
			foreach ($img_wrapped as $img => $tag) {
				$ret .= $tag;
				if (strpos($autoresize_type, "first") !== false) break;
			}
		}
	} else {
		$tpl_ex_modified = $tpl_ex;
		if ($may_modify_tpl == 1) $tpl_ex_modified = str_replace("#IDENT#", "#IMG_TXT#", $tpl_ex);
//		$debug_query = 1;
		$img_wrapped = prepare_img($tpl_ex_modified, $imgtype_hashkey, $entity_row["id"], $entity_row["entity"], $check_exists, $may_modify_tpl);
//		$debug_query = 0;
		pre($img_wrapped);
		foreach ($img_wrapped as $img => $tag) {
			$ret .= $tag;
			if (strpos($autoresize_type, "first") !== false) break;
		}

		if ($ret == "" && $imgtype_row[$autoresize_type . "_autoresize_apply"] == 1) {
			$ret = autoresize($entity_row, $imgtype_hashkey, $autoresize_type, $tpl_ex, $tpl_nex);
		}
	}

	$entity = $entity_saved;
	$id = $id_saved;

	return $ret;
}



function bgcolor_graywhite($row) {
	$ret = "";
	$ret = ($row["i"] % 2 == 0) ? "#E8ECEF" : "#E8EDF0";
	return $ret;
}

function ident_cdep($row, $tpl_content = "<a href='#ENTITY#.php?id=#ID#'>#IDENT#</a>", $tpl_nocontent = "#IDENT#") {
	global $ident_cdep_content, $ident_cdep_nocontent;

	$tpl = (isset($ident_cdep_nocontent) && $ident_cdep_nocontent != "") ? $ident_cdep_nocontent : $tpl_nocontent;
	$content = strtolower($row["content"]);
	if ($content != "" && $content != "<p>&nbsp;</p>") {
		$tpl = (isset($ident_cdep_content) && $ident_cdep_content != "") ? $ident_cdep_content : $tpl_content;
	}
	return hash_by_tpl($row, $tpl);
}

function brief_cdep($row, $tpl_content = "#BRIEF#<br><a href='#ENTITY#.php?id=#ID#'>читать дальше</a>", $tpl_nocontent = "#BRIEF#") {
	global $brief_cdep_content, $brief_cdep_nocontent;

	$tpl = (isset($brief_cdep_nocontent) && $brief_cdep_nocontent != "") ? $brief_cdep_nocontent : $tpl_nocontent;
	$content = strtolower($row["content"]);
	if ($content != "" && $content != "<p>&nbsp;</p>") {
		$tpl = (isset($brief_cdep_content) && $brief_cdep_content != "") ? $brief_cdep_content : $tpl_content;
	}
	$ret = hash_by_tpl($row, $tpl);
	return $ret;
}

function article_dep($row) {
	$ret = ($row["article"] != "") ? hash_by_tpl($row, "#ARTICLE#") : "";
	return $ret;
}


function prevnext_product_table90($row, $prevnext_fixed = array(), $prev_ident = "предыдущий товар", $next_ident = "следующий товар", $return_href) {
	global $entity;

	$ret = "";
	$entity_ = isset($row["entity"]) ? $row["entity"] : $entity;

	$tpl_hash = array(
		"prev" => "<table><tr><td>&lt;&lt;&nbsp;</td><td><a href='#SCRIPT_NAME#?id=#ID#&#FIXED_SUFFIX#' title='#IDENT#'>$prev_ident</a>&nbsp;(#CNT#)</td></tr><tr><td></td><td><h5>#IDENT#</h5></td></tr></table>",
		"prev_empty" => "",
		"next" => "<table><tr><td align=right>(#CNT#)&nbsp;<a href='#SCRIPT_NAME#?id=#ID#&#FIXED_SUFFIX#' title='#IDENT#'>$next_ident</a></td><td>&nbsp;&gt;&gt;</td></tr><tr><td align=right><h5>#IDENT#</h5></td><td></td></tr></table>",
		"next_empty" => ""
		);

	$tpl_hash = array(
		"prev" => "&laquo;&nbsp;<a href='#SCRIPT_NAME#?id=#ID##FIXED_SUFFIX#' title='#IDENT#'>$prev_ident</a>&nbsp;(#CNT#)",
		"prev_empty" => "",
		"next" => "(#CNT#)&nbsp;<a href='#SCRIPT_NAME#?id=#ID##FIXED_SUFFIX#' title='#IDENT#'>$next_ident</a>&nbsp;&raquo;",
		"next_empty" => ""
		);

	$prevnext_hash = select_entity_prevnext(
//			array($group_entity => $row[$group_entity], "published" => 1, "deleted" => 0),
			array_merge($prevnext_fixed, array("published" => 1, "deleted" => 0)),
			$entity_, $row["id"], $tpl_hash, $prevnext_fixed);

	 $tpl = <<< EOT
<table width=85% align=center>
<tr>
	<td width=40% align=left>#PREV#</td>
	<td width=20% align=center nowrap>$return_href</td>
	<td width=40% align=right>#NEXT#</td>
</tr>
</table>
EOT;
	
	$ret = hash_by_tpl($prevnext_hash, $tpl);

	return $ret;
}



function formatted_price($row, $price_column = 1) {
	$ret = "";

	$cur = $row["currency" . $price_column . "_ident"];
	$price = $row["price_" . $price_column . ""];
	$price = number_format($price, 0, '', ' ');
	if ($price == 0) {
		$ret = "<b class='price'>звоните</b>";
	} else {
		$ret = "<b class='price'>$price</b> $cur";
	}

	return $ret;
}

function formatted_price_2($row) { return formatted_price($row, 2); }
function formatted_price_3($row) { return formatted_price($row, 3); }


function product_imgtag($row, $imgtype = 3, $img_pcard_width = 190, $img_pcard_height = 0, $entity_ = "product", $field_from = "img_big") {
	global $upload_relpath, $upload_path;
	$ret = "";
	
//cleanup if resized older than
	$img_overwrite_ts = mktime (19, 45, 00, 11, 28, 2004);
//cleanup old-dimensioned resizes
	$old_img_pcard_width = 190;
	$old_img_pcard_height = 120;

	$id_ = $row["id"];
	$ident_alt = str_replace("\r\n", " ", $row["ident"]);

	$pcard_img_row = array();

	$query = "select * from img where owner_entity='$entity_' and owner_entity_id='$id_' and imgtype='$imgtype' order by id";
	$result = mysql_query($query) or die("SELECT PRODIMG failed:<br>$query:<br>" . mysql_error());

	while ($img_row = mysql_fetch_assoc($result)) {
		$good_ext = 0;

		$matches = array();
		preg_match ("/\.(\w*)$/", $img_row[$field_from], $matches);
		if (isset($matches[1])) {
			$ext = strtolower($matches[1]);

			switch ($ext) {
				case "jpg":
				case "jpeg":
				case "gif":
					$good_ext = 1;
					break;

				default:
			}
		}
		if ($good_ext == 0) continue;

		$pcard_img_row["IMG_PCARD_RELPATH"] = img_relpath($img_row, $field_from);
		$pcard_img_row["IMG_PCARD_WH"] = " width=$img_pcard_width height=$img_pcard_height ";
		if (img_exists($img_row, $field_from)) {
			$pcard_from_absname = $upload_path . $pcard_img_row["IMG_PCARD_RELPATH"];
			$img_size = getimagesize($pcard_from_absname);
			$pcard_img_row["IMG_PCARD_WH"] = $img_size[3];
		} else {
//			pre("source img does not exists: [" . img_exists($img_row, $field_from) . "]");
			$assumed_img_pcard_height = ($img_pcard_height != 0) ? $img_pcard_height : intval($img_pcard_width * 0.75);
			$pcard_img_row["IMG_PCARD_RELPATH"] = "blank-nonexistent.gif";
			$pcard_img_row["IMG_PCARD_WH"] = " width='$img_pcard_width' height='$assumed_img_pcard_height' style='border-color:gray'";
		}

//		pre($img_row);
		if (img_exists($img_row, $field_from) == 1 && ($img_pcard_width > 0 || $img_pcard_height > 0)) {
			$pcard_fname = img_resized_fname($img_row, $field_from, "pcard", $img_pcard_width, $img_pcard_height);
			$pcard_img_row = array_merge($img_row, array("img_pcard" => $pcard_fname));
//			pre($pcard_img_row);
			
			$pcard_from_absname = $upload_path . img_relpath($pcard_img_row, $field_from);
			$pcard_from_abspath = dirname($pcard_from_absname) . "/";
			
			$pcard_dst_absname = $pcard_from_abspath . $pcard_fname;

			$should_resize = 1;

			if (file_exists($pcard_dst_absname)) {
				clearstatcache();
				$resize_mtime = filemtime($pcard_dst_absname);
//				pre("product_imgtag($pcard_dst_absname)\nresize_mtime?img_overwrite_ts\n" . $resize_mtime . "?" . ($img_overwrite_ts) . "\n" . date ("d-m-Y G:i:s", filemtime($pcard_dst_absname)) . "?". date ("d-m-Y G:i:s", $img_overwrite_ts)) . "\n\n";
				if (intval($resize_mtime) > intval($img_overwrite_ts)) {
					$should_resize = 0;
//					pre ("should_resize = $should_resize");
				}
			}
			
			if ($should_resize == 1) {
				pre("should get new resize for " . basename($pcard_dst_absname));

				img_resize($pcard_from_abspath, $pcard_img_row[$field_from],
						$img_pcard_width, $img_pcard_height, $pcard_fname);
			}
	
			if (file_exists($pcard_dst_absname)) {
				$img_size = getimagesize($pcard_dst_absname);
	
				$pcard_img_row["IMG_PCARD_RELPATH"] = img_relpath($pcard_img_row, "img_pcard");
				$pcard_img_row["IMG_PCARD_WH"] = $img_size[3];
			} else {
				pre("pcard_resize does not exists");
				$assumed_img_pcard_height = ($img_pcard_height != 0) ? $img_pcard_height : intval($img_pcard_width * 0.33);
				$pcard_img_row["IMG_PCARD_RELPATH"] = "blank-nonexistent.gif";
				$pcard_img_row["IMG_PCARD_WH"] = " width='$img_pcard_width' height='$assumed_img_pcard_height'";
			}
	

//http://localhost:8114/upload/product/479/img/62/pcard_190x120-isuzu_2.jpg
			$pcard_delold_fname = img_resized_fname($img_row, $field_from, "pcard", $old_img_pcard_width, $old_img_pcard_height);
			$old_absname = $pcard_from_abspath. $pcard_delold_fname;
			if (file_exists($old_absname)) {
				pre("should delete old-dimensioned resize " . basename($old_absname));
				unlink($old_absname);
			}
			
			break;
		}
	}
	
//	pre($pcard_img_row);
	$tpl = "<img src='$upload_relpath#IMG_PCARD_RELPATH#' border='0' alt='$ident_alt' #IMG_PCARD_WH# vspace=4>";
	$ret = hash_by_tpl($pcard_img_row, $tpl);

	return $ret;
}



function hrefto_catalog_or_pgroup($row) {
	$ret = "";
	
	$child_pgroup_cnt = select_field("count(id)", array("parent_id" => $row["id"], "xt_published" => 1, "deleted" => 0), "pgroup");
	$hrefentity = ($child_pgroup_cnt > 0) ? "catalog" : "pgroup";
	$ret = "$hrefentity.php?pgroup=" . $row["id"];

	return $ret;
}

function pgroup_ident($row) {
	$ret = "";
	
//	$ret = preg_replace("/(\d+)?(\.)?(.*)/", "\$3", $row["ident"]);

	$tpl = "#IDENT#";

//	$tpl = (isset($row["product_cnt"]) && $row["product_cnt"] > 0)
//		? "#IDENT#&nbsp;(#PRODUCT_CNT#)"
//		: "#IDENT#";

	$ret = hash_by_tpl($row, $tpl);

	return $ret;
}


function hrefto_jsopen_or_pgroup1($row) {
	global $entity;
	$ret = "";
	
	$product_in_pgroup_cnt = isset($row["product_cnt"])
		? $row["product_cnt"]
		: select_field("count(id)", array("pgroup" => $row["id"], "published" => 1, "deleted" => 0), "product")
		;

	$pgroup_in_pgroup_cnt = isset($row["child_pgroup_cnt"])
		? $row["child_pgroup_cnt"]
		: select_field("count(id)", array("parent_id" => $row["id"], "published" => 1, "deleted" => 0), "pgroup")
		;

//	$ret = ($product_in_pgroup_cnt > 0 || $row["is_plain"] == 1)

	if ($pgroup_in_pgroup_cnt > 0) {
		$ret = "javascript:layer_switch(" . $row["i"] . ")";
	} else {
		$ret = ($product_in_pgroup_cnt > 0)
			? "pgroup.php?id=" . $row["id"]
//			: "javascript:layer_switch(" . $row["i"] . ")"
//			: "javascript:alert('в подгруппе нет элементов; такую подгруппу лучше убрать с сайта, сняв в бэкоффисе галочку [опубликовано]')"
			:  "pgroup.php?id=" . $row["id"]
		;
	}

	if ($entity == "pricelist") {
		$ret = "pricelist-pgroup.php?id=" . $row["id"];
	
	}

	return $ret;
}

function hrefto_jsopen_or_pgroup2($row) {
	$ret = "";
	
	$product_in_pgroup_cnt = isset($row["product_cnt"])
		? $row["product_cnt"]
		: select_field("count(id)", array("pgroup" => $row["id"], "published" => 1, "deleted" => 0), "product")
		;

//	$ret = ($product_in_pgroup_cnt > 0)
//		? "product-list.php?pgroup=" . $row["id"]
//		: "javascript:ilayer_switch(" . '"' . $row["parent_id"] . "_" . $row["i"] . '"' . ")"
//		;

	$ret = ($product_in_pgroup_cnt > 0)
		? "pgroup.php?id=" . $row["id"]
		: "javascript:ilayer_switch(" . '"' . $row["parent_id"] . "_" . $row["i"] . '"' . ")"
		;

	return $ret;
}

function hrefto_jsopen_or_supplier($row) {
	global $entity;
	$ret = "";
	
	$product_in_supplier_cnt = isset($row["product_cnt"])
		? $row["product_cnt"]
		: select_field("count(id)", array("supplier" => $row["id"], "published" => 1, "deleted" => 0), "product")
		;

	$ret = ($product_in_supplier_cnt > 0)
		? "supplier.php?id=" . $row["id"]
		: "supplier.php?id=" . $row["id"]
//		: "javascript:layer_switch(" . $row["i"] . ")"
//		: "javascript:alert('нет товаров этого производителя; такого производителя лучше убрать с сайта, сняв в бэкоффисе галочку [опубликовано]')"
		;

	if ($entity == "pricelist") {
		$ret = "pricelist-supplier.php?id=" . $row["id"];
	
	}

	return $ret;
}

function banner_list_from_brief($banner_qa,
		$tpl_banner_href = "<div class='banner_v'><a @banner_hrefto@>#BANNER_BRIEF#</a></div>",
		$tpl_banner_nohref = "<div class='banner_v'>#BANNER_BRIEF#</div>",
		$tpl_img_banner = "<img src='#IMG_RELPATH#' border='0' #IMG_WH# alt='#BANNER_IDENT#'>",
		$tpl_img_banner_popup = "#IMG_POPUPHREF#"
	) {
	global $debug_query;
	$ret = "";
	
	foreach ($banner_qa as $banner_row) {
		$banner_row["entity"] = "banner";
		$banner_id = $banner_row["id"];
		$banner_ident = $banner_row["ident"];
	
		$tpl_img_banner = hash_by_tpl(array("banner_ident" => $banner_ident), $tpl_img_banner);
	
		$banner_img_wrapped = prepare_img($tpl_img_banner, "IMG_CONTENT", $banner_id, "banner");
//		pre($banner_img_wrapped);
		$banner_brief = hash_by_tpl($banner_row, "#BRIEF#", "banner");
		if ($banner_brief == "" || $banner_brief == "<P>&nbsp;</P>") {
			$banner_brief = "";
			if (count(array_keys($banner_img_wrapped)) > 0) {
				$bshown = 0;
				if (isset ($banner_row["bshown"])) $bshown = $banner_row["bshown"];
				$bshown = intval($bshown);

				$i = 1;
				foreach ($banner_img_wrapped as $key => $banner_img) {
					$banner_brief .= "#" . strtoupper($key) . "#";
					if ($bshown > 0 && $i > $bshown) break;
					$i++;
				}
			}
		}
		$banner_brief = hash_by_tpl($banner_img_wrapped, $banner_brief);
	
		$banner_row["banner_brief"] = $banner_brief;
	
		$tpl_banner = (banner_hrefto($banner_row) != "") ? $tpl_banner_href : $tpl_banner_nohref;
		$ret .= hash_by_tpl($banner_row, $tpl_banner);



		$first_popuphref = "";
		$banner_img_wrapped = prepare_img($tpl_img_banner_popup, "IMG_CONTENT", $banner_id, "banner");
//		pre($banner_img_wrapped);
		foreach ($banner_img_wrapped as $img_nr => $banner_img_popuphref) {
			$first_popuphref = $banner_img_popuphref;
			break;
		}
//		pre($first_popuphref);
//		pre($banner_brief);
		$ret = hash_by_tpl(array("first_popuphref" => $first_popuphref), $ret);


		if (isset($banner_row["hits"])) {
			$hits = intval($banner_row["hits"]) + 1;
			update(array("hits" => $hits), array("id" => $banner_row["id"]), "banner");
		}
	}

	return $ret;
}

function banner_hrefto($row) {
	$ret = "";
	
	$hrefto = "";
	if ($row["content"] != "" && $row["content"] != "<P>&nbsp;</P>") $hrefto = "banner.php?id=" . $row["id"];
	if ($row["hrefto"] != "") $hrefto = $row["hrefto"];
	
	if ($hrefto != "") $ret = "href='$hrefto'";

	return $ret;
}

function add_mover_preload($row) {
	global $mover_preload_list;

	if ($mover_preload_list != "") $mover_preload_list .= ",";
	
	$mover_hash = entityrow_imgprepare("img_mover", $row);
	$mover_preload_list .= hash_by_tpl($mover_hash, "'#IMG_MOVER_RELPATH#'");
}

function mmenuleaf_anchor($hashkey
//	, $tpl_item = "<a href='@mmenu_hrefto@'><img src='#IMG_FREE_RELPATH#' #IMG_FREE_WH# border=0 alt='#IDENT#'></a>"
//	, $tpl_item_current = "<a href='@mmenu_hrefto@'><img src='#IMG_MOVER_RELPATH#' #IMG_FREE_WH# border=0 alt='#IDENT#'></a>"
//	, $tpl_item_separator = "<td><img src='img/m_shad_#I#.gif' alt=''></td>"
//	, $tpl_item_current_separator = "<td><img src='img/m_shad_2.gif' alt=''></td>"

	, $tpl_item = ""
	, $tpl_item_current = ""
	, $current = 0

	, $tpl_item_separator = ""
	, $tpl_item_current_separator = ""

	, $item_end = ""
		) {

/*
	$tpl_item = <<< EOT

<td><a href="@mmenu_hrefto@"><img src="#IMG_FREE_RELPATH#" #IMG_FREE_WH# name="MMENU_#ID#" onMouseOver="MM_swapImage('MMENU_#ID#','','#IMG_MOVER_RELPATH#',1)" onMouseOut="MM_swapImgRestore()" alt="#IDENT#"></a></td>

EOT;

	$tpl_item_current = <<< EOT

<td><a href="@mmenu_hrefto@"><img src="#IMG_MOVER_RELPATH#" #IMG_MOVER_WH# border=0 alt="#IDENT#"></a></td>

EOT;
*/

	global $root_tree;

	if ($current == 0) {
		$current = isset($root_tree[1]) ? $root_tree[1] : 0;
	}

	$ret = "";
	
	$parent_id = intval($hashkey);
	$parent_wcond = ($parent_id > 0) ? "id=$hashkey" : "hashkey='$hashkey'";
	
	$query = "select m.* from "
		. " " . TABLE_PREFIX . "mmenu m, " . TABLE_PREFIX . "mmenu parent"
		. " where parent.$parent_wcond and m.parent_id=parent.id"
		. " and m.published=1 and m.deleted=0"
		. " order by m.manorder";
	
	$qa = select_queryarray($query);
	foreach ($qa as $row) {
		$row["entity"] = "mmenu";
		$row = array_merge($row, entityrow_imgprepare("img_free", $row));
		$row = array_merge($row, entityrow_imgprepare("img_mover", $row));

		$tpl = ($row["id"] == $current) ? $tpl_item_current : $tpl_item;
		$ret .= hash_by_tpl($row, $tpl);

		if ($row["i"] < $row["rows_total"]) {
//			$tpl = ($row["id"] == $current) ? $tpl_item_current_separator : $tpl_item_separator;
			$tpl = $tpl_item_separator;
			$ret .= hash_by_tpl($row, $tpl);
		}
		if ($row["i"] == $row["rows_total"]) $ret .= hash_by_tpl($row, $item_end);
	}

	return $ret;
}

function mmenu_hrefto($row) {
	$ret = "";

	if ($row["is_drone"] == 1) {
		$ret = "#";		// feature to set "drone" but not clickable
		$row_child = select_entity_row(array("parent_id" => $row["id"], "published" => 1, "deleted" => 0), "mmenu");
		if (isset($row_child["id"])) $ret = mmenu_hrefto($row_child);
		return $ret;
	}

	if ($row["is_heredoc"] == 0) {
		$hashkey = $row["hashkey"];

		$controlchar = "";
		$len = strlen($hashkey);
		if ($len > 1) $controlchar = substr($hashkey, 0, 1);
	
		switch($controlchar) {
			case "=":
				$ret = substr($hashkey, 1);
				break;
	
			default:
				$ret = 	$hashkey . ".php";
		}
	
	} else {
		$ret = 	"mmenu.php?id=" . $row["id"];
	}

	return $ret;
}

function entityrow_imgprepare($imgfield, $row, $default_blank = "/img/blank.gif", $check_exists = 1) {
	global $upload_relpath, $debug_img;

	$hk_relpath = $imgfield . "_relpath";
	$hk_abspath = $imgfield . "_abspath";
	$hk_width = $imgfield . "_width";
	$hk_height = $imgfield . "_height";
	$hk_wh = $imgfield . "_wh";
	$hk_alt = $imgfield . "_alt";
	$hk_exists = $imgfield . "_exists";

	$ret = array(
		$hk_relpath => "",
		$hk_abspath => "",
		$hk_width => "",
		$hk_height => "",
		$hk_wh => "",
		$hk_alt => "",
		$hk_exists => 0
	);

	$relpath = $default_blank;
	$abspath = $_SERVER["DOCUMENT_ROOT"] . $relpath;

	$alt = "";
	list ($width, $height, $type, $wh) = array(0, 0, 0, "");

	$imgname = $row[$imgfield];
	if ($imgname == "") {
		if ($debug_img == 1) $alt = "entityrow_imgprepare($imgfield): field is empty";
	} else {
		$relpath = $upload_relpath . $row["entity"] . "/" . $row["id"] . "/" . $imgname;
		$abspath = $_SERVER["DOCUMENT_ROOT"] . $relpath;

		if (is_file($abspath)) {
			$ret[$hk_exists] = 1;
			list ($width, $height, $type, $wh) = getimagesize($abspath);
		} else {
			if ($debug_img == 1) $alt = "entityrow_imgprepare($imgfield): file does not exists: " . $abspath;
			if ($check_exists == 1) $relpath = $default_blank;
		}
	}
	
	if (is_file($abspath)) list ($width, $height, $type, $wh) = getimagesize($abspath);

	$ret[$hk_relpath] = $relpath;
	$ret[$hk_abspath] = $abspath;
	$ret[$hk_width] = $width;
	$ret[$hk_height] = $height;
	$ret[$hk_wh] = $wh;
	$ret[$hk_alt] = $alt;
	
//	pre($ret);

	return $ret;
}

function date_published($row) {
	global $months_when;
	$ret = "";

	$date_hash = parse_datetime($row["date_published"]);
//	pre($row["date_published"]);
//	pre($date_hash);
	$day = intval($date_hash["day"]);
	$month = intval($date_hash["month"]);
	$year = intval($date_hash["year"]);
	$ret = $day . " " . $months_when[$month] . " " . $year;

	return $ret;
}

function date_published_noyear($row) {
	global $months_when;
	$ret = "";

	$date_hash = parse_datetime($row["date_published"]);
//	pre($row["date_published"]);
//	pre($date_hash);
	$day = intval($date_hash["day"]);
	$month = intval($date_hash["month"]);
	$ret = $day . " " . $months_when[$month];

	return $ret;
}

function date_published_shortyear($row, $months_mnem_type = "long") {
	global $months_when, $months_when_short;
	$ret = "";
	
	$months_when_local = ($months_mnem_type == "long") ? $months_when : $months_when_short;

	$date_hash = parse_datetime($row["date_published"]);
//	pre($row["date_published"]);
//	pre($date_hash);
	$day = intval($date_hash["day"]);
	$month = intval($date_hash["month"]);
	$year = substr($date_hash["year"], 2, 2);
	$ret = $day . " " . $months_when_local[$month] . " " . $year;

	return $ret;
}

function date_published_num($row) {
	global $months_when;
	$ret = "";

	$date_hash = parse_datetime($row["date_published"]);
//	pre($row["date_published"]);
//	pre($date_hash);
	$day = $date_hash["day"];
	$month = $date_hash["month"];
	$year = $date_hash["year"];
	$ret = $day . "." . $month . "." . $year;

	return $ret;
}

function date_published_GMT($row, $field_from = "date_published") {
	global $months_when;
	$ret = "";

	$date_hash = parse_datetime($row[$field_from]);
	if ($date_hash["hour"] == "00" && $date_hash["minute"] == "00" && $date_hash["second"] == "00") {
		$date_hash["hour"] = "13";
	}
//	pre($date_hash);

	$date_uts = datehash_2uts($date_hash);
	$ret = strftime("%a, %d %b %Y %H:%M:%S +0300", $date_uts);		//Sat, 14 Jan 2006 19:55:00 +0300

	return $ret;
}


function date_updated_shortyear($row) {
	$ret = date_published_shortyear(array("date_published" => $row["date_updated"]), "short");
	$ret = "<nobr>" . $ret . "</nobr>";
	return $ret;
}

function date_started($row) {
	return date_published_shortyear(array("date_published" => $row["date_started"]));
}

function date_finished($row) {
	return date_published_shortyear(array("date_published" => $row["date_finished"]));
}


function sendorder($body) {
	global $path_back;
	
	$order_subscriber = select_field("content", array("hashkey" => "MAILTO_ORDER_ALL"), "constant");
	$subject = strip_tags($path_back);
//	$subject = html_entity_decode($subject);
	$body = "<p>$subject</p>" .  $body;

	sendmail($order_subscriber, $subject, $body);
//	pre ($body);

	$ret = "Спасибо за ваш вопрос...";

	return $ret;
}

//$counter_top = entity_tpl("#CONTENT#", "constant", array("hashkey" => "COUNTER_TOP", "published" => 1));
$counter_bottom = entity_tpl("#CONTENT#", "constant", array("hashkey" => "COUNTER_BOTTOM", "published" => 1));

//$counter_top_index = entity_tpl("#CONTENT#", "constant", array("hashkey" => "COUNTER_TOP_INDEX", "published" => 1));
//$counter_bottom_index = entity_tpl("#CONTENT#", "constant", array("hashkey" => "COUNTER_BOTTOM_INDEX", "published" => 1));


$content_top = entity_tpl("#CONTENT#", "constant", array("hashkey" => "CONTENT_TOP"));
$content_bottom = entity_tpl("#CONTENT#", "constant", array("hashkey" => "CONTENT_BOTTOM"));
//$content_logo = entity_tpl("#CONTENT#", "constant", array("hashkey" => "CONTENT_LOGO"));

//$mmenu_id_idx = select_field("id", array("hashkey" => "MMENU_INDEX"), "mmenu");
//$mmenu_id_top = select_field("id", array("hashkey" => "MMENU_TOP"), "mmenu");
//$mmenu_id_left = select_field("id", array("hashkey" => "MMENU_LEFT"), "mmenu");

//$banner_exposure_index = entity_tpl("#CONTENT#", "constant", array("hashkey" => "BANNER_EXPOSURE_INDEX"));
//$banner_exposure_index = intval($banner_exposure_index);
//if ($banner_exposure_index == 0) $banner_exposure_index = 5;

$content_bottom_addon = entity_tpl("#CONTENT#", "constant", array("hashkey" => "CONTENT_BOTTOM_ADDON"));

$antispam_question = entity_tpl("#CONTENT#", "constant", array("hashkey" => "ANTISPAM_QUESTION", "published" => 1));
$antispam_answer_ethalon = entity_tpl("#CONTENT#", "constant", array("hashkey" => "ANTISPAM_ANSWER_ETHALON", "published" => 1));
$antispam_wrong_msg = entity_tpl("#CONTENT#", "constant", array("hashkey" => "ANTISPAM_WRONG_MSG", "published" => 1));



$mover_preload_list = "";




$uprofile_default = array (
	"rows_per_page_table" => 10,
	"rows_per_page_mosaic" => 18,
	"currency" => 2,
	"listview" => 0,
);

$uprofile_default = array (
	"listview" => 0,
	$site_ident . "_idrandom" => "",
);


$uprofile = gethash_bytplhash($uprofile_default, 1, 1);


//pre($_REQUEST);
//pre($_GET);
//pre($_POST);
//pre($_COOKIE);
//plog(pr($uprofile));


$unhashed = array (
	"customer" => 0,
	"customer_ident" => "",
	"customer_ident_short" => "",
	"customer_email" => "",
	"customer_contract_discount" => 0,
	"customer_manager_name" => "",
	"customer_manager_email" => "",
	"customer_contact" => "",
	"customer_row" => array(),
);

//$debug_query = 1;
$unhashed["customer"] = unhash_id($uprofile[$site_ident . "_idrandom"], "customer", "idrandom");
//$debug_query = 0;
//plog(pr($unhashed));

// seems to be not necessary, but indeed speeds up by not accessing mysql with cookie data
if (session_id() == "") session_start();
if (isset($_SESSION["customer"]) && $_SESSION["customer"] > 0) $unhashed["customer"] = $_SESSION["customer"];

//plog(pr($unhashed));

if ($mode == "logoff_customer") {
	setcookie("customer");
	$unhashed["customer"] = 0;
}

//$unhashed["eur_exchrate_rub"] = floatval(select_field("exchrate_rub", array("hashkey" => "EUR"), "currency"));
//$unhashed["usd_exchrate_rub"] = floatval(select_field("exchrate_rub", array("hashkey" => "USD"), "currency"));

//$debug_query = 1;
//$unhashed["eur_exchrate_rub"] = round(get_currency_exchrate_cbr("EUR"), 4);
//$unhashed["usd_exchrate_rub"] = round(get_currency_exchrate_cbr("USD"), 4);
//$debug_query = 0;


$user_registration_photo_min = entity_tpl("#CONTENT#", "constant", array("hashkey" => "USER_REGISTRATION_PHOTO_MIN"));
$user_registration_photo_min = intval($user_registration_photo_min);
if ($user_registration_photo_min == 0) $user_registration_photo_min = 1;

//pre("user_registration_photo_min=$user_registration_photo_min");


$ordinary_user_photo_limit = entity_tpl("#CONTENT#", "constant", array("hashkey" => "ORDINARY_USER_PHOTO_LIMIT"));
$ordinary_user_photo_limit = intval($ordinary_user_photo_limit);
if ($ordinary_user_photo_limit == 0) $ordinary_user_photo_limit = 30;


$customer_me = 0;
$customer_me_first = "";
$customer_row = select_entity_row(array("id" => $unhashed["customer"]), "customer");
unset($customer_row["comment"]);
if (isset($customer_row["id"])) {
	$customer_me = $unhashed["customer"];

	$customer_row["ident"] = hash_by_tpl($customer_row, "#IDENT#");
	$customer_row["ident"] = stripslashes($customer_row["ident"]);
	$unhashed["customer_row"] = $customer_row;

// 4 basket
	$unhashed["customer_ident"] = $customer_row["ident"];
	if ($unhashed["customer_ident"] == "") $unhashed["customer_ident"] = $customer_row["login"];
	$short_length = (strlen($unhashed["customer_ident"]) > 22) ? 22 : strlen($unhashed["customer_ident"]);
	$unhashed["customer_ident_short"] = substr($unhashed["customer_ident"], 0, $short_length) . "";

	$unhashed["customer_contract_discount"] = floatval($customer_row["contract_discount"]);


	$unhashed["customer_manager_name"] = $customer_row["manager_name"];
	if ($unhashed["customer_manager_name"] == "") $unhashed["customer_manager_name"] = $customer_row["manager2_name"];
	if ($unhashed["customer_manager_name"] == "") $unhashed["customer_manager_name"] = $customer_row["manager3_name"];

	$unhashed["customer_manager_email"] = $customer_row["manager_email"];
	if ($unhashed["customer_manager_name"] == "") $unhashed["customer_manager_name"] = $customer_row["manager2_email"];
	if ($unhashed["customer_manager_name"] == "") $unhashed["customer_manager_name"] = $customer_row["manager3_email"];

	$unhashed["customer_contact"] = $customer_row["contact"];
	if ($unhashed["customer_contact"] == "") $unhashed["customer_contact"] = $customer_row["phone"];

	$unhashed["customer_row"]["idrandom_hex"] = sprintf("%x", $unhashed["customer_row"]["idrandom"]);
	
	$unhashed["customer_first"] = customer_first($customer_row);
	$customer_me_first = $unhashed["customer_first"];
//	plog(pr($customer_row));


	$lastclick_datetime_datehash = parse_datetime($customer_row["date_lastclick"]);
	$lastclick_datetime_uts = datehash_2uts($lastclick_datetime_datehash);


	$update_hash = array (
		"date_lastclick" => "CURRENT_TIMESTAMP",
		"lastip" => $_SERVER["REMOTE_ADDR"],
		"lastsid" => session_id(),
	);
//	$debug_query = 1;
	update($update_hash, array("id" => $customer_row["id"]), "customer");
//	$debug_query = 0;

//	$unhashed["basket_content_constant"] = select_field("content", array("hashkey" => "BASKET_CONTENT_AUTHORIZED"), "constant");
	$unhashed["basket_content_constant"] = entity_tpl("#CONTENT#", "constant", array("hashkey" => "BASKET_CONTENT_AUTHORIZED"), $unhashed);



	$script_name_basename = basename($_SERVER["SCRIPT_NAME"]);
//	pre($script_name_basename);

/*
	$not_operable_until_photo_loaded_array = array (
		"search.php", "message_board.php", "person.php", "my_raise.php", "my_balance.php", "my_inbox.php", "mmenu.php"
		);

	if (strpos($_SERVER["SCRIPT_NAME"], "xml") !== false
		|| in_array($script_name_basename, $not_operable_until_photo_loaded_array)
		) {
	}
*/

//		$query_photo_cnt = "select count(id) as cnt from img"
//			. " where owner_entity='customer' and owner_entity_id=" . $customer_row["id"]
//			. " and imgtype=7 and published=1 and deleted=0";

		$query_photo_cnt = "select count(img.id) as cnt"
			. " from img img"
			. " inner join imgtype it on img.imgtype=it.id and it.hashkey='IMG_CUSTOMER'"
			. " where img.owner_entity='customer' and owner_entity_id=" . $customer_row["id"]
			. " and img.published=1 and img.deleted=0"
			. " and it.published=1 and it.deleted=0";

		$qa = select_queryarray($query_photo_cnt);
//		pre($qa);
		$photo_cnt = $qa[0]["cnt"];		

		$user_registration_photo_min = 0;
		$msg_upload_please = "Загрузите пожалуйста хотя бы одну свою фотографию";
		if ($script_name_basename == "my_photo.php" && $mode == "update") {
			if ($alertmsg == $msg_upload_please) $alertmsg = "";
		} else {
			if ($photo_cnt < $user_registration_photo_min) {
				if ($script_name_basename != "my_photo.php" && $script_name_basename != "logoff.php") {
					if ($script_name_basename == "_auth_frame.php") {
					} else {
						$hrefto = "my_photo.php?alertmsg="
							. urlencode($msg_upload_please);
						redirect($hrefto, 0);
					}
				}
			}
		}


} else {
	if (strpos($_SERVER["SCRIPT_NAME"], "my_") !== false) {
		$errormsg = "Пройдите пожалуйста авторизацию";
		redirect("auth.php?alertmsg=" . urlencode($errormsg));
	}

//	$unhashed["basket_content_constant"] = select_field("content", array("hashkey" => "BASKET_CONTENT_NOT_AUTHORIZED"), "constant");
	$unhashed["basket_content_constant"] = entity_tpl("#CONTENT#", "constant", array("hashkey" => "BASKET_CONTENT_NOT_AUTHORIZED"), $unhashed);
}



if (!isset($_SESSION["basket"])) $_SESSION["basket"] = array();


//$debug_query = 0;
//plog(pr($unhashed));
//plog(pr($_SESSION));
//plog(pr($_REQUEST));
//plog(pr($_COOKIE));





?>