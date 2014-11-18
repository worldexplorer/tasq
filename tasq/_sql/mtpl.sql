use tasq;
#set names cp1251;

DROP TABLE IF EXISTS tasq_mtpl;
CREATE TABLE tasq_mtpl (
	id					INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	date_updated		TIMESTAMP,
	date_created		TIMESTAMP,
	date_published		TIMESTAMP,
	published			TINYINT UNSIGNED NOT NULL DEFAULT 1,
	deleted				TINYINT UNSIGNED NOT NULL DEFAULT 0,
	manorder			INTEGER UNSIGNED NOT NULL DEFAULT 0,
	ident				VARCHAR(250) NOT NULL DEFAULT '',

	hashkey				VARCHAR(250) NOT NULL DEFAULT '',
	subject				TEXT,
	body				TEXT,
	body_no_freetext	TINYINT UNSIGNED NOT NULL DEFAULT 1,

	rcptto				VARCHAR(250) NOT NULL DEFAULT '',
	sentmsg				VARCHAR(250) NOT NULL DEFAULT '',

	admtail				TEXT,
	savesentlog			TINYINT UNSIGNED NOT NULL DEFAULT 1,

	PRIMARY KEY(id),
	UNIQUE (hashkey)
);

#desc tasq_mtpl;
#select * from tasq_mtpl;

#alter table tasq_mtpl add savesentlog TINYINT UNSIGNED NOT NULL DEFAULT 1;
