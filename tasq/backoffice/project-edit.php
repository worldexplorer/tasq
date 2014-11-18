<?

require_once "../_lib/_init.php";

$entity_fields = array (
	"ident" => array ("", "textfield", ""),
//	"sign" => array ("Эмблема", "upload", ""),

	"hrefto" => array ("URL", "textfield"),
	"task" => array ("", "cnt", "#MASTER_ENTITY# группы [#IDENT#]"),

	"brief" => array("Кратко", "freetext_200"),
//	"content" => array ("Описание", "freetext_450", ""),

	"~13" => array("IMG_CONTENT", "img_layer"),

	"~2_open" => array ("Служебные поля", "layer_open"),
	"date_created" => array ("Cоздание", "timestampro", ""),
	"date_updated" => array ("Обновление", "timestampro", ""),
//	"hits" => array ("Просмотров", "number", 0),

	"contact" => array("Контакты<br>поставщика", "textarea"),
	"~2_close" => array ("Служебные поля", "layer_close"),

	"published" => array ("Опубликовано", "checkbox", 1),
);

?>

<? require "_entity_edit.php" ?>
<? require_once "_top.php" ?>
<? require "_edit_fields.php" ?>
<? require_once "_bottom.php" ?>