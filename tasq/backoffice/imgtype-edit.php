<?

require_once "../_lib/_init.php";

$hashkey_adding = ($id == 0) ? get_string("hashkey") : "";

$entity_fields = array (
	"ident" => array ("��������", "textfield", ""),
	"content" => array ("�����������", "textarea", ""),
	"hashkey" => array ("����", "textfield", $hashkey_adding),
	"imglimit" => array ("���-�� ��������", "number", "0", "����� �� ���-�� ���������� ��������; 0 - �� ����������"),

	"~52" => array ("&nbsp;", "ahref", "&nbsp;"),

	"~1_open" => array ("��������� �������� � ���������", "layer_open"),
	"img_present" => array ("���� ���������", "checkbox", 1, "��������� �� ���������->[Browse]"),
	"img_newqnty" => array ("���������� ������ ����� (Browse)", "number", 1, "���� ������� �������� ��������� ����� �����; 0=1"),
	"img_zip_present" => array ("���� �� ���� ZIP", "checkbox", 1, "��������� �� ���������->[Browse] ZIP ��� [�����]"),
	"img_url_present" => array ("���� �� ���� URL", "checkbox", 1, "��������� �� ���������->URL ��� ������� � [�����]"),
	"img_txt_present" => array ("���� �������", "checkbox", 1, "������� �� ���� ��� ������� ���������"),
	"img_txt_eq_fname" => array ("������� [������� = ��� �����]", "checkbox", 0, "������� �� �� ��������� [������� = ��� �����] ��� �����"),

	"resize_published" => array ("������� [���������] �� [�������]", "checkbox", 1, "��������� �� ���� ���� � ������������"),
	"resize_default_checked" => array ("��������� ������� [�������]", "checkbox", 0, "������� �� �� ��������� [������� ������] ��� �����"),
	"resize_default_qlty" => array ("�������� ������� [���������]", "number", "85", "�������� ���������� JPEG: 0&#8230;100"),
	"resize_default_width" => array ("������ ������� [���������]", "number", ""),
	"resize_default_height" => array ("������ ������� [���������]", "number", "80"),
	"~1_close" => array ("��������� �������� � ���������", "layer_close"),

	"~2_open" => array ("������� �������� � ���������", "layer_open"),
	"img_big_present" => array ("���� �������", "checkbox", 1, "��������� �� �������->[Browse]"),
	"img_big_newqnty" => array ("���������� ������ ����� (Browse)", "number", 1, "���� ������� �������� ��������� ����� �����; 0=1"),
	"img_big_zip_present" => array ("���� �� ���� ZIP", "checkbox", 1, "��������� �� �������->[Browse] ZIP ��� [�����]"),
	"img_big_url_present" => array ("���� �� ���� URL", "checkbox", 1, "��������� �� �������->URL ��� ������� � [�����]"),
	"img_big_txt_present" => array ("���� �������", "checkbox", 1, "������� �� ���� ��� ������� �������"),
	"img_big_txt_eq_fname" => array ("������� [������� = ��� �����]", "checkbox", 0, "������� �� �� ��������� [������� = ��� �����] ��� �����"),

	"big_resize_published" => array ("�������������� [�������] ��� �������", "checkbox", 1, "��������� �� ���� ���� � ������������"),
	"big_resize_default_checked" => array ("��������� ������� [��������������]", "checkbox", 0, "������� �� �� ��������� [������� ������] ��� �����"),
	"big_resize_default_qlty" => array ("�������� ������� [�������]", "number", "85", "�������� ���������� JPEG: 0&#8230;100"),
	"big_resize_default_width" => array ("������ ������� [�������]", "number", ""),
	"big_resize_default_height" => array ("������ ������� [�������]", "number", ""),
	"~2_close" => array ("������� �������� � ���������", "layer_close"),

	"~6_open" => array ("��������� � ���������", "layer_open"),
	"img_thumb_present" => array ("���� ���������", "checkbox", 1, "��������� �� ������ �� ����� ����������� ���������"),
	"img_thumb_qlty" => array ("�������� ������� [���������]", "number", "85", "�������� ���������� JPEG: 0&#8230;100"),
	"img_thumb_width" => array ("������ ������� [���������]", "number", ""),
	"img_thumb_height" => array ("������ ������� [���������]", "number", "80"),
	"~6_close" => array ("��������� � ���������", "layer_close"),

	"~51" => array ("&nbsp;", "ahref", "&nbsp;"),

	"~4_open" => array ("���������� ������ (�������� � ������ ������� ������)", "layer_open"),
	"first_autoresize_qlty" => array ("�������� �����������", "number", "80", "�������� ���������� JPEG: 0&#8230;100"),
	"first_autoresize_width" => array ("������ �����������", "number", ""),
	"first_autoresize_height" => array ("������ �����������", "number", ""),
//	"first_autoresize_firstonly" => array ("���������� ������ ������", "checkbox", 1, "��������� ����������� ����� �����"),
	"first_autoresize_apply" => array ("��������� ����������", "checkbox", 0, "��������� �� ����������� ��� ���������"),
	"~41" => array ("&nbsp;", "ahref"),
	"first_merge_img" => array ("���������", "image", 0, "��������� �� ����������� ��� ���������"),
	"first_merge_alfa" => array ("������������", "number", 30, "������������ ������������� ������ ��������: 0&#8230;100"),
//	"first_merge_type" => array ("����� ���������", "number", 0, "0=����������������� ������; 1=tile"),
	"first_merge_apply" => array ("����������� ���������", "checkbox", 1, "����� ������� ����-���������, � ����� ������ ��� �����"),
	"first_autoresize_debug" => array ("�������� �������", "checkbox", 0, "������� � ���� ��: �����������, ������ �� ������, �����������"),
	"first_autoresize_tpl_ex" => array ("������ ���<br>������������", "textarea_3"),
	"first_autoresize_tpl_nex" => array ("������ ���<br>��������������", "textarea_3"),
	"~4_close" => array ("���������� ������ (�������� � ������ ������� ������)", "layer_close"),

	"~3_open" => array ("���������� ������ (�������� ��������� � �������� ������)", "layer_open"),
	"every_autoresize_qlty" => array ("�������� �����������", "number", "80", "�������� ���������� JPEG: 0&#8230;100"),
	"every_autoresize_width" => array ("������ �����������", "number", ""),
	"every_autoresize_height" => array ("������ �����������", "number", ""),
//	"every_autoresize_firstonly" => array ("���������� ������ ������", "checkbox", 1, "��������� ����������� ����� �����"),
	"every_autoresize_apply" => array ("��������� ����������", "checkbox", 0, "��������� �� ����������� ��� ���������"),
	"every_merge_img" => array ("���������", "image", 0, "��������� �� ����������� ��� ���������"),
	"every_merge_alfa" => array ("������������", "number", 30, "������������ ������������� ������ ��������: 0&#8230;100"),
//	"every_merge_type" => array ("����� ���������", "number", 0, "0=����������������� ������; 1=tile"),
	"every_merge_apply" => array ("����������� ���������", "checkbox", 1, "����� ������� ����-���������, � ����� ������ ��� �����"),
	"every_autoresize_debug" => array ("�������� �������", "checkbox", 0, "������� � ���� ��: �����������, ������ �� ������, �����������"),
	"every_autoresize_tpl_ex" => array ("������ ���<br>������������", "textarea_3"),
	"every_autoresize_tpl_nex" => array ("������ ���<br>��������������", "textarea_3"),
	"~3_close" => array ("���������� ������ (�������� ��������� � �������� ������)", "layer_close"),

	"~53" => array ("&nbsp;", "ahref", "&nbsp;"),

	"~42_open" => array ("����������2 ������ (�������� � ������ ������� ������)", "layer_open"),
	"first2_autoresize_qlty" => array ("�������� �����������2", "number", "80", "�������� ���������� JPEG: 0&#8230;100"),
	"first2_autoresize_width" => array ("������ �����������2", "number", ""),
	"first2_autoresize_height" => array ("������ �����������2", "number", ""),
//	"first2_autoresize_firstonly" => array ("����������2 ������ ������", "checkbox", 1, "��������� ����������� ����� �����"),
	"first2_autoresize_apply" => array ("��������� ����������2", "checkbox", 0, "��������� �� ����������� ��� ���������"),
	"first2_merge_img" => array ("���������", "image", 0, "��������� �� ����������� ��� ���������"),
	"first2_merge_alfa" => array ("������������", "number", 30, "������������ ������������� ������ ��������: 0&#8230;100"),
//	"first2_merge_type" => array ("����� ���������", "number", 0, "0=����������������� ������; 1=tile"),
	"first2_merge_apply" => array ("����������� ���������", "checkbox", 1, "����� ������� ����-���������, � ����� ������ ��� �����"),
	"first2_autoresize_debug" => array ("�������� �������2", "checkbox", 0, "������� � ���� ��: �����������, ������ �� ������, �����������"),
	"first2_autoresize_tpl_ex" => array ("������ ���<br>������������", "textarea_3"),
	"first2_autoresize_tpl_nex" => array ("������ ���<br>��������������", "textarea_3"),
	"~42_close" => array ("����������2 ������ (�������� � ������ ������� ������)", "layer_close"),

	"~32_open" => array ("����������2 ������ (�������� ��������� � �������� ������)", "layer_open"),
	"every2_autoresize_qlty" => array ("�������� �����������2", "number", "80", "�������� ���������� JPEG: 0&#8230;100"),
	"every2_autoresize_width" => array ("������ �����������2", "number", ""),
	"every2_autoresize_height" => array ("������ �����������2", "number", ""),
//	"every2_autoresize_firstonly" => array ("����������2 ������ ������", "checkbox", 1, "��������� ����������� ����� �����"),
	"every2_autoresize_apply" => array ("��������� ����������2", "checkbox", 0, "��������� �� ����������� ��� ���������"),
	"every2_merge_img" => array ("���������", "image", 0, "��������� �� ����������� ��� ���������"),
	"every2_merge_alfa" => array ("������������", "number", 30, "������������ ������������� ������ ��������: 0&#8230;100"),
//	"every2_merge_type" => array ("����� ���������", "number", 0, "0=����������������� ������; 1=tile"),
	"every2_merge_apply" => array ("����������� ���������", "checkbox", 1, "����� ������� ����-���������, � ����� ������ ��� �����"),
	"every2_autoresize_debug" => array ("�������� �������2", "checkbox", 0, "������� � ���� ��: �����������, ������ �� ������, �����������"),
	"every2_autoresize_tpl_ex" => array ("������ ���<br>������������", "textarea_3"),
	"every2_autoresize_tpl_nex" => array ("������ ���<br>��������������", "textarea_3"),
	"~32_close" => array ("����������2 ������ (�������� ��������� � �������� ������)", "layer_close"),

	"~54" => array ("&nbsp;", "ahref", "&nbsp;"),


	"~52_open" => array ("����������3 ������ (�������� � ������ ������� ������)", "layer_open"),
	"first3_autoresize_qlty" => array ("�������� �����������3", "number", "80", "�������� ���������� JPEG: 0&#8230;100"),
	"first3_autoresize_width" => array ("������ �����������3", "number", ""),
	"first3_autoresize_height" => array ("������ �����������3", "number", ""),
//	"first3_autoresize_firstonly" => array ("����������3 ������ ������", "checkbox", 1, "��������� ����������� ����� �����"),
	"first3_autoresize_apply" => array ("��������� ����������3", "checkbox", 0, "��������� �� ����������� ��� ���������"),
	"first3_merge_img" => array ("���������", "image", 0, "��������� �� ����������� ��� ���������"),
	"first3_merge_alfa" => array ("������������", "number", 30, "������������ ������������� ������ ��������: 0&#8230;100"),
//	"first3_merge_type" => array ("����� ���������", "number", 0, "0=����������������� ������; 1=tile"),
	"first3_merge_apply" => array ("����������� ���������", "checkbox", 1, "����� ������� ����-���������, � ����� ������ ��� �����"),
	"first3_autoresize_debug" => array ("�������� �������3", "checkbox", 0, "������� � ���� ��: �����������, ������ �� ������, �����������"),
	"first3_autoresize_tpl_ex" => array ("������ ���<br>������������", "textarea_3"),
	"first3_autoresize_tpl_nex" => array ("������ ���<br>��������������", "textarea_3"),
	"~52_close" => array ("����������3 ������ (�������� � ������ ������� ������)", "layer_close"),

	"~62_open" => array ("����������3 ������ (�������� ��������� � �������� ������)", "layer_open"),
	"every3_autoresize_qlty" => array ("�������� �����������3", "number", "80", "�������� ���������� JPEG: 0&#8230;100"),
	"every3_autoresize_width" => array ("������ �����������3", "number", ""),
	"every3_autoresize_height" => array ("������ �����������3", "number", ""),
//	"every3_autoresize_firstonly" => array ("����������3 ������ ������", "checkbox", 1, "��������� ����������� ����� �����"),
	"every3_autoresize_apply" => array ("��������� ����������3", "checkbox", 0, "��������� �� ����������� ��� ���������"),
	"every3_merge_img" => array ("���������", "image", 0, "��������� �� ����������� ��� ���������"),
	"every3_merge_alfa" => array ("������������", "number", 30, "������������ ������������� ������ ��������: 0&#8230;100"),
//	"every3_merge_type" => array ("����� ���������", "number", 0, "0=����������������� ������; 1=tile"),
	"every3_merge_apply" => array ("����������� ���������", "checkbox", 1, "����� ������� ����-���������, � ����� ������ ��� �����"),
	"every3_autoresize_debug" => array ("�������� �������3", "checkbox", 0, "������� � ���� ��: �����������, ������ �� ������, �����������"),
	"every3_autoresize_tpl_ex" => array ("������ ���<br>������������", "textarea_3"),
	"every3_autoresize_tpl_nex" => array ("������ ���<br>��������������", "textarea_3"),
	"~62_close" => array ("����������3 ������ (�������� ��������� � �������� ������)", "layer_close"),

	"~64" => array ("&nbsp;", "ahref", "&nbsp;"),
	
	
	
	"~72_open" => array ("����������4 ������ (�������� � ������ ������� ������)", "layer_open"),
	"first4_autoresize_qlty" => array ("�������� �����������4", "number", "80", "�������� ���������� JPEG: 0&#8230;100"),
	"first4_autoresize_width" => array ("������ �����������4", "number", ""),
	"first4_autoresize_height" => array ("������ �����������4", "number", ""),
//	"first4_autoresize_firstonly" => array ("����������4 ������ ������", "checkbox", 1, "��������� ����������� ����� �����"),
	"first4_autoresize_apply" => array ("��������� ����������4", "checkbox", 0, "��������� �� ����������� ��� ���������"),
	"first4_merge_img" => array ("���������", "image", 0, "��������� �� ����������� ��� ���������"),
	"first4_merge_alfa" => array ("������������", "number", 30, "������������ ������������� ������ ��������: 0&#8230;100"),
//	"first4_merge_type" => array ("����� ���������", "number", 0, "0=����������������� ������; 1=tile"),
	"first4_merge_apply" => array ("����������� ���������", "checkbox", 1, "����� ������� ����-���������, � ����� ������ ��� �����"),
	"first4_autoresize_debug" => array ("�������� �������4", "checkbox", 0, "������� � ���� ��: �����������, ������ �� ������, �����������"),
	"first4_autoresize_tpl_ex" => array ("������ ���<br>������������", "textarea_3"),
	"first4_autoresize_tpl_nex" => array ("������ ���<br>��������������", "textarea_3"),
	"~72_close" => array ("����������4 ������ (�������� � ������ ������� ������)", "layer_close"),

	"~82_open" => array ("����������4 ������ (�������� ��������� � �������� ������)", "layer_open"),
	"every4_autoresize_qlty" => array ("�������� �����������4", "number", "80", "�������� ���������� JPEG: 0&#8230;100"),
	"every4_autoresize_width" => array ("������ �����������4", "number", ""),
	"every4_autoresize_height" => array ("������ �����������4", "number", ""),
//	"every4_autoresize_firstonly" => array ("����������4 ������ ������", "checkbox", 1, "��������� ����������� ����� �����"),
	"every4_autoresize_apply" => array ("��������� ����������4", "checkbox", 0, "��������� �� ����������� ��� ���������"),
	"every4_merge_img" => array ("���������", "image", 0, "��������� �� ����������� ��� ���������"),
	"every4_merge_alfa" => array ("������������", "number", 30, "������������ ������������� ������ ��������: 0&#8230;100"),
//	"every4_merge_type" => array ("����� ���������", "number", 0, "0=����������������� ������; 1=tile"),
	"every4_merge_apply" => array ("����������� ���������", "checkbox", 1, "����� ������� ����-���������, � ����� ������ ��� �����"),
	"every4_autoresize_debug" => array ("�������� �������4", "checkbox", 0, "������� � ���� ��: �����������, ������ �� ������, �����������"),
	"every4_autoresize_tpl_ex" => array ("������ ���<br>������������", "textarea_3"),
	"every4_autoresize_tpl_nex" => array ("������ ���<br>��������������", "textarea_3"),
	"~82_close" => array ("����������4 ������ (�������� ��������� � �������� ������)", "layer_close"),

	"~84" => array ("&nbsp;", "ahref", "&nbsp;"),




	"~9_open" => array ("������� � ���������", "layer_open"),
	"msg_ident" => array ("������� [��������]", "textfield", "��������"),
	"msg_add" => array ("������� [����� ��������]", "textfield", "����� ��������"),
	"msg_change" => array ("������� [�������� ��������]", "textfield", "�������� ��������"),
	"msg_img" => array ("������� [���������]", "textfield", "���������"),
	"msg_img_big" => array ("������� [�������]", "textfield", "�������"),
	"~9_close" => array ("������� � ���������", "layer_close"),

	"~91" => array ("&nbsp;", "ahref", "&nbsp;"),

	"img_table" => array ("������� � ����������<br>img ���� �����", "textfield", ""),
	"merge_seed" => array ("Merge Seed", "number", "", "������������ ��� �������������� ��������"),
	"date_updated" => array ("���������", "timestampro", "", "����������� ������ ���� ���� � ����� ����������������"),
//	"date_created" => array ("C�������", "timestampro", ""),
//	"published" => array ("������������", "checkbox", 1)
);

jsv_addvalidation("JSV_TF_CHAR", "ident", "��������");
jsv_addvalidation("JSV_TF_CHAR", "hashkey", "����");

?>

<? require "_entity_edit.php" ?>
<? require_once "_top.php" ?>
<? require "_edit_fields.php" ?>
<? require_once "_bottom.php" ?>
