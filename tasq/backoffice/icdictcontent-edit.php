<?

require_once "../_lib/_init.php";

$entity_fields = array (
	"ident" => array ("��������", "textarea_3", ""),
	"content" => array ("��������", "textarea", ""),

	"hashkey" => array ("����", "textfield", ""),
	"label_style" => array ("label style=[]", "textfield", ""),
	"tf1_width" => array ("����� - textfield �������", "number", 0),
	"tf1_incolumn" => array ("textfield � ��������� �������", "checkbox", 0),

	"tf1_addtodict" => array ("��������� ��������<br>�� textfield �����<br>� ���������� [#IDENT#]", "checkbox", 0),
	"tf1_addedpublished" => array ("������������ ��<br>����������� ��������", "checkbox", 0),

	"icdict" => array ("C���������", "select_table_all", "ident"),

	"published" => array ("������������", "checkbox", 1)
);
?>

<? include "_entity_edit.php" ?>
<? include "_top.php" ?>
<? include "_edit_fields.php" ?>
<? include "_bottom.php" ?>
