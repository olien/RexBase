<?php
$articleId = rex_request('article_id');
$clang = rex_request('clang');
$ctype = rex_request('ctype');

$doDataUpdate = true;
$dataUpdated = false;

if (rex_post('save_data', 'boolean')) {
	$newUrlType = rex_post('url_type', 'int');

	$newUrlData['url_type'] = $newUrlType;

	switch ($newUrlType) {
		case SEO42_URL_TYPE_DEFAULT:
			// do nothing
			break;
		case SEO42_URL_TYPE_USERDEF_INTERN:
			global $REXSEO_URLS;

			$sanitizedUrl = ltrim(rex_post('userdef_intern'), './');
			$sanitizedUrl = rexseo_parse_article_name($sanitizedUrl, $REX['ARTICLE_ID'], $REX['CUR_CLANG'], true);

			// check if url already exists
			if (isset($REXSEO_URLS[$sanitizedUrl])) { // url already exists
				$doDataUpdate = false;
				echo rex_warning($I18N->msg('seo42_urlpage_url_already_exists', seo42_utils::getCustomUrl($sanitizedUrl)));
			} else {
				$newUrlData['custom_url'] = $sanitizedUrl;
			}

			break;
		case SEO42_URL_TYPE_USERDEF_EXTERN:
			$newUrlData['custom_url'] = rex_post('userdef_extern');
			break;
		case SEO42_URL_TYPE_MEDIAPOOL:
			$newUrlData['file'] = rex_post('mediapool');
			break;
		case SEO42_URL_TYPE_INTERN_REPLACE:
			$newUrlData['article_id'] = rex_post('intern_replace_article_id', 'int');
			break;
		case SEO42_URL_TYPE_INTERN_REPLACE_CLANG:
			$newUrlData['article_id'] = rex_post('intern_replace_clang_article_id', 'int');
			$newUrlData['clang_id'] = rex_post('intern_replace_clang_clang_id', 'int');
			break;
		case SEO42_URL_TYPE_REMOVE_ROOT_CAT:
			global $REXSEO_URLS;

			$newUrl = seo42_utils::removeRootCatFromUrl(rex_getUrl($REX['ARTICLE_ID'], $REX['CUR_CLANG']), $REX['CUR_CLANG']);

			// check if url already exists
			if (isset($REXSEO_URLS[$newUrl])) { // url already exists
				$doDataUpdate = false;
				echo rex_warning($I18N->msg('seo42_urlpage_url_already_exists', seo42_utils::getCustomUrl($newUrl)));
			}

			break;
		case SEO42_URL_TYPE_CALL_FUNC:
			$newUrlData['func'] = rex_post('call_func');

			if (is_array(rex_post('url_clone_no_url'))) {
				$newUrlData['no_url'] = true;
			} else {
				$newUrlData['no_url'] = false;
			}

			break;
		case SEO42_URL_TYPE_LANGSWITCH:
			$newUrlData['clang_id'] = rex_post('langswitch_clang_id', 'int');
			break;
		case SEO42_URL_TYPE_NONE:
			// do nothing
			break;
	}	

	if (is_array(rex_post('url_clone'))) {
		$newUrlData['url_clone'] = true;
	} else {
		$newUrlData['url_clone'] = false;
	}

	if ($newUrlType == SEO42_URL_TYPE_DEFAULT && !$newUrlData['url_clone']) {
		$updateData = '';
	} else {
		$updateData = json_encode($newUrlData);
	}

	$sql = rex_sql::factory();
	//$sql->debugsql = 1;
	$sql->setTable($REX['TABLE_PREFIX'] . "article");
	$sql->setWhere("id=" . $articleId . " AND clang=" . $clang);

	$sql->setValue('seo_custom_url', $updateData);
	$sql->setValue('updatedate',  time());

	// do db update
	if ($doDataUpdate && $sql->update()) {
		// info msg
		echo rex_info($I18N->msg('seo42_urlpage_updated'));

		// generate stuff new
		rex_deleteCacheArticleContent($articleId, $clang);
		rex_generateArticle($articleId);
		rexseo_generate_pathlist(array());

		 // this is for frontend link fix with js
		$dataUpdated = true;
	} else {
		// err msg
		if ($sql->getError() != '') {
			echo rex_warning($sql->getError());
		}
	}
}

$sql = rex_sql::factory();
//$sql->debugsql = 1;
$urlData = $sql->getArray('SELECT seo_custom_url FROM '. $REX['TABLE_PREFIX'] .'article WHERE id=' . $articleId . ' AND clang=' . $clang);
$urlField = $urlData[0]['seo_custom_url'];

