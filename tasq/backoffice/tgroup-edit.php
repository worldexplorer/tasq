<? require_once "../_lib/_init.php" ?>
<?
/*
$msg_fields_
"file1-graycomment" => "Файл 1",
*/

$msg_fields = array_merge($msg_fields);

$entity_fields = array (
	"ident" => array ("", "textfield", ""),

	"content" => array ("Текст на страницу<br>с описанием<br>группы товаров<br>(этот текст в центре,<br>товары справа)"
		, "freetext"),

	"i_published" => array ("", "checkbox", 1, "заполните также [Текст в аннонс] и [Фотографии группы товаров] ниже&nbsp;&nbsp;&nbsp;&nbsp;[<a href='#ENTITY#-onindex.php'>отсортировать спецпредложения на главной</a>]"),
	"brief" => array ("Текст в аннонс<br>группы товаров<br>на главной", "textarea_3", "", "если это поле заполнено, проверьте, чтобы галочка [НА ГЛАВНОЙ] была включена"),
	"~11" => array("IMG_tgroup", "img_layer"),


//	"~filesattached_layer-open" => array ("Прайс-лист в PDF", "layer_open"),

	"file1" => array ("Прайс в PDF", "upload"),
//	"file1_comment" => array ("Комментарий к<br>прайсу в PDF", "freetext_200", ""),
//	"~31" => array ("", "ahref", "&nbsp;"),

/*	"file2" => array ("", "upload"),
	"file2_comment" => array ("", "textarea", ""),
	"~32" => array ("", "ahref", "&nbsp;"),

	"file3" => array ("", "upload"),
	"file3_comment" => array ("", "textarea", ""),
	"~33" => array ("", "ahref", "&nbsp;"),

	"file4" => array ("", "upload"),
	"file4_comment" => array ("", "textarea", ""),
	"~34" => array ("", "ahref", "&nbsp;"),

	"file5" => array ("", "upload"),
	"file5_comment" => array ("", "textarea", ""),
	"~35" => array ("", "ahref", "&nbsp;"),
*/
//	"~filesattached_layer-close" => array ("", "layer_close"),
		
	"parent_id" => array ("", "select_table_tree_root", "tgroup"),
	"task" => array ("", "cnt", "#MASTER_ENTITY# группы [#IDENT#]"),
//	"hashkey_pimport" => array("КлючИмпорт", "textfield", ""),
//	"hashkey_pimport" => array("КлючИмпорт", "textfieldro", ""),

	
	"~service_layer-open" => array ("", "layer_open"),
//	"pagetitle" => array ("", "textfield", ""),
//	"title" => array ("", "textfield", ""),
//	"meta_keywords" => array ("", "textarea_3", ""),
//	"meta_description" => array ("", "textarea_3", ""),

//	"banner_top" => array ("Баннер в шапке", "select_soft", "banner"),
//	"file1" => array ("Картинка в меню", "image", "", "только для корневых групп"),
//	"divclass" => array ("Класс DIV", "textfield", ""),

	"date_created" => array ("", "timestampro", ""),
	"date_updated" => array ("", "timestampro", ""),
//	"hits" => array ("Просмотров", "number", "", "любое обращение к новости с лица; считаются даже роботы"),
	"~service_layer-close" => array ("", "layer_close"),

//	"projectdriven" => array ("Выделить производителя", "checkbox", 0),
	"published" => array ("", "checkbox", 1, "@bo_href_preview@"),
);

/*if ($id > 0 && select_field("i_published") == 0) {
	 unset($entity_fields["brief"]);
}

function tgroup_after_update() {
	global $entity_fields;

	$i_published = intval(select_field("i_published"));
	pre("tgroup_after_update_function(): select_field(i_published) = " . $i_published);

	if ($i_published == 0) unset($entity_fields["brief"]);
}
*/

?>
<? require "_entity_edit.php" ?>
<? require_once "_top.php" ?>
<? require "_edit_fields.php" ?>
<? require_once "_bottom.php" ?>