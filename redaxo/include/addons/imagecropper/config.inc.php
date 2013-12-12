<?php
/*
	Redaxo-Addon imageCropper
	Konfiguration
	v1.1
	by Falko Müller @ 2012 (based on the original from www.jungwirth-media.at)
	package: redaxo4
*/

//Variablen deklarieren
$mypage = "imagecropper";

$REX['ADDON']['rxid'][$mypage] = '135';
$REX['ADDON']['page'][$mypage] = $mypage;
$REX['ADDON']['perm'][$mypage] = $mypage.'[]';
$REX['ADDON']['author'][$mypage] = '&copy; Falko M&uuml;ller';
$REX['ADDON']['version'][$mypage] = '1.2';
$REX['ADDON']['supportpage'][$mypage] = 'forum.redaxo.de';

$REX['PERM'][] = $mypage.'[]';

//AddOn Einstellungen
// --- DYN
// --- /DYN

//Sprachfile einladen
if ($REX['REDAXO']):
	$I18N->appendFile($REX['INCLUDE_PATH']."/addons/".$mypage."/lang/");
	$REX['ADDON']['name'][$mypage] = $I18N->Msg('a135_title');
	
	// Addon-Subnavigation für das REDAXO-Menue
	$REX['ADDON'][$mypage]['SUBPAGES'] = array (
		array('', 			$I18N->msg('a135_subnavi_index')),
		array('help',		$I18N->msg('a135_subnavi_help'))
	);	
endif;

//Globale Funktionen einladen/definieren
if (!function_exists(fdfm_checkIEbreaks)):
	function fdfm_checkIEbreaks($params)
	{	$OP = $params['subject'];
		$fChar = strpos($OP, "<");
			if ($fChar > 1):
				$OP = substr($OP, $fChar, strlen($OP));				
			endif;
		return $OP;
	}
endif;

//Backendfunktionen einladen/definieren
if ($REX['REDAXO'] && $REX['USER'] && $REX['ADDON']['status'][$mypage] == true):
	if ($REX['USER']->hasPerm($mypage.'[]') || $REX['USER']->isAdmin()):
		//Cropeinbindung festlegung
		$page = rex_request('page');
		$subpage = rex_request('subpage');
		$addaction = rex_request('addaction');
		$stcArr = array("mediapool");	//Definition der gültigen Seiten, wo die Strukturerweiterungen greifen sollen
		
			if ((in_array($page, $stcArr) || ($page == $mypage && $subpage == "cropimg"))):
				require_once $REX['INCLUDE_PATH']."/addons/".$mypage."/functions/functions.inc.php";
			
				rex_register_extension('PAGE_HEADER', 'a135_addAssets');
				rex_register_extension('MEDIA_FORM_EDIT', 'a135_addLink2Media', $params);
				rex_register_extension('MEDIA_LIST_FUNCTIONS', 'a135_addLink2Medialist');
				rex_register_extension('OUTPUT_FILTER', 'a135_showCrop');
			endif;
	endif;
	
	//rex_register_extension('OUTPUT_FILTER', 'fdfm_checkIEbreaks');
endif;

//Frontendfunktionen einladen/definieren
if (!$REX['REDAXO'] && $REX['ADDON']['status'][$mypage] == true):
endif;
?>