<?

require_once "../_lib/_init.php";

$table_columns = array (
	"id" => array("№", "sernoupdown"),
	"ident" => array($entity_msg_h, "hrefedit"),
	"hashkey" => array ("Ключ", "view", ""),
	"content" => array ("JS RegExp", "view", ""),
	"published" => array("Опубл", "checkboxro"),
	"~1" => array("Удал", "checkboxdel")
);


?>

<? require "_updown.php" ?>
<? require_once "_top.php" ?>
<? require "_list.php" ?>
<? require_once "_bottom.php" ?>