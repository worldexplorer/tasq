<? require_once "../_lib/_init.php" ?>
<?

$no_add = 1;
$entity_orderby_list[$entity] = "i_manorder " . get_entity_orderdir($entity);
$manorder_move_page_tpl = "#ENTITY#-onindex";
$spanstyle = "width:9em;";

$fixed_fields = array("i_published");
$_REQUEST["i_published"] = 1;


$table_columns = array (
	"id" => array("№", "sernoupdown"),
//	"banner" => array("", "tablero"),
//	"ident" => array($entity_msg_h, "hrefedit"),

//	"hashkey_pimport" => array("Имп", "textfield", "", "4em", "5em"),
//	"hashkey_pimport" => array("Имп", "view", "", "4em", "5em"),
	"~2" => array($entity_msg_h, "ahref", "<a href=#ENTITY#-edit.php?id=#ID#>#IDENT#</a>"),
	"ident" => array($entity_msg_h, "textfield", "", "17em", "16em"),

	"tgroup" => array("Подгруппы", "cnt", "подгруппы:&nbsp;"),
	"task" => array("", "cnt"),

	"hits" => array("", "viewcenter", "2em"),
	"published" => array("", "checkbox"),
	"i_published" => array("", "checkbox"),
//	"projectdriven" => array ("Произв", "checkbox"),
	"published" => array("", "checkbox"),
	"~delete" => array("", "checkboxdel")
);
/*
$list_query = "select e.*, pg.ident as tgroup_ident"
	. " from $entity e"
	. " where i_published=1 $fixed_cond"
	. " and e.deleted=0 and pg.deleted=0"
	. " order by e." . get_entity_orderby($entity)
	;
$list_query_cnt = "select count(e.id) as cnt"
	. " from $entity e"
	. " where i_published=1 $fixed_cond"
	. " and e.deleted=0 and pg.deleted=0"
	. " order by e." . get_entity_orderby($entity)
	;
*/
?>

<?
$manorder_field = "i_manorder";
require "_updown.php";
$manorder_field = "manorder";
?>

<? require_once "_top.php" ?>
<? require "_list.php" ?>
<? require_once "_bottom.php" ?>