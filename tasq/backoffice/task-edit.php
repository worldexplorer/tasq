<? require_once "../_lib/_init.php" ?>
<?

$m2mcb_colcnt = 4;
//$radio_colcnt = 7;
$radio_colcnt = 1;
//$debug_query = 1;

$currency_id_first = 3;
if ($id == 0) {
	$currency_id_first = select_first_published("id", array(), "currency");
}

$backrow_tpl = <<< EOT
<tr bgcolor="#SHEET_ROW_BGCOLOR#" title="#IT_GRAYCOMMENT#">
	<td align=right width=1% nowrap>#OBLIGATORY_SIGN# <font class="name"><label for="#IT_NAME#">#IT_TXT#</label></font></td>
	<td>#IT_WRAPPED# #IT_GRAYCOMMENT_GRAY#</td>
</tr>
EOT;
$backrow_obligatory_sign = "<font color=red title='это поле обязательно для заполнения'>*</font>&nbsp;&nbsp;";

$icwhose_id_properties = select_field("id", array("hashkey" => "TASK_PROPERTIES"), "icwhose");

$m2m_table = "m2m_{$entity}_iccontent";
$m2m_fixedhash = array();
$absorbing_fixedhash = array($entity => "_global:id");


// 4 multicomposite tree at left in bo
//if ($id > 0 && $mode != "update") $_REQUEST["tgroup"] = select_field("tgroup");


$entity_fields = array (
	"ident" => array ("Название", "textfield", ""),

	"tstatus" => array ("", "m2mcb", "m2m_task_tstatus"),

	"~6_open" => array ("Детальная однозначная конечная постановка задачи", "layer_open"),
	"request" => array ("Задача", "freetext_200", ""),
	//"~3_open" => array ("Привязка задачи к группе задач", "layer_open"),
	"project" => array ("", "radio_table", "ident", ""),
	"tgroup" => array ("", "m2mcb", "m2m_task_tgroup"),
	//"~3_close" => array ("Привязка задачи к группе задач", "layer_close"),
	"~6_close" => array ("Детальная однозначная конечная постановка задачи", "layer_close"),

	"~11" => array("IMG_TASK", "img_layer"),
	"~12" => array("ATTACHMENTS_TASK", "img_layer"),

	"~7_open" => array ("Обсуждение", "layer_open"),
	"discussion" => array ("Обсуждение", "freetext", ""),
	"~7_close" => array ("Обсуждение", "layer_close"),

	"~9_open" => array ("Оценка выполнения задачи", "layer_open"),
	"~61_open" => array ("", "columned_open"),
	"price_1" => array ("Цена", "number", ""),
	"currency_1" => array ("Валюта", "select_soft", "ident", ""),
	"pricecomment_1" => array ("Комментарий к цене", "number", "", ""),
//	"pricecomment_1" => array ("Комментарий к цене", "textfield", "", ""),
	"efforts_1" => array ("Усилия, часов", "number", ""),

	"~61_close" => array ("", "columned_close"),	

	"~62_open" => array ("", "columned_open"),
	"price_2" => array ("Цена 2", "number", ""),
	"currency_2" => array ("Валюта 2", "select_soft", "ident", ""),
	"pricecomment_2" => array ("Комментарий к цене 2", "number", "", ""),
	"efforts_2" => array ("Усилия 2, часов", "number", ""),
	"~62_close" => array ("", "columned_close"),	


	"~63_open" => array ("", "columned_open"),
	"price_3" => array ("Цена 3", "number", ""),
	"currency_3" => array ("Валюта 3", "select_soft", "ident", ""),
	"pricecomment_3" => array ("Комментарий к цене 3", "number", "", ""),
	"efforts_3" => array ("Усилия 3, часов", "number", ""),
	"~63_close" => array ("", "columned_close"),

//	"mayorder" => array("Наличие 1", "checkbox", 1, "нет = цена заменяется надписью [нет в наличии]"),
//	"tgroup" => array ("Группа", "select_table_tree", "ident", $tgroup),

	"~9_close" => array ("Стоимость выполнения задачи", "layer_close"),

	"~3_open" => array ("Выполненная задача", "layer_open"),
	"response" => array ("Выполненная задача", "freetext", ""),
	"~10" => array("IMG_CONTENT", "img_layer"),
	"~3_close" => array ("Выполненная задача", "layer_close"),


/*
	"tgrouptreeselectable" => array ("Товар<br>присутствует<br>в группах",
							"multicompositecontent", "m2m_task_tgroup",
							array("tgroup"), 1),
*/


//	"~3" => array ("Свойства задачи", "multicompositeiccontent"
//		, $m2m_table, $m2m_fixedhash, $absorbing_fixedhash, $icwhose_id_properties, "добавить новые свойства задачи"),


	"~4_open" => array ("Привязка задачи к новостям", "layer_open"),
	"news" => array ("Новости", "m2mcb", "m2m_task_news"),
	"~4_close" => array ("Привязка задачи к новостям", "layer_close"),

		
/*
	"~4_open" => array ("Необходимые и сопутствующие связанные товары", "layer_open"),
	"tgrouptreetaskselectable_1" => array ("Необходимые<br>связанные<br>товары",
							"multicompositebidirect", "m2m_task_task_necessary",
							array("task"), 1),

	"tgrouptreetaskselectable_2" => array ("Сопутствующие<br>связанные<br>товары",
							"multicompositebidirect", "m2m_task_task_accompanied",
							array("task"), 1),
	"~4_close" => array ("Необходимые и сопутствующие связанные товары", "layer_close"),
*/
//	"~5_open" => array ("Сопутствующие связанные товары", "layer_open"),
//	"~5_close" => array ("Сопутствующие связанные товары", "layer_close"),

/*	"~service_layer-open" => array ("", "layer_open"),
	"pagetitle" => array ("", "textfield", ""),
	"title" => array ("", "textfield", ""),
	"meta_keywords" => array ("", "textarea_3", ""),
	"meta_description" => array ("", "textarea_3", ""),
	"hits" => array ("", "number", 0),
	"date_created" => array ("", "timestampro", ""),
	"date_updated" => array ("", "timestampro", ""),

//	"archived" => array("В архиве", "checkbox"),
//	"is_new" => array ("Новинка", "checkbox", 0),

	"~service_layer-close" => array ("", "layer_close"),
*/
	"published" => array ("", "checkbox", 1, "@bo_href_preview@"),
//	"i_published" => array ("На главной", "checkbox", 1, "<a href='task-onindex.php'>отсортировать спецпредложения на главной</a>"),
);


