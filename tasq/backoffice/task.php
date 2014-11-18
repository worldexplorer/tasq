<? require_once "../_lib/_init.php" ?>
<?

//$debug_query = 1;

$tpl_ident_edit = <<<EOT
&nbsp;&nbsp;<a title="" href="#ENTITY#-edit.php?id=#ID#"><br><img hspace="5" height="7" width="7" vspace="2" align="absmiddle" style="border:0px solid #eeeeee" src="img/shortcut.gif">изменить</a>
EOT;

function project_group($row) {
	$ret = "";

	$project_ident = $row["project_ident"];
	$task = $row["id"];
	
	$query = <<<EOT
select t.ident
from tgroup t
inner join m2m_task_tgroup m2m on m2m.tgroup=t.id and m2m.task=$task
where m2m.published=1 and m2m.deleted=0
order by m2m.manorder
EOT;
	$tpl = "&nbsp;&#149;&nbsp;&nbsp;#IDENT#<br>";
	$ret = query_by_tpl($query, $tpl);
	
	$ret = <<<EOT
<font color=gray>$project_ident</font><br>
$ret
EOT;
	
	return $ret;
}

function status_by_date($row) {
	$ret = "";

	$task = $row["id"];
	
	$query = <<<EOT
select ts.ident, date_format(m2m.date_updated, '%a %d %b %H:%i') as date_updated
from tstatus ts
inner join m2m_task_tstatus m2m on m2m.tstatus=ts.id and m2m.task=$task
where m2m.published=1 and m2m.deleted=0
order by m2m.date_updated desc
EOT;
	$tpl = "<tr valign=top><td style='color:gray' nowrap>#DATE_UPDATED#</td><td></td><td nowrap>#IDENT#</td></tr>";
	$ret = query_by_tpl($query, $tpl);
	
	if ($ret != "") { 
	$ret = <<<EOT
<table cellpadding=0 cellspacing=0>
<tr><td></td><td width=10></td><td></td></tr> 
	$ret
</table>
EOT;
	}
	
	return $ret;	
}

$table_columns = array (
	"id" => array("№", $column_serno),
//	"date_created" => array("", "datetime"),
//	"date_updated" => array("", "datetime"),

//	"project_ident" => array("", "groupconcat"),
//	"tgroup_ident" => array("", "groupconcat"),
	"project_ident" => array("Цель / Задача", "ahref", "@project_group@"),

//	"ident" => array($entity_msg_h, "hrefedit", ""),
	"ident" => array($entity_msg_h, "ahref", "<a href=#ENTITY#-edit.php?id=#ID#>#IDENT#</a><div style='color:gray' align=right>обновлено #FORMATTED_DATE_UPDATED#</div>"),
//	"ident" => array($entity_msg_h, "textfield", "#INPUT#$tpl_ident_edit", "25em", "20em"),
//	"ident" => array($entity_msg_h, "ahref", "<a href='#ENTITY#-edit.php?id=#ID#'>#IDENT#</a><br><font color=darkgray>#pclass_ident#</font>"),
//	"article" => array("Артикул", "textfield", "#INPUT#", "6em", "6em"),

	//"tstatus_ident" => array("", "groupconcat"),
	"~tstatus_ident" => array("Статус", "ahref", "@status_by_date@"),

	"price_1" => array("Цена", "ahref", "#PRICE_1# #CURRENCY1_IDENT#"),
//	"price_1" => array("Цена", "number", "#INPUT#&nbsp;#CURRENCY1_IDENT# #PRICECOMMENT_1#", "8em", "4em"),
//	"price_2" => array("Цена2", "number", "#INPUT#&nbsp;#CURRENCY2_IDENT#", "7em", "4em"),
//	"price_3" => array("Цена3", "number", "#INPUT#&nbsp;#CURRENCY3_IDENT#", "7em", "4em"),

//	"taxrate_ident" => array("НДС", "viewcenter"),

//	"weight" => array("Вес", "viewcenter"),
//	"pclass_ident" => array("Тип", "viewcenter"),
//	"package_ident" => array("Упаков", "viewcenter"),
//	"saleunit_ident" => array("ЕдИзм", "viewcenter"),
//	"~3" => array("Упаков<br>ЕдИзм", "ahrefcenter", "#package_ident#<br><font color=darkgray>#saleunit_ident#</font>"),

//	"img_cnt" => array("Файлов", "viewcenter"),
	"news" => array("", "cnt", "нвc:"),
//	"task_rating_cnt" => array("Рей", "ahrefcenter", "<a href='m2m_task_rating.php?task=#ID#'>#task_RATING_CNT#</a>"),

//	"project_ident" => array("Произв", "viewcenter"),
//	"country_ident" => array("Страна", "viewcenter"),
//	"~2" => array("Произв<br>Страна", "ahrefcenter", "#project_ident#<br><font color=darkgray>#country_ident#</font>"),
//	"pmodel_ident" => array("Модель", "view"),

//	"mayorder" => array("Налич", "checkboxro"),
//	"hits" => array("", "viewcenter", "2em"),
//	"published" => array("", "checkbox"),
//	"i_published" => array("", "checkbox"),
//	"is_new" => array("New", "checkbox"),
	"~delete" => array("", "checkboxdel")
);

$list_left_fields = ", date_format(e.date_updated, '%a %d %b %H:%i') as formatted_date_updated";

$list_left_additional_fields = ""
	. ", cy1.ident as currency1_ident"
//	. ", cy2.ident as currency2_ident"
//	. ", cy3.ident as currency3_ident"
//	. ", tr.ident as taxrate_ident"
//	. ", pkg.ident as package_ident"
//	. ", ctry.ident as project_country_ident"
//	. ", count(distinct pr.id) as task_rating_cnt"
	;

$list_left_additional_joins = ""
	. " left outer join currency cy1 on e.currency_1=cy1.id"
//	. " left outer join currency cy2 on e.currency_2=cy2.id"
//	. " left outer join currency cy3 on e.currency_3=cy3.id"
//	. " left outer join taxrate tr on e.taxrate=tr.id"
//	. " left outer join package pkg on e.package=pkg.id"
//	. " left outer join project spl on e.project=spl.id"
//	. " left outer join country ctry on spl.country=ctry.id"
//	. " left outer join m2m_task_rating pr on pr.task=e.id and pr.deleted=0"
	;


//$debug_query = 1;


?>
<? require "_updown.php" ?>
<? require_once "_top.php" ?>
<? require "_list.php" ?>
<? require_once "_bottom.php" ?>
