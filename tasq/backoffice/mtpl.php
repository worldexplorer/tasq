<? require_once "../_lib/_init.php" ?>
<?

$mtpl_popup_tpl = <<< EOT
<a href="javascript:popup_blank('mtpl-popup.php?id=#ID#', 720, 630)">$msg_tag_shortcut $msg_check_popup</a>
EOT;

$table_columns = array (
	"id" => array("¹", "sernoupdown"),
	"date_created" => array("", "datetime"),
	"ident" => array($entity_msg_h, "hrefedit"),
	"~2" => array($msg_check_popup, "ahref", $mtpl_popup_tpl, "8em"),
	"hashkey" => array("", "view"),
	"published" => array("", "checkbox"),
	"~delete" => array("", "checkboxdel")
);


?>

<? require "_updown.php" ?>
<? require_once "_top.php" ?>
<? require "_list.php" ?>
<? require_once "_bottom.php" ?>