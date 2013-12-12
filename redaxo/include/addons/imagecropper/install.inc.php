<?php
/*
	Redaxo-Addon ImgaeCropper - Backend-Bildbearbeitung
	Installation
	v1.2
	Original by www.jungwirth-media.at
	Rewritten for Rex 4.2x and higher by Falko Müller (www.falkomueller.com)
	package: redaxo4
*/

//Variablen deklarieren
$mypage = "imagecropper";
$error = "";

//Sprachfile einladen
if ($REX['REDAXO']):
	$I18N->appendFile($REX['INCLUDE_PATH']."/addons/".$mypage."/lang/");
endif;

//Datenbank-Einträge vornehmen
$db = new sql();
$db->setQuery("DROP TABLE IF EXISTS ".$REX['TABLE_PREFIX']."135_imagecropper_config");

$db = new sql();
$db->setQuery("CREATE TABLE ".$REX['TABLE_PREFIX']."135_imagecropper_config (id INT( 3 ) NOT NULL AUTO_INCREMENT PRIMARY KEY, preset1 varchar(9) NOT NULL default '50x33', preset2 varchar(9) NOT NULL default '100x67', preset3 varchar(9) NOT NULL default '150x100', preset4 varchar(9) NOT NULL default '200x133', preset5 varchar(9) NOT NULL default '400x267') ENGINE = MYISAM COMMENT = 'Addon ImageCropper Konfiguration'");

$db = new sql();
$db->setTable($REX['TABLE_PREFIX']."135_imagecropper_config");

$db->setValue("preset1", '50x33');
$db->setValue("preset2", '100x67');
$db->setValue("preset3", '150x100');
$db->setValue("preset4", '200x133');
$db->setValue("preset5", '400x267');
$db->insert();

//files-Dateien erstellen/kopieren
$source_dir = $REX['INCLUDE_PATH'].'/addons/'.$mypage.'/files';
$dest_dir = $REX['MEDIAFOLDER'].'/addons/'.$mypage;
$start_dir = $REX['MEDIAFOLDER'].'/addons';

if (is_dir($source_dir)):
	//zuerst addons-Verzeichnis prüfen/anlegen im files-Ordner
	if (!is_dir($start_dir)):
		mkdir($start_dir);
		chmod($start_dir, 0777);
	endif;
	
	//jetzt alle Inhalte aus addon-files nach files-ordner kopieren
	if (!rex_copyDir($source_dir, $dest_dir, $start_dir)):
		$error = $I18N->msg('a135_dir').' '.$source_dir.' '.$I18N->msg('a135_notcopyed').' ('.$I18N->msg('a135_destdir').': '.$dest_dir.')!';
	endif;
endif;

//Module anlegen

//Aktionen anlegen

//Templates anlegen

//Installation abschließen und Fehler aufbereiten
if (!empty($error)):
	$REX['ADDON']['installmsg'][$mypage] = $error;
	$REX['ADDON']['install'][$mypage] = 0;
else:
	//Komponente als installiert markieren
	$REX['ADDON']['install'][$mypage] = 1;
endif;
?>