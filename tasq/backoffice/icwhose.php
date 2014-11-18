<?

require_once "../_lib/_init.php";

$table_columns = array (
	"id" => array("№", "sernoupdown"),
	"ident" => array($entity_msg_h, "hrefedit"),
	"ic" => array("", "cnt"),
	"icdict" => array("", "cnt"),
	"hashkey" => array("Ключ для шаблонов", "textfield", "", "12em", "11em"),
	"bo_only" => array("БО", "checkbox"),
	"jsv_debug" => array("Отладка", "checkbox"),
	"published" => array("Опубл", "checkbox"),
	"~1" => array("Удал", "checkboxdel")
);


?>

<? require "_updown.php" ?>
<? require_once "_top.php" ?>
<? require "_list.php" ?>
<? require_once "_bottom.php" ?>