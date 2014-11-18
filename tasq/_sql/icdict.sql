use tasq;
#set names cp1251;

DROP TABLE IF EXISTS tasq_icdict;
CREATE TABLE tasq_icdict (
	id				INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	date_updated	TIMESTAMP(14),
	date_created	TIMESTAMP(14),
	date_published	TIMESTAMP(14),
	published		TINYINT UNSIGNED NOT NULL DEFAULT 1,
	deleted			TINYINT UNSIGNED NOT NULL DEFAULT 0,
	manorder		INTEGER UNSIGNED NOT NULL DEFAULT 0,
	ident			VARCHAR(250) NOT NULL DEFAULT '',

	hashkey			VARCHAR(250) NOT NULL DEFAULT '',

	icwhose			INTEGER UNSIGNED NOT NULL DEFAULT 0,

	PRIMARY KEY(id)
);

#desc tasq_icdict;

insert into tasq_icdict(id, manorder, icwhose, ident) values(1, 1, 1, '”паковка');

#select * from tasq_icdict;
