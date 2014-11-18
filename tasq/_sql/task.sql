use tasq;
#set names cp1251;

DROP TABLE IF EXISTS tasq_task;
CREATE TABLE tasq_task (
	id					INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	date_updated		TIMESTAMP,
	date_created		TIMESTAMP,
	date_published		TIMESTAMP,
	published			TINYINT UNSIGNED NOT NULL DEFAULT 1,
	deleted				TINYINT UNSIGNED NOT NULL DEFAULT 0,
	manorder			INTEGER UNSIGNED NOT NULL DEFAULT 0,
	ident				VARCHAR(250) NOT NULL DEFAULT '',

	article				VARCHAR(250) NOT NULL DEFAULT '',

	project				INTEGER UNSIGNED NOT NULL DEFAULT 0,
#	pmodel				INTEGER UNSIGNED NOT NULL DEFAULT 0,
	tgroup				INTEGER UNSIGNED NOT NULL DEFAULT 0,

	mayorder			TINYINT UNSIGNED NOT NULL DEFAULT 1,

	price_1				FLOAT UNSIGNED NOT NULL DEFAULT 0,
	currency_1			INTEGER UNSIGNED NOT NULL DEFAULT 1,
	pricecomment_1		VARCHAR(250) NOT NULL DEFAULT '',
	efforts_1			FLOAT UNSIGNED NOT NULL DEFAULT 0,

	price_2				FLOAT UNSIGNED NOT NULL DEFAULT 0,
	currency_2			INTEGER UNSIGNED NOT NULL DEFAULT 0,
	pricecomment_2		VARCHAR(250) NOT NULL DEFAULT '',
	efforts_2			FLOAT UNSIGNED NOT NULL DEFAULT 0,

	price_3				FLOAT UNSIGNED NOT NULL DEFAULT 0,
	currency_3			INTEGER UNSIGNED NOT NULL DEFAULT 0,
	pricecomment_3		VARCHAR(250) NOT NULL DEFAULT '',
	efforts_3			FLOAT UNSIGNED NOT NULL DEFAULT 0,

#	price_buy1			FLOAT UNSIGNED NOT NULL DEFAULT 0,
#	currency_buy1		INTEGER UNSIGNED NOT NULL DEFAULT 0,
#	pricecomment_buy1	VARCHAR(250) NOT NULL DEFAULT '',

	request				TEXT,
	request_no_freetext	TINYINT UNSIGNED NOT NULL DEFAULT 0,
	discussion			TEXT,
	discussion_no_freetext	TINYINT UNSIGNED NOT NULL DEFAULT 0,
	response			TEXT,
	response_no_freetext	TINYINT UNSIGNED NOT NULL DEFAULT 0,
	
	
#	is_new				TINYINT NOT NULL DEFAULT 0,
#	archived			TINYINT NOT NULL DEFAULT 0,

#	i_published			TINYINT NOT NULL DEFAULT 1,
	i_manorder			INTEGER UNSIGNED NOT NULL DEFAULT 0,

#	pagetitle			VARCHAR(250) NOT NULL DEFAULT '',
#	title				VARCHAR(250) NOT NULL DEFAULT '',
#	meta_keywords		VARCHAR(250) NOT NULL DEFAULT '',
#	meta_description	VARCHAR(250) NOT NULL DEFAULT '',

	PRIMARY KEY(id)
#	, key (pmodel), key (project, pmodel)
	, key (project), key (tgroup)
	, key (published), key (deleted)
	, key(ident)
#	, key(pagetitle), key(title), key(meta_keywords), key(meta_description)
#	, key(brief)
);



#desc tasq_task;

insert into tasq_task (id, manorder, i_manorder, ident, tgroup, project, article, request, discussion, date_created) values
	(1, 1, 1, 'добавить четыре текстовых редактируемых поля в карточку клиента', 21, 1, 'прод_1', 'краткое описание продукта №1 краткое описание продукта №1 краткое описание продукта №1', '<p>полное описание продукта №1 полное описание продукта №1 полное описание продукта №1 полное описание продукта №1</p> <ul><li>полное описание, тезис номер 1</li><li>полное описание, тезис номер 2</li><li>полное описание, тезис номер 3</li></ul><h4>полное описание</h4><p>полное описание продукта №1 полное описание продукта №1 полное описание продукта №1 полное описание продукта №1 полное описание продукта №1 полное описание продукта №1 полное описание продукта №1 полное описание продукта №1</p>', CURRENT_TIMESTAMP),
	(2, 2, 2, 'добавить вычисление значений полей из оказанных услуг', 21, 1, 'прод_2', 'краткое описание продукта №2 краткое описание продукта №2 краткое описание продукта №2', '<p>полное описание продукта №2 полное описание продукта №2 полное описание продукта №2 полное описание продукта №2</p> <ul><li>полное описание, тезис номер 1</li><li>полное описание, тезис номер 2</li><li>полное описание, тезис номер 3</li></ul><h4>полное описание</h4><p>полное описание продукта №2 полное описание продукта №2 полное описание продукта №2 полное описание продукта №2 полное описание продукта №2 полное описание продукта №2 полное описание продукта №2 полное описание продукта №2</p>', CURRENT_TIMESTAMP),
	(3, 3, 3, 'исправить глюк 1', 21, 1, 'прод_3', 'краткое описание продукта №3 краткое описание продукта №3 краткое описание продукта №3 ', '<p>полное описание продукта №3 полное описание продукта №3 полное описание продукта №3 полное описание продукта №3</p> <ul><li>полное описание, тезис номер 1</li><li>полное описание, тезис номер 2</li><li>полное описание, тезис номер 3</li></ul><h4>полное описание</h4><p>полное описание продукта №3 полное описание продукта №3 полное описание продукта №3 полное описание продукта №3 полное описание продукта №3 полное описание продукта №3 полное описание продукта №3 полное описание продукта №3</p>', CURRENT_TIMESTAMP),
	(4, 4, 4, 'ой я забыл дорисовать 2', 21, 1, 'прод_4', 'краткое описание продукта №4 краткое описание продукта №4 краткое описание продукта №4', '<p>полное описание продукта №4 полное описание продукта №4 полное описание продукта №4 полное описание продукта №4</p> <ul><li>полное описание, тезис номер 1</li><li>полное описание, тезис номер 2</li><li>полное описание, тезис номер 3</li></ul><h4>полное описание</h4><p>полное описание продукта №4 полное описание продукта №4 полное описание продукта №4 полное описание продукта №4 полное описание продукта №4 полное описание продукта №4 полное описание продукта №4 полное описание продукта №4</p>', CURRENT_TIMESTAMP);

; 

#insert ignore into tasq_m2m_task_tgroup(task, tgroup) select id, tgroup from tasq_task;
#update tasq_m2m_task_tgroup set date_created=CURRENT_TIMESTAMP, date_published=CURRENT_TIMESTAMP, manorder=id;

update tasq_task set i_manorder=manorder;


#select * from tasq_task;

#alter table tasq_task add img_award VARCHAR(250) NOT NULL DEFAULT '';
#alter table tasq_task add features 	TEXT;
#alter table tasq_task add bundling 	TEXT;
