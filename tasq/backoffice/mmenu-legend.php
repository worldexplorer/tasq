<? require_once "../_lib/_init.php" ?>
<?

//$entity_fixed_list["mmenu"] = array();
//$fixed_hash = array();
//require "__fixed.php";

$table_columns = array (
	"id" => array("", "sernoupdown"),
	"ident" => array($entity_msg_h, "hrefedit"),
//	"~2" => array("Подпункты", "recursive"),
	"~2" => array("", "ahref", "<center><a href='#ENTITY#-legend.php?parent_id=#ID#'>подпункты: #mmenu_cnt#</a></center>", "8em"),
	"published" => array("", "checkbox"),
	"published_legend" => array("В&nbsp;легенде", "checkbox"),
	"~delete" => array("", "checkboxdel")
);

$entity_orderby_list[$entity] = "manorder_legend " . get_entity_orderdir($entity);
$manorder_move_page_tpl = "#ENTITY#-legend";

$list_query_cond = " and e.published_legend=1";
//$debug_query = 1;

?>
<?
$manorder_field = "manorder_legend";
require "_updown.php";
$manorder_field = "manorder_legend";
?>
<? require_once "_top.php" ?>
<? require "_list.php" ?>
<? require_once "_bottom.php" ?>
