<?

require_once "../_lib/_init.php";

$table_columns = array (
	"id" => array("№", "ahref", "<center>#ID#</center>", "4ex"),
	"date_created" => array("Дата создания", "timestamp"),
//	"date_updated" => array("Дата обновления", "timestamp"),
	"icwhose_ident" => array("Анкеты", "view"),
	"ident" => array($entity_msg_h, "hrefedit"),
	"published" => array("Опубл", "checkboxro"),
	"~1" => array("Удал", "checkboxdel")
);

require_once "../_lib/__fixed.php";
$list_query = "select ics.*, icw.ident as icwhose_ident"
	. " from icsheet ics"
	. " left outer join icwhose icw on ics.icwhose=icw.id"
	. " where ics.deleted=0 " . sqlcond_fromhash($fixed_hash, "ics", " and ")
	. " order by ics." . get_entity_orderby("icsheet")
	;

?>

<? require "_updown.php" ?>
<? require_once "_top.php" ?>
<? require "_list.php" ?>
<? require_once "_bottom.php" ?>