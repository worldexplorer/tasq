<?

require_once "../_lib/_init.php";

$entity_fields = array (
	"ident" => array ("������", "textfield", ""),

	"date_exchrate_rub" => array("���� �����", "datetime", $today_datetime),
	"exchrate_rub" => array ("���� ��������<br>� �����", "textfield", ""),
	"exchrate_rub_multiplier" => array("���������", "textfield", ""),
//	"exchrate_rub_multiplier" => array("�������� ����", "textfield", ""),

	"hashkey" => array ("����", "textfield", ""),

//	"date_created" => array ("���� ��������", "timestamp_date"),


	"expiration_minutes" => array ("�������, ���", "number", 60, "��� ���. ����������: ���� �������� = ������ + �������"),
	"date_expiration" => array ("��������", "datetime", $today_datetime),
//	"date_expiration" => array ("��������", "timestamp", $today_ts_datetime, "��� ���������� ��������� �� #HOURS_EXPIRATION#"),
	"scriptname_updated" => array("�������", "textfield"),
	"date_updated" => array ("����������", "timestampro", ""),


	"src_href" => array("<a href='#SRC_HREF#' target=_blank>src_href</a>", "textfield", ""),
	"src_content" => array ("content<br>cached", "textarea_3", ""),
	"exchrate_regexp" => array ("exchrate_regexp", "textarea_3", ""),
	"daterate_regexp" => array ("daterate_regexp", "textarea_3", ""),

	"comment" => array ("Comment", "textarea_3", ""),

	"published" => array ("������������", "checkbox", 1),
);

?>

<? require "../_lib/_entity_edit.php" ?>
<? require_once "_top.php" ?>
<? require "../_lib/_edit_fields.php" ?>
<? require_once "_bottom.php" ?>