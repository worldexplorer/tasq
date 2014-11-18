<?

require_once "../_lib/_init.php";

$popupface_href_tpl = "";
$no_bottomline = 1;

$entity_fields = array (
	"ident" => array ("", "textfield", ""),
	"date_published" => array ("", "timestampro"),

	"content" => array ("", "ahref", "<div style='border:1px solid gray; padding:10'>#CONTENT#</div>"),

//	"published" => array ("", "checkbox", 1, "@bo_href_preview@"),
//	"archived" => array ("", "checkbox")
);

?>

<? require "../_lib/_entity_edit.php" ?>
<? require_once "_top.php" ?>
<? require "../_lib/_edit_fields.php" ?>
<? require_once "_bottom.php" ?>
