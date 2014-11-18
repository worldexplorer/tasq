<?

require_once "../_lib/_init.php";

$table_columns = array (
	"id" => array("", "sernoupdown"),
	"ident" => array($entity_msg_h, "hrefedit"),
	"~2" => array("", "recursive"),
//	"hashkey" => array("Ключ/ссылка", "viewcenter", "10em"),
	"hashkey" => array("", "textfield", "#INPUT#", "11em", "10em"),
	"is_heredoc" => array("", "checkbox"),
//	"left0_right1" => array("Прав", "checkbox"),

//	"is_drone" => array ("Трут", "checkbox"),
	"content_no_freetext" => array ("", "checkbox"),
	"published" => array("", "checkbox"),
//	"published_legend" => array("", "checkbox"),
//	"published_sitemap" => array("КартаС", "checkbox"),
	"~delete" => array("", "checkboxdel")
);


?>

<? require "_updown.php" ?>
<? require_once "_top.php" ?>
<? require "_list.php" ?>
<? require_once "_bottom.php" ?>