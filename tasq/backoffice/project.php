<?

require_once "../_lib/_init.php";

$tpl_ident_edit = <<<EOT
&nbsp;&nbsp;<a title="" href="#ENTITY#-edit.php?id=#ID#"><img hspace="5" height="7" width="7" vspace="2" align="absmiddle" style="border:0px solid #eeeeee" src="img/shortcut.gif">изменить</a>
EOT;

$table_columns = array (
	"id" => array("№", "sernoupdown"),
	"date_created" => array("", "datetime"),
	"date_updated" => array("", "datetime"),
//	"ident" => array("$entity_msg_h", "hrefedit"),

//	"~2" => array($entity_msg_h, "ahref", "<a href=#ENTITY#-edit.php?id=#ID#>#IDENT#</a>"),
//	"ident" => array($entity_msg_h, "textfield", "", "17em", "16em"),
	"ident" => array($entity_msg_h, "textfield", "#INPUT#$tpl_ident_edit", "25em", "20em"),

//	"~3" => array("Моделей", "depend"),
//	"~4" => array("Товаров", "ahref", "<center><a href=task.php?supplier=#ID#>Товаров: #product_cnt#</a></center>", "9em"),
	"task" => array("", "cnt", "этапов: "),

//	"published" => array("", "checkbox"),
	"~delete" => array("", "checkboxdel")
);

?>

<? require "_updown.php" ?>
<? require_once "_top.php" ?>
<? require "_list.php" ?>
<? require_once "_bottom.php" ?>