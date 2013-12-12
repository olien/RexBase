<?php
/*
	Redaxo-Addon ImgaeCropper - Backend-Bildbearbeitung
	cropimg.inc.php
	v1.2
	Original by www.jungwirth-media.at
	Rewritten for Rex 4.2x and higher by Falko Müller (www.falkomueller.com)
	package: redaxo4
*/

//Variablen deklarieren
$mypage = "imagecropper";
$bild = rex_request('bild', 'string');

	//Std.vorgaben der Presets auslesen
	$db = new sql();
	$db->setQuery("SELECT * FROM ".$REX['TABLE_PREFIX']."135_imagecropper_config limit 0,1"); 
	$imagecropCfg = $db->get_array();	//mehrdimensionales Array kommt raus
		//Eingaben prüfen
		unset($presets);
		//if (!empty($imagecropCfg[0]['preset1']) && eregi("[0-9]x[0-9]", $imagecropCfg[0]['preset1'])):
		if (!empty($imagecropCfg[0]['preset1']) && preg_match("/[0-9]x[0-9]/i", $imagecropCfg[0]['preset1'])):
			$preset = explode("x", $imagecropCfg[0]['preset1']);
			$presets[0]['w'] = $preset[0]; $presets[0]['h'] = $preset[1];
		endif;
		//if (!empty($imagecropCfg[0]['preset2']) && eregi("[0-9]x[0-9]", $imagecropCfg[0]['preset2'])):
		if (!empty($imagecropCfg[0]['preset2']) && preg_match("/[0-9]x[0-9]/i", $imagecropCfg[0]['preset2'])):
			$preset = explode("x", $imagecropCfg[0]['preset2']);
			$presets[1]['w'] = $preset[0]; $presets[1]['h'] = $preset[1];
		endif;
		//if (!empty($imagecropCfg[0]['preset3']) && eregi("[0-9]x[0-9]", $imagecropCfg[0]['preset3'])):
		if (!empty($imagecropCfg[0]['preset3']) && preg_match("/[0-9]x[0-9]/i", $imagecropCfg[0]['preset3'])):
			$preset = explode("x", $imagecropCfg[0]['preset3']);
			$presets[2]['w'] = $preset[0]; $presets[2]['h'] = $preset[1];
		endif;
		//if (!empty($imagecropCfg[0]['preset4']) && eregi("[0-9]x[0-9]", $imagecropCfg[0]['preset4'])):
		if (!empty($imagecropCfg[0]['preset4']) && preg_match("/[0-9]x[0-9]/i", $imagecropCfg[0]['preset4'])):
			$preset = explode("x", $imagecropCfg[0]['preset4']);
			$presets[3]['w'] = $preset[0]; $presets[3]['h'] = $preset[1];
		endif;
		//if (!empty($imagecropCfg[0]['preset5']) && eregi("[0-9]x[0-9]", $imagecropCfg[0]['preset5'])):
		if (!empty($imagecropCfg[0]['preset5']) && preg_match("/[0-9]x[0-9]/i", $imagecropCfg[0]['preset5'])):
			$preset = explode("x", $imagecropCfg[0]['preset5']);
			$presets[4]['w'] = $preset[0]; $presets[4]['h'] = $preset[1];
		endif;
		

//Cropper starten und Bild zur Bearbeitung einladen
if (!empty($bild) && $subpage == "cropimg"):

	//prüfen ob Bilddatei zulässig ist
	if (preg_match("/(gif|jpg|jpeg|png)$/i", $bild)):
		$filepath = "../files/".$bild;
			if (is_file($filepath)):
				$imgsize = @getimagesize($filepath);
				
				//neuen Bildnamen vordefinieren (prefix)
				$tmpBildname = preg_replace("/^(.*)\.[a-zA-Z]{3,4}$/i", "$1", $bild)."_";
				
				//Kategorie holen
				$file = OOMedia::getMediaByFileName($bild);
			        $filecat = $file->getCategory();
					$filecatid = $file->getCategoryId();
					$filecatname = $file->getCategoryName();
						if (empty($filecatname)):
							$filecatname = $I18N->msg('a135_param_nocat');
						endif;
					$filesizeX = $file->getWidth();
						if (empty($imgsize[0])):
							$imgsize[0] = $filesizeX;
						endif;
					$filesizeY = $file->getHeight();
						if (empty($imgsize[1])):
							$imgsize[1] = $filesizeY;
						endif;
			else:
				echo rex_warning($I18N->msg('a135_error_filenotfound')." (".$bild.")");
				die();
			endif;
	else:
		//Fehler ausgeben, da Datei kein Bild ist
		echo rex_warning($I18N->msg('a135_error_noimg'));
		die();
	endif;
