use tasq;
#set names cp1251;

DROP TABLE IF EXISTS tasq_imgtype;
CREATE TABLE tasq_imgtype (
	id				INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	date_updated	TIMESTAMP,
	date_created	TIMESTAMP,
	date_published	TIMESTAMP,
	published		TINYINT UNSIGNED NOT NULL DEFAULT 1,
	deleted			TINYINT UNSIGNED NOT NULL DEFAULT 0,
	manorder		INTEGER UNSIGNED NOT NULL DEFAULT 0,
	ident			VARCHAR(250) NOT NULL DEFAULT '',

	hashkey			VARCHAR(250) NOT NULL DEFAULT '',
	content			TEXT,
	imglimit		INTEGER UNSIGNED NOT NULL DEFAULT 0,
	merge_seed		INTEGER UNSIGNED NOT NULL DEFAULT 0,

	resize_default_qlty			VARCHAR(250) NOT NULL DEFAULT 85,
	resize_default_width		VARCHAR(250) NOT NULL DEFAULT '',
	resize_default_height		VARCHAR(250) NOT NULL DEFAULT '',
	resize_published			TINYINT UNSIGNED NOT NULL DEFAULT 1,
	resize_default_checked		TINYINT UNSIGNED NOT NULL DEFAULT 0,

	big_resize_default_qlty		VARCHAR(250) NOT NULL DEFAULT 85,
	big_resize_default_width	VARCHAR(250) NOT NULL DEFAULT '',
	big_resize_default_height	VARCHAR(250) NOT NULL DEFAULT '',
	big_resize_published		TINYINT UNSIGNED NOT NULL DEFAULT 1,
	big_resize_default_checked	TINYINT UNSIGNED NOT NULL DEFAULT 0,

	first_autoresize_qlty		VARCHAR(250) NOT NULL DEFAULT 85,
	first_autoresize_width		VARCHAR(250) NOT NULL DEFAULT '',
	first_autoresize_height		VARCHAR(250) NOT NULL DEFAULT '',
#	first_autoresize_firstonly	TINYINT UNSIGNED NOT NULL DEFAULT 1,
	first_autoresize_apply		TINYINT UNSIGNED NOT NULL DEFAULT 0,
	first_autoresize_debug		TINYINT UNSIGNED NOT NULL DEFAULT 0,
	first_autoresize_tpl_ex		TEXT,
	first_autoresize_tpl_nex	TEXT,

	first_merge_img				VARCHAR(250) NOT NULL DEFAULT '',
	first_merge_dstfname		VARCHAR(250) NOT NULL DEFAULT '',
	first_merge_alfa			TINYINT UNSIGNED NOT NULL DEFAULT 30,
	first_merge_type			TINYINT UNSIGNED NOT NULL DEFAULT 0,
	first_merge_apply			TINYINT UNSIGNED NOT NULL DEFAULT 1,


	every_autoresize_qlty		VARCHAR(250) NOT NULL DEFAULT 85,
	every_autoresize_width		VARCHAR(250) NOT NULL DEFAULT '',
	every_autoresize_height		VARCHAR(250) NOT NULL DEFAULT '',
#	every_autoresize_firstonly	TINYINT UNSIGNED NOT NULL DEFAULT 1,
	every_autoresize_apply		TINYINT UNSIGNED NOT NULL DEFAULT 0,
	every_autoresize_debug		TINYINT UNSIGNED NOT NULL DEFAULT 0,
	every_autoresize_tpl_ex		TEXT,
	every_autoresize_tpl_nex	TEXT,

	every_merge_img				VARCHAR(250) NOT NULL DEFAULT '',
	every_merge_dstfname		VARCHAR(250) NOT NULL DEFAULT '',
	every_merge_alfa			TINYINT UNSIGNED NOT NULL DEFAULT 30,
	every_merge_type			TINYINT UNSIGNED NOT NULL DEFAULT 0,
	every_merge_apply			TINYINT UNSIGNED NOT NULL DEFAULT 1,


	first2_autoresize_qlty		VARCHAR(250) NOT NULL DEFAULT 85,
	first2_autoresize_width		VARCHAR(250) NOT NULL DEFAULT '',
	first2_autoresize_height	VARCHAR(250) NOT NULL DEFAULT '',
	first2_autoresize_firstonly	TINYINT UNSIGNED NOT NULL DEFAULT 1,
	first2_autoresize_apply		TINYINT UNSIGNED NOT NULL DEFAULT 0,
	first2_autoresize_debug		TINYINT UNSIGNED NOT NULL DEFAULT 0,
	first2_autoresize_tpl_ex	TEXT,
	first2_autoresize_tpl_nex	TEXT,

	first2_merge_img			VARCHAR(250) NOT NULL DEFAULT '',
	first2_merge_dstfname		VARCHAR(250) NOT NULL DEFAULT '',
	first2_merge_alfa			TINYINT UNSIGNED NOT NULL DEFAULT 30,
	first2_merge_type			TINYINT UNSIGNED NOT NULL DEFAULT 0,
	first2_merge_apply			TINYINT UNSIGNED NOT NULL DEFAULT 1,


	every2_autoresize_qlty		VARCHAR(250) NOT NULL DEFAULT 85,
	every2_autoresize_width		VARCHAR(250) NOT NULL DEFAULT '',
	every2_autoresize_height	VARCHAR(250) NOT NULL DEFAULT '',
	every2_autoresize_firstonly	TINYINT UNSIGNED NOT NULL DEFAULT 1,
	every2_autoresize_apply		TINYINT UNSIGNED NOT NULL DEFAULT 0,
	every2_autoresize_debug		TINYINT UNSIGNED NOT NULL DEFAULT 0,
	every2_autoresize_tpl_ex	TEXT,
	every2_autoresize_tpl_nex	TEXT,


	every2_merge_img			VARCHAR(250) NOT NULL DEFAULT '',
	every2_merge_dstfname		VARCHAR(250) NOT NULL DEFAULT '',
	every2_merge_alfa			TINYINT UNSIGNED NOT NULL DEFAULT 30,
	every2_merge_type			TINYINT UNSIGNED NOT NULL DEFAULT 0,
	every2_merge_apply			TINYINT UNSIGNED NOT NULL DEFAULT 1,






	first3_autoresize_qlty		VARCHAR(250) NOT NULL DEFAULT 85,
	first3_autoresize_width		VARCHAR(250) NOT NULL DEFAULT '',
	first3_autoresize_height	VARCHAR(250) NOT NULL DEFAULT '',
	first3_autoresize_firstonly	TINYINT UNSIGNED NOT NULL DEFAULT 1,
	first3_autoresize_apply		TINYINT UNSIGNED NOT NULL DEFAULT 0,
	first3_autoresize_debug		TINYINT UNSIGNED NOT NULL DEFAULT 0,
	first3_autoresize_tpl_ex	TEXT,
	first3_autoresize_tpl_nex	TEXT,

	first3_merge_img			VARCHAR(250) NOT NULL DEFAULT '',
	first3_merge_dstfname		VARCHAR(250) NOT NULL DEFAULT '',
	first3_merge_alfa			TINYINT UNSIGNED NOT NULL DEFAULT 30,
	first3_merge_type			TINYINT UNSIGNED NOT NULL DEFAULT 0,
	first3_merge_apply			TINYINT UNSIGNED NOT NULL DEFAULT 1,


	every3_autoresize_qlty		VARCHAR(250) NOT NULL DEFAULT 85,
	every3_autoresize_width		VARCHAR(250) NOT NULL DEFAULT '',
	every3_autoresize_height	VARCHAR(250) NOT NULL DEFAULT '',
	every3_autoresize_firstonly	TINYINT UNSIGNED NOT NULL DEFAULT 1,
	every3_autoresize_apply		TINYINT UNSIGNED NOT NULL DEFAULT 0,
	every3_autoresize_debug		TINYINT UNSIGNED NOT NULL DEFAULT 0,
	every3_autoresize_tpl_ex	TEXT,
	every3_autoresize_tpl_nex	TEXT,

	every3_merge_img			VARCHAR(250) NOT NULL DEFAULT '',
	every3_merge_dstfname		VARCHAR(250) NOT NULL DEFAULT '',
	every3_merge_alfa			TINYINT UNSIGNED NOT NULL DEFAULT 30,
	every3_merge_type			TINYINT UNSIGNED NOT NULL DEFAULT 0,
	every3_merge_apply			TINYINT UNSIGNED NOT NULL DEFAULT 1,



	first4_autoresize_qlty		VARCHAR(250) NOT NULL DEFAULT 85,
	first4_autoresize_width		VARCHAR(250) NOT NULL DEFAULT '',
	first4_autoresize_height	VARCHAR(250) NOT NULL DEFAULT '',
	first4_autoresize_firstonly	TINYINT UNSIGNED NOT NULL DEFAULT 1,
	first4_autoresize_apply		TINYINT UNSIGNED NOT NULL DEFAULT 0,
	first4_autoresize_debug		TINYINT UNSIGNED NOT NULL DEFAULT 0,
	first4_autoresize_tpl_ex	TEXT,
	first4_autoresize_tpl_nex	TEXT,

	first4_merge_img			VARCHAR(250) NOT NULL DEFAULT '',
	first4_merge_dstfname		VARCHAR(250) NOT NULL DEFAULT '',
	first4_merge_alfa			TINYINT UNSIGNED NOT NULL DEFAULT 30,
	first4_merge_type			TINYINT UNSIGNED NOT NULL DEFAULT 0,
	first4_merge_apply			TINYINT UNSIGNED NOT NULL DEFAULT 1,


	every4_autoresize_qlty		VARCHAR(250) NOT NULL DEFAULT 85,
	every4_autoresize_width		VARCHAR(250) NOT NULL DEFAULT '',
	every4_autoresize_height	VARCHAR(250) NOT NULL DEFAULT '',
	every4_autoresize_firstonly	TINYINT UNSIGNED NOT NULL DEFAULT 1,
	every4_autoresize_apply		TINYINT UNSIGNED NOT NULL DEFAULT 0,
	every4_autoresize_debug		TINYINT UNSIGNED NOT NULL DEFAULT 0,
	every4_autoresize_tpl_ex	TEXT,
	every4_autoresize_tpl_nex	TEXT,

	every4_merge_img			VARCHAR(250) NOT NULL DEFAULT '',
	every4_merge_dstfname		VARCHAR(250) NOT NULL DEFAULT '',
	every4_merge_alfa			TINYINT UNSIGNED NOT NULL DEFAULT 30,
	every4_merge_type			TINYINT UNSIGNED NOT NULL DEFAULT 0,
	every4_merge_apply			TINYINT UNSIGNED NOT NULL DEFAULT 1,


	img_present				TINYINT UNSIGNED NOT NULL DEFAULT 1,
	img_newqnty				TINYINT UNSIGNED NOT NULL DEFAULT 1,
	img_txt_present			TINYINT UNSIGNED NOT NULL DEFAULT 1,
	img_txt_eq_fname		TINYINT UNSIGNED NOT NULL DEFAULT 1,
	img_url_present			TINYINT UNSIGNED NOT NULL DEFAULT 1,
	img_zip_present			TINYINT UNSIGNED NOT NULL DEFAULT 1,

	img_big_present			TINYINT UNSIGNED NOT NULL DEFAULT 1,
	img_big_newqnty			TINYINT UNSIGNED NOT NULL DEFAULT 1,
	img_big_txt_present		TINYINT UNSIGNED NOT NULL DEFAULT 1,
	img_big_txt_eq_fname	TINYINT UNSIGNED NOT NULL DEFAULT 1,
	img_big_url_present		TINYINT UNSIGNED NOT NULL DEFAULT 1,
	img_big_zip_present		TINYINT UNSIGNED NOT NULL DEFAULT 1,


	img_thumb_present		TINYINT UNSIGNED NOT NULL DEFAULT 1,
	img_thumb_qlty			VARCHAR(250) NOT NULL DEFAULT 85,
	img_thumb_width			VARCHAR(250) NOT NULL DEFAULT '',
	img_thumb_height		VARCHAR(250) NOT NULL DEFAULT '80',


	msg_ident			VARCHAR(250) NOT NULL DEFAULT '��������',
	msg_change			VARCHAR(250) NOT NULL DEFAULT '�������� ��������',
	msg_add				VARCHAR(250) NOT NULL DEFAULT '����� ��������',
	msg_img				VARCHAR(250) NOT NULL DEFAULT '���������',
	msg_img_big			VARCHAR(250) NOT NULL DEFAULT '�������',

	img_table			VARCHAR(250) NOT NULL DEFAULT '',

	PRIMARY KEY(id)
);

