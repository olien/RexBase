<?php
/*
	Redaxo-Addon ImgaeCropper - Backend-Bildbearbeitung
	Deinstallation
	v1.2
	Original by www.jungwirth-media.at
	Rewritten for Rex 4.2x and higher by Falko M�ller (www.falkomueller.com)
	package: redaxo4
*/

//Variablen deklarieren
$mypage = 'imagecropper';
$error = ""; $notice = "";

//Sprachfile einladen
if ($REX['REDAXO']):
	$I18N->appendFile($REX['INCLUDE_PATH']."/addons/".$mypage."/lang/");
endif;

//Datenbank-Eintr�ge l�schen
$db = new sql();
$db->setQuery("DROP TABLE ".$REX['TABLE_PREFIX']."135_imagecropper_config");

//files-Dateien l�schen
$source_dir = $REX['MEDIAFOLDER'].'/addons/'.$mypage;

if (is_dir($source_dir)):
	if (!rex_deleteDir($source_dir, true)):
		$error = $I18N->msg('a135_dir').' '.$source_dir.' '.$I18N->msg('a135_notdeleted').'!';
	endif;
endif;

//Module l�schen
//$notice .= 'Bitte l�schen Sie die installierten Addon-Module von Hand.<br />';

//Aktionen l�schen
//$notice .= 'Bitte l�schen Sie die installierten Addon-Aktionen von Hand.<br />';

//Templates l�schen
//$notice .= 'Bitte l�schen Sie die installierten Addon-Templates von Hand.<br />';

//Deinstallation abschlie�en und Fehler aufbereiten
if (!empty($error)):
	$REX['ADDON']['installmsg'][$mypage] = $error;
	//rex_warning($error);
endif;
	//Komponente als deinstalliert markieren
	$REX['ADDON']['install'][$mypage] = 0;
		echo rex_info($notice);
?>