<?

require_once "../_lib/_init.php";


$entity_fields = array (
	"id" => array ("Номер", "ro"),
	"ident" => array ("Чья заявка", "ro"),
	"content" => array ("Форма", "ro"),
	"icwhose" => array("Анкета", "table_ro", "ident"),
//	"~1" => array("Файлы", "table_ro", "ident"),
);

$no_savebutton = 1;

?>

<? include "_entity_edit.php" ?>
<? include "_top.php" ?>
<? include "_edit_fields.php" ?>
<? include "_bottom.php" ?>
