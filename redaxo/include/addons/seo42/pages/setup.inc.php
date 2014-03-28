<?php
$codeExample = '<!DOCTYPE html>

<html lang="<?php echo seo42::getLangCode(); ?>">
<head>
	<meta charset="utf-8" />
	<base href="<?php echo seo42::getBaseUrl(); ?>" />
	<title><?php echo seo42::getTitle(); ?></title>
	<meta name="description" content="<?php echo seo42::getDescription(); ?>" />
	<meta name="keywords" content="<?php echo seo42::getKeywords(); ?>" />
	<meta name="robots" content="<?php echo seo42::getRobotRules();?>" />
	<link rel="stylesheet" href="<?php echo seo42::getCSSFile("default.css"); ?>" type="text/css" media="screen,print" />
	<link rel="stylesheet" href="<?php echo seo42::getCSSFile("print.css"); ?>" type="text/css" media="print" />
	<link rel="shortcut icon" href="<?php echo seo42::getImageFile("favicon.ico"); ?>" type="image/x-icon" />
	<link rel="canonical" href="<?php echo seo42::getCanonicalUrl(); ?>" />
	<?php echo seo42::getLangTags(); ?>
</head>

<body>
<div id="container">
	<div id="link"><a href="<?php echo rex_getUrl(1); ?>">' . $I18N->msg('seo42_setup_codeexamples_goto_startpage') . '</a></div>
	<div id="media"><img src="<?php echo seo42::getMediaFile("logo.png"); ?>" alt="" /></div>
	<div id="imagetype"><img src="<?php echo seo42::getImageManagerFile("pic.png", "my_img_type"); ?>" alt="" /></div>
	<div id="mainmenu"><?php echo seo42::getNavigationByLevel(0, 1); ?></div>
	<div id="submenu"><?php echo seo42::getNavigationByLevel(1, 3); ?></div>
	<div id="content"><?php echo $this->getArticle(); ?></div>
	<div id="footer"><?php echo seo42::getNavigationByCategory(42); ?></div>
</div>
<script type="text/javascript" src="<?php echo seo42::getJSFile("jquery.min.js"); ?>"></script>
<script type="text/javascript" src="<?php echo seo42::getJSFile("init.js"); ?>"></script>
</body>
</html>';

$page = rex_request('page', 'string');
$subpage = rex_request('subpage', 'string');
$chapter = rex_request('chapter', 'string');
$func = rex_request('func', 'string');

$htaccessRoot = $REX['FRONTEND_PATH'] . '/.htaccess';
$backupPathRoot = $REX['INCLUDE_PATH'] . '/addons/seo42/backup/';

if (isset($REX['WEBSITE_MANAGER'])) {
	$wmDisabled = ' disabled="disabled"';
	$step1Msg = $I18N->msg('seo42_setup_step1_msg1_wm', 'index.php?page=website_manager');
} else {
	$wmDisabled = '';
	$step1Msg = $I18N->msg('seo42_setup_step1_msg1');
}

