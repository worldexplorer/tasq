<? require_once "../_lib/_init.php" ?>
<?

$table_columns = array (
	"id" => array("�", "sernoupdown"),
//	"date_updated" => array("���� ����������", "timestamp"),
	"ident" => array($entity_msg_h, "hrefedit"),
	"hashkey" => array("����", "viewcenter"),
	"img" => array("", "cnt"),

	"published" => array("�����", "checkboxro"),
	"~1" => array("����", "checkboxdel")
);


?>

<? require "_updown.php" ?>
<? require_once "_top.php" ?>
<? require "_list.php" ?>
<? require_once "_bottom.php" ?>