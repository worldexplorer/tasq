<?

require_once "../_lib/_init.php";

function single_img($row) {
	global $tpl_img_singlerow, $tpl_img_new;
	global $imgtype_resize_default_width, $imgtype_resize_default_height;
	$ret = "";

	if (count($row) == 0) {
		$row = array (
			"id" => 0,
			"imgtype" => 1,
			"img" => "",
			"img_w" => 0,
			"img_h" => 0,
			"img_txt" => "",
			"img_big" => "",
			"img_big_w" => 0,
			"img_big_h" => 0,
			"img_big_txt" => "",
			"imgtype_resize_default_width" => $imgtype_resize_default_width,
			"imgtype_resize_default_height" => $imgtype_resize_default_height
		);
		$ret = hash_by_tpl($row, $tpl_img_new);
	} else {
		$row["pub_checked"] = ($row["published"] == '1') ? "checked" : "";
		$row["imgtype_resize_default_width"] = $imgtype_resize_default_width;
		$row["imgtype_resize_default_height"] = $imgtype_resize_default_height;
		$ret = hash_by_tpl($row, $tpl_img_singlerow);
	}

	return $ret;
}

if ($id > 0) {
	$img_row = select_entity_row();
	$_REQUEST["owner_entity"] = $img_row["owner_entity"];
	$_REQUEST["owner_entity_id"] = $img_row["owner_entity_id"];
}

$owner_entity = get_string("owner_entity");
$owner_entity_id = get_number("owner_entity_id");

if ($owner_entity != "" && $owner_entity != "0") {
	$fixed_hash["owner_entity"] = $owner_entity;

	$list_query = "select * from $entity where owner_entity='$owner_entity' order by " . get_entity_orderby();
	$list_query_cnt = "select count(*) as cnt from $entity where owner_entity='$owner_entity'";

	if ($owner_entity_id > 0) {
		$fixed_hash["owner_entity_id"] = $owner_entity_id;
		$list_query = "select * from $entity where owner_entity='$owner_entity' and owner_entity_id='$owner_entity_id' order by " . get_entity_orderby();
		$list_query_cnt = "select count(*) as cnt from $entity where owner_entity='$owner_entity' and owner_entity_id='$owner_entity_id'";
	}
}

$add_suffix = $f5_suffix = $back2list_suffix = hrefsuffix_fromhash($fixed_hash);

/*
$fixed_suffix = hrefsuffix_fromhash($fixed_hash);
$fixed_cond = sqlcond_fromhash($fixed_hash);
$fixed_hiddens = hidden_fromhash($fixed_hash);
echo htmlentities($fixed_hiddens);
*/

if ($owner_entity != "" && $owner_entity_id > 0) {
} else {
	if ($id == 0) $alertmsg = "Пожалуйста, добавляйте картинки\\nс привязкой к конкретному материалу.\\n\\nУточните редактируемый документ\\nс помощью рубрикатора.";
}

$fixed_getfirstfromdb = 0;
$force_selectall_onedit = 1;


if ($id > 0) {
	$tpl_hash = array(
		"prev" => "<< <a href='#SCRIPT_NAME#?id=#ID#&#FIXED_SUFFIX#' title='#IDENT#'>предыдущий элемент</a> (#CNT#)",
		"prev_empty" => "",
		"next" => "(#CNT#) <a href='#SCRIPT_NAME#?id=#ID#&#FIXED_SUFFIX#' title='#IDENT#'>следующий элемент</a> >>",
		"next_empty" => ""
		);
	
	$href_prevnext_hash = select_entity_prevnext($fixed_hash, $entity, $id, $tpl_hash);
//	pre ($fixed_hash);
	//pre ($href_prevnext_hash);
	
	$href_prev = $href_prevnext_hash["prev"];
	$href_next = $href_prevnext_hash["next"];
} else {
	$no_topline = 1;
}


$ctx_imgtype_query = "select it.id, it.ident"
	. " from imgtype it"
	. " inner join img i on i.imgtype=it.id"
	. " where i.owner_entity='$owner_entity'"
	. " group by it.id"
	. " order by it." . get_entity_orderby("imgtype")
	;

if ($id == 0) {
	$qa = select_queryarray($ctx_imgtype_query);
	if (isset($qa[0])) {
		$_REQUEST["imgtype"] = $qa[0]["id"];
	} else {
		$ctx_imgtype_query = "select id, ident from imgtype it "
			. " where id=" . select_first_published("id", array("published" => 1, "deleted" => 0), "imgtype");
		$qa = select_queryarray($ctx_imgtype_query);
		if (isset($qa[0])) $_REQUEST["imgtype"] = $qa[0]["id"];
	}
} else {
	$_REQUEST["imgtype"] = select_field("imgtype");
}


$imgtype_id = get_number("imgtype");
$imgtype_row = select_entity_row(array("id" => $imgtype_id), "imgtype");
$imgtype_resize_default_width = $imgtype_row["resize_default_width"];
$imgtype_resize_default_height = $imgtype_row["resize_default_height"];

if ($mode == "update") {
	$id = img_update($id, $imgtype_id);
	if (select_field("deleted") == 1) redirect("$entity.php");
} else {
	$_REQUEST["imgtype"] = 1;
}

$entity_fields = array (
	"~1" => array ("", "ahref", "@single_img@")
//	, "imgtype" => array ("Закладка", "select_table_all", "ident")
	, "imgtype" => array ("Закладка", "select_query", $ctx_imgtype_query)
);

if ($owner_entity != "" && $owner_entity != "0") {
	$entity_fields = array_merge ($entity_fields, 
			array (
				"~2" => array ("", "ahref", "<a href=$owner_entity-edit.php?id=$owner_entity_id>Редактировать соответствующий материал</a>")
			)
		);
}

?>

<? include "_entity_edit.php" ?>
<? include "_top.php" ?>
<? include "_edit_fields.php" ?>
<? include "_bottom.php" ?>
