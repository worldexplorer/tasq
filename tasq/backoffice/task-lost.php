<? require_once "../_lib/_init.php" ?>
<?


$table_columns = array (
	"id" => array("№", $column_serno),
	"date_created" => array("Дата создания", "timestamp"),
	"date_updated" => array("Дата обновления", "timestamp"),
//	"date_published" => array("Дата", "date"),

//	"project_ident" => array("Носитель", "ahref", "#project_IDENT# (#IMG_CNT#) : #PMODEL_IDENT#", "15em"),
//	"project_ident" => array("Производитель", "ahref", "#project_IDENT#", "15em"),

	"tgroup_ident" => array("Раздел", "viewcenter"),		//, "10em"
//	"pmodel_ident" => array("Носитель", "viewcenter", "8em"),
//	"project_ident" => array("Источник", "viewcenter", "10em"),

	"ident" => array($entity_msg_h, "hrefedit", ""),

//	"img_cnt" => array("Файлы", "viewcenter", "3em"),

//	"pmodel_ident" => array("Модель", "view"),
//	"ident" => array($entity_msg_h, "ahref", "<a href='#ENTITY#-edit.php?id=#ID#&tgroup=#tgroup#&project=#project#&pmodel=#PMODEL#'>#project_IDENT# #PMODEL_IDENT# #IDENT#</a>"),

	"published" => array("Опубл", "checkbox"),
//	"i_published" => array("НаГлав", "checkboxro"),
//	"archived" => array("Устаревшее", "checkboxro"),
	"~1" => array("Удал", "checkboxdel")
);


//$debug_query = 1;

/*
$list_left_additional_joins .= ""
	. " left outer join m2m_task_tgroup m2m on e.id=m2m.task"
	;

$list_left_additional_fields .= ""
	. ", count(m2m.id) as tgroup_links"
	;

//$list_query_cond = " having count(m2m.id) = 0";
*/



$list_query_cnt = "select count(e.id) as cnt"
	. " from task e"
	. " left join m2m_task_tgroup m2m_tgroup on m2m_tgroup.task=e.id and m2m_tgroup.deleted=0"
	. " left join tgroup tgroup on tgroup.id=m2m_tgroup.tgroup"
	. " left outer join m2m_task_tgroup m2m on e.id=m2m.task"
	. " where e.deleted=0"
	. " having count(m2m.id)=0"
	;



$list_query = "select e.*, tgroup.ident as tgroup_ident"
//	. ", pmodel.ident as pmodel_ident
//	. ", project.ident as project_ident"
	. ", count(m2m.id) as tgroup_links, count(distinct img.id) as img_cnt"
	. " from task e"
//	. " left join pmodel pmodel on e.pmodel=pmodel.id"
//	. " left join project project on e.project=project.id"
	. " left join img img on img.owner_entity='task' and img.owner_entity_id=e.id"
	. " left join m2m_task_tgroup m2m_tgroup on m2m_tgroup.task=e.id and m2m_tgroup.deleted=0"
	. " left join tgroup tgroup on tgroup.id=m2m_tgroup.tgroup"
	. " left outer join m2m_task_tgroup m2m on e.id=m2m.task"
	. " where 1=1 and e.deleted=0"
	. " group by e.id"
	. " having count(m2m.id)=0"
	. " order by e.date_created desc"
//	. " limit 20"
	;


/* sarges

$list_query_cnt = "select count(e.id) as cnt"
	. " from task e"
//	. " left join m2m_task_tgroup m2m_tgroup on m2m_tgroup.task=e.id and m2m_tgroup.published=1 and m2m_tgroup.deleted=0"
	. " left join m2m_task_tgroup m2m_tgroup on m2m_tgroup.task=e.id"
	. " left join tgroup tgroup on tgroup.id=m2m_tgroup.tgroup"
//	. " left outer join m2m_task_tgroup m2m on e.id=m2m.task"
	. " where e.deleted=0"
	. " and m2m_tgroup.published=1 and m2m_tgroup.deleted=0"
	. " having count(m2m_tgroup.id)=0"
	;


$list_query = "select e.*, tgroup.ident as tgroup_ident, pmodel.ident as pmodel_ident"
		. ", project.ident as project_ident, count(m2m_tgroup.id) as tgroup_links, count(distinct img.id) as img_cnt"
	. " from task e"
	. " left join pmodel pmodel on e.pmodel=pmodel.id"
	. " left join project project on e.project=project.id"
	. " left join img img on img.owner_entity='task' and img.owner_entity_id=e.id"
	. " left join m2m_task_tgroup m2m_tgroup on m2m_tgroup.task=e.id"
	. " left join tgroup tgroup on tgroup.id=m2m_tgroup.tgroup"
//	. " left outer join m2m_task_tgroup m2m on e.id=m2m.task"
	. " where 1=1 and e.deleted=0"
	. " and m2m_tgroup.published=1 and m2m_tgroup.deleted=0"
	. " group by e.id"
	. " having count(m2m_tgroup.id)=0"
	. " order by e.date_created desc"
//	. " limit 20"
	;

*/

?>
<? require "_updown.php" ?>
<? require_once "_top.php" ?>
<? require "_list.php" ?>
<? require_once "_bottom.php" ?>
