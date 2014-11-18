use tasq;
#set names cp1251;

DROP TABLE IF EXISTS tasq_currency;
CREATE TABLE tasq_currency (
	id				INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	date_updated	TIMESTAMP(14),
	date_created	TIMESTAMP(14),
	date_published	TIMESTAMP(14),
	published		TINYINT UNSIGNED NOT NULL DEFAULT 1,
	deleted			TINYINT UNSIGNED NOT NULL DEFAULT 0,
	manorder		INTEGER UNSIGNED NOT NULL DEFAULT 0,
	ident			VARCHAR(250) NOT NULL DEFAULT '',

	hashkey			VARCHAR(250) NOT NULL DEFAULT '',

	exchrate_rub	FLOAT UNSIGNED NOT NULL DEFAULT 0,
	exchrate_rub_multiplier	FLOAT UNSIGNED NOT NULL DEFAULT 1,
	date_exchrate_rub	TIMESTAMP(14),

	date_expiration	TIMESTAMP(14),
	expiration_minutes	FLOAT UNSIGNED NOT NULL DEFAULT 60,
	scriptname_updated	VARCHAR(250) NOT NULL DEFAULT '',

	src_href		VARCHAR(250) NOT NULL DEFAULT '',
	src_content		TEXT,
	exchrate_regexp	TEXT,
	daterate_regexp	TEXT,

	comment			VARCHAR(250) NOT NULL DEFAULT '',


	exchrate_eur	FLOAT UNSIGNED NOT NULL DEFAULT 0,
	import_href		VARCHAR(250) NOT NULL DEFAULT '',
	import_content		TEXT,
	import_regexp		TEXT,



	PRIMARY KEY(id)
);

#desc tasq_currency;

#insert into tasq_currency (id, manorder, date_created, ident, src_content, exchrate_regexp, daterate_regexp) values
#	(1, 1, CURRENT_TIMESTAMP, 'USD', ),
#	(2, 2, CURRENT_TIMESTAMP, 'EUR'),
#	(3, 3, CURRENT_TIMESTAMP, 'руб')
#	;
#update tasq_currency set hashkey='USD' where id=1;
#update tasq_currency set hashkey='EUR' where id=2;
#update tasq_currency set hashkey='RUB' where id=3;




