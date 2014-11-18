<?

require_once "../_lib/_init.php";

function imgsrc_tag($row) {
	$ret = "";

	$ret = imgsrc_input($row);

/*	if (img_exists($row, "img") == 1) {
		$tpl = "<img src='/upload/img/#ID#/#IMG#' width=#IMG_W# height=#IMG_H# border=0>";
		$ret = hash_by_tpl($row, $tpl);

		$ret = "<input type=text size=14 value=\"$ret\" title='[Ctrl+Click], [Ctrl-C], [Ctrl+V] в нужном месте'>";
	} else {
*/

	if ($ret == "") $ret = "файл потерян";

	return $ret;
}

function img_linkedto($row) {
	$ret = "";

	if ($row["owner_entity"] != "") {
		$tpl = "<a href='#OWNER_ENTITY#.php?'>#OWNER_ENTITY#</a>";

		if ($row["owner_entity_id"] != "") {
			$row["owner_entity_ident"] = select_field("ident", array("id" => $row["owner_entity_id"]), $row["owner_entity"]);
			$tpl = "<a href='#OWNER_ENTITY#-edit.php?id=#OWNER_ENTITY_ID#' title='#OWNER_ENTITY_IDENT#\n#IMGTYPE_IDENT#'>#OWNER_ENTITY#:#OWNER_ENTITY_ID#</a>";
		}

		$ret = hash_by_tpl($row, $tpl);
	}
	
	$ret = "<center>$ret</center>";
	return $ret;
}

function img_ident($row) {
	global $fixed_suffix;
	global $owner_entity, $owner_entity_id;

	$ret = "";

	$tpl = "";

	if ($row["img"] != "") $tpl = "#IMG#";
	
	if ($row["img_big"] != "") {
		if ($tpl != "") $tpl .= "&nbsp;&nbsp;»&nbsp;&nbsp;";
		$tpl .= "#IMG_BIG#";
	}

	if ($tpl == "") $tpl = "пустая запись, можно заполнять";

//	$tpl = "<a href='img-edit.php?id=#ID#&owner_entity=$owner_entity&owner_entity_id=$owner_entity_id{$fixed_suffix}'>$tpl</a>";
	$tpl = "<a href='img-edit.php?id=#ID#'>$tpl</a>";

	$ret = hash_by_tpl($row, $tpl);

	return $ret;
}

$list_query = "select * from $entity where deleted=0 order by manorder desc";
$list_query_cnt = "select count(*) as cnt from $entity where deleted=0";

$owner_entity = get_string("owner_entity");
$owner_entity_id = get_number("owner_entity_id");

if ($owner_entity != "" && $owner_entity != "0") {
	$fixed_hash["owner_entity"] = $owner_entity;

	$list_query = "select i.*, it.ident as imgtype_ident from $entity i"
		. " left outer join imgtype it on i.imgtype=it.id"
		. " where i.owner_entity='$owner_entity'"
		. " and i.deleted=0"
		. " order by i.manorder desc";
	$list_query_cnt = "select count(*) as cnt from $entity where owner_entity='$owner_entity' and deleted=0";

	if ($owner_entity_id > 0) {
		$fixed_hash["owner_entity_id"] = $owner_entity_id;
		$list_query = "select i.*, it.ident as imgtype_ident from $entity i"
			. " left outer join imgtype it on i.imgtype=it.id"
			. " where i.owner_entity='$owner_entity' and owner_entity_id='$owner_entity_id'"
			. " and i.deleted=0"
			. " order by i.manorder desc";

		$list_query_cnt = "select count(*) as cnt from $entity where owner_entity='$owner_entity' and owner_entity_id='$owner_entity_id' and deleted=0";
	}
}

/*
$_REQUEST["owner_entity"] = $owner_entity;
$_REQUEST["owner_entity_id"] = $owner_entity_id;

$fixed_cond = sqlcond_fromhash($fixed_hash);
$fixed_hiddens = hidden_fromhash($fixed_hash);
*/

$add_suffix = hrefsuffix_fromhash($fixed_hash);
$list_url = $_SERVER["SCRIPT_NAME"] . "?$add_suffix";

$fixed_getfirstfromdb = 0;

$table_columns = array (
	  "id" => array("№", "serno")
	, "date_updated" => array("Дата обновления", "timestamp")
//	, "img" => array($entity_msg_h, "hrefedit")
	, "img" => array($entity_msg_h, "ahref", "@img_ident@")

	, "~2" => array("Код", "ahref", "<center>@imgsrc_tag@</center>", "11em")
	, "~3" => array("Привязка", "ahref", "@img_linkedto@", "6em")
//	, "published" => array("Опубл", "checkboxro")
	, "~1" => array("Удал", "checkboxdel")
);

?>

<? require "_updown.php" //kills all manual computation of FIXED; updown disabled, manorder desc ?>
<? require_once "_top.php" ?>
<? require "_list.php" ?>
<? require_once "_bottom.php" ?>