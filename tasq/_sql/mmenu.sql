use tasq;
#set names cp1251;

DROP TABLE IF EXISTS tasq_mmenu;
CREATE TABLE tasq_mmenu (
	id					INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	date_updated		TIMESTAMP,
	date_created		TIMESTAMP,
	date_published		TIMESTAMP,
	published			TINYINT UNSIGNED NOT NULL DEFAULT 1,
	deleted				TINYINT UNSIGNED NOT NULL DEFAULT 0,
	manorder			INTEGER UNSIGNED NOT NULL DEFAULT 0,
	ident				VARCHAR(250) NOT NULL DEFAULT '',
	
	parent_id			INTEGER UNSIGNED NOT NULL DEFAULT 1,
	
	hashkey				VARCHAR(250) NOT NULL DEFAULT '',
	is_heredoc			TINYINT NOT NULL DEFAULT 1,
	is_drone			TINYINT NOT NULL DEFAULT 0,

	annotation			TEXT,
	brief				TEXT,
	brief_no_freetext	TINYINT UNSIGNED NOT NULL DEFAULT 0,

	content				TEXT,
	content_no_freetext	TINYINT UNSIGNED NOT NULL DEFAULT 0,

	img_free			VARCHAR(250) NOT NULL DEFAULT '',
	img_mover			VARCHAR(250) NOT NULL DEFAULT '',

	img_small_free		VARCHAR(250) NOT NULL DEFAULT '',
	img_small_mover		VARCHAR(250) NOT NULL DEFAULT '',
	img_small_current	VARCHAR(250) NOT NULL DEFAULT '',

	img_ctx_left		VARCHAR(250) NOT NULL DEFAULT '',
	img_ctx_right		VARCHAR(250) NOT NULL DEFAULT '',
	img_ctx_top			VARCHAR(250) NOT NULL DEFAULT '',

#	banner_top			INTEGER UNSIGNED NOT NULL DEFAULT 0,
#	banner_sky			INTEGER UNSIGNED NOT NULL DEFAULT 0,
#	left0_right1		TINYINT UNSIGNED NOT NULL DEFAULT 0,

	file1				VARCHAR(250) NOT NULL DEFAULT '',
	file1_comment		TEXT,
	file2				VARCHAR(250) NOT NULL DEFAULT '',
	file2_comment		TEXT,
	file3				VARCHAR(250) NOT NULL DEFAULT '',
	file3_comment		TEXT,
	file4				VARCHAR(250) NOT NULL DEFAULT '',
	file4_comment		TEXT,
	file5				VARCHAR(250) NOT NULL DEFAULT '',
	file5_comment		TEXT,


	pagetitle			TEXT,
	title				TEXT,
	meta_keywords		TEXT,
	meta_description	TEXT,

	tpl_list_item		TEXT,
	tpl_list_wrapper	TEXT,

	published_legend 	TINYINT UNSIGNED NOT NULL DEFAULT 0,
	manorder_legend		INTEGER UNSIGNED NOT NULL DEFAULT 0,

	published_sitemap 	TINYINT UNSIGNED NOT NULL DEFAULT 1,

	PRIMARY KEY(id),
	INDEX (parent_id)
);

#desc tasq_mmenu;


insert into tasq_mmenu(id, manorder, parent_id, ident, published) values(1, 1, 0, 'root', 0);

insert into tasq_mmenu(id, manorder, parent_id, ident, hashkey, published, deleted) values
	(2, 2, 1, 'верхнее меню', 'MMENU_TOP', 0, 0),
	(3, 3, 1, 'левое меню', 'MMENU_LEFT', 0, 1),
	(4, 4, 1, 'правое меню', 'MMENU_RIGHT', 0, 1),
	(5, 5, 1, 'нижнее меню', 'MMENU_BOTTOM', 0, 0),
	(6, 6, 1, 'неочевидные страницы', '', 0, 1),
	(7, 7, 1, 'авторизован', 'MMENU_AUTHORIZED', 1, 1)
	;

insert into tasq_mmenu(id, manorder, parent_id, ident, hashkey, is_heredoc) values
	(10, 10, 2, 'Главная', 'index', 0),
	(11, 11, 2, 'Косметология эстетическая', '', 1),
	(12, 12, 2, 'Эпиляция', '=task.php?tgroup=6', 1),
	(13, 13, 2, 'Мезотерапия', '=task.php?tgroup=7', 1),
	(14, 14, 2, 'Остеопатия', '=task.php?tgroup=8', 1),
	(15, 15, 2, 'Фотоомоложение', '=task.php?tgroup=9', 1)
	;
	
insert into tasq_mmenu(id, manorder, parent_id, ident, annotation, hashkey, is_heredoc) values
	(20, 20, 11, 'Косметическая линия ЯНССЕН', 'Германия', '', 1),
	(21, 21, 11, 'Химические пилинги РЕНОФАЗ', 'Франция', '', 1),
	(22, 22, 11, 'Биодермабразия лица', 'Химический и механический пилинг', '', 1),
	(23, 23, 11, 'Массаж лица и декольте', 'Эксклюзивная процедура', '', 1),
	(24, 24, 11, 'Ультразвуковая чистка лица', 'Безболезненная процедура', '', 1),
	(25, 25, 11, 'Косметические уходы Sothys', 'Франция', '', 1)
	;

insert into tasq_mmenu(id, manorder, parent_id, ident, annotation, hashkey, is_heredoc) values
	(30, 30, 12, 'Фотоэпиляция', '', '', 1)
	;
	
insert into tasq_mmenu(id, manorder, parent_id, ident, hashkey, is_heredoc) values
	(50, 50, 5, 'Контакты', '=shop-list.php', 0),
	(51, 51, 5, 'Обмен ссылками', '', 1),
	(52, 52, 5, 'Прайс-лист', '', 1),
	(53, 53, 5, 'Прием ведет...', '', 1),
	(54, 54, 5, 'Прикрепление на лечение', '', 1)
	;
	

insert into tasq_mmenu(id, manorder, parent_id, ident, hashkey, is_heredoc, published) values
	(91, 91, 6, 'результаты поиска', 'search', 0, 0),
	(94, 94, 6, 'группа новостей', 'ngroup', 0, 0),
	(95, 95, 6, 'разворот новости', 'news', 0, 0)
	;

#select * from tasq_mmenu;


#alter table tasq_mmenu add content_no_freetext TINYINT UNSIGNED NOT NULL DEFAULT 0;