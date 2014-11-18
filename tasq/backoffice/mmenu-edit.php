<?
require_once "../_lib/_init.php";

$entity_fields = array (
	"ident" => array ("", "textfield", ""),
	"annotation" => array ("", "textfield", ""),

//	"brief" => array ("", "freetext_450", ""),

//	"content" => array ("Контент", "textarea_18", ""),
	"content" => array ("", "freetext_450", ""),

	"~11" => array("IMG_CONTENT", "img_layer"),
//	"~14" => array("IMG_COLUMN_LEFT", "img_layer"),
//	"~17" => array("IMG_COLUMN_RIGHT", "img_layer"),

//	"~16" => array("IMG_MMENU_PGROUP", "img_layer"),
//	"~12" => array("IMG_MMENU", "img_layer"),
//	"~13" => array("IMG_PGHEADER", "img_layer"),
//	"~14" => array("IMG_LEFTCOL", "img_layer"),
//	"~15" => array("IMG_RIGHTCOL", "img_layer"),

//	"~1_open" => array ("Служебные картинки", "layer_open"),
//	"~1_close" => array ("Служебные картинки", "layer_close"),

/*
	"~mmenuimg_layer-open" => array ("", "layer_open"),
//	"img_header" => array ("Заголовок", "image"),
	"img_free" => array ("", "image"),
	"img_mover" => array ("", "image"),

//	"~151" => array ("", "ahref", "&nbsp;"),
//	"img_small_free" => array ("", "image"),
//	"img_small_mover" => array ("", "image"),
//	"img_small_current" => array ("", "image"),

//	"img_ctx_top" => array ("", "image"),
//	"img_ctx_left" => array ("", "image"),
	"~mmenuimg_layer-close" => array ("", "layer_close"),
*/

	"~filesattached_layer-open" => array ("Прайс-лист в PDF</a> (загружать только в пункт меню [<a href=mmenu-edit.php?id=52&layer_opened_nr=3>Нижнее меню -> Прайс-лист</a>])<a>", "layer_open"),
	"~filesattached_layer-close" => array ("Прайс-лист в PDF</a> (загружать только в пункт меню [<a href=mmenu-edit.php?id=52&layer_opened_nr=3>Нижнее меню -> Прайс-лист</a>])<a>", "layer_close"),


	"~service_layer-open" => array ("", "layer_open"),

	"pagetitle" => array ("", "textfield", ""),
	"title" => array ("", "textfield", ""),
	"meta_keywords" => array ("", "textarea_3", ""),
	"meta_description" => array ("", "textarea_3", ""),
//	"banner_top" => array ("Баннер в шапке", "select_soft", "banner"),

//	"left0_right1" => array ("На этой странице", "boolean_hash", $left0_right1_hash, "фыва"),
//	"banner_sky" => array ("Баннер-небоскрёб", "select_soft"),

//	"tpl_list_item" => array ("<a name=tpl></a>Шаблон для элемента", "textarea", ""),
//	"tpl_list_wrapper" => array ("Шаблон для списка элементов<br>(не для страниц-разворотов)", "textarea", ""),

	"is_drone" => array ("", "checkbox", 0, ""),
	"is_heredoc" => array ("", "checkbox", 1, ""),
	"hashkey" => array ("", "textfield", ""),

//	"top_comment" => array ("Комментарий в меню", "textarea_3", ""),
//	"bgcolor" => array ("Цвет бэкграунда", "textfield", ""),

	"parent_id" => array ("", "select_table_tree_root"),
//	"is_inline" => array ("Блоком на родителе", "checkbox"),
//	"published_legend" => array ("", "checkbox", 0),
//	"published_sitemap" => array ("", "checkbox", 0),
	"~service_layer-close" => array ("", "layer_close"),

//	"~1_open" => array ("Классификаторы", "columned_open"),
	"published" => array ("", "checkbox", 1, "@bo_href_preview@"),
//	"~1_close" => array ("Краткое описание и полное содержимое (для отсканированных текстов)", "columned_close"),	
);

$pricelist_valid_entity_fields = array (
	"file1" => array ("Прайс в PDF", "upload"),
	"file1_comment" => array ("", "textarea", ""),
	"~31" => array ("", "ahref", "&nbsp;"),

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
);

$pricelist_redirect_entity_fields = array (
	"~31" => array ("", "ahref", "Общий полный прайс-лист клиники в PDF"
		. "можно загружать только в пункт меню"
		. " [<a href=mmenu-edit.php?id=52&layer_opened_nr=2>Нижнее меню -> Прайс-лист</a>]"),
);

if ($id > 0 &&
		(strpos(select_field("hashkey"), "pricelist") !== false || $id == 52)
	) {
	$entity_fields = hash_insert_after($entity_fields, $pricelist_valid_entity_fields, "~filesattached_layer-open");
} else {
	$entity_fields = hash_insert_after($entity_fields, $pricelist_redirect_entity_fields, "~filesattached_layer-open");
}

?>
<? require "_entity_edit.php" ?>
<? require "_top.php" ?>
<? require "_edit_fields.php" ?>
<? require "_bottom.php" ?>