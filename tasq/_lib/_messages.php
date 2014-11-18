<?

// chucha 27-09-2009:
// I use global variables in message bundle because it is easier to operate while coding;
// define("_KEY", "VALUE") can not be used in $a = "foo _KEY bar"; or $a = <<< EOT;

$msg_tag_shortcut = "<img src='img/shortcut.gif' width=7 height=7 style='border:0px solid #eeeeee' align=absmiddle hspace=2 vspace=2>";

// system messages, used in _lib/_*.php
switch ($lang_current) {
	case "ru":
	case "fr":

		if (!isset($site_name)) $site_name = "Webie.CMS ��������� ����";
		
		// $menu_bo in _constants.php has empty values because of language dependency (filled at bottom of _messages.php)
		if (!isset($entity_list))
		$entity_list = array (
			"issue" => "�������",
			"m2m_issue_rating" => "�������,�����,�����",
			"m2m_issue_replic" => "����������",

			"article" => "������",
			"m2m_article_rating" => "�������,�����,�����",
			"m2m_article_replic" => "����������",

			"agroup" => "�������",
			"m2m_agroup_rating" => "�������,�����,�����",
			"m2m_agroup_replic" => "����������",
		
			"person" => "�������",
			"m2m_person_rating" => "�������,�����,�����",
			"m2m_person_replic" => "����������",
		
		
			"cgroup" => "������ ��������",
			"customer" => "�������",
			"corder" => "������",
			"shop" => "������� �������",
		
			"=product-lost.php" => "�������� ��� ������",
			"product" => "��������",
			"pgroup" => "������ ���������",
			"=pgroup-onindex.php" => "������� ����� �� �������",
			"supplier" => "�������������",
			"pmodel" => "������",
			"currency" => "������",
		
			"spart" => "��������",
			"sgroup" => "������ ���������",

			"m2m_product_rating" => "�������,�����,�����",
			"m2m_product_replic" => "����������",
		
			"news" => "�������",
			"ngroup" => "��������� �����",
			"=../mailer/user/login.php?mlist=1&l_login=1234&l_passwd=1234&mode=login' target='_blank" => "��������",
		
			"faq" => "FAQ",
			"fgroup" => "��������� FAQ",

			"project" => "����",
			"tgroup" => "������",
			"task" => "����",
			"tstatus" => "������� ������",
				
			"banner" => "�������",
			"bgroup" => "������ ��������",
		
			"constant" => "���������",
			"cached" => "���",
			"mtpl" => "������� �����",
			"sentlog" => "������������",
			"imgtype" => "���� ��������",
			"img" => "��� �������� ������",
			"change_word" => "�������� �����",
			"jsvalidator" => "�������� �����",
		
			"icwhose" => "������",
			"ic" => "������� � ������",
			"icdict" => "�����������",
			"icdictcontent" => "�������� �����������",
			"ictype" => "���� ����� �����",
			"icsheet" => "����������� ������",
		
			"mmenu" => "�������� �����",
			"=mmenu-legend.php?parent_id=2" => "������� �����",
			"=mmenu-legend.php" => "������� ������� � �������",
		);
		
		
		if (!isset($add_entity_msg_list))
		$add_entity_msg_list = array (
			"issue" => "����� ������",
			"article" => "����� ������",
			"agroup" => "����� �������",
			"person" => "����� �������",
			"m2m_article_rating" => "����� �������,�����,�����",
			"m2m_article_replic" => "����� �������",

			"customer" => "������ �������",
			"�group" => "����� ������ ��������",
		
			"pimportsource" => "����� ��������� ������ ������� ������ �����-�����",
			"product" => "����� �������",
			"pgroup" => "����� ������ ���������",
			"ugroup" => "����� ������� ������",
			"supplier" => "������ �������������",
			"pmodel" => "����� ������",
		
			"country" => "����� ������",
			"currency" => "����� ������",
		
			"m2m_product_rating" => "����� �������,�����,�����",
			"m2m_product_replic" => "����� �������",
		
			"taxrate" => "����� ������ ������",
			"package" => "����� ��������",
			"saleunit" => "����� ������� ���������",
			"shiptype" => "����� ��� ��������",
			"pclass" => "����� ��� ��������",
		
			"shop" => "����� ������ �������",
		
			"banner" => "����� ������",
			"bgroup" => "����� ������ ��������",
			"news" => "�������",
			"ngroup" => "����� ��������� �����",
		
			"faq" => "����� FAQ",
			"fgroup" => "����� ��������� FAQ",

			"project" => "����� ����",
			"tgroup" => "����� ������",
			"task" => "����� ����",
			"tstatus" => "����� ������ �����",
		
			"constant" => "����� ���������",
			"cached" => "����� ������ � ����",
			"mtpl" => "����� ������ ������",
			"imgtype" => "����� ��� ��������",
			"img" => "����� ��������",
			"jsvalidator" => "����� JSValidator",
		
			"icwhose" => "����� ������",
			"ic" => "����� ������ � ������",
			"icdict" => "����� ����������",
			"icdictcontent" => "����� �������� �����������",
			"ictype" => "����� ��� ����� �����",
		
			"mmenu" => "����� ����� ����",
		);
		
		
		if (!isset($new_entity_ident_list))
		$new_entity_ident_list = array (
			"issue" => "����� ������",
			"article" => "����� ������",
			"agroup" => "����� �������",
			"person" => "����� �������",
			"m2m_article_rating" => "����� �������,�����,�����",
			"m2m_article_replic" => "����� �������",

			"customer" => "����� ������",
			"cgroup" => "����� ������ ��������",
			"pimportsource" => "����� ��������� ������ ������� ������ �����-�����",
			"product" => "����� �������",
			"pgroup" => "����� ������ ���������",
			"ugroup" => "����� ������� ������",
			"supplier" => "������ �������������",
			"pmodel" => "����� ������",
		
			"country" => "����� ������",
			"currency" => "����� ������",
		
			"m2m_product_rating" => "����� �������,�����,�����",
			"m2m_product_replic" => "����� �������",
		
		
			"taxrate" => "����� ������ ������",
			"package" => "����� ��������",
			"saleunit" => "����� ������� ���������",
			"shiptype" => "����� ��� ��������",
			"pclass" => "����� ��� ��������",
		
			"shop" => "����� ������ �������",
				
			"banner" => "����� ������",
			"bgroup" => "����� ������ ��������",
			"news" => "�������",
			"ngroup" => "����� ��������� �����",
		
			"faq" => "����� FAQ",
			"fgroup" => "����� ��������� FAQ",
		
			"project" => "����� ����",
			"tgroup" => "����� ������",
			"task" => "����� ����",
			"tstatus" => "����� ������ ������",
		
			"constant" => "����� ���������",
			"cached" => "����� ������ � ����",
			"mtpl" => "����� ������ ������",
			"imgtype" => "����� ��� ��������",
			"jsvalidator" => "����� JSValidator",
		
			"icwhose" => "����� ������",
			"ic" => "����� ���� � ������",
			"icdict" => "����� ����������",
			"icdictcontent" => "����� �������� �����������",
			"ictype" => "����� ��� ����� �����",
		
			"mmenu" => "����� ����� ����",
		);

		$msg_fields = array (
// common
			"id" => "�",
			"ident" => "��������",
			"annotation" => "���������",
			"annotation-graycomment" => "������� ��� ������� ���� (� ����� ���������� ����, ������ ��� ������� ������)",
			"hashkey" => "����",
			"date_created" => "���� ��������",
			"date_updated" => "���� ����������",
//			"date_published" => "���� ����������",		// too many entities where data_published is date of ...
			"date_published" => "����",
			"img_cnt" => "����",
			"published-list" => "�����",
			"published-edit" => "������������",
			"i_published-list" => "������",
			"i_published-edit" => "�� �������",

			"~delete" => "����",
			"parent_id" => "��������",
			"group" => "������",
			"comment" => "����������<br>�����������",

			"pagetitle" => "��������� ��������",
			"title" => "��������� ��������",
			"meta_keywords" => "Meta Keywords",
			"meta_description" => "Meta Description",

// "explainations are too complicated"
//			"brief-edit" => "������<br><br>����� � ������<br>����������<br>���������<br><br>(������� � �����,<br>��������<br> ����� ������...)",
//			"content-edit" => "��������<br><br>����� �<br>�������� ������,<br>����� ������� ...",

			"brief" => "����",
			"content" => "�������",

			"date_lastclick" => "����.����",
			"remote_address" => "IP �����������",
			"lastip" => "IP ����.�����",
			"lastsid" => "������ cookie",
			"idrandom" => "IDrandom",

			"additional_layer" => "�������������",
			"service_layer" => "��������� ����",
			"filesattached_layer" => "���������� �����",

			"product_iccontent" => "�������� ������",

			"filesattached_layer_open" => "����������",
			"file1" => "���� 1",
			"file1_comment" => "����������� 1",
			"file2" => "���� 2",
			"file2_comment" => "����������� 2",
			"file3" => "���� 3",
			"file3_comment" => "����������� 3",
			"file4" => "���� 4",
			"file4_comment" => "����������� 4",
			"file5" => "���� 5",
			"file5_comment" => "����������� 5",

// customer
			"contract_discount" => "������,&nbsp;%",
			"login" => "�����",
			"passwd" => "������",
			"manager_name" => "�������",
			"cgroup-list" => "������",			// next taking from entity_list_single
//			"customer-ident" => "������� ����",	// next taking from entity_list_single
			"phone" => "�������",
			"address" => "�����",
			"fax" => "����",
			"contract_number" => "����� ��������",
			"tin" => "���",
			"customer_sheet" => "������ ������������",
			"login_layer" => "�����, ������, ������",
			"customer-published-list" => "����",
			"customer-published-edit" => "���� ������",
			"customer-date_created-edit" => "�����������",
			"customer-date_updated-edit" => "����������",

// product
			"pgroup-list" => "������",			// next taking from entity_list_single
			"pgroup-edit" => "������<br>���������",			// next taking from entity_list_single
			//"article" => "�������",
			"price_1" => "����",
			"price_2" => "���� 2",
			"price_3" => "���� 3",
			"pricecomment_1" => "����������� � ���� 1",
			"pricecomment_2" => "����������� � ���� 2",
			"pricecomment_3" => "����������� � ���� 3",

			"product-brief-edit" => "������<br><br>� ������ �������<br>������",
			"product-content-edit" => "��������<br><br>� �������� ������,<br>������ �� ����",
					
			"weight" => "���",
			"hits" => "Hits",
			"hits-edit" => "����������",
			"hits-graycomment" => "����� ��������� � ����; ��������� ���� ������",	// � [$entity_list[$entity]]

			"briefful_layer" => "������� ��������, ������ ��������",
			"news4product_layer" => "�������� ������ � ��������",
//			"product-date_published" => "����",
			
			"archived-list" => "�����",
			"archived" => "� ������",
			"is_new" => "�������",
			"banner_top" => "������ � �����",

			"sold" => "Sold",
			"disclaimer_list" => "Disclaimer for product list",
			"disclaimer_pcard" => "Disclaimer for product card",

// pgroup
			"divclass" => "����� DIV",
			//"file1" => "�������� � ����",
			//"file1-graycomment" => "������ ��� �������� �����",

// currency
			"date_exchrate_rub" => "���� �����",
			"exchrate_rub" => "���� ��������<br>� �����",
			"exchrate_rub_multiplier" => "���������",
			"exchrate_rub_multiplied" => "����",
			"currency-date_updated" => "����������",
			"src_href" => "���� �����",
			"src_content" => "content<br>cached",
			"exchrate_regexp" => "exchrate_regexp",
			"daterate_regexp" => "daterate_regexp",

// m2m_product_rating
			"m2m_product_rating-customer_ident" => "��� ������",
			"rating" => "������",
			"opinion" => "��� �����������",
			"opinion-edit" => "��� �����������,<br>��� ���",
			"wish" => "�����������",
			"m2m_product_rating-content" => "�����������<br>����������",

// news
//			"news-date_published" => "����",
			"ngroup_ident-list" => "�����",
			"rsss_ident" => "RSS-��������",
			"rss_published" => "�&nbsp;RSS",
			"news-brief-edit" => "����",
			"news-content-edit" => "����� �������",

			"srcurl" => "<a href='#SRCURL#' target=_blank>��������</a>",
			"hrefto" => "<a href='#HREFTO#' target=_blank>������</a>",

// faq
			"cname" => "���",
			"email" => "Email",
			"answer_sent" => "����",
			"answer_sent-edit" => "���������",
			"answer_sent-graycomment" => "���� ��������� ��������� ����� ������������ �� ���������� �� email'� - ���������� ��� ����� � ���������",
			"subject" => "����",
			"contact" => "������ ��������",
			"contact-edit" => "������<br>��������",
			"faq-content" => "����� ������������",
			"faq-fgroup-list" => "��������� FAQ",
			"faq-fgroup-graycomment" => "������� �����!",

// constant
			"constant-ident" => "���",
			"constant-content" => "��������",

// cached
			"cached-date_published" => "������������",
			"expiration_minutes" => "�������, ���",
			"expiration_minutes-list" => "���",
			"expiration_minutes-graycomment" => "��� ���. ����������: ���� �������� = ������ + ������� ���",
			"date_expiration" => "��������",
			"scriptname_updated" => "�������",
			"scriptname_created" => "������",

// mtpl
			"subject" => "���� ������",
			"body" => "����� ������",
			"admtail" => "��������<br>���������",
			"admtail-graycomment" => "����� �� ����� ���� ����������� � ������; ����� ���� ������������ #HTTP_HOST# � ��.",
			"sender" => "�����������",
			"rcptto" => "����������",
			"rcptto-graycomment" => "email, email... ���������� ���������� ����� ����������� ������ � [��������� ���������]",
			"sentmsg" => "���� �������",
			"sentmsg-graycomment" => "��������� �� ������ ��� �������� �������� (javascript:alert)",

			"savesentlog" => "SentLog",
			"savesentlog-edit" => "���������<br>������������",
			"savesentlog-graycomment" => "� ������ �������� ������, ����� ����������� � <a href='sentlog.php'>������������</a>",			


// sentlog
			"sentlog-content-list" => "���������� ������",
			"sentlog-content-edit" => "����������<br>������",

// imgtype
			"imgtype-content" => "�����������",
			"imglimit-list" => "�����",
			"imglimit" => "���-�� ��������",
			"imglimit-graycomment" => "����� �� ���-�� ���������� ��������; 0 - �� ����������",

			"imgsmall_layer" => "��������� �������� � ���������",
			"img_present" => "���� ���������",
			"img_present-graycomment" => "��������� �� ���������->[Browse]",
			"img_newqnty" => "���������� ������ ����� (Browse)",
			"img_newqnty-graycomment" => "���� ������� �������� ��������� ����� �����; 0=1",
			"img_zip_present" => "���� �� ���� ZIP",
			"img_zip_present-graycomment" => "��������� �� ���������->[Browse] ZIP ��� [�����]",
			"img_url_present" => "���� �� ���� URL",
			"img_url_present-graycomment" => "��������� �� ���������->URL ��� ������� � [�����]",
			"img_txt_present" => "���� �������",
			"img_txt_present-graycomment" => "������� �� ���� ��� ������� ���������",
			"img_txt_eq_fname" => "������� [������� = ��� �����]",
			"img_txt_eq_fname-graycomment" => "������� �� �� ��������� [������� = ��� �����] ��� �����",
			"resize_published" => "������� [���������] �� [�������]",
			"resize_published-graycomment" => "��������� �� ���� ���� � ������������",
			"resize_default_checked" => "��������� ������� [�������]",
			"resize_default_checked-graycomment" => "������� �� �� ��������� [������� ������] ��� �����",
			"resize_default_qlty" => "�������� ������� [���������]",
			"resize_default_qlty-graycomment" => "�������� ���������� JPEG: 0&#8230;100",
			"resize_default_width" => "������ ������� [���������]",
			"resize_default_height" => "������ ������� [���������]",

			"imgbig_layer" => "������� �������� � ���������",
			"img_big_present" => "���� �������",
			"img_big_present-graycomment" => "��������� �� �������->[Browse]",
			"img_big_newqnty" => "���������� ������ ����� (Browse)",
			"img_big_newqnty-graycomment" => "���� ������� �������� ��������� ����� �����; 0=1",
			"img_big_zip_present" => "���� �� ���� ZIP",
			"img_big_zip_present-graycomment" => "��������� �� �������->[Browse] ZIP ��� [�����]",
			"img_big_url_present" => "���� �� ���� URL",
			"img_big_url_present-graycomment" => "��������� �� �������->URL ��� ������� � [�����]",
			"img_big_txt_present" => "���� �������",
			"img_big_txt_present-graycomment" => "������� �� ���� ��� ������� �������",
			"img_big_txt_eq_fname" => "������� [������� = ��� �����]",
			"img_big_txt_eq_fname-graycomment" => "������� �� �� ��������� [������� = ��� �����] ��� �����",
			"big_resize_published" => "�������������� [�������] ��� �������",
			"big_resize_published-graycomment" => "��������� �� ���� ���� � ������������",
			"big_resize_default_checked" => "��������� ������� [��������������]",
			"big_resize_default_checked-graycomment" => "������� �� �� ��������� [������� ������] ��� �����",
			"big_resize_default_qlty" => "�������� ������� [�������]",
			"big_resize_default_qlty-graycomment" => "�������� ���������� JPEG: 0&#8230;100",
			"big_resize_default_width" => "������ ������� [�������]",
			"big_resize_default_height" => "������ ������� [�������]",
			

			"imgthumb_layer" => "��������� � ���������",
			"img_thumb_present" => "���� ���������",
			"img_thumb_present-graycomment" => "��������� �� ������ �� ����� ����������� ���������",
			"img_thumb_qlty" => "�������� ������� [���������]",
			"img_thumb_qlty-graycomment" => "�������� ���������� JPEG: 0&#8230;100",
			"img_thumb_width" => "������ ������� [���������]",
			"img_thumb_height" => "������ ������� [���������]",
			
			"imgfirst_layer" => "���������� ������ (�������� � ������ ������� ������)",
			"_autoresize_qlty" => "�������� �����������",
			"_autoresize_qlty-graycomment" => "�������� ���������� JPEG: 0&#8230;100",
			"_autoresize_width" => "������ �����������",
			"_autoresize_height" => "������ �����������",
			"_autoresize_apply" => "��������� ����������",
			"_autoresize_apply-graycomment" => "��������� �� ����������� ��� ���������",
			"_merge_img" => "���������",
			"_merge_img-graycomment" => "����������� �� ������� ���� �� ����������",
			"_merge_alfa" => "������������",
			"_merge_alfa-graycomment" => "������������ ������������� ������ ��������: 0&#8230;100",
			"_merge_type" => "����� ���������",
			"_merge_type-graycomment" => "0=����������������� ������; 1=tile",
			"_merge_apply" => "����������� ���������",
			"_merge_apply-graycomment" => "����� ������� ����-���������, � ����� ������ ��� �����",
			"_autoresize_debug" => "�������� �������",
			"_autoresize_debug-graycomment" => "������� � ���� ��: �����������, ������ �� ������, �����������",
			"_autoresize_tpl_ex" => "������ ���<br>������������",
			"_autoresize_tpl_nex" => "������ ���<br>��������������",
			
			"imgfirst2_layer" => "����������2 ������ (�������� � ������ ������� ������)",
			"imgfirst3_layer" => "����������3 ������ (�������� � ������ ������� ������)",
			"imgfirst4_layer" => "����������4 ������ (�������� � ������ ������� ������)",
			"imgevery_layer" => "���������� ������ (�������� ��������� � �������� ������)",
			"imgevery2_layer" => "����������2 ������ (�������� ��������� � �������� ������)",
			"imgevery3_layer" => "����������3 ������ (�������� ��������� � �������� ������)",
			"imgevery4_layer" => "����������4 ������ (�������� ��������� � �������� ������)",
			
			"imgmsg_layer" => "������� � ���������",
			"msg_ident" => "������� [��������]",
			"msg_add" => "������� [����� ��������]",
			"msg_change" => "������� [�������� ��������]",
			"msg_img" => "������� [���������]",
			"msg_img_big" => "������� [�������]",

			"img_table" => "������� � ����������<br>img ���� �����",
			"merge_seed" => "Merge Seed",
			"merge_seed-graycomment" => "������������ ��� �������������� ��������",
			
			"imgtype-date_updated" => "���������",
			"imgtype-date_updated-graycomment" => "����������� ������ ���� ���� � ����� ����������������",
			
// img
			"~img_tag" => "���",
			"~img_linkedto" => "��������",

// img
			"jsvalidator-content" => "JS RegExp",

// icwhose
//			"icwhose-hashkey" => "���� ��� ��������",
			"bo_only-list" => "��",
			"bo_only" => "������ � ��",
			"bo_only-graycomment" => "������ �� ����� ���������� � ���������",
			"jsv_debug" => "�������",
			
// ic
			"obligatory-list" => "����",
			"obligatory_bo-list" => "������",
			"inbrief-list" => "�������",
			"sorting-list" => "����",
			"published_bo-list" => "������",

			"obligatory" => "������������",
			"obligatory_bo" => "������ � ��",
			"inbrief" => "�������� � �����",
			"inbrief-graycomment" => "�������� ���������� � ������ �������",

			"sorting" => "����������� ��������",
			"sorting-graycomment" => "� ������ ������� ��������� ��� �������� ��� ����������",
			"published_bo" => "������������ � ��",

			"graycomment" => "�����������",
			"ic-icwhose" => "������ - ��� ����",

// icdict
			"icdict-icwhose" => "��� ����������",
			
// icdictcontent
			"label_style-list" => "label style",
			"tf1_width-list" => "TF���",
			"tf1_incolumn-list" => "TF���",
			"icdictcontent-content" => "��������",
			
			"label_style" => "label style=[]",
			"tf1_width" => "����� - textfield �������",
			"tf1_incolumn" => "textfield � ��������� �������",

			"tf1_addtodict" => "��������� ��������<br>�� textfield ����� � ����������",
			"tf1_addedpublished" => "������������ ��<br>����������� ��������",


// mmenu
			"mmenu-hashkey" => "����/������",
			"mmenu-is_heredoc-list" => "�����",
			"mmenu-is_heredoc" => "����������� ��������",
			"mmenu-is_heredoc-graycomment" => "�� = [������������ ������ ����� ��������; ��� ����������� ��������]",

			"mmenu-is_drone-list" => "����",
			"mmenu-is_drone" => "�������",
			"mmenu-is_drone-graycomment" => "� ���� � ���� ������ ���� �� ������ �������� �������",

			"mmenu-content_no_freetext-list" => "TArea",
			"mmenu-published_legend-list" => "�����",
			"mmenu-published_legend" => "� ������� �����",

			"mmenu-published_sitemap-list" => "������",
			"mmenu-published_sitemap" => "�� ����� �����",

			"mmenuimg_layer" => "�������� ��� ������� ����",
			"mmenu-img_header" => "���������",
			"mmenu-img_free" => "������� � ����<br>���������",
			"mmenu-img_mover" => "������� � ����<br>mouseover",
			"mmenu-img_small_free" => "������� � ���� ���������<br>���������",
			"mmenu-img_small_mover" => "������� � ���� ���������<br>mouseover",
			"mmenu-img_small_current" => "������� � ���� ���������<br>�������",
			"mmenu-img_ctx_top" => "����������� �������� ������<br>(�����������)",
			"mmenu-img_ctx_left" => "����������� �������� �����<br>(�����������)",



//			"" => "",
//			"-graycomment" => "",


		);


		// userland constants defined in backoffice/*.php
		$msg_bo_add_passwd_generated = "��� ���������� �� ��������� ������������ ������";		
		$msg_bo_update_passwd_entered = "�������� ��������� ��� ����� ������";
		$msg_bo_face_auth_as_user = "����� ���� �������������";
		$msg_bo_face = "����";
		$msg_bo_email_from_admin = "������ �� ��������������";
		$msg_bo_add_passwd_generation_gray = "� ������ ���������� ������ ������������ ����� � ������ ������������ �������������<br>��� ��������� ������ ������������� ������������ ������ ����� ������������ � md5";
		$msg_bo_sort_offers_onindex = "������������� ��������������� �� �������";

// change_word
		$msg_bo_changeword_import_ident = "������ ����� �� ���� ��";
		$msg_bo_changeword_ucase_lcase = "��������/���������";
		$msg_bo_changeword_replace_seleted = "�������� ����������";
		$msg_bo_changeword_not_found = "�� ������� �� ���� �������� ����� �� �����";

		$msg_bo_changeword_table_id = "�������:ID";
		$msg_bo_changeword_field = "����";
		$msg_bo_changeword_before = "��";
		$msg_bo_changeword_replace = "��������";
		$msg_bo_changeword_what_welook = "��� ����:";
		$msg_bo_changeword_replacement = "�� ��� ��������:";
		$msg_bo_changeword_find = "������";

// ic
		$msg_bo_ic_icdict_values = "�������� �����������";
		$msg_bo_ic_columns_in_table = "������� � �������";

		$msg_bo_ic_string_template = "������-������";
		$msg_bo_ic_html = "HTML ���";
		$msg_bo_ic_width = "������";		
		$msg_bo_ic_height = "������";
		$msg_bo_ic_default = "�� �����";

		$msg_bo_ic_filemax_kb = "��� �� �����, ��";
		$msg_bo_ic_default_state = "Default ���������";
		$msg_bo_ic_no_additional_params = "[��� �������������� ����������]";
		
		$msg_bo_ic_formula = "Formula<br><br>";
		$msg_bo_ic_formula_graycomment = "example: [USD_AUCTION_COST + USD_AUCTION_FEE] where all variables are hashkeys of same sheet; no () and calc priorities";
		
		

			// /userland constants defined in backoffice/*.php



			// _edit_fields.php
		$msg_bo_backtolist = "��������� � ������";
		$msg_bo_F5_title = "���������� ��� ���� �� �� \n\n������� ��� ������������� \n��������� ���� �������� \n����������� �����������";
		$msg_bo_previous_element = "���������� �������";
		$msg_bo_next_element = "��������� �������";

		$msg_bo_switch_to_textarea = "��� TEXTAREA";
		$msg_bo_switch_to_textarea_tip = "����������� �� TEXTAREA; ��������� �� ����������; ����� ���������� ���������� �����";
		$msg_bo_switch_to_freetext = "��� FREETEXT";
		$msg_bo_switch_to_freetext_tip = "����������� �� FREETEXT; ��������� �� ����������; ����� ���������� ���������� �����";
		
		$msg_bo_imgtype_not_defined = "�� �������� ���� ��� ��������";
		$msg_bo_icwhose_not_defined = "��� ������ �� ����������";
		
		$msg_bo_it_change = "��������";
		$msg_bo_it_add = "��������";
		$msg_bo_it_tolist = "� ������";
		

		$msg_bo_add = "��������";
		$msg_bo_add_unable = "������ ��������";
		$msg_bo_save = "���������";
		$msg_bo_updated = "���������";
		$msg_bo_updated_for = "���";

		$msg_bo_subitems = "���������";
		$msg_bo_preview = "��������������� ��������";
			// _edit_fields.php
		
			// _compositebidiect.php
		$msg_bo_subgroup_qnty = "��������";
		$msg_bo_products_in_subgroup = "������� � ���������";
		$msg_bo_products_selected = "������� �������";
		$msg_bo_products_in_subgroup_selected = "�� ��� ������� �������";
		$msg_bo_go_product_editing = "������� � �������������� ������";
		$msg_bo_not_selected = "�� ������";
		$msg_bo_bidirect_reciplink_restored = "������������� �������� �����";
		$msg_bo_bidirect_reciplink_restore_failed = "�� ������� ������������ �������� �����";
		$msg_bo_bidirect_reciplink_was_absent = "�� ���� �����";
		$msg_bo_bidirect_directlink_was_absent = "�� ���� ������ �����";
		$msg_bo_bidirect_directlink_add_failed = "�� ������� �������� ������ �����";
		$msg_bo_bidirect_reverselink_add_failed = "�� ������� �������� �������� �����";
			// /_compositebidiect.php


			// _entity_edit.php
		$msg_bo_required_parameter_missing = "�� ������ ������������ ��������";
		$msg_bo_file_delete_unable = "�������� ����� ����������";
		$msg_bo_file_format_wrong = "�������� ������ �����";
		$msg_bo_database_updated = "���������� ���������";
		$msg_bo_database_swapfield_unable = "������ �����������";
			// /_entity_edit.php


			// _image_layer.php
		$msg_bo_img_preview_only = "������ ��� ������ \n(�� ��� ������������� � ����)";
		$msg_bo_img_original = "��������";
		$msg_bo_img_jpeg_save_optimalq = "�������� ���������� JPEG: 0�100 \n\n����������� �������� 75";
		$msg_bo_img_width_destination = "������ �������� \n\n��� ����������������� ��������������� \n������� ���� �� �������";
		$msg_bo_img_height_destination = "������ �������� \n\n��� ����������������� ��������������� \n������� ���� �� �������";
		$msg_bo_img_big_overwrite = "�������������� [�������]";
		$msg_bo_img_big_overwrite_tip = "������������ [�������] �������� \n�������� �� ���������� \n\n��������� ������ � ������ �������";
		$msg_bo_img_small_create_frombig = "������� [���������] �� [�������]";
		$msg_bo_img_small_create_frombig_tip = "������������ [���������] �������� \n�������� �� [�������] \n\n���� [�������] ����������� \n[���������] �� ���������";
		$msg_bo_img_marker_tip = "Click, [Ctrl-C], [Ctrl+V] � ������ ����� \n\n���� ������, ����������� � �����: \n1. ���������� [���������] �������� � �������� \n2. ��� ����� �� [��������] �������� \n���������� ��������� ������ \n3. � ������ ������ �������� ������������ \n[�������] �������� � ��������";
		$msg_bo_img_published = "������������";
		$msg_bo_img_published_tip = "���� ������ ���������, \n���� �������� ����������� \n����� ������ &#35;IMG#ID#&#35;";
		$msg_bo_img_main = "�������";
		$msg_bo_img_delete = "�������";
		$msg_bo_img_delete_existing = "������� ������������";
		$msg_bo_img_delete_tip = "�������� ��������� \n��������� � �����, \n���������� �����";
		$msg_bo_img_label = "�������";
		$msg_bo_img_label_tip = "������� ��������� ��� ���������, \n������ ���� �������� ����������� \n����� ������ &#35;IMG#ID#&#35;";
		$msg_bo_img_try_dragging = "���������� ���������� ��� �������� \n� �����, ����������� ����� FreeTextBox";

		$msg_bo_img_maker_width = "������ \n\n��� ������� ��������� \n������ ��� ������������� \n������� &#35;IMG#ID#&#35; \n\n��� ��������� ����� � ���� ���� \n�������� ������ �������, \n������������� ����� ������; \n��������������� �������� �������� \n�� ����������";
		$msg_bo_img_maker_height = "������ \n\n��� ������� ��������� \n������ ��� ������������� \n������� &#35;IMG#ID#&#35; \n\n��� ��������� ����� � ���� ���� \n�������� ������ �������, \n������������� ����� ������; \n��������������� �������� �������� \n�� ����������";
		
		$msg_bo_img_upload_tip = "���� ��� �������� ����� ���������� (Browse) \n\n����� ������ ���� [�������] ��������, \n���������������� � ��� ������� \n� ������� [���������] � ���� �������� \n\nPS: ����� �� ���������� �������� - $max_execution_time ������";
		$msg_bo_img_upload_only_one = "����� ����� ������� \n������ ���� ���� �������� \n\n����� ������ \n��������� ����� ��������, \n����������� [����� ��������] \n\n�� ����� ��������� \n� ���� ����� Browse, \n� URL ������������";
		$msg_bo_img_upload_zip_tip = "����� ��������� ��������� ��������, �������� \nZIP-����� ��������, ��� �������� ��������, \n� ������� ����� ������������ ZIP-����� \n\n��������, ������������� � ��������� ������ \n������ - ����� ����� ��������� �� ��� �������� \n\n��������� ������ �� �������������� \n\n��� ����� ������ ��������� [�������] ��������, \n���������������� ������ ��� ������� \n� ������� [���������] �������� � ���� �������� \n\nPS: ����� �� ���������� �������� - $max_execution_time ������";
		$msg_bo_img_upload_url_tip = "����� ������ ��������� ��������, ����������� \n� ���� ���� URL'� �������� ����� ������ \n\n��� ����� ������ ��������� [�������] ��������, \n���������������� ������ ��� ������� \n� ������� [���������] � ���� �������� \n\nPS: ����� �� ���������� �������� - $max_execution_time ������";

		$msg_bo_img_label_equal_filename = "= ��� �����";
		$msg_bo_img_label_equal_filename_tip = "�������� � ������� �������� \n��� ����� ��� ���������� \n��� ������� ��� ������ \n������� ����� �������� � ��������� windows, \n�� �������������� � �������� \n\n��������� ������ � ������ �������";
		$msg_bo_img_file_deleted = "...����� ����";
		$msg_bo_img_directory_deleted = "...����� �������";

		$msg_bo_img_file_size_bytes = "�";
		$msg_bo_img_file_lost = "(�������)";

		$msg_bo_img_label_big = " �������";
		$msg_bo_img_label_small = " ���������";

		$msg_bo_img_popuphref_zoom_unable = "� ���������, ��� ����������� �� �������������";

		$msg_bo_img_autoresize_element_has = "����������: � ��������";
		$msg_bo_img_autoresize_element_has_no_big_uploaded = "�� ������ �� ����� ������� ��������";
		$msg_bo_img_autoresize_element_has_no_resize_apply_checked = "�� ���������� [��������� ����������]";
		$msg_bo_img_autoresize_element_has_no_big_uploaded_or_HW_zero = "�� ������ ������� �������� ��� ��� ��������� ������-������ ����������� ����� ����";
			// /_image_layer.php
			

			// /_input_control.php
		$msg_bo_select_ctrl_shift = "��� ���������<br>����� ������������<br>������� Shift � Ctrl";
		$msg_bo_field_obligatory = "��� ���� ����������� ��� ����������";
			// /_input_control.php

			// /_input_types.php
		$msg_bo_depend_of_that_group = "������ ������";
		$msg_bo_linked_elements = "��������� ��������";
		$msg_bo_file_delete_existing = "������� ������������";
		$msg_bo_img_popup = "����������";
		$msg_bo_img_not_uploaded = "[����������� �� ������]";
		$msg_bo_notification_send = "��������� �����������";
			// /_input_types.php


			// _link_types.php
		$msg_bo_depend_of_that_group = "������ ������";
		$msg_bo_field = "����";
		$msg_bo_edit = "�������������";
		$msg_bo_empty = "[�����]";
		$msg_bo_link_delete = "[������� ��������]";
		$msg_bo_selector_every = " - ��� - ";
		$msg_bo_o2m_link_absent = " - ��� �������� - ";
		$msg_bo_o2m_link_delete = " - ������� �������� - ";
		$msg_bo_fyu = "���������� ��� �������";
			// /_link_types.php


			// _list.php
		$msg_bo_depend_of_that_group = "������ ������";
		$msg_bo_delete = "�������";
		$msg_bo_delete_all = "���";
		$msg_bo_delete_all_tip = "������� ��������� ������ � ������ �������";
		$msg_bo_delete_unable = "������ �������";
		$msg_bo_depend_of_that_group = "������ ������";
		$msg_bo_faceopen = "������� � ����";
			// /_list.php


			// _mysql.php
		$msg_pager_disabled = "pager disabled: too much to calculate, may hang on...";
		$msg_pager_open = "�������";
		$msg_pager_page = "��������";
		$msg_pager_nth = "-�";
		$msg_pager_from = "��";
		$msg_pager_previous = "����������";
		$msg_pager_next = "���������";
		$msg_pager_all = "���";		
		$msg_bo_cant_be_parent_of_youself = "������ ������� ��������� ������ ����";
			// /_mysql.php


			// _sendmail.php
		$msg_sendmail_client_noaddress_notsent = "������ �� ������ email, ��� �� ����������";
		$msg_sendmail_error_sending_bylist = "������ ��� �������� ��������� �� �������:";
		$msg_sendmail_error_emptylist = "������ ������ �����������";
		$msg_sendmail_mtpl = "������ ������";
		$msg_sendmail_mtpl_notfound_notsent = "�� ������; ������ �� ����������";
		$msg_sendmail_sentto = "��������� ���������� ��";
		$msg_sendmail_error_sendingto = "������ ��� ����������� ��������� ��";
		$msg_sendmail_sent_byaddress = "���������� �� ������:";
			// /_sendmail.php

			// _submenu.php
		$msg_submenu_search = "�����";
		$msg_submenu_find = "������";
		$msg_submenu_all = "���";		
		$msg_submenu_shown = "��������:";
		$msg_submenu_shown_from = "��";
			// /_submenu.php

			// _updown.php
		$msg_direction_up = "�����";
		$msg_direction_down = "����";
		$msg_bo_updown_element = "�������";
		$msg_bo_updown_element_moved = "���������";
		$msg_bo_updown_element_move_unable = "������ �����������";
			// /_updown.php

			// _init.php
		$msg_bo_jsv_checkbox_not_checked = "�� ������ ������� ";
		$msg_bo_jsv_fieldcheck_failed = "����������� ��������� ���� ";
			// /_init.php


			// mtpl-popup
		$msg_check_popup = "����������";

			// face
		$msg_no_picture = "��� ����";
		$msg_other_pictures = "��� ����";
		$msg_picture_absent = "����������� �����������";
		$msg_details = "���������";
			// /face


		break;

	case "en":
		if (!isset($site_name))
		$site_name = "Webie.CMS template website";
		
		// $menu_bo in _constants.php has empty values because of language dependency (filled at bottom of _messages.php)
		if (!isset($entity_list))
		$entity_list = array (
			"person" => "�������",
		//	"=article-lost.php" => "������ ��� ��������",
			"article" => "������",
			"agroup" => "�������",
			"m2m_article_rating" => "�������,�����,�����",
			"m2m_article_replic" => "����������",

			"cgroup" => "Client Groups",
			"customer" => "Clients",
			"corder" => "Orders",
		
			"=product-lost.php" => "Lost Products",
			"product" => "Products",
//			"pgroup" => "Product Groups",
			"pgroup" => "Categories",
			"=pgroup-onindex.php" => "Categories order on Homepage",
			"supplier" => "Suppliers",
			"pmodel" => "Models",

			"spart" => "Spare Parts",
			"sgroup" => "Spare Parts Categories",

			"currency" => "Currencies",
		
			"m2m_product_rating" => "Rating,Opinion,Claim",
			"m2m_product_replic" => "Discussion",
		
			"news" => "News",
			"ngroup" => "News Groups",
			"=../mailer/user/login.php?mlist=1&l_login=1234&l_passwd=1234&mode=login' target='_blank" => "Mailer",
		
			"faq" => "FAQ",
			"fgroup" => "FAQ Group",
		
			"banner" => "Banners",
			"bgroup" => "Banner groups",
		
			"constant" => "Constants",
			"cached" => "Cache",
			"mtpl" => "Mail Templates",
			"sentlog" => "Sent Messages",
			"imgtype" => "Image Types",
			"img" => "All Images",
			"change_word" => "Replace everywhere",
			"jsvalidator" => "Input Validators",
		
			"icwhose" => "Sheets",
			"ic" => "Sheet Questions",
			"icdict" => "Dictionnaries",
			"icdictcontent" => "Dictionnary Values",
			"ictype" => "Input Types",
			"icsheet" => "Sheets Filled",
		
			"mmenu" => "Document Tree",		//Website Structure
			"=mmenu-legend.php?parent_id=2" => "Website Legend",
		);
		
		
		if (!isset($add_entity_msg_list))
		$add_entity_msg_list = array (
			"person" => "����� �������",
			"article" => "����� ������",
			"agroup" => "����� �������",
			"m2m_article_rating" => "����� �������,�����,�����",
			"m2m_article_replic" => "����� �������",

			"customer" => "new Client",
			"�group" => "new Client Group",
			"pimportsource" => "new pricelist import setting",
			"product" => "new Product",
			"pgroup" => "new Product Group",
			"ugroup" => "new User Group",
			"supplier" => "new Supplier",
			"pmodel" => "new Product Model",
		
			"spart" => "new Spare Part",
			"sgroup" => "new Spare Parts Category",

			"country" => "new Country",
			"currency" => "new Currency",
		
			"m2m_product_rating" => "new Rating,Opinion,Claim",
			"m2m_product_replic" => "new Replic",
		
			"taxrate" => "new Tax Rate",
			"package" => "new Package",
			"saleunit" => "new Product Unit",
			"shiptype" => "new Shipment Type",
			"pclass" => "new Product Class",
		
			"shop" => "new Shop",
		
			"banner" => "new Banner",
			"bgroup" => "new Banner Group",
			"news" => "new News",
			"ngroup" => "new News Group",
		
			"faq" => "new ������-�����",
			"fgroup" => "new FAQ Group",
		
			"constant" => "new Constant",
			"cached" => "new Cache Record",
			"mtpl" => "new Mail Template",
			"imgtype" => "new Image Type",
			"img" => "new Image",
			"jsvalidator" => "new Input Validator",
		
			"icwhose" => "new Sheet",
			"ic" => "new Sheet Question",
			"icdict" => "new Dictionnary",
			"icdictcontent" => "new Dictionnary Value",
			"ictype" => "new Input Type",
		
			"mmenu" => "new Menu Item",
		);
		
		
		if (!isset($new_entity_ident_list))
		$new_entity_ident_list = array (
			"person" => "����� �������",
			"article" => "����� ������",
			"agroup" => "����� �������",
			"m2m_article_rating" => "����� �������,�����,�����",
			"m2m_article_replic" => "����� �������",

			"customer" => "new Client",
			"�group" => "new Client Group",
			"pimportsource" => "new pricelist import setting",
			"product" => "new Product",
			"pgroup" => "new Product Group",
			"ugroup" => "new User Group",
			"supplier" => "new Supplier",
			"pmodel" => "new Product Model",
		
			"spart" => "new Spare Part",
			"sgroup" => "new Spare Parts Category",

			"country" => "new Country",
			"currency" => "new Currency",
		
			"m2m_product_rating" => "new Rating,Opinion,Claim",
			"m2m_product_replic" => "new Replic",
		
			"taxrate" => "new Tax Rate",
			"package" => "new Package",
			"saleunit" => "new Product Unit",
			"shiptype" => "new Shipment Type",
			"pclass" => "new Product Class",
		
			"shop" => "new Shop",
		
			"banner" => "new Banner",
			"bgroup" => "new Banner Group",
			"news" => "new News",
			"ngroup" => "new News Group",
		
			"faq" => "����� ������-�����",
			"fgroup" => "����� ��������� FAQ",
		
			"constant" => "new Constant",
			"cached" => "new Cache Record",
			"mtpl" => "new Mail Template",
			"imgtype" => "new Image Type",
			"img" => "new Image",
			"jsvalidator" => "new Input Validator",
		
			"icwhose" => "new Sheet",
			"ic" => "new Sheet Question",
			"icdict" => "new Dictionnary",
			"icdictcontent" => "new Dictionnary Value",
			"ictype" => "new Input Type",
		
			"mmenu" => "new Menu Item",
		);

/*
// ��������� "������ ��������" �� "����� ������ ��������"
		$entity_list_single = array ();
		foreach ($new_entity_ident_list as $new_entity_name => $new_entity_txt) {
			$new_entity_txt = preg_replace("~^\S+\s+~", "", $new_entity_txt);
			$new_entity_txt = ucfirst($new_entity_txt);
			$entity_list_single[$new_entity_name] = $new_entity_txt;
		}
//		pre($entity_list_single);
*/

		$msg_fields = array (
// common
			"id" => "�",
			"ident" => "Title",
			"annotation" => "Annotation",
			"annotation-graycomment" => "label under the menu item",
			"hashkey" => "Key",
			"date_created" => "Creation Date",
			"date_updated" => "Update date",
//			"date_published" => "Publishing date",		// too many entities where data_published is date of ...
			"date_published" => "Date",
			"img_cnt" => "Img",
			"published-list" => "Pbl",
			"published-edit" => "Published",
			"i_published-list" => "OnMainP",
			"i_published-edit" => "On Main Page",

			"~delete" => "Del",
			"parent_id" => "Parent",
			"group" => "Group",
			"comment" => "Internal Comment",

			"pagetitle" => "Page Title",
			"title" => "Content Title",
			"meta_keywords" => "Meta Keywords",
			"meta_description" => "Meta Description",

// "explainations are too complicated"
//			"brief-edit" => "������<br><br>����� � ������<br>����������<br>���������<br><br>(������� � �����,<br>��������<br> ����� ������...)",
//			"content-edit" => "��������<br><br>����� �<br>�������� ������,<br>����� ������� ...",

			"brief" => "Brief",
			"content" => "Content",

			"date_lastclick" => "Last Click",
			"remote_address" => "Registraion IP",
			"lastip" => "Last Click IP",
			"lastsid" => "Cookie sent",
			"idrandom" => "IDrandom",

			"additional_layer" => "Additional options",
			"service_layer" => "Service Fields",
			"filesattached_layer" => "Downloadable files",

			"product_iccontent" => "Product Properties",

			"file1" => "File 1",
			"file1_comment" => "Comment 1",
			"file2" => "File 2",
			"file2_comment" => "Comment 2",
			"file3" => "File 3",
			"file3_comment" => "Comment 3",
			"file4" => "File 4",
			"file4_comment" => "Comment 4",
			"file5" => "File 5",
			"file5_comment" => "Comment 5",

// customer
			"contract_discount" => "Discount,&nbsp;%",
			"login" => "Login",
			"passwd" => "Password",
			"manager_name" => "Contact",
			"cgroup-list" => "Group",			// next taking from entity_list_single
//			"customer-ident" => "������� ����",	// next taking from entity_list_single
			"phone" => "Phone",
			"address" => "Address",
			"fax" => "Fax",
			"contract_number" => "Contract Number",
			"tin" => "UTN",
			"customer_sheet" => "Customer Sheet",
			"login_layer" => "Login, Password, Access",
			"customer-published-list" => "Access",
			"customer-published-edit" => "Access Granted",
			"customer-date_created-edit" => "Registered",
			"customer-date_updated-edit" => "Updated",

// product
			"pgroup-list" => "Categories",			// next taking from entity_list_single
			"pgroup-edit" => "Categories",			// next taking from entity_list_single
			"article" => "Article",
			"price_1" => "Price",
			"price_2" => "Price 2",
			"price_3" => "Price 3",
			"pricecomment_1" => "Price Comment",
			"pricecomment_2" => "Price Comment 2",
			"pricecomment_3" => "Price Comment 3",

			"product-brief-edit" => "Brief<br><br>for same group<br>product list",
			"product-content-edit" => "Description<br><br>for product card,<br>at rigth of photos",
					
			"weight" => "Weight",
			"hits" => "Hits",
			"hits-edit" => "Hits",
			"hits-graycomment" => "any request from Face, robots are counted as well",

			"briefful_layer" => "Short and Full Description",
			"news4product_layer" => "Product is connected to news...",
//			"product-date_published" => "����",
			
			"archived-list" => "Archive",
			"archived" => "In Archive",
			"is_new" => "New",
			"banner_top" => "Banner on Top",

			"sold" => "Sold",
			"disclaimer_list" => "Disclaimer for product list",
			"disclaimer_pcard" => "Disclaimer for product card",

// pgroup
			"divclass" => "DIV Class",
			//"file1" => "Image in Menu",
			//"file1-graycomment" => "only for 1-level groups",

// currency
			"date_exchrate_rub" => "Rate Date",
			"exchrate_rub" => "Exchange Rate",
			"exchrate_rub_multiplier" => "Multiplier",
			"exchrate_rub_multiplied" => "Result Rate",
			"currency-date_updated" => "Last Update",
			"src_href" => "Rate Source",
			"src_content" => "Content<br>Cached",
			"exchrate_regexp" => "exchrate_regexp",
			"daterate_regexp" => "daterate_regexp",

// m2m_product_rating
			"m2m_product_rating-customer_ident" => "Whose rating",
			"rating" => "Rating",
			"opinion" => "WhatGood",
			"opinion-edit" => "What She liked,<br>What Was Bad",
			"wish" => "Proposals",
			"m2m_product_rating-content" => "Moderator's<br>Comment",

// news
//			"news-date_published" => "Date",
			"ngroup_ident-list" => "News Group",
			"rsss_ident" => "RSS-source",
			"rss_published" => "In&nbsp;RSS",
			"news-brief-edit" => "Brief",
			"news-content-edit" => "News Content",

			"srcurl" => "<a href='#SRCURL#' target=_blank>Source</a>",
			"hrefto" => "<a href='#HREFTO#' target=_blank>Hyperlink</a>",

// faq
			"cname" => "Name",
			"email" => "Email",
			"answer_sent" => "Sent",
			"answer_sent-edit" => "Send Answer",
			"answer_sent-graycomment" => "if you need to send an answer to client by email indicated - check this box and save",
			"subject" => "Subject",
			"contact" => "Other Contacts",
			"contact-edit" => "Other<br>Contacts",
			"faq-content" => "Consultant's Reply",
			"faq-fgroup-list" => "FAQ Group",
			"faq-fgroup-graycomment" => "Select Common Group!",

// constant
			"constant-ident" => "Name",
			"constant-content" => "Value",

// cached
			"cached-date_published" => "Cached",
			"expiration_minutes" => "Keep, min",
			"expiration_minutes-list" => "Min",
			"expiration_minutes-graycomment" => "for automatic update: expiration date = NOW + THIS FIELD minutes",
			"date_expiration" => "Expiration",
			"scriptname_updated" => "Updater",
			"scriptname_created" => "Creator",

// mtpl
			"subject" => "Mail Subect",
			"body" => "Mail Body",
			"admtail" => "Administrator's<br>tail",
			"admtail-graycomment" => "text in this field is added to letter; you may use #HTTP_HOST# etc...",
			"sender" => "Sender",
			"rcptto" => "Recipient",
			"rcptto-graycomment" => "email, email... of managers who get a copy of client message + [Adninistrator's tail]",
			"sentmsg" => "In Case of Success",
			"sentmsg-graycomment" => "FrontEnd message after mail been sent successfully (javascript:alert)",

			"savesentlog" => "SentLog",
			"savesentlog-edit" => "Save<br>SentMail",
			"savesentlog-graycomment" => "after having sent, a copy is saved in <a href='sentlog.php'>Sent Log</a>",			


// sentlog
			"sentlog-content-list" => "Letter Content",
			"sentlog-content-edit" => "Letter<br>Content",

// imgtype
			"imgtype-content" => "Internal<br>Comment",
			"imglimit-list" => "ImageLimit",
			"imglimit" => "Image Limit",
			"imglimit-graycomment" => "limit for images uploaded; 0 = unlimited",

			"imgsmall_layer" => "Small Image in Backoffice",
			"img_present" => "Small Image is shown",
			"img_present-graycomment" => "whether Small Image -> [Browse] is displayed",
			"img_newqnty" => "Quantity of buttons [Browse]",
			"img_newqnty-graycomment" => "if you want to upload several at once; 0=1",
			"img_zip_present" => "ZIP upload",
			"img_zip_present-graycomment" => "whether Small Image -> [Browse] ZIP is displayed for New Images",
			"img_url_present" => "URL upload",
			"img_url_present-graycomment" => "whether Small Image -> [URL] is displayed for new and uploaded",
			"img_txt_present" => "Image Label",
			"img_txt_present-graycomment" => "whether Image Label for Small Image is displayed",
			"img_txt_eq_fname" => "Checkbox [Image Label = File Name]",
			"img_txt_eq_fname-graycomment" => "Checkbox [Image Label = File Name] state for new Images",
			"resize_published" => "Create [Small] Resize from [Big]",
			"resize_published-graycomment" => "whether the feature is displayed",
			"resize_default_checked" => "Create checkbox state",
			"resize_default_checked-graycomment" => "whether Create Resize is checked by default",
			"resize_default_qlty" => "[Big]>>[Small] resize Quality",
			"resize_default_qlty-graycomment" => "JPEG Quality: 0&#8230;100",
			"resize_default_width" => "[Small] Resize Width",
			"resize_default_height" => "[Small] Resize Height",

			"imgbig_layer" => "Big Image in Backoffice",
			"img_big_present" => "Big Image is shown",
			"img_big_present-graycomment" => "whether Big Image -> [Browse] is displayed",
			"img_big_newqnty" => "Quantity of buttons [Browse]",
			"img_big_newqnty-graycomment" => "if you want to upload several at once; 0=1",
			"img_big_zip_present" => "ZIP upload",
			"img_big_zip_present-graycomment" => "whether Big Image -> [Browse] ZIP is displayed for New Images",
			"img_big_url_present" => "URL upload",
			"img_big_url_present-graycomment" => "whether Big Image -> [URL] is displayed for new and uploaded",
			"img_big_txt_present" => "Image Label",
			"img_big_txt_present-graycomment" => "whether Image Label for Big Image is displayed",
			"img_big_txt_eq_fname" => "Checkbox [Image Label = File Name]",
			"img_big_txt_eq_fname-graycomment" => "Checkbox [Image Label = File Name] state for new Images",
			"big_resize_published" => "Resize [Big] while upload",
			"big_resize_published-graycomment" => "whether the feature is displayed",
			"big_resize_default_checked" => "Create checkbox state",
			"big_resize_default_checked-graycomment" => "whether Create Resize is checked by default",
			"big_resize_default_qlty" => "[Big] upload resize Quality",
			"big_resize_default_qlty-graycomment" => "JPEG Quality: 0&#8230;100",
			"big_resize_default_width" => "[Big] Resize Width",
			"big_resize_default_height" => "[Big] Resize Height",




			"imgthumb_layer" => "Thumbnail in Backoffice",
			"img_thumb_present" => "Thumbnail is shown",
			"img_thumb_present-graycomment" => "whether thumbnail is displayed at right of upload form",
			"img_thumb_qlty" => "[Thumbnail] resize Quality",
			"img_thumb_qlty-graycomment" => "JPEG Quality: 0&#8230;100",
			"img_thumb_width" => "[Thumbnail] resize Width",
			"img_thumb_height" => "[Thumbnail] resize Height",
			
			"imgfirst_layer" => "Autoresize of First (ex, for list of products)",
			"_autoresize_qlty" => "Autoresize Quality",
			"_autoresize_qlty-graycomment" => "JPEG Quality: 0&#8230;100",
			"_autoresize_width" => "Autoresize Width",
			"_autoresize_height" => "Autoresize Height",
			"_autoresize_apply" => "Autoresize Active",
			"_autoresize_apply-graycomment" => "Autoresize are created on page Requst",
			"_merge_img" => "Watermark",
			"_merge_img-graycomment" => "whether Watermark is put over the Autoresize",
			"_merge_alfa" => "Transparency",
			"_merge_alfa-graycomment" => "Image overlay's Transparency: 0&#8230;100",
			"_merge_type" => "Overlay Method",
			"_merge_type-graycomment" => "0=Propotrional Resize; 1=Tile",
			"_merge_apply" => "Put Watermark",
			"_merge_apply-graycomment" => "delete file-watermark of release the tick",
			"_autoresize_debug" => "Debug Autoresize",
			"_autoresize_debug-graycomment" => "print messages on Frontent about: autoresize, delete stallen, watermarks",
			"_autoresize_tpl_ex" => "Template for<br>Existing",
			"_autoresize_tpl_nex" => "Template for<br>Non-Existing",
			
			"imgfirst2_layer" => "Autoresize2 of First (ex, for list of products)",
			"imgfirst3_layer" => "Autoresize3 of First (ex, for list of products)",
			"imgfirst4_layer" => "Autoresize4 of First (ex, for list of products)",
			"imgevery_layer" => "Autoresize of Every (ex, for previews in product card)",
			"imgevery2_layer" => "Autoresize2 of Every (ex, for previews in product card)",
			"imgevery3_layer" => "Autoresize3 of Every (ex, for previews in product card)",
			"imgevery4_layer" => "Autoresize4 of Every (ex, for previews in product card)",
			
			"imgmsg_layer" => "Backoffice Labels",
			"msg_ident" => "Label [Image]",
			"msg_add" => "Label [New Image]",
			"msg_change" => "Label [Change Image]",
			"msg_img" => "Label [Small]",
			"msg_img_big" => "Label [Big]",

			"img_table" => "Image DB Table<br>[img] if empty",
			"merge_seed" => "Merge Seed",
			"merge_seed-graycomment" => "used for watermarked image",
			
			"imgtype-date_updated" => "Last Updated",
			"imgtype-date_updated-graycomment" => "Autoresizes older than Date will be re-generated",
			
// img
			"~img_tag" => "Marker",
			"~img_linkedto" => "Link To",

// img
			"jsvalidator-content" => "JavaScript<br>RegularExpression",

// icwhose
//			"icwhose-hashkey" => "Key for Templates",
			"bo_only-list" => "BO",
			"bo_only" => "Only in BackOffice",
			"bo_only-graycomment" => "Sheet will not be displayed in BackOffice",
			"jsv_debug" => "Debug",
			
// ic
			"obligatory-list" => "Reqd",
			"obligatory_bo-list" => "BOReqd",
			"inbrief-list" => "inList",
			"sorting-list" => "Sort",
			"published_bo-list" => "PubBO",

			"obligatory" => "Required",
			"obligatory_bo" => "Required in BO",
			"inbrief" => "Show in Brief",
			"inbrief-graycomment" => "field is displayed in product list",

			"sorting" => "Sortable Field",
			"sorting-graycomment" => "FrontEnd: in product list you may sort by this column",
			"published_bo" => "Published in BO",

			"graycomment" => "Comment",
			"ic-icwhose" => "Sheet - whose field is",

// icdict
			"icdict-icwhose" => "Whose Dictionnary is",
			
// icdictcontent
			"label_style-list" => "Label Style",
			"tf1_width-list" => "TF",
			"tf1_incolumn-list" => "TFsep",
			"icdictcontent-content" => "Descr",
			
			"label_style" => "Label Style=[]",
			"tf1_width" => "Textfield width [] is nearby",
			"tf1_incolumn" => "Textfield in Separate Column",

			"tf1_addtodict" => "Add values<br>from Textfield to Dictionnary",
			"tf1_addedpublished" => "Whether added values<br>becomes Published",


// mmenu
			"mmenu-hashkey" => "Key/Link",
			"mmenu-is_heredoc-list" => "Stand",
			"mmenu-is_heredoc" => "Standard Document",
			"mmenu-is_heredoc-graycomment" => "yes = [only content is displayed, there is no special page]",

			"mmenu-is_drone-list" => "Drone",
			"mmenu-is_drone" => "Drone",
			"mmenu-is_drone-graycomment" => "FrontOffice: menu href leads to first child element",

			"mmenu-content_no_freetext-list" => "TArea",
			"mmenu-published_legend-list" => "Legnd",
			"mmenu-published_legend" => "Published In Legend",

			"mmenu-published_sitemap-list" => "Map",
			"mmenu-published_sitemap" => "Published In SiteMap",

			"mmenuimg_layer" => "Images for menu items",
			"mmenu-img_header" => "Title",
			"mmenu-img_free" => "Menu image<br>MouseFree",
			"mmenu-img_mover" => "Menu image<br>MouseOver",
			"mmenu-img_small_free" => "Label in menu small<br>MouseFree",
			"mmenu-img_small_mover" => "Label in menu small<br>MouseOver",
			"mmenu-img_small_current" => "Label in menu small<br>Current",
			"mmenu-img_ctx_top" => "Upper Context image<br>(inherits)",
			"mmenu-img_ctx_left" => "Left Context image<br>(inherits)",



//			"" => "",
//			"-graycomment" => "",


		);


			// userland constants defined in backoffice/*.php
		$msg_bo_add_passwd_generated = "password generated while adding";
		$msg_bo_update_passwd_entered = "backoffice operator entered new password";
		$msg_bo_face_auth_as_user = "login by this user";
		$msg_bo_face = "face";
		$msg_bo_email_from_admin = "Message from Administrator";
		$msg_bo_add_passwd_generation_gray = "while new user is added, his login and password will be generated automatically<br>when password is changed, password will be crypted with md5";
		$msg_bo_sort_offers_onindex = "Sort offers on index page";

// change_word
		$msg_bo_changeword_import_ident = "Change the word all across DataBase";
		$msg_bo_changeword_ucase_lcase = "uppercase/lowercase";
		$msg_bo_changeword_replace_seleted = "Change Selected";
		$msg_bo_changeword_not_found = "Not Found in every significant DataBase Fields";

		$msg_bo_changeword_table_id = "Table:ID";
		$msg_bo_changeword_field = "Field";
		$msg_bo_changeword_before = "Before";
		$msg_bo_changeword_replace = "Change";
		$msg_bo_changeword_what_welook = "We look for:";
		$msg_bo_changeword_replacement = "We replace to:";
		$msg_bo_changeword_find = "Find";

// ic
		$msg_bo_ic_icdict_values = "Dictionnary Values";
		$msg_bo_ic_columns_in_table = "Table Columns";

		$msg_bo_ic_string_template = "String-Template";
		$msg_bo_ic_html = "raw HTML";
		$msg_bo_ic_width = "width";
		$msg_bo_ic_height = "height";
		$msg_bo_ic_default = "by default";

		$msg_bo_ic_filemax_kb = "File size not exceeding, Kb";
		$msg_bo_ic_default_state = "Default state";
		$msg_bo_ic_no_additional_params = "[ADDITIONAL PARAMETERS NOT REQUIRED]";
		
		
		$msg_bo_ic_formula = "Formula<br><br>";
		$msg_bo_ic_formula_graycomment = "example: [USD_AUCTION_COST + USD_AUCTION_FEE] where all variables are hashkeys of same sheet; no () and calc priorities";
		

			// /userland constants defined in backoffice/*.php



			// _edit_fields.php
		$msg_bo_backtolist = "Return To List";
		$msg_bo_F5_title = "Re-read every field from DataBase \n\nuseful while page \nis edited and changed \nby multiple users";
		$msg_bo_previous_element = "previous element";
		$msg_bo_next_element = "next element";

		$msg_bo_switch_to_textarea = "as TEXTAREA";
		$msg_bo_switch_to_textarea_tip = "switch to TEXTAREA; changes will not be saved; content will be read again from prevously saved state";
		$msg_bo_switch_to_freetext = "as FREETEXT";
		$msg_bo_switch_to_freetext_tip = "switch to FREETEXT; ; changes will not be saved; content will be read again from prevously saved state";
		
		$msg_bo_imgtype_not_defined = "this Image Type is not defined";
		$msg_bo_icwhose_not_defined = "this Sheet is not defined";
		
		$msg_bo_it_change = "change";
		$msg_bo_it_add = "add";
		$msg_bo_it_tolist = "to the list";
		

		$msg_bo_add = "Add";
		$msg_bo_add_unable = "Unable to Add";
		$msg_bo_save = "Save";
		$msg_bo_updated = "Updated";
		$msg_bo_updated_for = "for";

		$msg_bo_subitems = "Sub-items";
		$msg_bo_preview = "preview";
			// _edit_fields.php
		
			// _compositebidiect.php
		$msg_bo_subgroup_qnty = "subgroups";
		$msg_bo_products_in_subgroup = "products in subgroup";
		$msg_bo_products_selected = "products seleted";
		$msg_bo_products_in_subgroup_selected = "products in subgroup seleted";
		$msg_bo_go_product_editing = "edit this product";
		$msg_bo_not_selected = "not selected";
		$msg_bo_bidirect_reciplink_restored = "reverse link is recovered";
		$msg_bo_bidirect_reciplink_restore_failed = "reverse link failed to recover";
		$msg_bo_bidirect_reciplink_was_absent = "link was not established";
		$msg_bo_bidirect_directlink_was_absent = "direct link was not established";
		$msg_bo_bidirect_directlink_add_failed = "failed to add direct link";
		$msg_bo_bidirect_reverselink_add_failed = "failed to add reverse link";
			// /_compositebidiect.php


			// _entity_edit.php
		$msg_bo_required_parameter_missing = "Required parameter is missing";
		$msg_bo_file_delete_unable = "Unable to delete file";
		$msg_bo_file_format_wrong = "Wrong file format";
		$msg_bo_database_updated = "Item is updated";
		$msg_bo_database_swapfield_unable = "Unable to move";
			// /_entity_edit.php


			// _image_layer.php
		$msg_bo_img_preview_only = "only for preview \n(not for FrontEnd usage)";
		$msg_bo_img_original = "original";
		$msg_bo_img_jpeg_save_optimalq = "JPEG Quality: 0�100 \n\noptimal value 75";
		$msg_bo_img_width_destination = "target width \n\nfor proportional resize \nenter one of values";
		$msg_bo_img_height_destination = "target height \n\nfor proportional resize \nenter one of values";
		$msg_bo_img_big_overwrite = "resize [Big] on-the-fly";
		$msg_bo_img_big_overwrite_tip = "overwrite [Big] image \nby upload self-resize \n\nactual only while uploading";
		$msg_bo_img_small_create_frombig = "create [Small] from [Big]";
		$msg_bo_img_small_create_frombig_tip = "overwrite [Small] image \nwith resize from [Big] \n\nif [Big] is absent \n[Small] will not be deleted";
		$msg_bo_img_marker_tip = "Click, [Ctrl-C], [Ctrl+V] in target place \n\nthis marker being inserted to text \n1. displays [Small] image with label \n2. on click to [Small] image \na popup window opens \n3. in properly sized window the \n [Big] image is displayed";
		$msg_bo_img_published = "published";
		$msg_bo_img_published_tip = "this flag works \nif the image was inserted \nby means of marker &#35;IMG#ID#&#35;";
		$msg_bo_img_main = "main";
		$msg_bo_img_delete = "delete";
		$msg_bo_img_delete_existing = "delete existing";
		$msg_bo_img_delete_tip = "images are physically \nerased from disk, \nreleasing free space";
		$msg_bo_img_label = "label";
		$msg_bo_img_label_tip = "label is shown under image \nonly if image is inserted to text \nby means of marker &#35;IMG#ID#&#35;";
		$msg_bo_img_try_dragging = "try to drag&drop this image \nto the text shown in FreeTextBox";

		$msg_bo_img_maker_width = "width \n\nthese sizes are applied \nonly when marker &#35;IMG#ID#&#35; \nwas used \n\nwhen you change value in this field \nonly sizes of HTML tag are changed, \nrepresented with marker; \nno resize of original image occurs";
		$msg_bo_img_maker_height = "height \n\nthese sizes are applied \nonly when marker &#35;IMG#ID#&#35; \nwas used \n\nwhen you change value in this field \nonly sizes of HTML tag are changed, \nrepresented with marker; \nno resize of original image occurs";
		
		$msg_bo_img_upload_tip = "field for upload one image (Browse) \n\nyou can upload one [Big] image, \nresize it while uploading \nand create [Small] at once \n\nPS: maximum page execution time is $max_execution_time sec";
		$msg_bo_img_upload_only_one = "here you can upload \nonly one image \n\nin order to upload \nmultiple new images \nuse [NEW IMAGE] \n\nyou can not upload both \nfile with Browse, \nand URL simultaneously";
		$msg_bo_img_upload_zip_tip = "in order to upload multiple images, create \nZIP-archive containing whole folder with images, \nand select here resulting ZIP-archive \n\nimages from subfolders inside ZIP-archive, \nwill be added as well \n\nenclosed archives WILL NOT BE PROCESSED \n\nthis is a tool to upload multiple [Big]images, \nmake self-resize of each while uploading \nand created [Small] resizes at one step \n\nPS: maximum page execution time is $max_execution_time sec";
		$msg_bo_img_upload_url_tip = "to upload multiple images, state in this field \nall URLs of every image separated with [space] \n\nthis is a tool to upload multiple [Big]images, \nmake self-resize of each while uploading \nand created [Small] resizes at one step \n\nPS: maximum page execution time is $max_execution_time sec";

		$msg_bo_img_label_equal_filename = "= file name";
		$msg_bo_img_label_equal_filename_tip = "fill image labels \nwith file names except extension \nfor Russian file names \nimage labels will be saved in windows-1251 codepage, \nbefore TRANSLIT convertation \n\ncheckbox works only while uploading";
		$msg_bo_img_file_deleted = "...file deleted";
		$msg_bo_img_directory_deleted = "...folder deleted";

		$msg_bo_img_file_size_bytes = "b";
		$msg_bo_img_file_lost = "(lost)";

		$msg_bo_img_label_big = " big";
		$msg_bo_img_label_small = " small";

		$msg_bo_img_popuphref_zoom_unable = "Unfortunately, this image could not be enlarged";

		$msg_bo_img_autoresize_element_has = "autoresize: element";
		$msg_bo_img_autoresize_element_has_no_big_uploaded = "has no [Big] image uploaded";
		$msg_bo_img_autoresize_element_has_no_resize_apply_checked = "box [Autoresize apply] is not checked";
		$msg_bo_img_autoresize_element_has_no_big_uploaded_or_HW_zero = "has no [Big] image uploaded or both Width\Height parameters are ZERO";
			// /_image_layer.php
			

			// /_input_control.php
		$msg_bo_select_ctrl_shift = "Use Shift � Ctrl keys<br>for selection";
		$msg_bo_field_obligatory = "this field is mandatory";
			// /_input_control.php

			// /_input_types.php
		$msg_bo_depend_of_that_group = "of this group";
		$msg_bo_linked_elements = "Linked elements";
		$msg_bo_file_delete_existing = "delete existing";
		$msg_bo_img_popup = "preview";
		$msg_bo_img_not_uploaded = "[no image uploaded]";
		$msg_bo_notification_send = "send a notification";
			// /_input_types.php


			// _link_types.php
		$msg_bo_depend_of_that_group = "is this group";
		$msg_bo_field = "Field";
		$msg_bo_edit = "edit";
		$msg_bo_empty = "[empy]";
		$msg_bo_link_delete = "[delete the link]";
		$msg_bo_selector_every = " - all - ";
		$msg_bo_o2m_link_absent = " - no link - ";
		$msg_bo_o2m_link_delete = " - delete the link - ";
		$msg_bo_fyu = "For Your Information";
			// /_link_types.php


			// _list.php
		$msg_bo_depend_of_that_group = "of this group";
		$msg_bo_delete = "Delete";
		$msg_bo_delete_all = "all";
		$msg_bo_delete_all_tip = "tick refers only to the button DELETE";
		$msg_bo_delete_unable = "unable to delete";
		$msg_bo_depend_of_that_group = "of this group";
		$msg_bo_faceopen = "open in FrontEnd";
			// /_list.php


			// _mysql.php
		$msg_pager_disabled = "pager disabled: too much to calculate, may hang on...";
		$msg_pager_open = "open";
		$msg_pager_page = "page";
		$msg_pager_nth = "-th";
		$msg_pager_from = "of";
		$msg_pager_previous = "previous";
		$msg_pager_next = "next";
		$msg_pager_all = "all";		
		$msg_bo_cant_be_parent_of_youself = "You can not select item as its parent";
			// /_mysql.php


			// _sendmail.php
		$msg_sendmail_client_noaddress_notsent = "Client has empty email, message is not sent";
		$msg_sendmail_error_sending_bylist = "error sending message by these addresses:";
		$msg_sendmail_error_emptylist = "Recipient list is empty";
		$msg_sendmail_mtpl = "message template";
		$msg_sendmail_mtpl_notfound_notsent = "not found; letter was not sent";
		$msg_sendmail_sentto = "Message sent to";
		$msg_sendmail_error_sendingto = "ERROR sending message to";
		$msg_sendmail_sent_byaddress = "Send by address:";
			// /_sendmail.php

			// _submenu.php
		$msg_submenu_search = "Search";
		$msg_submenu_find = "Find";
		$msg_submenu_all = "all";		
		$msg_submenu_shown = "shown:";
		$msg_submenu_shown_from = "of";
			// /_submenu.php

			// _updown.php
		$msg_direction_up = "up";
		$msg_direction_down = "down";
		$msg_bo_updown_element = "Element";
		$msg_bo_updown_element_moved = "moved";
		$msg_bo_updown_element_move_unable = "unable to move";
			// /_updown.php

			// _init.php
		$msg_bo_jsv_checkbox_not_checked = "Checkbox is not checked ";
		$msg_bo_jsv_fieldcheck_failed = "Field is not filled correctly ";
			// /_init.php

		$list_empty_msg = "<b>No data</b>";


			// mtpl-popup
		$msg_check_popup = "Check";

			// face
		$msg_no_picture = "no picture";
		$msg_other_pictures = "more pictures";
		$msg_picture_absent = "image is missing";
		$msg_details = "details";
			// /face


		break;

//	case "fr";
//		break;

}


