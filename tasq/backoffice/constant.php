<?

require_once "../_lib/_init.php";

$table_columns = array (
	"id" => array("¹", "sernoupdown"),
	"ident" => array($entity_msg_h, "hrefedit"),
	"hashkey" => array("", "view"),
//	"content" => array("", "view"),
	"published" => array("", "checkbox"),
	"~delete" => array("", "checkboxdel")
);


?>

<? require "../_lib/_updown.php" ?>
<? require_once "_top.php" ?>
<? require "../_lib/_list.php" ?>
<? require_once "_bottom.php" ?>