$urlType = SEO42_URL_TYPE_DEFAULT;
$cloneChecked = false;

if ($urlField != '') {
	$urlData = seo42_utils::getUrlTypeData($urlField);
	$jsonData = json_decode($urlData, true);

	// url type
	if (isset($jsonData['url_type'])) {
		$urlType = $jsonData['url_type'];
	} 

	// clone checkbox
	if (isset($jsonData['url_clone']) && $jsonData['url_clone']) {
		$cloneChecked = true;
	}
}
?>

<div class="rex-content-body" id="url-page">
	<div class="rex-content-body-2">
		<div class="rex-form" id="rex-addon-editmode">
			<form action="index.php" method="post" id="url-form" name="url-form">
				<input type="hidden" name="page" value="content" />
				<input type="hidden" name="article_id" value="<?php echo $articleId; ?>" />
				<input type="hidden" name="mode" value="url" />
				<input type="hidden" name="save" value="1" />
				<input type="hidden" name="clang" value="<?php echo $clang; ?>" />
				<input type="hidden" name="ctype" value="<?php echo $ctype; ?>" />

				<fieldset class="rex-form-col-1">
					<legend id="url-default"><?php echo $I18N->msg('seo42_urlpage_main_section'); ?></legend>
					<div class="rex-form-wrapper">
						<div class="rex-form-row">
							<p class="rex-form-textarea">
								<label for="url_description"><?php echo $I18N->msg('seo42_urlpage_url_type'); ?></label>
								<select name="url_type" size="1" id="url_type" class="rex-form-select">
									<option <?php if ($urlType == SEO42_URL_TYPE_DEFAULT) { echo 'selected'; } ?> value="<?php echo SEO42_URL_TYPE_DEFAULT; ?>"><?php echo $I18N->msg('seo42_urlpage_urltype_default'); ?></option>
									<option <?php if ($urlType == SEO42_URL_TYPE_USERDEF_INTERN) { echo 'selected'; } ?> value="<?php echo SEO42_URL_TYPE_USERDEF_INTERN; ?>"><?php echo $I18N->msg('seo42_urlpage_urltype_userdef_intern'); ?></option>
									<option <?php if ($urlType == SEO42_URL_TYPE_USERDEF_EXTERN) { echo 'selected'; } ?> value="<?php echo SEO42_URL_TYPE_USERDEF_EXTERN; ?>"><?php echo $I18N->msg('seo42_urlpage_urltype_userdef_extern'); ?></option>
									<option <?php if ($urlType == SEO42_URL_TYPE_MEDIAPOOL) { echo 'selected'; } ?> value="<?php echo SEO42_URL_TYPE_MEDIAPOOL; ?>"><?php echo $I18N->msg('seo42_urlpage_urltype_mediapool'); ?></option>
									<option <?php if ($urlType == SEO42_URL_TYPE_INTERN_REPLACE) { echo 'selected'; } ?> value="<?php echo SEO42_URL_TYPE_INTERN_REPLACE; ?>"><?php echo $I18N->msg('seo42_urlpage_urltype_intern_replace'); ?></option>
									<option <?php if ($urlType == SEO42_URL_TYPE_INTERN_REPLACE_CLANG) { echo 'selected'; } ?> value="<?php echo SEO42_URL_TYPE_INTERN_REPLACE_CLANG; ?>"><?php echo $I18N->msg('seo42_urlpage_urltype_intern_replace_clang'); ?></option>
									<option <?php if ($urlType == SEO42_URL_TYPE_REMOVE_ROOT_CAT) { echo 'selected'; } ?> value="<?php echo SEO42_URL_TYPE_REMOVE_ROOT_CAT; ?>"><?php echo $I18N->msg('seo42_urlpage_urltype_remove_root_cat'); ?></option>
									<?php if ($REX['ADDON']['seo42']['settings']['all_url_types']) { ?>
									<option <?php if ($urlType == SEO42_URL_TYPE_CALL_FUNC) { echo 'selected'; } ?> value="<?php echo SEO42_URL_TYPE_CALL_FUNC; ?>"><?php echo $I18N->msg('seo42_urlpage_urltype_call_func'); ?></option>
									<option <?php if ($urlType == SEO42_URL_TYPE_LANGSWITCH) { echo 'selected'; } ?> value="<?php echo SEO42_URL_TYPE_LANGSWITCH; ?>"><?php echo $I18N->msg('seo42_urlpage_urltype_langswitch'); ?></option>
									<option <?php if ($urlType == SEO42_URL_TYPE_NONE) { echo 'selected'; } ?> value="<?php echo SEO42_URL_TYPE_NONE; ?>"><?php echo $I18N->msg('seo42_urlpage_urltype_none'); ?></option>
									<?php } ?>
								</select>
							</p>
						</div>

						<div class="section" id="urltype_default" style="<?php if ($urlType == SEO42_URL_TYPE_DEFAULT) { echo 'display: block;'; } ?>">
						</div>

						<div class="section" id="urltype_userdef_intern" style="<?php if ($urlType == SEO42_URL_TYPE_USERDEF_INTERN) { echo 'display: block;'; } ?>">
							<div class="rex-form-row">
								<p class="rex-form-text">
									<label for="userdef_intern"><?php echo $I18N->msg('seo42_urlpage_urltype_userdef_intern'); ?></label>
									<input type="text" value="<?php if ($urlType == SEO42_URL_TYPE_USERDEF_INTERN) { echo seo42_utils::getCustomUrl($jsonData['custom_url']); } ?>" name="userdef_intern" id="userdef_intern" class="rex-form-text" />
								</p>
							</div>
						</div>

						<div class="section" id="urltype_userdef_extern" style="<?php if ($urlType == SEO42_URL_TYPE_USERDEF_EXTERN) { echo 'display: block;'; } ?>">
							<div class="rex-form-row">
								<p class="rex-form-text">
									<label for="userdef_extern"><?php echo $I18N->msg('seo42_urlpage_urltype_userdef_extern'); ?></label>
									<input type="text" value="<?php if ($urlType == SEO42_URL_TYPE_USERDEF_EXTERN) { echo $jsonData['custom_url']; } ?>" name="userdef_extern" id="userdef_extern" class="rex-form-text" />
								</p>
							</div>
						</div>

						<div class="section" id="urltype_mediapool" style="<?php if ($urlType == SEO42_URL_TYPE_MEDIAPOOL) { echo 'display: block;'; } ?>">
							<div class="rex-form-row">
								<p class="rex-form-col-a ">
                   					<label for="mediapool"><?php echo $I18N->msg('seo42_urlpage_urltype_mediapool'); ?></label>

    							</p>	
								<?php
								if ($urlType == SEO42_URL_TYPE_MEDIAPOOL) {
									$file = $jsonData['file'];
								} else {
									$file = '';
								}

								$button = rex_var_media::getMediaButton(1);
								$button = str_replace('REX_MEDIA[1]', $file, $button);
								$button = str_replace('MEDIA[1]', 'mediapool', $button);
								echo $button;
								?>
							</div>
						</div>

						<div class="section" id="urltype_intern_replace" style="<?php if ($urlType == SEO42_URL_TYPE_INTERN_REPLACE) { echo 'display: block;'; } ?>">
							<div class="rex-form-row">
								<p class="rex-form-col-a ">
		           					<label for="intern_replace_article_id"><?php echo $I18N->msg('seo42_urlpage_urltype_article'); ?></label>
	   							</p>
								<?php
								if ($urlType == SEO42_URL_TYPE_INTERN_REPLACE) {
									$articleId = $jsonData['article_id'];
								} else {
									$articleId = '';
								}

								$linkButton = rex_input::factory('linkbutton');
								$linkButton->setButtonId(1);
								$linkButton->setValue($articleId);
								$linkButton->setAttribute('name', 'intern_replace_article_id');
								echo $linkButton->getHtml();
								?>
							</div>
						</div>

						<div class="section" id="urltype_intern_replace_clang" style="<?php if ($urlType == SEO42_URL_TYPE_INTERN_REPLACE_CLANG) { echo 'display: block;'; } ?>">
							<div class="rex-form-row">
								<p class="rex-form-col-a">
		           					<label for="intern_replace_clang_article_id"><?php echo $I18N->msg('seo42_urlpage_urltype_article'); ?></label>
	   							</p>
								<?php
								if ($urlType == SEO42_URL_TYPE_INTERN_REPLACE_CLANG) {
									$articleId = $jsonData['article_id'];
								} else {
									$articleId = '';
								}

								$linkButton = rex_input::factory('linkbutton');
								$linkButton->setButtonId(2);
								$linkButton->setValue($articleId);
								$linkButton->setAttribute('name', 'intern_replace_clang_article_id');
								echo $linkButton->getHtml();
								?>
							</div>	
							<div class="rex-form-row">
								<p class="rex-form-text">
									<label for="intern_replace_clang_clang_id"><?php echo $I18N->msg('seo42_urlpage_urltype_clang'); ?></label>
									<select name="intern_replace_clang_clang_id" size="1" id="intern_replace_clang_clang_id" class="rex-form-select">
										<?php
										if ($urlType == SEO42_URL_TYPE_INTERN_REPLACE_CLANG) {
											$clangId = $jsonData['clang_id'];
										} else {
											$clangId = '0';
										}

										foreach ($REX['CLANG'] as $id => $name) {
											if ($clangId == $id) {
												$selected = 'selected';
											} else {
												$selected = '';
											}

											echo '<option value="' . $id . '" ' . $selected . '>' . $name . '</option>';
										}
										?>
								</select>
								</p>
							</div>
						</div>

						<div class="section" id="urltype_remove_root_cat" style="<?php if ($urlType == SEO42_URL_TYPE_REMOVE_ROOT_CAT) { echo 'display: block;'; } ?>">
						</div>

						<div class="section" id="urltype_call_func" style="<?php if ($urlType == SEO42_URL_TYPE_CALL_FUNC) { echo 'display: block;'; } ?>">
							<div class="rex-form-row">
								<p class="rex-form-text">
									<label for="call_func"><?php echo $I18N->msg('seo42_urlpage_urltype_function'); ?></label>
									<input type="text" value="<?php if ($urlType == SEO42_URL_TYPE_CALL_FUNC) { echo $jsonData['func']; } ?>" name="call_func" id="call_func" class="rex-form-text" />
								</p>
							</div>
							<div class="rex-form-row">
								<p class="rex-form-col-a rex-form-checkbox">
									<input type="checkbox" value="<?php if (isset($jsonData['no_url']) && $jsonData['no_url']) { echo "1"; $check = 'checked = "checked"'; } else { echo ""; $check = ""; } ?>" name="url_clone_no_url[]" class="rex-form-checkbox" <?php echo $check; ?> />								
									<label for="url_clone_no_url"><?php echo $I18N->msg('seo42_urlpage_url_clone_no_url') ?></label>
								</p>
							</div>
						</div>

						<div class="section" id="urltype_langswitch" style="<?php if ($urlType == SEO42_URL_TYPE_LANGSWITCH) { echo 'display: block;'; } ?>">
							<div class="rex-form-row">
								<p class="rex-form-text">
									<label for="langswitch_clang_id"><?php echo $I18N->msg('seo42_urlpage_urltype_clang'); ?></label>
									<select name="langswitch_clang_id" size="1" id="langswitch_clang_id" class="rex-form-select">
										<?php
										if ($urlType == SEO42_URL_TYPE_LANGSWITCH) {
											$clangId = $jsonData['clang_id'];
										} else {
											$clangId = '0';
										}

										foreach ($REX['CLANG'] as $id => $name) {
											if ($clangId == $id) {
												$selected = 'selected';
											} else {
												$selected = '';
											}

											echo '<option value="' . $id . '" ' . $selected . '>' . $name . '</option>';
										}
										?>
								</select>
								</p>
							</div>
						</div>

						<div class="section" id="urltype_none" style="<?php if ($urlType == SEO42_URL_TYPE_NONE) { echo 'display: block;'; } ?>">
						</div>	
						
						<?php if ($REX['START_CLANG_ID'] == $clang && count($REX['CLANG']) > 1) { ?>
						<div class="rex-form-row" id="clone-row" style="<?php if ($urlType == SEO42_URL_TYPE_USERDEF_INTERN) { echo 'display: none;'; } ?>">
							<p class="rex-form-col-a rex-form-checkbox">
								<input type="checkbox" id="url_clone" value="<?php if (isset($jsonData['url_clone']) && $jsonData['url_clone']) { echo "1"; $check = 'checked = "checked"'; } else { echo ""; $check = ""; } ?>" name="url_clone[]" class="rex-form-checkbox" <?php echo $check; ?> />								
								<label for="url_clone"><?php echo $I18N->msg('seo42_urlpage_url_clone') ?></label>
							</p>
						</div>
						<?php } else { ?>
						<?php
						$sql = rex_sql::factory();
						//$sql->debugsql = 1;
						$urlDataStartClang = $sql->getArray('SELECT seo_custom_url FROM '. $REX['TABLE_PREFIX'] .'article WHERE id=' . $REX['ARTICLE_ID'] . ' AND clang=' . $REX['START_CLANG_ID']);

						$urlFieldStartClang = $urlDataStartClang[0]['seo_custom_url'];
						$cloneCheckedStartClang = false;

						if ($urlFieldStartClang != '') {
							$urlDataStartClang = seo42_utils::getUrlTypeData($urlFieldStartClang);
							$jsonDataStartClang = json_decode($urlDataStartClang, true);

							if (isset($jsonDataStartClang['url_clone']) && $jsonDataStartClang['url_clone']) {
								$cloneCheckedStartClang = true;
							}
						}

						if ($cloneCheckedStartClang == true && !isset($jsonData['url_type']) || (isset($jsonData['url_type']) && $jsonData['url_type'] == SEO42_URL_TYPE_DEFAULT)) {
						?>
							<div class="rex-form-row">
								<p class="rex-form-col-a rex-form-read">
									<label for="document_id_visible"><?php echo $I18N->msg('seo42_urlpage_lang_clone_hint'); ?></label>
									<span class="rex-form-read" id="document_id_visible"><?php echo $I18N->msg('seo42_urlpage_lang_clone_hint_msg', $REX['CLANG'][$REX['START_CLANG_ID']]); ?></span>
								</p>
							</div>
						<?php } ?>
						<?php } ?>
					</div>
				</fieldset>
				<fieldset class="rex-form-col-1">
					<div class="rex-form-wrapper">
						<div class="rex-form-row">
							<p class="rex-form-col-a rex-form-submit">
								<input type="submit" value="<?php echo $I18N->msg('seo42_urlpage_button_text'); ?>" name="save_data" class="rex-form-submit" />
								<br/><br/>
							</p>
						</div>
						<div class="rex-clearer"></div>
					</div>
				</fieldset>
			</form>
		</div>
	</div>
