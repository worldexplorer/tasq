<?

require_once "../_lib/_init.php";

$entity_fields = array (
	"ident" => array ("", "textfield", ""),
//	"sign" => array ("�������", "upload", ""),

	"hrefto" => array ("URL", "textfield"),
	"task" => array ("", "cnt", "#MASTER_ENTITY# ������ [#IDENT#]"),

	"brief" => array("������", "freetext_200"),
//	"content" => array ("��������", "freetext_450", ""),

	"~13" => array("IMG_CONTENT", "img_layer"),

	"~2_open" => array ("��������� ����", "layer_open"),
	"date_created" => array ("C�������", "timestampro", ""),
	"date_updated" => array ("����������", "timestampro", ""),
//	"hits" => array ("����������", "number", 0),

	"contact" => array("��������<br>����������", "textarea"),
	"~2_close" => array ("��������� ����", "layer_close"),

	"published" => array ("������������", "checkbox", 1),
);

?>

<? require "_entity_edit.php" ?>
<? require_once "_top.php" ?>
<? require "_edit_fields.php" ?>
<? require_once "_bottom.php" ?>