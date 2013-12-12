<?php
/*
	Redaxo-Addon ImgaeCropper - Backend-Bildbearbeitung
	makeimg.inc.php
	v1.1
	Original by www.jungwirth-media.at
	Rewritten for Rex 4.2x and higher by Falko Müller (www.falkomueller.com)
	package: redaxo4
*/

/*
$icrop_filepath			Pflicht
$icrop_filename			Pflicht
$icrop_x1				Optional (Int)
$icrop_x2				Optional (Int)
$icrop_y1				Optional (Int)
$icrop_y2				Optional (Int)
$icrop_width			Optional (Int)
$icrop_height			Optional (Int)
$icrop_imgname			Standard setzen
$icrop_imgtype			Standard setzen
$icrop_imgqual			Standard setzen (Int)
$icrop_turn				Optional (Int)
$icrop_resize			Optional (Int)
$icrop_resizepx			Optional (Int)
$icrop_filter			Optional
$icrop_filterphp4		Optional
$icrop_filterlevel		Optional (Int)
$icrop_oldcat			Optional (Int)
*/

//Standards setzen
$makemsg = "";
if (empty($icrop_imgname)):
	$icrop_imgname = date("Ymd-His")."_".time();
endif;
//if (!eregi("gif|jpg|jpeg|png", $icrop_imgtype)):
if (!preg_match("/(gif|jpg|jpeg|png)/i", $icrop_imgtype)):
	$icrop_imgtype = "jpg";
endif;
if (empty($icrop_imgqual) || $icrop_imgqual <= 0 || $icrop_imgqual > 100):
	$icrop_imgqual = 75;
endif;
if ($icrop_turn > 0 && !function_exists("imagerotate")):
	$icrop_turn = 0;
	$makemsg .= $I18N->msg('a135_makeimg_info1');
elseif ($icrop_turn == 270):
	$icrop_turn = -90;
endif;
if (!function_exists("imagefilter")):
	//PHP5-imagefilter nicht verfügbar (debian php5.2)
	$makemsg .= $I18N->msg('a135_makeimg_info2');
else:
	//PHP5-imagefilter verfügbar
	if (in_array($icrop_filter, $php4filters)):
		$icrop_filterphp4 = $icrop_filter;
		$icrop_filter = 0;
	endif;	
endif;
$icrop_filterlevel = intval($icrop_filterlevel);

//zus. Variablenb holen/erstellen/prüfen
$user = $REX_USER->getValue('login');
$newtype = "image/".$icrop_imgtype;
$newfilename = preg_replace('/[^-_\\da-zA-Z]/', '_', $icrop_imgname);
	if (@file_exists($REX['MEDIAFOLDER'].'/'.$newfilename.'.'.$icrop_imgtype)):
		$aa = 1;
		while (@file_exists($REX['MEDIAFOLDER'].'/_'.$aa.'.'.$icrop_imgtype)):
			$aa++;
		endwhile;
		
		$newfilename .= '_'.$aa.'.'.$icrop_imgtype;
	else:
		$newfilename .= '.'.$icrop_imgtype;
	endif;
$newfilepath = $REX['MEDIAFOLDER'].'/'.$newfilename;

//neues Bild erstellen
$newimg = Image::open($icrop_filepath);

	//Crop
	if ($icrop_x1 != $icrop_x2 && $icrop_y1 != $icrop_y2 && !empty($icrop_width) && !empty($icrop_height)):
		$newimg = $newimg->crop($icrop_x1, $icrop_y1, $icrop_x2, $icrop_y2);
	endif;
	
	//Resize
	//if (eregi("width|height", $icrop_resize) && $icrop_resizepx > 0):
	if (preg_match("/(width|height)/i", $icrop_resize) && $icrop_resizepx > 0):
		if ($icrop_resize == "width"):
			$newimg = $newimg->resize($icrop_resizepx, 0);
		else:
			$newimg = $newimg->resize(0, $icrop_resizepx);
		endif;
	endif;
	
	//Rotate
	if (!empty($icrop_turn)):
		$newimg = $newimg->rotate($icrop_turn);
	endif;

	//Filter anwenden (imagefilter PHP5)
	if (!empty($icrop_filter)):
		$newimg = $newimg->filter(constant(rtrim($icrop_filter)));
	endif;
	//Filter anwenden (PHP4)
	if (!empty($icrop_filterphp4)):
		$newimg = $newimg->filterphp4(trim($icrop_filterphp4), $icrop_filterlevel);
	endif;
	

//Bild abspeichern und im Medienpool verankern
$newimg->save($newfilepath, $icrop_imgqual);
	@chmod($newfilepath, 0777);
	
	if (@file_exists($newfilepath)):
		//Medienpool füllen
		$newfilesize = @filesize($newfilepath);
		$newimgsize = @getimagesize($newfilepath);
		
		//MP-Kategorie prüfen
		if (empty($icrop_oldcat) || $icrop_oldcat < 0):
			$icrop_oldcat = 0;
		endif;
	
		//MP-DB schreiben
		$db = new sql();
		$db->setTable($REX['TABLE_PREFIX']."file");
	
		$db->setValue("re_file_id", 0, 'int');
		$db->setValue("category_id", $icrop_oldcat, 'int');
		$db->setValue("filetype", $newtype, 'string');
		$db->setValue("filename", $newfilename, 'string');
		$db->setValue("originalname", $icrop_filename, 'string');
		$db->setValue("filesize", $newfilesize, 'int');
		$db->setValue("width", $newimgsize[0], 'int');
		$db->setValue("height", $newimgsize[1], 'int');
		$db->setValue("title", $newfilename, 'string');
		$db->setValue("med_description", "", 'string');
		$db->setValue("createdate", time(), 'int');
		$db->setValue("updatedate", time(), 'int');
		$db->setValue("createuser", $user, 'int');
		$db->setValue("updateuser", $user, 'int');
		
		if($db->insert()):
			$makeimgsuccess = 1;
		else:
			$makeimgsuccess = 0;
		endif;
	else:
		$makeimgsuccess = 0;
	endif;
?>