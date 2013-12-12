<?php
/*
	Redaxo-Addon ImgaeCropper - Backend-Bildbearbeitung
	Deinstallation
	v1.2
	Original by www.jungwirth-media.at
	Rewritten for Rex 4.2x and higher by Falko Müller (www.falkomueller.com)
	package: redaxo4
*/

//Variablen deklarieren
$mypage = 'imagecropper';
$error = ""; $notice = "";

//Sprachfile einladen
if ($REX['REDAXO']):
	$I18N->appendFile($REX['INCLUDE_PATH']."/addons/".$mypage."/lang/");
endif;

//Datenbank-Einträge löschen
$db = new sql();
$db->setQuery("DROP TABLE ".$REX['TABLE_PREFIX']."135_imagecropper_config");

//files-Dateien löschen
$source_dir = $REX['MEDIAFOLDER'].'/addons/'.$mypage;

if (is_dir($source_dir)):
	if (!rex_deleteDir($source_dir, true)):
		$error = $I18N->msg('a135_dir').' '.$source_dir.' '.$I18N->msg('a135_notdeleted').'!';
	endif;
endif;

//Module löschen
//$notice .= 'Bitte löschen Sie die installierten Addon-Module von Hand.<br />';

//Aktionen löschen
//$notice .= 'Bitte löschen Sie die installierten Addon-Aktionen von Hand.<br />';

//Templates löschen
//$notice .= 'Bitte löschen Sie die installierten Addon-Templates von Hand.<br />';

//Deinstallation abschließen und Fehler aufbereiten
if (!empty($error)):
	$REX['ADDON']['installmsg'][$mypage] = $error;
	//rex_warning($error);
endif;
	//Komponente als deinstalliert markieren
	$REX['ADDON']['install'][$mypage] = 0;
		echo rex_info($notice);
?>