if ($func == "do_copy") {
	// first backup files
	$htaccessBackupFile = '_htaccess_' . date('Ymd_His');
	$doCopy = true;
	$htaccessFileExists = false;
	$copySuccessful = false;

	if (file_exists($htaccessRoot)) {
		$htaccessFileExists = true;

		if (copy($htaccessRoot, $backupPathRoot . $htaccessBackupFile)) {
			$doCopy = true;
		} else {
			rex_warning($I18N->msg('seo42_setup_file_backup_failed', $htaccessRoot));
			$doCopy = false;
		} 
	}

	// then copy if backup was successful
	if ($doCopy) {
		$sourceFile = $REX['INCLUDE_PATH'] . '/addons/seo42/install/_htaccess';

		if (copy($sourceFile, $htaccessRoot)) {
			$copySuccessful = true;
			$msg = $I18N->msg('seo42_setup_file_copy_successful');
	
			if ($htaccessFileExists) {
				$msg .= ' ' . $I18N->msg('seo42_setup_backup_successful');
			}

			echo rex_info($msg);
		} else {
			echo rex_warning($I18N->msg('seo42_setup_file_copy_failed'));	
		}
	} else {
		echo rex_warning($I18N->msg('seo42_setup_backup_failed'));
	}

	if ($copySuccessful && (rex_request('www_redirect', 'int') == 1 || rex_request('modify_rewritebase', 'int') == 1 || rex_request('directory_listing', 'int') == 1)) {
		$content = rex_get_file_contents($htaccessRoot);

		// this is for non-ww to www redirect
		if (rex_request('www_redirect', 'int') == 1) {
			if (seo42::isWwwServerUrl()) {
				$wwwRedirect1 = '#RewriteCond %{HTTP_HOST} ^[^.]+\.[^.]+$';
				$wwwRedirect2 = '#RewriteRule ^(.*)$ http://www.%{HTTP_HOST}/$1 [L,R=301]';
	
				$content = str_replace($wwwRedirect1, ltrim($wwwRedirect1, '#'), $content);
				$content = str_replace($wwwRedirect2, ltrim($wwwRedirect2, '#'), $content);
			} else {
				$wwwRedirect1 = '#RewriteCond %{HTTP_HOST} ^www\.(.*)$ [NC]';
				$wwwRedirect2 = '#RewriteRule ^(.*)$ http://%1/$1 [R=301,L]';
	
				$content = str_replace($wwwRedirect1, ltrim($wwwRedirect1, '#'), $content);
				$content = str_replace($wwwRedirect2, ltrim($wwwRedirect2, '#'), $content);
			}
		}

		// this is for subdir installations  
		if (rex_request('modify_rewritebase', 'int') == 1) {
			$rewriteBase = 'RewriteBase /';

			$content = str_replace($rewriteBase,$rewriteBase . seo42::getServerSubDir(), $content);
		}

		// this is for misconfigured webservers to turn of directory listing
		if (rex_request('directory_listing', 'int') == 1) {
			$optionsIndexes = '#Options -Indexes';

			$content = str_replace($optionsIndexes, ltrim($optionsIndexes, '#'), $content);
		}

		if (rex_put_file_contents($htaccessRoot, $content) > 0) {
			//echo rex_info($I18N->msg('seo42_setup_htaccess_patch_ok'));
		} else {
			echo rex_warning($I18N->msg('seo42_setup_htaccess_patch_failed'));
		}
	}
} elseif ($func == "apply_settings") {
	$server = str_replace("\\'", "'", rex_post('server', 'string'));
	$servername  = str_replace("\\'", "'", rex_post('servername', 'string'));

	$masterFile = $REX['INCLUDE_PATH'] . '/master.inc.php';
	$content = rex_get_file_contents($masterFile);

	$search = array('\\"', "'", '$');
	$destroy = array('"', "\\'", '\\$');
	$replace = array(
		'search' => array(
			"@(REX\['SERVER'\].?\=.?).*$@m",
			"@(REX\['SERVERNAME'\].?\=.?).*$@m"
		),
		'replace' => array(
			"$1'".str_replace($search, $destroy, $server) . "';",
			"$1'".str_replace($search, $destroy, $servername) . "';"
		)
	);

	$content = preg_replace($replace['search'], $replace['replace'], $content);

	if (rex_put_file_contents($masterFile, $content) > 0) {
		echo rex_info($I18N->msg('seo42_setup_settings_saved'));

		$REX['SERVER'] = stripslashes($server);
		$REX['SERVERNAME'] = stripslashes($servername);

		// reinit because of subdir check in step 2
		seo42::init();
	} else {
		echo rex_warning($I18N->msg('seo42_setup_settings_save_failed'));
	}
}
?>