INSERT INTO `tasq_currency` (`id`, `date_updated`, `date_created`, `date_published`, `published`, `deleted`, `manorder`, `ident`, `exchrate_eur`, `exchrate_rub`, `hashkey`, `date_expiration`, `expiration_minutes`, `scriptname_updated`, `exchrate_rub_multiplier`, `date_exchrate_rub`, `import_href`, `import_content`, `import_regexp`, `comment`, `src_href`, `exchrate_regexp`, `daterate_regexp`, `src_content`) VALUES (1,'2009-01-14 22:40:27','2007-09-12 01:16:13','2009-01-14 22:40:27',1,0,1,'$',0,31.56,'USD','2009-01-14 23:40:25',60,'/index.php',1,'2008-11-10 00:15:10','','','','','http://www.cbr.ru/scripts/XML_daily.asp','<Valute ID=\\\"R01235\\\">\\\\s*<NumCode>840</NumCode>\\\\s*<CharCode>USD</CharCode>\\\\s*<Nominal>1</Nominal>\\\\s*<Name>Доллар США</Name>\\\\s*<Value>(.*)</Value>\\\\s*</Valute>','<ValCurs Date=\\\"08.11.2008\\\" name=\\\"Foreign Currency Market\\\">\r\n','<?xml version=\"1.0\" encoding=\"windows-1251\" ?>\r\n<ValCurs Date=\"15.01.2009\" name=\"Foreign Currency Market\">\r\n<Valute ID=\"R01010\">\r\n	<NumCode>036</NumCode>\r\n	<CharCode>AUD</CharCode>\r\n	<Nominal>1</Nominal>\r\n	<Name>Австралийский доллар</Name>\r\n	<Value>21,3956</Value>\r\n</Valute>\r\n<Valute ID=\"R01035\">\r\n	<NumCode>826</NumCode>\r\n	<CharCode>GBP</CharCode>\r\n	<Nominal>1</Nominal>\r\n	<Name>Фунт стерлингов Соединенного королевства</Name>\r\n	<Value>46,3577</Value>\r\n</Valute>\r\n<Valute ID=\"R01090\">\r\n	<NumCode>974</NumCode>\r\n	<CharCode>BYR</CharCode>\r\n	<Nominal>1000</Nominal>\r\n	<Name>Белорусских рублей</Name>\r\n	<Value>11,9145</Value>\r\n</Valute>\r\n<Valute ID=\"R01215\">\r\n	<NumCode>208</NumCode>\r\n	<CharCode>DKK</CharCode>\r\n	<Nominal>10</Nominal>\r\n	<Name>Датских крон</Name>\r\n	<Value>56,3902</Value>\r\n</Valute>\r\n<Valute ID=\"R01235\">\r\n	<NumCode>840</NumCode>\r\n	<CharCode>USD</CharCode>\r\n	<Nominal>1</Nominal>\r\n	<Name>Доллар США</Name>\r\n	<Value>31,5616</Value>\r\n</Valute>\r\n<Valute ID=\"R01239\">\r\n	<NumCode>978</NumCode>\r\n	<CharCode>EUR</CharCode>\r\n	<Nominal>1</Nominal>\r\n	<Name>Евро</Name>\r\n	<Value>41,9706</Value>\r\n</Valute>\r\n<Valute ID=\"R01310\">\r\n	<NumCode>352</NumCode>\r\n	<CharCode>ISK</CharCode>\r\n	<Nominal>100</Nominal>\r\n	<Name>Исландских крон</Name>\r\n	<Value>25,0112</Value>\r\n</Valute>\r\n<Valute ID=\"R01335\">\r\n	<NumCode>398</NumCode>\r\n	<CharCode>KZT</CharCode>\r\n	<Nominal>100</Nominal>\r\n	<Name>Казахских тенге</Name>\r\n	<Value>26,0109</Value>\r\n</Valute>\r\n<Valute ID=\"R01350\">\r\n	<NumCode>124</NumCode>\r\n	<CharCode>CAD</CharCode>\r\n	<Nominal>1</Nominal>\r\n	<Name>Канадский доллар</Name>\r\n	<Value>25,9916</Value>\r\n</Valute>\r\n<Valute ID=\"R01375\">\r\n	<NumCode>156</NumCode>\r\n	<CharCode>CNY</CharCode>\r\n	<Nominal>10</Nominal>\r\n	<Name>Китайских юаней Жэньминьби</Name>\r\n	<Value>46,1906</Value>\r\n</Valute>\r\n<Valute ID=\"R01535\">\r\n	<NumCode>578</NumCode>\r\n	<CharCode>NOK</CharCode>\r\n	<Nominal>10</Nominal>\r\n	<Name>Норвежских крон</Name>\r\n	<Value>44,7905</Value>\r\n</Valute>\r\n<Valute ID=\"R01589\">\r\n	<NumCode>960</NumCode>\r\n	<CharCode>XDR</CharCode>\r\n	<Nominal>1</Nominal>\r\n	<Name>СДР (специальные права заимствования)</Name>\r\n	<Value>47,8180</Value>\r\n</Valute>\r\n<Valute ID=\"R01625\">\r\n	<NumCode>702</NumCode>\r\n	<CharCode>SGD</CharCode>\r\n	<Nominal>1</Nominal>\r\n	<Name>Сингапурский доллар</Name>\r\n	<Value>21,2665</Value>\r\n</Valute>\r\n<Valute ID=\"R01700J\">\r\n	<NumCode>949</NumCode>\r\n	<CharCode>TRY</CharCode>\r\n	<Nominal>1</Nominal>\r\n	<Name>Новая турецкая лира</Name>\r\n	<Value>19,9378</Value>\r\n</Valute>\r\n<Valute ID=\"R01720\">\r\n	<NumCode>980</NumCode>\r\n	<CharCode>UAH</CharCode>\r\n	<Nominal>10</Nominal>\r\n	<Name>Украинских гривен</Name>\r\n	<Value>35,8858</Value>\r\n</Valute>\r\n<Valute ID=\"R01770\">\r\n	<NumCode>752</NumCode>\r\n	<CharCode>SEK</CharCode>\r\n	<Nominal>10</Nominal>\r\n	<Name>Шведских крон</Name>\r\n	<Value>38,6495</Value>\r\n</Valute>\r\n<Valute ID=\"R01775\">\r\n	<NumCode>756</NumCode>\r\n	<CharCode>CHF</CharCode>\r\n	<Nominal>1</Nominal>\r\n	<Name>Швейцарский франк</Name>\r\n	<Value>28,3369</Value>\r\n</Valute>\r\n<Valute ID=\"R01820\">\r\n	<NumCode>392</NumCode>\r\n	<CharCode>JPY</CharCode>\r\n	<Nominal>100</Nominal>\r\n	<Name>Японских иен</Name>\r\n	<Value>35,1681</Value>\r\n</Valute>\r\n</ValCurs>\r\n'),(2,'2009-01-14 22:40:27','2007-09-12 01:16:13','2009-01-14 22:40:27',1,0,2,'EUR',0,41.97,'EUR','2009-01-14 23:40:25',60,'/index.php',1,'2009-01-15 00:00:00','http://www.cbr.ru/scripts/XML_dynamic.asp?VAL_NM_RQ=R01235','','','','http://www.cbr.ru/scripts/XML_daily.asp','<Valute ID=\\\"R01239\\\">\\\\s*<NumCode>978</NumCode>\\\\s*<CharCode>EUR</CharCode>\\\\s*<Nominal>1</Nominal>\\\\s*<Name>Евро</Name>\\\\s*<Value>(.*)</Value>\\\\s*</Valute>','<ValCurs Date=\\\"(.*)\\\" name=\\\"Foreign Currency Market\\\">','<?xml version=\"1.0\" encoding=\"windows-1251\" ?>\r\n<ValCurs Date=\"15.01.2009\" name=\"Foreign Currency Market\">\r\n<Valute ID=\"R01010\">\r\n	<NumCode>036</NumCode>\r\n	<CharCode>AUD</CharCode>\r\n	<Nominal>1</Nominal>\r\n	<Name>Австралийский доллар</Name>\r\n	<Value>21,3956</Value>\r\n</Valute>\r\n<Valute ID=\"R01035\">\r\n	<NumCode>826</NumCode>\r\n	<CharCode>GBP</CharCode>\r\n	<Nominal>1</Nominal>\r\n	<Name>Фунт стерлингов Соединенного королевства</Name>\r\n	<Value>46,3577</Value>\r\n</Valute>\r\n<Valute ID=\"R01090\">\r\n	<NumCode>974</NumCode>\r\n	<CharCode>BYR</CharCode>\r\n	<Nominal>1000</Nominal>\r\n	<Name>Белорусских рублей</Name>\r\n	<Value>11,9145</Value>\r\n</Valute>\r\n<Valute ID=\"R01215\">\r\n	<NumCode>208</NumCode>\r\n	<CharCode>DKK</CharCode>\r\n	<Nominal>10</Nominal>\r\n	<Name>Датских крон</Name>\r\n	<Value>56,3902</Value>\r\n</Valute>\r\n<Valute ID=\"R01235\">\r\n	<NumCode>840</NumCode>\r\n	<CharCode>USD</CharCode>\r\n	<Nominal>1</Nominal>\r\n	<Name>Доллар США</Name>\r\n	<Value>31,5616</Value>\r\n</Valute>\r\n<Valute ID=\"R01239\">\r\n	<NumCode>978</NumCode>\r\n	<CharCode>EUR</CharCode>\r\n	<Nominal>1</Nominal>\r\n	<Name>Евро</Name>\r\n	<Value>41,9706</Value>\r\n</Valute>\r\n<Valute ID=\"R01310\">\r\n	<NumCode>352</NumCode>\r\n	<CharCode>ISK</CharCode>\r\n	<Nominal>100</Nominal>\r\n	<Name>Исландских крон</Name>\r\n	<Value>25,0112</Value>\r\n</Valute>\r\n<Valute ID=\"R01335\">\r\n	<NumCode>398</NumCode>\r\n	<CharCode>KZT</CharCode>\r\n	<Nominal>100</Nominal>\r\n	<Name>Казахских тенге</Name>\r\n	<Value>26,0109</Value>\r\n</Valute>\r\n<Valute ID=\"R01350\">\r\n	<NumCode>124</NumCode>\r\n	<CharCode>CAD</CharCode>\r\n	<Nominal>1</Nominal>\r\n	<Name>Канадский доллар</Name>\r\n	<Value>25,9916</Value>\r\n</Valute>\r\n<Valute ID=\"R01375\">\r\n	<NumCode>156</NumCode>\r\n	<CharCode>CNY</CharCode>\r\n	<Nominal>10</Nominal>\r\n	<Name>Китайских юаней Жэньминьби</Name>\r\n	<Value>46,1906</Value>\r\n</Valute>\r\n<Valute ID=\"R01535\">\r\n	<NumCode>578</NumCode>\r\n	<CharCode>NOK</CharCode>\r\n	<Nominal>10</Nominal>\r\n	<Name>Норвежских крон</Name>\r\n	<Value>44,7905</Value>\r\n</Valute>\r\n<Valute ID=\"R01589\">\r\n	<NumCode>960</NumCode>\r\n	<CharCode>XDR</CharCode>\r\n	<Nominal>1</Nominal>\r\n	<Name>СДР (специальные права заимствования)</Name>\r\n	<Value>47,8180</Value>\r\n</Valute>\r\n<Valute ID=\"R01625\">\r\n	<NumCode>702</NumCode>\r\n	<CharCode>SGD</CharCode>\r\n	<Nominal>1</Nominal>\r\n	<Name>Сингапурский доллар</Name>\r\n	<Value>21,2665</Value>\r\n</Valute>\r\n<Valute ID=\"R01700J\">\r\n	<NumCode>949</NumCode>\r\n	<CharCode>TRY</CharCode>\r\n	<Nominal>1</Nominal>\r\n	<Name>Новая турецкая лира</Name>\r\n	<Value>19,9378</Value>\r\n</Valute>\r\n<Valute ID=\"R01720\">\r\n	<NumCode>980</NumCode>\r\n	<CharCode>UAH</CharCode>\r\n	<Nominal>10</Nominal>\r\n	<Name>Украинских гривен</Name>\r\n	<Value>35,8858</Value>\r\n</Valute>\r\n<Valute ID=\"R01770\">\r\n	<NumCode>752</NumCode>\r\n	<CharCode>SEK</CharCode>\r\n	<Nominal>10</Nominal>\r\n	<Name>Шведских крон</Name>\r\n	<Value>38,6495</Value>\r\n</Valute>\r\n<Valute ID=\"R01775\">\r\n	<NumCode>756</NumCode>\r\n	<CharCode>CHF</CharCode>\r\n	<Nominal>1</Nominal>\r\n	<Name>Швейцарский франк</Name>\r\n	<Value>28,3369</Value>\r\n</Valute>\r\n<Valute ID=\"R01820\">\r\n	<NumCode>392</NumCode>\r\n	<CharCode>JPY</CharCode>\r\n	<Nominal>100</Nominal>\r\n	<Name>Японских иен</Name>\r\n	<Value>35,1681</Value>\r\n</Valute>\r\n</ValCurs>\r\n'),(3,'2008-02-12 22:56:22','2007-09-12 01:16:13','0000-00-00 00:00:00',1,0,3,'руб',0,1,'RUB','0000-00-00 00:00:00',60,'',1,'0000-00-00 00:00:00','','','','','','','',''),(4,'2008-07-20 23:33:54','2008-07-20 23:33:54','0000-00-00 00:00:00',1,0,4,'USD',0,0,'','0000-00-00 00:00:00',60,'',1,'0000-00-00 00:00:00','','','','','','','','');









#select * from currency;

#alter table tasq_currency add exchrate_rub FLOAT UNSIGNED NOT NULL DEFAULT 0;
#alter table tasq_currency add hashkey VARCHAR(250) NOT NULL DEFAULT '';


#alter table tasq_currency add date_expiration	TIMESTAMP(14);
#alter table tasq_currency add expiration_minutes	FLOAT UNSIGNED NOT NULL DEFAULT 60;
#alter table tasq_currency add scriptname_updated	VARCHAR(250) NOT NULL DEFAULT '';
#alter table tasq_currency add exchrate_rub_multiplier	FLOAT UNSIGNED NOT NULL DEFAULT 1;
#alter table tasq_currency add date_exchrate_rub TIMESTAMP(14);

#alter table tasq_currency add comment VARCHAR(250) NOT NULL DEFAULT '';

#alter table tasq_currency add src_href VARCHAR(250) NOT NULL DEFAULT '';
#alter table tasq_currency add src_content TEXT;
#alter table tasq_currency add exchrate_regexp TEXT;
#alter table tasq_currency add daterate_regexp TEXT;
