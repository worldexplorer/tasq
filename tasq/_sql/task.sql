use tasq;
#set names cp1251;

DROP TABLE IF EXISTS tasq_task;
CREATE TABLE tasq_task (
	id					INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	date_updated		TIMESTAMP,
	date_created		TIMESTAMP,
	date_published		TIMESTAMP,
	published			TINYINT UNSIGNED NOT NULL DEFAULT 1,
	deleted				TINYINT UNSIGNED NOT NULL DEFAULT 0,
	manorder			INTEGER UNSIGNED NOT NULL DEFAULT 0,
	ident				VARCHAR(250) NOT NULL DEFAULT '',

	article				VARCHAR(250) NOT NULL DEFAULT '',

	project				INTEGER UNSIGNED NOT NULL DEFAULT 0,
#	pmodel				INTEGER UNSIGNED NOT NULL DEFAULT 0,
	tgroup				INTEGER UNSIGNED NOT NULL DEFAULT 0,

	mayorder			TINYINT UNSIGNED NOT NULL DEFAULT 1,

	price_1				FLOAT UNSIGNED NOT NULL DEFAULT 0,
	currency_1			INTEGER UNSIGNED NOT NULL DEFAULT 1,
	pricecomment_1		VARCHAR(250) NOT NULL DEFAULT '',
	efforts_1			FLOAT UNSIGNED NOT NULL DEFAULT 0,

	price_2				FLOAT UNSIGNED NOT NULL DEFAULT 0,
	currency_2			INTEGER UNSIGNED NOT NULL DEFAULT 0,
	pricecomment_2		VARCHAR(250) NOT NULL DEFAULT '',
	efforts_2			FLOAT UNSIGNED NOT NULL DEFAULT 0,

	price_3				FLOAT UNSIGNED NOT NULL DEFAULT 0,
	currency_3			INTEGER UNSIGNED NOT NULL DEFAULT 0,
	pricecomment_3		VARCHAR(250) NOT NULL DEFAULT '',
	efforts_3			FLOAT UNSIGNED NOT NULL DEFAULT 0,

#	price_buy1			FLOAT UNSIGNED NOT NULL DEFAULT 0,
#	currency_buy1		INTEGER UNSIGNED NOT NULL DEFAULT 0,
#	pricecomment_buy1	VARCHAR(250) NOT NULL DEFAULT '',

	request				TEXT,
	request_no_freetext	TINYINT UNSIGNED NOT NULL DEFAULT 0,
	discussion			TEXT,
	discussion_no_freetext	TINYINT UNSIGNED NOT NULL DEFAULT 0,
	response			TEXT,
	response_no_freetext	TINYINT UNSIGNED NOT NULL DEFAULT 0,
	
	
#	is_new				TINYINT NOT NULL DEFAULT 0,
#	archived			TINYINT NOT NULL DEFAULT 0,

#	i_published			TINYINT NOT NULL DEFAULT 1,
	i_manorder			INTEGER UNSIGNED NOT NULL DEFAULT 0,

#	pagetitle			VARCHAR(250) NOT NULL DEFAULT '',
#	title				VARCHAR(250) NOT NULL DEFAULT '',
#	meta_keywords		VARCHAR(250) NOT NULL DEFAULT '',
#	meta_description	VARCHAR(250) NOT NULL DEFAULT '',

	PRIMARY KEY(id)
#	, key (pmodel), key (project, pmodel)
	, key (project), key (tgroup)
	, key (published), key (deleted)
	, key(ident)
#	, key(pagetitle), key(title), key(meta_keywords), key(meta_description)
#	, key(brief)
);



#desc tasq_task;

insert into tasq_task (id, manorder, i_manorder, ident, tgroup, project, article, request, discussion, date_created) values
	(1, 1, 1, '�������� ������ ��������� ������������� ���� � �������� �������', 21, 1, '����_1', '������� �������� �������� �1 ������� �������� �������� �1 ������� �������� �������� �1', '<p>������ �������� �������� �1 ������ �������� �������� �1 ������ �������� �������� �1 ������ �������� �������� �1</p> <ul><li>������ ��������, ����� ����� 1</li><li>������ ��������, ����� ����� 2</li><li>������ ��������, ����� ����� 3</li></ul><h4>������ ��������</h4><p>������ �������� �������� �1 ������ �������� �������� �1 ������ �������� �������� �1 ������ �������� �������� �1 ������ �������� �������� �1 ������ �������� �������� �1 ������ �������� �������� �1 ������ �������� �������� �1</p>', CURRENT_TIMESTAMP),
	(2, 2, 2, '�������� ���������� �������� ����� �� ��������� �����', 21, 1, '����_2', '������� �������� �������� �2 ������� �������� �������� �2 ������� �������� �������� �2', '<p>������ �������� �������� �2 ������ �������� �������� �2 ������ �������� �������� �2 ������ �������� �������� �2</p> <ul><li>������ ��������, ����� ����� 1</li><li>������ ��������, ����� ����� 2</li><li>������ ��������, ����� ����� 3</li></ul><h4>������ ��������</h4><p>������ �������� �������� �2 ������ �������� �������� �2 ������ �������� �������� �2 ������ �������� �������� �2 ������ �������� �������� �2 ������ �������� �������� �2 ������ �������� �������� �2 ������ �������� �������� �2</p>', CURRENT_TIMESTAMP),
	(3, 3, 3, '��������� ���� 1', 21, 1, '����_3', '������� �������� �������� �3 ������� �������� �������� �3 ������� �������� �������� �3 ', '<p>������ �������� �������� �3 ������ �������� �������� �3 ������ �������� �������� �3 ������ �������� �������� �3</p> <ul><li>������ ��������, ����� ����� 1</li><li>������ ��������, ����� ����� 2</li><li>������ ��������, ����� ����� 3</li></ul><h4>������ ��������</h4><p>������ �������� �������� �3 ������ �������� �������� �3 ������ �������� �������� �3 ������ �������� �������� �3 ������ �������� �������� �3 ������ �������� �������� �3 ������ �������� �������� �3 ������ �������� �������� �3</p>', CURRENT_TIMESTAMP),
	(4, 4, 4, '�� � ����� ���������� 2', 21, 1, '����_4', '������� �������� �������� �4 ������� �������� �������� �4 ������� �������� �������� �4', '<p>������ �������� �������� �4 ������ �������� �������� �4 ������ �������� �������� �4 ������ �������� �������� �4</p> <ul><li>������ ��������, ����� ����� 1</li><li>������ ��������, ����� ����� 2</li><li>������ ��������, ����� ����� 3</li></ul><h4>������ ��������</h4><p>������ �������� �������� �4 ������ �������� �������� �4 ������ �������� �������� �4 ������ �������� �������� �4 ������ �������� �������� �4 ������ �������� �������� �4 ������ �������� �������� �4 ������ �������� �������� �4</p>', CURRENT_TIMESTAMP);

; 

#insert ignore into tasq_m2m_task_tgroup(task, tgroup) select id, tgroup from tasq_task;
#update tasq_m2m_task_tgroup set date_created=CURRENT_TIMESTAMP, date_published=CURRENT_TIMESTAMP, manorder=id;

update tasq_task set i_manorder=manorder;


#select * from tasq_task;

#alter table tasq_task add img_award VARCHAR(250) NOT NULL DEFAULT '';
#alter table tasq_task add features 	TEXT;
#alter table tasq_task add bundling 	TEXT;
