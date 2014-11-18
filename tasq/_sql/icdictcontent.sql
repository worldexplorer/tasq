use tasq;
#set names cp1251;

DROP TABLE IF EXISTS tasq_icdictcontent;
CREATE TABLE tasq_icdictcontent (
	id				INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	date_updated	TIMESTAMP(14),
	date_created	TIMESTAMP(14),
	date_published	TIMESTAMP(14),
	published		TINYINT UNSIGNED NOT NULL DEFAULT 1,
	deleted			TINYINT UNSIGNED NOT NULL DEFAULT 0,
	manorder		INTEGER UNSIGNED NOT NULL DEFAULT 0,
	ident			VARCHAR(250) NOT NULL DEFAULT '',

	hashkey			VARCHAR(250) NOT NULL DEFAULT '',
	content			TEXT,

	label_style		VARCHAR(250) NOT NULL DEFAULT '',
	tf1_width		INTEGER UNSIGNED NOT NULL DEFAULT 0,
	tf1_incolumn 	TINYINT UNSIGNED NOT NULL DEFAULT 0,

	tf1_addtodict 		TINYINT UNSIGNED NOT NULL DEFAULT 0,
	tf1_addedpublished 	TINYINT UNSIGNED NOT NULL DEFAULT 0,

	icdict			INTEGER UNSIGNED NOT NULL DEFAULT 0,

	PRIMARY KEY(id)
);

#desc tasq_icdictcontent;

insert into tasq_icdictcontent(id, manorder, icdict, ident) values(1, 1, 1, 'Бумага');
insert into tasq_icdictcontent(id, manorder, icdict, ident) values(2, 2, 1, 'Картон');
insert into tasq_icdictcontent(id, manorder, icdict, ident) values(3, 3, 1, 'Пластик');
insert into tasq_icdictcontent(id, manorder, icdict, ident) values(4, 4, 1, 'Подарочная');

#select * from tasq_icdictcontent;


#alter table tasq_icdictcontent add tf1_addtodict TINYINT UNSIGNED NOT NULL DEFAULT 0;
#alter table tasq_icdictcontent add tf1_addedpublished TINYINT UNSIGNED NOT NULL DEFAULT 0;
