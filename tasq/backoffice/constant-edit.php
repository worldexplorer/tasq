<?

require_once "../_lib/_init.php";

$ident = get_string("ident");
$hashkey = get_string("hashkey");

$entity_fields = array (
	"ident" => array ("", "textfield", $ident),
	"hashkey" => array ("", "textfield", $hashkey),
	"content" => array ("", "textarea_18", ""),

	"published" => array ("", "checkbox", 1)
);


//jsv_addvalidation("JSV_TF_CHAR", "ident", "Название");
//jsv_addvalidation("JSV_TF_CHAR", "hashkey", "Ключ");

?>

<? require "../_lib/_entity_edit.php" ?>
<? require_once "_top.php" ?>
<? require "../_lib/_edit_fields.php" ?>
<? require_once "_bottom.php" ?>
