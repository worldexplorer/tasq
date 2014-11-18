use tasq;
##set names cp1251;

DROP TABLE IF EXISTS tasq_news;
CREATE TABLE tasq_news (
	id					INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	date_updated		TIMESTAMP,
	date_created		TIMESTAMP,
	date_published		TIMESTAMP,
	published			TINYINT UNSIGNED NOT NULL DEFAULT 1,
	deleted				TINYINT UNSIGNED NOT NULL DEFAULT 0,
	manorder			INTEGER UNSIGNED NOT NULL DEFAULT 0,
	ident				VARCHAR(250) NOT NULL DEFAULT '',

	brief				TEXT,
	brief_no_freetext	TINYINT UNSIGNED NOT NULL DEFAULT 0,
	content				TEXT,
	content_no_freetext	TINYINT UNSIGNED NOT NULL DEFAULT 0,
	ngroup				TINYINT UNSIGNED NOT NULL DEFAULT 2,

	hrefto				VARCHAR(250) NOT NULL DEFAULT '',
	srcurl				TEXT,

	i_published			TINYINT NOT NULL DEFAULT 1,
	i_manorder			INTEGER UNSIGNED NOT NULL DEFAULT 0,

	rsss				INTEGER NOT NULL DEFAULT 0,
	rss_published		TINYINT NOT NULL DEFAULT 1,

	file1				VARCHAR(250) NOT NULL DEFAULT '',
	file1_comment		TEXT,
	file2				VARCHAR(250) NOT NULL DEFAULT '',
	file2_comment		TEXT,
	file3				VARCHAR(250) NOT NULL DEFAULT '',
	file3_comment		TEXT,
	file4				VARCHAR(250) NOT NULL DEFAULT '',
	file4_comment		TEXT,
	file5				VARCHAR(250) NOT NULL DEFAULT '',
	file5_comment		TEXT,

	pagetitle			VARCHAR(250) NOT NULL DEFAULT '',
	title				VARCHAR(250) NOT NULL DEFAULT '',
	meta_keywords		TEXT,
	meta_description	TEXT,
	banner_top			INTEGER UNSIGNED NOT NULL DEFAULT 0,
	hits				INTEGER UNSIGNED NOT NULL DEFAULT 0,
	archived			TINYINT NOT NULL DEFAULT 0,

	PRIMARY KEY(id)
);

#desc tasq_news;
#insert into tasq_news(id, ident) values(1, 'NONE');

insert into tasq_news(id, manorder, date_created, date_published, ident, brief, content) values (1, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, '���� vfsglobal (��� ������� � ��� ��������)', '<p>� ����������� ��� � ��� � ������� ��������� ������:</p><ul><li>����� �1</li><li>����� �2</li><li>����� �3</li></ul><p>�������� �� ���������� ������������</p>', '<p>��������, � ����������� ��� � ������� ������:</p><ul><li>����� �1</li><li>����� �2</li><li>����� �3</li></ul><p>�������� �� ���������� ������������</p><p>��������, � ����������� ��� � ������� ������:</p><ul><li>����� �1</li><li>����� �2</li><li>����� �3</li></ul><p>�������� �� ���������� ������������</p><h4>�������� �� ���������� ������������</h4><p>��������, � ����������� ��� � ������� ������ �������� �� ���������� ������������</p>');

insert into tasq_news(id, manorder, date_created, date_published, ident, brief, content) values (2, 2, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, '���� ��� ������������� �����', '<p>� 1 ������� ���� �������� �������� � ���� �� ����. �������� ��������� �� ������������ ����������</p>', '<p>� 1 ������� ���� �������� �������� � ���� �� ����. �������� ��������� �� ������������ ����������. � 1 ������� ���� �������� �������� � ���� �� ����. �������� ��������� �� ������������ ����������. � 1 ������� ���� �������� �������� � ���� �� ����. �������� ��������� �� ������������ ����������.</p><ul><li>����� �1</li><li>����� �2</li><li>����� �3</li></ul><p>� 1 ������� ���� �������� �������� � ���� �� ����. �������� ��������� �� ������������ ����������. � 1 ������� ���� �������� �������� � ���� �� ����. �������� ��������� �� ������������ ����������. � 1 ������� ���� �������� �������� � ���� �� ����. �������� ��������� �� ������������ ���������� � 1 ������� ���� �������� �������� � ���� �� ����. �������� ��������� �� ������������ ���������� � 1 ������� ���� �������� �������� � ���� �� ����. �������� ��������� �� ������������ ���������� � 1 ������� ���� �������� �������� � ���� �� ����. �������� ��������� �� ������������ ����������.</p><h4>� 1 ������� ���� �������� �������� � ���� �� ����</h4><p>� 1 ������� ���� �������� �������� � ���� �� ����. �������� ��������� �� ������������ ����������. � 1 ������� ���� �������� �������� � ���� �� ����. �������� ��������� �� ������������ ����������. � 1 ������� ���� �������� �������� � ���� �� ����. �������� ��������� �� ������������ ����������.</p>');


#alter table tasq_news add i_published TINYINT NOT NULL DEFAULT 1;
#alter table tasq_news add i_manorder INTEGER UNSIGNED NOT NULL DEFAULT 0;
#alter table tasq_news add hrefto VARCHAR(250) NOT NULL DEFAULT '';

#select * from tasq_news;