//$entity_fixed_list["task"] = array("tgroup", "project", "country", "package", "saleunit", "taxrate", "pclass");
//$entity_fixed_list["project"] = array("country");
//unset($entity_fixed_list["project"]);

//	"~51_open" => array ("", "columned_open"),
//	"article" => array ("Артикул", "number", ""),

//	"pclass" => array ("Тип продукта", "select_soft", "ident"),
//	"package" => array ("Упаковка", "radio_table", "ident"),
//	"package" => array ("Упаковка", "select_soft", "ident"),
//	"saleunit" => array ("Ед.измерения", "select_soft", "ident"),
//	"weight" => array ("Вес, кг", "number", ""),
//	"pmodel" => array ("Модель", "select_soft", "ident"),

//	"~51_close" => array ("", "columned_close"),


//	"~52_open" => array ("", "columned_open"),
//	"country" => array ("Страна", "select_soft", "ident"),
//	"project" => array ("Производитель", "select_soft", "ident"),
//	"project" => array ("Производитель", "radio_table", "ident"),
//	"~52_close" => array ("", "columned_close"),	

//	"taxrate" => array ("Ставка налога", "radio_table", "ident"),
//	"taxrate" => array ("Ставка налога", "select_soft", "ident"),




?>
<? require "_entity_edit.php" ?>
<? require_once "_top.php" ?>
<? require "_edit_fields.php" ?>
<? require_once "_bottom.php" ?>
