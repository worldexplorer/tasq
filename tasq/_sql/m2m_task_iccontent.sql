use tasq;
#set names cp1251;

DROP TABLE IF EXISTS tasq_m2m_task_iccontent;
CREATE TABLE tasq_m2m_task_iccontent (
	id				INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	date_updated	TIMESTAMP(14),
	date_created	TIMESTAMP(14),
	date_published	TIMESTAMP(14),
	published		TINYINT UNSIGNED NOT NULL DEFAULT 1,
	deleted			TINYINT UNSIGNED NOT NULL DEFAULT 0,
	manorder		INTEGER UNSIGNED NOT NULL DEFAULT 0,
	ident			VARCHAR(250) NOT NULL DEFAULT '',

	task			INTEGER UNSIGNED NOT NULL DEFAULT 0,

	ic				INTEGER UNSIGNED NOT NULL DEFAULT 0,
	iccontent		TEXT,
	iccontent_tf1	VARCHAR(250) NOT NULL DEFAULT '',

	
	PRIMARY KEY(id)
	, key(task, ic)
);

#desc tasq_m2m_task_iccontent;
#select * from tasq_m2m_task_iccontent;