endif;
?>

	<div class="rex-addon-output">
        <h2 class="rex-hl2"><?php echo $I18N->msg('a135_head'); ?></h2>
            
		<div class="rex-form">
			
			<form action="index.php" method="post" enctype="multipart/form-data" onsubmit="return icrop_checkform();">
				<fieldset class="rex-form-col-1">
                
                	<script language="javascript">
					var icrop_Submit = "<?php echo str_replace('"', "'", $I18N->msg('a135_submit')); ?>";
					</script>
				
					<div class="rex-form-wrapper">
						<input type="hidden" name="page" value="<?php echo $page; ?>" />
						<input type="hidden" name="subpage" value="<?php echo $subpage; ?>" />
						<input type="hidden" name="func" value="save" />
                        <input type="hidden" name="icrop_filepath" value="<?php echo $filepath; ?>" />
     					<input type="hidden" name="icrop_filename" value="<?php echo $bild; ?>" />
                        <input type="hidden" name="tinymce" value="<?php echo $isTinymce; ?>" />
                        <input type="hidden" name="opener_input_field" value="<?php echo $tinymceOpener; ?>" />
                        
                        <input type="hidden" name="icrop_x1" value="0" id="icrop_x1" />
                        <input type="hidden" name="icrop_x2" value="0" id="icrop_x2" />
                        <input type="hidden" name="icrop_y1" value="0" id="icrop_y1" />
                        <input type="hidden" name="icrop_y2" value="0" id="icrop_y2" />
                        <input type="hidden" name="icrop_width" value="" id="icrop_width" />
                        <input type="hidden" name="icrop_height" value="" id="icrop_height" />

						<div class="rex-addon-content">
							<p class="rex-form-text"><?php echo $I18N->msg('a135_cropimg_text1'); ?></p>
							<p class="rex-form-text"><?php echo $I18N->msg('a135_selimg'); ?>: <?php echo $bild; ?> (<?php echo $I18N->msg('a135_bh'); ?>: <?php echo $imgsize[0]; ?>px/<?php echo $imgsize[1]; ?>px)</p>
						</div>						
	
                            <table summary="imagecropper <?php echo $I18N->msg('a135_ratio'); ?>" class="rex-table">
                                <caption><?php echo $I18N->msg('a135_ratio'); ?></caption>
                                <colgroup>
                                    <col width="40%" />
                                    <col width="60%" />
                                </colgroup>
                                
                                <tbody>
                                    <tr>
                                        <td><h5 class="rex-form-headline"><?php echo $I18N->msg('a135_subheader_thumb'); ?></h5></td>
                                        <td align="right"><input type="checkbox" id="icrop_seitenverh" value="1" name="icrop_seitenverh" checked="checked" /> <label for="icrop_seitenverh"><?php echo $I18N->msg('a135_thumb_ratio'); ?></label></td>
                                    </tr>
                                </tbody>
                            </table>    
                        
							<div class="rex-form-row">
								<div id="cropContainer">
									<img src="<?php echo $filepath; ?>" alt="<?php echo $I18N->msg('a135_thumb_imagecrop'); ?>" title="<?php echo $I18N->msg('a135_thumb_imagecrop'); ?>" id="cropImage" width="<?php echo $imgsize[0]; ?>" height="<?php echo $imgsize[1]; ?>" />
								</div>
                            </div>
							<div class="rex-addon-content">
                            	<div id="icropselection">&nbsp;</div>
								<p class="rex-form-col-a rex-form-checkbox rex-form-label-right" align="right" style="float: right;"><?php echo $I18N->msg('a135_thumb_presets'); ?>: &nbsp; 
                                	<?php
									//Presetsbuttons erstellen
									for ($i=0; $i<5; $i++):
										if (!empty($presets[$i]['w'])):
											?>
                                            	<button type="button" id="icrop_preset<?php echo ($i+1); ?>" value="<?php echo $presets[$i]['w'].'x'.$presets[$i]['h']; ?>" class="icrop_presets"><?php echo $presets[$i]['w'].' x '.$presets[$i]['h']; ?></button>                                            
                                            <?php
										endif;									
									endfor;									
									?>
                                    <button type="button" id="icrop_release" value="" class="icrop_presets"><?php echo $I18N->msg('a135_thumb_release'); ?></button>
								</p>
                            </div>
						
						<div class="rex-form-row">
							<h5 class="rex-form-headline"><?php echo $I18N->msg('a135_subheader_params'); ?></h5>
						</div>
                        
                            <table summary="imagecropper <?php echo $I18N->msg('a135_imgparams'); ?>" class="rex-table">
                                <caption><?php echo $I18N->msg('a135_imgparams'); ?></caption>
                                <colgroup>
                                    <col width="20" />
                                    <col width="*" />
                                    <col width="150" />
                                    <col width="150" />
                                </colgroup>
                                
                                <tbody>
                                    <tr>
                                        <td colspan="4"><?php echo $I18N->msg('a135_param_headerfile'); ?>:</td>
                                    </tr>
                                    <tr>
                                    	<td>&nbsp;</td>
                                        <td><?php echo $I18N->msg('a135_param_filename'); ?><br />
											<input name="icrop_imgname" id="icrop_imgname" type="text" value="<?php echo $tmpBildname.date("Ymd-His"); ?>" size="30" maxlength="250" class="rex-form-text icrop_select1" />
                                        </td>
                                        <td><?php echo $I18N->msg('a135_param_filetype'); ?><br />
											<select name="icrop_imgtype" size="1" class="rex-form-select icrop_select2">
                                                <option value="jpg" selected="selected">JPG / JPEG</option>
                                                <option value="png">PNG</option>
                                                <option value="gif">GIF</option>
                                            </select>
                                        </td>
                                        <td><?php echo $I18N->msg('a135_param_filequal'); ?><br />
											<select name="icrop_imgqual" size="1" class="rex-form-select icrop_select2">
                                            	<option value="100">100%</option>
                                                <option value="90">90%</option>
                                                <option value="80">80%</option>
                                                <option value="75" selected="selected">75%</option>
                                                <option value="70">70%</option>
                                                <option value="60">60%</option>
                                                <option value="50">50%</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4"><?php echo $I18N->msg('a135_param_headeredit'); ?>:</td>
                                    </tr>
                                    <tr>
                                    	<td>&nbsp;</td>
                                        <td>
                                        <?php
											if (function_exists("imagefilter")):
										?>
											<?php echo $I18N->msg('a135_param_turn'); ?><br />
											<select name="icrop_turn" size="1" class="rex-form-select icrop_select2">
                                            	<option value="0" selected="selected"><?php echo $I18N->msg('a135_paramopt_nochange'); ?></option>
                                                <option value="">&nbsp;</option>
                                                <option value="90"><?php echo $I18N->msg('a135_paramopt_90uzs'); ?></option>
                                                <option value="180"><?php echo $I18N->msg('a135_paramopt_180uzs'); ?></option>
                                                <option value="270"><?php echo $I18N->msg('a135_paramopt_270uzs'); ?></option>
                                            </select>
                                        <?php
										else:
											echo "&nbsp;";
										endif;
										?>
                                        </td>
                                        <td><?php echo $I18N->msg('a135_param_resize'); ?><br />
											<select name="icrop_resize" size="1" class="rex-form-select icrop_select2">
                                            	<option value="0" selected="selected"><?php echo $I18N->msg('a135_paramopt_nochange'); ?></option>
                                                <option value="">&nbsp;</option>
                                                <option value="width"><?php echo $I18N->msg('a135_paramopt_width'); ?></option>
                                                <option value="height"><?php echo $I18N->msg('a135_paramopt_height'); ?></option>
                                            </select>
                                        </td>
                                        <td><?php echo $I18N->msg('a135_param_respixel'); ?><br />
											<input name="icrop_resizepx" type="text" value="" size="" maxlength="6" class="rex-form-text icrop_select2" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td><?php echo $I18N->msg('a135_param_filter'); ?><br />
                                    
										<?php
                                        if (function_exists("imagefilter")):
											?>
                                                <select name="icrop_filter" size="1" class="rex-form-select icrop_select1">
                                                    <option value="0" selected="selected"><?php echo $I18N->msg('a135_paramopt_nochange'); ?></option>
                                                    <option value="">&nbsp;</option>
                                                    <option value="mirrorx"><?php echo $I18N->msg('a135_paramopt_f_mirrorx'); ?></option>
                                                    <option value="mirrory"><?php echo $I18N->msg('a135_paramopt_f_mirrory'); ?></option>
                                                    <option value="sharpening"><?php echo $I18N->msg('a135_paramopt_f_sharpening'); ?></option>
                                                    <option value="IMG_FILTER_NEGATE"><?php echo $I18N->msg('a135_paramopt_f_negate'); ?></option>
                                                    <option value="IMG_FILTER_GRAYSCALE"><?php echo $I18N->msg('a135_paramopt_f_grayscale'); ?></option>
                                                    <option value="IMG_FILTER_EDGEDETECT"><?php echo $I18N->msg('a135_paramopt_f_edgedetect'); ?></option>
                                                    <option value="IMG_FILTER_EMBOSS"><?php echo $I18N->msg('a135_paramopt_f_emboss'); ?></option>
                                                    <option value="IMG_FILTER_GAUSSIAN_BLUR"><?php echo $I18N->msg('a135_paramopt_f_gauss'); ?></option>
                                                    <option value="IMG_FILTER_SELECTIVE_BLUR"><?php echo $I18N->msg('a135_paramopt_f_blur'); ?></option>
                                                    <option value="IMG_FILTER_MEAN_REMOVAL"><?php echo $I18N->msg('a135_paramopt_f_removal'); ?></option>
                                                    <option value="pixelate"><?php echo $I18N->msg('a135_paramopt_f_pixelate'); ?></option>
                                                    <option value="scatter"><?php echo $I18N->msg('a135_paramopt_f_scatter'); ?></option>
                                                </select>
											<?php
										else:
											?>
                                                <select name="icrop_filterphp4" size="1" class="rex-form-select icrop_select1">
                                                    <option value="0" selected="selected"><?php echo $I18N->msg('a135_paramopt_nochange'); ?></option>
                                                    <option value="">&nbsp;</option>
                                                    <option value="mirrorx"><?php echo $I18N->msg('a135_paramopt_f_mirrorx'); ?></option>
                                                    <option value="mirrory"><?php echo $I18N->msg('a135_paramopt_f_mirrory'); ?></option>
                                                    <option value="sharpening"><?php echo $I18N->msg('a135_paramopt_f_sharpening'); ?></option>
                                                    <option value="negative"><?php echo $I18N->msg('a135_paramopt_f_negate'); ?></option>
                                                    <option value="grayscale"><?php echo $I18N->msg('a135_paramopt_f_grayscale'); ?></option>
                                                    <option value="pixelate"><?php echo $I18N->msg('a135_paramopt_f_pixelate'); ?></option>
                                                    <option value="scatter"><?php echo $I18N->msg('a135_paramopt_f_scatter'); ?></option>
                                                </select>
											<?php
                                        endif;
                                        ?>

                                        </td>
                                        <td colspan="2"><?php echo $I18N->msg('a135_param_filterlevel'); ?><br />
                                            <select name="icrop_filterlevel" size="1" class="rex-form-select icrop_select2">
                                                <option value="1" selected="selected">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10">10</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4"><?php echo $I18N->msg('a135_param_headersave'); ?>:</td>
                                    </tr>
                                    <tr>
                                    	<td>&nbsp;</td>
                                        <td colspan="3"><input class="rex-form-checkbox" name="icrop_oldcat" type="checkbox" id="icrop_oldcat" value="<?php echo $filecatid; ?>" checked="checked" /> <label for="icrop_oldcat"><?php echo $I18N->msg('a135_param_savedir')." (".$I18N->msg('a135_param_savedir_cat').": ".$filecatname.")"; ?></label></td>
                                    </tr>
                       			</tbody>
                                </table>
						
						<div class="rex-form-row">
							<p class="rex-form-submit">
								<br />
								<input type="submit" class="rex-form-submit icrop_submit" name="submit" value="<?php echo $I18N->msg('a135_save'); ?>" />
							</p>
						</div>
                        
						<div class="rex-addon-content">
                        	<p>&nbsp;</p>
							<p class="icrop_information"><?php echo $I18N->msg('a135_cropimg_text2'); ?></p>
						</div>						
                        
					</div>
					
				</fieldset>
			</form>
			
		</div>
        
	</div>
    
    <!-- PLEASE DO NOT REMOVE THIS COPYRIGHT -->
    <p>&nbsp;</p>
    <p><?php echo $REX['ADDON']['author'][$mypage]; ?></p>
    <!-- THANK YOU! -->


