<?

// chucha 27-09-2009:
// I use global variables in message bundle because it is easier to operate while coding;
// define("_KEY", "VALUE") can not be used in $a = "foo _KEY bar"; or $a = <<< EOT;

$msg_tag_shortcut = "<img src='img/shortcut.gif' width=7 height=7 style='border:0px solid #eeeeee' align=absmiddle hspace=2 vspace=2>";

// system messages, used in _lib/_*.php
switch ($lang_current) {
	case "ru":
	case "fr":

		if (!isset($site_name)) $site_name = "Webie.CMS шаблонный сайт";
		
		// $menu_bo in _constants.php has empty values because of language dependency (filled at bottom of _messages.php)
		if (!isset($entity_list))
		$entity_list = array (
			"issue" => "Выпуски",
			"m2m_issue_rating" => "Рейтинг,отзыв,пожел",
			"m2m_issue_replic" => "Обсуждение",

			"article" => "Статьи",
			"m2m_article_rating" => "Рейтинг,отзыв,пожел",
			"m2m_article_replic" => "Обсуждение",

			"agroup" => "Рубрики",
			"m2m_agroup_rating" => "Рейтинг,отзыв,пожел",
			"m2m_agroup_replic" => "Обсуждение",
		
			"person" => "Персоны",
			"m2m_person_rating" => "Рейтинг,отзыв,пожел",
			"m2m_person_replic" => "Обсуждение",
		
		
			"cgroup" => "Группы клиентов",
			"customer" => "Клиенты",
			"corder" => "Заказы",
			"shop" => "Филиалы клиники",
		
			"=product-lost.php" => "Продукты без группы",
			"product" => "Продукты",
			"pgroup" => "Группы продуктов",
			"=pgroup-onindex.php" => "Порядок групп на главной",
			"supplier" => "Производители",
			"pmodel" => "Модели",
			"currency" => "Валюта",
		
			"spart" => "Запчасти",
			"sgroup" => "Группы запчастей",

			"m2m_product_rating" => "Рейтинг,отзыв,пожел",
			"m2m_product_replic" => "Обсуждение",
		
			"news" => "Новости",
			"ngroup" => "Новостные ленты",
			"=../mailer/user/login.php?mlist=1&l_login=1234&l_passwd=1234&mode=login' target='_blank" => "Рассылка",
		
			"faq" => "FAQ",
			"fgroup" => "Категория FAQ",

			"project" => "Цель",
			"tgroup" => "Задача",
			"task" => "Этап",
			"tstatus" => "Статусы задачи",
				
			"banner" => "Баннеры",
			"bgroup" => "Группа баннеров",
		
			"constant" => "Константы",
			"cached" => "Кэш",
			"mtpl" => "Шаблоны писем",
			"sentlog" => "Отправленные",
			"imgtype" => "Типы картинок",
			"img" => "Все картинки подряд",
			"change_word" => "Заменить везде",
			"jsvalidator" => "Проверки ввода",
		
			"icwhose" => "Анкеты",
			"ic" => "Вопросы в анкете",
			"icdict" => "Справочники",
			"icdictcontent" => "Значения справочника",
			"ictype" => "Типы полей ввода",
			"icsheet" => "Заполненные анкеты",
		
			"mmenu" => "Стуктура сайта",
			"=mmenu-legend.php?parent_id=2" => "Легенда сайта",
			"=mmenu-legend.php" => "Порядок пунктов в легенде",
		);
		
		
		if (!isset($add_entity_msg_list))
		$add_entity_msg_list = array (
			"issue" => "новый выпуск",
			"article" => "новую статью",
			"agroup" => "новую рубрику",
			"person" => "новую персону",
			"m2m_article_rating" => "Новый Рейтинг,отзыв,пожел",
			"m2m_article_replic" => "Новую реплику",

			"customer" => "нового клиента",
			"сgroup" => "новую группу клиентов",
		
			"pimportsource" => "новую настройку нового импорта нового прайс-листа",
			"product" => "новый продукт",
			"pgroup" => "новую группу продуктов",
			"ugroup" => "новую целевую группу",
			"supplier" => "нового производителя",
			"pmodel" => "новую модель",
		
			"country" => "новую страну",
			"currency" => "новую валюту",
		
			"m2m_product_rating" => "новый Рейтинг,отзыв,пожел",
			"m2m_product_replic" => "новую реплику",
		
			"taxrate" => "новую ставку налога",
			"package" => "новую упаковку",
			"saleunit" => "новую единицу измерения",
			"shiptype" => "новый вид доставки",
			"pclass" => "новый тип продукта",
		
			"shop" => "новый филиал клиники",
		
			"banner" => "новый баннер",
			"bgroup" => "новую группа баннеров",
			"news" => "новость",
			"ngroup" => "новую новостную ленту",
		
			"faq" => "новый FAQ",
			"fgroup" => "новую категорию FAQ",

			"project" => "новую цель",
			"tgroup" => "новую задачу",
			"task" => "новый этап",
			"tstatus" => "новый статус этапа",
		
			"constant" => "новую константу",
			"cached" => "новую запись в кэше",
			"mtpl" => "новый шаблон письма",
			"imgtype" => "новый тип картинок",
			"img" => "новую картинку",
			"jsvalidator" => "новый JSValidator",
		
			"icwhose" => "новую анкету",
			"ic" => "новый вопрос в анкете",
			"icdict" => "новый справочник",
			"icdictcontent" => "новое значение справочника",
			"ictype" => "новый тип полей ввода",
		
			"mmenu" => "новый пункт меню",
		);
		
		
		if (!isset($new_entity_ident_list))
		$new_entity_ident_list = array (
			"issue" => "новый выпуск",
			"article" => "новая статья",
			"agroup" => "новая рубрика",
			"person" => "новая персона",
			"m2m_article_rating" => "новый Рейтинг,отзыв,пожел",
			"m2m_article_replic" => "новая реплика",

			"customer" => "новый клиент",
			"cgroup" => "новая группа клиентов",
			"pimportsource" => "новая настройка нового импорта нового прайс-листа",
			"product" => "новый продукт",
			"pgroup" => "новая группа продуктов",
			"ugroup" => "новая целевая группа",
			"supplier" => "нового производителя",
			"pmodel" => "новую модель",
		
			"country" => "новая страна",
			"currency" => "новая валюта",
		
			"m2m_product_rating" => "новый Рейтинг,отзыв,пожел",
			"m2m_product_replic" => "новая реплика",
		
		
			"taxrate" => "новая ставка налога",
			"package" => "новая упаковка",
			"saleunit" => "новая единица измерения",
			"shiptype" => "новый вид доставки",
			"pclass" => "новый тип продукта",
		
			"shop" => "новый филиал клиники",
				
			"banner" => "новый баннер",
			"bgroup" => "новая группа баннеров",
			"news" => "новость",
			"ngroup" => "новая новостная лента",
		
			"faq" => "новый FAQ",
			"fgroup" => "новая категория FAQ",
		
			"project" => "новая цель",
			"tgroup" => "новая задача",
			"task" => "новый этап",
			"tstatus" => "новый статус задачи",
		
			"constant" => "новая константа",
			"cached" => "новая запись в кэше",
			"mtpl" => "новый шаблон письма",
			"imgtype" => "новый тип картинок",
			"jsvalidator" => "новый JSValidator",
		
			"icwhose" => "новая анкета",
			"ic" => "новое поле в анкете",
			"icdict" => "новый справочник",
			"icdictcontent" => "новое значение справочника",
			"ictype" => "новый тип полей ввода",
		
			"mmenu" => "новый пункт меню",
		);

		$msg_fields = array (
// common
			"id" => "№",
			"ident" => "Название",
			"annotation" => "Аннотация",
			"annotation-graycomment" => "подпись под пунктом меню (в синем ВЫПАДАЮЩЕМ меню, только для второго уровня)",
			"hashkey" => "Ключ",
			"date_created" => "Дата создания",
			"date_updated" => "Дата обновления",
//			"date_published" => "Дата публикации",		// too many entities where data_published is date of ...
			"date_published" => "Дата",
			"img_cnt" => "Фото",
			"published-list" => "Опубл",
			"published-edit" => "Опубликовано",
			"i_published-list" => "НаГлав",
			"i_published-edit" => "На главной",

			"~delete" => "Удал",
			"parent_id" => "Родитель",
			"group" => "Группа",
			"comment" => "Внутренний<br>комментарий",

			"pagetitle" => "Заголовок страницы",
			"title" => "Заголовок контента",
			"meta_keywords" => "Meta Keywords",
			"meta_description" => "Meta Description",

// "explainations are too complicated"
//			"brief-edit" => "Кратко<br><br>текст в список<br>однородных<br>элементов<br><br>(новости в ленте,<br>продукты<br> одной группы...)",
//			"content-edit" => "Описание<br><br>текст в<br>карточку товара,<br>текст новости ...",

			"brief" => "Бриф",
			"content" => "Контент",

			"date_lastclick" => "Посл.клик",
			"remote_address" => "IP регистрации",
			"lastip" => "IP посл.клика",
			"lastsid" => "Выслан cookie",
			"idrandom" => "IDrandom",

			"additional_layer" => "Дополнительно",
			"service_layer" => "Служебные поля",
			"filesattached_layer" => "Сгружаемые файлы",

			"product_iccontent" => "Свойства товара",

			"filesattached_layer_open" => "Аттачменты",
			"file1" => "Файл 1",
			"file1_comment" => "Комментарий 1",
			"file2" => "Файл 2",
			"file2_comment" => "Комментарий 2",
			"file3" => "Файл 3",
			"file3_comment" => "Комментарий 3",
			"file4" => "Файл 4",
			"file4_comment" => "Комментарий 4",
			"file5" => "Файл 5",
			"file5_comment" => "Комментарий 5",

// customer
			"contract_discount" => "Скидка,&nbsp;%",
			"login" => "Логин",
			"passwd" => "Пароль",
			"manager_name" => "Контакт",
			"cgroup-list" => "Группа",			// next taking from entity_list_single
//			"customer-ident" => "Клиенты фыва",	// next taking from entity_list_single
			"phone" => "Телефон",
			"address" => "Адрес",
			"fax" => "Факс",
			"contract_number" => "Номер договора",
			"tin" => "ИНН",
			"customer_sheet" => "Анкета пользователя",
			"login_layer" => "Логин, пароль, доступ",
			"customer-published-list" => "Дост",
			"customer-published-edit" => "Дать доступ",
			"customer-date_created-edit" => "Регистрация",
			"customer-date_updated-edit" => "Обновление",

// product
			"pgroup-list" => "Группы",			// next taking from entity_list_single
			"pgroup-edit" => "Группы<br>продуктов",			// next taking from entity_list_single
			//"article" => "Артикул",
			"price_1" => "Цена",
			"price_2" => "Цена 2",
			"price_3" => "Цена 3",
			"pricecomment_1" => "Комментарий к цене 1",
			"pricecomment_2" => "Комментарий к цене 2",
			"pricecomment_3" => "Комментарий к цене 3",

			"product-brief-edit" => "Кратко<br><br>в список товаров<br>группы",
			"product-content-edit" => "Описание<br><br>в карточку товара,<br>справа от фото",
					
			"weight" => "Вес",
			"hits" => "Hits",
			"hits-edit" => "Просмотров",
			"hits-graycomment" => "любое обращение с лица; считаются даже роботы",	// к [$entity_list[$entity]]

			"briefful_layer" => "Краткое описание, Полное описание",
			"news4product_layer" => "Привязка товара к новостям",
//			"product-date_published" => "Дата",
			
			"archived-list" => "Архив",
			"archived" => "В архиве",
			"is_new" => "Новинка",
			"banner_top" => "Баннер в шапке",

			"sold" => "Sold",
			"disclaimer_list" => "Disclaimer for product list",
			"disclaimer_pcard" => "Disclaimer for product card",

// pgroup
			"divclass" => "Класс DIV",
			//"file1" => "Картинка в меню",
			//"file1-graycomment" => "только для корневых групп",

// currency
			"date_exchrate_rub" => "Дата курса",
			"exchrate_rub" => "Курс перевода<br>в рубли",
			"exchrate_rub_multiplier" => "Множитель",
			"exchrate_rub_multiplied" => "Итог",
			"currency-date_updated" => "Обновление",
			"src_href" => "Дата курса",
			"src_content" => "content<br>cached",
			"exchrate_regexp" => "exchrate_regexp",
			"daterate_regexp" => "daterate_regexp",

// m2m_product_rating
			"m2m_product_rating-customer_ident" => "Чья оценка",
			"rating" => "Оценка",
			"opinion" => "Что понравилось",
			"opinion-edit" => "Что понравилось,<br>что нет",
			"wish" => "Предложения",
			"m2m_product_rating-content" => "Комментарий<br>модератора",

// news
//			"news-date_published" => "Дата",
			"ngroup_ident-list" => "Лента",
			"rsss_ident" => "RSS-источник",
			"rss_published" => "В&nbsp;RSS",
			"news-brief-edit" => "Бриф",
			"news-content-edit" => "Текст новости",

			"srcurl" => "<a href='#SRCURL#' target=_blank>Источник</a>",
			"hrefto" => "<a href='#HREFTO#' target=_blank>Ссылка</a>",

// faq
			"cname" => "Имя",
			"email" => "Email",
			"answer_sent" => "Отпр",
			"answer_sent-edit" => "Отправить",
			"answer_sent-graycomment" => "если требуется отправить ответ вопрошающему по указанному им email'у - проставить эту галку и сохранить",
			"subject" => "Тема",
			"contact" => "Другие контакты",
			"contact-edit" => "Другие<br>контакты",
			"faq-content" => "Ответ консультанта",
			"faq-fgroup-list" => "Категория FAQ",
			"faq-fgroup-graycomment" => "Выбрать общую!",

// constant
			"constant-ident" => "Имя",
			"constant-content" => "Значение",

// cached
			"cached-date_published" => "Закешировано",
			"expiration_minutes" => "Хранить, мин",
			"expiration_minutes-list" => "Мин",
			"expiration_minutes-graycomment" => "при авт. обновлении: дата Истекает = СЕЙЧАС + СТОЛЬКО мин",
			"date_expiration" => "Истекает",
			"scriptname_updated" => "Обновил",
			"scriptname_created" => "Создал",

// mtpl
			"subject" => "Тема письма",
			"body" => "Текст письма",
			"admtail" => "Приписка<br>менеджеру",
			"admtail-graycomment" => "текст из этого поля добавляется к письму; могут быть использованы #HTTP_HOST# и др.",
			"sender" => "Отправитель",
			"rcptto" => "Получатель",
			"rcptto-graycomment" => "email, email... менеджеров получающих копию клиентского письма с [припиской менеджеру]",
			"sentmsg" => "Если успешно",
			"sentmsg-graycomment" => "сообщение на экране при успешной отправке (javascript:alert)",

			"savesentlog" => "SentLog",
			"savesentlog-edit" => "Сохранять<br>отправленные",
			"savesentlog-graycomment" => "в момент отправки письма, копия сохраняется в <a href='sentlog.php'>Отправленных</a>",			


// sentlog
			"sentlog-content-list" => "Содержание письма",
			"sentlog-content-edit" => "Содержание<br>письма",

// imgtype
			"imgtype-content" => "Комментарий",
			"imglimit-list" => "Лимит",
			"imglimit" => "Кол-во картинок",
			"imglimit-graycomment" => "лимит на кол-во заливаемых картинок; 0 - не ограничено",

			"imgsmall_layer" => "Маленькая картинка в бэкоффисе",
			"img_present" => "Есть маленькая",
			"img_present-graycomment" => "выводится ли Маленькая->[Browse]",
			"img_newqnty" => "Количество кнопок Обзор (Browse)",
			"img_newqnty-graycomment" => "если хочется заливать несколько новых сразу; 0=1",
			"img_zip_present" => "Есть ли поле ZIP",
			"img_zip_present-graycomment" => "выводится ли Маленькая->[Browse] ZIP для [Новой]",
			"img_url_present" => "Есть ли поле URL",
			"img_url_present-graycomment" => "выводится ли Маленькая->URL для залитых и [Новой]",
			"img_txt_present" => "Есть подпись",
			"img_txt_present-graycomment" => "имеется ли поле для подписи Маленькой",
			"img_txt_eq_fname" => "Галочка [подпись = имя файла]",
			"img_txt_eq_fname-graycomment" => "чекнуто ли по умолчанию [подпись = имя файла] для новых",
			"resize_published" => "Создать [маленькую] из [большой]",
			"resize_published-graycomment" => "выводится ли сама фича у пользователя",
			"resize_default_checked" => "Состояние галочки [создать]",
			"resize_default_checked-graycomment" => "чекнуто ли по умолчанию [создать ресайз] для новых",
			"resize_default_qlty" => "Качество ресайза [маленькой]",
			"resize_default_qlty-graycomment" => "качество сохранения JPEG: 0&#8230;100",
			"resize_default_width" => "Ширина ресайза [маленькой]",
			"resize_default_height" => "Высота ресайза [маленькой]",

			"imgbig_layer" => "Большая картинка в бэкоффисе",
			"img_big_present" => "Есть большая",
			"img_big_present-graycomment" => "выводится ли Большая->[Browse]",
			"img_big_newqnty" => "Количество кнопок Обзор (Browse)",
			"img_big_newqnty-graycomment" => "если хочется заливать несколько новых сразу; 0=1",
			"img_big_zip_present" => "Есть ли поле ZIP",
			"img_big_zip_present-graycomment" => "выводится ли Большая->[Browse] ZIP для [Новой]",
			"img_big_url_present" => "Есть ли поле URL",
			"img_big_url_present-graycomment" => "выводится ли Большая->URL для залитых и [Новой]",
			"img_big_txt_present" => "Есть подпись",
			"img_big_txt_present-graycomment" => "имеется ли поле для подписи Большой",
			"img_big_txt_eq_fname" => "Галочка [подпись = имя файла]",
			"img_big_txt_eq_fname-graycomment" => "чекнуто ли по умолчанию [подпись = имя файла] для новых",
			"big_resize_published" => "Масштабировать [большую] при закачке",
			"big_resize_published-graycomment" => "выводится ли сама фича у пользователя",
			"big_resize_default_checked" => "Состояние галочки [масштабировать]",
			"big_resize_default_checked-graycomment" => "чекнуто ли по умолчанию [создать ресайз] для новых",
			"big_resize_default_qlty" => "Качество ресайза [большой]",
			"big_resize_default_qlty-graycomment" => "качество сохранения JPEG: 0&#8230;100",
			"big_resize_default_width" => "Ширина ресайза [большой]",
			"big_resize_default_height" => "Высота ресайза [большой]",
			

			"imgthumb_layer" => "Превьюшка в бэкоффисе",
			"img_thumb_present" => "Есть превьюшка",
			"img_thumb_present-graycomment" => "выводится ли справа от формы уменьшенная превьюшка",
			"img_thumb_qlty" => "Качество ресайза [превьюшки]",
			"img_thumb_qlty-graycomment" => "качество сохранения JPEG: 0&#8230;100",
			"img_thumb_width" => "Ширина ресайза [превьюшки]",
			"img_thumb_height" => "Высота ресайза [превьюшки]",
			
			"imgfirst_layer" => "Авторесайз первой (например в список товаров группы)",
			"_autoresize_qlty" => "Качество авторесайза",
			"_autoresize_qlty-graycomment" => "качество сохранения JPEG: 0&#8230;100",
			"_autoresize_width" => "Ширина авторесайза",
			"_autoresize_height" => "Высота авторесайза",
			"_autoresize_apply" => "Применять авторесайз",
			"_autoresize_apply-graycomment" => "создаются ли авторесайзы при обращении",
			"_merge_img" => "Ватермарк",
			"_merge_img-graycomment" => "накладывать ли водяной знак на авторесайз",
			"_merge_alfa" => "Прозрачность",
			"_merge_alfa-graycomment" => "прозрачность накладываемой сверху картинки: 0&#8230;100",
			"_merge_type" => "Метод наложения",
			"_merge_type-graycomment" => "0=пропорционайльный ресайз; 1=tile",
			"_merge_apply" => "Накладывать ватермарк",
			"_merge_apply-graycomment" => "можно удалить файл-ватермарк, а можно убрать эту галку",
			"_autoresize_debug" => "Выводить отладку",
			"_autoresize_debug-graycomment" => "надписи с лица об: авторесайзе, чистке от старья, ватермарках",
			"_autoresize_tpl_ex" => "Шаблон для<br>существующей",
			"_autoresize_tpl_nex" => "Шаблон для<br>несуществующей",
			
			"imgfirst2_layer" => "Авторесайз2 первой (например в список товаров группы)",
			"imgfirst3_layer" => "Авторесайз3 первой (например в список товаров группы)",
			"imgfirst4_layer" => "Авторесайз4 первой (например в список товаров группы)",
			"imgevery_layer" => "Авторесайз каждой (например превьюшки в карточку товара)",
			"imgevery2_layer" => "Авторесайз2 каждой (например превьюшки в карточку товара)",
			"imgevery3_layer" => "Авторесайз3 каждой (например превьюшки в карточку товара)",
			"imgevery4_layer" => "Авторесайз4 каждой (например превьюшки в карточку товара)",
			
			"imgmsg_layer" => "Надписи в бэкоффисе",
			"msg_ident" => "Надпись [Картинка]",
			"msg_add" => "Надпись [Новая картинка]",
			"msg_change" => "Надпись [изменить картинку]",
			"msg_img" => "Надпись [маленькая]",
			"msg_img_big" => "Надпись [большая]",

			"img_table" => "Таблица с картинками<br>img если пусто",
			"merge_seed" => "Merge Seed",
			"merge_seed-graycomment" => "используется для ватермаркнутой картинки",
			
			"imgtype-date_updated" => "Обновлено",
			"imgtype-date_updated-graycomment" => "авторесайзы старее этой даты – будут перегенерированы",
			
// img
			"~img_tag" => "Код",
			"~img_linkedto" => "Привязка",

// img
			"jsvalidator-content" => "JS RegExp",

// icwhose
//			"icwhose-hashkey" => "Ключ для шаблонов",
			"bo_only-list" => "БО",
			"bo_only" => "Только в БО",
			"bo_only-graycomment" => "анкета не будет выводиться в бэкоффисе",
			"jsv_debug" => "Отладка",
			
// ic
			"obligatory-list" => "Обяз",
			"obligatory_bo-list" => "ОбязБО",
			"inbrief-list" => "вСписке",
			"sorting-list" => "Сорт",
			"published_bo-list" => "ОпубБО",

			"obligatory" => "Обязательное",
			"obligatory_bo" => "Обязат в БО",
			"inbrief" => "Печатать в брифе",
			"inbrief-graycomment" => "свойство печатается в списке товаров",

			"sorting" => "Сортируемое свойство",
			"sorting-graycomment" => "в списке товаров возникает это свойство для сортировки",
			"published_bo" => "Опубликовано в БО",

			"graycomment" => "Комментарий",
			"ic-icwhose" => "Анкета - чьё поле",

// icdict
			"icdict-icwhose" => "Чей справочник",
			
// icdictcontent
			"label_style-list" => "label style",
			"tf1_width-list" => "TFшир",
			"tf1_incolumn-list" => "TFотд",
			"icdictcontent-content" => "Описание",
			
			"label_style" => "label style=[]",
			"tf1_width" => "Рядом - textfield шириной",
			"tf1_incolumn" => "textfield в отдельном столбце",

			"tf1_addtodict" => "Добавлять значения<br>из textfield сразу в справочник",
			"tf1_addedpublished" => "Опубликованы ли<br>добавленные значения",


// mmenu
			"mmenu-hashkey" => "Ключ/ссылка",
			"mmenu-is_heredoc-list" => "Станд",
			"mmenu-is_heredoc" => "Стандартный документ",
			"mmenu-is_heredoc-graycomment" => "да = [отображается только текст контента; нет специальной страницы]",

			"mmenu-is_drone-list" => "Трут",
			"mmenu-is_drone" => "Трутень",
			"mmenu-is_drone-graycomment" => "в меню с лица ссылка ведёт на первый дочерний элемент",

			"mmenu-content_no_freetext-list" => "TArea",
			"mmenu-published_legend-list" => "Лгенд",
			"mmenu-published_legend" => "В легенде сайта",

			"mmenu-published_sitemap-list" => "КартаС",
			"mmenu-published_sitemap" => "На карте сайта",

			"mmenuimg_layer" => "Картинки для пунктов меню",
			"mmenu-img_header" => "Заголовок",
			"mmenu-img_free" => "Надпись в меню<br>свободная",
			"mmenu-img_mover" => "Надпись в меню<br>mouseover",
			"mmenu-img_small_free" => "Надпись в меню маленькая<br>свободная",
			"mmenu-img_small_mover" => "Надпись в меню маленькая<br>mouseover",
			"mmenu-img_small_current" => "Надпись в меню маленькая<br>текущая",
			"mmenu-img_ctx_top" => "Контекстная картинка сверху<br>(наследуется)",
			"mmenu-img_ctx_left" => "Контекстная картинка слева<br>(наследуется)",



//			"" => "",
//			"-graycomment" => "",


		);


		// userland constants defined in backoffice/*.php
		$msg_bo_add_passwd_generated = "при добавлении из бэкоффиса сгенерирован пароль";		
		$msg_bo_update_passwd_entered = "оператор бэкоффиса ввёл новый пароль";
		$msg_bo_face_auth_as_user = "войти этим пользователем";
		$msg_bo_face = "лицо";
		$msg_bo_email_from_admin = "Письмо от администратора";
		$msg_bo_add_passwd_generation_gray = "в момент добавления нового пользователя логин и пароль генерируются автоматически<br>при изменении пароля существующего пользователя пароль будет преобразован в md5";
		$msg_bo_sort_offers_onindex = "отсортировать спецпредложения на главной";

// change_word
		$msg_bo_changeword_import_ident = "Замена слова по всей БД";
		$msg_bo_changeword_ucase_lcase = "строчные/прописные";
		$msg_bo_changeword_replace_seleted = "Заменить выделенные";
		$msg_bo_changeword_not_found = "не найдено во всех значимых полях БД сайта";

		$msg_bo_changeword_table_id = "Таблица:ID";
		$msg_bo_changeword_field = "Поле";
		$msg_bo_changeword_before = "До";
		$msg_bo_changeword_replace = "Заменить";
		$msg_bo_changeword_what_welook = "Что ищем:";
		$msg_bo_changeword_replacement = "На что заменить:";
		$msg_bo_changeword_find = "Искать";

// ic
		$msg_bo_ic_icdict_values = "значения справочника";
		$msg_bo_ic_columns_in_table = "Колонок в таблице";

		$msg_bo_ic_string_template = "Строка-шаблон";
		$msg_bo_ic_html = "HTML код";
		$msg_bo_ic_width = "ширина";		
		$msg_bo_ic_height = "высота";
		$msg_bo_ic_default = "по умолч";

		$msg_bo_ic_filemax_kb = "Вес не более, Кб";
		$msg_bo_ic_default_state = "Default состояние";
		$msg_bo_ic_no_additional_params = "[НЕТ ДОПОЛНИТЕЛЬНЫХ ПАРАМЕТРОВ]";
		
		$msg_bo_ic_formula = "Formula<br><br>";
		$msg_bo_ic_formula_graycomment = "example: [USD_AUCTION_COST + USD_AUCTION_FEE] where all variables are hashkeys of same sheet; no () and calc priorities";
		
		

			// /userland constants defined in backoffice/*.php



			// _edit_fields.php
		$msg_bo_backtolist = "Вернуться к списку";
		$msg_bo_F5_title = "Перечитать все поля из БД \n\nполезно при одновременном \nизменении этой страницы \nнесколькими редакторами";
		$msg_bo_previous_element = "предыдущий элемент";
		$msg_bo_next_element = "следующий элемент";

		$msg_bo_switch_to_textarea = "как TEXTAREA";
		$msg_bo_switch_to_textarea_tip = "переключить на TEXTAREA; изменения не сохранятся; будет перечитано сохранённое ранее";
		$msg_bo_switch_to_freetext = "как FREETEXT";
		$msg_bo_switch_to_freetext_tip = "переключить на FREETEXT; изменения не сохранятся; будет перечитано сохранённое ранее";
		
		$msg_bo_imgtype_not_defined = "не определён этот тип картинок";
		$msg_bo_icwhose_not_defined = "эта анкета не определена";
		
		$msg_bo_it_change = "изменить";
		$msg_bo_it_add = "добавить";
		$msg_bo_it_tolist = "в список";
		

		$msg_bo_add = "Добавить";
		$msg_bo_add_unable = "Нельзя добавить";
		$msg_bo_save = "Сохранить";
		$msg_bo_updated = "Обновлено";
		$msg_bo_updated_for = "для";

		$msg_bo_subitems = "Подпункты";
		$msg_bo_preview = "предварительный просмотр";
			// _edit_fields.php
		
			// _compositebidiect.php
		$msg_bo_subgroup_qnty = "подгрупп";
		$msg_bo_products_in_subgroup = "товаров в подгруппе";
		$msg_bo_products_selected = "выбрано товаров";
		$msg_bo_products_in_subgroup_selected = "из них выбрано товаров";
		$msg_bo_go_product_editing = "перейти в редактирование товара";
		$msg_bo_not_selected = "не выбран";
		$msg_bo_bidirect_reciplink_restored = "восстановлена обратная связь";
		$msg_bo_bidirect_reciplink_restore_failed = "не удалось восстановить обратную связь";
		$msg_bo_bidirect_reciplink_was_absent = "не было связи";
		$msg_bo_bidirect_directlink_was_absent = "не было прямой связи";
		$msg_bo_bidirect_directlink_add_failed = "не удалось добавить прямую связь";
		$msg_bo_bidirect_reverselink_add_failed = "не удалось добавить обратную связь";
			// /_compositebidiect.php


			// _entity_edit.php
		$msg_bo_required_parameter_missing = "Не указан обязательный параметр";
		$msg_bo_file_delete_unable = "Удаление файла невозможно";
		$msg_bo_file_format_wrong = "Неверный формат файла";
		$msg_bo_database_updated = "Информация обновлена";
		$msg_bo_database_swapfield_unable = "Нельзя переместить";
			// /_entity_edit.php


			// _image_layer.php
		$msg_bo_img_preview_only = "только для превью \n(не для использования с лица)";
		$msg_bo_img_original = "оригинал";
		$msg_bo_img_jpeg_save_optimalq = "качество сохранения JPEG: 0…100 \n\nоптимальное значение 75";
		$msg_bo_img_width_destination = "ширина желаемая \n\nдля пропорционального масштабирования \nвведите одну из величин";
		$msg_bo_img_height_destination = "высота желаемая \n\nдля пропорционального масштабирования \nвведите одну из величин";
		$msg_bo_img_big_overwrite = "масштабировать [большую]";
		$msg_bo_img_big_overwrite_tip = "перезаписать [большую] картинку \nресайзом из заливаемой \n\nдействует только в момент заливки";
		$msg_bo_img_small_create_frombig = "создать [маленькую] из [большой]";
		$msg_bo_img_small_create_frombig_tip = "перезаписать [маленькую] картинку \nресайзом из [большой] \n\nесли [большая] отсутствует \n[маленькая] не удаляется";
		$msg_bo_img_marker_tip = "Click, [Ctrl-C], [Ctrl+V] в нужном месте \n\nэтот маркер, вставленный в текст: \n1. отображает [маленькую] картинку с подписью \n2. при клике на [маленкую] картинку \nотрывается всплывает окошко \n3. в окошке нужных размеров отображается \n[большая] картинка с подписью";
		$msg_bo_img_published = "опубликовано";
		$msg_bo_img_published_tip = "этот флажок действует, \nесли картинка вставляется \nчерез маркер &#35;IMG#ID#&#35;";
		$msg_bo_img_main = "главная";
		$msg_bo_img_delete = "удалить";
		$msg_bo_img_delete_existing = "удалить существующую";
		$msg_bo_img_delete_tip = "картинки физически \nстираются с диска, \nосвобождая место";
		$msg_bo_img_label = "подпись";
		$msg_bo_img_label_tip = "подпись возникает под картинкой, \nтолько если картинка вставляется \nчерез маркер &#35;IMG#ID#&#35;";
		$msg_bo_img_try_dragging = "попробуйте перетащить эту картинку \nв текст, отображённый через FreeTextBox";

		$msg_bo_img_maker_width = "ширина \n\nэти размеры действуют \nтолько при использовании \nмаркера &#35;IMG#ID#&#35; \n\nпри изменении числа в этом поле \nменяются только размеры, \nподставляемые через маркер; \nмасштабирования исходной картинки \nне происходит";
		$msg_bo_img_maker_height = "высота \n\nэти размеры действуют \nтолько при использовании \nмаркера &#35;IMG#ID#&#35; \n\nпри изменении числа в этом поле \nменяются только размеры, \nподставляемые через маркер; \nмасштабирования исходной картинки \nне происходит";
		
		$msg_bo_img_upload_tip = "поле для загрузки одной фотографии (Browse) \n\nможно залить одну [большую] картинку, \nотмасштабировать её при закачке \nи создать [маленькую] в одно действие \n\nPS: лимит на выполнение страницы - $max_execution_time секунд";
		$msg_bo_img_upload_only_one = "здесь можно указать \nтолько один файл картинки \n\nчтобы залить \nнесколько новых картинок, \nиспользуйте [НОВАЯ КАРТИНКА] \n\nне стоит указывать \nи файл через Browse, \nи URL одновременно";
		$msg_bo_img_upload_zip_tip = "чтобы загрузить несколько картинок, создайте \nZIP-архив каталога, где хранятся картинки, \nи укажите здесь получившийся ZIP-архив \n\nкартинки, расположенные в подпапках внутри \nархива - также будет добавлены на эту страницу \n\nвложенные архивы НЕ обрабатываются \n\nтак можно залить несколько [больших] картинок, \nотмасштабировать каждую при закачке \nи создать [маленькие] картинки в одно действие \n\nPS: лимит на выполнение страницы - $max_execution_time секунд";
		$msg_bo_img_upload_url_tip = "чтобы залить несколько картинок, перечислите \nв этом поле URL'и картинок через пробел \n\nтак можно залить несколько [больших] картинок, \nотмасштабировать каждую при закачке \nи создать [маленькие] в одно действие \n\nPS: лимит на выполнение страницы - $max_execution_time секунд";

		$msg_bo_img_label_equal_filename = "= имя файла";
		$msg_bo_img_label_equal_filename_tip = "записать в подпись картинки \nимя файла без расширения \nдля русских имён файлов \nподпись будет записана в кодировке windows, \nдо преобразования в транслит \n\nдействует только в момент заливки";
		$msg_bo_img_file_deleted = "...удалён файл";
		$msg_bo_img_directory_deleted = "...удалён каталог";

		$msg_bo_img_file_size_bytes = "б";
		$msg_bo_img_file_lost = "(потерян)";

		$msg_bo_img_label_big = " большая";
		$msg_bo_img_label_small = " маленькая";

		$msg_bo_img_popuphref_zoom_unable = "К сожалению, это изображение не увеличивается";

		$msg_bo_img_autoresize_element_has = "авторесайз: у элемента";
		$msg_bo_img_autoresize_element_has_no_big_uploaded = "не залито ни одной большой картинки";
		$msg_bo_img_autoresize_element_has_no_resize_apply_checked = "не выставлено [применять авторесайз]";
		$msg_bo_img_autoresize_element_has_no_big_uploaded_or_HW_zero = "не залито большой картинки или оба параметра ширина-высота авторесайза равны нулю";
			// /_image_layer.php
			

			// /_input_control.php
		$msg_bo_select_ctrl_shift = "Для выделения<br>можно использовать<br>клавиши Shift и Ctrl";
		$msg_bo_field_obligatory = "это поле обязательно для заполнения";
			// /_input_control.php

			// /_input_types.php
		$msg_bo_depend_of_that_group = "данной группы";
		$msg_bo_linked_elements = "Связанные элементы";
		$msg_bo_file_delete_existing = "удалить существующий";
		$msg_bo_img_popup = "посмотреть";
		$msg_bo_img_not_uploaded = "[изображение не залито]";
		$msg_bo_notification_send = "отправить уведомление";
			// /_input_types.php


			// _link_types.php
		$msg_bo_depend_of_that_group = "данной группы";
		$msg_bo_field = "Поле";
		$msg_bo_edit = "редактировать";
		$msg_bo_empty = "[пусто]";
		$msg_bo_link_delete = "[удалить привязку]";
		$msg_bo_selector_every = " - все - ";
		$msg_bo_o2m_link_absent = " - нет привязки - ";
		$msg_bo_o2m_link_delete = " - удалить привязку - ";
		$msg_bo_fyu = "Информация для справки";
			// /_link_types.php


			// _list.php
		$msg_bo_depend_of_that_group = "данной группы";
		$msg_bo_delete = "Удалить";
		$msg_bo_delete_all = "все";
		$msg_bo_delete_all_tip = "галочка относится только к кнопке УДАЛИТЬ";
		$msg_bo_delete_unable = "нельзя удалить";
		$msg_bo_depend_of_that_group = "данной группы";
		$msg_bo_faceopen = "открыть с лица";
			// /_list.php


			// _mysql.php
		$msg_pager_disabled = "pager disabled: too much to calculate, may hang on...";
		$msg_pager_open = "открыть";
		$msg_pager_page = "страницу";
		$msg_pager_nth = "-ю";
		$msg_pager_from = "из";
		$msg_pager_previous = "предыдущих";
		$msg_pager_next = "следующих";
		$msg_pager_all = "все";		
		$msg_bo_cant_be_parent_of_youself = "Нельзя указать родителем самого себя";
			// /_mysql.php


			// _sendmail.php
		$msg_sendmail_client_noaddress_notsent = "Клиент не указал email, ему не отправлено";
		$msg_sendmail_error_sending_bylist = "ошибка при отправке сообщения по адресам:";
		$msg_sendmail_error_emptylist = "Пустой список получателей";
		$msg_sendmail_mtpl = "шаблон письма";
		$msg_sendmail_mtpl_notfound_notsent = "не найден; письмо не отправлено";
		$msg_sendmail_sentto = "Сообщение отправлено на";
		$msg_sendmail_error_sendingto = "ОШИБКА при отправлении сообщения на";
		$msg_sendmail_sent_byaddress = "Отправлено по адресу:";
			// /_sendmail.php

			// _submenu.php
		$msg_submenu_search = "Поиск";
		$msg_submenu_find = "искать";
		$msg_submenu_all = "все";		
		$msg_submenu_shown = "показано:";
		$msg_submenu_shown_from = "из";
			// /_submenu.php

			// _updown.php
		$msg_direction_up = "вверх";
		$msg_direction_down = "вниз";
		$msg_bo_updown_element = "Элемент";
		$msg_bo_updown_element_moved = "перемещён";
		$msg_bo_updown_element_move_unable = "Нельзя переместить";
			// /_updown.php

			// _init.php
		$msg_bo_jsv_checkbox_not_checked = "Не нажата галочка ";
		$msg_bo_jsv_fieldcheck_failed = "Некорректно заполнено поле ";
			// /_init.php


			// mtpl-popup
		$msg_check_popup = "посмотреть";

			// face
		$msg_no_picture = "нет фото";
		$msg_other_pictures = "ещё фото";
		$msg_picture_absent = "изображение отсутствует";
		$msg_details = "подробнее";
			// /face


		break;

	case "en":
		if (!isset($site_name))
		$site_name = "Webie.CMS template website";
		
		// $menu_bo in _constants.php has empty values because of language dependency (filled at bottom of _messages.php)
		if (!isset($entity_list))
		$entity_list = array (
			"person" => "Персоны",
		//	"=article-lost.php" => "Статьи без разделов",
			"article" => "Статьи",
			"agroup" => "Рубрики",
			"m2m_article_rating" => "Рейтинг,отзыв,пожел",
			"m2m_article_replic" => "Обсуждение",

			"cgroup" => "Client Groups",
			"customer" => "Clients",
			"corder" => "Orders",
		
			"=product-lost.php" => "Lost Products",
			"product" => "Products",
//			"pgroup" => "Product Groups",
			"pgroup" => "Categories",
			"=pgroup-onindex.php" => "Categories order on Homepage",
			"supplier" => "Suppliers",
			"pmodel" => "Models",

			"spart" => "Spare Parts",
			"sgroup" => "Spare Parts Categories",

			"currency" => "Currencies",
		
			"m2m_product_rating" => "Rating,Opinion,Claim",
			"m2m_product_replic" => "Discussion",
		
			"news" => "News",
			"ngroup" => "News Groups",
			"=../mailer/user/login.php?mlist=1&l_login=1234&l_passwd=1234&mode=login' target='_blank" => "Mailer",
		
			"faq" => "FAQ",
			"fgroup" => "FAQ Group",
		
			"banner" => "Banners",
			"bgroup" => "Banner groups",
		
			"constant" => "Constants",
			"cached" => "Cache",
			"mtpl" => "Mail Templates",
			"sentlog" => "Sent Messages",
			"imgtype" => "Image Types",
			"img" => "All Images",
			"change_word" => "Replace everywhere",
			"jsvalidator" => "Input Validators",
		
			"icwhose" => "Sheets",
			"ic" => "Sheet Questions",
			"icdict" => "Dictionnaries",
			"icdictcontent" => "Dictionnary Values",
			"ictype" => "Input Types",
			"icsheet" => "Sheets Filled",
		
			"mmenu" => "Document Tree",		//Website Structure
			"=mmenu-legend.php?parent_id=2" => "Website Legend",
		);
		
		
		if (!isset($add_entity_msg_list))
		$add_entity_msg_list = array (
			"person" => "новую персону",
			"article" => "новую статью",
			"agroup" => "новую рубрику",
			"m2m_article_rating" => "Новый Рейтинг,отзыв,пожел",
			"m2m_article_replic" => "Новую реплику",

			"customer" => "new Client",
			"сgroup" => "new Client Group",
			"pimportsource" => "new pricelist import setting",
			"product" => "new Product",
			"pgroup" => "new Product Group",
			"ugroup" => "new User Group",
			"supplier" => "new Supplier",
			"pmodel" => "new Product Model",
		
			"spart" => "new Spare Part",
			"sgroup" => "new Spare Parts Category",

			"country" => "new Country",
			"currency" => "new Currency",
		
			"m2m_product_rating" => "new Rating,Opinion,Claim",
			"m2m_product_replic" => "new Replic",
		
			"taxrate" => "new Tax Rate",
			"package" => "new Package",
			"saleunit" => "new Product Unit",
			"shiptype" => "new Shipment Type",
			"pclass" => "new Product Class",
		
			"shop" => "new Shop",
		
			"banner" => "new Banner",
			"bgroup" => "new Banner Group",
			"news" => "new News",
			"ngroup" => "new News Group",
		
			"faq" => "new вопрос-ответ",
			"fgroup" => "new FAQ Group",
		
			"constant" => "new Constant",
			"cached" => "new Cache Record",
			"mtpl" => "new Mail Template",
			"imgtype" => "new Image Type",
			"img" => "new Image",
			"jsvalidator" => "new Input Validator",
		
			"icwhose" => "new Sheet",
			"ic" => "new Sheet Question",
			"icdict" => "new Dictionnary",
			"icdictcontent" => "new Dictionnary Value",
			"ictype" => "new Input Type",
		
			"mmenu" => "new Menu Item",
		);
		
		
		if (!isset($new_entity_ident_list))
		$new_entity_ident_list = array (
			"person" => "новая персона",
			"article" => "новая статья",
			"agroup" => "новая рубрика",
			"m2m_article_rating" => "новый Рейтинг,отзыв,пожел",
			"m2m_article_replic" => "новая реплика",

			"customer" => "new Client",
			"сgroup" => "new Client Group",
			"pimportsource" => "new pricelist import setting",
			"product" => "new Product",
			"pgroup" => "new Product Group",
			"ugroup" => "new User Group",
			"supplier" => "new Supplier",
			"pmodel" => "new Product Model",
		
			"spart" => "new Spare Part",
			"sgroup" => "new Spare Parts Category",

			"country" => "new Country",
			"currency" => "new Currency",
		
			"m2m_product_rating" => "new Rating,Opinion,Claim",
			"m2m_product_replic" => "new Replic",
		
			"taxrate" => "new Tax Rate",
			"package" => "new Package",
			"saleunit" => "new Product Unit",
			"shiptype" => "new Shipment Type",
			"pclass" => "new Product Class",
		
			"shop" => "new Shop",
		
			"banner" => "new Banner",
			"bgroup" => "new Banner Group",
			"news" => "new News",
			"ngroup" => "new News Group",
		
			"faq" => "новый вопрос-ответ",
			"fgroup" => "новая категория FAQ",
		
			"constant" => "new Constant",
			"cached" => "new Cache Record",
			"mtpl" => "new Mail Template",
			"imgtype" => "new Image Type",
			"img" => "new Image",
			"jsvalidator" => "new Input Validator",
		
			"icwhose" => "new Sheet",
			"ic" => "new Sheet Question",
			"icdict" => "new Dictionnary",
			"icdictcontent" => "new Dictionnary Value",
			"ictype" => "new Input Type",
		
			"mmenu" => "new Menu Item",
		);

/*
// формируем "Группа клиентов" из "Новая группа клиентов"
		$entity_list_single = array ();
		foreach ($new_entity_ident_list as $new_entity_name => $new_entity_txt) {
			$new_entity_txt = preg_replace("~^\S+\s+~", "", $new_entity_txt);
			$new_entity_txt = ucfirst($new_entity_txt);
			$entity_list_single[$new_entity_name] = $new_entity_txt;
		}
//		pre($entity_list_single);
*/

		$msg_fields = array (
// common
			"id" => "№",
			"ident" => "Title",
			"annotation" => "Annotation",
			"annotation-graycomment" => "label under the menu item",
			"hashkey" => "Key",
			"date_created" => "Creation Date",
			"date_updated" => "Update date",
//			"date_published" => "Publishing date",		// too many entities where data_published is date of ...
			"date_published" => "Date",
			"img_cnt" => "Img",
			"published-list" => "Pbl",
			"published-edit" => "Published",
			"i_published-list" => "OnMainP",
			"i_published-edit" => "On Main Page",

			"~delete" => "Del",
			"parent_id" => "Parent",
			"group" => "Group",
			"comment" => "Internal Comment",

			"pagetitle" => "Page Title",
			"title" => "Content Title",
			"meta_keywords" => "Meta Keywords",
			"meta_description" => "Meta Description",

// "explainations are too complicated"
//			"brief-edit" => "Кратко<br><br>текст в список<br>однородных<br>элементов<br><br>(новости в ленте,<br>продукты<br> одной группы...)",
//			"content-edit" => "Описание<br><br>текст в<br>карточку товара,<br>текст новости ...",

			"brief" => "Brief",
			"content" => "Content",

			"date_lastclick" => "Last Click",
			"remote_address" => "Registraion IP",
			"lastip" => "Last Click IP",
			"lastsid" => "Cookie sent",
			"idrandom" => "IDrandom",

			"additional_layer" => "Additional options",
			"service_layer" => "Service Fields",
			"filesattached_layer" => "Downloadable files",

			"product_iccontent" => "Product Properties",

			"file1" => "File 1",
			"file1_comment" => "Comment 1",
			"file2" => "File 2",
			"file2_comment" => "Comment 2",
			"file3" => "File 3",
			"file3_comment" => "Comment 3",
			"file4" => "File 4",
			"file4_comment" => "Comment 4",
			"file5" => "File 5",
			"file5_comment" => "Comment 5",

// customer
			"contract_discount" => "Discount,&nbsp;%",
			"login" => "Login",
			"passwd" => "Password",
			"manager_name" => "Contact",
			"cgroup-list" => "Group",			// next taking from entity_list_single
//			"customer-ident" => "Клиенты фыва",	// next taking from entity_list_single
			"phone" => "Phone",
			"address" => "Address",
			"fax" => "Fax",
			"contract_number" => "Contract Number",
			"tin" => "UTN",
			"customer_sheet" => "Customer Sheet",
			"login_layer" => "Login, Password, Access",
			"customer-published-list" => "Access",
			"customer-published-edit" => "Access Granted",
			"customer-date_created-edit" => "Registered",
			"customer-date_updated-edit" => "Updated",

// product
			"pgroup-list" => "Categories",			// next taking from entity_list_single
			"pgroup-edit" => "Categories",			// next taking from entity_list_single
			"article" => "Article",
			"price_1" => "Price",
			"price_2" => "Price 2",
			"price_3" => "Price 3",
			"pricecomment_1" => "Price Comment",
			"pricecomment_2" => "Price Comment 2",
			"pricecomment_3" => "Price Comment 3",

			"product-brief-edit" => "Brief<br><br>for same group<br>product list",
			"product-content-edit" => "Description<br><br>for product card,<br>at rigth of photos",
					
			"weight" => "Weight",
			"hits" => "Hits",
			"hits-edit" => "Hits",
			"hits-graycomment" => "any request from Face, robots are counted as well",

			"briefful_layer" => "Short and Full Description",
			"news4product_layer" => "Product is connected to news...",
//			"product-date_published" => "Дата",
			
			"archived-list" => "Archive",
			"archived" => "In Archive",
			"is_new" => "New",
			"banner_top" => "Banner on Top",

			"sold" => "Sold",
			"disclaimer_list" => "Disclaimer for product list",
			"disclaimer_pcard" => "Disclaimer for product card",

// pgroup
			"divclass" => "DIV Class",
			//"file1" => "Image in Menu",
			//"file1-graycomment" => "only for 1-level groups",

// currency
			"date_exchrate_rub" => "Rate Date",
			"exchrate_rub" => "Exchange Rate",
			"exchrate_rub_multiplier" => "Multiplier",
			"exchrate_rub_multiplied" => "Result Rate",
			"currency-date_updated" => "Last Update",
			"src_href" => "Rate Source",
			"src_content" => "Content<br>Cached",
			"exchrate_regexp" => "exchrate_regexp",
			"daterate_regexp" => "daterate_regexp",

// m2m_product_rating
			"m2m_product_rating-customer_ident" => "Whose rating",
			"rating" => "Rating",
			"opinion" => "WhatGood",
			"opinion-edit" => "What She liked,<br>What Was Bad",
			"wish" => "Proposals",
			"m2m_product_rating-content" => "Moderator's<br>Comment",

// news
//			"news-date_published" => "Date",
			"ngroup_ident-list" => "News Group",
			"rsss_ident" => "RSS-source",
			"rss_published" => "In&nbsp;RSS",
			"news-brief-edit" => "Brief",
			"news-content-edit" => "News Content",

			"srcurl" => "<a href='#SRCURL#' target=_blank>Source</a>",
			"hrefto" => "<a href='#HREFTO#' target=_blank>Hyperlink</a>",

// faq
			"cname" => "Name",
			"email" => "Email",
			"answer_sent" => "Sent",
			"answer_sent-edit" => "Send Answer",
			"answer_sent-graycomment" => "if you need to send an answer to client by email indicated - check this box and save",
			"subject" => "Subject",
			"contact" => "Other Contacts",
			"contact-edit" => "Other<br>Contacts",
			"faq-content" => "Consultant's Reply",
			"faq-fgroup-list" => "FAQ Group",
			"faq-fgroup-graycomment" => "Select Common Group!",

// constant
			"constant-ident" => "Name",
			"constant-content" => "Value",

// cached
			"cached-date_published" => "Cached",
			"expiration_minutes" => "Keep, min",
			"expiration_minutes-list" => "Min",
			"expiration_minutes-graycomment" => "for automatic update: expiration date = NOW + THIS FIELD minutes",
			"date_expiration" => "Expiration",
			"scriptname_updated" => "Updater",
			"scriptname_created" => "Creator",

// mtpl
			"subject" => "Mail Subect",
			"body" => "Mail Body",
			"admtail" => "Administrator's<br>tail",
			"admtail-graycomment" => "text in this field is added to letter; you may use #HTTP_HOST# etc...",
			"sender" => "Sender",
			"rcptto" => "Recipient",
			"rcptto-graycomment" => "email, email... of managers who get a copy of client message + [Adninistrator's tail]",
			"sentmsg" => "In Case of Success",
			"sentmsg-graycomment" => "FrontEnd message after mail been sent successfully (javascript:alert)",

			"savesentlog" => "SentLog",
			"savesentlog-edit" => "Save<br>SentMail",
			"savesentlog-graycomment" => "after having sent, a copy is saved in <a href='sentlog.php'>Sent Log</a>",			


// sentlog
			"sentlog-content-list" => "Letter Content",
			"sentlog-content-edit" => "Letter<br>Content",

// imgtype
			"imgtype-content" => "Internal<br>Comment",
			"imglimit-list" => "ImageLimit",
			"imglimit" => "Image Limit",
			"imglimit-graycomment" => "limit for images uploaded; 0 = unlimited",

			"imgsmall_layer" => "Small Image in Backoffice",
			"img_present" => "Small Image is shown",
			"img_present-graycomment" => "whether Small Image -> [Browse] is displayed",
			"img_newqnty" => "Quantity of buttons [Browse]",
			"img_newqnty-graycomment" => "if you want to upload several at once; 0=1",
			"img_zip_present" => "ZIP upload",
			"img_zip_present-graycomment" => "whether Small Image -> [Browse] ZIP is displayed for New Images",
			"img_url_present" => "URL upload",
			"img_url_present-graycomment" => "whether Small Image -> [URL] is displayed for new and uploaded",
			"img_txt_present" => "Image Label",
			"img_txt_present-graycomment" => "whether Image Label for Small Image is displayed",
			"img_txt_eq_fname" => "Checkbox [Image Label = File Name]",
			"img_txt_eq_fname-graycomment" => "Checkbox [Image Label = File Name] state for new Images",
			"resize_published" => "Create [Small] Resize from [Big]",
			"resize_published-graycomment" => "whether the feature is displayed",
			"resize_default_checked" => "Create checkbox state",
			"resize_default_checked-graycomment" => "whether Create Resize is checked by default",
			"resize_default_qlty" => "[Big]>>[Small] resize Quality",
			"resize_default_qlty-graycomment" => "JPEG Quality: 0&#8230;100",
			"resize_default_width" => "[Small] Resize Width",
			"resize_default_height" => "[Small] Resize Height",

			"imgbig_layer" => "Big Image in Backoffice",
			"img_big_present" => "Big Image is shown",
			"img_big_present-graycomment" => "whether Big Image -> [Browse] is displayed",
			"img_big_newqnty" => "Quantity of buttons [Browse]",
			"img_big_newqnty-graycomment" => "if you want to upload several at once; 0=1",
			"img_big_zip_present" => "ZIP upload",
			"img_big_zip_present-graycomment" => "whether Big Image -> [Browse] ZIP is displayed for New Images",
			"img_big_url_present" => "URL upload",
			"img_big_url_present-graycomment" => "whether Big Image -> [URL] is displayed for new and uploaded",
			"img_big_txt_present" => "Image Label",
			"img_big_txt_present-graycomment" => "whether Image Label for Big Image is displayed",
			"img_big_txt_eq_fname" => "Checkbox [Image Label = File Name]",
			"img_big_txt_eq_fname-graycomment" => "Checkbox [Image Label = File Name] state for new Images",
			"big_resize_published" => "Resize [Big] while upload",
			"big_resize_published-graycomment" => "whether the feature is displayed",
			"big_resize_default_checked" => "Create checkbox state",
			"big_resize_default_checked-graycomment" => "whether Create Resize is checked by default",
			"big_resize_default_qlty" => "[Big] upload resize Quality",
			"big_resize_default_qlty-graycomment" => "JPEG Quality: 0&#8230;100",
			"big_resize_default_width" => "[Big] Resize Width",
			"big_resize_default_height" => "[Big] Resize Height",




			"imgthumb_layer" => "Thumbnail in Backoffice",
			"img_thumb_present" => "Thumbnail is shown",
			"img_thumb_present-graycomment" => "whether thumbnail is displayed at right of upload form",
			"img_thumb_qlty" => "[Thumbnail] resize Quality",
			"img_thumb_qlty-graycomment" => "JPEG Quality: 0&#8230;100",
			"img_thumb_width" => "[Thumbnail] resize Width",
			"img_thumb_height" => "[Thumbnail] resize Height",
			
			"imgfirst_layer" => "Autoresize of First (ex, for list of products)",
			"_autoresize_qlty" => "Autoresize Quality",
			"_autoresize_qlty-graycomment" => "JPEG Quality: 0&#8230;100",
			"_autoresize_width" => "Autoresize Width",
			"_autoresize_height" => "Autoresize Height",
			"_autoresize_apply" => "Autoresize Active",
			"_autoresize_apply-graycomment" => "Autoresize are created on page Requst",
			"_merge_img" => "Watermark",
			"_merge_img-graycomment" => "whether Watermark is put over the Autoresize",
			"_merge_alfa" => "Transparency",
			"_merge_alfa-graycomment" => "Image overlay's Transparency: 0&#8230;100",
			"_merge_type" => "Overlay Method",
			"_merge_type-graycomment" => "0=Propotrional Resize; 1=Tile",
			"_merge_apply" => "Put Watermark",
			"_merge_apply-graycomment" => "delete file-watermark of release the tick",
			"_autoresize_debug" => "Debug Autoresize",
			"_autoresize_debug-graycomment" => "print messages on Frontent about: autoresize, delete stallen, watermarks",
			"_autoresize_tpl_ex" => "Template for<br>Existing",
			"_autoresize_tpl_nex" => "Template for<br>Non-Existing",
			
			"imgfirst2_layer" => "Autoresize2 of First (ex, for list of products)",
			"imgfirst3_layer" => "Autoresize3 of First (ex, for list of products)",
			"imgfirst4_layer" => "Autoresize4 of First (ex, for list of products)",
			"imgevery_layer" => "Autoresize of Every (ex, for previews in product card)",
			"imgevery2_layer" => "Autoresize2 of Every (ex, for previews in product card)",
			"imgevery3_layer" => "Autoresize3 of Every (ex, for previews in product card)",
			"imgevery4_layer" => "Autoresize4 of Every (ex, for previews in product card)",
			
			"imgmsg_layer" => "Backoffice Labels",
			"msg_ident" => "Label [Image]",
			"msg_add" => "Label [New Image]",
			"msg_change" => "Label [Change Image]",
			"msg_img" => "Label [Small]",
			"msg_img_big" => "Label [Big]",

			"img_table" => "Image DB Table<br>[img] if empty",
			"merge_seed" => "Merge Seed",
			"merge_seed-graycomment" => "used for watermarked image",
			
			"imgtype-date_updated" => "Last Updated",
			"imgtype-date_updated-graycomment" => "Autoresizes older than Date will be re-generated",
			
// img
			"~img_tag" => "Marker",
			"~img_linkedto" => "Link To",

// img
			"jsvalidator-content" => "JavaScript<br>RegularExpression",

// icwhose
//			"icwhose-hashkey" => "Key for Templates",
			"bo_only-list" => "BO",
			"bo_only" => "Only in BackOffice",
			"bo_only-graycomment" => "Sheet will not be displayed in BackOffice",
			"jsv_debug" => "Debug",
			
// ic
			"obligatory-list" => "Reqd",
			"obligatory_bo-list" => "BOReqd",
			"inbrief-list" => "inList",
			"sorting-list" => "Sort",
			"published_bo-list" => "PubBO",

			"obligatory" => "Required",
			"obligatory_bo" => "Required in BO",
			"inbrief" => "Show in Brief",
			"inbrief-graycomment" => "field is displayed in product list",

			"sorting" => "Sortable Field",
			"sorting-graycomment" => "FrontEnd: in product list you may sort by this column",
			"published_bo" => "Published in BO",

			"graycomment" => "Comment",
			"ic-icwhose" => "Sheet - whose field is",

// icdict
			"icdict-icwhose" => "Whose Dictionnary is",
			
// icdictcontent
			"label_style-list" => "Label Style",
			"tf1_width-list" => "TF",
			"tf1_incolumn-list" => "TFsep",
			"icdictcontent-content" => "Descr",
			
			"label_style" => "Label Style=[]",
			"tf1_width" => "Textfield width [] is nearby",
			"tf1_incolumn" => "Textfield in Separate Column",

			"tf1_addtodict" => "Add values<br>from Textfield to Dictionnary",
			"tf1_addedpublished" => "Whether added values<br>becomes Published",


// mmenu
			"mmenu-hashkey" => "Key/Link",
			"mmenu-is_heredoc-list" => "Stand",
			"mmenu-is_heredoc" => "Standard Document",
			"mmenu-is_heredoc-graycomment" => "yes = [only content is displayed, there is no special page]",

			"mmenu-is_drone-list" => "Drone",
			"mmenu-is_drone" => "Drone",
			"mmenu-is_drone-graycomment" => "FrontOffice: menu href leads to first child element",

			"mmenu-content_no_freetext-list" => "TArea",
			"mmenu-published_legend-list" => "Legnd",
			"mmenu-published_legend" => "Published In Legend",

			"mmenu-published_sitemap-list" => "Map",
			"mmenu-published_sitemap" => "Published In SiteMap",

			"mmenuimg_layer" => "Images for menu items",
			"mmenu-img_header" => "Title",
			"mmenu-img_free" => "Menu image<br>MouseFree",
			"mmenu-img_mover" => "Menu image<br>MouseOver",
			"mmenu-img_small_free" => "Label in menu small<br>MouseFree",
			"mmenu-img_small_mover" => "Label in menu small<br>MouseOver",
			"mmenu-img_small_current" => "Label in menu small<br>Current",
			"mmenu-img_ctx_top" => "Upper Context image<br>(inherits)",
			"mmenu-img_ctx_left" => "Left Context image<br>(inherits)",



//			"" => "",
//			"-graycomment" => "",


		);


			// userland constants defined in backoffice/*.php
		$msg_bo_add_passwd_generated = "password generated while adding";
		$msg_bo_update_passwd_entered = "backoffice operator entered new password";
		$msg_bo_face_auth_as_user = "login by this user";
		$msg_bo_face = "face";
		$msg_bo_email_from_admin = "Message from Administrator";
		$msg_bo_add_passwd_generation_gray = "while new user is added, his login and password will be generated automatically<br>when password is changed, password will be crypted with md5";
		$msg_bo_sort_offers_onindex = "Sort offers on index page";

// change_word
		$msg_bo_changeword_import_ident = "Change the word all across DataBase";
		$msg_bo_changeword_ucase_lcase = "uppercase/lowercase";
		$msg_bo_changeword_replace_seleted = "Change Selected";
		$msg_bo_changeword_not_found = "Not Found in every significant DataBase Fields";

		$msg_bo_changeword_table_id = "Table:ID";
		$msg_bo_changeword_field = "Field";
		$msg_bo_changeword_before = "Before";
		$msg_bo_changeword_replace = "Change";
		$msg_bo_changeword_what_welook = "We look for:";
		$msg_bo_changeword_replacement = "We replace to:";
		$msg_bo_changeword_find = "Find";

// ic
		$msg_bo_ic_icdict_values = "Dictionnary Values";
		$msg_bo_ic_columns_in_table = "Table Columns";

		$msg_bo_ic_string_template = "String-Template";
		$msg_bo_ic_html = "raw HTML";
		$msg_bo_ic_width = "width";
		$msg_bo_ic_height = "height";
		$msg_bo_ic_default = "by default";

		$msg_bo_ic_filemax_kb = "File size not exceeding, Kb";
		$msg_bo_ic_default_state = "Default state";
		$msg_bo_ic_no_additional_params = "[ADDITIONAL PARAMETERS NOT REQUIRED]";
		
		
		$msg_bo_ic_formula = "Formula<br><br>";
		$msg_bo_ic_formula_graycomment = "example: [USD_AUCTION_COST + USD_AUCTION_FEE] where all variables are hashkeys of same sheet; no () and calc priorities";
		

			// /userland constants defined in backoffice/*.php



			// _edit_fields.php
		$msg_bo_backtolist = "Return To List";
		$msg_bo_F5_title = "Re-read every field from DataBase \n\nuseful while page \nis edited and changed \nby multiple users";
		$msg_bo_previous_element = "previous element";
		$msg_bo_next_element = "next element";

		$msg_bo_switch_to_textarea = "as TEXTAREA";
		$msg_bo_switch_to_textarea_tip = "switch to TEXTAREA; changes will not be saved; content will be read again from prevously saved state";
		$msg_bo_switch_to_freetext = "as FREETEXT";
		$msg_bo_switch_to_freetext_tip = "switch to FREETEXT; ; changes will not be saved; content will be read again from prevously saved state";
		
		$msg_bo_imgtype_not_defined = "this Image Type is not defined";
		$msg_bo_icwhose_not_defined = "this Sheet is not defined";
		
		$msg_bo_it_change = "change";
		$msg_bo_it_add = "add";
		$msg_bo_it_tolist = "to the list";
		

		$msg_bo_add = "Add";
		$msg_bo_add_unable = "Unable to Add";
		$msg_bo_save = "Save";
		$msg_bo_updated = "Updated";
		$msg_bo_updated_for = "for";

		$msg_bo_subitems = "Sub-items";
		$msg_bo_preview = "preview";
			// _edit_fields.php
		
			// _compositebidiect.php
		$msg_bo_subgroup_qnty = "subgroups";
		$msg_bo_products_in_subgroup = "products in subgroup";
		$msg_bo_products_selected = "products seleted";
		$msg_bo_products_in_subgroup_selected = "products in subgroup seleted";
		$msg_bo_go_product_editing = "edit this product";
		$msg_bo_not_selected = "not selected";
		$msg_bo_bidirect_reciplink_restored = "reverse link is recovered";
		$msg_bo_bidirect_reciplink_restore_failed = "reverse link failed to recover";
		$msg_bo_bidirect_reciplink_was_absent = "link was not established";
		$msg_bo_bidirect_directlink_was_absent = "direct link was not established";
		$msg_bo_bidirect_directlink_add_failed = "failed to add direct link";
		$msg_bo_bidirect_reverselink_add_failed = "failed to add reverse link";
			// /_compositebidiect.php


			// _entity_edit.php
		$msg_bo_required_parameter_missing = "Required parameter is missing";
		$msg_bo_file_delete_unable = "Unable to delete file";
		$msg_bo_file_format_wrong = "Wrong file format";
		$msg_bo_database_updated = "Item is updated";
		$msg_bo_database_swapfield_unable = "Unable to move";
			// /_entity_edit.php


			// _image_layer.php
		$msg_bo_img_preview_only = "only for preview \n(not for FrontEnd usage)";
		$msg_bo_img_original = "original";
		$msg_bo_img_jpeg_save_optimalq = "JPEG Quality: 0…100 \n\noptimal value 75";
		$msg_bo_img_width_destination = "target width \n\nfor proportional resize \nenter one of values";
		$msg_bo_img_height_destination = "target height \n\nfor proportional resize \nenter one of values";
		$msg_bo_img_big_overwrite = "resize [Big] on-the-fly";
		$msg_bo_img_big_overwrite_tip = "overwrite [Big] image \nby upload self-resize \n\nactual only while uploading";
		$msg_bo_img_small_create_frombig = "create [Small] from [Big]";
		$msg_bo_img_small_create_frombig_tip = "overwrite [Small] image \nwith resize from [Big] \n\nif [Big] is absent \n[Small] will not be deleted";
		$msg_bo_img_marker_tip = "Click, [Ctrl-C], [Ctrl+V] in target place \n\nthis marker being inserted to text \n1. displays [Small] image with label \n2. on click to [Small] image \na popup window opens \n3. in properly sized window the \n [Big] image is displayed";
		$msg_bo_img_published = "published";
		$msg_bo_img_published_tip = "this flag works \nif the image was inserted \nby means of marker &#35;IMG#ID#&#35;";
		$msg_bo_img_main = "main";
		$msg_bo_img_delete = "delete";
		$msg_bo_img_delete_existing = "delete existing";
		$msg_bo_img_delete_tip = "images are physically \nerased from disk, \nreleasing free space";
		$msg_bo_img_label = "label";
		$msg_bo_img_label_tip = "label is shown under image \nonly if image is inserted to text \nby means of marker &#35;IMG#ID#&#35;";
		$msg_bo_img_try_dragging = "try to drag&drop this image \nto the text shown in FreeTextBox";

		$msg_bo_img_maker_width = "width \n\nthese sizes are applied \nonly when marker &#35;IMG#ID#&#35; \nwas used \n\nwhen you change value in this field \nonly sizes of HTML tag are changed, \nrepresented with marker; \nno resize of original image occurs";
		$msg_bo_img_maker_height = "height \n\nthese sizes are applied \nonly when marker &#35;IMG#ID#&#35; \nwas used \n\nwhen you change value in this field \nonly sizes of HTML tag are changed, \nrepresented with marker; \nno resize of original image occurs";
		
		$msg_bo_img_upload_tip = "field for upload one image (Browse) \n\nyou can upload one [Big] image, \nresize it while uploading \nand create [Small] at once \n\nPS: maximum page execution time is $max_execution_time sec";
		$msg_bo_img_upload_only_one = "here you can upload \nonly one image \n\nin order to upload \nmultiple new images \nuse [NEW IMAGE] \n\nyou can not upload both \nfile with Browse, \nand URL simultaneously";
		$msg_bo_img_upload_zip_tip = "in order to upload multiple images, create \nZIP-archive containing whole folder with images, \nand select here resulting ZIP-archive \n\nimages from subfolders inside ZIP-archive, \nwill be added as well \n\nenclosed archives WILL NOT BE PROCESSED \n\nthis is a tool to upload multiple [Big]images, \nmake self-resize of each while uploading \nand created [Small] resizes at one step \n\nPS: maximum page execution time is $max_execution_time sec";
		$msg_bo_img_upload_url_tip = "to upload multiple images, state in this field \nall URLs of every image separated with [space] \n\nthis is a tool to upload multiple [Big]images, \nmake self-resize of each while uploading \nand created [Small] resizes at one step \n\nPS: maximum page execution time is $max_execution_time sec";

		$msg_bo_img_label_equal_filename = "= file name";
		$msg_bo_img_label_equal_filename_tip = "fill image labels \nwith file names except extension \nfor Russian file names \nimage labels will be saved in windows-1251 codepage, \nbefore TRANSLIT convertation \n\ncheckbox works only while uploading";
		$msg_bo_img_file_deleted = "...file deleted";
		$msg_bo_img_directory_deleted = "...folder deleted";

		$msg_bo_img_file_size_bytes = "b";
		$msg_bo_img_file_lost = "(lost)";

		$msg_bo_img_label_big = " big";
		$msg_bo_img_label_small = " small";

		$msg_bo_img_popuphref_zoom_unable = "Unfortunately, this image could not be enlarged";

		$msg_bo_img_autoresize_element_has = "autoresize: element";
		$msg_bo_img_autoresize_element_has_no_big_uploaded = "has no [Big] image uploaded";
		$msg_bo_img_autoresize_element_has_no_resize_apply_checked = "box [Autoresize apply] is not checked";
		$msg_bo_img_autoresize_element_has_no_big_uploaded_or_HW_zero = "has no [Big] image uploaded or both Width\Height parameters are ZERO";
			// /_image_layer.php
			

			// /_input_control.php
		$msg_bo_select_ctrl_shift = "Use Shift и Ctrl keys<br>for selection";
		$msg_bo_field_obligatory = "this field is mandatory";
			// /_input_control.php

			// /_input_types.php
		$msg_bo_depend_of_that_group = "of this group";
		$msg_bo_linked_elements = "Linked elements";
		$msg_bo_file_delete_existing = "delete existing";
		$msg_bo_img_popup = "preview";
		$msg_bo_img_not_uploaded = "[no image uploaded]";
		$msg_bo_notification_send = "send a notification";
			// /_input_types.php


			// _link_types.php
		$msg_bo_depend_of_that_group = "is this group";
		$msg_bo_field = "Field";
		$msg_bo_edit = "edit";
		$msg_bo_empty = "[empy]";
		$msg_bo_link_delete = "[delete the link]";
		$msg_bo_selector_every = " - all - ";
		$msg_bo_o2m_link_absent = " - no link - ";
		$msg_bo_o2m_link_delete = " - delete the link - ";
		$msg_bo_fyu = "For Your Information";
			// /_link_types.php


			// _list.php
		$msg_bo_depend_of_that_group = "of this group";
		$msg_bo_delete = "Delete";
		$msg_bo_delete_all = "all";
		$msg_bo_delete_all_tip = "tick refers only to the button DELETE";
		$msg_bo_delete_unable = "unable to delete";
		$msg_bo_depend_of_that_group = "of this group";
		$msg_bo_faceopen = "open in FrontEnd";
			// /_list.php


			// _mysql.php
		$msg_pager_disabled = "pager disabled: too much to calculate, may hang on...";
		$msg_pager_open = "open";
		$msg_pager_page = "page";
		$msg_pager_nth = "-th";
		$msg_pager_from = "of";
		$msg_pager_previous = "previous";
		$msg_pager_next = "next";
		$msg_pager_all = "all";		
		$msg_bo_cant_be_parent_of_youself = "You can not select item as its parent";
			// /_mysql.php


			// _sendmail.php
		$msg_sendmail_client_noaddress_notsent = "Client has empty email, message is not sent";
		$msg_sendmail_error_sending_bylist = "error sending message by these addresses:";
		$msg_sendmail_error_emptylist = "Recipient list is empty";
		$msg_sendmail_mtpl = "message template";
		$msg_sendmail_mtpl_notfound_notsent = "not found; letter was not sent";
		$msg_sendmail_sentto = "Message sent to";
		$msg_sendmail_error_sendingto = "ERROR sending message to";
		$msg_sendmail_sent_byaddress = "Send by address:";
			// /_sendmail.php

			// _submenu.php
		$msg_submenu_search = "Search";
		$msg_submenu_find = "Find";
		$msg_submenu_all = "all";		
		$msg_submenu_shown = "shown:";
		$msg_submenu_shown_from = "of";
			// /_submenu.php

			// _updown.php
		$msg_direction_up = "up";
		$msg_direction_down = "down";
		$msg_bo_updown_element = "Element";
		$msg_bo_updown_element_moved = "moved";
		$msg_bo_updown_element_move_unable = "unable to move";
			// /_updown.php

			// _init.php
		$msg_bo_jsv_checkbox_not_checked = "Checkbox is not checked ";
		$msg_bo_jsv_fieldcheck_failed = "Field is not filled correctly ";
			// /_init.php

		$list_empty_msg = "<b>No data</b>";


			// mtpl-popup
		$msg_check_popup = "Check";

			// face
		$msg_no_picture = "no picture";
		$msg_other_pictures = "more pictures";
		$msg_picture_absent = "image is missing";
		$msg_details = "details";
			// /face


		break;

//	case "fr";
//		break;

}


