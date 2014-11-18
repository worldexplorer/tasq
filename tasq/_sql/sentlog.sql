use tasq;
#set names cp1251;

DROP TABLE IF EXISTS tasq_sentlog;
CREATE TABLE tasq_sentlog (
	id				INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	date_updated	TIMESTAMP,
	date_created	TIMESTAMP,
	date_published	TIMESTAMP,
	published		TINYINT UNSIGNED NOT NULL DEFAULT 0,
	deleted			TINYINT UNSIGNED NOT NULL DEFAULT 0,
	manorder		INTEGER UNSIGNED NOT NULL DEFAULT 0,
	ident			VARCHAR(250) NOT NULL DEFAULT '',

	mtpl			INTEGER UNSIGNED NOT NULL DEFAULT 0,
	content			TEXT,
	remote_address	VARCHAR(250) NOT NULL DEFAULT '',

	PRIMARY KEY(id)
	,key(mtpl)
);

#desc tasq_sentlog;
#insert into tasq_sentlog(id, ident) values(1, 'NONE');
#select * from tasq_sentlog;

#alter table tasq_sentlog add mtpl INTEGER UNSIGNED NOT NULL DEFAULT 0;
#alter table tasq_sentlog add key(mtpl);
