<? require_once "../_lib/_init.php" ?>
<?

$table_columns = array (
	"id" => array("№", "sernoupdown"),
	"ident" => array($entity_msg_h, "hrefedit"),
//	"~2" => array("Подпункты", "recursive"),
//	"~3" => array("Новостей", "depend", "новостей: "),
	"news" => array("Новостей", "cnt"),
//	"hits" => array("Hits", "viewcenter", "2em"),
	"published" => array("Опубл", "checkbox")
	, "~1" => array("Удал", "checkboxdel")
);


?>

<? require "_updown.php" ?>
<? require_once "_top.php" ?>
<? require "_list.php" ?>
<? require_once "_bottom.php" ?>