#desc tasq_imgtype;
insert into tasq_imgtype (id, manorder, deleted, ident, hashkey, first_autoresize_width, first_autoresize_apply, every_autoresize_width, every_autoresize_apply) values
	(1, 1, 0, "�������� � �������", "IMG_CONTENT", 100, 1, '', 0);

insert into tasq_imgtype (id, manorder, deleted, ident, hashkey, first_autoresize_width, first_autoresize_apply, every_autoresize_width, every_autoresize_apply, img_present) values
	(2, 2, 0, "���������� �������", "IMG_PRODUCT", 100, 1, 120, 1, 0);

insert into tasq_imgtype(id, manorder, deleted, ident, hashkey, imglimit, first_autoresize_width, first_autoresize_height, first_autoresize_apply) values
	(3, 3, 1, "������� ����������", "IMG_SUPPLIER", 1, 100, 110, 1);

insert into tasq_imgtype (id, manorder, deleted, ident, hashkey
		, first_autoresize_width, first_autoresize_height, first_autoresize_apply
		, first2_autoresize_width, first2_autoresize_height, first2_autoresize_apply
		, every_autoresize_width, every_autoresize_apply, img_present) values
	(4, 4, 0, "�������� � ������ ������� (������ �������� ����� ���������� �� ������� ���� � ������ �������� ����� [�� �������])", "IMG_tgroup"
		, 198, 141, 1
		, 626, 331, 1
		, 110, 1, 0);

