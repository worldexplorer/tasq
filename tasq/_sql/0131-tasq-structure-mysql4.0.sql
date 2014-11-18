-- MySQL dump 9.11
--
-- Host: localhost    Database: tasq
-- ------------------------------------------------------
-- Server version	4.0.20a-nt

--
-- Table structure for table `tasq_constant`
--

DROP TABLE IF EXISTS tasq_constant;
CREATE TABLE tasq_constant (
  id int(10) unsigned NOT NULL auto_increment,
  date_updated timestamp(14) NOT NULL,
  date_created timestamp(14) NOT NULL default '00000000000000',
  date_published timestamp(14) NOT NULL default '00000000000000',
  published tinyint(3) unsigned NOT NULL default '1',
  deleted tinyint(3) unsigned NOT NULL default '0',
  manorder int(10) unsigned NOT NULL default '0',
  ident varchar(250) NOT NULL default '',
  hashkey varchar(250) NOT NULL default '',
  content text,
  content_no_freetext tinyint(3) unsigned NOT NULL default '1',
  PRIMARY KEY  (id),
  UNIQUE KEY hashkey (hashkey)
) TYPE=MyISAM;

--
-- Table structure for table `tasq_currency`
--

DROP TABLE IF EXISTS tasq_currency;
CREATE TABLE tasq_currency (
  id int(10) unsigned NOT NULL auto_increment,
  date_updated timestamp(14) NOT NULL,
  date_created timestamp(14) NOT NULL default '00000000000000',
  date_published timestamp(14) NOT NULL default '00000000000000',
  published tinyint(3) unsigned NOT NULL default '1',
  deleted tinyint(3) unsigned NOT NULL default '0',
  manorder int(10) unsigned NOT NULL default '0',
  ident varchar(250) NOT NULL default '',
  hashkey varchar(250) NOT NULL default '',
  exchrate_rub float unsigned NOT NULL default '0',
  exchrate_rub_multiplier float unsigned NOT NULL default '1',
  date_exchrate_rub timestamp(14) NOT NULL default '00000000000000',
  date_expiration timestamp(14) NOT NULL default '00000000000000',
  expiration_minutes float unsigned NOT NULL default '60',
  scriptname_updated varchar(250) NOT NULL default '',
  src_href varchar(250) NOT NULL default '',
  src_content text,
  exchrate_regexp text,
  daterate_regexp text,
  comment varchar(250) NOT NULL default '',
  exchrate_eur float unsigned NOT NULL default '0',
  import_href varchar(250) NOT NULL default '',
  import_content text,
  import_regexp text,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `tasq_ic`
--

DROP TABLE IF EXISTS tasq_ic;
CREATE TABLE tasq_ic (
  id int(10) unsigned NOT NULL auto_increment,
  date_updated timestamp(14) NOT NULL,
  date_created timestamp(14) NOT NULL default '00000000000000',
  date_published timestamp(14) NOT NULL default '00000000000000',
  published tinyint(3) unsigned NOT NULL default '1',
  deleted tinyint(3) unsigned NOT NULL default '0',
  manorder int(10) unsigned NOT NULL default '0',
  ident varchar(250) NOT NULL default '',
  hashkey varchar(250) NOT NULL default '',
  icwhose int(10) unsigned NOT NULL default '0',
  icwhat int(10) unsigned NOT NULL default '0',
  ictype int(10) unsigned NOT NULL default '0',
  icdict int(10) unsigned NOT NULL default '0',
  param1 varchar(250) NOT NULL default '',
  param2 varchar(250) NOT NULL default '',
  graycomment varchar(250) NOT NULL default '',
  jsvalidator int(10) unsigned NOT NULL default '0',
  obligatory tinyint(3) unsigned NOT NULL default '0',
  obligatory_bo tinyint(3) unsigned NOT NULL default '0',
  inbrief tinyint(3) unsigned NOT NULL default '0',
  sorting tinyint(3) unsigned NOT NULL default '0',
  published_bo tinyint(3) unsigned NOT NULL default '1',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `tasq_icdict`
--

DROP TABLE IF EXISTS tasq_icdict;
CREATE TABLE tasq_icdict (
  id int(10) unsigned NOT NULL auto_increment,
  date_updated timestamp(14) NOT NULL,
  date_created timestamp(14) NOT NULL default '00000000000000',
  date_published timestamp(14) NOT NULL default '00000000000000',
  published tinyint(3) unsigned NOT NULL default '1',
  deleted tinyint(3) unsigned NOT NULL default '0',
  manorder int(10) unsigned NOT NULL default '0',
  ident varchar(250) NOT NULL default '',
  hashkey varchar(250) NOT NULL default '',
  icwhose int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `tasq_icdictcontent`
--

DROP TABLE IF EXISTS tasq_icdictcontent;
CREATE TABLE tasq_icdictcontent (
  id int(10) unsigned NOT NULL auto_increment,
  date_updated timestamp(14) NOT NULL,
  date_created timestamp(14) NOT NULL default '00000000000000',
  date_published timestamp(14) NOT NULL default '00000000000000',
  published tinyint(3) unsigned NOT NULL default '1',
  deleted tinyint(3) unsigned NOT NULL default '0',
  manorder int(10) unsigned NOT NULL default '0',
  ident varchar(250) NOT NULL default '',
  hashkey varchar(250) NOT NULL default '',
  content text,
  label_style varchar(250) NOT NULL default '',
  tf1_width int(10) unsigned NOT NULL default '0',
  tf1_incolumn tinyint(3) unsigned NOT NULL default '0',
  tf1_addtodict tinyint(3) unsigned NOT NULL default '0',
  tf1_addedpublished tinyint(3) unsigned NOT NULL default '0',
  icdict int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `tasq_icsheet`
--

DROP TABLE IF EXISTS tasq_icsheet;
CREATE TABLE tasq_icsheet (
  id int(10) unsigned NOT NULL auto_increment,
  date_updated timestamp(14) NOT NULL,
  date_created timestamp(14) NOT NULL default '00000000000000',
  date_published timestamp(14) NOT NULL default '00000000000000',
  published tinyint(3) unsigned NOT NULL default '1',
  deleted tinyint(3) unsigned NOT NULL default '0',
  manorder int(10) unsigned NOT NULL default '0',
  ident varchar(250) NOT NULL default '',
  icwhose int(10) unsigned NOT NULL default '0',
  content text,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `tasq_ictype`
--

DROP TABLE IF EXISTS tasq_ictype;
CREATE TABLE tasq_ictype (
  id int(10) unsigned NOT NULL auto_increment,
  date_updated timestamp(14) NOT NULL,
  date_created timestamp(14) NOT NULL default '00000000000000',
  date_published timestamp(14) NOT NULL default '00000000000000',
  published tinyint(3) unsigned NOT NULL default '0',
  deleted tinyint(3) unsigned NOT NULL default '0',
  manorder int(10) unsigned NOT NULL default '0',
  ident varchar(250) NOT NULL default '',
  hashkey varchar(250) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `tasq_icwhose`
--

DROP TABLE IF EXISTS tasq_icwhose;
CREATE TABLE tasq_icwhose (
  id int(10) unsigned NOT NULL auto_increment,
  date_updated timestamp(14) NOT NULL,
  date_created timestamp(14) NOT NULL default '00000000000000',
  date_published timestamp(14) NOT NULL default '00000000000000',
  published tinyint(3) unsigned NOT NULL default '1',
  deleted tinyint(3) unsigned NOT NULL default '0',
  manorder int(10) unsigned NOT NULL default '0',
  ident varchar(250) NOT NULL default '',
  hashkey varchar(250) NOT NULL default '',
  brief text,
  jsv_debug tinyint(3) unsigned NOT NULL default '0',
  bo_only tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `tasq_img`
--

DROP TABLE IF EXISTS tasq_img;
CREATE TABLE tasq_img (
  id int(10) unsigned NOT NULL auto_increment,
  date_updated timestamp(14) NOT NULL,
  date_created timestamp(14) NOT NULL default '00000000000000',
  date_published timestamp(14) NOT NULL default '00000000000000',
  published tinyint(3) unsigned NOT NULL default '0',
  deleted tinyint(3) unsigned NOT NULL default '0',
  manorder int(10) unsigned NOT NULL default '0',
  ident varchar(250) NOT NULL default '',
  img varchar(255) NOT NULL default '',
  img_w int(10) unsigned NOT NULL default '0',
  img_h int(10) unsigned NOT NULL default '0',
  img_txt varchar(255) NOT NULL default '',
  img_big varchar(255) NOT NULL default '',
  img_big_w int(10) unsigned NOT NULL default '0',
  img_big_h int(10) unsigned NOT NULL default '0',
  img_big_txt varchar(255) NOT NULL default '',
  owner_entity varchar(250) NOT NULL default '',
  owner_entity_id int(10) unsigned NOT NULL default '1',
  imgtype int(10) unsigned NOT NULL default '1',
  img_src varchar(250) NOT NULL default '',
  img_big_src varchar(250) NOT NULL default '',
  img_main tinyint(3) unsigned NOT NULL default '0',
  crc32 int(10) unsigned NOT NULL default '0',
  date_faceted timestamp(14) NOT NULL default '00000000000000',
  faceted tinyint(3) unsigned NOT NULL default '0',
  faceting tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY owner_entity (owner_entity,owner_entity_id),
  KEY imgtype (imgtype),
  KEY published (published,deleted),
  KEY img_txt (img_txt),
  KEY img_big_txt (img_big_txt),
  KEY faceted (faceted),
  KEY faceting (faceting)
) TYPE=MyISAM;

--
-- Table structure for table `tasq_imgtype`
--

DROP TABLE IF EXISTS tasq_imgtype;
CREATE TABLE tasq_imgtype (
  id int(10) unsigned NOT NULL auto_increment,
  date_updated timestamp(14) NOT NULL,
  date_created timestamp(14) NOT NULL default '00000000000000',
  date_published timestamp(14) NOT NULL default '00000000000000',
  published tinyint(3) unsigned NOT NULL default '1',
  deleted tinyint(3) unsigned NOT NULL default '0',
  manorder int(10) unsigned NOT NULL default '0',
  ident varchar(250) NOT NULL default '',
  hashkey varchar(250) NOT NULL default '',
  content text,
  imglimit int(10) unsigned NOT NULL default '0',
  merge_seed int(10) unsigned NOT NULL default '0',
  resize_default_qlty varchar(250) NOT NULL default '85',
  resize_default_width varchar(250) NOT NULL default '',
  resize_default_height varchar(250) NOT NULL default '',
  resize_published tinyint(3) unsigned NOT NULL default '1',
  resize_default_checked tinyint(3) unsigned NOT NULL default '0',
  big_resize_default_qlty varchar(250) NOT NULL default '85',
  big_resize_default_width varchar(250) NOT NULL default '',
  big_resize_default_height varchar(250) NOT NULL default '',
  big_resize_published tinyint(3) unsigned NOT NULL default '1',
  big_resize_default_checked tinyint(3) unsigned NOT NULL default '0',
  first_autoresize_qlty varchar(250) NOT NULL default '85',
  first_autoresize_width varchar(250) NOT NULL default '',
  first_autoresize_height varchar(250) NOT NULL default '',
  first_autoresize_apply tinyint(3) unsigned NOT NULL default '0',
  first_autoresize_debug tinyint(3) unsigned NOT NULL default '0',
  first_autoresize_tpl_ex text,
  first_autoresize_tpl_nex text,
  first_merge_img varchar(250) NOT NULL default '',
  first_merge_dstfname varchar(250) NOT NULL default '',
  first_merge_alfa tinyint(3) unsigned NOT NULL default '30',
  first_merge_type tinyint(3) unsigned NOT NULL default '0',
  first_merge_apply tinyint(3) unsigned NOT NULL default '1',
  every_autoresize_qlty varchar(250) NOT NULL default '85',
  every_autoresize_width varchar(250) NOT NULL default '',
  every_autoresize_height varchar(250) NOT NULL default '',
  every_autoresize_apply tinyint(3) unsigned NOT NULL default '0',
  every_autoresize_debug tinyint(3) unsigned NOT NULL default '0',
  every_autoresize_tpl_ex text,
  every_autoresize_tpl_nex text,
  every_merge_img varchar(250) NOT NULL default '',
  every_merge_dstfname varchar(250) NOT NULL default '',
  every_merge_alfa tinyint(3) unsigned NOT NULL default '30',
  every_merge_type tinyint(3) unsigned NOT NULL default '0',
  every_merge_apply tinyint(3) unsigned NOT NULL default '1',
  first2_autoresize_qlty varchar(250) NOT NULL default '85',
  first2_autoresize_width varchar(250) NOT NULL default '',
  first2_autoresize_height varchar(250) NOT NULL default '',
  first2_autoresize_firstonly tinyint(3) unsigned NOT NULL default '1',
  first2_autoresize_apply tinyint(3) unsigned NOT NULL default '0',
  first2_autoresize_debug tinyint(3) unsigned NOT NULL default '0',
  first2_autoresize_tpl_ex text,
  first2_autoresize_tpl_nex text,
  first2_merge_img varchar(250) NOT NULL default '',
  first2_merge_dstfname varchar(250) NOT NULL default '',
  first2_merge_alfa tinyint(3) unsigned NOT NULL default '30',
  first2_merge_type tinyint(3) unsigned NOT NULL default '0',
  first2_merge_apply tinyint(3) unsigned NOT NULL default '1',
  every2_autoresize_qlty varchar(250) NOT NULL default '85',
  every2_autoresize_width varchar(250) NOT NULL default '',
  every2_autoresize_height varchar(250) NOT NULL default '',
  every2_autoresize_firstonly tinyint(3) unsigned NOT NULL default '1',
  every2_autoresize_apply tinyint(3) unsigned NOT NULL default '0',
  every2_autoresize_debug tinyint(3) unsigned NOT NULL default '0',
  every2_autoresize_tpl_ex text,
  every2_autoresize_tpl_nex text,
  every2_merge_img varchar(250) NOT NULL default '',
  every2_merge_dstfname varchar(250) NOT NULL default '',
  every2_merge_alfa tinyint(3) unsigned NOT NULL default '30',
  every2_merge_type tinyint(3) unsigned NOT NULL default '0',
  every2_merge_apply tinyint(3) unsigned NOT NULL default '1',
  first3_autoresize_qlty varchar(250) NOT NULL default '85',
  first3_autoresize_width varchar(250) NOT NULL default '',
  first3_autoresize_height varchar(250) NOT NULL default '',
  first3_autoresize_firstonly tinyint(3) unsigned NOT NULL default '1',
  first3_autoresize_apply tinyint(3) unsigned NOT NULL default '0',
  first3_autoresize_debug tinyint(3) unsigned NOT NULL default '0',
  first3_autoresize_tpl_ex text,
  first3_autoresize_tpl_nex text,
  first3_merge_img varchar(250) NOT NULL default '',
  first3_merge_dstfname varchar(250) NOT NULL default '',
  first3_merge_alfa tinyint(3) unsigned NOT NULL default '30',
  first3_merge_type tinyint(3) unsigned NOT NULL default '0',
  first3_merge_apply tinyint(3) unsigned NOT NULL default '1',
  every3_autoresize_qlty varchar(250) NOT NULL default '85',
  every3_autoresize_width varchar(250) NOT NULL default '',
  every3_autoresize_height varchar(250) NOT NULL default '',
  every3_autoresize_firstonly tinyint(3) unsigned NOT NULL default '1',
  every3_autoresize_apply tinyint(3) unsigned NOT NULL default '0',
  every3_autoresize_debug tinyint(3) unsigned NOT NULL default '0',
  every3_autoresize_tpl_ex text,
  every3_autoresize_tpl_nex text,
  every3_merge_img varchar(250) NOT NULL default '',
  every3_merge_dstfname varchar(250) NOT NULL default '',
  every3_merge_alfa tinyint(3) unsigned NOT NULL default '30',
  every3_merge_type tinyint(3) unsigned NOT NULL default '0',
  every3_merge_apply tinyint(3) unsigned NOT NULL default '1',
  first4_autoresize_qlty varchar(250) NOT NULL default '85',
  first4_autoresize_width varchar(250) NOT NULL default '',
  first4_autoresize_height varchar(250) NOT NULL default '',
  first4_autoresize_firstonly tinyint(3) unsigned NOT NULL default '1',
  first4_autoresize_apply tinyint(3) unsigned NOT NULL default '0',
  first4_autoresize_debug tinyint(3) unsigned NOT NULL default '0',
  first4_autoresize_tpl_ex text,
  first4_autoresize_tpl_nex text,
  first4_merge_img varchar(250) NOT NULL default '',
  first4_merge_dstfname varchar(250) NOT NULL default '',
  first4_merge_alfa tinyint(3) unsigned NOT NULL default '30',
  first4_merge_type tinyint(3) unsigned NOT NULL default '0',
  first4_merge_apply tinyint(3) unsigned NOT NULL default '1',
  every4_autoresize_qlty varchar(250) NOT NULL default '85',
  every4_autoresize_width varchar(250) NOT NULL default '',
  every4_autoresize_height varchar(250) NOT NULL default '',
  every4_autoresize_firstonly tinyint(3) unsigned NOT NULL default '1',
  every4_autoresize_apply tinyint(3) unsigned NOT NULL default '0',
  every4_autoresize_debug tinyint(3) unsigned NOT NULL default '0',
  every4_autoresize_tpl_ex text,
  every4_autoresize_tpl_nex text,
  every4_merge_img varchar(250) NOT NULL default '',
  every4_merge_dstfname varchar(250) NOT NULL default '',
  every4_merge_alfa tinyint(3) unsigned NOT NULL default '30',
  every4_merge_type tinyint(3) unsigned NOT NULL default '0',
  every4_merge_apply tinyint(3) unsigned NOT NULL default '1',
  img_present tinyint(3) unsigned NOT NULL default '1',
  img_newqnty tinyint(3) unsigned NOT NULL default '1',
  img_txt_present tinyint(3) unsigned NOT NULL default '1',
  img_txt_eq_fname tinyint(3) unsigned NOT NULL default '1',
  img_url_present tinyint(3) unsigned NOT NULL default '1',
  img_zip_present tinyint(3) unsigned NOT NULL default '1',
  img_big_present tinyint(3) unsigned NOT NULL default '1',
  img_big_newqnty tinyint(3) unsigned NOT NULL default '1',
  img_big_txt_present tinyint(3) unsigned NOT NULL default '1',
  img_big_txt_eq_fname tinyint(3) unsigned NOT NULL default '1',
  img_big_url_present tinyint(3) unsigned NOT NULL default '1',
  img_big_zip_present tinyint(3) unsigned NOT NULL default '1',
  img_thumb_present tinyint(3) unsigned NOT NULL default '1',
  img_thumb_qlty varchar(250) NOT NULL default '85',
  img_thumb_width varchar(250) NOT NULL default '',
  img_thumb_height varchar(250) NOT NULL default '80',
  msg_ident varchar(250) NOT NULL default 'Картинка',
  msg_change varchar(250) NOT NULL default 'изменить картинку',
  msg_add varchar(250) NOT NULL default 'Новая картинка',
  msg_img varchar(250) NOT NULL default 'маленькая',
  msg_img_big varchar(250) NOT NULL default 'большая',
  img_table varchar(250) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `tasq_jsvalidator`
--

DROP TABLE IF EXISTS tasq_jsvalidator;
CREATE TABLE tasq_jsvalidator (
  id int(10) unsigned NOT NULL auto_increment,
  date_updated timestamp(14) NOT NULL,
  date_created timestamp(14) NOT NULL default '00000000000000',
  date_published timestamp(14) NOT NULL default '00000000000000',
  published tinyint(3) unsigned NOT NULL default '1',
  deleted tinyint(3) unsigned NOT NULL default '0',
  manorder int(10) unsigned NOT NULL default '0',
  ident varchar(250) NOT NULL default '',
  hashkey varchar(250) NOT NULL default '',
  content text,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `tasq_m2m_task_iccontent`
--

DROP TABLE IF EXISTS tasq_m2m_task_iccontent;
CREATE TABLE tasq_m2m_task_iccontent (
  id int(10) unsigned NOT NULL auto_increment,
  date_updated timestamp(14) NOT NULL,
  date_created timestamp(14) NOT NULL default '00000000000000',
  date_published timestamp(14) NOT NULL default '00000000000000',
  published tinyint(3) unsigned NOT NULL default '1',
  deleted tinyint(3) unsigned NOT NULL default '0',
  manorder int(10) unsigned NOT NULL default '0',
  ident varchar(250) NOT NULL default '',
  task int(10) unsigned NOT NULL default '0',
  ic int(10) unsigned NOT NULL default '0',
  iccontent text,
  iccontent_tf1 varchar(250) NOT NULL default '',
  PRIMARY KEY  (id),
  KEY task (task,ic)
) TYPE=MyISAM;

--
-- Table structure for table `tasq_m2m_task_news`
--

DROP TABLE IF EXISTS tasq_m2m_task_news;
CREATE TABLE tasq_m2m_task_news (
  id int(10) unsigned NOT NULL auto_increment,
  date_updated timestamp(14) NOT NULL,
  date_created timestamp(14) NOT NULL default '00000000000000',
  date_published timestamp(14) NOT NULL default '00000000000000',
  published tinyint(3) unsigned NOT NULL default '1',
  deleted tinyint(3) unsigned NOT NULL default '0',
  manorder int(10) unsigned NOT NULL default '0',
  ident varchar(250) default '',
  task int(10) unsigned NOT NULL default '0',
  news int(10) unsigned NOT NULL default '0',
  content text,
  PRIMARY KEY  (id),
  KEY task (task,news)
) TYPE=MyISAM;

--
-- Table structure for table `tasq_m2m_task_tgroup`
--

DROP TABLE IF EXISTS tasq_m2m_task_tgroup;
CREATE TABLE tasq_m2m_task_tgroup (
  id int(10) unsigned NOT NULL auto_increment,
  date_updated timestamp(14) NOT NULL,
  date_created timestamp(14) NOT NULL default '00000000000000',
  date_published timestamp(14) NOT NULL default '00000000000000',
  published tinyint(3) unsigned NOT NULL default '1',
  deleted tinyint(3) unsigned NOT NULL default '0',
  manorder int(10) unsigned NOT NULL default '0',
  ident varchar(250) default '',
  task int(10) unsigned NOT NULL default '0',
  tgroup int(10) unsigned NOT NULL default '0',
  content text,
  PRIMARY KEY  (id),
  KEY task (task,tgroup)
) TYPE=MyISAM;

--
-- Table structure for table `tasq_m2m_task_tstatus`
--

DROP TABLE IF EXISTS tasq_m2m_task_tstatus;
CREATE TABLE tasq_m2m_task_tstatus (
  id int(10) unsigned NOT NULL auto_increment,
  date_updated timestamp(14) NOT NULL,
  date_created timestamp(14) NOT NULL default '00000000000000',
  date_published timestamp(14) NOT NULL default '00000000000000',
  published tinyint(3) unsigned NOT NULL default '1',
  deleted tinyint(3) unsigned NOT NULL default '0',
  manorder int(10) unsigned NOT NULL default '0',
  ident varchar(250) default '',
  task int(10) unsigned NOT NULL default '0',
  tstatus int(10) unsigned NOT NULL default '0',
  content text,
  PRIMARY KEY  (id),
  KEY task (task,tstatus)
) TYPE=MyISAM;

--
-- Table structure for table `tasq_mmenu`
--

DROP TABLE IF EXISTS tasq_mmenu;
CREATE TABLE tasq_mmenu (
  id int(10) unsigned NOT NULL auto_increment,
  date_updated timestamp(14) NOT NULL,
  date_created timestamp(14) NOT NULL default '00000000000000',
  date_published timestamp(14) NOT NULL default '00000000000000',
  published tinyint(3) unsigned NOT NULL default '1',
  deleted tinyint(3) unsigned NOT NULL default '0',
  manorder int(10) unsigned NOT NULL default '0',
  ident varchar(250) NOT NULL default '',
  parent_id int(10) unsigned NOT NULL default '1',
  hashkey varchar(250) NOT NULL default '',
  is_heredoc tinyint(4) NOT NULL default '1',
  is_drone tinyint(4) NOT NULL default '0',
  annotation text,
  brief text,
  brief_no_freetext tinyint(3) unsigned NOT NULL default '0',
  content text,
  content_no_freetext tinyint(3) unsigned NOT NULL default '0',
  img_free varchar(250) NOT NULL default '',
  img_mover varchar(250) NOT NULL default '',
  img_small_free varchar(250) NOT NULL default '',
  img_small_mover varchar(250) NOT NULL default '',
  img_small_current varchar(250) NOT NULL default '',
  img_ctx_left varchar(250) NOT NULL default '',
  img_ctx_right varchar(250) NOT NULL default '',
  img_ctx_top varchar(250) NOT NULL default '',
  file1 varchar(250) NOT NULL default '',
  file1_comment text,
  file2 varchar(250) NOT NULL default '',
  file2_comment text,
  file3 varchar(250) NOT NULL default '',
  file3_comment text,
  file4 varchar(250) NOT NULL default '',
  file4_comment text,
  file5 varchar(250) NOT NULL default '',
  file5_comment text,
  pagetitle text,
  title text,
  meta_keywords text,
  meta_description text,
  tpl_list_item text,
  tpl_list_wrapper text,
  published_legend tinyint(3) unsigned NOT NULL default '0',
  manorder_legend int(10) unsigned NOT NULL default '0',
  published_sitemap tinyint(3) unsigned NOT NULL default '1',
  PRIMARY KEY  (id),
  KEY parent_id (parent_id)
) TYPE=MyISAM;

--
-- Table structure for table `tasq_mtpl`
--

DROP TABLE IF EXISTS tasq_mtpl;
CREATE TABLE tasq_mtpl (
  id int(10) unsigned NOT NULL auto_increment,
  date_updated timestamp(14) NOT NULL,
  date_created timestamp(14) NOT NULL default '00000000000000',
  date_published timestamp(14) NOT NULL default '00000000000000',
  published tinyint(3) unsigned NOT NULL default '1',
  deleted tinyint(3) unsigned NOT NULL default '0',
  manorder int(10) unsigned NOT NULL default '0',
  ident varchar(250) NOT NULL default '',
  hashkey varchar(250) NOT NULL default '',
  subject text,
  body text,
  body_no_freetext tinyint(3) unsigned NOT NULL default '1',
  rcptto varchar(250) NOT NULL default '',
  sentmsg varchar(250) NOT NULL default '',
  admtail text,
  savesentlog tinyint(3) unsigned NOT NULL default '1',
  PRIMARY KEY  (id),
  UNIQUE KEY hashkey (hashkey)
) TYPE=MyISAM;

--
-- Table structure for table `tasq_news`
--

DROP TABLE IF EXISTS tasq_news;
CREATE TABLE tasq_news (
  id int(10) unsigned NOT NULL auto_increment,
  date_updated timestamp(14) NOT NULL,
  date_created timestamp(14) NOT NULL default '00000000000000',
  date_published timestamp(14) NOT NULL default '00000000000000',
  published tinyint(3) unsigned NOT NULL default '1',
  deleted tinyint(3) unsigned NOT NULL default '0',
  manorder int(10) unsigned NOT NULL default '0',
  ident varchar(250) NOT NULL default '',
  brief text,
  brief_no_freetext tinyint(3) unsigned NOT NULL default '0',
  content text,
  content_no_freetext tinyint(3) unsigned NOT NULL default '0',
  ngroup tinyint(3) unsigned NOT NULL default '2',
  hrefto varchar(250) NOT NULL default '',
  srcurl text,
  i_published tinyint(4) NOT NULL default '1',
  i_manorder int(10) unsigned NOT NULL default '0',
  rsss int(11) NOT NULL default '0',
  rss_published tinyint(4) NOT NULL default '1',
  file1 varchar(250) NOT NULL default '',
  file1_comment text,
  file2 varchar(250) NOT NULL default '',
  file2_comment text,
  file3 varchar(250) NOT NULL default '',
  file3_comment text,
  file4 varchar(250) NOT NULL default '',
  file4_comment text,
  file5 varchar(250) NOT NULL default '',
  file5_comment text,
  pagetitle varchar(250) NOT NULL default '',
  title varchar(250) NOT NULL default '',
  meta_keywords text,
  meta_description text,
  banner_top int(10) unsigned NOT NULL default '0',
  hits int(10) unsigned NOT NULL default '0',
  archived tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `tasq_ngroup`
--

DROP TABLE IF EXISTS tasq_ngroup;
CREATE TABLE tasq_ngroup (
  id int(10) unsigned NOT NULL auto_increment,
  date_updated timestamp(14) NOT NULL,
  date_created timestamp(14) NOT NULL default '00000000000000',
  date_published timestamp(14) NOT NULL default '00000000000000',
  published tinyint(3) unsigned NOT NULL default '1',
  deleted tinyint(3) unsigned NOT NULL default '0',
  manorder int(10) unsigned NOT NULL default '0',
  ident varchar(250) NOT NULL default '',
  brief text,
  parent_id int(10) unsigned NOT NULL default '1',
  file1 varchar(250) NOT NULL default '',
  file1_comment text,
  file2 varchar(250) NOT NULL default '',
  file2_comment text,
  file3 varchar(250) NOT NULL default '',
  file3_comment text,
  file4 varchar(250) NOT NULL default '',
  file4_comment text,
  file5 varchar(250) NOT NULL default '',
  file5_comment text,
  pagetitle varchar(250) NOT NULL default '',
  title varchar(250) NOT NULL default '',
  meta_keywords text,
  meta_description text,
  banner_top int(10) unsigned NOT NULL default '0',
  hits int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `tasq_project`
--

DROP TABLE IF EXISTS tasq_project;
CREATE TABLE tasq_project (
  id int(10) unsigned NOT NULL auto_increment,
  date_updated timestamp(14) NOT NULL,
  date_created timestamp(14) NOT NULL default '00000000000000',
  date_published timestamp(14) NOT NULL default '00000000000000',
  published tinyint(3) unsigned NOT NULL default '1',
  deleted tinyint(3) unsigned NOT NULL default '0',
  manorder int(10) unsigned NOT NULL default '0',
  ident varchar(250) NOT NULL default '',
  brief text,
  content text,
  hrefto varchar(250) NOT NULL default '',
  contact text,
  PRIMARY KEY  (id),
  KEY published (published),
  KEY deleted (deleted)
) TYPE=MyISAM;

--
-- Table structure for table `tasq_sentlog`
--

DROP TABLE IF EXISTS tasq_sentlog;
CREATE TABLE tasq_sentlog (
  id int(10) unsigned NOT NULL auto_increment,
  date_updated timestamp(14) NOT NULL,
  date_created timestamp(14) NOT NULL default '00000000000000',
  date_published timestamp(14) NOT NULL default '00000000000000',
  published tinyint(3) unsigned NOT NULL default '0',
  deleted tinyint(3) unsigned NOT NULL default '0',
  manorder int(10) unsigned NOT NULL default '0',
  ident varchar(250) NOT NULL default '',
  mtpl int(10) unsigned NOT NULL default '0',
  content text,
  remote_address varchar(250) NOT NULL default '',
  PRIMARY KEY  (id),
  KEY mtpl (mtpl)
) TYPE=MyISAM;

--
-- Table structure for table `tasq_task`
--

DROP TABLE IF EXISTS tasq_task;
CREATE TABLE tasq_task (
  id int(10) unsigned NOT NULL auto_increment,
  date_updated timestamp(14) NOT NULL,
  date_created timestamp(14) NOT NULL default '00000000000000',
  date_published timestamp(14) NOT NULL default '00000000000000',
  published tinyint(3) unsigned NOT NULL default '1',
  deleted tinyint(3) unsigned NOT NULL default '0',
  manorder int(10) unsigned NOT NULL default '0',
  ident varchar(250) NOT NULL default '',
  article varchar(250) NOT NULL default '',
  project int(10) unsigned NOT NULL default '0',
  tgroup int(10) unsigned NOT NULL default '0',
  mayorder tinyint(3) unsigned NOT NULL default '1',
  price_1 float unsigned NOT NULL default '0',
  currency_1 int(10) unsigned NOT NULL default '1',
  pricecomment_1 varchar(250) NOT NULL default '',
  efforts_1 float unsigned NOT NULL default '0',
  price_2 float unsigned NOT NULL default '0',
  currency_2 int(10) unsigned NOT NULL default '0',
  pricecomment_2 varchar(250) NOT NULL default '',
  efforts_2 float unsigned NOT NULL default '0',
  price_3 float unsigned NOT NULL default '0',
  currency_3 int(10) unsigned NOT NULL default '0',
  pricecomment_3 varchar(250) NOT NULL default '',
  efforts_3 float unsigned NOT NULL default '0',
  request text,
  request_no_freetext tinyint(3) unsigned NOT NULL default '0',
  discussion text,
  discussion_no_freetext tinyint(3) unsigned NOT NULL default '0',
  response text,
  response_no_freetext tinyint(3) unsigned NOT NULL default '0',
  i_manorder int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY project (project),
  KEY tgroup (tgroup),
  KEY published (published),
  KEY deleted (deleted),
  KEY ident (ident)
) TYPE=MyISAM;

--
-- Table structure for table `tasq_tgroup`
--

DROP TABLE IF EXISTS tasq_tgroup;
CREATE TABLE tasq_tgroup (
  id int(10) unsigned NOT NULL auto_increment,
  date_updated timestamp(14) NOT NULL,
  date_created timestamp(14) NOT NULL default '00000000000000',
  date_published timestamp(14) NOT NULL default '00000000000000',
  published tinyint(3) unsigned NOT NULL default '1',
  deleted tinyint(3) unsigned NOT NULL default '0',
  manorder int(10) unsigned NOT NULL default '0',
  ident varchar(250) NOT NULL default '',
  brief text,
  brief_no_freetext tinyint(3) unsigned NOT NULL default '0',
  content text,
  content_no_freetext tinyint(3) unsigned NOT NULL default '0',
  parent_id int(10) unsigned NOT NULL default '1',
  file1 varchar(250) NOT NULL default '',
  file1_comment text,
  file2 varchar(250) NOT NULL default '',
  file2_comment text,
  file3 varchar(250) NOT NULL default '',
  file3_comment text,
  file4 varchar(250) NOT NULL default '',
  file4_comment text,
  file5 varchar(250) NOT NULL default '',
  file5_comment text,
  i_published tinyint(4) NOT NULL default '0',
  i_manorder int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY parent_id (parent_id),
  KEY published (published),
  KEY deleted (deleted),
  KEY ident (ident)
) TYPE=MyISAM;

--
-- Table structure for table `tasq_tstatus`
--

DROP TABLE IF EXISTS tasq_tstatus;
CREATE TABLE tasq_tstatus (
  id int(10) unsigned NOT NULL auto_increment,
  date_updated timestamp(14) NOT NULL,
  date_created timestamp(14) NOT NULL default '00000000000000',
  date_published timestamp(14) NOT NULL default '00000000000000',
  published tinyint(3) unsigned NOT NULL default '0',
  deleted tinyint(3) unsigned NOT NULL default '0',
  manorder int(10) unsigned NOT NULL default '0',
  ident varchar(250) NOT NULL default '',
  hashkey varchar(250) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

