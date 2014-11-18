use tasq;
#set names cp1251;

DROP TABLE IF EXISTS tasq_project;
CREATE TABLE tasq_project (
	id				INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	date_updated	TIMESTAMP(14),
	date_created	TIMESTAMP(14),
	date_published	TIMESTAMP(14),
	published		TINYINT UNSIGNED NOT NULL DEFAULT 1,
	deleted			TINYINT UNSIGNED NOT NULL DEFAULT 0,
	manorder		INTEGER UNSIGNED NOT NULL DEFAULT 0,
	ident			VARCHAR(250) NOT NULL DEFAULT '',

	brief			TEXT,
	content			TEXT,
	hrefto			VARCHAR(250) NOT NULL DEFAULT '',

	contact			TEXT,

	PRIMARY KEY(id)
	, key (published), key (deleted)
);

#desc tasq_project;

insert into tasq_project(id, manorder, published, ident) values(1, 1, 1, 'удалённая работа с клиентом');
insert into tasq_project(id, manorder, published, ident) values(2, 2, 0, 'экспорт в PDF');
insert into tasq_project(id, manorder, published, ident) values(3, 3, 0, 'автокопирование в FoxitReader');
insert into tasq_project(id, manorder, published, ident) values(4, 4, 0, 'другой отдельный проект');
insert into tasq_project(id, manorder, published, ident) values(5, 5, 0, 'ещё отдельный проект');

#select * from tasq_project;

#alter TABLE project add contact TEXT;
