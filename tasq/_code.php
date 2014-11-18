<? require_once "_code_once.php" ?>
<?

$root_mmenu_hashkey = "";
$root_mmenu_id = $id;

if ($entity == "mmenu") {
	$mmenu_hashkey = "";
	$mmenu_id = $id;
	$print_href = "mmenu.php?id=$id&print=1";
} else {
	if (!isset($mmenu_id)) {
		$mmenu_hashkey = $entity;

		$debug_query = 0;
// нет смысла создавать mmenu для avacancy-reply.php?id=2, но пусть пока так
		$mmenu_hashkey_eq = "=" . substr(strrchr($_SERVER["REQUEST_URI"], "/"), 1);
		if ($rewrite_engine_on == 1) $mmenu_hashkey_eq .= ".php";
		$mmenu_id = select_field("id", array("hashkey" => $mmenu_hashkey_eq, "deleted" => 0), "mmenu");

		if ($mmenu_id == "") {
			$mmenu_hashkey_eq = "=" . substr(strrchr($_SERVER["SCRIPT_FILENAME"], "/"), 1);
			$mmenu_id = select_field("id", array("hashkey" => $mmenu_hashkey_eq, "deleted" => 0), "mmenu");
		}

		if ($mmenu_id == "") {
			$mmenu_id = select_field("id", array("hashkey" => $mmenu_hashkey, "deleted" => 0), "mmenu");
		}
		$debug_query = 0;
	}
}

//echo "entity=[$entity] mmenu_id=[$mmenu_id]<br>";
$fromend_root_tree_content = select_root_tree_content("mmenu", $mmenu_id);
//pre($fromend_root_tree_content);
$root_tree_content = array_reverse($fromend_root_tree_content);
//pre($root_tree_content);

$fromend_root_tree = array_keys($fromend_root_tree_content);
$root_tree = array_reverse($fromend_root_tree);
$root_mmenu_id = $root_tree[0];
if (isset($root_tree[1])) $root_mmenu_id = $root_tree[1];
//print_r($root_tree);
//print_r($root_mmenu_id);

// для редиректа если что-то в развороте не так
$href_mmenu_upper_level = "/";
if (count($root_tree) > 1) {
	$mmenu_row_upper = $fromend_root_tree_content[$root_tree[count($root_tree)-2]];
//	pre($mmenu_row_upper);
	$href_mmenu_upper_level = mmenu_hrefto($mmenu_row_upper);
}


if (strpos($_SERVER["SCRIPT_FILENAME"], "print") !== false) $print = 1;


//$root_mmenu_row = select_entity_row(array("id" => $root_mmenu_id), "mmenu");

//$mmenu_row = select_entity_row(array("id" => $mmenu_id), "mmenu");
$mmenu_row = $fromend_root_tree_content[$mmenu_id];
//print_r($mmenu_row);
$mmenu_ident = hash_by_tpl($mmenu_row, "#ident#");
$mmenu_title = hash_by_tpl($mmenu_row, "#title#");
$mmenu_pagetitle = hash_by_tpl($mmenu_row, "#pagetitle#");
$mmenu_meta_keywords = hash_by_tpl($mmenu_row, "#meta_keywords#");
$mmenu_meta_description = hash_by_tpl($mmenu_row, "#meta_description#");

$mmenu_hashkey = $mmenu_row["hashkey"];
$mmenu_tpl_list_item = hash_by_tpl($mmenu_row, "#tpl_list_item#", "_global:entity", 1, 0);
$mmenu_tpl_list_wrapper = hash_by_tpl($mmenu_row, "#tpl_list_wrapper#", "_global:entity", 1, 0);

$mmenu_left0_right1 = $mmenu_row["left0_right1"];
$left0_right1 = $mmenu_left0_right1;

$article_row = array();
if ($mmenu_hashkey == "article") $article_row = select_entity_row();

if (isset($article_row["id"])) {
	$article_left0_right1 = $article_row["left0_right1"];
	$left0_right1 = $article_left0_right1;
}


