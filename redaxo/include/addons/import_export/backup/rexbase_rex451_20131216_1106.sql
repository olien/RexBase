## Redaxo Database Dump Version 4
## Prefix rex_
## charset utf-8

DROP TABLE IF EXISTS `rex_420_xoutputfilter`;
CREATE TABLE `rex_420_xoutputfilter` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `typ` tinyint(1) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `lang` int(11) NOT NULL DEFAULT '0',
  `marker` text NOT NULL,
  `html` text NOT NULL,
  `allcats` tinyint(1) NOT NULL DEFAULT '0',
  `subcats` tinyint(1) NOT NULL DEFAULT '0',
  `once` tinyint(1) NOT NULL DEFAULT '0',
  `categories` text NOT NULL,
  `insertbefore` tinyint(1) NOT NULL DEFAULT '0',
  `excludeids` text NOT NULL,
  `useragent` text NOT NULL,
  `dataarea` text NOT NULL,
  `validfrom` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `validto` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

LOCK TABLES `rex_420_xoutputfilter` WRITE;
/*!40000 ALTER TABLE `rex_420_xoutputfilter` DISABLE KEYS */;
INSERT INTO `rex_420_xoutputfilter` VALUES 
  (1,4,1,'Beispiel_003','Alle HTML-Kommentare entfernen - ausser Conditional Comments für IE',0,'/<!--[^\\[](.|\\s)*?[^\\]]-->/','',1,1,0,'',3,'','','','0000-00-00 00:00:00','0000-00-00 00:00:00'),
  (2,4,1,'Beispiel_005','Ungeschlossene Tags schliessen (<br>, <hr>, <img>, <input>, <meta>, <base>, <basefont>, <param>, <link>, <area>)',0,'/\r\n<(br|hr|img|input|meta|base|basefont|param|link|area)\r\n+\r\n((\\s+[a-zA-Z-]+\\s*=\\s*(\"([^\"]*)\"|\'([^\']*)\'|([a-zA-Z0-9]*)))*\\s*)>\r\n/ix','<$1$2 />',1,1,0,'',3,'','','','0000-00-00 00:00:00','0000-00-00 00:00:00'),
  (3,4,0,'Beispiel_006','Leere HTML-Tags entfernen (<p>, <span>, <strong>, <b>, <em>, <h1>, <h2>, <h3>, <h4>, <h5>, <h6>)',0,'/<(p|span|strong|b|em|h1|h2|h3|h4|h5|h6)>(\\s|\\b)*<\\/\\1>/','',1,1,0,'',3,'','','','0000-00-00 00:00:00','0000-00-00 00:00:00'),
  (4,4,1,'Beispiel_007','Fehlende alt-Attribute bei <img>-Tags einfügen',0,'/(?!<img[^>]*\\salt[^=>]*=[^>]*>)<img[^>](.*)(>)/','<img alt=\"\" $1>',1,1,0,'',3,'','','','0000-00-00 00:00:00','0000-00-00 00:00:00'),
  (5,4,1,'Beispiel_008','Zeichen und Tags ersetzen / entfernen',0,'<head>','<?php\r\n/**\r\n * Zeichen und Tags ersetzen / entfernen\r\n */\r\nglobal $REX;\r\n\r\n  $xoutputfilter_codereplace = array(\r\n  \'<b>\' => \'<strong>\' ,\r\n  \'</b>\' => \'</strong>\' ,\r\n  \'<i>\' => \'<em>\' ,\r\n  \'</i>\' => \'</em>\' ,\r\n  \'ä\' => \'&auml;\' ,\r\n  \'ö\' => \'&ouml;\' ,\r\n  \'ü\' => \'&uuml;\' ,\r\n  \'Ä\' => \'&Auml;\' ,\r\n  \'Ö\' => \'&Ouml;\' ,\r\n  \'Ü\' => \'&Uuml;\' ,\r\n  \'ß\' => \'&szlig;\',\r\n  \'(c)\' => \'&copy;\',\r\n  \' ismap=\"ismap\"\' => \'\',\r\n  \' ismap=\"true\"\' => \'\',\r\n  \' target=\"_self\"\' => \'\',\r\n  \' target=\"_blank\"\' => \' onclick=\"window.open(this.href); return false;\"\',\r\n  \'<div align=\"center\">\' => \'<div style=\"text-align:center;\">\',\r\n  \'<hr width=\"100%\" size=\"2\" />\' => \'<hr />\'\r\n  );\r\n\r\n  $search = array();\r\n  $replace = array();\r\n  foreach ($xoutputfilter_codereplace as $key => $value)\r\n  {\r\n    $search[] = $key;\r\n    $replace[] = $value;\r\n  }\r\n  $REX[\'xoutputfilter\'][\'content\'] = str_replace($search, $replace, $REX[\'xoutputfilter\'][\'content\']);\r\n?>',1,1,1,'',4,'','','','0000-00-00 00:00:00','0000-00-00 00:00:00'),
  (6,4,1,'Beispiel_010','E-Mail-Adressen mit Klasse email versehen und verschlüsseln',0,'mailto:','<?php\r\n/**\r\n * E-Mail Adressen verschlüsseln\r\n */\r\nglobal $REX;\r\n\r\n  // hier via regEx alle absoluten, externen Linkadressen heraussuchen\r\n  preg_match_all(\"/<a[^>]*(href\\s*=\\s*(\\\"|\')(mailto)(?=:).*?(\\\"|\'))[^>]*>(.*?)<\\/a>/ims\", $REX[\'xoutputfilter\'][\'content\'], $matches);\r\n\r\n  // hier jetzt alle gefundenen durchgehen und um klasse erweitern\r\n  if ( isset ($matches[0][0]) and $matches[0][0] != \'\')\r\n  {\r\n    for ($m = 0; $m < count ($matches[0]); $m++)\r\n    {\r\n      $msearch = $matches[0][$m];\r\n      if (strstr($matches[0][$m], \'class=\'))\r\n      {\r\n        $mreplace = $matches[0][$m];\r\n      }\r\n      else\r\n      {\r\n        $mreplace = str_replace(\'href=\', \'class=\"email\" href=\', $matches[0][$m]);\r\n      }\r\n      $REX[\'xoutputfilter\'][\'content\'] = str_replace($msearch, $mreplace, $REX[\'xoutputfilter\'][\'content\']);\r\n    }\r\n  }\r\n\r\n  // hier jetzt alle gefundenen durchgehen und crypt\r\n  if ( isset ($matches[1][0]) and $matches[1][0] != \'\')\r\n  {\r\n    for ($m = 0; $m < count ($matches[1]); $m++)\r\n    {\r\n      $va = explode(\'mailto:\', $matches[1][$m]);\r\n      $originalemail = str_replace(\'\"\', \'\', $va[1]);\r\n\r\n      $encryptedemail = \'\';\r\n      for ($i=0; $i < strlen($originalemail); $i++) {\r\n        $encryptedemail .= \'&#\'.ord(substr($originalemail, $i, 1)).\';\';\r\n      }\r\n\r\n      $msearch = \'mailto:\'.$originalemail;\r\n      $mreplace = \'mailto:\'.str_replace(\'&#64;\', \'%40\', $encryptedemail);\r\n      $REX[\'xoutputfilter\'][\'content\'] = str_replace($msearch, $mreplace, $REX[\'xoutputfilter\'][\'content\']);\r\n\r\n      $msearch = $originalemail;\r\n      $mreplace = $encryptedemail;\r\n      $REX[\'xoutputfilter\'][\'content\'] = str_replace($msearch, $mreplace, $REX[\'xoutputfilter\'][\'content\']);\r\n    }\r\n  }\r\n?>',1,1,1,'',4,'','','','0000-00-00 00:00:00','0000-00-00 00:00:00'),
  (7,4,1,'Beispiel_012','Externe Links mit Klasse extern versehen',0,'href=\"http:','<?php\r\n/**\r\n * Kennzeichnung externe Links mit Ausnahme\r\n */\r\nglobal $REX;\r\n\r\n  //$content = $REX[\'xoutputfilter\'][\'content\'];\r\n\r\n  // Von der Ersetzung ausgeschlossen:\r\n  $excl = array();\r\n  $excl[] = \'href=\"\' . $REX[\'SERVER\'];\r\n  $excl[] = \'href=\"http://\' . $_SERVER[\'HTTP_HOST\'];\r\n  $excl[] = \'href=\"https://\' . $_SERVER[\'HTTP_HOST\'];\r\n  $excl[] = \'#top\';\r\n  $excl[] = \'#nav\';\r\n  $excl[] = \'#mainnav\';\r\n  $excl[] = \'#hauptnavigation\';\r\n  $excl[] = \'#content\';\r\n  $excl[] = \'href=\"http://www.facebook.com/\';\r\n  $excl[] = \'href=\"http://twitter.com/\';\r\n\r\n  // hier via regEx alle absoluten, externen Linkadressen heraussuchen\r\n  preg_match_all(\"/<a[^>]*(href\\s*=\\s*(\\\"|\')(http(s)?|ftp(s)?|telnet|irc)(?=:\\/\\/).*?(\\\"|\'))[^>]*>(.*?)<\\/a>/ims\", $REX[\'xoutputfilter\'][\'content\'], $matches);\r\n\r\n  if ( isset ($matches[0][0]) and $matches[0][0] != \'\')\r\n  {\r\n    $srch = $repl = array();\r\n    for ($m = 0; $m < count ($matches[0]); $m++)\r\n    {\r\n      $msearch = $matches[0][$m];\r\n      if (strstr($matches[0][$m], \'class=\'))\r\n      {\r\n        $mreplace = $matches[0][$m];\r\n      }\r\n      else\r\n      {\r\n        $mreplace = str_replace(\'href=\', \'class=\"extern\" href=\', $matches[0][$m]);\r\n      }\r\n      for ($x = 0; $x < count($excl); $x++)\r\n      {\r\n        if (strstr($matches[1][$m], $excl[$x]))\r\n        {\r\n          $mreplace = $matches[0][$m];\r\n        }\r\n      }\r\n      $srch[] = $msearch;\r\n      $repl[] = $mreplace;\r\n    }\r\n    $REX[\'xoutputfilter\'][\'content\'] = str_replace($srch, $repl, $REX[\'xoutputfilter\'][\'content\']);\r\n  }  \r\n?>',1,1,1,'',4,'','','','0000-00-00 00:00:00','0000-00-00 00:00:00'),
  (8,4,0,'Beispiel_013','Wartungsarbeiten - Nicht angemeldete Benutzer auf eine Wartungsseite umleiten',0,'<head>','<?php\r\n/**\r\n* Wartungsarbeiten\r\n*/\r\n  global $REX;\r\n\r\n  // Hier die URL angeben auf die weitergeleitet werden soll!\r\n  $offline_url = $REX[\'SERVER\'] . \'wartungsarbeiten.html\';\r\n\r\n  // evtl. Weiterleitung\r\n  @session_start();\r\n  $islogon = false;\r\n  \r\n  if (isset($_SESSION[$REX[\'INSTNAME\']]) and isset($_SESSION[$REX[\'INSTNAME\']][\'UID\']) and $_SESSION[$REX[\'INSTNAME\']][\'UID\']<>\'\')\r\n  {\r\n    $islogon = true;\r\n  }\r\n\r\n  if (!$islogon) \r\n  {\r\n    if ( !strstr($_SERVER[\"REQUEST_URI\"], \'&maintenance\') )\r\n    {\r\n      $trash = ob_get_contents();\r\n      ob_end_clean();\r\n      if (strstr($offline_url, \'?\'))\r\n      {\r\n        header(\'Location: \' . $offline_url . \'&maintenance\');\r\n      }\r\n      else\r\n      {\r\n        header(\'Location: \' . $offline_url);\r\n      }\r\n      \r\n    }\r\n  }\r\n?>',1,1,1,'',4,'','','','0000-00-00 00:00:00','0000-00-00 00:00:00'),
  (9,5,0,'Beispiel_001','Beispiel für HTML-Insert im Header (mit PHP-Code)',0,'<head>',' \r\n\r\n<!--\r\n	Beispiel für einen Insert im Header-Bereich des Backends ;-)\r\n<?php\r\n	global $REX;\r\n	echo date(\'	d.m.Y H:i:s\') . \"\\n\";\r\n	echo \'	REDAXO Version: \' . $REX[\'VERSION\'] . \'.\' . $REX[\'SUBVERSION\'] . \'.\' . $REX[\'MINORVERSION\'] . \"\\n\";\r\n?>\r\n-->\r\n\r\n',1,0,1,'',0,'','','','0000-00-00 00:00:00','0000-00-00 00:00:00'),
  (10,5,1,'Beispiel_002','Anzeige der REDAXO-Version im Backend',0,'<li class=\"rex-navi-first\">','<?php\r\n  global $REX;\r\n  echo \'<em>Version: \'\r\n    . $REX[\'VERSION\'] . \'.\' . $REX[\'SUBVERSION\'] . \'.\' . $REX[\'MINORVERSION\']\r\n    . \'</em>&nbsp;&nbsp;</li><li>\';\r\n?>',1,0,1,'',0,'mediapool','','','0000-00-00 00:00:00','0000-00-00 00:00:00'),
  (11,5,1,'Beispiel_003','Link zum Frontend einfügen',0,'<li><a href=\"index.php?page=profile\">Mein Profil</a></li>','<li>\r\n<a href=\"../index.php\" onclick=\"window.open(this.href); return false;\">zur Webseite</a>\r\n</li>',1,0,1,'',1,'','','','0000-00-00 00:00:00','0000-00-00 00:00:00'),
  (12,5,1,'Beispiel_004','Beispiel \"abmelden\" durch \"Logout\" ersetzen',0,'<a href=\"index.php?rex_logout=1\" title=\"abmelden\">abmelden</a>\r\n|\r\n<a href=\"index.php?rex_logout=1\" accesskey=\"l\" title=\"abmelden [l]\">abmelden</a>','<a href=\"index.php?rex_logout=1\" accesskey=\"l\" title=\"Logout [l]\">Logout</a>',1,0,1,'',2,'','','','0000-00-00 00:00:00','0000-00-00 00:00:00'),
  (13,5,1,'Beispiel_005','Beispiel Linkmap und Medienpool ohne REDAXO-Logo (4.3.x)',0,'</head>\r\n<body class=\"rex-popuplinkmap\r\n|\r\n</head>\r\n<body class=\"rex-popupmediapool','  \r\n<style type=\"text/css\">\r\n#rex-website { margin-top:-65px; }\r\n#rex-wrapper { margin-top:-50px; } \r\n</style>\r\n ',0,0,1,'linkmap, mediapool',1,'','','','0000-00-00 00:00:00','0000-00-00 00:00:00'),
  (14,5,1,'Beispiel_006','Beispiel Linkmap und Medienpool ohne REDAXO-Logo (4.2.x)',0,'</head>\r\n<body id=\"rex-popup\r\n|','  \r\n<style type=\"text/css\">\r\n#rex-website { margin-top:-65px; }\r\n#rex-wrapper { margin-top:-20px; } \r\n</style>\r\n ',0,0,1,'linkmap, mediapool',1,'','','','0000-00-00 00:00:00','0000-00-00 00:00:00'),
  (15,5,0,'Beispiel_007','Alle HTML-Kommentare entfernen - ausser Conditional Comments für IE',0,'/<!--[^\\[](.|\\s)*?[^\\]]-->/','',1,0,0,'',3,'','','','0000-00-00 00:00:00','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `rex_420_xoutputfilter` ENABLE KEYS */;
UNLOCK TABLES;

DROP TABLE IF EXISTS `rex_62_params`;
CREATE TABLE `rex_62_params` (
  `field_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `prior` int(10) unsigned NOT NULL,
  `attributes` text NOT NULL,
  `type` int(10) unsigned DEFAULT NULL,
  `default` varchar(255) NOT NULL,
  `params` text,
  `validate` text,
  `restrictions` text NOT NULL,
  `createuser` varchar(255) NOT NULL,
  `createdate` int(11) NOT NULL,
  `updateuser` varchar(255) NOT NULL,
  `updatedate` int(11) NOT NULL,
  PRIMARY KEY (`field_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

LOCK TABLES `rex_62_params` WRITE;
/*!40000 ALTER TABLE `rex_62_params` DISABLE KEYS */;
INSERT INTO `rex_62_params` VALUES 
  (1,'translate:pool_file_description','med_description',1,'',2,'','','','','%USER%',1386849500,'%USER%',1386849500),
  (2,'translate:pool_file_copyright','med_copyright',2,'',1,'','','','','%USER%',1386849500,'%USER%',1386849500),
  (3,'translate:online_from','art_online_from',1,'',10,'','','','','%USER%',1386849500,'%USER%',1386849500),
  (4,'translate:online_to','art_online_to',2,'',10,'','','','','%USER%',1386849500,'%USER%',1386849500),
  (7,'translate:metadata_image','art_file',5,'',6,'','','','','%USER%',1386849500,'%USER%',1386849500),
  (9,'translate:header_article_type','art_type_id',7,'size=1',3,'','Standard|Zugriff fuer alle','','','%USER%',1386849500,'%USER%',1386849500);
/*!40000 ALTER TABLE `rex_62_params` ENABLE KEYS */;
UNLOCK TABLES;

DROP TABLE IF EXISTS `rex_62_type`;
CREATE TABLE `rex_62_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(255) DEFAULT NULL,
  `dbtype` varchar(255) NOT NULL,
  `dblength` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

LOCK TABLES `rex_62_type` WRITE;
/*!40000 ALTER TABLE `rex_62_type` DISABLE KEYS */;
INSERT INTO `rex_62_type` VALUES 
  (1,'text','text',0),
  (2,'textarea','text',0),
  (3,'select','varchar',255),
  (4,'radio','varchar',255),
  (5,'checkbox','varchar',255),
  (10,'date','text',0),
  (13,'time','text',0),
  (11,'datetime','text',0),
  (12,'legend','text',0),
  (6,'REX_MEDIA_BUTTON','varchar',255),
  (7,'REX_MEDIALIST_BUTTON','text',0),
  (8,'REX_LINK_BUTTON','varchar',255),
  (9,'REX_LINKLIST_BUTTON','text',0);
/*!40000 ALTER TABLE `rex_62_type` ENABLE KEYS */;
UNLOCK TABLES;

DROP TABLE IF EXISTS `rex_630_cronjobs`;
CREATE TABLE `rex_630_cronjobs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `parameters` text,
  `interval` varchar(255) DEFAULT NULL,
  `nexttime` int(11) DEFAULT '0',
  `environment` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `createdate` int(11) NOT NULL,
  `createuser` varchar(255) NOT NULL,
  `updatedate` int(11) NOT NULL,
  `updateuser` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

LOCK TABLES `rex_630_cronjobs` WRITE;
/*!40000 ALTER TABLE `rex_630_cronjobs` DISABLE KEYS */;
INSERT INTO `rex_630_cronjobs` VALUES 
  (1,'Artikel-Status','rex_cronjob_article_status','a:0:{}','|1|d|',1387234800,'|0|1|',1,1386855143,'olien',1386855163,'olien');
/*!40000 ALTER TABLE `rex_630_cronjobs` ENABLE KEYS */;
UNLOCK TABLES;

DROP TABLE IF EXISTS `rex_679_type_effects`;
CREATE TABLE `rex_679_type_effects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) NOT NULL,
  `effect` varchar(255) NOT NULL,
  `parameters` text NOT NULL,
  `prior` int(11) NOT NULL,
  `updatedate` int(11) NOT NULL,
  `updateuser` varchar(255) NOT NULL,
  `createdate` int(11) NOT NULL,
  `createuser` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

LOCK TABLES `rex_679_type_effects` WRITE;
/*!40000 ALTER TABLE `rex_679_type_effects` DISABLE KEYS */;
INSERT INTO `rex_679_type_effects` VALUES 
  (1,1,'resize','a:6:{s:15:\"rex_effect_crop\";a:5:{s:21:\"rex_effect_crop_width\";s:0:\"\";s:22:\"rex_effect_crop_height\";s:0:\"\";s:28:\"rex_effect_crop_offset_width\";s:0:\"\";s:29:\"rex_effect_crop_offset_height\";s:0:\"\";s:24:\"rex_effect_crop_position\";s:13:\"middle_center\";}s:22:\"rex_effect_filter_blur\";a:3:{s:29:\"rex_effect_filter_blur_amount\";s:2:\"80\";s:29:\"rex_effect_filter_blur_radius\";s:1:\"8\";s:32:\"rex_effect_filter_blur_threshold\";s:1:\"3\";}s:25:\"rex_effect_filter_sharpen\";a:3:{s:32:\"rex_effect_filter_sharpen_amount\";s:2:\"80\";s:32:\"rex_effect_filter_sharpen_radius\";s:3:\"0.5\";s:35:\"rex_effect_filter_sharpen_threshold\";s:1:\"3\";}s:15:\"rex_effect_flip\";a:1:{s:20:\"rex_effect_flip_flip\";s:1:\"X\";}s:23:\"rex_effect_insert_image\";a:5:{s:34:\"rex_effect_insert_image_brandimage\";s:0:\"\";s:28:\"rex_effect_insert_image_hpos\";s:5:\"right\";s:28:\"rex_effect_insert_image_vpos\";s:6:\"bottom\";s:33:\"rex_effect_insert_image_padding_x\";s:3:\"-10\";s:33:\"rex_effect_insert_image_padding_y\";s:3:\"-10\";}s:17:\"rex_effect_resize\";a:4:{s:23:\"rex_effect_resize_width\";s:3:\"200\";s:24:\"rex_effect_resize_height\";s:3:\"200\";s:23:\"rex_effect_resize_style\";s:7:\"maximum\";s:31:\"rex_effect_resize_allow_enlarge\";s:11:\"not_enlarge\";}}',1,1386849500,'%USER%',1386849500,'%USER%'),
  (2,2,'resize','a:6:{s:15:\"rex_effect_crop\";a:5:{s:21:\"rex_effect_crop_width\";s:0:\"\";s:22:\"rex_effect_crop_height\";s:0:\"\";s:28:\"rex_effect_crop_offset_width\";s:0:\"\";s:29:\"rex_effect_crop_offset_height\";s:0:\"\";s:24:\"rex_effect_crop_position\";s:13:\"middle_center\";}s:22:\"rex_effect_filter_blur\";a:3:{s:29:\"rex_effect_filter_blur_amount\";s:2:\"80\";s:29:\"rex_effect_filter_blur_radius\";s:1:\"8\";s:32:\"rex_effect_filter_blur_threshold\";s:1:\"3\";}s:25:\"rex_effect_filter_sharpen\";a:3:{s:32:\"rex_effect_filter_sharpen_amount\";s:2:\"80\";s:32:\"rex_effect_filter_sharpen_radius\";s:3:\"0.5\";s:35:\"rex_effect_filter_sharpen_threshold\";s:1:\"3\";}s:15:\"rex_effect_flip\";a:1:{s:20:\"rex_effect_flip_flip\";s:1:\"X\";}s:23:\"rex_effect_insert_image\";a:5:{s:34:\"rex_effect_insert_image_brandimage\";s:0:\"\";s:28:\"rex_effect_insert_image_hpos\";s:5:\"right\";s:28:\"rex_effect_insert_image_vpos\";s:6:\"bottom\";s:33:\"rex_effect_insert_image_padding_x\";s:3:\"-10\";s:33:\"rex_effect_insert_image_padding_y\";s:3:\"-10\";}s:17:\"rex_effect_resize\";a:4:{s:23:\"rex_effect_resize_width\";s:3:\"600\";s:24:\"rex_effect_resize_height\";s:3:\"600\";s:23:\"rex_effect_resize_style\";s:7:\"maximum\";s:31:\"rex_effect_resize_allow_enlarge\";s:11:\"not_enlarge\";}}',1,1386849500,'%USER%',1386849500,'%USER%'),
  (3,3,'resize','a:6:{s:15:\"rex_effect_crop\";a:5:{s:21:\"rex_effect_crop_width\";s:0:\"\";s:22:\"rex_effect_crop_height\";s:0:\"\";s:28:\"rex_effect_crop_offset_width\";s:0:\"\";s:29:\"rex_effect_crop_offset_height\";s:0:\"\";s:24:\"rex_effect_crop_position\";s:13:\"middle_center\";}s:22:\"rex_effect_filter_blur\";a:3:{s:29:\"rex_effect_filter_blur_amount\";s:2:\"80\";s:29:\"rex_effect_filter_blur_radius\";s:1:\"8\";s:32:\"rex_effect_filter_blur_threshold\";s:1:\"3\";}s:25:\"rex_effect_filter_sharpen\";a:3:{s:32:\"rex_effect_filter_sharpen_amount\";s:2:\"80\";s:32:\"rex_effect_filter_sharpen_radius\";s:3:\"0.5\";s:35:\"rex_effect_filter_sharpen_threshold\";s:1:\"3\";}s:15:\"rex_effect_flip\";a:1:{s:20:\"rex_effect_flip_flip\";s:1:\"X\";}s:23:\"rex_effect_insert_image\";a:5:{s:34:\"rex_effect_insert_image_brandimage\";s:0:\"\";s:28:\"rex_effect_insert_image_hpos\";s:5:\"right\";s:28:\"rex_effect_insert_image_vpos\";s:6:\"bottom\";s:33:\"rex_effect_insert_image_padding_x\";s:3:\"-10\";s:33:\"rex_effect_insert_image_padding_y\";s:3:\"-10\";}s:17:\"rex_effect_resize\";a:4:{s:23:\"rex_effect_resize_width\";s:2:\"80\";s:24:\"rex_effect_resize_height\";s:2:\"80\";s:23:\"rex_effect_resize_style\";s:7:\"maximum\";s:31:\"rex_effect_resize_allow_enlarge\";s:11:\"not_enlarge\";}}',1,1386849500,'%USER%',1386849500,'%USER%'),
  (4,4,'resize','a:6:{s:15:\"rex_effect_crop\";a:5:{s:21:\"rex_effect_crop_width\";s:0:\"\";s:22:\"rex_effect_crop_height\";s:0:\"\";s:28:\"rex_effect_crop_offset_width\";s:0:\"\";s:29:\"rex_effect_crop_offset_height\";s:0:\"\";s:24:\"rex_effect_crop_position\";s:13:\"middle_center\";}s:22:\"rex_effect_filter_blur\";a:3:{s:29:\"rex_effect_filter_blur_amount\";s:2:\"80\";s:29:\"rex_effect_filter_blur_radius\";s:1:\"8\";s:32:\"rex_effect_filter_blur_threshold\";s:1:\"3\";}s:25:\"rex_effect_filter_sharpen\";a:3:{s:32:\"rex_effect_filter_sharpen_amount\";s:2:\"80\";s:32:\"rex_effect_filter_sharpen_radius\";s:3:\"0.5\";s:35:\"rex_effect_filter_sharpen_threshold\";s:1:\"3\";}s:15:\"rex_effect_flip\";a:1:{s:20:\"rex_effect_flip_flip\";s:1:\"X\";}s:23:\"rex_effect_insert_image\";a:5:{s:34:\"rex_effect_insert_image_brandimage\";s:0:\"\";s:28:\"rex_effect_insert_image_hpos\";s:5:\"right\";s:28:\"rex_effect_insert_image_vpos\";s:6:\"bottom\";s:33:\"rex_effect_insert_image_padding_x\";s:3:\"-10\";s:33:\"rex_effect_insert_image_padding_y\";s:3:\"-10\";}s:17:\"rex_effect_resize\";a:4:{s:23:\"rex_effect_resize_width\";s:3:\"246\";s:24:\"rex_effect_resize_height\";s:3:\"246\";s:23:\"rex_effect_resize_style\";s:7:\"maximum\";s:31:\"rex_effect_resize_allow_enlarge\";s:11:\"not_enlarge\";}}',1,1386849500,'%USER%',1386849500,'%USER%'),
  (5,5,'resize','a:6:{s:15:\"rex_effect_crop\";a:5:{s:21:\"rex_effect_crop_width\";s:0:\"\";s:22:\"rex_effect_crop_height\";s:0:\"\";s:28:\"rex_effect_crop_offset_width\";s:0:\"\";s:29:\"rex_effect_crop_offset_height\";s:0:\"\";s:24:\"rex_effect_crop_position\";s:13:\"middle_center\";}s:22:\"rex_effect_filter_blur\";a:3:{s:29:\"rex_effect_filter_blur_amount\";s:2:\"80\";s:29:\"rex_effect_filter_blur_radius\";s:1:\"8\";s:32:\"rex_effect_filter_blur_threshold\";s:1:\"3\";}s:25:\"rex_effect_filter_sharpen\";a:3:{s:32:\"rex_effect_filter_sharpen_amount\";s:2:\"80\";s:32:\"rex_effect_filter_sharpen_radius\";s:3:\"0.5\";s:35:\"rex_effect_filter_sharpen_threshold\";s:1:\"3\";}s:15:\"rex_effect_flip\";a:1:{s:20:\"rex_effect_flip_flip\";s:1:\"X\";}s:23:\"rex_effect_insert_image\";a:5:{s:34:\"rex_effect_insert_image_brandimage\";s:0:\"\";s:28:\"rex_effect_insert_image_hpos\";s:5:\"right\";s:28:\"rex_effect_insert_image_vpos\";s:6:\"bottom\";s:33:\"rex_effect_insert_image_padding_x\";s:3:\"-10\";s:33:\"rex_effect_insert_image_padding_y\";s:3:\"-10\";}s:17:\"rex_effect_resize\";a:4:{s:23:\"rex_effect_resize_width\";s:3:\"246\";s:24:\"rex_effect_resize_height\";s:3:\"246\";s:23:\"rex_effect_resize_style\";s:7:\"maximum\";s:31:\"rex_effect_resize_allow_enlarge\";s:11:\"not_enlarge\";}}',1,1386849500,'%USER%',1386849500,'%USER%'),
  (6,6,'resize','a:8:{s:15:\"rex_effect_crop\";a:6:{s:21:\"rex_effect_crop_width\";s:0:\"\";s:22:\"rex_effect_crop_height\";s:0:\"\";s:28:\"rex_effect_crop_offset_width\";s:0:\"\";s:29:\"rex_effect_crop_offset_height\";s:0:\"\";s:20:\"rex_effect_crop_hpos\";s:6:\"center\";s:20:\"rex_effect_crop_vpos\";s:6:\"middle\";}s:22:\"rex_effect_filter_blur\";a:3:{s:29:\"rex_effect_filter_blur_amount\";s:2:\"80\";s:29:\"rex_effect_filter_blur_radius\";s:1:\"8\";s:32:\"rex_effect_filter_blur_threshold\";s:1:\"3\";}s:25:\"rex_effect_filter_sharpen\";a:3:{s:32:\"rex_effect_filter_sharpen_amount\";s:2:\"80\";s:32:\"rex_effect_filter_sharpen_radius\";s:3:\"0.5\";s:35:\"rex_effect_filter_sharpen_threshold\";s:1:\"3\";}s:15:\"rex_effect_flip\";a:1:{s:20:\"rex_effect_flip_flip\";s:1:\"X\";}s:23:\"rex_effect_insert_image\";a:5:{s:34:\"rex_effect_insert_image_brandimage\";s:0:\"\";s:28:\"rex_effect_insert_image_hpos\";s:4:\"left\";s:28:\"rex_effect_insert_image_vpos\";s:3:\"top\";s:33:\"rex_effect_insert_image_padding_x\";s:3:\"-10\";s:33:\"rex_effect_insert_image_padding_y\";s:3:\"-10\";}s:17:\"rex_effect_mirror\";a:5:{s:24:\"rex_effect_mirror_height\";s:0:\"\";s:33:\"rex_effect_mirror_set_transparent\";s:7:\"colored\";s:22:\"rex_effect_mirror_bg_r\";s:0:\"\";s:22:\"rex_effect_mirror_bg_g\";s:0:\"\";s:22:\"rex_effect_mirror_bg_b\";s:0:\"\";}s:17:\"rex_effect_resize\";a:4:{s:23:\"rex_effect_resize_width\";s:3:\"500\";s:24:\"rex_effect_resize_height\";s:0:\"\";s:23:\"rex_effect_resize_style\";s:7:\"maximum\";s:31:\"rex_effect_resize_allow_enlarge\";s:7:\"enlarge\";}s:20:\"rex_effect_workspace\";a:8:{s:26:\"rex_effect_workspace_width\";s:0:\"\";s:27:\"rex_effect_workspace_height\";s:0:\"\";s:25:\"rex_effect_workspace_hpos\";s:4:\"left\";s:25:\"rex_effect_workspace_vpos\";s:3:\"top\";s:36:\"rex_effect_workspace_set_transparent\";s:7:\"colored\";s:25:\"rex_effect_workspace_bg_r\";s:0:\"\";s:25:\"rex_effect_workspace_bg_g\";s:0:\"\";s:25:\"rex_effect_workspace_bg_b\";s:0:\"\";}}',1,1386851017,'admin',1386851017,'admin'),
  (7,6,'filter_sharpen','a:8:{s:15:\"rex_effect_crop\";a:6:{s:21:\"rex_effect_crop_width\";s:0:\"\";s:22:\"rex_effect_crop_height\";s:0:\"\";s:28:\"rex_effect_crop_offset_width\";s:0:\"\";s:29:\"rex_effect_crop_offset_height\";s:0:\"\";s:20:\"rex_effect_crop_hpos\";s:6:\"center\";s:20:\"rex_effect_crop_vpos\";s:6:\"middle\";}s:22:\"rex_effect_filter_blur\";a:3:{s:29:\"rex_effect_filter_blur_amount\";s:2:\"80\";s:29:\"rex_effect_filter_blur_radius\";s:1:\"8\";s:32:\"rex_effect_filter_blur_threshold\";s:1:\"3\";}s:25:\"rex_effect_filter_sharpen\";a:3:{s:32:\"rex_effect_filter_sharpen_amount\";s:2:\"80\";s:32:\"rex_effect_filter_sharpen_radius\";s:3:\"0.5\";s:35:\"rex_effect_filter_sharpen_threshold\";s:1:\"3\";}s:15:\"rex_effect_flip\";a:1:{s:20:\"rex_effect_flip_flip\";s:1:\"X\";}s:23:\"rex_effect_insert_image\";a:5:{s:34:\"rex_effect_insert_image_brandimage\";s:0:\"\";s:28:\"rex_effect_insert_image_hpos\";s:4:\"left\";s:28:\"rex_effect_insert_image_vpos\";s:3:\"top\";s:33:\"rex_effect_insert_image_padding_x\";s:3:\"-10\";s:33:\"rex_effect_insert_image_padding_y\";s:3:\"-10\";}s:17:\"rex_effect_mirror\";a:5:{s:24:\"rex_effect_mirror_height\";s:0:\"\";s:33:\"rex_effect_mirror_set_transparent\";s:7:\"colored\";s:22:\"rex_effect_mirror_bg_r\";s:0:\"\";s:22:\"rex_effect_mirror_bg_g\";s:0:\"\";s:22:\"rex_effect_mirror_bg_b\";s:0:\"\";}s:17:\"rex_effect_resize\";a:4:{s:23:\"rex_effect_resize_width\";s:0:\"\";s:24:\"rex_effect_resize_height\";s:0:\"\";s:23:\"rex_effect_resize_style\";s:7:\"maximum\";s:31:\"rex_effect_resize_allow_enlarge\";s:7:\"enlarge\";}s:20:\"rex_effect_workspace\";a:8:{s:26:\"rex_effect_workspace_width\";s:0:\"\";s:27:\"rex_effect_workspace_height\";s:0:\"\";s:25:\"rex_effect_workspace_hpos\";s:4:\"left\";s:25:\"rex_effect_workspace_vpos\";s:3:\"top\";s:36:\"rex_effect_workspace_set_transparent\";s:7:\"colored\";s:25:\"rex_effect_workspace_bg_r\";s:0:\"\";s:25:\"rex_effect_workspace_bg_g\";s:0:\"\";s:25:\"rex_effect_workspace_bg_b\";s:0:\"\";}}',2,1386851017,'admin',1386851017,'admin'),
  (8,7,'resize','a:8:{s:15:\"rex_effect_crop\";a:6:{s:21:\"rex_effect_crop_width\";s:0:\"\";s:22:\"rex_effect_crop_height\";s:0:\"\";s:28:\"rex_effect_crop_offset_width\";s:0:\"\";s:29:\"rex_effect_crop_offset_height\";s:0:\"\";s:20:\"rex_effect_crop_hpos\";s:6:\"center\";s:20:\"rex_effect_crop_vpos\";s:6:\"middle\";}s:22:\"rex_effect_filter_blur\";a:3:{s:29:\"rex_effect_filter_blur_amount\";s:2:\"80\";s:29:\"rex_effect_filter_blur_radius\";s:1:\"8\";s:32:\"rex_effect_filter_blur_threshold\";s:1:\"3\";}s:25:\"rex_effect_filter_sharpen\";a:3:{s:32:\"rex_effect_filter_sharpen_amount\";s:2:\"80\";s:32:\"rex_effect_filter_sharpen_radius\";s:3:\"0.5\";s:35:\"rex_effect_filter_sharpen_threshold\";s:1:\"3\";}s:15:\"rex_effect_flip\";a:1:{s:20:\"rex_effect_flip_flip\";s:1:\"X\";}s:23:\"rex_effect_insert_image\";a:5:{s:34:\"rex_effect_insert_image_brandimage\";s:0:\"\";s:28:\"rex_effect_insert_image_hpos\";s:4:\"left\";s:28:\"rex_effect_insert_image_vpos\";s:3:\"top\";s:33:\"rex_effect_insert_image_padding_x\";s:3:\"-10\";s:33:\"rex_effect_insert_image_padding_y\";s:3:\"-10\";}s:17:\"rex_effect_mirror\";a:5:{s:24:\"rex_effect_mirror_height\";s:0:\"\";s:33:\"rex_effect_mirror_set_transparent\";s:7:\"colored\";s:22:\"rex_effect_mirror_bg_r\";s:0:\"\";s:22:\"rex_effect_mirror_bg_g\";s:0:\"\";s:22:\"rex_effect_mirror_bg_b\";s:0:\"\";}s:17:\"rex_effect_resize\";a:4:{s:23:\"rex_effect_resize_width\";s:3:\"200\";s:24:\"rex_effect_resize_height\";s:0:\"\";s:23:\"rex_effect_resize_style\";s:7:\"maximum\";s:31:\"rex_effect_resize_allow_enlarge\";s:7:\"enlarge\";}s:20:\"rex_effect_workspace\";a:8:{s:26:\"rex_effect_workspace_width\";s:0:\"\";s:27:\"rex_effect_workspace_height\";s:0:\"\";s:25:\"rex_effect_workspace_hpos\";s:4:\"left\";s:25:\"rex_effect_workspace_vpos\";s:3:\"top\";s:36:\"rex_effect_workspace_set_transparent\";s:7:\"colored\";s:25:\"rex_effect_workspace_bg_r\";s:0:\"\";s:25:\"rex_effect_workspace_bg_g\";s:0:\"\";s:25:\"rex_effect_workspace_bg_b\";s:0:\"\";}}',1,1386851017,'admin',1386851017,'admin'),
  (9,7,'crop','a:8:{s:15:\"rex_effect_crop\";a:6:{s:21:\"rex_effect_crop_width\";s:3:\"200\";s:22:\"rex_effect_crop_height\";s:3:\"133\";s:28:\"rex_effect_crop_offset_width\";s:0:\"\";s:29:\"rex_effect_crop_offset_height\";s:0:\"\";s:20:\"rex_effect_crop_hpos\";s:6:\"center\";s:20:\"rex_effect_crop_vpos\";s:6:\"middle\";}s:22:\"rex_effect_filter_blur\";a:3:{s:29:\"rex_effect_filter_blur_amount\";s:2:\"80\";s:29:\"rex_effect_filter_blur_radius\";s:1:\"8\";s:32:\"rex_effect_filter_blur_threshold\";s:1:\"3\";}s:25:\"rex_effect_filter_sharpen\";a:3:{s:32:\"rex_effect_filter_sharpen_amount\";s:2:\"80\";s:32:\"rex_effect_filter_sharpen_radius\";s:3:\"0.5\";s:35:\"rex_effect_filter_sharpen_threshold\";s:1:\"3\";}s:15:\"rex_effect_flip\";a:1:{s:20:\"rex_effect_flip_flip\";s:1:\"X\";}s:23:\"rex_effect_insert_image\";a:5:{s:34:\"rex_effect_insert_image_brandimage\";s:0:\"\";s:28:\"rex_effect_insert_image_hpos\";s:4:\"left\";s:28:\"rex_effect_insert_image_vpos\";s:3:\"top\";s:33:\"rex_effect_insert_image_padding_x\";s:3:\"-10\";s:33:\"rex_effect_insert_image_padding_y\";s:3:\"-10\";}s:17:\"rex_effect_mirror\";a:5:{s:24:\"rex_effect_mirror_height\";s:0:\"\";s:33:\"rex_effect_mirror_set_transparent\";s:7:\"colored\";s:22:\"rex_effect_mirror_bg_r\";s:0:\"\";s:22:\"rex_effect_mirror_bg_g\";s:0:\"\";s:22:\"rex_effect_mirror_bg_b\";s:0:\"\";}s:17:\"rex_effect_resize\";a:4:{s:23:\"rex_effect_resize_width\";s:0:\"\";s:24:\"rex_effect_resize_height\";s:0:\"\";s:23:\"rex_effect_resize_style\";s:7:\"maximum\";s:31:\"rex_effect_resize_allow_enlarge\";s:7:\"enlarge\";}s:20:\"rex_effect_workspace\";a:8:{s:26:\"rex_effect_workspace_width\";s:0:\"\";s:27:\"rex_effect_workspace_height\";s:0:\"\";s:25:\"rex_effect_workspace_hpos\";s:4:\"left\";s:25:\"rex_effect_workspace_vpos\";s:3:\"top\";s:36:\"rex_effect_workspace_set_transparent\";s:7:\"colored\";s:25:\"rex_effect_workspace_bg_r\";s:0:\"\";s:25:\"rex_effect_workspace_bg_g\";s:0:\"\";s:25:\"rex_effect_workspace_bg_b\";s:0:\"\";}}',2,1386851017,'admin',1386851017,'admin'),
  (10,7,'filter_sharpen','a:8:{s:15:\"rex_effect_crop\";a:6:{s:21:\"rex_effect_crop_width\";s:0:\"\";s:22:\"rex_effect_crop_height\";s:0:\"\";s:28:\"rex_effect_crop_offset_width\";s:0:\"\";s:29:\"rex_effect_crop_offset_height\";s:0:\"\";s:20:\"rex_effect_crop_hpos\";s:6:\"center\";s:20:\"rex_effect_crop_vpos\";s:6:\"middle\";}s:22:\"rex_effect_filter_blur\";a:3:{s:29:\"rex_effect_filter_blur_amount\";s:2:\"80\";s:29:\"rex_effect_filter_blur_radius\";s:1:\"8\";s:32:\"rex_effect_filter_blur_threshold\";s:1:\"3\";}s:25:\"rex_effect_filter_sharpen\";a:3:{s:32:\"rex_effect_filter_sharpen_amount\";s:2:\"80\";s:32:\"rex_effect_filter_sharpen_radius\";s:3:\"0.5\";s:35:\"rex_effect_filter_sharpen_threshold\";s:1:\"3\";}s:15:\"rex_effect_flip\";a:1:{s:20:\"rex_effect_flip_flip\";s:1:\"X\";}s:23:\"rex_effect_insert_image\";a:5:{s:34:\"rex_effect_insert_image_brandimage\";s:0:\"\";s:28:\"rex_effect_insert_image_hpos\";s:4:\"left\";s:28:\"rex_effect_insert_image_vpos\";s:3:\"top\";s:33:\"rex_effect_insert_image_padding_x\";s:3:\"-10\";s:33:\"rex_effect_insert_image_padding_y\";s:3:\"-10\";}s:17:\"rex_effect_mirror\";a:5:{s:24:\"rex_effect_mirror_height\";s:0:\"\";s:33:\"rex_effect_mirror_set_transparent\";s:7:\"colored\";s:22:\"rex_effect_mirror_bg_r\";s:0:\"\";s:22:\"rex_effect_mirror_bg_g\";s:0:\"\";s:22:\"rex_effect_mirror_bg_b\";s:0:\"\";}s:17:\"rex_effect_resize\";a:4:{s:23:\"rex_effect_resize_width\";s:0:\"\";s:24:\"rex_effect_resize_height\";s:0:\"\";s:23:\"rex_effect_resize_style\";s:7:\"maximum\";s:31:\"rex_effect_resize_allow_enlarge\";s:7:\"enlarge\";}s:20:\"rex_effect_workspace\";a:8:{s:26:\"rex_effect_workspace_width\";s:0:\"\";s:27:\"rex_effect_workspace_height\";s:0:\"\";s:25:\"rex_effect_workspace_hpos\";s:4:\"left\";s:25:\"rex_effect_workspace_vpos\";s:3:\"top\";s:36:\"rex_effect_workspace_set_transparent\";s:7:\"colored\";s:25:\"rex_effect_workspace_bg_r\";s:0:\"\";s:25:\"rex_effect_workspace_bg_g\";s:0:\"\";s:25:\"rex_effect_workspace_bg_b\";s:0:\"\";}}',3,1386851017,'admin',1386851017,'admin');
/*!40000 ALTER TABLE `rex_679_type_effects` ENABLE KEYS */;
UNLOCK TABLES;

DROP TABLE IF EXISTS `rex_679_types`;
CREATE TABLE `rex_679_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

LOCK TABLES `rex_679_types` WRITE;
/*!40000 ALTER TABLE `rex_679_types` DISABLE KEYS */;
INSERT INTO `rex_679_types` VALUES 
  (1,1,'rex_mediapool_detail','Zur Darstellung von Bildern in der Detailansicht im Medienpool'),
  (2,1,'rex_mediapool_maximized','Zur Darstellung von Bildern im Medienpool wenn maximiert'),
  (3,1,'rex_mediapool_preview','Zur Darstellung der Vorschaubilder im Medienpool'),
  (4,1,'rex_mediabutton_preview','Zur Darstellung der Vorschaubilder in REX_MEDIA_BUTTON[]s'),
  (5,1,'rex_medialistbutton_preview','Zur Darstellung der Vorschaubilder in REX_MEDIALIST_BUTTON[]s'),
  (6,0,'magnific_popup_image_thumb','Magnific Popup Einzelbild Vorschaubild'),
  (7,0,'magnific_popup_gallery_thumb','Magnific Popup Galerie Vorschaubild');
/*!40000 ALTER TABLE `rex_679_types` ENABLE KEYS */;
UNLOCK TABLES;

DROP TABLE IF EXISTS `rex_action`;
CREATE TABLE `rex_action` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `preview` text,
  `presave` text,
  `postsave` text,
  `previewmode` tinyint(4) DEFAULT NULL,
  `presavemode` tinyint(4) DEFAULT NULL,
  `postsavemode` tinyint(4) DEFAULT NULL,
  `createuser` varchar(255) NOT NULL,
  `createdate` int(11) NOT NULL,
  `updateuser` varchar(255) NOT NULL,
  `updatedate` int(11) NOT NULL,
  `revision` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `rex_article`;
CREATE TABLE `rex_article` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(11) NOT NULL,
  `re_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `catname` varchar(255) NOT NULL,
  `catprior` int(11) NOT NULL,
  `attributes` text NOT NULL,
  `startpage` tinyint(1) NOT NULL,
  `prior` int(11) NOT NULL,
  `path` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `createdate` int(11) NOT NULL,
  `updatedate` int(11) NOT NULL,
  `template_id` int(11) NOT NULL,
  `clang` int(11) NOT NULL,
  `createuser` varchar(255) NOT NULL,
  `updateuser` varchar(255) NOT NULL,
  `revision` int(11) NOT NULL,
  `art_online_from` text,
  `art_online_to` text,
  `art_file` varchar(255) DEFAULT '',
  `art_type_id` varchar(255) DEFAULT '',
  `seo_title` text,
  `seo_description` text,
  `seo_keywords` text,
  `seo_custom_url` text,
  `seo_canonical_url` text,
  `seo_noindex` varchar(1) DEFAULT NULL,
  `seo_ignore_prefix` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`pid`),
  UNIQUE KEY `find_articles` (`id`,`clang`),
  KEY `id` (`id`),
  KEY `clang` (`clang`),
  KEY `re_id` (`re_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

LOCK TABLES `rex_article` WRITE;
/*!40000 ALTER TABLE `rex_article` DISABLE KEYS */;
INSERT INTO `rex_article` VALUES 
  (1,1,0,'Starseite','Starseite',1,'',1,1,'|',1,1386851172,1386944328,1,0,'olien','olien',0,'','','','','','','','','','',''),
  (3,2,0,'404 Seite','',0,'',0,1,'|',1,1386851132,1386851173,1,0,'olien','olien',0,'','','','','','','','','','',''),
  (4,3,0,'Wartungsarbeiten','',0,'',0,2,'|',1,1386851140,1386851173,1,0,'olien','olien',0,'','','','','','','','','','',''),
  (6,4,0,'Kontakt','Kontakt',4,'',1,1,'|',1,1387187819,1387187838,1,0,'olien','olien',0,'','','','','','','','','','',''),
  (7,5,0,'Noch einer','Noch einer',2,'',1,1,'|',1,1387187840,1387187827,1,0,'olien','olien',0,'','','','','','','','','','',''),
  (8,6,0,'Nioch einer mehr','Nioch einer mehr',3,'',1,1,'|',1,1387187841,1387187834,1,0,'olien','olien',0,'','','','','','','','','','',''),
  (9,7,5,'Sub','Sub',1,'',1,1,'|5|',1,1387187852,1387187847,1,0,'olien','olien',0,'','','','','','','','','','',''),
  (10,8,5,'Sub 2','Sub 2',2,'',1,1,'|5|',1,1387187853,1387187851,1,0,'olien','olien',0,'','','','','','','','','','',''),
  (11,9,5,'offline','offline',3,'',1,1,'|5|',0,1387187955,1387187955,1,0,'olien','olien',0,'','','','','','','','','','','');
/*!40000 ALTER TABLE `rex_article` ENABLE KEYS */;
UNLOCK TABLES;

DROP TABLE IF EXISTS `rex_article_slice`;
CREATE TABLE `rex_article_slice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clang` int(11) NOT NULL,
  `ctype` int(11) NOT NULL,
  `re_article_slice_id` int(11) NOT NULL,
  `value1` text,
  `value2` text,
  `value3` text,
  `value4` text,
  `value5` text,
  `value6` text,
  `value7` text,
  `value8` text,
  `value9` text,
  `value10` text,
  `value11` text,
  `value12` text,
  `value13` text,
  `value14` text,
  `value15` text,
  `value16` text,
  `value17` text,
  `value18` text,
  `value19` text,
  `value20` text,
  `file1` varchar(255) DEFAULT NULL,
  `file2` varchar(255) DEFAULT NULL,
  `file3` varchar(255) DEFAULT NULL,
  `file4` varchar(255) DEFAULT NULL,
  `file5` varchar(255) DEFAULT NULL,
  `file6` varchar(255) DEFAULT NULL,
  `file7` varchar(255) DEFAULT NULL,
  `file8` varchar(255) DEFAULT NULL,
  `file9` varchar(255) DEFAULT NULL,
  `file10` varchar(255) DEFAULT NULL,
  `filelist1` text,
  `filelist2` text,
  `filelist3` text,
  `filelist4` text,
  `filelist5` text,
  `filelist6` text,
  `filelist7` text,
  `filelist8` text,
  `filelist9` text,
  `filelist10` text,
  `link1` varchar(10) DEFAULT NULL,
  `link2` varchar(10) DEFAULT NULL,
  `link3` varchar(10) DEFAULT NULL,
  `link4` varchar(10) DEFAULT NULL,
  `link5` varchar(10) DEFAULT NULL,
  `link6` varchar(10) DEFAULT NULL,
  `link7` varchar(10) DEFAULT NULL,
  `link8` varchar(10) DEFAULT NULL,
  `link9` varchar(10) DEFAULT NULL,
  `link10` varchar(10) DEFAULT NULL,
  `linklist1` text,
  `linklist2` text,
  `linklist3` text,
  `linklist4` text,
  `linklist5` text,
  `linklist6` text,
  `linklist7` text,
  `linklist8` text,
  `linklist9` text,
  `linklist10` text,
  `php` text,
  `html` text,
  `article_id` int(11) NOT NULL,
  `modultyp_id` int(11) NOT NULL,
  `createdate` int(11) NOT NULL,
  `updatedate` int(11) NOT NULL,
  `createuser` varchar(255) NOT NULL,
  `updateuser` varchar(255) NOT NULL,
  `next_article_slice_id` int(11) DEFAULT NULL,
  `revision` int(11) NOT NULL,
  `status` tinyint(1) unsigned zerofill NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`,`re_article_slice_id`,`article_id`,`modultyp_id`),
  KEY `id` (`id`),
  KEY `clang` (`clang`),
  KEY `re_article_slice_id` (`re_article_slice_id`),
  KEY `article_id` (`article_id`),
  KEY `find_slices` (`clang`,`article_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

LOCK TABLES `rex_article_slice` WRITE;
/*!40000 ALTER TABLE `rex_article_slice` DISABLE KEYS */;
INSERT INTO `rex_article_slice` VALUES 
  (1,0,1,3,'rrr','h1','rr','','','','l','noresize','1','','','','nurbildlink','','','','','30.12.2013','01.12.2013','1','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','',1,2,1386854778,1386855099,'olien','olien',0,0,0),
  (2,0,1,1,'ttt','h2','tt','','','','l','noresize','','','','','nurbildlink','','','','','26.12.2013','02.12.2013','1','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','',1,2,1386854791,1386855103,'olien','olien',0,0,1),
  (3,0,1,0,'sdad[COL|COL]asd[COL|COL]adasdasdasdasdasd[COL|COL]asd[COL|COL]asd[COL|COL]mtr[COL|COL]ewgegrwe[COL|COL]gerg[COL|COL]erge[COL|COL]ergerg[ROW|ROW]asd[COL|COL]asd[COL|COL]asd[COL|COL]asdad[COL|COL]asdas[COL|COL]etz[COL|COL][COL|COL]erg[COL|COL]rthb[COL|COL]ergerg[ROW|ROW]*sfdac* _sdfsdf_[COL|COL]asd[COL|COL]* dsf sdkrg erg wergweth\r\n*  jtu kezum\r\n* twzh rwthg wrthwr\r\n* hrhwdb[COL|COL]sdasd[COL|COL]\"ljbljgbjl\":http://test.de[COL|COL]rth[COL|COL]ferg[COL|COL] ngh[COL|COL]geg[COL|COL]erg[ROW|ROW]asda[COL|COL]asda[COL|COL]dasd[COL|COL]ad[COL|COL]asd[COL|COL]dfdg[COL|COL]egetg[COL|COL]tb[COL|COL]erge[COL|COL]ergeg','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','',1,6,1386944239,1386944328,'olien','olien',0,0,1);
/*!40000 ALTER TABLE `rex_article_slice` ENABLE KEYS */;
UNLOCK TABLES;

DROP TABLE IF EXISTS `rex_clang`;
CREATE TABLE `rex_clang` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `revision` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `rex_clang` WRITE;
/*!40000 ALTER TABLE `rex_clang` DISABLE KEYS */;
INSERT INTO `rex_clang` VALUES 
  (0,'deutsch',0);
/*!40000 ALTER TABLE `rex_clang` ENABLE KEYS */;
UNLOCK TABLES;

DROP TABLE IF EXISTS `rex_file`;
CREATE TABLE `rex_file` (
  `file_id` int(11) NOT NULL AUTO_INCREMENT,
  `re_file_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `attributes` text,
  `filetype` varchar(255) DEFAULT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `originalname` varchar(255) DEFAULT NULL,
  `filesize` varchar(255) DEFAULT NULL,
  `width` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `createdate` int(11) NOT NULL,
  `updatedate` int(11) NOT NULL,
  `createuser` varchar(255) NOT NULL,
  `updateuser` varchar(255) NOT NULL,
  `revision` int(11) NOT NULL,
  `med_description` text,
  `med_copyright` text,
  PRIMARY KEY (`file_id`),
  KEY `re_file_id` (`re_file_id`),
  KEY `category_id` (`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `rex_file_category`;
CREATE TABLE `rex_file_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `re_id` int(11) NOT NULL,
  `path` varchar(255) NOT NULL,
  `createdate` int(11) NOT NULL,
  `updatedate` int(11) NOT NULL,
  `createuser` varchar(255) NOT NULL,
  `updateuser` varchar(255) NOT NULL,
  `attributes` text,
  `revision` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `re_id` (`re_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `rex_module`;
CREATE TABLE `rex_module` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `ausgabe` text NOT NULL,
  `eingabe` text NOT NULL,
  `createuser` varchar(255) NOT NULL,
  `updateuser` varchar(255) NOT NULL,
  `createdate` int(11) NOT NULL,
  `updatedate` int(11) NOT NULL,
  `attributes` text,
  `revision` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

LOCK TABLES `rex_module` WRITE;
/*!40000 ALTER TABLE `rex_module` DISABLE KEYS */;
INSERT INTO `rex_module` VALUES 
  (6,'500 Tabelle',0,'<?php\r\nif(!function_exists(\'getdyntable\')) {\r\n    function getdyntable($string) {\r\n        $tmp = explode(\'[ROW|ROW]\', $string);\r\n        $table = array();\r\n        for($i=0;$i<count($tmp);$i++) {\r\n            $tmp2 = explode(\'[COL|COL]\', $tmp[$i]);\r\n            foreach($tmp2 as $col) {\r\n                $table[$i][] = trim($col);\r\n            }           \r\n        }\r\n        return $table;\r\n    }\r\n}\r\n$table_data = \"REX_VALUE[1]\";\r\n$table_data = getdyntable($table_data);\r\n\r\n$out = \'<table>\';\r\n$rowcount=0;\r\nforeach ($table_data as $row) {\r\n    $out .= \'<tr>\';\r\n\r\n    foreach ($row as $col) {\r\n        ($rowcount==0) ? $out .= \'<th>\' : $out .= \'<td>\';\r\n        $out .= $col;\r\n        ($rowcount==0) ? $out .= \'</th>\' : $out .= \'</td>\';\r\n    }\r\n    $out .= \'<tr>\';\r\n    $rowcount++;\r\n}\r\n$out .= \'</table>\';\r\nprint $out;\r\n?>','<script type=\"text/javascript\">\r\n\r\njQuery(function($){ \r\n    \r\n//--- Functions ---///\r\n\r\n    function get_dyn_table_data() {\r\n        var table_data = \'\';\r\n        i=0;\r\n        $(\'div#dyntable table tr[class!=\"control\"]\').each(function() {\r\n            if(i>0) {\r\n                table_data = table_data+\'[ROW|ROW]\';\r\n            }\r\n            i++;\r\n            \r\n            j=0;\r\n            $(this).children(\'.dyn_content\').each(function() {\r\n                if(j>0) {\r\n                table_data = table_data+\'[COL|COL]\';\r\n                }\r\n                j++;     \r\n                var fieldvalue = $(this).children().first().val();\r\n                var fieldvalue_clean = fieldvalue.replace(\'[COL|COL]\', \'[col|col]\').replace(\'[ROW|ROW]\', \'[row|row]\');\r\n                table_data = table_data+fieldvalue_clean;\r\n            });\r\n        });\r\n        $(\'#debugout\').html(table_data);\r\n        $(\'#dyntable_string\').val(table_data);\r\n\r\n    }     \r\n    \r\n    function generate_dyn_table() {\r\n        var table_data = $(\'#dyntable_string\').val();\r\n        var table_rows = table_data.split(\'[ROW|ROW]\');\r\n        var table_cols = table_rows[0].split(\'[COL|COL]\');\r\n        for(var i=1; i<table_cols.length;i++) {\r\n            add_dyn_table_col();\r\n        }\r\n        for(var i=1; (i+1)<table_rows.length;i++) {\r\n           add_dyn_table_row();            \r\n        }       \r\n        update_dyn_table_counter();\r\n \r\n        for(var i=0;i<table_rows.length;i++) {\r\n            table_cols = table_rows[i].split(\'[COL|COL]\');\r\n            for(var j=0;j<table_cols.length;j++) {\r\n                if(i==0) {\r\n                    $(\'th[data-col=\"\'+(j+2)+\'\"][data-row=\"\'+(i+2)+\'\"] input\').val(table_cols[j]);\r\n                }\r\n                else {\r\n                    $(\'td[data-col=\"\'+(j+2)+\'\"][data-row=\"\'+(i+2)+\'\"] textarea\').html(table_cols[j]);\r\n                }\r\n            }   \r\n        }\r\n        get_dyn_table_data();\r\n    }\r\n\r\n    function update_dyn_table_counter() {\r\n        i=0;\r\n        $(\'div#dyntable table tr\').each(function() {\r\n            i++;\r\n            j=0;\r\n            $(this).children().each(function() {\r\n                j++;\r\n                $(this).attr(\'data-row\', i);\r\n                $(this).attr(\'data-col\', j);\r\n            });\r\n        });  \r\n    }\r\n\r\n    function add_dyn_table_col() {\r\n        $(\'div#dyntable_show table tr[class!=\"control\"][class!=\"dyn_head\"]\').append(\'<td class=\"dyn_content\"><textarea style=\"width:95%\"></textarea></td>\');\r\n        $(\'div#dyntable_show table tr[class=\"dyn_head\"][class!=\"control\"]\').append(\'<th class=\"dyn_content\"><input style=\"width:95%\"></input></th>\');\r\n        $(\'div#dyntable_show table tr[class=\"control\"]\').append(\'<th><a href=\"#\" class=\"dyntable_del_col\" >[löschen]</a> <br> <a href=\"#\" class=\"left\">[links]</a> <a href=\"#\" class=\"right\">[rechts]</a></th>\');\r\n    } \r\n\r\n    function add_dyn_table_row() {\r\n        var count = $(\'div#dyntable_show table tr.control th\').size();\r\n        var rowout = \'\';\r\n        for (var i=0; i<(count-1); i++){\r\n            rowout = rowout+\'<td class=\"dyn_content\"><textarea style=\"width:95%\"></textarea></td>\';\r\n        }\r\n\r\n        $(\'div#dyntable_show table\').append(\'<tr><td><a href=\"#\" class=\"dyntable_del_row\">[löschen]</a><br><a href=\"#\" class=\"up\" >[hoch]</a><br><a href=\"#\" class=\"down\">[runter]</a></td>\'+rowout+\'</tr>\');\r\n    }  \r\n\r\n    function move_table_col(from,to) {\r\n        var rows = $(\'tr\', \'#dyntable_show table\');\r\n        var cols;\r\n        rows.each(function() {\r\n            cols = $(this).children(\'th, td\');\r\n            cols.eq(from).detach().insertBefore(cols.eq(to));\r\n        });\r\n        get_dyn_table_data();\r\n        update_dyn_table_counter();\r\n    }\r\n\r\n//--- Events ---//\r\n\r\n    $(\'#dyntable_add_col\').click(function(e) {\r\n        e.preventDefault();\r\n        add_dyn_table_col();\r\n        update_dyn_table_counter();\r\n        get_dyn_table_data();\r\n    });\r\n    $(\'#dyntable_add_row\').click(function(e) {\r\n        e.preventDefault();\r\n        add_dyn_table_row();\r\n        update_dyn_table_counter();\r\n        get_dyn_table_data();\r\n    });\r\n  \r\n      \r\n    $(\'#dyntable_show\').on(\'click\', \'.up,.down\', function(e){\r\n        e.preventDefault();\r\n        var row = $(this).closest(\'tr\');\r\n        \r\n        if ($(this).is(\".up\")) {\r\n            var index = $( \"tr\" ).index( $(this).parent().parent() );\r\n            if(index>2)\r\n                row.insertBefore(row.prev());\r\n        } else {\r\n            row.insertAfter(row.next());\r\n        }\r\n        get_dyn_table_data();\r\n    });\r\n\r\n\r\n    $(\'#dyntable_show\').on(\'click\', \'.left,.right\', function(e) {\r\n        e.preventDefault();\r\n        var index = $( \"th\" ).index( $(this).parent() );\r\n        if($(this).is(\'.left\')) {\r\n            if(index > 1)\r\n                move_table_col(index, index-1);\r\n        }\r\n        else {\r\n            move_table_col(index+1, index);\r\n        }    \r\n    });\r\n\r\n    $(\'#dyntable_show\').on(\'click\', \'.dyntable_del_col\' ,function(e) {\r\n        e.preventDefault();\r\n        var col = $(this).parent().attr(\'data-col\');\r\n        $(\'th[data-col=\"\'+col+\'\"],td[data-col=\"\'+col+\'\"]\').remove();\r\n        get_dyn_table_data();\r\n     });\r\n\r\n     $(\'#dyntable_show\').on(\'click\', \'.dyntable_del_row\', function(e) {\r\n         e.preventDefault();\r\n         var row = $(this).parent().attr(\'data-row\');\r\n         $(this).parent().parent().remove();\r\n         get_dyn_table_data();\r\n     });\r\n\r\n     $(\'div#dyntable\').on(\'change\', \'input,textarea\', function() {\r\n            get_dyn_table_data();\r\n     });\r\n\r\n//--- Initial execution ---//\r\n\r\n     generate_dyn_table();\r\n\r\n});\r\n</script>\r\n\r\n\r\n<div id=\"dyntable\">    \r\n    <input id=\"dyntable_string\" type=\"hidden\" class=\"text\'.$classes.$wc.\'\" name=\"VALUE[1]\" id=\"test\" value=\"REX_VALUE[1]\">\r\n    <div id=\"dyntable_show\">    \r\n        <table class=\"rex-table\" style=\"padding-right:10px;\" >\r\n                <tr class=\"control\">\r\n                    <th><a id=\"dyntable_add_row\" style=\"display:block;float:left;width:20px; height:20px; background-image:url(media/pfeil_down.gif); background-position:center center; background-repeat:no-repeat;text-indent:-9999px;\" href=\"#\">[Zeile hinzu]</a><a id=\"dyntable_add_col\" style=\"display:block;width:20px; height:20px; background-image:url(media/pfeil_right.gif); background-position:center center; background-repat:no-repeat; float:left;text-indent:-9999px;\" href=\"#\">[Spalte hinzu]</a></th><th><a href=\"#\" class=\"dyntable_del_col\" >[löschen]</a> <br> <a href=\"#\" class=\"left\">[links]</a> <a href=\"#\" class=\"right\">[rechts]</a></th>\r\n                </tr>          \r\n                <tr class=\"dyn_head\">\r\n                        <th></th>\r\n                        <th class=\"dyn_content\"><input style=\"width:95%\"></input></th>\r\n                </tr>\r\n                <tr>\r\n                        <td><a href=\"#\" class=\"dyntable_del_row\">[löschen]</a><br><a href=\"#\" class=\"up\" >[hoch]</a><br><a href=\"#\" class=\"down\">[runter]</a></td>\r\n                        <td class=\"dyn_content\"><textarea style=\"width:95%\"></textarea></td>\r\n                </tr>             \r\n        </table>\r\n    </div>\r\n</div>','olien','',1386944196,0,'',0),
  (2,'0010 - Überschrift / Text / Bild / Link',0,'<?php\r\n\r\n	$link 				= \"\";\r\n	$linkbild 			= \"\";\r\n	$linkueberschrift 	= \"\";\r\n	$linkanfang 		= \"\";\r\n	$linkende 			= \"\";		\r\n	$bild 				= \'\';\r\n	$bildunterschrift 	= \'\';\r\n	$weiterlesenlink 	= \'\';\r\n	$bildcode 			= \'\';\r\n\r\n\r\n\r\n# Variablen für Online-Prüfung\r\n	$online = \'REX_VALUE[20]\';\r\n	$time = time();\r\n	$start = strtotime(\'REX_VALUE[19]\');\r\n	$end = strtotime(\'REX_VALUE[18]\');\r\n\r\n$msgonline = \'\r\n<div class=\"rex-message\"><div class=\"rex-info\" style=\"font-size: 15px; font-weight: normal;\"><p><span>Für diesen Inhalt ist ein in Veröffentlichungszeitraum angegeben (REX_VALUE[19] - REX_VALUE[18])<br/><b>Dieser Inhalt wird auf der Webseite angezeigt.</b><span></p></div></div>\';\r\n\r\n$msgoffline = \'\r\n<div class=\"rex-message\"><div class=\"rex-warning\" style=\"font-size: 15px; font-weight: normal;\"><p><span>Für diesen Inhalt ist ein in Veröffentlichungszeitraum angegeben (REX_VALUE[19] - REX_VALUE[18])<br/><b>Dieser Inhalt wird momentan NICHT auf der Webseite angezeigt.</b><span></p></div></div>\';\r\n\r\n\r\nif (!isset($REX[\'base\'][\'textmodulcount\'])){\r\n	$REX[\'base\'][\'textmodulcount\'] = 0;\r\n}\r\n$REX[\'base\'][\'textmodulcount\'] = $REX[\'base\'][\'textmodulcount\'] + 1;\r\n\r\n\r\nif(!$REX[\'REDAXO\']) {\r\n\r\n//\r\n//	Frontend\r\n//\r\n  if(OOAddon::isAvailable(\'textile\'))\r\n	{\r\n\r\n	if(REX_IS_VALUE[3]) { // Text\r\n		$text = \'\';\r\n 		$text = htmlspecialchars_decode(\'REX_VALUE[3]\',ENT_QUOTES);\r\n		$text = str_replace(\'<br />\',\'\',$text);\r\n		$text = rex_a79_textile($text);\r\n	} \r\n\r\n// echo \"REX_FILE[1]\";\r\n\r\n    if (\"REX_FILE[1]\" != \"\") { // Bild\r\n\r\n	  	$bild 				= OOMedia::getMediaByName(\'REX_FILE[1]\');\r\n	   	$bildTitle 			= $bild->getTitle();\r\n	   	$bildBeschreibung 	= $bild->getValue(\'med_description\');\r\n	    $bildCopyright 		= $bild->getValue(\'med_copyright\');\r\n	   	$bildDateiName 		= $bild->getFileName();\r\n	   	$bildBreite 		= $bild->getWidth();\r\n	   	$bildHoehe 			= $bild->getHeight();\r\n\r\n		$image = rex_image_manager::getImageCache(\'REX_FILE[1]\', \"contentimage_REX_VALUE[8]\");\r\n\r\n			//  printf(\'%s[%s] = %d x %d Pixel\',\r\n	  		// $bildDateiName,\r\n	  		// \"contentimage_REX_VALUE[8]\",\r\n	  		// $image->getWidth(),\r\n			// $image->getHeight()\r\n			// );\r\n\r\n	   	if ($bildCopyright != \'\') {\r\n	   		$bildCopyright = \" | (c) \".$bildCopyright;\r\n	   	}\r\n\r\n	$bildunterschrift = \'\';\r\n	if(REX_IS_VALUE[6])   {\r\n 			$bildunterschrift = htmlspecialchars_decode(\'REX_VALUE[6]\',ENT_QUOTES);\r\n			$bildunterschrift = str_replace(\'<br />\',\'\',$bildunterschrift);\r\n			$bildunterschrift = rex_a79_textile($bildunterschrift);\r\n			$bildunterschrift = \'<div class=\"bildunterschrift\">\'.$bildunterschrift.\'</div>\'.PHP_EOL;\r\n	} \r\n\r\n		if(REX_IS_VALUE[9])   { \r\n			$rahmen = \'class=\"rahmen\"\';\r\n		} else {\r\n			$rahmen = \'\';\r\n		}\r\n\r\n\r\n\r\n	$bildcode = \'<img \'.$rahmen.\' src=\"index.php?rex_img_type=contentimage_REX_VALUE[8]&amp;rex_img_file=\'.$bildDateiName.\'\" title=\"REX_VALUE[5]\'.$bildCopyright.\'\" alt=\"REX_VALUE[5]\'.$bildCopyright.\'\" width=\"\'.$image->getWidth().\'\" height=\"\'.$image->getHeight().\'\"/>\'.PHP_EOL;\r\n	}\r\n\r\n \r\n\r\n	 if(REX_IS_VALUE[11] OR \"REX_LINK_ID[1]\" != 0) {\r\n\r\n		$link = \"1\";\r\n	   	$externerlink = \"REX_VALUE[11]\";\r\n	 	  	if($externerlink != str_replace(\"http://\", \"\",$externerlink)) {\r\n				$linkanfang = \'<a href=\"REX_VALUE[11]\">\'.PHP_EOL;\r\n			} else {\r\n				$linkanfang = \'<a href=\"http://REX_VALUE[11]\">\'.PHP_EOL;\r\n			}\r\n	 \r\n 		if (\"REX_LINK_ID[1]\" != 0) {\r\n	  		$linkanfang  = \'<a href=\"\'.rex_geturl(\"REX_LINK_ID[1]\", $REX[\'CUR_CLANG\']).\'\">\'.PHP_EOL;\r\n		  } \r\n	\r\n		$linkende =\'</a>\'.PHP_EOL;\r\n\r\n\r\n		if (\"REX_VALUE[13]\" == \"nurbildlink\") { $linkbild = \"1\"; }\r\n		if (\"REX_VALUE[13]\" == \"ueberschriftlink\") { $linkueberschrift = \"1\"; }\r\n		if (\"REX_VALUE[13]\" == \"ueberschriftundbildlink\") { $linkbild = \"1\"; $linkueberschrift = \"1\";}	\r\n\r\n		$weiterlesenlink = \'\';		\r\n		if (REX_IS_VALUE[10]) {\r\n			$weiterlesenlink = \'<div class=\"weiterlesen\">\'.$linkanfang.\'REX_VALUE[10]\'.$linkende.\'</div>\'.PHP_EOL;\r\n		} else {\r\n			$weiterlesenlink = \'\';\r\n		}\r\n\r\n		}\r\n	\r\n\r\n	// Überschrift\r\n	if ($linkueberschrift) {\r\n		$contentueberschrift = \'<REX_VALUE[2]>\'.$linkanfang.\'REX_VALUE[1]\'.$linkende.\'</REX_VALUE[2]>\'.PHP_EOL;\r\n	} else {\r\n		$contentueberschrift =  \'<REX_VALUE[2]>REX_VALUE[1]</REX_VALUE[2]>\'.PHP_EOL;\r\n	}\r\n\r\n	// Bild\r\n	if ($linkbild) {\r\n		$contentbild = $linkanfang.$bildcode.$linkende.$bildunterschrift;\r\n	} else {\r\n		$contentbild = $bildcode.$bildunterschrift;\r\n	}\r\n\r\n	// Text\r\n	$contenttext = $text;\r\n	$contentweiterlesen = $weiterlesenlink;\r\n\r\n	//HTML\r\n	$content = \'<div class=\"textbildlink\">\'.PHP_EOL;\r\n\r\n	// Ausrichtung \r\n\r\n	$floatimg = \'\';\r\n	$block = \'\';\r\n\r\n	// Ausrichtungen \"im Fliesstext links\"\r\n	if (\"REX_VALUE[7]\" == \'l\' OR \"REX_VALUE[7]\" == \'tl\' OR \"REX_VALUE[7]\" == \'tlu\') {\r\n		$floatimg = \"flLeft\";\r\n	}\r\n\r\n	// Ausrichtungen \"im Fliesstext rechts\"\r\n	if (\"REX_VALUE[7]\" == \'r\' OR \"REX_VALUE[7]\" == \'tr\' OR \"REX_VALUE[7]\" == \'tru\') {\r\n		$floatimg = \"flRight\";\r\n	}\r\n\r\n	if (\"REX_VALUE[7]\" == \'tl\' OR \"REX_VALUE[7]\" == \'tr\' OR \"REX_VALUE[7]\" == \'tlu\' OR \"REX_VALUE[7]\" == \'tru\' ) {\r\n		$block = \'block\';\r\n	} else {\r\n		$block = \'\';\r\n	}\r\n\r\n	if (\"REX_VALUE[7]\" == \'tlu\' OR \"REX_VALUE[7]\" == \'tru\') {\r\n		$content .= \'<div class=\"bildcontainer \'.$floatimg.\' REX_VALUE[8]\">\'.PHP_EOL;\r\n		$content .= $contentbild.\'</div>\'.PHP_EOL;\r\n		$content .= \'<div class=\"text \'.$block.\'\">\'.PHP_EOL;\r\n		$content .= $contentueberschrift.$contenttext.$contentweiterlesen.\'</div>\'.PHP_EOL;\r\n	} else {\r\n		$content .= $contentueberschrift;\r\n		$content .= \'<div class=\"bildcontainer \'.$floatimg.\' REX_VALUE[8]\">\'.PHP_EOL;\r\n		$content .= $contentbild.\'</div>\'.PHP_EOL;\r\n		$content .= \'<div class=\"text \'.$block.\'\">\'.PHP_EOL;\r\n		$content .= $contenttext.$contentweiterlesen.\'</div>\'.PHP_EOL;\r\n	}\r\n\r\n	$content .=  \'</div>\'.PHP_EOL;\r\n\r\n\r\n// Zeiteinstellung\r\n\r\nif ($online == \"1\") {\r\n	\r\n	if( $time > $start && $time < $end )\r\n	{\r\n		if ($REX[\'REDAXO\']) {\r\n			echo $msgonline;\r\n		}\r\n\r\n	echo PHP_EOL.\'<!-- SLICE ID REX_SLICE_ID ANFANG -->\'.PHP_EOL;\r\n	echo $content;\r\n  	echo \'<!-- // -->\'.PHP_EOL;\r\n			\r\n	}\r\n	else {\r\n		if ($REX[\'REDAXO\']) {\r\n		echo $msgoffline;\r\n		}\r\n	}\r\n	}\r\n# Prüfung aus\r\n  if ($online == \"\") {\r\n	echo PHP_EOL.\'<!-- SLICE ID REX_SLICE_ID ANFANG -->\'.PHP_EOL;\r\n	echo $content;\r\n  	echo \'<!-- // -->\'.PHP_EOL;	   \r\n  }\r\n\r\n\r\n	} else {\r\n	  echo rex_warning(\'Dieses Modul benötigt das \"textile\" Addon!\');\r\n	}\r\n} else {\r\n\r\n//\r\n//	Backend\r\n//\r\n\r\n// Eingaben prüfen\r\n$warnings = [];\r\n\r\nif (\"REX_FILE[1]\" != \"\" AND \"REX_VALUE[5]\" == \"\" ) {\r\n    $warnings[] = \'Bitte geben Sie einen Alternativtext für das Bild an.\';\r\n}\r\n\r\nif (\"REX_FILE[1]\" == \"\" AND \"REX_VALUE[5]\" != \"\" OR \"REX_FILE[1]\" == \"\" AND \"REX_VALUE[6]\" != \"\" ) {\r\n    $warnings[] = \'Sie haben Angaben zu einem Bild gemacht ohne ein Bild auszuwählen. Bitte wählen Sie ein Bild aus.\';\r\n}\r\n\r\nif (\"REX_VALUE[10]\" != \"\" AND (\"REX_LINK_ID[1]\" == \"\" AND \"REX_VALUE[11]\" == \"\" )) {\r\n    $warnings[] = \'Bitte geben Sie einen Link an.\';\r\n}\r\n\r\nif (\"REX_VALUE[11]\" != \"\" AND \"REX_LINK_ID[1]\" != \"\") {\r\n    $warnings[] = \'Bitte geben Sie entweder einen externen oder einen internen Link an.\';\r\n} \r\n\r\nif ((\"REX_VALUE[11]\" != \"\" OR \"REX_LINK_ID[1]\" != \"\") AND ((\"REX_VALUE[1]\" == \"\" AND \"REX_VALUE[13]\" == \"ueberschriftlink\") OR (\"REX_VALUE[1]\" == \"\" AND \"REX_VALUE[13]\" == \"ueberschriftundbildlink\"))) {\r\n    $warnings[] = \'Die Überschrift kann nicht verlinkt werden. Bitte geben Sie eine Überschrift ein.\';\r\n}\r\n\r\nif ((\"REX_VALUE[11]\" != \"\" OR \"REX_LINK_ID[1]\" != \"\") AND ((\"REX_FILE[1]\" == \"\" AND \"REX_VALUE[13]\" == \"nurbildlink\") OR (\"REX_FILE[1]\" == \"\" AND \"REX_VALUE[13]\" == \"ueberschriftundbildlink\"))) {\r\n    $warnings[] = \'Das Bild kann nicht verlinkt werden. Bitte wählen Sie ein Bild aus.\';\r\n}\r\n\r\nif ($REX[\'REDAXO\'] && count($warnings) > 0) {\r\n    foreach ($warnings as $warning) {\r\n        echo rex_warning($warning);\r\n    }\r\n}\r\n\r\n	echo \'<table style=\"width: 100%;\">\'.PHP_EOL;\r\n\r\n	if (REX_IS_VALUE[1]) // Überschrift\r\n	{\r\n\r\n		echo \'<tr>\'.PHP_EOL;\r\n		echo \'<td style=\"padding: 5px; width: 100px; font-weight: bold;\">Überschrift</td>\'.PHP_EOL;\r\n		echo \'<td style=\"padding: 5px;\">REX_VALUE[1]</td>\'.PHP_EOL;\r\n		echo \'</tr>\'.PHP_EOL;\r\n		echo \'<tr>\'.PHP_EOL;		\r\n		echo \'<td style=\"padding: 5px; width: 100px; font-weight: bold;\">Grösse</td>\'.PHP_EOL;\r\n		echo \'<td style=\"padding: 5px;\">REX_VALUE[2]</td>\'.PHP_EOL;\r\n		echo \'</tr>\'.PHP_EOL;\r\n	}\r\n	\r\n	\r\n	if(REX_IS_VALUE[3])\r\n	{\r\n		$text = \'\';\r\n 		$text = htmlspecialchars_decode(\'REX_VALUE[3]\',ENT_QUOTES);\r\n		$text = str_replace(\'<br />\',\'\',$text);\r\n		$text = rex_a79_textile($text);\r\n		\r\n		echo \'<tr>\'.PHP_EOL;\r\n		echo \'<td style=\"padding: 5px; width: 100px; font-weight: bold;\">Text</td>\'.PHP_EOL;\r\n		echo \'<td style=\"padding: 5px;\">\'.$text.\'</td>\'.PHP_EOL;\r\n		echo \'</tr>\'.PHP_EOL;\r\n	} \r\n\r\n\r\n    //  Wenn Bild eingefuegt wurde, Code schreiben \r\n    if (\"REX_FILE[1]\" != \"\") {\r\n\r\n	echo \'<tr>\'.PHP_EOL;		\r\n	echo \'<th colspan=\"2\"><br/><hr/><br/></th>\'.PHP_EOL;\r\n	echo \'</tr>\'.PHP_EOL;\r\n\r\n	$ausrichtung = \"\";\r\n    if (\"REX_VALUE[7]\" == \"l\") 		$ausrichtung = \"im Text links\";\r\n    if (\"REX_VALUE[7]\" == \"r\") 		$ausrichtung = \"im Text rechts\";\r\n    if (\"REX_VALUE[7]\" == \"tl\") 	$ausrichtung = \"links vom Text\";\r\n    if (\"REX_VALUE[7]\" == \"tr\") 	$ausrichtung = \"rechts vom Text\";\r\n	if (\"REX_VALUE[7]\" == \"tlu\") 	$ausrichtung = \"links von Text und Überschrift\";\r\n    if (\"REX_VALUE[7]\" == \"tru\") 	$ausrichtung = \"rechts von Text und Überschrift\";\r\n\r\n  	$bild 				= OOMedia::getMediaByName(\'REX_FILE[1]\');\r\n   	$bildTitle 			= $bild->getTitle();\r\n   	$bildBeschreibung 	= $bild->getValue(\'med_description\');\r\n    $bildCopyright 		= $bild->getValue(\'med_copyright\');\r\n   	$bildDateiName 		= $bild->getFileName();\r\n   	$bildBreite 		= $bild->getWidth();\r\n   	$bildHoehe 			= $bild->getHeight();\r\n\r\n   	if ($bildCopyright != \'\') {\r\n   		$bildCopyright = \" | (c) \".$bildCopyright;\r\n   	}\r\n\r\n	echo \'<tr>\'.PHP_EOL;\r\n	echo \'<td style=\"padding: 5px; width: 100px; font-weight: bold;\">Bild</td>\'.PHP_EOL;\r\n	echo \'<td style=\"padding: 5px;\">REX_FILE[1]<br/><br/>\r\n			<img src=\"index.php?rex_img_type=rex_medialistbutton_preview&rex_img_file=\'.$bildDateiName.\'\" title=\"REX_VALUE[5]\'.$bildCopyright.\'\" alt=\"REX_VALUE[5]\'.$bildCopyright.\'\" />\r\n		  </td>\'.PHP_EOL;\r\n	echo \'</tr>\'.PHP_EOL;\r\n\r\n	echo \'<tr>\'.PHP_EOL;\r\n	echo \'<td style=\"padding: 5px; width: 100px; font-weight: bold;\">Alternativtext</td>\'.PHP_EOL;\r\n	echo \'<td style=\"padding: 5px;\">REX_VALUE[5]\'.$bildCopyright.\'</td>\'.PHP_EOL;\r\n	echo \'</tr>\'.PHP_EOL;\r\n\r\n	$bildunterschrift = \'\';\r\n	  if(REX_IS_VALUE[6])\r\n		  {\r\n 			$bildunterschrift = htmlspecialchars_decode(\'REX_VALUE[6]\',ENT_QUOTES);\r\n			$bildunterschrift = str_replace(\'<br />\',\'\',$bildunterschrift);\r\n			$bildunterschrift = rex_a79_textile($bildunterschrift);\r\n\r\n			echo \'<tr>\'.PHP_EOL;\r\n			echo \'<td style=\"padding: 5px; width: 100px; font-weight: bold;\">Bildunterschrift</td>\'.PHP_EOL;\r\n			echo \'<td style=\"padding: 5px;\">\'.$bildunterschrift.\'</td>\'.PHP_EOL;\r\n			echo \'</tr>\'.PHP_EOL;\r\n		   } \r\n\r\n	$bildgroesse = \"\";\r\n    if (\"REX_VALUE[8]\" == \"noresize\") 	$bildgroesse = \"keine Anpassung\";\r\n    if (\"REX_VALUE[8]\" == \"full\") 		$bildgroesse = \"ganze Breite\";\r\n    if (\"REX_VALUE[8]\" == \"half\") 		$bildgroesse = \"halbe Breite\";\r\n    if (\"REX_VALUE[8]\" == \"quarter\")	$bildgroesse = \"viertel Breite\";\r\n\r\n	echo \'<tr>\'.PHP_EOL;\r\n	echo \'<td style=\"padding: 5px; width: 100px; font-weight: bold;\">Grösse</td>\'.PHP_EOL;\r\n	echo \'<td style=\"padding: 5px;\">\'.$bildgroesse.\'</td>\'.PHP_EOL;\r\n	echo \'</tr>\'.PHP_EOL;\r\n\r\n	echo \'<tr>\'.PHP_EOL;\r\n	echo \'<td style=\"padding: 5px; width: 100px; font-weight: bold;\">Ausrichtung</td>\'.PHP_EOL;\r\n	echo \'<td style=\"padding: 5px;\">\'.$ausrichtung.\'</td>\'.PHP_EOL;\r\n	echo \'</tr>\'.PHP_EOL;\r\n\r\n	$bildrahmen = \"\";\r\n     if(REX_IS_VALUE[9]) {\r\n     	$bildrahmen = \"ja\";\r\n		echo \'<tr>\'.PHP_EOL;\r\n		echo \'<td style=\"padding: 5px; width: 100px; font-weight: bold;\">Rahmen</td>\'.PHP_EOL;\r\n		echo \'<td style=\"padding: 5px;\">\'.$bildrahmen.\'</td>\'.PHP_EOL;\r\n		echo \'</tr>\'.PHP_EOL;\r\n     }\r\n\r\n\r\n	}\r\n\r\n    // Link\r\n    if (REX_IS_VALUE[10] OR REX_IS_VALUE[11] OR \"REX_LINK_ID[1]\" != 0 ) {\r\n\r\n		echo \'<tr>\'.PHP_EOL;		\r\n		echo \'<th colspan=\"2\"><br/><hr/><br/></th>\'.PHP_EOL;\r\n		echo \'</tr>\'.PHP_EOL;\r\n\r\n	    if(REX_IS_VALUE[11]) {\r\n\r\n	    	$externerlink = \"REX_VALUE[11]\";\r\n	    	if($externerlink != str_replace(\"http://\", \"\",$externerlink)) {\r\n				$externerlink = \"REX_VALUE[11]\";\r\n			} else {\r\n				$externerlink = \"http://REX_VALUE[11]\";\r\n			}\r\n\r\n			echo \'<tr>\'.PHP_EOL;\r\n			echo \'<td style=\"padding: 5px; width: 100px; font-weight: bold;\">externe URL</td>\'.PHP_EOL;\r\n			echo \'<td style=\"padding: 5px;\">\'.$externerlink.\'</td>\'.PHP_EOL;\r\n			echo \'</tr>\'.PHP_EOL;\r\n		}	\r\n\r\n	    if (\"REX_LINK_ID[1]\" != 0) {\r\n\r\n			echo \'<tr>\'.PHP_EOL;\r\n			echo \'<td style=\"padding: 5px; width: 100px; font-weight: bold;\">interner Link</td>\'.PHP_EOL;\r\n\r\n			$article=OOArticle::getArticleById(REX_LINK_ID[1]);\r\n			$name=$article->getName(); \r\n\r\n			echo \'<td style=\"padding: 5px;\"><a href=\"index.php?page=content&article_id=REX_LINK_ID[1]&mode=edit\">\'.$name.\'</a></td>\'.PHP_EOL;\r\n			echo \'</tr>\'.PHP_EOL;\r\n		}	\r\n\r\n		$verlinkungsart = \"\";\r\n    	if (\"REX_VALUE[13]\" == \"nurbildlink\") 				$verlinkungsart = \"nur das Bild ist verlinkt\";\r\n    	if (\"REX_VALUE[13]\" == \"ueberschriftlink\") 			$verlinkungsart = \"nur die Überschrift ist verlinkt\";\r\n    	if (\"REX_VALUE[13]\" == \"ueberschriftundbildlink\") 	$verlinkungsart = \"Überschrift und Bild sind verlinkt\";\r\n\r\n		echo \'<tr>\'.PHP_EOL;\r\n		echo \'<td style=\"padding: 5px; width: 100px; font-weight: bold;\">Verlinkungsart</td>\'.PHP_EOL;\r\n		echo \'<td style=\"padding: 5px;\">\'.$verlinkungsart.\'</td>\'.PHP_EOL;\r\n		echo \'</tr>\'.PHP_EOL;\r\n\r\n	    if(REX_IS_VALUE[10]) {\r\n			echo \'<tr>\'.PHP_EOL;\r\n			echo \'<td style=\"padding: 5px; width: 100px; font-weight: bold;\">Linkbezeichnung</td>\'.PHP_EOL;\r\n			echo \'<td style=\"padding: 5px;\">REX_VALUE[10]</td>\'.PHP_EOL;\r\n			echo \'</tr>\'.PHP_EOL;\r\n		}	\r\n\r\n    }\r\n\r\n	echo \'</table>\'.PHP_EOL;\r\n\r\n}\r\n\r\n?>','<h1>Todo</h1>\r\n\r\n<ul>\r\n<li>Lightbox einbauen (http://dimsemenov.com/plugins/magnific-popup/)</li>\r\n<li>Youtube unterstützung einbauen? Mit Vorschaubild?</li>\r\n<li>Hilfe einbauen?</li>\r\n</ul>\r\n\r\n\r\n<div id=\"tabs\">\r\n	<ul>\r\n		<li><a href=\"#text\">Überschrift &amp; Text</a></li>\r\n		<li><a href=\"#bild\">Bild</a></li>\r\n		<li><a href=\"#link\">Link</a></li>\r\n		<li style=\"float:right;\"><a href=\"#weiteres\">Weitere Einstellungen</a></li>		\r\n	</ul>\r\n\r\n\r\n<?php\r\n\r\n\r\n// Rex Values\r\n//  1  : Überschrift\r\n//  2  : Überschrift-Tag\r\n//  3  : Inhaltstext\r\n// 	1  : Bild Datei -> REX_FILE[1]\r\n// 	5  : Alt Attribu \r\n// 	6  : Bildunterschrift\r\n// 	7  : Bild Ausrichtung\r\n// 	8  : Bild Größe\r\n// 	9  : Bild Rahmen\r\n// 10  : Link Bezeichnung\r\n// 11  : externe URL\r\n//  1  : interne URL -> REX_LINK_ID[1]\r\n// 13  : Verlinkungsart\r\n\r\n// 18,19,20 -> Online Einstellungen\r\n\r\nif (!isset($REX[\'base\'][\'textmodulcount\'])){ \r\n	$REX[\'base\'][\'textmodulcount\'] = 0;\r\n}\r\n$REX[\'base\'][\'textmodulcount\'] = $REX[\'base\'][\'textmodulcount\'] + 1;\r\n\r\n\r\n\r\n$objForm = new mform();\r\n\r\n// TEXT\r\n\r\n$objForm->addHtml(\'<div id=\"text\">\');\r\n\r\n$objForm->addHeadline(\'Überschrift\');\r\n\r\n\r\n// Mit Hilfe\r\n/*\r\n$objForm->addHeadline(\'Überschrift \r\n<div id=\"info_ueberschrift\" class=\"ui-state-default ui-corner-all\" title=\".ui-icon-help\" style=\"width: 17px; float: left; margin: -3px 10px 0 0; \">\r\n    <span class=\"ui-icon ui-icon-help\"></span>\r\n</div>\r\n	\');\r\n\r\n$objForm->addHtml(\'\r\n<div class=\"dialog\" id=\"dialog_ueberschrift\" title=\"Überschriften\" style=\"display:none;\">\r\n		<p>Lorem ipsum</p>\r\n</div>\r\n\');\r\n*/\r\n\r\n$objForm->addTextAreaField(1,array(\'label\'=>\'Text\',\'style\'=>\'width:500px\'));\r\n\r\n// Tag für Überschrift\r\n$tag = \'REX_VALUE[2]\';\r\nif ($tag == \'\' and $REX[\'base\'][\'textmodulcount\'] == 1) $tag = \'h1\';\r\nif ($tag == \'\') $tag = \'h2\';\r\n\r\n$objForm->addSelectField(2,array(\'h1\'=>\'H1\',\'h2\'=>\'H2\',\'h3\'=>\'H3\',\'h4\'=>\'H4\',\'h5\'=>\'H5\',\'h6\'=>\'H6\'),array(\'label\'=>\'Grösse\'),\'\',$tag);\r\n\r\n$objForm->addHtml(\'<br/>\');\r\n$objForm->addHeadline(\'Text\');\r\n$objForm->addTextAreaField(3,array(\'label\'=>\'Text eingeben\',\'class\'=>\"rex-markitup\",\'data-buttonset\'=>\"kreischer\",\'style\'=>\'width:500px !important;\'));\r\n$objForm->addHtml(\'</div>\');\r\n\r\n// BILD\r\n\r\n$objForm->addHtml(\'<div id=\"bild\">\');\r\n$objForm->addHeadline(\'Bild\');\r\n$objForm->addMediaField(1,array(\'types\'=>\'gif,jpg,png\',\'preview\'=>0,\'category\'=>0,\'label\'=>\'Datei\'));\r\n$objForm->addTextField(5,array(\'label\'=>\'Alternativtext\',\'style\'=>\'width:500px\'));\r\n\r\n$objForm->addHtml(\'<br/>\');\r\n$objForm->addHeadline(\'Weitere Eigenschaften\');\r\n$objForm->addTextAreaField(6,array(\'label\'=>\'Bildunterschrift\',\'style\'=>\'width:500px\'));\r\n\r\n\r\n$objForm->addSelectField(7, array(\r\n	\'l\'=>\'im Fliesstext links\',\r\n	\'r\'=>\'im Fliestext rechts\',\r\n	\'tl\'=>\'links vom Text\',\r\n	\'tr\'=>\'rechts vom Text\',\r\n	\'tlu\'=>\'links von Text und Überschrift\',\r\n	\'tru\'=>\'rechts von Text und Überschrift\'\r\n), array(\'label\'=>\'Ausrichtung\'));\r\n\r\n$objForm->addSelectField(8,array(\r\n	\'noresize\'=>\'keine Anpassung\',\r\n	\'full\'=>\'ganze Breite\',\r\n	\'half\'=>\'halbe Breite\',\r\n	\'quarter\'=>\'viertel Breite\'\r\n	),array(\'label\'=>\'Größe\'));\r\n\r\n$objForm->addCheckboxField(9,array(1=>\'\'),array(\'label\'=>\'Bildrahmen\'));\r\n\r\n$objForm->addHtml(\'</div>\');\r\n\r\n// LINK\r\n\r\n$objForm->addHtml(\'<div id=\"link\">\');\r\n$objForm->addHeadline(\'Link\');\r\n$objForm->addTextField(11,array(\'label\'=>\'extern\',\'style\'=>\'width:500px\'));\r\n$objForm->addLinkField(1,array(\'label\'=>\'intern\',\'category\'=>0));\r\n\r\n$objForm->addHtml(\'<br/>\');\r\n$objForm->addHeadline(\'Weitere Eigenschaften\');\r\n\r\n$objForm->addTextField(10,array(\'label\'=>\'Bezeichnung\',\'style\'=>\'width:500px\'));\r\n\r\n$objForm->addSelectField(13, array(\r\n	\'nurbildlink\'=>\'nur das Bild verlinken\',\r\n	\'ueberschriftlink\'=>\'nur Überschrift verlinken\',\r\n	\'ueberschriftundbildlink\'=>\'Überschrift und Bild verlinken\'\r\n), array(\'label\'=>\'Elemente\'));\r\n\r\n\r\n$objForm->addHtml(\'</div>\');\r\n\r\n// Weitere Einstellungen\r\n\r\n$objForm->addHtml(\'<div id=\"weiteres\">\');\r\n$objForm->addHeadline(\'Online Zeitraum einstellen\');\r\n$objForm->addCheckboxField(20,array(1=>\'\'),array(\'label\'=>\'Aktiv\'));\r\n$objForm->addTextField(19,array(\'label\'=>\'Online von\',\'style\'=>\'width:100px\',\'class\'=>\'datepicker1\'));\r\n$objForm->addTextField(18,array(\'label\'=>\'Online bis\',\'style\'=>\'width:100px\',\'class\'=>\'datepicker2\'));\r\n\r\n$objForm->addHtml(\'</div>\');\r\n\r\necho $objForm->show_mform();\r\n\r\n?>\r\n\r\n<script type=\"text/javascript\">\r\njQuery(\'#tabs\').tabs({\r\n	fx: { height: \'toggle\', duration: 200 },\r\n	select: function(event, ui) {\r\n		jQuery(this).css(\'height\', jQuery(this).height());\r\n	},\r\n//	show: function(event, ui) {\r\n//		jQuery(this).css(\'height\', \'550px\');\r\n//		jQuery(this).css(\'overflow\', \'visible\');\r\n//	}\r\n});\r\n\r\n\r\n\r\n	jQuery(document).ready(function($) {\r\n	\r\n// Hilfe Boxen\r\n// $(\'.dialog\').dialog({ autoOpen: false, width: 700});\r\n// $(\'#info_ueberschrift\').click(function(){  $(\'#dialog_ueberschrift\').dialog(\'open\');return false;});\r\n// $(\'#info_text\').click(function(){  $(\'#dialog_text\').dialog(\'open\');return false;});\r\n\r\n			$(\'#tabs\').tabs();\r\n\r\n			$(\".datepicker1\").datepicker({\r\n							inline: true,\r\n							dateFormat: \"dd.mm.yy\"\r\n							\r\n			});\r\n\r\n			$(\".datepicker2\").datepicker({\r\n							inline: true,\r\n				 			defaultDate: \"+1w\",\r\n							dateFormat: \"dd.mm.yy\"\r\n			});\r\n\r\n	});\r\n</script>','olien','olien',1386851753,1386854761,'',0),
  (3,'0060 - Abstand einfügen',0,'<?php\r\n\r\nif ($REX[\'REDAXO\']) {\r\necho  \'Höhe: REX_VALUE[1] px\';\r\n} else {\r\necho \'<span class=\"abstand\" style=\"height: REX_VALUE[1]px\"></span>\';\r\n}\r\n?>','<?php\r\n\r\n$objForm = new mform();\r\n\r\n$objForm->addHeadline(\'Abstand einfügen\');\r\n\r\n$objForm->addTextField(1,array(\'label\'=>\'Höhe in Pixel\',\'style\'=>\'width:150px\'));\r\n\r\necho $objForm->show_mform();\r\n\r\n\r\n?>','olien','olien',1386851774,1386851817,'',0),
  (4,'0400 - Linkliste',0,'<?php\r\n\r\n$ausgabe = \'\';\r\n\r\n$arr = explode(\',\',\'REX_LINKLIST[1]\');\r\n\r\n$letztesElement = end($arr); \r\n\r\n$aktuelleArtikelID = $REX[\'ARTICLE_ID\'];\r\n\r\nforeach ($arr as $value)\r\n{\r\n  $article = OOArticle::getArticleById($value);\r\n \r\n  if(is_object($article))\r\n  {\r\n	$artikelid = $article->getID();\r\n	\r\n	if ($artikelid == $letztesElement) { $klasse_last = \'class=\"last\"\';} else { $klasse_last = \'\';}\r\n	if ($artikelid == $aktuelleArtikelID ) { $klasse_active = \'class=\"aktiv\"\';} else { $klasse_active = \'\';}\r\n	\r\n	$ausgabe .= \'<li><a \'.$klasse_active.\' href=\"\'.rex_getUrl($value, $REX[\'CUR_CLANG\']).\'\" title=\"\'.$article->getName().\'\">\'.$article->getName().\'</a></li>\'.PHP_EOL;		\r\n\r\n  }\r\n}\r\n\r\necho \'<ul>\'.\"\\r\\n\";\r\necho $ausgabe;\r\necho \'</ul>\'.\"\\r\\n\";\r\n?>','<?php\r\n\r\n$objForm = new mform();\r\n\r\n$objForm->addHeadline(\'Linkliste\');\r\n\r\n$objForm->addLinklistField(1,array(\'label\'=>\'Seiten\'));\r\necho $objForm->show_mform();\r\n\r\n?>\r\n\r\n','olien','',1386851793,0,'',0),
  (5,'5000 --- ---------------------------------------------',0,'','','olien','',1386851803,0,'',0);
/*!40000 ALTER TABLE `rex_module` ENABLE KEYS */;
UNLOCK TABLES;

DROP TABLE IF EXISTS `rex_module_action`;
CREATE TABLE `rex_module_action` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_id` int(11) NOT NULL,
  `action_id` int(11) NOT NULL,
  `revision` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `rex_redirects`;
CREATE TABLE `rex_redirects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `source_url` varchar(255) NOT NULL,
  `target_url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `rex_rexpixel`;
CREATE TABLE `rex_rexpixel` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `anaus` varchar(255) DEFAULT NULL,
  `sichtbarkeit` varchar(255) DEFAULT NULL,
  `layeraktiv` varchar(255) DEFAULT NULL,
  `images` text,
  `aktivesbild` varchar(255) DEFAULT NULL,
  `aktivesbildhoehe` varchar(255) DEFAULT NULL,
  `opacity` int(10) DEFAULT NULL,
  `posleft` int(20) DEFAULT NULL,
  `postop` int(20) DEFAULT NULL,
  `openclose` varchar(255) DEFAULT NULL,
  `zindex` int(20) DEFAULT NULL,
  `layoutpos` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

LOCK TABLES `rex_rexpixel` WRITE;
/*!40000 ALTER TABLE `rex_rexpixel` DISABLE KEYS */;
INSERT INTO `rex_rexpixel` VALUES 
  (1,'an','eingeloggte','inaktiv','rex_pixel_default.jpg','rex_pixel_default.jpg','768',47,1008,27,'close',0,'center');
/*!40000 ALTER TABLE `rex_rexpixel` ENABLE KEYS */;
UNLOCK TABLES;

DROP TABLE IF EXISTS `rex_template`;
CREATE TABLE `rex_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `content` text,
  `active` tinyint(1) DEFAULT NULL,
  `createuser` varchar(255) NOT NULL,
  `updateuser` varchar(255) NOT NULL,
  `createdate` int(11) NOT NULL,
  `updatedate` int(11) NOT NULL,
  `attributes` text,
  `revision` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

LOCK TABLES `rex_template` WRITE;
/*!40000 ALTER TABLE `rex_template` DISABLE KEYS */;
INSERT INTO `rex_template` VALUES 
  (1,'','010 - Standard','<?php\r\nheader(\'Content-Type: text/html; charset=utf-8\');\r\n\r\n  error_reporting(E_ALL); // error_reporting(0);\r\n  \r\n\r\n/* Artikel/Kategorie online? Wenn nein dann auf die Startseite */\r\n\r\nif(!isset($_SESSION)) { session_start(); }\r\n\r\n if (!isset($_SESSION[$REX[\'INSTNAME\']][\'UID\'])) // aber nur wenn nicht im Backend angemeldet\r\n { if ($this->getValue(\'status\') == 0)\r\n   { if ($this->getValue(\'startpage\') == 0)\r\n     {  // Weiterleitung für Artikel\r\n      header(\'Location: \' . $REX[\'SERVER\']);\r\n      exit;\r\n     }\r\n    else\r\n   {\r\n    // Weiterleitung für Kategorien\r\n    header(\'Location: \' . $REX[\'SERVER\']);\r\n    exit;\r\n   }\r\n  }\r\n }\r\n  \r\n?>\r\n<!DOCTYPE html>\r\n<html lang=\"de\">\r\n<head>\r\n  <base href=\"<?php echo seo42::getBaseUrl(); ?>\" />\r\n  <title><?php echo seo42::getTitle(); ?></title>\r\n  <meta name=\"description\" content=\"<?php echo seo42::getDescription(); ?>\" />\r\n  <meta name=\"keywords\" content=\"<?php echo seo42::getKeywords(); ?>\" />\r\n  <meta name=\"robots\" content=\"<?php echo seo42::getRobotRules();?>\" />\r\n  <link rel=\"canonical\" href=\"<?php echo seo42::getCanonicalUrl(); ?>\" />\r\n\r\n  <?php echo seo42::getLangTags(); ?> \r\n\r\n\r\n  <link rel=\"shortcut icon\" href=\"assets/img/icons/favicon.ico\">\r\n  <link rel=\"apple-touch-icon-precomposed\" sizes=\"144x144\" href=\"assets/img/icons/apple-touch-icon-144x144-precomposed.png\">\r\n  <link rel=\"apple-touch-icon-precomposed\" sizes=\"114x114\" href=\"assets/img/icons/apple-touch-icon-114x114-precomposed.png\">\r\n  <link rel=\"apple-touch-icon-precomposed\" sizes=\"72x72\" href=\"assets/img/icons/apple-touch-icon-72x72-precomposed.png\">\r\n  <link rel=\"apple-touch-icon-precomposed\" href=\"assets/img/icons/apple-touch-icon-57x57-precomposed.png\">\r\n  \r\n  \r\n  <meta name=\"MSSmartTagsPreventParsing\" content=\"no\" >\r\n  <meta name=\"robots\" content=\"index, follow\">\r\n  <meta http-equiv=\"cleartype\" content=\"on\">\r\n  <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge,chrome=1\" />\r\n  <!-- http://t.co/dKP3o1e -->\r\n  <meta name=\"HandheldFriendly\" content=\"True\">\r\n  <meta name=\"MobileOptimized\" content=\"320\">\r\n  <meta name=\"viewport\" content=\"width=device-width, target-densitydpi=160dpi, initial-scale=1.0\">\r\n  <!-- Facebook -->\r\n  <meta content=\"de_DE\" />\r\n  <meta content=\"article\" />\r\n  <meta content=\"Social Meta-Tags in WordPress – Allgemein -\" />\r\n  <meta content=\"Das ist die Facebook-Beschreibung des Artikels über Social Meta-Tags.\" />\r\n  <meta content=\"SEO Book\" />\r\n  <meta content=\"assets/img/icons/facebook_twitter-590x434.png\" />\r\n  <!-- Twitter -->\r\n  <meta name=\"twitter:card\" content=\"summary\" />\r\n  <meta name=\"twitter:site\" content=\"@eric108\" />\r\n  <meta name=\"twitter:domain\" content=\"SEO Book\" />\r\n  <meta name=\"twitter:creator\" content=\"@vanseo\" />\r\n  <meta name=\"twitter:image:src\" content=\"assets/img/icons/facebook_twitter-590x434.png\" />\r\n  <!-- http://www.geo-tag.de/generator/en.html -->\r\n  <meta name=\"geo.placename\" content=\"\" />\r\n  <meta name=\"geo.position\" content=\"\" />\r\n  <meta name=\"geo.region\" content=\"\" />\r\n  <meta name=\"ICBM\" content=\"\" />\r\n \r\n  <link rel=\"stylesheet\" href=\"assets/css/screen.min.cc.css\" media=\"screen, print\">\r\n  <link rel=\"stylesheet\" href=\"assets/css/responsive.min.cc.css\" media=\"screen\">\r\n  <link rel=\"stylesheet\" href=\"assets/css/print.min.cc.css\" media=\"print\">\r\n  <!--[if IE]><![endif]-->\r\n\r\n  </head>\r\n<?php if ($REX[\'START_ARTICLE_ID\'] == $this->getValue(\"article_id\")) {\r\necho \'<body id=\"home\">\'.PHP_EOL;\r\n} else {\r\necho \'<body>\'.PHP_EOL;\r\n}\r\n\r\n?>\r\n<!--[if lt IE 7]>\r\n<div class=\"iehinweis\"><p class=\"chromeframe\">You are using an <strong>outdated</strong> browser. Please <a href=\"http://browsehappy.com/\">upgrade your browser</a>.</p> </div>\r\n<![endif]-->\r\n\r\n<a href=\"top\"></a>\r\n\r\n<?php echo nav42::getNavigationByLevel(0, 2, false, true, false); ?>\r\n\r\nREX_ARTICLE[]\r\n\r\n<script src=\"assets/js/vendor/jquery.1.10.2.min.js\"></script>\r\n<script src=\"assets/js/vendor/modernizr.2.7.0.min.js\"></script>\r\n<script src=\"assets/js/vendor/jquery.easing.1.3.min.js\"></script>\r\n<script src=\"assets/js/domscript.js\"></script>\r\n\r\n</body>\r\n</html>',1,'olien','olien',1386852065,1387188340,'a:3:{s:10:\"categories\";a:1:{s:3:\"all\";s:1:\"1\";}s:5:\"ctype\";a:0:{}s:7:\"modules\";a:1:{i:1;a:1:{s:3:\"all\";s:1:\"1\";}}}',0),
  (2,'','Wartungsarbeiten','<!DOCTYPE html>\r\n<html lang=\"de\">\r\n  <head>\r\n    <meta charset=\"utf-8\" />\r\n    <title><?php $REX[\'SERVERNAME\'].\' | \'.$this->getValue(\"name\"); ?></title>\r\n \r\n<link href=\'http://fonts.googleapis.com/css?family=Didact+Gothic|PT+Serif&amp;v2\' rel=\'stylesheet\' type=\'text/css\'>\r\n\r\n <style type=\"text/css\">\r\n\r\n	* {margin: 0; padding: 0;}\r\n	\r\n    body {\r\n    size:12px;\r\n	color: #555;\r\n	background: #e6e6e6;\r\n	}\r\n	\r\n	#content{\r\n		margin: 20px 0 0 20px;\r\n	}\r\n	\r\n	h1 {\r\n	font-family: \'Didact Gothic\', sans-serif;\r\n	font-style: normal;\r\n	font-weight: 400;\r\n	font-size: 30px;\r\n	text-transform: none;\r\n	text-decoration: none;\r\n	letter-spacing: 0em;\r\n	word-spacing: 0em;\r\n	line-height: 1.4;\r\n	}\r\n\r\n	p {\r\n	font-family: \'PT Serif\', serif;\r\n	font-style: normal;\r\n	font-weight: 400;\r\n	font-size: 14px;\r\n	text-transform: none;\r\n	text-decoration: none;\r\n	letter-spacing: 0.025em;\r\n	word-spacing: 0em;\r\n	line-height: 1.4;\r\n	}\r\n	\r\n</style>\r\n\r\n  </head>\r\n  <body>\r\n	<div id=\"content\">	\r\n	REX_ARTICLE[]\r\n	</div>\r\n  </body>\r\n</html>',0,'olien','',1386852053,0,'a:3:{s:5:\"ctype\";a:0:{}s:7:\"modules\";a:1:{i:1;a:1:{s:3:\"all\";s:1:\"1\";}}s:10:\"categories\";a:1:{s:3:\"all\";s:1:\"1\";}}',0);
/*!40000 ALTER TABLE `rex_template` ENABLE KEYS */;
UNLOCK TABLES;

DROP TABLE IF EXISTS `rex_xform_email_template`;
CREATE TABLE `rex_xform_email_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `mail_from` varchar(255) NOT NULL DEFAULT '',
  `mail_from_name` varchar(255) NOT NULL DEFAULT '',
  `subject` varchar(255) NOT NULL DEFAULT '',
  `body` text NOT NULL,
  `body_html` text NOT NULL,
  `attachments` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `rex_xform_field`;
CREATE TABLE `rex_xform_field` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table_name` varchar(100) NOT NULL,
  `prio` int(11) NOT NULL,
  `type_id` varchar(100) NOT NULL,
  `type_name` varchar(100) NOT NULL,
  `f1` text NOT NULL,
  `f2` text NOT NULL,
  `f3` text NOT NULL,
  `f4` text NOT NULL,
  `f5` text NOT NULL,
  `f6` text NOT NULL,
  `f7` text NOT NULL,
  `f8` text NOT NULL,
  `f9` text NOT NULL,
  `list_hidden` tinyint(4) NOT NULL,
  `search` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `rex_xform_relation`;
CREATE TABLE `rex_xform_relation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `source_table` varchar(100) NOT NULL,
  `source_name` varchar(100) NOT NULL,
  `source_id` int(11) NOT NULL,
  `target_table` varchar(100) NOT NULL,
  `target_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `rex_xform_table`;
CREATE TABLE `rex_xform_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` tinyint(4) NOT NULL,
  `table_name` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `list_amount` tinyint(3) unsigned NOT NULL DEFAULT '50',
  `prio` int(11) NOT NULL,
  `search` tinyint(4) NOT NULL,
  `hidden` tinyint(4) NOT NULL,
  `export` tinyint(4) NOT NULL,
  `import` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `table_name` (`table_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
