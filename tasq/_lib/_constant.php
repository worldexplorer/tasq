<?

if (!isset($site_name)) $site_name = "Центр Управления Потоком Сознания";
if (!isset($site_ident)) $site_ident = "tasq";
$path_separator = $pagetitle_separator = " &raquo; ";
//$path_separator = $pagetitle_separator = "";
//$path_separator = "&nbsp;&nbsp;<img src='img/breadcrumb-arrow.gif' width='8' height='8'>&nbsp;&nbsp;";
$legend_separator = "&nbsp;&nbsp;|&nbsp;&nbsp;";


$mysql_info = array (
	"host" => "localhost",
	"login" => "tasq",
	"passwd" => "tasq",
	"db" => "tasq"
);


if ($_SERVER["SERVER_NAME"] == "localhost") {
	$mysql_info = array (
		"host" => "localhost",
		"login" => "tasq",
		"passwd" => "tasq",
		"db" => "tasq"
	);
}

if (strpos($_SERVER["SERVER_NAME"], "webie.ru") !== false) {
	$mysql_info = array (
		"host" => "localhost",
		"login" => "devwebie1",
		"passwd" => "00000000000000",
		"db" => "db_devwebie1"
	);
}

//pre($mysql_info);


$menu_bo1 = array (
	array (
		"=search.php" => "Поиск",

		"~21" => "&nbsp;",
//		"~2" => "Материалы",
//		array (
			"product" => "Материалы",
//		),

		"~31" => "&nbsp;",
		"~3" => "Справочники",
		array (
			"pgroup" => "Разделы",
			"supplier" => "Носители",
			"pmodel" => "Источники",
			"=icdictcontent.php?icdict=1" => "Ключевые слова",
		),

		"~41" => "&nbsp;",
		"~4" => "Анкеты",
		array (
			"icwhose" => "Анкеты",
			"ic" => "Вопросы в анкете",
			"icdict" => "Справочники",
			"icdictcontent" => "Значения справочника",
			"ictype" => "Типы полей ввода",
		),

		"~51" => "&nbsp;",
		"~61" => "Системное",
		array (
			"imgtype" => "Типы картинок",
			"img" => "Все картинки подряд",
			"change_word" => "Заменить везде",
			"=product-lost.php" => "Материалы без разделов",
		),
	),
);


// $menu_bo in _constants.php has empty values because of language dependency (filled at bottom of _messages.php)



// after _messages.php was introduced, $entity_list is really large and not needed in $menu_bo
//$menu_bo = $entity_list = array (
$menu_bo = array (
	"project" => "1. Цель",
	"tgroup" => "2. Задача",
	"task" => "3. Этап",
//	"~2" => "&nbsp;",
//	"=task-lost.php" => "Этапы без привязки",
//	"tstatus" => "",
//	"supplier" => "Производители",
//	"pmodel" => "Модели",
//	"currency" => "Валюта",

//	"faq" => "",
//	"fgroup" => "",

//	"~9" => "&nbsp;",
//	"mmenu" => "",
//	"shop" => "",

//	"~8" => "&nbsp;",
//	"ngroup" => "",
//	"news" => "",
//	"=../mailer/user/login.php?mlist=1&l_login=1234&l_passwd=1234&mode=login' target='_blank" => "Рассылка",
//	"=mmenu-legend.php" => "",

//	"~7" => "&nbsp;",
//	"constant" => "",
//	"mtpl" => "",
//	"sentlog" => "",
//	"imgtype" => "",
//	"img" => "",
//	"~5" => "&nbsp;",
//	"jsvalidator" => "",

//	"~12" => "&nbsp;",
//	"icwhose" => "",
//	"ic" => "",
//	"icdict" => "",
//	"icdictcontent" => "",
//	"ictype" => "",
//	"icsheet" => "",
);


