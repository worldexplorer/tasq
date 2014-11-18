<?

require_once "../_lib/_init.php";

$hashkey_adding = ($id == 0) ? get_string("hashkey") : "";

$entity_fields = array (
	"ident" => array ("Название", "textfield", ""),
	"content" => array ("Комментарий", "textarea", ""),
	"hashkey" => array ("Ключ", "textfield", $hashkey_adding),
	"imglimit" => array ("Кол-во картинок", "number", "0", "лимит на кол-во заливаемых картинок; 0 - не ограничено"),

	"~52" => array ("&nbsp;", "ahref", "&nbsp;"),

	"~1_open" => array ("Маленькая картинка в бэкоффисе", "layer_open"),
	"img_present" => array ("Есть маленькая", "checkbox", 1, "выводится ли Маленькая->[Browse]"),
	"img_newqnty" => array ("Количество кнопок Обзор (Browse)", "number", 1, "если хочется заливать несколько новых сразу; 0=1"),
	"img_zip_present" => array ("Есть ли поле ZIP", "checkbox", 1, "выводится ли Маленькая->[Browse] ZIP для [Новой]"),
	"img_url_present" => array ("Есть ли поле URL", "checkbox", 1, "выводится ли Маленькая->URL для залитых и [Новой]"),
	"img_txt_present" => array ("Есть подпись", "checkbox", 1, "имеется ли поле для подписи Маленькой"),
	"img_txt_eq_fname" => array ("Галочка [подпись = имя файла]", "checkbox", 0, "чекнуто ли по умолчанию [подпись = имя файла] для новых"),

	"resize_published" => array ("Создать [маленькую] из [большой]", "checkbox", 1, "выводится ли сама фича у пользователя"),
	"resize_default_checked" => array ("Состояние галочки [создать]", "checkbox", 0, "чекнуто ли по умолчанию [создать ресайз] для новых"),
	"resize_default_qlty" => array ("Качество ресайза [маленькой]", "number", "85", "качество сохранения JPEG: 0&#8230;100"),
	"resize_default_width" => array ("Ширина ресайза [маленькой]", "number", ""),
	"resize_default_height" => array ("Высота ресайза [маленькой]", "number", "80"),
	"~1_close" => array ("Маленькая картинка в бэкоффисе", "layer_close"),

	"~2_open" => array ("Большая картинка в бэкоффисе", "layer_open"),
	"img_big_present" => array ("Есть большая", "checkbox", 1, "выводится ли Большая->[Browse]"),
	"img_big_newqnty" => array ("Количество кнопок Обзор (Browse)", "number", 1, "если хочется заливать несколько новых сразу; 0=1"),
	"img_big_zip_present" => array ("Есть ли поле ZIP", "checkbox", 1, "выводится ли Большая->[Browse] ZIP для [Новой]"),
	"img_big_url_present" => array ("Есть ли поле URL", "checkbox", 1, "выводится ли Большая->URL для залитых и [Новой]"),
	"img_big_txt_present" => array ("Есть подпись", "checkbox", 1, "имеется ли поле для подписи Большой"),
	"img_big_txt_eq_fname" => array ("Галочка [подпись = имя файла]", "checkbox", 0, "чекнуто ли по умолчанию [подпись = имя файла] для новых"),

	"big_resize_published" => array ("Масштабировать [большую] при закачке", "checkbox", 1, "выводится ли сама фича у пользователя"),
	"big_resize_default_checked" => array ("Состояние галочки [масштабировать]", "checkbox", 0, "чекнуто ли по умолчанию [создать ресайз] для новых"),
	"big_resize_default_qlty" => array ("Качество ресайза [большой]", "number", "85", "качество сохранения JPEG: 0&#8230;100"),
	"big_resize_default_width" => array ("Ширина ресайза [большой]", "number", ""),
	"big_resize_default_height" => array ("Высота ресайза [большой]", "number", ""),
	"~2_close" => array ("Большая картинка в бэкоффисе", "layer_close"),

	"~6_open" => array ("Превьюшка в бэкоффисе", "layer_open"),
	"img_thumb_present" => array ("Есть превьюшка", "checkbox", 1, "выводится ли справа от формы уменьшенная превьюшка"),
	"img_thumb_qlty" => array ("Качество ресайза [превьюшки]", "number", "85", "качество сохранения JPEG: 0&#8230;100"),
	"img_thumb_width" => array ("Ширина ресайза [превьюшки]", "number", ""),
	"img_thumb_height" => array ("Высота ресайза [превьюшки]", "number", "80"),
	"~6_close" => array ("Превьюшка в бэкоффисе", "layer_close"),

	"~51" => array ("&nbsp;", "ahref", "&nbsp;"),

	"~4_open" => array ("Авторесайз первой (например в список товаров группы)", "layer_open"),
	"first_autoresize_qlty" => array ("Качество авторесайза", "number", "80", "качество сохранения JPEG: 0&#8230;100"),
	"first_autoresize_width" => array ("Ширина авторесайза", "number", ""),
	"first_autoresize_height" => array ("Высота авторесайза", "number", ""),
//	"first_autoresize_firstonly" => array ("Авторесайз только первой", "checkbox", 1, "остальные авторесайзы будут стёрты"),
	"first_autoresize_apply" => array ("Применять авторесайз", "checkbox", 0, "создаются ли авторесайзы при обращении"),
	"~41" => array ("&nbsp;", "ahref"),
	"first_merge_img" => array ("Ватермарк", "image", 0, "создаются ли авторесайзы при обращении"),
	"first_merge_alfa" => array ("Прозрачность", "number", 30, "прозрачность накладываемой сверху картинки: 0&#8230;100"),
//	"first_merge_type" => array ("Метод наложения", "number", 0, "0=пропорционайльный ресайз; 1=tile"),
	"first_merge_apply" => array ("Накладывать ватермарк", "checkbox", 1, "можно удалить файл-ватермарк, а можно убрать эту галку"),
	"first_autoresize_debug" => array ("Выводить отладку", "checkbox", 0, "надписи с лица об: авторесайзе, чистке от старья, ватермарках"),
	"first_autoresize_tpl_ex" => array ("Шаблон для<br>существующей", "textarea_3"),
	"first_autoresize_tpl_nex" => array ("Шаблон для<br>несуществующей", "textarea_3"),
	"~4_close" => array ("Авторесайз первой (например в список товаров группы)", "layer_close"),

	"~3_open" => array ("Авторесайз каждой (например превьюшки в карточку товара)", "layer_open"),
	"every_autoresize_qlty" => array ("Качество авторесайза", "number", "80", "качество сохранения JPEG: 0&#8230;100"),
	"every_autoresize_width" => array ("Ширина авторесайза", "number", ""),
	"every_autoresize_height" => array ("Высота авторесайза", "number", ""),
//	"every_autoresize_firstonly" => array ("Авторесайз только первой", "checkbox", 1, "остальные авторесайзы будут стёрты"),
	"every_autoresize_apply" => array ("Применять авторесайз", "checkbox", 0, "создаются ли авторесайзы при обращении"),
	"every_merge_img" => array ("Ватермарк", "image", 0, "создаются ли авторесайзы при обращении"),
	"every_merge_alfa" => array ("Прозрачность", "number", 30, "прозрачность накладываемой сверху картинки: 0&#8230;100"),
//	"every_merge_type" => array ("Метод наложения", "number", 0, "0=пропорционайльный ресайз; 1=tile"),
	"every_merge_apply" => array ("Накладывать ватермарк", "checkbox", 1, "можно удалить файл-ватермарк, а можно убрать эту галку"),
	"every_autoresize_debug" => array ("Выводить отладку", "checkbox", 0, "надписи с лица об: авторесайзе, чистке от старья, ватермарках"),
	"every_autoresize_tpl_ex" => array ("Шаблон для<br>существующей", "textarea_3"),
	"every_autoresize_tpl_nex" => array ("Шаблон для<br>несуществующей", "textarea_3"),
	"~3_close" => array ("Авторесайз каждой (например превьюшки в карточку товара)", "layer_close"),

	"~53" => array ("&nbsp;", "ahref", "&nbsp;"),

	"~42_open" => array ("Авторесайз2 первой (например в список товаров группы)", "layer_open"),
	"first2_autoresize_qlty" => array ("Качество авторесайза2", "number", "80", "качество сохранения JPEG: 0&#8230;100"),
	"first2_autoresize_width" => array ("Ширина авторесайза2", "number", ""),
	"first2_autoresize_height" => array ("Высота авторесайза2", "number", ""),
//	"first2_autoresize_firstonly" => array ("Авторесайз2 только первой", "checkbox", 1, "остальные авторесайзы будут стёрты"),
	"first2_autoresize_apply" => array ("Применять авторесайз2", "checkbox", 0, "создаются ли авторесайзы при обращении"),
	"first2_merge_img" => array ("Ватермарк", "image", 0, "создаются ли авторесайзы при обращении"),
	"first2_merge_alfa" => array ("Прозрачность", "number", 30, "прозрачность накладываемой сверху картинки: 0&#8230;100"),
//	"first2_merge_type" => array ("Метод наложения", "number", 0, "0=пропорционайльный ресайз; 1=tile"),
	"first2_merge_apply" => array ("Накладывать ватермарк", "checkbox", 1, "можно удалить файл-ватермарк, а можно убрать эту галку"),
	"first2_autoresize_debug" => array ("Выводить отладку2", "checkbox", 0, "надписи с лица об: авторесайзе, чистке от старья, ватермарках"),
	"first2_autoresize_tpl_ex" => array ("Шаблон для<br>существующей", "textarea_3"),
	"first2_autoresize_tpl_nex" => array ("Шаблон для<br>несуществующей", "textarea_3"),
	"~42_close" => array ("Авторесайз2 первой (например в список товаров группы)", "layer_close"),

	"~32_open" => array ("Авторесайз2 каждой (например превьюшки в карточку товара)", "layer_open"),
	"every2_autoresize_qlty" => array ("Качество авторесайза2", "number", "80", "качество сохранения JPEG: 0&#8230;100"),
	"every2_autoresize_width" => array ("Ширина авторесайза2", "number", ""),
	"every2_autoresize_height" => array ("Высота авторесайза2", "number", ""),
//	"every2_autoresize_firstonly" => array ("Авторесайз2 только первой", "checkbox", 1, "остальные авторесайзы будут стёрты"),
	"every2_autoresize_apply" => array ("Применять авторесайз2", "checkbox", 0, "создаются ли авторесайзы при обращении"),
	"every2_merge_img" => array ("Ватермарк", "image", 0, "создаются ли авторесайзы при обращении"),
	"every2_merge_alfa" => array ("Прозрачность", "number", 30, "прозрачность накладываемой сверху картинки: 0&#8230;100"),
//	"every2_merge_type" => array ("Метод наложения", "number", 0, "0=пропорционайльный ресайз; 1=tile"),
	"every2_merge_apply" => array ("Накладывать ватермарк", "checkbox", 1, "можно удалить файл-ватермарк, а можно убрать эту галку"),
	"every2_autoresize_debug" => array ("Выводить отладку2", "checkbox", 0, "надписи с лица об: авторесайзе, чистке от старья, ватермарках"),
	"every2_autoresize_tpl_ex" => array ("Шаблон для<br>существующей", "textarea_3"),
	"every2_autoresize_tpl_nex" => array ("Шаблон для<br>несуществующей", "textarea_3"),
	"~32_close" => array ("Авторесайз2 каждой (например превьюшки в карточку товара)", "layer_close"),

	"~54" => array ("&nbsp;", "ahref", "&nbsp;"),


	"~52_open" => array ("Авторесайз3 первой (например в список товаров группы)", "layer_open"),
	"first3_autoresize_qlty" => array ("Качество авторесайза3", "number", "80", "качество сохранения JPEG: 0&#8230;100"),
	"first3_autoresize_width" => array ("Ширина авторесайза3", "number", ""),
	"first3_autoresize_height" => array ("Высота авторесайза3", "number", ""),
//	"first3_autoresize_firstonly" => array ("Авторесайз3 только первой", "checkbox", 1, "остальные авторесайзы будут стёрты"),
	"first3_autoresize_apply" => array ("Применять авторесайз3", "checkbox", 0, "создаются ли авторесайзы при обращении"),
	"first3_merge_img" => array ("Ватермарк", "image", 0, "создаются ли авторесайзы при обращении"),
	"first3_merge_alfa" => array ("Прозрачность", "number", 30, "прозрачность накладываемой сверху картинки: 0&#8230;100"),
//	"first3_merge_type" => array ("Метод наложения", "number", 0, "0=пропорционайльный ресайз; 1=tile"),
	"first3_merge_apply" => array ("Накладывать ватермарк", "checkbox", 1, "можно удалить файл-ватермарк, а можно убрать эту галку"),
	"first3_autoresize_debug" => array ("Выводить отладку3", "checkbox", 0, "надписи с лица об: авторесайзе, чистке от старья, ватермарках"),
	"first3_autoresize_tpl_ex" => array ("Шаблон для<br>существующей", "textarea_3"),
	"first3_autoresize_tpl_nex" => array ("Шаблон для<br>несуществующей", "textarea_3"),
	"~52_close" => array ("Авторесайз3 первой (например в список товаров группы)", "layer_close"),

	"~62_open" => array ("Авторесайз3 каждой (например превьюшки в карточку товара)", "layer_open"),
	"every3_autoresize_qlty" => array ("Качество авторесайза3", "number", "80", "качество сохранения JPEG: 0&#8230;100"),
	"every3_autoresize_width" => array ("Ширина авторесайза3", "number", ""),
	"every3_autoresize_height" => array ("Высота авторесайза3", "number", ""),
//	"every3_autoresize_firstonly" => array ("Авторесайз3 только первой", "checkbox", 1, "остальные авторесайзы будут стёрты"),
	"every3_autoresize_apply" => array ("Применять авторесайз3", "checkbox", 0, "создаются ли авторесайзы при обращении"),
	"every3_merge_img" => array ("Ватермарк", "image", 0, "создаются ли авторесайзы при обращении"),
	"every3_merge_alfa" => array ("Прозрачность", "number", 30, "прозрачность накладываемой сверху картинки: 0&#8230;100"),
//	"every3_merge_type" => array ("Метод наложения", "number", 0, "0=пропорционайльный ресайз; 1=tile"),
	"every3_merge_apply" => array ("Накладывать ватермарк", "checkbox", 1, "можно удалить файл-ватермарк, а можно убрать эту галку"),
	"every3_autoresize_debug" => array ("Выводить отладку3", "checkbox", 0, "надписи с лица об: авторесайзе, чистке от старья, ватермарках"),
	"every3_autoresize_tpl_ex" => array ("Шаблон для<br>существующей", "textarea_3"),
	"every3_autoresize_tpl_nex" => array ("Шаблон для<br>несуществующей", "textarea_3"),
	"~62_close" => array ("Авторесайз3 каждой (например превьюшки в карточку товара)", "layer_close"),

	"~64" => array ("&nbsp;", "ahref", "&nbsp;"),
	
	
	
	"~72_open" => array ("Авторесайз4 первой (например в список товаров группы)", "layer_open"),
	"first4_autoresize_qlty" => array ("Качество авторесайза4", "number", "80", "качество сохранения JPEG: 0&#8230;100"),
	"first4_autoresize_width" => array ("Ширина авторесайза4", "number", ""),
	"first4_autoresize_height" => array ("Высота авторесайза4", "number", ""),
//	"first4_autoresize_firstonly" => array ("Авторесайз4 только первой", "checkbox", 1, "остальные авторесайзы будут стёрты"),
	"first4_autoresize_apply" => array ("Применять авторесайз4", "checkbox", 0, "создаются ли авторесайзы при обращении"),
	"first4_merge_img" => array ("Ватермарк", "image", 0, "создаются ли авторесайзы при обращении"),
	"first4_merge_alfa" => array ("Прозрачность", "number", 30, "прозрачность накладываемой сверху картинки: 0&#8230;100"),
//	"first4_merge_type" => array ("Метод наложения", "number", 0, "0=пропорционайльный ресайз; 1=tile"),
	"first4_merge_apply" => array ("Накладывать ватермарк", "checkbox", 1, "можно удалить файл-ватермарк, а можно убрать эту галку"),
	"first4_autoresize_debug" => array ("Выводить отладку4", "checkbox", 0, "надписи с лица об: авторесайзе, чистке от старья, ватермарках"),
	"first4_autoresize_tpl_ex" => array ("Шаблон для<br>существующей", "textarea_3"),
	"first4_autoresize_tpl_nex" => array ("Шаблон для<br>несуществующей", "textarea_3"),
	"~72_close" => array ("Авторесайз4 первой (например в список товаров группы)", "layer_close"),

	"~82_open" => array ("Авторесайз4 каждой (например превьюшки в карточку товара)", "layer_open"),
	"every4_autoresize_qlty" => array ("Качество авторесайза4", "number", "80", "качество сохранения JPEG: 0&#8230;100"),
	"every4_autoresize_width" => array ("Ширина авторесайза4", "number", ""),
	"every4_autoresize_height" => array ("Высота авторесайза4", "number", ""),
//	"every4_autoresize_firstonly" => array ("Авторесайз4 только первой", "checkbox", 1, "остальные авторесайзы будут стёрты"),
	"every4_autoresize_apply" => array ("Применять авторесайз4", "checkbox", 0, "создаются ли авторесайзы при обращении"),
	"every4_merge_img" => array ("Ватермарк", "image", 0, "создаются ли авторесайзы при обращении"),
	"every4_merge_alfa" => array ("Прозрачность", "number", 30, "прозрачность накладываемой сверху картинки: 0&#8230;100"),
//	"every4_merge_type" => array ("Метод наложения", "number", 0, "0=пропорционайльный ресайз; 1=tile"),
	"every4_merge_apply" => array ("Накладывать ватермарк", "checkbox", 1, "можно удалить файл-ватермарк, а можно убрать эту галку"),
	"every4_autoresize_debug" => array ("Выводить отладку4", "checkbox", 0, "надписи с лица об: авторесайзе, чистке от старья, ватермарках"),
	"every4_autoresize_tpl_ex" => array ("Шаблон для<br>существующей", "textarea_3"),
	"every4_autoresize_tpl_nex" => array ("Шаблон для<br>несуществующей", "textarea_3"),
	"~82_close" => array ("Авторесайз4 каждой (например превьюшки в карточку товара)", "layer_close"),

	"~84" => array ("&nbsp;", "ahref", "&nbsp;"),




	"~9_open" => array ("Надписи в бэкоффисе", "layer_open"),
	"msg_ident" => array ("Надпись [Картинка]", "textfield", "Картинка"),
	"msg_add" => array ("Надпись [Новая картинка]", "textfield", "Новая картинка"),
	"msg_change" => array ("Надпись [изменить картинку]", "textfield", "изменить картинку"),
	"msg_img" => array ("Надпись [маленькая]", "textfield", "маленькая"),
	"msg_img_big" => array ("Надпись [большая]", "textfield", "большая"),
	"~9_close" => array ("Надписи в бэкоффисе", "layer_close"),

	"~91" => array ("&nbsp;", "ahref", "&nbsp;"),

	"img_table" => array ("Таблица с картинками<br>img если пусто", "textfield", ""),
	"merge_seed" => array ("Merge Seed", "number", "", "используется для ватермаркнутой картинки"),
	"date_updated" => array ("Обновлено", "timestampro", "", "авторесайзы старее этой даты – будут перегенерированы"),
//	"date_created" => array ("Cоздание", "timestampro", ""),
//	"published" => array ("Опубликовано", "checkbox", 1)
);

jsv_addvalidation("JSV_TF_CHAR", "ident", "Название");
jsv_addvalidation("JSV_TF_CHAR", "hashkey", "Ключ");

?>

<? require "_entity_edit.php" ?>
<? require_once "_top.php" ?>
<? require "_edit_fields.php" ?>
<? require_once "_bottom.php" ?>