// ��������� "������ ��������" �� "����� ������ ��������"
$entity_list_single = array ();
foreach ($new_entity_ident_list as $new_entity_name => $new_entity_txt) {
	$new_entity_txt = preg_replace("~^\S+\s+~", "", $new_entity_txt);
	$new_entity_txt = ucfirst($new_entity_txt);
	$entity_list_single[$new_entity_name] = $new_entity_txt;
}
//pre($entity_list_single);


// ��������� "������ ��������" �� "����� ������ ��������"
$entity_list_single_savebutton = array ();
foreach ($add_entity_msg_list as $add_entity_name => $add_entity_txt) {
	$add_entity_txt = preg_replace("~^\S+\s+~", "", $add_entity_txt);
	$add_entity_txt = ucfirst($add_entity_txt);
	$entity_list_single_savebutton[$add_entity_name] = $add_entity_txt;
}
//pre($entity_list_single_savebutton);


foreach ($menu_bo as $key => $value) {
	if ($menu_bo[$key] == "" && isset($entity_list[$key])) $menu_bo[$key] = $entity_list[$key];
}

//pre($menu_bo);

$lang_hashkey = array (
	"en" => "English",
	"fr" => "Francais",
	"ru" => "�������",
	);

$lang_content_type_charset_hash = array (
	"en" => "",
	"fr" => "",
	"ru" => "windows-1251",
	);


