<?php

	$link 				= "";
	$linkbild 			= "";
	$linkueberschrift 	= "";
	$linkanfang 		= "";
	$linkende 			= "";		
	$bild 				= '';
	$bildunterschrift 	= '';
	$weiterlesenlink 	= '';
	$bildcode 			= '';
	$text 	 			= '';



# Variablen für Online-Prüfung
	$online = 'REX_VALUE[20]';
	$time = time();
	$start = strtotime('REX_VALUE[19]');
	$end = strtotime('REX_VALUE[18]');

$msgonline = '
<div class="rex-message"><div class="rex-info" style="font-size: 15px; font-weight: normal;"><p><span>Für diesen Inhalt ist ein in Veröffentlichungszeitraum angegeben (REX_VALUE[19] - REX_VALUE[18])<br/><b>Dieser Inhalt wird auf der Webseite angezeigt.</b><span></p></div></div>';

$msgoffline = '
<div class="rex-message"><div class="rex-warning" style="font-size: 15px; font-weight: normal;"><p><span>Für diesen Inhalt ist ein in Veröffentlichungszeitraum angegeben (REX_VALUE[19] - REX_VALUE[18])<br/><b>Dieser Inhalt wird momentan NICHT auf der Webseite angezeigt.</b><span></p></div></div>';


if (!isset($REX['base']['textmodulcount'])){
	$REX['base']['textmodulcount'] = 0;
}
$REX['base']['textmodulcount'] = $REX['base']['textmodulcount'] + 1;