// формируем "Группа клиентов" из "Новая группа клиентов"
$entity_list_single = array ();
foreach ($new_entity_ident_list as $new_entity_name => $new_entity_txt) {
	$new_entity_txt = preg_replace("~^\S+\s+~", "", $new_entity_txt);
	$new_entity_txt = ucfirst($new_entity_txt);
	$entity_list_single[$new_entity_name] = $new_entity_txt;
}
//pre($entity_list_single);


// формируем "группу клиентов" из "новую группу клиентов"
$entity_list_single_savebutton = array ();
foreach ($add_entity_msg_list as $add_entity_name => $add_entity_txt) {
	$add_entity_txt = preg_replace("~^\S+\s+~", "", $add_entity_txt);
	$add_entity_txt = ucfirst($add_entity_txt);
	$entity_list_single_savebutton[$add_entity_name] = $add_entity_txt;
}
//pre($entity_list_single_savebutton);


foreach ($menu_bo as $key => $value) {
	if ($menu_bo[$key] == "" && isset($entity_list[$key])) $menu_bo[$key] = $entity_list[$key];
}

//pre($menu_bo);

$lang_hashkey = array (
	"en" => "English",
	"fr" => "Francais",
	"ru" => "Русский",
	);

$lang_content_type_charset_hash = array (
	"en" => "",
	"fr" => "",
	"ru" => "windows-1251",
	);


