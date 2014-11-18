use tasq;
#set names cp1251;

DROP TABLE IF EXISTS tasq_img;
CREATE TABLE tasq_img (
	id				INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	date_updated	TIMESTAMP,
	date_created	TIMESTAMP,
	date_published	TIMESTAMP,
	published		TINYINT UNSIGNED NOT NULL DEFAULT 0,
	deleted			TINYINT UNSIGNED NOT NULL DEFAULT 0,
	manorder		INTEGER UNSIGNED NOT NULL DEFAULT 0,
	ident			VARCHAR(250) NOT NULL DEFAULT '',

	img				VARCHAR(255) NOT NULL DEFAULT '',
	img_w			INTEGER UNSIGNED NOT NULL DEFAULT 0,
	img_h			INTEGER UNSIGNED NOT NULL DEFAULT 0,
	img_txt			VARCHAR(255) NOT NULL DEFAULT '',

	img_big			VARCHAR(255) NOT NULL DEFAULT '',
	img_big_w		INTEGER UNSIGNED NOT NULL DEFAULT 0,
	img_big_h		INTEGER UNSIGNED NOT NULL DEFAULT 0,
	img_big_txt		VARCHAR(255) NOT NULL DEFAULT '',

	owner_entity	VARCHAR(250) NOT NULL DEFAULT '',
	owner_entity_id	INTEGER UNSIGNED NOT NULL DEFAULT 1,
	imgtype			INTEGER UNSIGNED NOT NULL DEFAULT 1,

	img_src			VARCHAR(250) NOT NULL DEFAULT '',
	img_big_src		VARCHAR(250) NOT NULL DEFAULT '',

	img_main		TINYINT UNSIGNED NOT NULL DEFAULT 0,

	crc32			INTEGER UNSIGNED NOT NULL DEFAULT 0,

	date_faceted	TIMESTAMP,
	faceted			TINYINT UNSIGNED NOT NULL DEFAULT 0,
	faceting		TINYINT UNSIGNED NOT NULL DEFAULT 0,

	PRIMARY KEY(id)
	, key (owner_entity, owner_entity_id)
	, key (imgtype)
	, key (published, deleted)
	, key (img_txt)
	, key (img_big_txt)
);

#desc tasq_img;
#select * from tasq_img;


#alter table tasq_img add img_main TINYINT UNSIGNED NOT NULL DEFAULT 0;
#alter table tasq_img add crc32 INTEGER UNSIGNED NOT NULL DEFAULT 0;

#0118
#alter table tasq_img add date_faceted TIMESTAMP;
#alter table tasq_img add faceted TINYINT UNSIGNED NOT NULL DEFAULT 0;
#alter table tasq_img add faceting TINYINT UNSIGNED NOT NULL DEFAULT 0;

alter table tasq_img add key(faceted);
alter table tasq_img add key(faceting);

#0217
#UPDATE `tasq_img` SET faceted =1, faceting =0
