<? require_once "../_lib/_init.php" ?>
<?

$datetime_fmt = $date_fmt;

$table_columns = array (
	"id" => array("�", "sernoupdown"),
	"date_published" => array("����", "date"),
	"ngroup_ident" => array("�����", "viewcenter"),
//	"ugroup_ident" => array("�����������", "groupconcat"),
//	"cgroup_ident" => array("������������", "groupconcat"),
	"task_ident" => array("������", "groupconcat"),
	"ident" => array($entity_msg_h, "hrefedit"),

//	"hits" => array("Hits", "viewcenter", "2em"),
	"published" => array("�����", "checkbox"),
//	"i_published" => array("������", "checkbox"),
	"~1" => array("����", "checkboxdel")

//	"rsss_ident" => array("RSS-��������", "ahref", "<a href='rsss-edit.php?id=#rsss_id#'>#rsss_ident#</a>"),
//	"rss_published" => array("RSS", "checkbox"),
//	"archived" => array("�����", "checkboxro"),

);


?>

<? require "_updown.php" ?>
<? require_once "_top.php" ?>
<? require "_list.php" ?>
<? require_once "_bottom.php" ?>