if ($title == "") $title = $mmenu_title;
if ($title == "") $title = $mmenu_ident;
if ($pagetitle == "" && $mmenu_pagetitle != "") $pagetitle = $mmenu_pagetitle;
if ($pagetitle == "" && $title != "") $pagetitle = $title;
if ($pagetitle != "") $pagetitle = $pagetitle_separator . $pagetitle;

if ($meta_keywords == "") $meta_keywords = $mmenu_meta_keywords;
if ($meta_keywords == "") $meta_keywords = $pagetitle;
if ($meta_description == "") $meta_description = $mmenu_meta_description;



$tpl_img_content = <<< EOT
<a href="#IMG_POPUPHREF#"><img src="/upload/#IMG#" border="0" alt="#IMG_TXT#" #IMG_WH#></a><br><p class="img_desc" style="margin-bottom: 0; padding-bottom: 0; width: #IMG_WIDTH#px">#IMG_TXT#</p>
EOT;

$tpl_img_content = <<< EOT
<a href="#IMG_POPUPHREF#"><img src="/upload/#IMG#" border="0" alt="#IMG_TXT#" #IMG_WH#></a><br>
<p class="img_desc" style="width: #IMG_WIDTH#px">#IMG_TXT#</p>
EOT;

$tpl_img_content = <<< EOT
<table cellpadding=0 cellspacing=0 class="image" style="width: #IMG_WIDTH#px;">
<tr><td><a href="#IMG_POPUPHREF#"><img src="/upload/#IMG#" border="0" alt="#IMG_TXT#" #IMG_WH#></a></td></tr>
<tr><td style="padding-top: 1.3ex">#IMG_TXT#</td></tr>
</table>
EOT;

$tpl_img_content = <<< EOT
<div class="image" style="width: #IMG_WIDTH#px;">
<a href="#IMG_POPUPHREF#"><img src="#IMG_RELPATH#" border="0" alt="#IMG_TXT#" #IMG_WH#></a>
<p style="width: #IMG_WIDTH#px;">#IMG_TXT#</p>
</div>
EOT;


$mmenu_img_wrapped = prepare_img($tpl_img_content, "IMG_CONTENT", $mmenu_id, "mmenu");
/*
$mmenu_brief = hash_by_tpl($mmenu_row, "#BRIEF#", "mmenu");
$mmenu_brief = hash_by_tpl($mmenu_img_wrapped, $mmenu_brief);
if ($mmenu_brief != "") {
	$mmenu_brief = $mmenu_brief . "<br clear=both><br clear=both>";
}
*/
$mmenu_content = hash_by_tpl($mmenu_row, "#CONTENT#", "mmenu");
$mmenu_content = hash_by_tpl($mmenu_img_wrapped, $mmenu_content);
//pre ($mmenu_img_wrapped);

$ctx_img_wrapped = array();
if ($entity != "mmenu" || $mmenu_id != $id) $ctx_img_wrapped = prepare_img($tpl_img_content, "IMG_CONTENT", $id, $entity, 0);
//pre ($ctx_img_wrapped);


/* ctx image inherited from parent, recursive
$img_ctx_top_prepared = array (
	"img_ctx_top_relpath" => "img/head_2.jpg",
	);

$img_ctx_top_tpl = "#IMG_CTX_TOP_RELPATH#";


//pre($root_tree_content);
foreach ($root_tree_content as $mmenu_id_tmp => $mmenu_row_tmp) {
//	pre($mmenu_id_tmp);

	$img_ctx_top_prepared_tmp = entityrow_imgprepare("img_ctx_top", $mmenu_row_tmp, "/img/head_2.jpg", 1);

//	pre ($img_ctx_top_prepared_tmp);



	if ($img_ctx_top_prepared_tmp["img_ctx_top_exists"] == 1) {
		$img_ctx_top_prepared = $img_ctx_top_prepared_tmp;
	}

}

//pre ("img_ctx_top_prepared is");
//pre ($img_ctx_top_prepared);

$mmenu_img_ctx_top_url = hash_by_tpl($img_ctx_top_prepared, $img_ctx_top_tpl);

*/


$pgroup_root_tree = array();



$datetime_fmt = "%d.%m.%Y %H:%M:%S";
$date_fmt = "%d.%m.%Y";


