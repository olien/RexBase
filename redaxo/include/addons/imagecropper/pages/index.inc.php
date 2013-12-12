<?php
/*
	Redaxo-Addon ImgaeCropper - Backend-Bildbearbeitung
	Index
	v1.2
	Original by www.jungwirth-media.at
	Rewritten for Rex 4.2x and higher by Falko Müller (www.falkomueller.com)
	package: redaxo4
*/

//Variablen deklarieren
$mypage = "imagecropper";

$page = rex_request('page', 'string');
$subpage = rex_request('subpage', 'string');
$func = rex_request('func', 'string');
$isTinymce = rex_request('tinymce', 'string');
	$tinymceOpener = rex_request('opener_input_field', 'string');
	
$php4filters = array("mirrorx", "mirrory", "grayscale", "negative", "pixelate", "scatter", "sharpening");

if (!function_exists(a135_getTinyLink)):
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
endif;

if ($REX['USER']->hasPerm($mypage.'[]') || $REX["USER"]->isAdmin()):
															
	if ($subpage == "mediapool"):
		header("location: index.php?page=".$subpage.a135_getTinyLink());
	elseif ($subpage == "cropimg"):
		//Ausgabe der Cropimg-Inhalte, da Aufruf aus MP
		//Bildbearbeitung abfangen
		if (isset($_POST['submit']) && $func == "save"):
			//weitere Variablen einlesen
			$icrop_filepath = rex_request('icrop_filepath', 'string');
			$icrop_filename = rex_request('icrop_filename', 'string');
			$icrop_x1 = rex_request('icrop_x1', 'int');
			$icrop_x2 = rex_request('icrop_x2', 'int');
			$icrop_y1 = rex_request('icrop_y1', 'int');
			$icrop_y2 = rex_request('icrop_y2', 'int');
			$icrop_width = rex_request('icrop_width', 'int');
			$icrop_height = rex_request('icrop_height', 'int');
			$icrop_imgname = rex_request('icrop_imgname', 'string');
			$icrop_imgtype = rex_request('icrop_imgtype', 'string');
			$icrop_imgqual = rex_request('icrop_imgqual', 'int');
			$icrop_turn = rex_request('icrop_turn', 'int');
			$icrop_resize = rex_request('icrop_resize', 'string');
			$icrop_resizepx = rex_request('icrop_resizepx', 'int');
			$icrop_filter = rex_request('icrop_filter', 'string');
			$icrop_filterphp4 = rex_request('icrop_filterphp4', 'string');
			$icrop_filterlevel = rex_request('icrop_filterlevel', 'int');
			$icrop_oldcat = rex_request('icrop_oldcat', 'int');		
			
			$icrop_error = "";
			$icrop_success = "";
			
			//Rexlayout :: Start
			$REX["PAGE_NO_NAVI"] = 1;	//Navigation von Rex nicht ausgeben		
			include $REX['INCLUDE_PATH'].'/layout/top.php';		
			//Navigation vorbereiten um diese später ändern zu können
			$subpages = array(
				array('icrop_actionend', $I18N->Msg('a135_subnavi_back'))
			);		
			//Seitentitel ausgeben
			rex_title($I18N->Msg('a135_title_cropimg'), $subpages);
	
				if (!empty($icrop_filepath) && !empty($icrop_filename)):
					//Bildbearbeitung ausführen und Success/Error-Seite anzeigen
					require_once $REX['INCLUDE_PATH']."/addons/".$mypage."/classes/croppie.class.php";
					require_once $REX['INCLUDE_PATH']."/addons/".$mypage."/functions/makeimg.inc.php";
					
					if ($makeimgsuccess):
						$icrop_success = $I18N->msg('a135_makeimg_success');
						$icrop_success .= "<br />".$I18N->msg('a135_newfile').": ".$newfilename;
					else:
						$icrop_error = $I18N->msg('a135_makeimg_error1');
					endif;
				else:
					$icrop_error = $I18N->msg('a135_makeimg_error2');
				endif;
			
				//Erfolg oder Fehler abfangen und auf entsprechende Seite weiterleiten
				if (!empty($icrop_error)):
					echo rex_warning($icrop_error);
				elseif (!empty($icrop_success)):
					echo rex_info($icrop_success);
				endif;
				?>
		
					<div class="rex-addon-output">
						<h2 class="rex-hl2"><?php echo $I18N->msg('a135_head'); ?></h2>
						
						<div class="rex-addon-content">
							<?php
							if (!empty($makemsg)):
							?>
								<p class="rex-form-text"><strong><?php echo $I18N->msg('a135_info'); ?>:</strong><br /><?php echo $makemsg; ?></p>
							<?php
							endif;
							?>
							
							<?php
							if (!empty($icrop_success)):
								$thumbsize = 200;
								
								if ($newimgsize[0] > 0 && $newimgsize[0] > $thumbsize):
									$thumbwidth = 'width="200"';
								else:
									$thumbwidth = '';
								endif;
								?>
									<p class="rex-form-text"><strong><?php echo $I18N->msg('a135_preview'); ?>:</strong><br /><img src="../files/<?php echo $newfilename; ?>" <?php echo $thumbwidth; ?> alt="<?php echo $newfilename; ?>" title="<?php echo $newfilename; ?>" border="1" /></p>
								<?php
							endif;
							?>
							
						</div>	
					</div>
					
					<!-- PLEASE DO NOT REMOVE THIS COPYRIGHT -->
					<p>&nbsp;</p>
					<p><?php echo $REX['ADDON']['author'][$mypage]; ?></p>
					<!-- THANK YOU! -->
		
				<?php
	
			//Rexlayout :: Ende
			include $REX['INCLUDE_PATH'].'/layout/bottom.php';
			
		else:
			//Cropimg-Seite anzeigen	
			//Rexlayout :: Start
			$REX["PAGE_NO_NAVI"] = 1;	//Navigation von Rex nicht ausgeben
			include $REX['INCLUDE_PATH'].'/layout/top.php';
			//Navigation vorbereiten um diese später ändern zu können
			$subpages = array(
				array('icrop_backlink', $I18N->Msg('a135_subnavi_back'))
			);
			//Seitentitel ausgeben
			rex_title($I18N->Msg('a135_title_cropimg'), $subpages);
			
				//Seite einbinden
				require_once $REX['INCLUDE_PATH']."/addons/".$mypage."/pages/cropimg.inc.php";
			
			//Rexlayout :: Ende
			include $REX['INCLUDE_PATH'].'/layout/bottom.php';
		endif;

	elseif ($subpage == "help"):
		//Ausgabe der Hilfeseite
		
		//Rexlayout :: Start
		include $REX['INCLUDE_PATH'].'/layout/top.php';
		//Seitentitel ausgeben
		rex_title($I18N->Msg('a135_title'), $REX['ADDON'][$mypage]['SUBPAGES']);

			//Seite einbinden
			require_once $REX['INCLUDE_PATH']."/addons/".$mypage."/pages/help.inc.php";

		//Rexlayout :: Ende
		include $REX['INCLUDE_PATH'].'/layout/bottom.php';

	else:
		//Ausgabe der Standard-Addon-Seiten
		
		//Rexlayout :: Start
		include $REX['INCLUDE_PATH'].'/layout/top.php';
		//Seitentitel ausgeben
		rex_title($I18N->Msg('a135_title'), $REX['ADDON'][$mypage]['SUBPAGES']);
	
	
		//Konfiguration nur dem Admin anzeigen
		if ($REX['USER']->isAdmin()):
		
			//Formular dieser Seite verarbeiten
			if ($func == "save" && isset($_POST['submit'])):
				//Konfig speichern
				$db = new sql();
				$db->setQuery("TRUNCATE TABLE ".$REX['TABLE_PREFIX']."135_imagecropper_config");
			
				$db = new sql();
				$db->setTable($REX['TABLE_PREFIX']."135_imagecropper_config");
			
				$db->setValue("preset1", rex_request('icrop_preset1', 'string'));
				$db->setValue("preset2", rex_request('icrop_preset2', 'string'));
				$db->setValue("preset3", rex_request('icrop_preset3', 'string'));
				$db->setValue("preset4", rex_request('icrop_preset4', 'string'));
				$db->setValue("preset5", rex_request('icrop_preset5', 'string'));
				
				if($db->insert()):
					echo rex_info($I18N->msg('a135_settings_saved'));
				else:
					echo rex_warning($I18N->msg('a135_error'));
				endif;
			endif;
		
			//Unterseite einbinden
			/*
			switch($subpage):
				case "menu2":
								break;
				
				case "menu3":
								break;
			
				case "menu4":
								break;
			
				default:
								break;
			endswitch;
			*/
			
			//Std.vorgaben der Felder auslesen
			$db = new sql();
			$db->setQuery("SELECT * FROM ".$REX['TABLE_PREFIX']."135_imagecropper_config limit 0,1"); 
			$imagecropCfg = $db->get_array();	//mehrdimensionales Array kommt raus
			?>
			
			<div class="rex-addon-output">
				<div class="rex-form">
					<h2 class="rex-hl2"><?php echo $I18N->msg('a135_head'); ?></h2>
					
					<form action="index.php" method="post">
						<fieldset class="rex-form-col-1">
						
							<div class="rex-form-wrapper">
								<input type="hidden" name="page" value="<?php echo $page; ?>" />
								<input type="hidden" name="subpage" value="<?php echo $subpage; ?>" />
								<input type="hidden" name="func" value="save" />
								
			
								<div class="rex-form-row">
									<h5 class="rex-form-headline"><?php echo $I18N->msg('a135_subheader_cfg'); ?></h5>
								</div>
								
									<div class="rex-form-row">
										<p class="rex-form-col-a rex-form-text">
											<label for="icrop_preset1"><?php echo $I18N->msg('a135_cfg_preset1'); ?></label>
											<input class="rex-form-text" name="icrop_preset1" id="icrop_preset1" type="text" value="<?php echo $imagecropCfg[0]['preset1']; ?>" size="20" maxlength="9" /> &nbsp; <?php echo $I18N->msg('a135_cfg_format'); ?>: 100x200
										</p>
									</div>
									<div class="rex-form-row">
										<p class="rex-form-col-a rex-form-text">
											<label for="icrop_preset2"><?php echo $I18N->msg('a135_cfg_preset2'); ?></label>
											<input class="rex-form-text" name="icrop_preset2" id="icrop_preset2" type="text" value="<?php echo $imagecropCfg[0]['preset2']; ?>" size="20" maxlength="9" /> &nbsp; <?php echo $I18N->msg('a135_cfg_format'); ?>: 100x200
										</p>
									</div>
									<div class="rex-form-row">
										<p class="rex-form-col-a rex-form-text">
											<label for="icrop_preset3"><?php echo $I18N->msg('a135_cfg_preset3'); ?></label>
											<input class="rex-form-text" name="icrop_preset3" id="icrop_preset3" type="text" value="<?php echo $imagecropCfg[0]['preset3']; ?>" size="20" maxlength="9" /> &nbsp; <?php echo $I18N->msg('a135_cfg_format'); ?>: 100x200
										</p>
									</div>
									<div class="rex-form-row">
										<p class="rex-form-col-a rex-form-text">
											<label for="icrop_preset4"><?php echo $I18N->msg('a135_cfg_preset4'); ?></label>
											<input class="rex-form-text" name="icrop_preset4" id="icrop_preset4" type="text" value="<?php echo $imagecropCfg[0]['preset4']; ?>" size="20" maxlength="9" /> &nbsp; <?php echo $I18N->msg('a135_cfg_format'); ?>: 100x200
										</p>
									</div>
									<div class="rex-form-row">
										<p class="rex-form-col-a rex-form-text">
											<label for="icrop_preset5"><?php echo $I18N->msg('a135_cfg_preset5'); ?></label>
											<input class="rex-form-text" name="icrop_preset5" id="icrop_preset5" type="text" value="<?php echo $imagecropCfg[0]['preset5']; ?>" size="20" maxlength="9" /> &nbsp; <?php echo $I18N->msg('a135_cfg_format'); ?>: 100x200
										</p>
									</div>
								
								<div class="rex-form-row">
									<p class="rex-form-submit">
										<br />
										<input type="submit" class="rex-form-submit" name="submit" value="<?php echo $I18N->msg('a135_save'); ?>" />
									</p>
								</div>
							</div>
							
						</fieldset>
					</form>
					
				</div>
			</div>
            
           	<?php
		else:
			?>

			<div class="rex-addon-output">
                <h2 class="rex-hl2"><?php echo $I18N->msg('a135_head'); ?></h2>
                
                <div class="rex-form-wrapper">
                    <div class="rex-addon-content">
                        <p class="rex-form-text"><?php echo $I18N->msg('a135_norights'); ?></p>
                    </div>						
                
                </div>
            </div>
            
            <?php	
		endif;
		?>
		
		<!-- PLEASE DO NOT REMOVE THIS COPYRIGHT -->
		<p>&nbsp;</p>
		<p><?php echo $REX['ADDON']['author'][$mypage]; ?></p>
		<!-- THANK YOU! -->
		
		<?php
		//Rexlayout :: Ende
		include $REX['INCLUDE_PATH'].'/layout/bottom.php';
	endif;
	
endif;
?>
