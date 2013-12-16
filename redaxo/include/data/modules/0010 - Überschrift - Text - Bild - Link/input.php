<h1>Todo</h1>

<ul>
<li>Lightbox einbauen (http://dimsemenov.com/plugins/magnific-popup/)</li>
<li>Youtube unterstützung einbauen? Mit Vorschaubild?</li>
<li>Hilfe einbauen?</li>
</ul>


<div id="tabs">
	<ul>
		<li><a href="#text">Überschrift &amp; Text</a></li>
		<li><a href="#bild">Bild</a></li>
		<li><a href="#link">Link</a></li>
		<li style="float:right;"><a href="#weiteres">Weitere Einstellungen</a></li>		
	</ul>


<?php


// Rex Values
//  1  : Überschrift
//  2  : Überschrift-Tag
//  3  : Inhaltstext
// 	1  : Bild Datei -> REX_FILE[1]
// 	5  : Alt Attribu 
// 	6  : Bildunterschrift
// 	7  : Bild Ausrichtung
// 	8  : Bild Größe
// 	9  : Bild Rahmen
// 10  : Link Bezeichnung
// 11  : externe URL
//  1  : interne URL -> REX_LINK_ID[1]
// 13  : Verlinkungsart

// 18,19,20 -> Online Einstellungen

if (!isset($REX['base']['textmodulcount'])){ 
	$REX['base']['textmodulcount'] = 0;
}
$REX['base']['textmodulcount'] = $REX['base']['textmodulcount'] + 1;



$objForm = new mform();

// TEXT

$objForm->addHtml('<div id="text">');

$objForm->addHeadline('Überschrift');


// Mit Hilfe
/*
$objForm->addHeadline('Überschrift 
<div id="info_ueberschrift" class="ui-state-default ui-corner-all" title=".ui-icon-help" style="width: 17px; float: left; margin: -3px 10px 0 0; ">
    <span class="ui-icon ui-icon-help"></span>
</div>
	');

$objForm->addHtml('
<div class="dialog" id="dialog_ueberschrift" title="Überschriften" style="display:none;">
		<p>Lorem ipsum</p>
</div>
');
*/

$objForm->addTextAreaField(1,array('label'=>'Text','style'=>'width:500px'));

// Tag für Überschrift
$tag = 'REX_VALUE[2]';
if ($tag == '' and $REX['base']['textmodulcount'] == 1) $tag = 'h1';
if ($tag == '') $tag = 'h2';

$objForm->addSelectField(2,array('h1'=>'H1','h2'=>'H2','h3'=>'H3','h4'=>'H4','h5'=>'H5','h6'=>'H6'),array('label'=>'Grösse'),'',$tag);

$objForm->addHtml('<br/>');
$objForm->addHeadline('Text');
$objForm->addTextAreaField(3,array('label'=>'Text eingeben','class'=>"rex-markitup",'data-buttonset'=>"kreischer",'style'=>'width:500px !important;'));
$objForm->addHtml('</div>');

// BILD

$objForm->addHtml('<div id="bild">');
$objForm->addHeadline('Bild');
$objForm->addMediaField(1,array('types'=>'gif,jpg,png','preview'=>0,'category'=>0,'label'=>'Datei'));
$objForm->addTextField(5,array('label'=>'Alternativtext','style'=>'width:500px'));

$objForm->addHtml('<br/>');
$objForm->addHeadline('Weitere Eigenschaften');
$objForm->addTextAreaField(6,array('label'=>'Bildunterschrift','style'=>'width:500px'));


$objForm->addSelectField(7, array(
	'l'=>'im Fliesstext links',
	'r'=>'im Fliestext rechts',
	'tl'=>'links vom Text',
	'tr'=>'rechts vom Text',
	'tlu'=>'links von Text und Überschrift',
	'tru'=>'rechts von Text und Überschrift'
), array('label'=>'Ausrichtung'));

$objForm->addSelectField(8,array(
	'noresize'=>'keine Anpassung',
	'full'=>'ganze Breite',
	'half'=>'halbe Breite',
	'quarter'=>'viertel Breite'
	),array('label'=>'Größe'));

$objForm->addCheckboxField(9,array(1=>''),array('label'=>'Bildrahmen'));

$objForm->addHtml('</div>');

// LINK

$objForm->addHtml('<div id="link">');
$objForm->addHeadline('Link');
$objForm->addTextField(11,array('label'=>'extern','style'=>'width:500px'));
$objForm->addLinkField(1,array('label'=>'intern','category'=>0));

$objForm->addHtml('<br/>');
$objForm->addHeadline('Weitere Eigenschaften');

$objForm->addTextField(10,array('label'=>'Bezeichnung','style'=>'width:500px'));

$objForm->addSelectField(13, array(
	'nurbildlink'=>'nur das Bild verlinken',
	'ueberschriftlink'=>'nur Überschrift verlinken',
	'ueberschriftundbildlink'=>'Überschrift und Bild verlinken'
), array('label'=>'Elemente'));


$objForm->addHtml('</div>');

// Weitere Einstellungen

$objForm->addHtml('<div id="weiteres">');
$objForm->addHeadline('Online Zeitraum einstellen');
$objForm->addCheckboxField(20,array(1=>''),array('label'=>'Aktiv'));
$objForm->addTextField(19,array('label'=>'Online von','style'=>'width:100px','class'=>'datepicker1'));
$objForm->addTextField(18,array('label'=>'Online bis','style'=>'width:100px','class'=>'datepicker2'));

$objForm->addHtml('</div>');

echo $objForm->show_mform();

?>

<script type="text/javascript">
jQuery('#tabs').tabs({
	fx: { height: 'toggle', duration: 200 },
	select: function(event, ui) {
		jQuery(this).css('height', jQuery(this).height());
	},
//	show: function(event, ui) {
//		jQuery(this).css('height', '550px');
//		jQuery(this).css('overflow', 'visible');
//	}
});



	jQuery(document).ready(function($) {
	
// Hilfe Boxen
// $('.dialog').dialog({ autoOpen: false, width: 700});
// $('#info_ueberschrift').click(function(){  $('#dialog_ueberschrift').dialog('open');return false;});
// $('#info_text').click(function(){  $('#dialog_text').dialog('open');return false;});

			$('#tabs').tabs();

			$(".datepicker1").datepicker({
							inline: true,
							dateFormat: "dd.mm.yy"
							
			});

			$(".datepicker2").datepicker({
							inline: true,
				 			defaultDate: "+1w",
							dateFormat: "dd.mm.yy"
			});

	});
</script>