$entity_orderby_list = array (
	"article" => "manorder desc",
	"agroup" => "manorder asc",

	"product" => "manorder asc",
	"supplier" => "ident asc",
	"pmodel" => "ident asc",
	"color" => "ident asc",

	"package" => "ident asc",

	"news" => "manorder desc",
	"sentlog" => "manorder desc",
	"pimportsource" => "manorder desc",
	"importlog" => "manorder desc",
	
	"customer" => "manorder desc",
	"corder" => "manorder desc",

//	"icdictcontent" => "ident asc",

	"m2m_product_rating" => "manorder asc",
	"m2m_product_replic" => "manorder asc",

	"m2m_article_rating" => "manorder asc",
	"m2m_article_replic" => "manorder asc",

	"task" => "manorder desc",
	"article" => "manorder desc",
	"article" => "manorder desc",
);

$entity_fixed_list = array (
	"mmenu" => array("parent_id"),

	"agroup" => array("parent_id"),
	"article" => array("issue", "agroup", "person"),

	"pgroup" => array("parent_id"),
//	"product" => array("pgroup", "supplier", "pmodel"),
//	"product" => array("pgroup", "supplier", "country", "package", "saleunit", "taxrate", "pclass"),
//	"product" => array("pgroup", "supplier"),
	"product" => array("pgroup"),
	"pmodel" => array("supplier"),

	"tgroup" => array("parent_id"),
	"task" => array("project", "tgroup", "tstatus"),
	"tstatus" => array("task"),

	"sgroup" => array("parent_id"),
	"spart" => array("sgroup", "supplier", "pmodel"),

//	"caroption" => array("ogroup"),
//	"ppgoption" => array("pgroup"),

//	"corder" => array("customer"),

//	"supplier" => array("country"),

	"cgroup" => array("parent_id"),
//	"customer" => array("cgroup"),

	"ugroup" => array("parent_id"),

	"bgroup" => array("parent_id"),
	"banner" => array("bgroup"),
	"review" => array("rgroup"),
	"rgroup" => array("parent_id"),
//	"news" => array("ngroup", "cgroup"),
	"news" => array("ngroup", "task"),
	"ngroup" => array("parent_id"),

	"faq" => array("fgroup"),
	"fgroup" => array("parent_id"),

	"ic" => array("icwhose", "ictype"),
	"icdict" => array("icwhose"),
	"icdictcontent" => array("icdict"),
	"icsheet" => array("icwhose"),

//	"img" => array("imgtype"),

	"sentlog" => array("mtpl"),

//	"m2m_product_rating" => array("product", "customer"),
//	"m2m_product_replic" => array("product", "customer"),
//	"m2m_article_rating" => array("article", "person"),
//	"m2m_article_replic" => array("article", "person"),
);

$entity_m2mfixed_list = array (
	"m2m_ugroup_pgroup" => array("ugroup", "pgroup"),
	"m2m_product_pgroup" => array("product", "pgroup"),
	"m2m_task_tgroup" => array("task", "tgroup"),
	"m2m_task_news" => array("task", "news"),
	"m2m_task_tstatus" => array("task", "tstatus"),
//	"m2m_customer_ugroup" => array("customer", "ugroup"),
//	"m2m_customer_cgroup" => array("customer", "cgroup"),
//	"m2m_product_news" => array("product", "news"),
//	"m2m_news_ugroup" => array("news", "ugroup"),
	"m2m_news_cgroup" => array("cgroup", "news"),
//	"m2m_article_rating" => array("article", "person"),
//	"m2m_article_replic" => array("article", "person"),
);

$m2m_bidirect_insert_backward = array (
	"m2m_product_product_necessary" => 0,
	"m2m_product_product_accompanied" => 0,
);


$entity_swapdbfields_list = array (
	"article" => array (
		array("file1", "file1_comment"),
		array("file2", "file2_comment"),
		array("file3", "file3_comment"),
		array("file4", "file4_comment"),
		array("file5", "file5_comment"),
	),

	"product" => array (
		array("file1", "file1_comment"),
		array("file2", "file2_comment"),
		array("file3", "file3_comment"),
		array("file4", "file4_comment"),
		array("file5", "file5_comment"),
	),

	"news" => array (
		array("file1", "file1_comment"),
		array("file2", "file2_comment"),
		array("file3", "file3_comment"),
		array("file4", "file4_comment"),
		array("file5", "file5_comment"),
	),
);


$no_entity_img_leftjoin = array (
	"img", "imgtype", "client", "corder" 
	);
//$no_entity_img_leftjoin = array_keys($entity_list);

