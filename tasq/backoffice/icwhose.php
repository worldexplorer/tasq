<?

require_once "../_lib/_init.php";

$table_columns = array (
	"id" => array("�", "sernoupdown"),
	"ident" => array($entity_msg_h, "hrefedit"),
	"ic" => array("", "cnt"),
	"icdict" => array("", "cnt"),
	"hashkey" => array("���� ��� ��������", "textfield", "", "12em", "11em"),
	"bo_only" => array("��", "checkbox"),
	"jsv_debug" => array("�������", "checkbox"),
	"published" => array("�����", "checkbox"),
	"~1" => array("����", "checkboxdel")
);


?>

<? require "_updown.php" ?>
<? require_once "_top.php" ?>
<? require "_list.php" ?>
<? require_once "_bottom.php" ?>