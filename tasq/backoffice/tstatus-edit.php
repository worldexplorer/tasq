<?

require_once "../_lib/_init.php";

$entity_fields = array (
	"ident" => array ("��������", "textfield", ""),
	"hashkey" => array ("����", "textfield", ""),

	"published" => array ("������������", "checkbox")
);
?>

<? include "_entity_edit.php" ?>
<? include "_top.php" ?>
<? include "_edit_fields.php" ?>
<? include "_bottom.php" ?>
