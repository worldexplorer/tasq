use tasq;
#set names cp1251;

DROP TABLE IF EXISTS tasq_ngroup;
CREATE TABLE tasq_ngroup (
	id					INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	date_updated		TIMESTAMP,
	date_created		TIMESTAMP,
	date_published		TIMESTAMP,
	published			TINYINT UNSIGNED NOT NULL DEFAULT 1,
	deleted				TINYINT UNSIGNED NOT NULL DEFAULT 0,
	manorder			INTEGER UNSIGNED NOT NULL DEFAULT 0,
	ident				VARCHAR(250) NOT NULL DEFAULT '',
	brief				TEXT,

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

	pagetitle			VARCHAR(250) NOT NULL DEFAULT '',
	title				VARCHAR(250) NOT NULL DEFAULT '',
	meta_keywords		TEXT,
	meta_description	TEXT,
	banner_top			INTEGER UNSIGNED NOT NULL DEFAULT 0,
	hits				INTEGER UNSIGNED NOT NULL DEFAULT 0,

	PRIMARY KEY(id)
);

#desc tasq_ngroup;
insert into tasq_ngroup(id, manorder, parent_id, ident, published) values(1, 1, 0, 'root', 0);

insert into tasq_ngroup(id, manorder, parent_id, ident, published) values(2, 2, 1, 'Демо-версии', 1);
insert into tasq_ngroup(id, manorder, parent_id, ident, published) values(3, 3, 1, 'Документация как работают фичи', 1);
insert into tasq_ngroup(id, manorder, parent_id, ident, published) values(4, 4, 1, 'Общие замечания и мысли', 1);

#insert into tasq_ngroup(id, ident) values(1, '0');
#update ngroup set id=0 where id=1;
#select * from tasq_ngroup;
