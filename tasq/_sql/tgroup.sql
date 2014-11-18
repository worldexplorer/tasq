use tasq;
#set names cp1251;

DROP TABLE IF EXISTS tasq_tgroup;
CREATE TABLE tasq_tgroup (
	id					INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	date_updated		TIMESTAMP,
	date_created		TIMESTAMP,
	date_published		TIMESTAMP,
	published			TINYINT UNSIGNED NOT NULL DEFAULT 1,
	deleted				TINYINT UNSIGNED NOT NULL DEFAULT 0,
	manorder			INTEGER UNSIGNED NOT NULL DEFAULT 0,
	ident				VARCHAR(250) NOT NULL DEFAULT '',

	brief				TEXT,
	brief_no_freetext	TINYINT UNSIGNED NOT NULL DEFAULT 0,

	content				TEXT,
	content_no_freetext	TINYINT UNSIGNED NOT NULL DEFAULT 0,

	parent_id			INTEGER UNSIGNED NOT NULL DEFAULT 1,

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

	i_published			TINYINT NOT NULL DEFAULT 0,
	i_manorder			INTEGER UNSIGNED NOT NULL DEFAULT 0,

	PRIMARY KEY(id)
	, INDEX (parent_id)
	, key (published), key (deleted)
	, key(ident)
#	, key(brief)
);

#desc tasq_tgroup;
insert into tasq_tgroup(id, manorder, published, i_published, parent_id, ident) values(1, 1, 0, 0, 0, 'root');
insert into tasq_tgroup(id, manorder, published, i_published, parent_id, ident, brief) values
	(2, 2, 1, 1, 1, 'сохранять данные для vfsglobal', ''),
	(3, 3, 1, 1, 1, 'добавить комплект услуг', ''),
	(4, 4, 1, 1, 1, 'добавить новые статусы в отчёты', ''),
	(5, 5, 1, 1, 1, 'сделать 1', ''),
	(6, 6, 1, 0, 1, 'улучшить 2', ''),
	(7, 7, 1, 0, 1, 'убрать 3', ''),
	(8, 8, 1, 0, 1, 'переработать 4', ''),
	(9, 9, 1, 0, 1, 'заныкать 5', '')
	;

update tasq_tgroup set i_manorder=manorder;
update tasq_tgroup set brief_no_freetext=1;

#update tgroup set id=0 where id=1;
#select * from tasq_tgroup;
