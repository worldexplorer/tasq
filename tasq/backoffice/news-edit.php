<? require_once "../_lib/_init.php" ?>
<?

$m2mcb_colcnt = 8;

$entity_fields = array (
	"ident" => array ("", "textfield", ""),
	"date_published" => array ("", "datetime_date"),

	//"brief" => array ("", "freetext_200", ""),
	"content" => array ("", "freetext", ""),

	"srcurl" => array ("<a href='#SRCURL#' target=_blank>Источник</a>", "textfield", ""),
//	"srcurl" => array ("Источник", "ahref", "<a href='#SRCURL#' target=_blank>#SRCURL#</a>"),
	"hrefto" => array ("<a href='#HREFTO#' target=_blank>Ссылка</a>", "textfield", ""),

	"~11" => array("IMG_NEWS", "img_layer"),
//	"~12" => array("IMG_CONTENT", "img_layer"),

/*
	"~filesattached_layer_open" => array ("", "layer_open"),

	"file1" => array ("", "upload"),
	"file1_comment" => array ("", "textarea", ""),
	"~31" => array ("", "ahref", "&nbsp;"),

	"file2" => array ("", "upload"),
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

	"~filesattached_layer_close" => array ("", "layer_close"),
*/
//	"~4_open" => array ("Привязка задачи к новостям", "layer_open"),
//	"news" => array ("Новости", "m2mcb", "m2m_task_news"),
//	"~4_close" => array ("Привязка задачи к новостям", "layer_close"),


//	"task" => array ("Привязка к товарам",
//							"multicompositecontent", "m2m_task_$entity",
//							array("task"), 1),

	"~service_layer-open" => array ("", "layer_open"),
//	"pagetitle" => array ("", "textfield", ""),
//	"title" => array ("", "textfield", ""),
//	"meta_keywords" => array ("", "textfield", ""),
//	"meta_description" => array ("", "textfield", ""),

//	"rsss" => array ("RSS-источник", "select_soft"),
//	"rss_published" => array ("В RSS", "checkbox", 1),
//	"banner_top" => array ("Баннер в шапке", "select_soft", "banner"),

	"date_published" => array ("", "timestamp_date"),
	"date_created" => array ("", "timestampro", ""),
	"date_updated" => array ("", "timestampro", ""),

	"hits" => array ("", "textfield"),
	"archived" => array("", "checkbox"),
	//"is_new" => array ("", "checkbox", 0),
	"~service_layer-close" => array ("", "layer_close"),

//	"~4_open" => array ("Привязка товара к целевым группам", "layer_open"),
//	"ugroup" => array ("Группа", "m2mcb", "m2m_news_ugroup"),
//	"cgroup" => array ("Показывать<br>клиентам", "m2mcb", "m2m_news_cgroup"),
//	"~4_close" => array ("Привязка товара к целевым группам", "layer_close"),

	"ngroup" => array ("", "select_table_tree", "ngroup", "ident"),

	"published" => array ("", "checkbox", 1, $bo_href_preview),
//	"i_published" => array ("", "checkbox", 1),
);

?>

<? require "_entity_edit.php" ?>
<? require_once "_top.php" ?>
<? require "_edit_fields.php" ?>
<? require_once "_bottom.php" ?>
