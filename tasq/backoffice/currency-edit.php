<?

require_once "../_lib/_init.php";

$entity_fields = array (
	"ident" => array ("Валюта", "textfield", ""),

	"date_exchrate_rub" => array("Дата курса", "datetime", $today_datetime),
	"exchrate_rub" => array ("Курс перевода<br>в рубли", "textfield", ""),
	"exchrate_rub_multiplier" => array("Множитель", "textfield", ""),
//	"exchrate_rub_multiplier" => array("Итоговый курс", "textfield", ""),

	"hashkey" => array ("Ключ", "textfield", ""),

//	"date_created" => array ("Дата создания", "timestamp_date"),


	"expiration_minutes" => array ("Хранить, мин", "number", 60, "при авт. обновлении: дата Истекает = СЕЙЧАС + СТОЛЬКО"),
	"date_expiration" => array ("Истекает", "datetime", $today_datetime),
//	"date_expiration" => array ("Истекает", "timestamp", $today_ts_datetime, "для обновления уменьшить на #HOURS_EXPIRATION#"),
	"scriptname_updated" => array("Обновил", "textfield"),
	"date_updated" => array ("Обновление", "timestampro", ""),


	"src_href" => array("<a href='#SRC_HREF#' target=_blank>src_href</a>", "textfield", ""),
	"src_content" => array ("content<br>cached", "textarea_3", ""),
	"exchrate_regexp" => array ("exchrate_regexp", "textarea_3", ""),
	"daterate_regexp" => array ("daterate_regexp", "textarea_3", ""),

	"comment" => array ("Comment", "textarea_3", ""),

	"published" => array ("Опубликовано", "checkbox", 1),
);

?>

<? require "../_lib/_entity_edit.php" ?>
<? require_once "_top.php" ?>
<? require "../_lib/_edit_fields.php" ?>
<? require_once "_bottom.php" ?>