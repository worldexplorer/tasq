<?

require_once "../_lib/_init.php";

$table_columns = array (
	"id" => array("�", "sernoupdown"),

	"icwhose_ident" => array("������", "viewcenter"),
	"ictype_ident" => array("��� ����", "viewcenter"),
	"ident" => array($entity_msg_h, "hrefedit"),
	"icdict_ident" => array("����������", "ahrefcenter", "<a href=icdictcontent.php?icdict=#ICDICT#>#ICDICT_IDENT#</a>"),

	"hashkey" => array("����", "view"),
	"obligatory" => array("����", "checkbox"),
	"obligatory_bo" => array("������", "checkbox"),
	"inbrief" => array("�������", "checkbox"),
	"sorting" => array("����", "checkbox"),
	"published" => array("�����", "checkbox"),
	"published_bo" => array("������", "checkbox"),
	"~1" => array("����", "checkboxdel")
);

//$debug_query = 1;
$list_left_additional_fields = " , icd.ident as icdict_ident, count(distinct icdc.id) as icdictcontent_cnt";
$list_left_additional_joins = " left outer join icdict icd on e.icdict=icd.id and icd.deleted=0"
	. " left outer join icdictcontent icdc on icdc.icdict=icdc.id and icdc.deleted=0"
	;

?>

<? require "_updown.php" ?>
<? require_once "_top.php" ?>
<? require "_list.php" ?>
<? require_once "_bottom.php" ?>