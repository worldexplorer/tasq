<?

require_once "../_lib/_init.php";

$entity_fields = array (
	"ident" => array ("Название", "textfield", ""),
	"hashkey" => array ("Ключ", "textfield", ""),

	"brief" => array ("Бриф", "freetext_200", ""),


	"ic" => array ("", "cnt", "#MASTER_ENTITY# для [#IDENT#]"),
	"icdict" => array ("", "cnt", "#MASTER_ENTITY# для [#IDENT#]"),

//	"~1" => array ("", "ahref", "<a href=ic.php?icwhose=$id>Редактировать поля анкеты</a>"),
//	"~11" => array ("[@masterdepend_cnt@]", "ahref", "<a href='@masterdepend_entity@.php?#ENTITY#=#ID#'>@masterdepend_entity_hr@</a>"),

	"bo_only" => array("Только в БО", "checkbox", 0, "анкета не будет выводиться с лица"),
	"jsv_debug" => array("Отладка", "checkbox", 0),
	"published" => array ("Опубликовано", "checkbox", 1)
);

?>

<? include "_entity_edit.php" ?>
<? include "_top.php" ?>
<? include "_edit_fields.php" ?>
<? include "_bottom.php" ?>
