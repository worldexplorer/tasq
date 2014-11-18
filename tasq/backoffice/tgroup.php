<?

require_once "../_lib/_init.php";

//$debug_query = 1;

$tpl_ident_edit = <<<EOT
&nbsp;&nbsp;<a title="" href="#ENTITY#-edit.php?id=#ID#"><img hspace="5" height="7" width="7" vspace="2" align="absmiddle" style="border:0px solid #eeeeee" src="img/shortcut.gif">изменить</a>
EOT;

$table_columns = array (
	"id" => array("№", "sernoupdown"),
	"date_created" => array("", "datetime"),
	"date_updated" => array("", "datetime"),
//	"banner" => array("", "tablero"),
//	"ident" => array($entity_msg_h, "hrefedit"),

//	"hashkey_pimport" => array("Имп", "textfield", "", "4em", "5em"),
//	"hashkey_pimport" => array("Имп", "view", "", "4em", "5em"),
//	"~2" => array($entity_msg_h, "ahref", "<a href=#ENTITY#-edit.php?id=#ID#>#IDENT#</a>"),
//	"ident" => array($entity_msg_h, "textfield", "", "17em", "16em"),
	"ident" => array($entity_msg_h, "textfield", "#INPUT#$tpl_ident_edit", "25em", "20em"),

//	"tgroup" => array("", "cnt", "подгруппы: "),
	"task" => array("", "cnt", "этапов: "),

//	"hits" => array("", "viewcenter", "2em"),
//	"published" => array("", "checkbox"),
//	"i_published" => array("", "checkbox"),
//	"projectdriven" => array ("Произв", "checkbox"),
	"published" => array("", "checkbox"),
	"~delete" => array("", "checkboxdel")
);


?>
<? require "_updown.php" ?>
<? require_once "_top.php" ?>
<? require "_list.php" ?>
<? require_once "_bottom.php" ?>