<div class="rex-addon-output">
	<h2 class="rex-hl2"><?php echo $I18N->msg('seo42_setup_step1'); ?></h2>
	<div class="rex-area-content">
		<p class="info-msg"><?php echo $step1Msg; ?></p>
		<form action="index.php" method="post" id="settings-form">
			<p class="rex-form-col-a first-textfield">
				<label for="servername"><?php echo $I18N->msg('seo42_setup_website_name'); ?></label>
				<input <?php echo $wmDisabled; ?> name="servername" id="servername" type="text" class="rex-form-text" value="<?php echo htmlspecialchars($REX['SERVERNAME']); ?>" />
			</p>

			<p class="rex-form-col-a">
				<label for="server"><?php echo $I18N->msg('seo42_setup_website_url'); ?></label>
				<input <?php echo $wmDisabled; ?> name="server" id="server" type="text" class="rex-form-text" value="<?php echo htmlspecialchars($REX['SERVER']); ?>" />
				<?php if (seo42_utils::detectSubDir()) { echo '<span class="subdir-hint">' . $I18N->msg('seo42_setup_subdir_hint') . '</span>'; } ?>
				<span class="url-hint"><?php echo $I18N->msg('seo42_setup_url_alert'); ?></span>
			</p>
			
			<p class="rex-form-col-a rex-form-read">
				<label for="lang_hint"><?php echo $I18N->msg('seo42_settings_lang_hint'); ?></label>
				<?php echo seo42_utils::getLangSettingsFile(); ?>
			</p>

			<input type="hidden" name="page" value="seo42" />
			<input type="hidden" name="subpage" value="setup" />
			<input type="hidden" name="func" value="apply_settings" />
			<div class="rex-form-row">
				<p class="button"><input <?php echo $wmDisabled; ?> type="submit" class="rex-form-submit" name="sendit" value="<?php echo $I18N->msg('seo42_setup_step1_button'); ?>" /></p>
			</div>
		</form>
	</div>
</div>

<div class="rex-addon-output">
	<h2 class="rex-hl2"><?php echo $I18N->msg('seo42_setup_step2'); ?></h2>
	<div class="rex-area-content">
		<p><?php echo $I18N->msg('seo42_setup_step2_msg1'); ?></p>
		<form action="index.php" method="post">
			<p class="no-bottom-margin" id="codeline">
				<code>/redaxo/include/addons/seo42/install/_htaccess</code> &nbsp;<?php echo $I18N->msg('seo42_setup_to'); ?>&nbsp; <code>/.htaccess</code>
			</p>

			<?php if (seo42::getServerSubDir() != '' || seo42_utils::hasHtaccessSubDirRewriteBase()) { ?>
			<p class="rex-form-checkbox rex-form-label-right"> 
				<input type="checkbox" value="1" id="modify_rewritebase" name="modify_rewritebase" checked="checked" />
				<label for="modify_rewritebase"><?php echo $I18N->msg('seo42_setup_rewritebase', seo42::getServerSubDir()). ' <span>' . $I18N->msg('seo42_setup_required') . '</span>'; ?></label>
			</p>
			<?php } ?>

			<p class="rex-form-checkbox rex-form-label-right"> 
				<input type="checkbox" value="1" id="www_redirect" name="www_redirect" />
				<label for="www_redirect"><?php if (seo42::isWwwServerUrl()) { echo $I18N->msg('seo42_setup_www_redirect_checkbox'); } else { echo $I18N->msg('seo42_setup_non_www_redirect_checkbox'); } echo  ' <span>' . $I18N->msg('seo42_setup_recommended') . '</span>'; ?></label>
			</p>

			<p class="rex-form-checkbox rex-form-label-right"> 
				<input type="checkbox" value="1" id="directory_listing" name="directory_listing" />
				<label for="directory_listing"><?php echo $I18N->msg('seo42_setup_directory_listing_checkbox') . ' <span>' . $I18N->msg('seo42_setup_recommended') . '</span>'; ?></label>
				<span id="directory_listing_hint" title="<?php echo $I18N->msg('seo42_setup_directory_listing_alert'); ?>" class="seo42-tooltip status exclamation">&nbsp;</span>
			</p>

			<input type="hidden" name="page" value="seo42" />
			<input type="hidden" name="subpage" value="setup" />
			<input type="hidden" name="func" value="do_copy" />
			<div class="rex-form-row">
				<p class="button"><input type="submit" class="rex-form-submit" name="sendit" id="copy-file-submit" value="<?php echo $I18N->msg('seo42_setup_step2_button'); ?>" /></p>
			</div>
		</form>
	</div>
</div>

