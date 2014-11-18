use tasq;
#set names cp1251;

DROP TABLE IF EXISTS tasq_icwhose;
CREATE TABLE tasq_icwhose (
	id				INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	date_updated	TIMESTAMP(14),
	date_created	TIMESTAMP(14),
	date_published	TIMESTAMP(14),
	published		TINYINT UNSIGNED NOT NULL DEFAULT 1,
	deleted			TINYINT UNSIGNED NOT NULL DEFAULT 0,
	manorder		INTEGER UNSIGNED NOT NULL DEFAULT 0,
	ident			VARCHAR(250) NOT NULL DEFAULT '',

	hashkey			VARCHAR(250) NOT NULL DEFAULT '',

	brief			TEXT,
	jsv_debug		TINYINT UNSIGNED NOT NULL DEFAULT 0,
	bo_only			TINYINT UNSIGNED NOT NULL DEFAULT 0,

	PRIMARY KEY(id)
);

#desc tasq_icwhose;

insert into tasq_icwhose (id, manorder, ident, hashkey) values (1, 1, 'Свойства задачи', 'TASK_PROPERTIES');

#select * from tasq_icwhose;

#alter TABLE szd_icwhose	add brief TEXT;
#alter TABLE szd_icwhose	add jsv_debug TINYINT UNSIGNED NOT NULL DEFAULT 0;


#alter table tasq_icwhose add bo_only TINYINT UNSIGNED NOT NULL DEFAULT 0;