//$debug_lang = 1;
if ($debug_lang == 1) {
	pre("lang_current = " . pr($lang_current));
	pre("entity_list = " . pr($entity_list));
}


/*	// generated automatically
		$entity_list_single = array (
			"cgroup" => "������ ��������",
			"customer" => "������",
			"corder" => "�����",
		
			"product" => "�������",
			"pgroup" => "������ ���������",
			"supplier" => "�������������",
			"pmodel" => "������",
			"currency" => "������",
		
			"m2m_product_rating" => "�������,�����,�����",
			"m2m_product_replic" => "�������",
		
			"news" => "�������",
			"ngroup" => "��������� �����",
		
			"faq" => "������",
			"fgroup" => "������ ������-�����",
		
			"banner" => "������",
			"bgroup" => "������ ��������",
		
			"constant" => "���������",
			"cached" => "���",
			"mtpl" => "������ ������",
			"sentlog" => "������������",
			"imgtype" => "��� ��������",
			"img" => "��������",
			"jsvalidator" => "�������� �����",
		
			"icwhose" => "������",
			"ic" => "������ � ������",
			"icdict" => "����������",
			"icdictcontent" => "�������� �����������",
			"ictype" => "��� ����� �����",
			"icsheet" => "����������� ������",
		
			"mmenu" => "����� ����",
		);


*/		