//$debug_lang = 1;
if ($debug_lang == 1) {
	pre("lang_current = " . pr($lang_current));
	pre("entity_list = " . pr($entity_list));
}


/*	// generated automatically
		$entity_list_single = array (
			"cgroup" => "Группа клиентов",
			"customer" => "Клиент",
			"corder" => "Заказ",
		
			"product" => "Продукт",
			"pgroup" => "Группа продуктов",
			"supplier" => "Производитель",
			"pmodel" => "Модель",
			"currency" => "Валюта",
		
			"m2m_product_rating" => "Рейтинг,отзыв,пожел",
			"m2m_product_replic" => "Реплика",
		
			"news" => "Новость",
			"ngroup" => "Новостная лента",
		
			"faq" => "Вопрос",
			"fgroup" => "Группа вопрос-ответ",
		
			"banner" => "Баннер",
			"bgroup" => "Группа баннеров",
		
			"constant" => "Константа",
			"cached" => "Кэш",
			"mtpl" => "Шаблон письма",
			"sentlog" => "Отправленные",
			"imgtype" => "Тип картинок",
			"img" => "Картинка",
			"jsvalidator" => "Проверка ввода",
		
			"icwhose" => "Анкета",
			"ic" => "Вопрос в анкете",
			"icdict" => "Справочник",
			"icdictcontent" => "Значение справочника",
			"ictype" => "Тип полей ввода",
			"icsheet" => "Заполненная анкеты",
		
			"mmenu" => "Пункт меню",
		);


*/		

