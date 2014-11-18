use tasq;
#set names cp1251;

DROP TABLE IF EXISTS tasq_m2m_task_news;
CREATE TABLE tasq_m2m_task_news (
	id				INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	date_updated	TIMESTAMP(14),
	date_created	TIMESTAMP(14),
	date_published	TIMESTAMP(14),
	published		TINYINT UNSIGNED NOT NULL DEFAULT 1,
	deleted			TINYINT UNSIGNED NOT NULL DEFAULT 0,
	manorder		INTEGER UNSIGNED NOT NULL DEFAULT 0,
	ident			VARCHAR(250) DEFAULT '',

	task			INTEGER UNSIGNED NOT NULL DEFAULT 0,
	news			INTEGER UNSIGNED NOT NULL DEFAULT 0,
	content			TEXT,

	PRIMARY KEY(id),
	key(task, news)
);

#desc tasq_m2m_task_news;
#select * from tasq_m2m_task_news;