/*
$add_entity_msg_list = array (
	"pgroup" => "new category",
	"product" => "new product",
	"supplier" => "new manufacturer",
	"pmodel" => "new model",

	"comptn" => "����� ������������",
	"ogroup" => "����� ������ �����",
//	"caroption" => "����� �����",
	"ppgoption" => "new option",
	"brand" => "������ �������������",
	"color" => "new color",
	"colortone" => "����� �������",
	"surface" => "����� ��� ��������",
	"kuz" => "����� ��� �����",
	"gearbox" => "����� ��� �������",
	"engine" => "����� ��� ���������",
	"gearbox" => "����� ��� �������",
	"salon" => "����� ������ ������",
	"currency" => "new currency",
	"metrics" => "new measure of length",
	"news" => "�������",
	"banner" => "new banner",
	"bgroup" => "new banner group",

	"constant" => "new constant value",
	"mtpl" => "new mail template",
	"imgtype" => "new image type",
	"img" => "new image",
	"jsvalidator" => "new JSValidator",

	"icwhose" => "����� ������",
	"ic" => "����� ������ � ������",
	"icdict" => "����� ����������",
	"icdictcontent" => "����� �������� �����������",
	"ictype" => "����� ��� ����� �����",

	"mmenu" => "new document",
);


$new_entity_ident_list = array (
	"pgroup" => "new category",
	"product" => "new product",
	"supplier" => "new manufacturer",
	"pmodel" => "new model",

	"comptn" => "����� ������������",
	"ogroup" => "����� ������ �����",
//	"caroption" => "����� �����",
	"ppgoption" => "new option",
	"brand" => "����� �������������",
	"color" => "new color",
	"colortone" => "����� �������",
	"surface" => "����� ��� ��������",
	"kuz" => "����� ��� �����",
	"gearbox" => "����� ��� �������",
	"engine" => "����� ��� ���������",
	"gearbox" => "����� ��� �������",
	"salon" => "����� ������ ������",
	"currency" => "new currency",
	"metrics" => "new measure of length",
	"news" => "�������",
	"banner" => "new banner",
	"bgroup" => "new banner group",

	"constant" => "new constant value",
	"mtpl" => "new mail template",
	"imgtype" => "new image type",
	"jsvalidator" => "����� JSValidator",

	"icwhose" => "����� ������",
	"ic" => "����� ������ � ������",
	"icdict" => "����� ����������",
	"icdictcontent" => "����� �������� �����������",
	"ictype" => "����� ��� ����� �����",

	"mmenu" => "new document",
);
*/
/*			$matches = array();
			preg_match("~^\S+\s+(.*)~", $new_entity_txt, $matches);
			pre($matches);
			if (isset($matches[1])) {
				$new_entity_txt = $matches[1];
			}
*/



?>