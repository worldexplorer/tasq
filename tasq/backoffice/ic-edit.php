<?

require_once "../_lib/_init.php";

$fixed_fields = array("icwhose");

if ($id > 0 && $mode == "update") {
	$icwhose = select_field("icwhose");
	$icwhose_hashkey = select_field("hashkey", array("id" => $icwhose), "icwhose");

	$hashkey = get_string("hashkey");
	if ($hashkey == "") $hashkey = $id;
	
	$pos = strpos($hashkey, $icwhose_hashkey);
	if ($pos === false or $pos > 0) {
		$hashkey = "{$icwhose_hashkey}_{$hashkey}";
	}

	update (array("hashkey" => $hashkey));
}
/*
$entity_fields = array (
	"ident" => array ("Вопрос в анкете", "textarea_3", ""),

	"ictype" => array ("Тип поля ввода", "select_soft", "ident"),

//	"param1" => array ("Параметр 1", "textfield", ""),
//	"param2" => array ("Параметр 2", "textfield", ""),

//	"hashkey" => array ("Ключ", "textfieldro", "#HASHKEY#"),
	"hashkey" => array ("Ключ", "textfield", ""),

	"icwhose" => array ("Чьё поле", "select_soft", "ident"),
//	"icwhat" => array ("Поле ввода", "select_soft", "ident"),

	"obligatory" => array ("Обязательное для заполнения", "checkbox"),
	"published" => array ("Опубликовано", "checkbox")
);
*/


$entity_fields_header = array (
	"ident" => array ("Вопрос в анкете", "textarea_3", ""),
	"graycomment" => array ("Комментарий", "textfield", ""),
	"ictype" => array ("Тип поля ввода", "select_soft", "ident")
);


$entity_fields_footer = array (
	"jsvalidator" => array ("Проверка ввода", "select_table_all", "ident"),
//	"hashkey" => array ("Ключ", "textfieldro", "#HASHKEY#"),
	"hashkey" => array ("Ключ", "textfield", ""),
	"icwhose" => array ("Анкета - чьё поле", "select_soft", "ident"),

//	"icwhat" => array ("Поле ввода", "select_table_all", "ident"),
//	"param1" => array ("Параметр 1", "textfield", ""),
//	"param2" => array ("Параметр 2", "textfield", ""),

	"inbrief" => array ("Печатать в брифе", "checkbox", 0, "свойство печатается в списке товаров"),
	"sorting" => array ("Сортируемое свойство", "checkbox", 0, "в списке товаров возникает это свойство для сортировки"),
	"obligatory" => array ("Обязательное", "checkbox", 0),
	"obligatory_bo" => array ("Обязат в БО", "checkbox", 0),
	"published" => array ("Опубликовано", "checkbox", 1),
	"published_bo" => array ("Опубликовано в БО", "checkbox", 1),
);



$ctx_fields = array();

if ($id > 0) {
	if ($mode == "update") {
		$ictype = get_number("ictype");
		if ($ictype > 0) update(array("ictype" => $ictype));
	}
	$ictype = select_field("ictype");

	$ictype_hashkey = "";
	
	$query = "select t.hashkey"
		. " from ictype t"
		. " inner join ic i on i.ictype=t.id"
		. " where i.id=$id";
	$query = add_sql_table_prefix($query);
	$result1 = mysql_query($query, $cms_dbc) or die("SELECT FIELD failed:<br>$query<br>" . mysql_error($cms_dbc));
	if (mysql_num_rows($result1) > 0) {
		$row1 = mysql_fetch_row($result1);
		$ictype_hashkey = $row1[0];
	}

	switch ($ictype_hashkey) {
		case "SELECT":
		case "ICSELECT":
		case "ICRADIO":
		case "ICMULTISELECT":
		case "ICMULTICHECKBOX":
			if ($mode == "update") {
				$icwhose = get_number("icwhose");
				if ($icwhose > 0) update(array("icwhose" => $icwhose, "icdict" => 0));
			}
			$icwhose = select_field("icwhose");

/*
			$query_icdict = "select * from icdict where icwhose=$icwhose order by manorder";
			$ctx_fields = array (
				"icdict" => array ("Справочник", "select_query", $query_icdict),
				"~2" => array ("", "ahref", "<a href=''>значения справочника</a>"),
				"param1" => array ("Колонок в таблице", "number", 1),
				);
*/
			$ctx_fields = array (
				"icdict" => array ("Справочник", "select_soft"),
				"~2" => array ("", "ahref", "<a href=icdictcontent.php?icdict=#ICDICT# target=_blank>значения справочника</a>&nbsp;<a href=icdictcontent-edit.php?icdict=#ICDICT# target=_blank>добавить</a>"),
				"param1" => array ("Колонок в таблице", "number", 1),
				);

//			$ctx_fields = array ("icdict" => array ("Справочник", "select_soft"));

			break;
			
		case "AHREF":
			$ctx_fields = array ("param1" => array ("Строка-шаблон", "textfield", ""));
			break;
			
		case "RAWHTML":
			$ctx_fields = array ("param1" => array ("HTML код", "textarea", ""));
			break;
			
		case "TEXTAREA_SCROLL":
			$ctx_fields = array (
				"param1" => array ("ширина", "number", ""),
				"param2" => array ("высота", "number", ""),
				"param3" => array ("по умолч", "textfield", ""),
				);
			break;
			
		case "IMAGE":
		case "UPLOAD":
			$ctx_fields = array ("param1" => array ("Вес не более, Кб", "number", ""));
			break;
			
		case "CHECKBOX":
			$ctx_fields = array ("param1" => array ("Default состояние", "checkbox", 0));
			break;
			
		default:
			$ctx_fields = array ("~10" => array (" ", "ahref", "[НЕТ ДОПОЛНИТЕЛЬНЫХ ПАРАМЕТРОВ]"));
			break;
	}
	
}


$entity_fields = array_merge($entity_fields_header, $ctx_fields, $entity_fields_footer);

?>

<? include "_entity_edit.php" ?>
<? include "_top.php" ?>
<? include "_edit_fields.php" ?>
<? include "_bottom.php" ?>
