<?

require_once "../_lib/_init.php";

$entity_fields = array (
	"ident" => array ("��������", "textfield", ""),
	"hashkey" => array ("����", "textfield", ""),
	"content" => array ("JS RegExp", "textfield", ""),

	"published" => array ("������������", "checkbox")
);
?>

<? require "_entity_edit.php" ?>
<? require_once "_top.php" ?>
<? require "_edit_fields.php" ?>
<? require_once "_bottom.php" ?>
