<?

require_once "../_lib/_init.php";

$table_columns = array (
	"id" => array("�", "sernoupdown"),
//	"ident" => array($entity_msg_h, "hrefedit"),

	"~2" => array($entity_msg_h, "ahref", "<a href=#ENTITY#-edit.php?id=#ID#>#IDENT#</a>"),
//	"ident" => array($entity_msg_h, "textfield", "", "4em", "5em"),

	"date_exchrate_rub" => array("���� �����", "datetime"),
	"exchrate_rub" => array("���� �������� � �����", "textfield", "#INPUT#", "5em", "13em"),
	"exchrate_rub_multiplier" => array("���������", "textfield", "#INPUT#", "5em", "6em"),
	"exchrate_rub_multiplied" => array("����", "numberro", "#INPUT#", "5em", "6em"),

	"date_expiration" => array("��������", "datetime"),
//	"expiration_minutes" => array("���", "viewcenter"),
	"expiration_minutes" => array("���", "number", "", "3em", "3em"),


//	"hashkey" => array("����", "textfield", "#INPUT#", "2em", "3em"),

//	"product" => array("", "cnt"),

	"published" => array("�����", "checkbox"),
	"~1" => array("����", "checkboxdel")
);

$list_left_additional_fields = ""
	. ", format(exchrate_rub*exchrate_rub_multiplier, 2) as exchrate_rub_multiplied"
	;

?>

<? require "../_lib/_updown.php" ?>
<? require_once "_top.php" ?>
<? require "../_lib/_list.php" ?>
<? require_once "_bottom.php" ?>