$tpl = <<< EOT
<tr>
<td valign="top" width="182"><a href="@mmenu_hrefto@"
	onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('mmenu_#ID#','','#IMG_MOVER_RELPATH#',1)"><img
	name="mmenu_#ID#" border="0" src="#IMG_FREE_RELPATH#" #IMG_FREE_WH# alt="#IDENT#"></a></td>
</tr>
@add_mover_preload@
EOT;

$mover_preload_list = "";
//$mmenu_right = mmenuleaf_anchor("MMENU_RIGHT", $tpl);

if ($mover_preload_list != "") {
	$onload = "onload=MM_preloadImages($mover_preload_list)";
}


$path_home_HTML = $path_HTML = "<a href=/>$site_name</a>";
//$path_HTML = "";

$mmenu_id_backup = $mmenu_id;
foreach ($root_tree as $mmenu_id) {
//	$mmenu_row_tmp = select_entity_row(array("id" => $mmenu_id), "mmenu");
	$mmenu_row_tmp = $fromend_root_tree_content[$mmenu_id];
	if (isset($mmenu_row_tmp["id"]) && $mmenu_row_tmp["published"] == 1) {
//		pre($mmenu_row_tmp);

		$tpl = <<< EOT
<a href="@mmenu_hrefto@">#IDENT#</a>
EOT;

		if ($path_HTML != "") $path_HTML .= $path_separator;
		$path_HTML .= hash_by_tpl($mmenu_row_tmp, $tpl);
	}
}
$mmenu_id = $mmenu_id_backup;

$news_id = 0;
if ($entity == "news") $news_id = $id;

$imgs_preload = "";

$tpl_product = <<< EOT
    <td width=17% style="padding: 0 2ex 5ex 0">
    	@product_first@
        <div align=center class="price">@formatted_price@</div>
    </td>
    <td width=30% style="padding: 0 0 5ex 0">
		<table cellpadding=0 cellspacing=0 width=100% border=0>
		<tr>
			<td ondblclick="javascript:popup_bo('product-edit.php?id=#ID#')"><b><a href="product.php?id=#ID#">#IDENT#</a></b></td>
			<!--td align=right>№<form action=product.php class=pid><input type=text name="id" value="#ID#" width=3></form></td-->
		</tr>
		</table>
        @product_pgroup_iccontent@
        <P>#BRIEF#</P>
    </td>
    <td width=2%></td>
@even_anothertd@
@odd_newtr@
EOT;

$tpl_product_flat_2col = <<< EOT
    <td width=17% style="padding: 0 2ex 5ex 0">
    	@product_first@
    	<div align=right><a href="askme.php?subject=#IDENT_URLENCODED#">заказать &raquo;</a></div>
        <div align=center class="price">@formatted_price@</div>
    </td>
    <td width=30% style="padding: 0 0 5ex 0" ondblclick="javascript:popup_bo('product-edit.php?id=#ID#')">
		<table cellpadding=0 cellspacing=0 width=100% border=0>
		<tr>
			<td><b><a href="product.php?id=#ID#">#IDENT#</a></b></td>
			<!--td align=right>№<form action=product.php class=pid><input type=text name="id" value="#ID#" width=3></form></td-->
		</tr>
		</table>
        @product_pgroup_iccontent@
        <P>#BRIEF#</P>
    </td>
    <td width=2%></td>
@even_anothertd@
@odd_newtr@
EOT;


$tpl_product_flat = <<< EOT
<tr valign=top>
    <td width=1% style="padding: 0 2ex 5ex 0">
    	@product_first@
    	<!--div align=right><a href="askme.php?subject=#IDENT_URLENCODED#">заказать &raquo;</a></div-->
        <div align=right style="padding-right:2em"><a href="product.php?id=#ID#">подробнее&nbsp;&raquo;</a></div>
        <div align=center class="price">Цена: @formatted_price@</div>
    </td>
    <td width=30% style="padding: 0 0 5ex 0" ondblclick="javascript:popup_bo('product-edit.php?id=#ID#')">
		<table cellpadding=0 cellspacing=0 width=100% border=0>
		<tr>
			<td colspan=2><b><a href="product.php?id=#ID#">#IDENT#</a></b></td>
			<!--td align=right>№<form action=product.php class=pid><input type=text name="id" value="#ID#" width=3></form></td-->
		</tr>
		<tr><td height=10></td></tr>
		<tr valign=top>
			<td>@product_pgroup_iccontent@</td>
			<td><P>#BRIEF#</P></td>
		</tr>
		</table>
		
    </td>