if(!$REX['REDAXO']) {

//
//	Frontend
//
  if(OOAddon::isAvailable('textile'))
	{

	if(REX_IS_VALUE[3]) { // Text
		$text = '';
 		$text = htmlspecialchars_decode('REX_VALUE[3]',ENT_QUOTES);
		$text = str_replace('<br />','',$text);
		$text = rex_a79_textile($text);
	} 

// echo "REX_FILE[1]";

    if ("REX_FILE[1]" != "") { // Bild

	  	$bild 				= OOMedia::getMediaByName('REX_FILE[1]');
	   	$bildTitle 			= $bild->getTitle();
	   	$bildBeschreibung 	= $bild->getValue('med_description');
	    $bildCopyright 		= $bild->getValue('med_copyright');
	   	$bildDateiName 		= $bild->getFileName();
	   	$bildBreite 		= $bild->getWidth();
	   	$bildHoehe 			= $bild->getHeight();

		$image = rex_image_manager::getImageCache('REX_FILE[1]', "contentimage_REX_VALUE[8]");

			//  printf('%s[%s] = %d x %d Pixel',
	  		// $bildDateiName,
	  		// "contentimage_REX_VALUE[8]",
	  		// $image->getWidth(),
			// $image->getHeight()
			// );

	   	if ($bildCopyright != '') {
	   		$bildCopyright = " | (c) ".$bildCopyright;
	   	}

	$bildunterschrift = '';
	if(REX_IS_VALUE[6])   {
 			$bildunterschrift = htmlspecialchars_decode('REX_VALUE[6]',ENT_QUOTES);
			$bildunterschrift = str_replace('<br />','',$bildunterschrift);
			$bildunterschrift = rex_a79_textile($bildunterschrift);
			$bildunterschrift = '<div class="bildunterschrift">'.$bildunterschrift.'</div>'.PHP_EOL;
	} 

		if(REX_IS_VALUE[9])   { 
			$rahmen = 'class="rahmen"';
		} else {
			$rahmen = '';
		}

	$bildcode = '<img '.$rahmen.' src="index.php?rex_img_type=contentimage_REX_VALUE[8]&amp;rex_img_file='.$bildDateiName.'" title="REX_VALUE[5]'.$bildCopyright.'" alt="REX_VALUE[5]'.$bildCopyright.'" width="'.$image->getWidth().'" height="'.$image->getHeight().'"/>'.PHP_EOL;
	}

	 if(REX_IS_VALUE[11] OR "REX_LINK_ID[1]" != 0) {

		$link = "1";
	   	$externerlink = "REX_VALUE[11]";
	 	  	if($externerlink != str_replace("http://", "",$externerlink)) {
				$linkanfang = '<a href="REX_VALUE[11]">'.PHP_EOL;
			} else {
				$linkanfang = '<a href="http://REX_VALUE[11]">'.PHP_EOL;
			}
	 
 		if ("REX_LINK_ID[1]" != 0) {
	  		$linkanfang  = '<a href="'.rex_geturl("REX_LINK_ID[1]", $REX['CUR_CLANG']).'">'.PHP_EOL;
		  } 
	
		$linkende ='</a>'.PHP_EOL;


		if ("REX_VALUE[13]" == "nurbildlink") { $linkbild = "1"; }
		if ("REX_VALUE[13]" == "ueberschriftlink") { $linkueberschrift = "1"; }
		if ("REX_VALUE[13]" == "ueberschriftundbildlink") { $linkbild = "1"; $linkueberschrift = "1";}	

		$weiterlesenlink = '';		
		if (REX_IS_VALUE[10]) {
			$weiterlesenlink = '<div class="weiterlesen">'.$linkanfang.'REX_VALUE[10]'.$linkende.'</div>'.PHP_EOL;
		} else {
			$weiterlesenlink = '';
		}

		}
	

	// Überschrift
	if ($linkueberschrift) {
		$contentueberschrift = '<REX_VALUE[2]>'.$linkanfang.'REX_VALUE[1]'.$linkende.'</REX_VALUE[2]>'.PHP_EOL;
	} else {
		$contentueberschrift =  '<REX_VALUE[2]>REX_VALUE[1]</REX_VALUE[2]>'.PHP_EOL;
	}

	// Bild
	if ($linkbild) {
		$contentbild = $linkanfang.$bildcode.$linkende.$bildunterschrift;
	} else {
		$contentbild = $bildcode.$bildunterschrift;
	}

	// Text
	$contenttext = $text;
	$contentweiterlesen = $weiterlesenlink;

	//HTML
	$content = '<div class="textbildlink">'.PHP_EOL;

	// Ausrichtung 

	$floatimg = '';
	$block = '';

	// Ausrichtungen "im Fliesstext links"
	if ("REX_VALUE[7]" == 'l' OR "REX_VALUE[7]" == 'tl' OR "REX_VALUE[7]" == 'tlu') {
		$floatimg = "flLeft";
	}

	// Ausrichtungen "im Fliesstext rechts"
	if ("REX_VALUE[7]" == 'r' OR "REX_VALUE[7]" == 'tr' OR "REX_VALUE[7]" == 'tru') {
		$floatimg = "flRight";
	}

	if ("REX_VALUE[7]" == 'tl' OR "REX_VALUE[7]" == 'tr' OR "REX_VALUE[7]" == 'tlu' OR "REX_VALUE[7]" == 'tru' ) {
		$block = 'block';
	} else {
		$block = '';
	}

	if ("REX_VALUE[7]" == 'tlu' OR "REX_VALUE[7]" == 'tru') {
		$content .= '<div class="bildcontainer '.$floatimg.' REX_VALUE[8]">'.PHP_EOL;
		$content .= $contentbild.'</div>'.PHP_EOL;
		$content .= '<div class="text '.$block.'">'.PHP_EOL;
		$content .= $contentueberschrift.$contenttext.$contentweiterlesen.'</div>'.PHP_EOL;
	} else {
		$content .= $contentueberschrift;
		$content .= '<div class="bildcontainer '.$floatimg.' REX_VALUE[8]">'.PHP_EOL;
		$content .= $contentbild.'</div>'.PHP_EOL;
		$content .= '<div class="text '.$block.'">'.PHP_EOL;
		$content .= $contenttext.$contentweiterlesen.'</div>'.PHP_EOL;
	}

	$content .=  '</div>'.PHP_EOL;


// Zeiteinstellung
if ($online == "1") {

	if( $time > $start && $time < $end )
		{
			echo PHP_EOL.'<!-- SLICE ID REX_SLICE_ID ANFANG -->'.PHP_EOL;
			echo $content;
	  		echo '<!-- // -->'.PHP_EOL;
		}
	}
# Prüfung aus
  if ($online == "") {
	echo PHP_EOL.'<!-- SLICE ID REX_SLICE_ID ANFANG -->'.PHP_EOL;
	echo $content;
  	echo '<!-- // -->'.PHP_EOL;	   
  }


	} else {
	  echo rex_warning('Dieses Modul benötigt das "textile" Addon!');
	}
} else {

//
//	Backend
//

// Eingaben prüfen

$warnings = [];

if ("REX_FILE[1]" != "" AND "REX_VALUE[5]" == "" ) {
    $warnings[] = 'Bitte geben Sie einen Alternativtext für das Bild an.';
}

if ("REX_FILE[1]" == "" AND "REX_VALUE[5]" != "" OR "REX_FILE[1]" == "" AND "REX_VALUE[6]" != "" ) {
    $warnings[] = 'Sie haben Angaben zu einem Bild gemacht ohne ein Bild auszuwählen. Bitte wählen Sie ein Bild aus.';
}

if ("REX_VALUE[10]" != "" AND ("REX_LINK_ID[1]" == "" AND "REX_VALUE[11]" == "" )) {
    $warnings[] = 'Bitte geben Sie einen Link an.';
}

if ("REX_VALUE[11]" != "" AND "REX_LINK_ID[1]" != "") {
    $warnings[] = 'Bitte geben Sie entweder einen externen oder einen internen Link an.';
} 

if (("REX_VALUE[11]" != "" OR "REX_LINK_ID[1]" != "") AND (("REX_VALUE[1]" == "" AND "REX_VALUE[13]" == "ueberschriftlink") OR ("REX_VALUE[1]" == "" AND "REX_VALUE[13]" == "ueberschriftundbildlink"))) {
    $warnings[] = 'Die Überschrift kann nicht verlinkt werden. Bitte geben Sie eine Überschrift ein.';
}

if (("REX_VALUE[11]" != "" OR "REX_LINK_ID[1]" != "") AND (("REX_FILE[1]" == "" AND "REX_VALUE[13]" == "nurbildlink") OR ("REX_FILE[1]" == "" AND "REX_VALUE[13]" == "ueberschriftundbildlink"))) {
    $warnings[] = 'Das Bild kann nicht verlinkt werden. Bitte wählen Sie ein Bild aus.';
}

if ($REX['REDAXO'] && count($warnings) > 0) {
    foreach ($warnings as $warning) {
        echo rex_warning($warning);
    }
}

// Zeiteinstellung
if ($online == "1") {
	if( $time > $start && $time < $end ) {
		echo $msgonline;
	} else {
		echo $msgoffline;
	}
}

	echo '<table style="width: 100%;">'.PHP_EOL;

	if (REX_IS_VALUE[1]) // Überschrift
	{

		echo '<tr>'.PHP_EOL;
		echo '<td style="padding: 5px; width: 100px; font-weight: bold;">Überschrift</td>'.PHP_EOL;
		echo '<td style="padding: 5px;">REX_VALUE[1]</td>'.PHP_EOL;
		echo '</tr>'.PHP_EOL;
		echo '<tr>'.PHP_EOL;		
		echo '<td style="padding: 5px; width: 100px; font-weight: bold;">Grösse</td>'.PHP_EOL;
		echo '<td style="padding: 5px;">REX_VALUE[2]</td>'.PHP_EOL;
		echo '</tr>'.PHP_EOL;
	}
	
	
	if(REX_IS_VALUE[3])
	{
		$text = '';
 		$text = htmlspecialchars_decode('REX_VALUE[3]',ENT_QUOTES);
		$text = str_replace('<br />','',$text);
		$text = rex_a79_textile($text);
		
		echo '<tr>'.PHP_EOL;
		echo '<td style="padding: 5px; width: 100px; font-weight: bold;">Text</td>'.PHP_EOL;
		echo '<td style="padding: 5px;">'.$text.'</td>'.PHP_EOL;
		echo '</tr>'.PHP_EOL;
	} 


    //  Wenn Bild eingefuegt wurde, Code schreiben 
    if ("REX_FILE[1]" != "") {

	echo '<tr>'.PHP_EOL;		
	echo '<th colspan="2"><br/><hr/><br/></th>'.PHP_EOL;
	echo '</tr>'.PHP_EOL;

	$ausrichtung = "";
    if ("REX_VALUE[7]" == "l") 		$ausrichtung = "im Text links";
    if ("REX_VALUE[7]" == "r") 		$ausrichtung = "im Text rechts";
    if ("REX_VALUE[7]" == "tl") 	$ausrichtung = "links vom Text";
    if ("REX_VALUE[7]" == "tr") 	$ausrichtung = "rechts vom Text";
	if ("REX_VALUE[7]" == "tlu") 	$ausrichtung = "links von Text und Überschrift";
    if ("REX_VALUE[7]" == "tru") 	$ausrichtung = "rechts von Text und Überschrift";

  	$bild 				= OOMedia::getMediaByName('REX_FILE[1]');
   	$bildTitle 			= $bild->getTitle();
   	$bildBeschreibung 	= $bild->getValue('med_description');
    $bildCopyright 		= $bild->getValue('med_copyright');
   	$bildDateiName 		= $bild->getFileName();
   	$bildBreite 		= $bild->getWidth();
   	$bildHoehe 			= $bild->getHeight();

   	if ($bildCopyright != '') {
   		$bildCopyright = " | (c) ".$bildCopyright;
   	}

	echo '<tr>'.PHP_EOL;
	echo '<td style="padding: 5px; width: 100px; font-weight: bold;">Bild</td>'.PHP_EOL;
	echo '<td style="padding: 5px;">REX_FILE[1]<br/><br/>
			<img src="index.php?rex_img_type=rex_medialistbutton_preview&rex_img_file='.$bildDateiName.'" title="REX_VALUE[5]'.$bildCopyright.'" alt="REX_VALUE[5]'.$bildCopyright.'" />
		  </td>'.PHP_EOL;
	echo '</tr>'.PHP_EOL;

	echo '<tr>'.PHP_EOL;
	echo '<td style="padding: 5px; width: 100px; font-weight: bold;">Alternativtext</td>'.PHP_EOL;
	echo '<td style="padding: 5px;">REX_VALUE[5]'.$bildCopyright.'</td>'.PHP_EOL;
	echo '</tr>'.PHP_EOL;

	$bildunterschrift = '';
	  if(REX_IS_VALUE[6])
		  {
 			$bildunterschrift = htmlspecialchars_decode('REX_VALUE[6]',ENT_QUOTES);
			$bildunterschrift = str_replace('<br />','',$bildunterschrift);
			$bildunterschrift = rex_a79_textile($bildunterschrift);

			echo '<tr>'.PHP_EOL;
			echo '<td style="padding: 5px; width: 100px; font-weight: bold;">Bildunterschrift</td>'.PHP_EOL;
			echo '<td style="padding: 5px;">'.$bildunterschrift.'</td>'.PHP_EOL;
			echo '</tr>'.PHP_EOL;
		   } 

	$bildgroesse = "";
    if ("REX_VALUE[8]" == "noresize") 	$bildgroesse = "keine Anpassung";
    if ("REX_VALUE[8]" == "full") 		$bildgroesse = "ganze Breite";
    if ("REX_VALUE[8]" == "half") 		$bildgroesse = "halbe Breite";
    if ("REX_VALUE[8]" == "quarter")	$bildgroesse = "viertel Breite";

	echo '<tr>'.PHP_EOL;
	echo '<td style="padding: 5px; width: 100px; font-weight: bold;">Grösse</td>'.PHP_EOL;
	echo '<td style="padding: 5px;">'.$bildgroesse.'</td>'.PHP_EOL;
	echo '</tr>'.PHP_EOL;

	echo '<tr>'.PHP_EOL;
	echo '<td style="padding: 5px; width: 100px; font-weight: bold;">Ausrichtung</td>'.PHP_EOL;
	echo '<td style="padding: 5px;">'.$ausrichtung.'</td>'.PHP_EOL;
	echo '</tr>'.PHP_EOL;

	$bildrahmen = "";
     if(REX_IS_VALUE[9]) {
     	$bildrahmen = "ja";
		echo '<tr>'.PHP_EOL;
		echo '<td style="padding: 5px; width: 100px; font-weight: bold;">Rahmen</td>'.PHP_EOL;
		echo '<td style="padding: 5px;">'.$bildrahmen.'</td>'.PHP_EOL;
		echo '</tr>'.PHP_EOL;
     }


	}

    // Link
    if (REX_IS_VALUE[10] OR REX_IS_VALUE[11] OR "REX_LINK_ID[1]" != 0 ) {

		echo '<tr>'.PHP_EOL;		
		echo '<th colspan="2"><br/><hr/><br/></th>'.PHP_EOL;
		echo '</tr>'.PHP_EOL;

	    if(REX_IS_VALUE[11]) {

	    	$externerlink = "REX_VALUE[11]";
	    	if($externerlink != str_replace("http://", "",$externerlink)) {
				$externerlink = "REX_VALUE[11]";
			} else {
				$externerlink = "http://REX_VALUE[11]";
			}

			echo '<tr>'.PHP_EOL;
			echo '<td style="padding: 5px; width: 100px; font-weight: bold;">externe URL</td>'.PHP_EOL;
			echo '<td style="padding: 5px;">'.$externerlink.'</td>'.PHP_EOL;
			echo '</tr>'.PHP_EOL;
		}	

	    if ("REX_LINK_ID[1]" != 0) {

			echo '<tr>'.PHP_EOL;
			echo '<td style="padding: 5px; width: 100px; font-weight: bold;">interner Link</td>'.PHP_EOL;

			$article=OOArticle::getArticleById(REX_LINK_ID[1]);
			$name=$article->getName(); 

			echo '<td style="padding: 5px;"><a href="index.php?page=content&article_id=REX_LINK_ID[1]&mode=edit">'.$name.'</a></td>'.PHP_EOL;
			echo '</tr>'.PHP_EOL;
		}	

		$verlinkungsart = "";
    	if ("REX_VALUE[13]" == "nurbildlink") 				$verlinkungsart = "nur das Bild ist verlinkt";
    	if ("REX_VALUE[13]" == "ueberschriftlink") 			$verlinkungsart = "nur die Überschrift ist verlinkt";
    	if ("REX_VALUE[13]" == "ueberschriftundbildlink") 	$verlinkungsart = "Überschrift und Bild sind verlinkt";

		echo '<tr>'.PHP_EOL;
		echo '<td style="padding: 5px; width: 100px; font-weight: bold;">Verlinkungsart</td>'.PHP_EOL;
		echo '<td style="padding: 5px;">'.$verlinkungsart.'</td>'.PHP_EOL;
		echo '</tr>'.PHP_EOL;

	    if(REX_IS_VALUE[10]) {
			echo '<tr>'.PHP_EOL;
			echo '<td style="padding: 5px; width: 100px; font-weight: bold;">Linkbezeichnung</td>'.PHP_EOL;
			echo '<td style="padding: 5px;">REX_VALUE[10]</td>'.PHP_EOL;
			echo '</tr>'.PHP_EOL;
		}	

    }

	echo '</table>'.PHP_EOL;

}

?>