insert into tasq_imgtype(id, manorder, deleted, ident, hashkey, imglimit, first_autoresize_width, first_autoresize_height, first_autoresize_apply) values
	(5, 5, 1, "���������� ���������", "IMG_PMODEL", 1, 100, 110, 1);

insert into tasq_imgtype (id, manorder, deleted, ident, hashkey, first_autoresize_width, first_autoresize_height, first_autoresize_apply, every_autoresize_width, every_autoresize_apply, img_present, resize_published) values
	(6, 6, 0, "�������� � �������", "IMG_NEWS", 100, 110, 1, 120, 1, 0, 0);

insert into tasq_imgtype (id, manorder, deleted, ident, hashkey, imglimit, first_autoresize_width, first_autoresize_height, first_autoresize_apply, img_present, resize_published) values
	(7, 7, 1, "���������� ����������", "IMG_PERSON", 1, 100, 110, 1, 0, 0);

insert into tasq_imgtype (id, manorder, deleted, ident, hashkey, imglimit, first_autoresize_width, first_autoresize_apply, every_autoresize_width, every_autoresize_apply, img_present, resize_published) values
	(8, 8, 0, "����� �������", "IMG_SHOPMAP", 0, 120, 1, 120, 1, 0, 0);

insert into tasq_imgtype (id, manorder, deleted, ident, hashkey, first_autoresize_width, first_autoresize_apply, every_autoresize_width, every_autoresize_apply, img_present, resize_published) values
	(9, 9, 1, "�������� �� ������ �����", "IMG_CONTENT_LEFT", 100, 1, 120, 1, 0, 0);

