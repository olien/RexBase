<?php
/*
	Redaxo-Addon ImgaeCropper - Backend-Bildbearbeitung
	help.inc.php
	v1.2
	Original by www.jungwirth-media.at
	Rewritten for Rex 4.2x and higher by Falko Müller (www.falkomueller.com)
	package: redaxo4
*/

//Variablen deklarieren
$mypage = "imagecropper";

$page = rex_request('page', 'string');
$subpage = rex_request('subpage', 'string');
?>

	<div class="rex-addon-output">
		<h2 class="rex-hl2"><?php echo $I18N->msg('a135_head_help'); ?></h2>
		
		<div class="rex-form-wrapper">
			<div class="rex-addon-content">
            	<h5 class="rex-form-headline"><?php echo $I18N->msg('a135_help_textheader1'); ?>:</h5>
				<p class="rex-form-text"><?php echo $I18N->msg('a135_help_text1'); ?></p>
                
              <h5 class="rex-form-headline"><?php echo $I18N->msg('a135_help_textheader2'); ?>:</h5>
                <p class="rex-form-text"><?php echo $I18N->msg('a135_help_text2a'); ?></p>
                <p class="rex-form-text"><?php echo $I18N->msg('a135_help_text2b'); ?></p>
                
                <h5 class="rex-form-headline"><?php echo $I18N->msg('a135_help_textheader3'); ?>:</h5>
                <p class="rex-form-text"><?php echo $I18N->msg('a135_help_text3'); ?></p>
			</div>						
		</div>
	</div>
    
    <!-- PLEASE DO NOT REMOVE THIS COPYRIGHT -->
    <p>&nbsp;</p>
    <p><?php echo $REX['ADDON']['author'][$mypage]; ?></p>
    <!-- THANK YOU! -->