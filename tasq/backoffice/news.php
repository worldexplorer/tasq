<? require_once "../_lib/_init.php" ?>
<?

$datetime_fmt = $date_fmt;

$table_columns = array (
	"id" => array("№", "sernoupdown"),
	"date_published" => array("Дата", "date"),
	"ngroup_ident" => array("Лента", "viewcenter"),
//	"ugroup_ident" => array("ЦелевГруппа", "groupconcat"),
//	"cgroup_ident" => array("КлиентГруппа", "groupconcat"),
	"task_ident" => array("Задачи", "groupconcat"),
	"ident" => array($entity_msg_h, "hrefedit"),

//	"hits" => array("Hits", "viewcenter", "2em"),
	"published" => array("Опубл", "checkbox"),
//	"i_published" => array("НаГлав", "checkbox"),
	"~1" => array("Удал", "checkboxdel")

//	"rsss_ident" => array("RSS-источник", "ahref", "<a href='rsss-edit.php?id=#rsss_id#'>#rsss_ident#</a>"),
//	"rss_published" => array("RSS", "checkbox"),
//	"archived" => array("Архив", "checkboxro"),

);


?>

<? require "_updown.php" ?>
<? require_once "_top.php" ?>
<? require "_list.php" ?>
<? require_once "_bottom.php" ?>