<!--
    <fieldset style="border: #CCC 1px solid; padding: 5px; margin: 10px;"><legend>Optionale Medienbeschreibungen * (<a href="javascript:;" onclick="document.getElementById('jmcropMedDesc').style.display = 'block';">anzeigen</a>)</legend>
    <div id="jmcropMedDesc" style="display: none;">
        <div style="float:left; width: 300px; padding-top:15px; padding-left:10px;">
            <label for="bildtitel">Bildtitel:</label><br  />
            <input type="text" name="bildtitel" id="bildtitel" style="margin-top: 2px; width: 100%;" />
            <br />
            <br />
            <br />
            <br />
            * Diese beiden Angaben dienen der Hinter-<br />
            &nbsp;&nbsp;&nbsp;legung von beschreibenden Medien-<br />
            &nbsp;&nbsp;&nbsp;informationen, welche z.B. bei Downloads<br />
            &nbsp;&nbsp;&nbsp;Anwendung finden können.
        </div>    
        <div style="float:left; width: 30px;">&nbsp;</div>
        <div style="float:left; width: 270px; padding-top:15px;">
            <label for="beschreibung">Bildbeschreibung:</label><br  />
            <textarea name="beschreibung" id="beschreibung" rows="7" cols="50" style="margin-top: 2px; width: 100%;"></textarea>
        </div>
    </div>
    </fieldset>

    <div style="float:left; width: 120px; padding-top:15px; padding-left:10px; padding-bottom:10px;  ">
        <input type="submit" value="Neue Datei speichern" id="speichern" onclick="checkcropform(); return false;" />
    </div>
-->