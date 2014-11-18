<?

require_once "../_lib/_init.php";

$popupface_href_tpl = "";

$table_columns = array (
	"id" => array("", "serno"),
	"date_published" => array("", "datetime"),
	"ident" => array("", "hrefedit"),
	"content" => array("", "ahref", "<a href='#ENTITY#-edit.php?id=#ID#'>@firstwords_stripped_content@</a>"),
	"remote_address" => array("", "viewcenter", "", "7em"),
//	"published" => array("", "checkboxro"),
	"~delete" => array("", "checkboxdel")
);


?>

<? require "../_lib/_updown.php" ?>
<? require_once "_top.php" ?>
<? require "../_lib/_list.php" ?>
<? require_once "_bottom.php" ?>