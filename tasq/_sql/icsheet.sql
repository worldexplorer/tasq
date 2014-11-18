use tasq;
#set names cp1251;

DROP TABLE IF EXISTS tasq_icsheet;
CREATE TABLE tasq_icsheet (
	id				INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	date_updated	TIMESTAMP(14),
	date_created	TIMESTAMP(14),
	date_published	TIMESTAMP(14),
	published		TINYINT UNSIGNED NOT NULL DEFAULT 1,
	deleted			TINYINT UNSIGNED NOT NULL DEFAULT 0,
	manorder		INTEGER UNSIGNED NOT NULL DEFAULT 0,
	ident			VARCHAR(250) NOT NULL DEFAULT '',

	icwhose			INTEGER UNSIGNED NOT NULL DEFAULT 0,
	content			TEXT,

	PRIMARY KEY(id)
);

#desc tasq_icsheet;
#insert into tasq_icsheet(id, ident) values(1, 'NONE');
#select * from tasq_icsheet;