$no_addentity_list = $no_delentity_list = array (
	"imgtype",
	"searchlog",
	"corder",
//	"person",
	);

$no_addentity_list[] = "sentlog";
//$no_delentity_list[] = "";

$fixed_getfirstfromdb_array = array ("");

$no_submenu_list = array (
//	"news"
//	"product",
	);

$no_pager_list = array (
//	"mmenu" => 1
	);

$no_prevnext_list = array (
//	"client"
	);

$no_search_list = array (
	"poll", "imgtype"
	);

$entity_fixedlike_list = array (
	"mmenu" => array("id", "ident", "content", "hashkey", "img_free", "img_mover", "pagetitle", "title", "meta_keywords", "meta_description"),

	"agroup" => array("ident", "pagetitle", "title", "meta_keywords", "meta_description"),
	"article" => array("ident", "brief", "content", "date_published", "meta_keywords", "meta_description"
			, "person_ident"
		),

	"product" => array("ident", "article", "brief", "content", "date_published", "meta_keywords", "meta_description"
			, "pricecomment_1"
//			, "supplier_ident", "pmodel_ident"
		),
	"pgroup" => array("ident", "pagetitle", "title", "meta_keywords", "meta_description"),

	"mtpl" => array("id", "ident", "hashkey", "subject", "body", "rcptto", "sentmsg", "admtail"),
	"sentlog" => array("id", "ident", "content", "remote_address"),
	"shop" => array("id", "ident", "content"),
	"constant" => array("id", "ident", "content", "hashkey"),
	"img" => array("id", "img", "img_txt", "img_big", "img_big_txt", "img_src", "img_big_src", "owner_entity"),


	"raw_material" => array("id", "ident", "pn", "descr", "location", "lotcode", "qty", "date_shipped"),

	);


$mail_from = "webie website <info@webie.ru>";
$mail_subscriber = "";
$mail_visor = "info@webie.ru";
$mail_visor_debug = 0;
$mail_sendSMTP = 1;
$mail_relyon_issent = 0;

//ini_set("SMTP", "post");
ini_set("sendmail_from", $mail_from);

$FTB_version = "303";
$FTB_Style = "2003";
$FTB_DesignModeCss = "../default.css";
$FTB_HtmlModeCss = "default.css";
$no_freetext = 0;

$debug_query = 0;
$debug_sendmail = 1;
$debug_img = 0;
$debug_cookies = 0;
$debug_session = 0;
$debug_rewrite = 0;
$debug_lang = 0;

$img_rename0_copy1_moveupload2 = 0;
$slashes_ok0_strip1 = 1;
if (!isset($in_silent_mode)) $in_silent_mode = 1;
$spanstyle_ex_multiplier = 1.5;

//$in_backoffice = 1;
$m2m_sameentity_displaymode_default = "flat";	// tree
$m2m_sameentity_displaymode_nolinks = "flat";	// tree

$_submenu_rowlimit = 100;

$lang_multi_support = 1;
$lang_database_default = "ru";
$lang_face_default = "ru";
$lang_backoffice_default = "ru";
$lang_backoffice_order = array("ru", "en", "fr");
$lang_backoffice_out_of_order_force_print = 1;

$entities_language_independant = array("currency", "sentlog", "mtpl", "cached", "jsvalidator", "imgtype");

$dbfields_language_independant = array("id", "deleted", "manorder", "hashkey", "archived", "hrefto", "srcurl", "hits"
	, "date_created", "date_published", "date_updated"
	, "is_heredoc", "is_drone", "parent_id"
	, "price_1", "price_2", "price_3", "price_4", "price_5", "article"
	, "file1", "file2", "file3", "file4", "file5", "file1"
	, "date_registered", "email", "comment", "date_lastclick", "remote_address", "user_agent", "idrandom", "lastip", "lastsid", "phone", "fax", "contact", "tin"
	, "date_faceted", "faceted", "faceting", "img_src", "img_big_src", "img_main", "crc32", "owner_entity", "owner_entity_id", "img", "img_w", "img_h", "img_big", "img_big_w", "img_big_h"
	, "remote_address", "user_agent", "idrandom", "lastip", "lastsid", "phone", "fax", "contact", "tin"
	);

$dbfields_language_independant_strpos = array("case_debugging", "count(");


?>