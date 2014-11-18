use tasq;
#set names cp1251;

DROP TABLE IF EXISTS tasq_jsvalidator;
CREATE TABLE tasq_jsvalidator (
	id				INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	date_updated	TIMESTAMP,
	date_created	TIMESTAMP,
	date_published	TIMESTAMP,
	published		TINYINT UNSIGNED NOT NULL DEFAULT 1,
	deleted			TINYINT UNSIGNED NOT NULL DEFAULT 0,
	manorder		INTEGER UNSIGNED NOT NULL DEFAULT 0,
	ident			VARCHAR(250) NOT NULL DEFAULT '',

	hashkey			VARCHAR(250) NOT NULL DEFAULT '',
	content 		TEXT,

	PRIMARY KEY(id)
);

#desc tasq_jsvalidator;
#insert into tasq_jsvalidator(id, ident) values(1, 'NONE');
INSERT INTO tasq_jsvalidator (id, date_updated, date_created, date_published, published, deleted, manorder, ident, hashkey, content) VALUES
(1,20040511184305,20040511181249,20040511181249,1,0,2,'хотя бы один символ','JSV_TF_CHAR','/./'),
(2,20040511184310,20040511181528,20040511181528,1,0,3,'одна цифра','JSV_TF_DIGIT','/^d$/'),
(3,20040511184330,20040511181639,20040511181639,1,0,6,'слово из латинских букв','JSV_TF_ELETTERS','/[a-z]/i'),
(4,20040511184350,20040511181707,20040511181707,1,0,8,'email [name@domain.com]','JSV_TF_EMAIL','/[a-z_0-9\.]+@[a-z_0-9\\.]+\.[a-z]{2,3}$/i'),
(5,20040511184404,20040511181751,20040511181751,1,0,9,'короткий URL [domain@com]','JSV_TF_SHORTURL','/[a-z_0-9\.]+\.[a-z]{2,3}$/i'),
(6,20040511184314,20040511181937,20040511181937,1,0,4,'число из одной или нескольких цифр','JSV_TF_DIGITS','/^d+$/'),
(12,20040512182155,20040511184615,20040511184615,1,0,1,'без проверки','JSV_NONE',''),
(7,20040511184701,20040511183018,20040511183018,0,0,7,'слово из русских букв','JSV_TF_RLETTERS','/[а-я]/i'),
(8,20040511184321,20040511183036,20040511183036,0,0,5,'телефон','JSV_TF_PHONE','/./'),
(9,20040513155919,20040511184006,20040511184006,1,0,10,'выбрано значение != 0','JSV_SELECT_SELECTED','/[1-9][0-9]*/'),
(10,20040513155952,20040511184043,20040511184043,1,0,12,'выбран хоть один чекбокс из группы','JSV_MULTICHECKBOX_CHECKED',''),
(11,20040511184415,20040511184125,20040511184125,0,0,11,'выбрано хоть одно значение из multi#select','JSV_MULTISELECT_SELECTED','позже');

#select * from tasq_jsvalidator;