insert into tasq_imgtype(id, manorder, deleted, ident, hashkey, first_autoresize_width, first_autoresize_apply, every_autoresize_width, every_autoresize_apply, img_present, resize_published) values
	(10, 10, 1, "�������� �� ������� �����", "IMG_CONTENT_RIGHT", 100, 1, 120, 1, 0, 0);

insert into tasq_imgtype (id, manorder, deleted, ident, hashkey, imglimit, first_autoresize_width, first_autoresize_height, first_autoresize_apply, img_present, resize_published) values
	(11, 11, 1, "���������� ������������", "IMG_CONSULTANT", 1, 100, 110, 1, 0, 0);

insert into tasq_imgtype (id, manorder, deleted, ident, hashkey, imglimit, first_autoresize_width, first_autoresize_height, first_autoresize_apply, img_present, resize_published) values
	(12, 12, 1, "���������� ����������", "IMG_PAPERMAN", 1, 100, 110, 1, 0, 0);

insert into tasq_imgtype (id, manorder, deleted, ident, hashkey, first_autoresize_width, first_autoresize_apply, every_autoresize_width, every_autoresize_apply, img_big_present, resize_published) values
	(13, 13, 1, "�������� � ����� �������", "IMG_COLUMN_LEFT", 100, 1, 120, 1, 0, 0);

insert into tasq_imgtype(id, manorder, deleted, ident, hashkey, first_autoresize_width, first_autoresize_apply, every_autoresize_width, every_autoresize_apply, img_big_present, resize_published) values
	(14, 14, 1, "�������� � ������ �������", "IMG_COLUMN_RIGHT", 100, 1, 120, 1, 0, 0);

insert into tasq_imgtype (id, manorder, deleted, ident, hashkey, first_autoresize_width, first_autoresize_apply, every_autoresize_width, every_autoresize_apply, img_present) values
	(15, 15, 1, "����������� � ������", "IMG_SERVICE", 100, 1, 120, 1, 0);

