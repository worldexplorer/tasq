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
//	"date_created" => array("Дата создания", "timestamp"),
//	"date_updated" => array("Дата обновления", "timestamp"),
//	"ident" => array($entity_msg_h, "ahref", "<a href='#ENTITY#-edit.php?id=#ID#{$fixed_suffix}'>#project_IDENT# #IDENT#</a>"),

//	"article" => array("Артикул", "view"),
//	"brand_ident" => array("Производитель", "view"),
	"tgroup_ident" => array("Группа", "view"),
//	"ident" => array($entity_msg_h, "hrefedit"),
	"ident" => array($entity_msg_h, "ahref", "<a href='#ENTITY#-edit.php?id=#ID#'>#IDENT#</a>"),
	"price_1" => array("Цена", "ahref", "<center>#PRICE_1#</center>"),

//	"hits" => array("Просмотров", "ahref", "<center>#HITS#</center>"),
	"published" => array("Опубл", "checkboxro"),
	"i_published" => array("НаГлав", "checkboxro"),
	"mayorder" => array("Налич", "checkboxro"),
	"~1" => array("Удал", "checkboxdel")
);

$list_query = "select e.*, pg.ident as tgroup_ident"
	. " from $entity e"
	. " left outer join tgroup pg on e.tgroup=pg.id"
	. " where i_published=1 $fixed_cond"
	. " and e.deleted=0 and pg.deleted=0"
	. " order by e." . get_entity_orderby($entity)
	;
$list_query_cnt = "select count(e.id) as cnt"
	. " from $entity e"
	. " left outer join tgroup pg on e.tgroup=pg.id"
	. " where i_published=1 $fixed_cond"
	. " and e.deleted=0 and pg.deleted=0"
	. " order by e." . get_entity_orderby($entity)
	;

?>

<?
$manorder_field = "i_manorder";
require "_updown.php";
$manorder_field = "manorder";
?>

<? require_once "_top.php" ?>
<? require "_list.php" ?>
<? require_once "_bottom.php" ?>