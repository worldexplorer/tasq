<?

require_once "../_lib/_init.php";

$table_columns = array (
	"id" => array("№", "sernoupdown"),
	"ident" => array($entity_msg_h, "hrefedit"),
	"hashkey" => array ("Ключ", "textfield", "", "15em", "14em"),
	"published" => array("Опубл", "checkbox"),
	"~1" => array("Удал", "checkboxdel")
);


?>

<? require "_updown.php" ?>
<? require_once "_top.php" ?>
<? require "_list.php" ?>
<? require_once "_bottom.php" ?>