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
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

LOCK TABLES `rex_62_params` WRITE;
/*!40000 ALTER TABLE `rex_62_params` DISABLE KEYS */;
INSERT INTO `rex_62_params` VALUES 
  (1,'translate:pool_file_description','med_description',1,'',2,'','','','','%USER%',1386849500,'%USER%',1386849500),
  (2,'translate:pool_file_copyright','med_copyright',2,'',1,'','','','','%USER%',1386849500,'%USER%',1386849500),
  (3,'translate:online_from','art_online_from',1,'',10,'','','','','%USER%',1386849500,'%USER%',1386849500),
  (4,'translate:online_to','art_online_to',2,'',10,'','','','','%USER%',1386849500,'%USER%',1386849500),
  (5,'translate:description','art_description',3,'',2,'','','','','%USER%',1386849500,'%USER%',1386849500),
  (6,'translate:keywords','art_keywords',4,'',2,'','','','','%USER%',1386849500,'%USER%',1386849500),
  (7,'translate:metadata_image','art_file',5,'',6,'','','','','%USER%',1386849500,'%USER%',1386849500),
  (8,'translate:teaser','art_teaser',6,'',5,'','','','','%USER%',1386849500,'%USER%',1386849500),
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
  `art_description` text,
  `art_keywords` text,
  `art_file` varchar(255) DEFAULT '',
  `art_teaser` varchar(255) DEFAULT '',
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
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

LOCK TABLES `rex_article` WRITE;
/*!40000 ALTER TABLE `rex_article` DISABLE KEYS */;
INSERT INTO `rex_article` VALUES 
  (1,1,0,'Starseite','Starseite',1,'',1,1,'|',1,1386851172,1386851163,1,0,'olien','olien',0,'','','','','','','','','','','','','',''),
  (3,2,0,'404 Seite','',0,'',0,1,'|',1,1386851132,1386851173,1,0,'olien','olien',0,'','','','','','','','','','','','','',''),
  (4,3,0,'Wartungsarbeiten','',0,'',0,2,'|',1,1386851140,1386851173,1,0,'olien','olien',0,'','','','','','','','','','','','','','');
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
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
  `anaus` text,
  `sichtbarkeit` text,
  `images` text,
  `aktivesbild` varchar(255) DEFAULT NULL,
  `aktivesbildhoehe` varchar(255) DEFAULT NULL,
  `opacity` int(10) DEFAULT NULL,
  `posleft` int(20) DEFAULT NULL,
  `postop` int(20) DEFAULT NULL,
  `openclose` varchar(255) DEFAULT NULL,
  `zindex` varchar(255) DEFAULT NULL,
  `layoutpos` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

LOCK TABLES `rex_rexpixel` WRITE;
/*!40000 ALTER TABLE `rex_rexpixel` DISABLE KEYS */;
INSERT INTO `rex_rexpixel` VALUES 
  (1,'an','eingeloggte','rex_pixel_default.jpg','rex_pixel_default.jpg','768',50,10,10,'open','drueber','center');
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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

LOCK TABLES `rex_template` WRITE;
/*!40000 ALTER TABLE `rex_template` DISABLE KEYS */;
INSERT INTO `rex_template` VALUES 
  (1,'','010 - Standard','',1,'olien','olien',1386851154,1386851154,'a:3:{s:10:\"categories\";a:1:{s:3:\"all\";s:1:\"1\";}s:5:\"ctype\";a:0:{}s:7:\"modules\";a:1:{i:1;a:1:{s:3:\"all\";s:1:\"1\";}}}',0);
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
