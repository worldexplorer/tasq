<?

require_once "../_lib/_init.php";

$entity_fields = array (
	"ident" => array ("��������", "textfield", ""),
	"hashkey" => array ("����", "textfield", ""),

	"brief" => array ("����", "freetext_200", ""),


	"ic" => array ("", "cnt", "#MASTER_ENTITY# ��� [#IDENT#]"),
	"icdict" => array ("", "cnt", "#MASTER_ENTITY# ��� [#IDENT#]"),

//	"~1" => array ("", "ahref", "<a href=ic.php?icwhose=$id>������������� ���� ������</a>"),
//	"~11" => array ("[@masterdepend_cnt@]", "ahref", "<a href='@masterdepend_entity@.php?#ENTITY#=#ID#'>@masterdepend_entity_hr@</a>"),

	"bo_only" => array("������ � ��", "checkbox", 0, "������ �� ����� ���������� � ����"),
	"jsv_debug" => array("�������", "checkbox", 0),
	"published" => array ("������������", "checkbox", 1)
);

?>

<? include "_entity_edit.php" ?>
<? include "_top.php" ?>
<? include "_edit_fields.php" ?>
<? include "_bottom.php" ?>
