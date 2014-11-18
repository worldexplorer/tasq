use tasq;
#set names cp1251;

DROP TABLE IF EXISTS tasq_constant;
CREATE TABLE tasq_constant (
	id					INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	date_updated		TIMESTAMP,
	date_created		TIMESTAMP,
	date_published		TIMESTAMP,
	published			TINYINT UNSIGNED NOT NULL DEFAULT 1,
	deleted				TINYINT UNSIGNED NOT NULL DEFAULT 0,
	manorder			INTEGER UNSIGNED NOT NULL DEFAULT 0,
	ident				VARCHAR(250) NOT NULL DEFAULT '',

	hashkey				VARCHAR(250) NOT NULL DEFAULT '',
	content				TEXT,
	content_no_freetext	TINYINT UNSIGNED NOT NULL DEFAULT 1,

	PRIMARY KEY(id)
	, UNIQUE (hashkey)
);

#desc tasq_constant;

insert into tasq_constant (id, manorder, ident, hashkey, content) values
(1,1,'Счётчики в начале страниц','COUNTER_TOP',''),
(2,2,'Счётчики в конце страниц','COUNTER_BOTTOM',''),
(3,3,'Контент в шапке','CONTENT_TOP','Шапка сайта'),
(4,4,'Контент в подвале','CONTENT_BOTTOM','Подвал сайта')
;

#select * from tasq_constant;
