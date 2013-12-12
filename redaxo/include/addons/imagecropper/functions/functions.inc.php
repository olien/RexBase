<?php
/*
	Redaxo-Addon ImgaeCropper - Backend-Bildbearbeitung
	Funktionen für ExtensionPoints
	v1.2
	Original by www.jungwirth-media.at
	Rewritten for Rex 4.2x and higher by Falko Müller (www.falkomueller.com)
	package: redaxo4
*/

//Std.vorgaben auslesen
$db = new sql();
$db->setQuery("SELECT * FROM ".$REX['TABLE_PREFIX']."135_imagecropper_config limit 0,1"); 
$imagecropCfg = $db->get_array();	//mehrdimensionales Array kommt raus

function a135_showCrop($params)
{	global $REX, $REX_ARTICLE, $I18N;
	global $imagecropCfg;
	global $mypage;
	//session_start();

	//Variablen deklarieren
	$newOP = $params['subject'];
	$page = rex_request('page');
	
	$search[0] = '?page=imagecropper&amp;subpage=icrop_backlink';
	$replace[0] = 'javascript:history.back();';
	$search[1] = '?page=imagecropper&amp;subpage=mediapool';
	$replace[1] = '?page=imagecropper&amp;subpage=mediapool'.a135_getTinyLink();
	$search[2] = '?page=imagecropper&amp;subpage=icrop_actionend';
	$replace[2] = '?page=mediapool'.a135_getTinyLink();
		$newOP = str_replace($search, $replace, $newOP);
	
	//Ausgabe freigeben	
    return $newOP;
}

function a135_addAssets($params)
{	global $REX, $REX_ARTICLE;
	global $imagecropCfg;
	global $mypage, $func;

	//CSS und JS anfügen
	$params['subject'] .= '<link rel="stylesheet" type="text/css" href="../files/addons/'.$mypage.'/jcrop.css" />';
	if (!isset($_POST['submit']) && $func != "save"):
		$params['subject'] .= '<script type="text/javascript" src="../files/addons/'.$mypage.'/jquery.crop.min.js"></script>';
		$params['subject'] .= '<script type="text/javascript" src="../files/addons/'.$mypage.'/jcrop.js"></script>';
	endif;

	return $params['subject'];
}

function a135_addLink2Media($params)
{	global $mypage, $I18N;
	$file = rex_request('file_id', "int");

	//Bildparameter holen
	$filename = $filetype = "";	
	if (!empty($file)):
		$file = OOMedia::getMediaById($file);
		if (is_object($file)):
			$filename = $file->getFileName();
			$filetype = $file->getType();
		endif;
	endif;
	
	//Link setzen, wenn Bilddatei
	if (!empty($filename) && preg_match("/(png|gif|jpg|jpeg)$/i", $filetype)):
		echo ' <div class="rex-form-row">
					<p class="rex-form-read">
						<label for="flink">Bearbeiten</label>
						<span class="rex-form-read"><a href="index.php?page=imagecropper&subpage=cropimg&bild='.$filename.a135_getTinyLink().'" id="flink">'.$I18N->msg('a135_edit').'</a></span>
					</p>
			   </div>';
	endif;
}

function a135_addLink2Medialist($params)
{	global $mypage, $I18N;
	$file = $params['file_name'];

	//Bildparameter holen
	$filename = $filetype = "";
	if (!empty($file)):
		$file = OOMedia::getMediaByFileName($file);
		if (is_object($file)):
			$filename = $file->getFileName();
			$filetype = $file->getType();
		endif;
	endif;
		
	//Link setzen, wenn Bilddatei
	if (!empty($filename) && preg_match("/(png|gif|jpg|jpeg)$/i", $filetype)):
		echo '<a href="index.php?page=imagecropper&subpage=cropimg&bild='.$filename.a135_getTinyLink().'" id="flink">'.$I18N->msg('a135_edit').'</a><br /><br />';
	endif;
}

function a135_getTinyLink()
{	//Hilffunktion zur Übernahme der Tiny-Parameter für Ansteuerung Tiny
	$isTinymce = rex_request('tinymce', 'string');
		$tinymceOpener = rex_request('opener_input_field', 'string');

	//Tiny abfangen
	if (strtolower($isTinymce) == "true" && !empty($tinymceOpener)):
		$tinyLink = '&tinymce=true&opener_input_field='.$tinymceOpener;
	else:
		$tinyLink = "";
	endif;

	return $tinyLink;
}
?>