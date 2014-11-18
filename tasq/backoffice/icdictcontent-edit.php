<?

require_once "../_lib/_init.php";

$entity_fields = array (
	"ident" => array ("Значение", "textarea_3", ""),
	"content" => array ("Описание", "textarea", ""),

	"hashkey" => array ("Ключ", "textfield", ""),
	"label_style" => array ("label style=[]", "textfield", ""),
	"tf1_width" => array ("Рядом - textfield шириной", "number", 0),
	"tf1_incolumn" => array ("textfield в отдельном столбце", "checkbox", 0),

	"tf1_addtodict" => array ("Добавлять значения<br>из textfield сразу<br>в справочник [#IDENT#]", "checkbox", 0),
	"tf1_addedpublished" => array ("Опубликованы ли<br>добавленные значения", "checkbox", 0),

	"icdict" => array ("Cправочник", "select_table_all", "ident"),

	"published" => array ("Опубликовано", "checkbox", 1)
);
?>

<? include "_entity_edit.php" ?>
<? include "_top.php" ?>
<? include "_edit_fields.php" ?>
<? include "_bottom.php" ?>