</tr>
<tr><td height=20></td></tr>
EOT;

$tpl_product_mosaic = <<< EOT
<table cellpadding=0 cellspacing=0 style="float:left; width:22em; height:12em; margin:0 4ex 4ex 0; border=0px solid gray"  ondblclick="javascript:popup_bo('product-edit.php?id=#ID#')">
<tr><td colspan=2 align=center><b><a href="product.php?id=#ID#">#IDENT#</a></b></td></tr>
<tr><td colspan=3 height=10></td></tr>
<tr valign=top>
	<td>@product_first2@</td>
	<td width=70%>
        @product_pgroup_iccontent@
        <P>@brief_cdep@</P>
		<div class="price">@formatted_price@</div>
    	<div><a href="askme.php?subject=#IDENT_URLENCODED#">заказать&nbsp;&raquo;</a></div>
	</td>
</tr>
</table>
EOT;


$tpl_product = ($uprofile["listview"] == 0) ? $tpl_product_flat : $tpl_product_mosaic;

$productlist_before = ($uprofile["listview"] == 0) ? "<table cellpadding=0 cellspacing=0 border=0 width=100%><tr valign=top>" : "";
$productlist_after = ($uprofile["listview"] == 0) ? "</tr></table>" : "";


$title_right = "";

/* cityclinic
$pgroup_banner_image = "<div>здесь простой баннер</div>";

$page_number = $id;
if($page_number == 0) $page_number = $mmenu_id;






$tpl_banner_sky = <<< EOT
<p align=center ondblclick="javascript:popup_bo('banner-edit.php?id=#ID#')"><a @banner_hrefto@ alt="#IDENT#">#BANNER_BRIEF#</a></p>

EOT;

$tpl_banner_sky_nohref = <<< EOT
<p align=center ondblclick="javascript:popup_bo('banner-edit.php?id=#ID#')">#BANNER_BRIEF#</p>

EOT;


$banner_sky_html = "";
$mmenu_banner_sky_html = "";
$article_banner_sky_html = "";

$mmenu_banner_sky = $mmenu_row["banner_sky"];
$banner_sky = $mmenu_banner_sky;

foreach ($fromend_root_tree_content as $mmenu_id_tmp => $mmenu_row_tmp) {
//	pre($mmenu_id_tmp);

	if ($mmenu_row_tmp["banner_sky"] != 0) {
		$query = "select b.*"
			. " from banner b"
			. " where id=" . $mmenu_row_tmp["banner_sky"]
			. " and b.published=1 and b.deleted=0"
			;
		$banner_qa = select_queryarray($query, "banner");
//		pre($banner_qa);
		$mmenu_banner_sky_html = banner_list_from_brief($banner_qa, "#BANNER_BRIEF#", "#BANNER_BRIEF#", "#IMG_RELPATH#");
		
		if ($mmenu_banner_sky_html != "") {
			$banner_sky_html = $mmenu_banner_sky_html;
			break;
		}
	} 
}


if (isset($article_row["id"])) {
	if ($article_row["banner_sky"] != 0) {
		$query = "select b.*"
			. " from banner b"
			. " where id=" . $article_row["banner_sky"]
			. " and b.published=1 and b.deleted=0"
			;
		$banner_qa = select_queryarray($query, "banner");
//		pre($banner_qa);
		$article_banner_sky_html = banner_list_from_brief($banner_qa, "#BANNER_BRIEF#", "#BANNER_BRIEF#", "#IMG_RELPATH#");
		
		if ($article_banner_sky_html != "") {
			$banner_sky_html = $article_banner_sky_html;
		}
	} 
}

//echo $banner_sky_html;
*/

?>