/*
$add_entity_msg_list = array (
	"pgroup" => "new category",
	"product" => "new product",
	"supplier" => "new manufacturer",
	"pmodel" => "new model",

	"comptn" => "новую комплектацию",
	"ogroup" => "новую группу опций",
//	"caroption" => "новую опцию",
	"ppgoption" => "new option",
	"brand" => "нового производителя",
	"color" => "new color",
	"colortone" => "новый оттенок",
	"surface" => "новый тип покрытия",
	"kuz" => "новый тип кузов",
	"gearbox" => "новый тип коробки",
	"engine" => "новый тип двигателя",
	"gearbox" => "новый тип коробки",
	"salon" => "новую обивку салона",
	"currency" => "new currency",
	"metrics" => "new measure of length",
	"news" => "новость",
	"banner" => "new banner",
	"bgroup" => "new banner group",

	"constant" => "new constant value",
	"mtpl" => "new mail template",
	"imgtype" => "new image type",
	"img" => "new image",
	"jsvalidator" => "new JSValidator",

	"icwhose" => "новая анкета",
	"ic" => "новый вопрос в анкете",
	"icdict" => "новый справочник",
	"icdictcontent" => "новое значение справочника",
	"ictype" => "новый тип полей ввода",

	"mmenu" => "new document",
);


$new_entity_ident_list = array (
	"pgroup" => "new category",
	"product" => "new product",
	"supplier" => "new manufacturer",
	"pmodel" => "new model",

	"comptn" => "новая комплектация",
	"ogroup" => "новая группа опций",
//	"caroption" => "новая опция",
	"ppgoption" => "new option",
	"brand" => "новый производитель",
	"color" => "new color",
	"colortone" => "новый оттенок",
	"surface" => "новый тип покрытия",
	"kuz" => "новый тип кузов",
	"gearbox" => "новый тип коробки",
	"engine" => "новый тип двигателя",
	"gearbox" => "новый тип коробки",
	"salon" => "новая обивка салона",
	"currency" => "new currency",
	"metrics" => "new measure of length",
	"news" => "новость",
	"banner" => "new banner",
	"bgroup" => "new banner group",

	"constant" => "new constant value",
	"mtpl" => "new mail template",
	"imgtype" => "new image type",
	"jsvalidator" => "новый JSValidator",

	"icwhose" => "новая анкета",
	"ic" => "новый вопрос в анкете",
	"icdict" => "новый справочник",
	"icdictcontent" => "новое значение справочника",
	"ictype" => "новый тип полей ввода",

	"mmenu" => "new document",
);
*/
/*			$matches = array();
			preg_match("~^\S+\s+(.*)~", $new_entity_txt, $matches);
			pre($matches);
			if (isset($matches[1])) {
				$new_entity_txt = $matches[1];
			}
*/



?>