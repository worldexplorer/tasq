<? require_once "../_lib/_init.php" ?>
<?

$entity_fields = array (
	"ident" => array ("", "textfield", ""),

	"date_published" => array ("", "timestamp_date"),
	"brief" => array ("", "freetext_200", ""),

//	"~11" => array ("[@masterdepend_cnt@]", "ahref", "<a href='@masterdepend_entity@.php?#ENTITY#=#ID#'>@masterdepend_entity_hr@ данной группы</a>"),
//	"news" => array ("", "cnt", "#MASTER_ENTITY# группы [#IDENT#]"),
	"news" => array ("", "cnt"),

	"parent_id" => array ("Родитель", "select_table_tree_root", $entity),

	"~service_layer-open" => array ("", "layer_open"),
	"pagetitle" => array ("", "textfield", ""),
	"title" => array ("", "textfield", ""),
	"meta_keywords" => array ("", "textfield", ""),
	"meta_description" => array ("", "textfield", ""),

	"date_created" => array ("", "timestampro", ""),
	"date_updated" => array ("", "timestampro", ""),

	"hits" => array ("", "textfield"),
	//"archived" => array("", "checkbox"),
	//"is_new" => array ("", "checkbox", 0),
	"~service_layer-close" => array ("", "layer_close"),

	"published" => array ("", "checkbox", 1)
);

?>

<? require "_entity_edit.php" ?>
<? require_once "_top.php" ?>
<? require "_edit_fields.php" ?>
<? require_once "_bottom.php" ?>