insert into tasq_imgtype (id, manorder, deleted, ident, hashkey, imglimit, first_autoresize_width, first_autoresize_height, first_autoresize_apply, img_present, resize_published) values
	(16, 16, 1, "�������� � �����", "IMG_TOP", 1, 747, 0, 1, 0, 0);

insert into tasq_imgtype (id, manorder, deleted, ident, hashkey, imglimit, first_autoresize_width, first_autoresize_height, first_autoresize_apply, img_present, resize_published) values
	(17, 17, 1, "������ ������� ���������", "IMG_DOC_INOTHER_FORMAT", 0, 0, 0, 0, 1, 0);

insert into tasq_imgtype (id, manorder, deleted, ident, hashkey, first_autoresize_width, first_autoresize_apply, every_autoresize_width, every_autoresize_apply, img_big_present, resize_published) values
	(18, 18, 1, "����������� ��� ����������� �� ������� ������", "IMG_CONTENT_FOR_UPPER_MENULEVEL", 140, 1, 0, 0, 0, 0);

insert into tasq_imgtype (id, manorder, deleted, ident, hashkey, imglimit, first_autoresize_width, first_autoresize_apply, every_autoresize_width, every_autoresize_apply, img_big_present, resize_published) values
	(19, 19, 0, "������� ��������� ����", "IMG_CSTORE", 140, 1, 1, 0, 0, 0, 0);

insert into tasq_imgtype (id, manorder, deleted, ident, hashkey, first_autoresize_width, first_autoresize_apply, every_autoresize_width, every_autoresize_apply, img_big_present, resize_published) values
	(20, 20, 1, "C�����, ������, ������ � ������ ���������� � PDF", "PDF_ARTICLE", 140, 1, 0, 0, 0, 0);

insert into tasq_imgtype (id, manorder, deleted, ident, hashkey, first_autoresize_width, first_autoresize_apply, every_autoresize_width, every_autoresize_apply, img_big_present, resize_published) values
	(21, 21, 1, "����������� ��� 3D-�����������", "MPG_ARTICLE", 140, 1, 0, 0, 0, 0);

insert into tasq_imgtype (id, manorder, deleted, ident, hashkey, first_autoresize_width, first_autoresize_apply, every_autoresize_width, every_autoresize_apply, img_big_present, resize_published) values
	(22, 22, 1, "����������", "MP3_AUDIO", 140, 1, 0, 0, 0, 0);

insert into tasq_imgtype (id, manorder, deleted, ident, hashkey, first_autoresize_width, first_autoresize_apply, every_autoresize_width, every_autoresize_apply, img_present) values
	(23, 23, 0, "�������� � ������", "IMG_ARTICLE", 100, 1, 120, 1, 0);

insert into tasq_imgtype (id, manorder, deleted, ident, hashkey, first_autoresize_width, first_autoresize_apply, every_autoresize_width, every_autoresize_apply, img_present) values
	(24, 24, 0, "�������� � ������� ������", "IMG_AGROUP", 100, 1, 110, 1, 0);

insert into tasq_imgtype (id, manorder, deleted, ident, hashkey, first_autoresize_width, first_autoresize_apply, every_autoresize_width, every_autoresize_apply, img_present) values
	(25, 25, 0, "�������� � �������", "IMG_ISSUE", 100, 1, 110, 1, 0);

insert into tasq_imgtype (id, manorder, deleted, ident, hashkey, first_autoresize_width, first_autoresize_apply, every_autoresize_width, every_autoresize_apply, img_present) values
	(26, 26, 0, "�������� � �������", "IMG_PROJECT", 100, 1, 110, 1, 0);

insert into tasq_imgtype (id, manorder, deleted, ident, hashkey, first_autoresize_width, first_autoresize_apply, every_autoresize_width, every_autoresize_apply, img_present) values
	(27, 27, 0, "�������� � ������ �����", "IMG_TGROUP", 100, 1, 110, 1, 0);

insert into tasq_imgtype (id, manorder, deleted, ident, hashkey, first_autoresize_width, first_autoresize_apply, every_autoresize_width, every_autoresize_apply, img_present) values
	(28, 28, 0, "�������� � ������", "IMG_TASK", 100, 1, 110, 1, 0);

insert into tasq_imgtype (id, manorder, deleted, ident, hashkey, first_autoresize_width, first_autoresize_apply, every_autoresize_width, every_autoresize_apply, img_present) values
	(29, 29, 0, "������ � ������", "ATTACHMENTS_TASK", 100, 1, 110, 1, 0);


#select * from tasq_imgtype;