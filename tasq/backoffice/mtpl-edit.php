<? require_once "../_lib/_init.php" ?>
<?

$mtpl_popup_tpl = <<< EOT
<a href="javascript:popup_blank('mtpl-popup.php?id=#ID#', 720, 630)">$msg_tag_shortcut $msg_check_popup</a>
EOT;

$entity_fields = array (
	"ident" => array ("", "textfield", ""),
	"hashkey" => array ("", "textfield", ""),

	"rcptto" => array ("", "textfield", ""),
	"subject" => array ("", "textfield", ""),
	"body" => array ("", "freetext_450", ""),

	"~1" => array ("&nbsp;", "ahref", $mtpl_popup_tpl),

	"admtail" => array ("", "textarea_3", ""),
	"sentmsg" => array ("", "textfield", ""),
	"published" => array ("", "checkbox", 1)
);
?>

<? include "_entity_edit.php" ?>
<? include "_top.php" ?>
<? include "_edit_fields.php" ?>
<? include "_bottom.php" ?>