</div>


<style type="text/css">
#url-page .section {
	display: none;
}

#url-page div.rex-form div.rex-form-row p input.rex-form-submit {
    margin-top: 8px;
}
</style>

<script type="text/javascript">
jQuery(document).ready(function($) {
	urlTypes = new Array();
	urlType = <?php echo $urlType; ?>;
	cloneChecked = <?php if ($cloneChecked) { echo 'true'; } else { echo 'false;'; } ?>;
	
	urlTypes[<?php echo SEO42_URL_TYPE_DEFAULT; ?>] = '#urltype_default';
	urlTypes[<?php echo SEO42_URL_TYPE_USERDEF_INTERN; ?>] = '#urltype_userdef_intern';
	urlTypes[<?php echo SEO42_URL_TYPE_USERDEF_EXTERN; ?>] = '#urltype_userdef_extern';
	urlTypes[<?php echo SEO42_URL_TYPE_MEDIAPOOL; ?>] = '#urltype_mediapool';
	urlTypes[<?php echo SEO42_URL_TYPE_INTERN_REPLACE; ?>] = '#urltype_intern_replace';
	urlTypes[<?php echo SEO42_URL_TYPE_INTERN_REPLACE_CLANG; ?>] = '#urltype_intern_replace_clang';
	urlTypes[<?php echo SEO42_URL_TYPE_REMOVE_ROOT_CAT; ?>] = '#urltype_remove_root_cat';
	urlTypes[<?php echo SEO42_URL_TYPE_CALL_FUNC; ?>] = '#urltype_call_func';
	urlTypes[<?php echo SEO42_URL_TYPE_LANGSWITCH; ?>] = '#urltype_langswitch';
	urlTypes[<?php echo SEO42_URL_TYPE_NONE; ?>] = '#urltype_none';
	
	$('#url_type').change(function() {
		// first hide all sections
		$('.section').hide();

		// then make section for new selected url type visible 
		$(urlTypes[$(this).val()]).show();

		// clone checkbox
		if ($(this).val() == urlType && cloneChecked) {
			// set correct checkbox state
			$('#url_clone').attr('checked', true);
		} else {
			// reset checkbox state
			$('#url_clone').attr('checked', false);
		}

		// hide row with clone checkbox for certain url types
		if ($(this).val() == <?php echo SEO42_URL_TYPE_USERDEF_INTERN; ?> || $(this).val() == <?php echo SEO42_URL_TYPE_LANGSWITCH; ?>) {
			$('#clone-row').hide();
		} else {
			$('#clone-row').show();
		}
	});

	<?php if ($dataUpdated) { ?>jQuery('.rex-navi-content li:last-child a').attr('href', '<?php echo seo42::getFullUrl(); ?>');<?php } ?>

	<?php if (!$doDataUpdate) { ?>$('#url_type').val(<?php echo rex_request('url_type', 'int'); ?>); $('#url_type').change();<?php } ?>
});
</script>





