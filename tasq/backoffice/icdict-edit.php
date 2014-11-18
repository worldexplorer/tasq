<?

require_once "../_lib/_init.php";

$fixed_fields = array("icwhose");

$entity_fields = array (
	"ident" => array ("Название", "textfield", ""),
	"hashkey" => array ("Ключ", "textfield", ""),

//	"~11" => array ("[@masterdepend_cnt@]", "ahref", "<a href='@masterdepend_entity@.php?#ENTITY#=#ID#'>@masterdepend_entity_hr@</a>"),
//	"~1" => array ("", "ahref", "<a href='icdictcontent.php?icdict=$id'>Редактировать значения справочника</a>"),

	"icdictcontent" => array ("", "cnt", "#MASTER_ENTITY# справочника [#IDENT#]"),

	"icwhose" => array ("Чей справочник", "select_hard", "ident"),

	"published" => array ("Опубликовано", "checkbox", 1)
);
?>

<? include "_entity_edit.php" ?>
<? include "_top.php" ?>
<? include "_edit_fields.php" ?>
<? include "_bottom.php" ?>
