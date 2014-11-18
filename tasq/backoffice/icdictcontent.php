<?

require_once "../_lib/_init.php";

$table_columns = array (
	"id" => array("№", "sernoupdown"),
	"icdict_ident" => array("Справочник", "view", "15em"),
	"~2" => array("Значения справочника", "ahref", "<a href=#ENTITY#-edit.php?id=#ID#>#IDENT#</a>"),
	"ident" => array("Значения справочника", "textfield", "", "17em", "16em"),
//	"hashkey" => array ("Ключ", "textfield", "", "15em", "16em"),
	"published" => array("Опубл", "checkbox"),
	"published" => array("Опубл", "checkbox"),
	"published" => array("Опубл", "checkbox"),
	"~1" => array("Удал", "checkboxdel")
);


?>

<? require "_updown.php" ?>
<? require_once "_top.php" ?>
<? require "_list.php" ?>
<? require_once "_bottom.php" ?>