<div class="rex-addon-output">
	<h2 class="rex-hl2"><?php echo $I18N->msg('seo42_setup_step3'); ?></h2>
	<div class="rex-area-content">
		<p class="info-msg"><?php echo $I18N->msg('seo42_setup_step3_msg1'); ?></p>
		<div id="code-example1"><?php rex_highlight_string($codeExample); ?></div>

		<p class="info-msg last-msg"><?php echo $I18N->msg('seo42_setup_codeexamples'); ?></p>
	</div>
</div>

<style type="text/css">
#rex-page-seo42 span.subdir-hint,
#rex-page-seo42 span.url-hint {
	margin-left: 165px;
	display: block;
	margin-bottom: 5px;
    margin-top: -5px;
}
#rex-page-seo42 span.url-hint {
	color: red;
	display: none;
}

#rex-page-seo42 .rex-code {
    overflow: auto;
	white-space: nowrap;

}

#rex-page-seo42 .spacer {
	height: 10px;
}

#rex-page-seo42 .info-msg {
	margin-bottom: 10px;
}

#rex-page-seo42 .no-bottom-margin {
	margin-bottom: 0px;
	margin-top: 7px;
}

#rex-page-seo42 .last-msg {
	margin-bottom: 0px;
	margin-top: 12px;
}


#rex-page-seo42 .button {
	float: right; 
	margin-bottom: 10px; 
	margin-right: 5px;
	
}

#rex-page-seo42 p.rex-form-col-a.first-textfield {
	margin-bottom: 3px;
}

#rex-page-seo42 p.rex-form-col-a label {
	width: 160px;
	display: inline-block;
	margin-bottom: 10px;
}

#rex-page-seo42 p.rex-form-col-a input.rex-form-text {
	width: 320px;
}

#rex-page-seo42 p.rex-form-checkbox input {
	position: relative;
	top: 3px;
}

#rex-page-seo42 #modify_rewritebase {
	margin-top: 10px;
}

#rex-page-seo42 #www_redirect {
    margin-top: 8px;
}

#rex-page-seo42 #directory_listing {
    margin-top: 8px;
}

#rex-page-seo42 #directory_listing_hint {
	vertical-align: bottom;
	display: none;
}

.rex-form-checkbox label span {
	font-size: 10px;
}
</style>

<script type="text/javascript">
var rewriteBaseMsgShown = false;

function isCompleteWebsiteUrl() {
	var pat = /^https?:\/\//i;
	var serverString = jQuery('#server').val();
	var slashPosAfterDomain = serverString.indexOf("/", 8); // https:// = 8

	if (pat.test(serverString) && slashPosAfterDomain !== -1 && (serverString.charAt(serverString.length - 1) == '/')) {
		return true;
	}

	return false;
}

jQuery(document).ready( function() {
	if (!isCompleteWebsiteUrl()) {
		jQuery('span.url-hint').css('display', 'block');
	}

	jQuery('#settings-form').submit(function() {
		if (!isCompleteWebsiteUrl()) {
			alert('<?php echo $I18N->msg('seo42_setup_url_alert'); ?>');
			return false;
		}

		return true;
	});

	<?php if (file_exists($htaccessRoot)) { ?>
	jQuery('#copy-file-submit').click(function(e) {
		if (!confirm("<?php echo $I18N->msg('seo42_setup_htaccess_alert'); ?>")) {
			e.preventDefault();
		}
	});
	<?php } ?>

	jQuery('#modify_rewritebase').click(function(e) {
		if (!jQuery('#modify_rewritebase').is(':checked') && !rewriteBaseMsgShown) {
			rewriteBaseMsgShown = true;
			alert("<?php echo $I18N->msg('seo42_setup_rewritebase_alert'); ?>\r\n\r\nRewriteBase /<?php echo seo42::getServerSubDir(); ?>");
		}
	});

	jQuery('#directory_listing').click(function(e) {
		if (jQuery('#directory_listing').is(':checked')) {
			jQuery('#directory_listing_hint').css('display', 'inline-block');
		} else {
			jQuery('#directory_listing_hint').hide();
		}
	});
});
</script>

