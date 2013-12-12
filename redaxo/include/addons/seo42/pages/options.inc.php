<?php
$myself = rex_request('page', 'string');
$subpage = rex_request('subpage', 'string');
$func = rex_request('func', 'string');

// config file
$config_file = $REX['INCLUDE_PATH'] . '/addons/seo42/settings.dyn.inc.php';

// save settings
if ($func == 'update') {
	$_url_ending = trim(rex_request('url_ending', 'string'));
	$_hide_langslug = trim(rex_request('hide_langslug', 'int'));
	$_homeurl = trim(rex_request('homeurl', 'int'));
	$_homelang = trim(rex_request('homelang', 'int'));
	$_robots = trim(rex_request('robots', 'string'));

	$REX['ADDON']['seo42']['settings']['url_ending'] = $_url_ending;
	$REX['ADDON']['seo42']['settings']['hide_langslug'] = $_hide_langslug;
	$REX['ADDON']['seo42']['settings']['homeurl'] = $_homeurl;
	$REX['ADDON']['seo42']['settings']['homelang'] = $_homelang;

	$content = '
		$REX[\'ADDON\'][\'seo42\'][\'settings\'][\'url_ending\'] = \'' . $_url_ending . '\';
		$REX[\'ADDON\'][\'seo42\'][\'settings\'][\'hide_langslug\'] = ' . $_hide_langslug . ';
		$REX[\'ADDON\'][\'seo42\'][\'settings\'][\'homeurl\'] = ' . $_homeurl . ';
		$REX[\'ADDON\'][\'seo42\'][\'settings\'][\'homelang\'] = ' . $_homelang . ';
	';

	// write independent config file
	if (rex_replace_dynamic_contents($config_file, str_replace("\t", "", $content)) !== false) {
		echo rex_info($I18N->msg('seo42_config_ok'));
	} else {
		echo rex_warning($I18N->msg('seo42_config_error'));
	}

	// update robots file
	if ($REX['ADDON']['seo42']['settings']['robots'] == '' && $_robots == '') {
		// do nothing
	} else {
		seo42_utils::updateRobotsFile($_robots);
		seo42_utils::includeRobotsSettings();
	}
	
	rexseo_generate_pathlist('');
}

if (!is_writable($config_file)) {
	echo rex_warning($I18N->msg('seo42_config_file_no_perms'), $config_file);
}

// url ending select box
$url_ending_select = new rex_select();
$url_ending_select->setSize(1);
$url_ending_select->setName('url_ending');
$url_ending_select->addOption('.html','.html');
$url_ending_select->addOption('/','/');
$url_ending_select->addOption($I18N->msg('seo42_settings_url_ending_without'), '');
$url_ending_select->setAttribute('style','width:70px;');
$url_ending_select->setSelected($REX['ADDON'][$myself]['settings']['url_ending']);

// home url select box
$ooa = OOArticle::getArticleById($REX['START_ARTICLE_ID']);

if ($ooa) {
  $homename = strtolower($ooa->getName());
} else {
  $homename = 'Startartikel';
}

unset($ooa);

$homeurl_select = new rex_select();
$homeurl_select->setSize(1);
$homeurl_select->setName('homeurl');
$homeurl_select->addOption(seo42::getServerUrl().$homename.'.html',0);
$homeurl_select->addOption(seo42::getServerUrl(),1);
$homeurl_select->addOption(seo42::getServerUrl().'lang-slug/',2);
$homeurl_select->setAttribute('style','width:250px;');
$homeurl_select->setSelected($REX['ADDON'][$myself]['settings']['homeurl']);

// lang slug select box
if (count($REX['CLANG']) > 1) {
  $hide_langslug_select = new rex_select();
  $hide_langslug_select->setSize(1);
  $hide_langslug_select->setName('hide_langslug');
  $hide_langslug_select->addOption($I18N->msg('seo42_settings_langslug_all'),-1);

  foreach($REX['CLANG'] as $id => $str) {
    $hide_langslug_select->addOption($I18N->msg('seo42_settings_langslug_noslug') . ' '.$str,$id);
  }

  $hide_langslug_select->setSelected($REX['ADDON'][$myself]['settings']['hide_langslug']);
  $hide_langslug_select = '
          <div class="rex-form-row">
            <p class="rex-form-col-a rex-form-select">
              <label for="hide_langslug">' . $I18N->msg('seo42_settings_langslug') . '</label>
                '.$hide_langslug_select->get().'
                </p>
          </div><!-- /rex-form-row -->';
} else {
  $hide_langslug_select = '';
}

// home lang select box
if (count($REX['CLANG']) > 1) {
  $homelang_select = new rex_select();
  $homelang_select->setSize(1);
  $homelang_select->setName('homelang');

  foreach($REX['CLANG'] as $id => $str) {
    $homelang_select->addOption($str,$id);
  }

  $homelang_select->setSelected($REX['ADDON'][$myself]['settings']['homelang']);
  $homelang_select->setAttribute('style','width:70px;margin-left:20px;');
  $homelang_box = '
              <span style="margin:0 4px 0 4px;display:inline-block;width:100px;text-align:right;">
                ' . $I18N->msg('seo42_settings_language') . '
              </span>
              '.$homelang_select->get().'
              ';
} else {
  $homelang_box = '';
}

// form
echo '

<div class="rex-addon-output">
  <div class="rex-form">

  <form action="index.php" method="post">
    <input type="hidden" name="page" value="seo42" />
    <input type="hidden" name="subpage" value="' . $subpage . '" />
    <input type="hidden" name="func" value="update" />
';

echo '
      <fieldset class="rex-form-col-1">
        <legend>' . $I18N->msg('seo42_settings_main_section') . '</legend>
        <div class="rex-form-wrapper">

          <div class="rex-form-row">
            <p class="rex-form-col-a rex-form-select">
              <label for="url_ending">' . $I18N->msg('seo42_settings_url_ending') . '</label>
               '.$url_ending_select->get().'
            </p>
          </div>

          '.$hide_langslug_select.'

          <div class="rex-form-row">
            <p class="rex-form-col-a rex-form-select">
              <label for="homeurl">' . $I18N->msg('seo42_settings_startpage') . '</label>
                '.$homeurl_select->get().'
                '.$homelang_box.'
            </p>
          </div>';

	echo '<div class="rex-form-row rex-form-element-v1">
			<p class="rex-form-col-a rex-form-read">
				<label for="lang_hint">' . $I18N->msg('seo42_settings_lang_hint') . '</label>
				' . seo42_utils::getLangSettingsFile() . '
			</p>
		</div>';

	echo '<div class="rex-form-row rex-form-element-v1">
			<p class="rex-form-col-a rex-form-read">
				<label for="advanced_hint">' . $I18N->msg('seo42_settings_advanced_hint') . '</label>
				<span class="rex-form-read" id="advanced_hint"><code>/seo42/settings.advanced.inc.php</code></span>
			</p>
		</div>

		<div class="rex-form-row rex-form-element-v1">
			<p class="rex-form-col-a rex-form-read">
				<label for="show-settings">' . $I18N->msg('seo42_settings_show_all') . '</label>
				<span class="rex-form-read"><a id="show-settings" href="#">' . $I18N->msg('seo42_settings_show') . '</a></span>
			</p>
		</div>

		<div id="all-settings" style="display: none;" class="rex-form-row rex-form-element-v1">
			<p class="rex-form-col-a rex-form-read">
				<pre class="rex-code">' . seo42_utils::print_r_pretty($REX['ADDON']['seo42']['settings']) . '</pre>
			</p>
		</div>

        </div>
      </fieldset>

      <fieldset class="rex-form-col-1">
        <legend>robots.txt</legend>
        <div class="rex-form-wrapper">

          <div class="rex-form-row">
            <p class="rex-form-col-a rex-form-select">
              <label for="robots">' . $I18N->msg('seo42_settings_robots_additional') . '</label>
              <textarea id="rexseo_robots" name="robots" rows="2">'.stripslashes($REX['ADDON'][$myself]['settings']['robots']).'</textarea>
            </p>
          </div>

		  <div class="rex-form-row">
            <p class="rex-form-col-a rex-form-select">
              <label for="robots-txt">' . $I18N->msg('seo42_settings_robots_link') . '</label>
              <span class="rex-form-read" id="robots-txt"><a href="' . seo42::getBaseUrl() . 'robots.txt" target="_blank">' . seo42::getBaseUrl() . 'robots.txt</a></span>
            </p>
          </div>

        </div>
      </fieldset>


      <fieldset class="rex-form-col-1">
        <legend>sitemap.xml</legend>
        <div class="rex-form-wrapper">

          <div class="rex-form-row">
            <p class="rex-form-col-a rex-form-select">
              <label for="xml-sitemap">' . $I18N->msg('seo42_settings_sitemap_link') . '</label>
              <span class="rex-form-read" id="xml-sitemap"><a href="' . seo42::getBaseUrl() . 'sitemap.xml" target="_blank">' . seo42::getBaseUrl() . 'sitemap.xml</a></span>
            </p>
          </div>

        </div>
      </fieldset>

      <fieldset class="rex-form-col-1">
        <legend>&nbsp;</legend>
        <div class="rex-form-wrapper">

          <div class="rex-form-row rex-form-element-v2">

            <p class="rex-form-submit">
              <input style="margin-top: 5px; margin-bottom: 5px;" class="rex-form-submit" type="submit" id="sendit" name="sendit" value="' . $I18N->msg('seo42_settings_submit') . '" />
            </p>
          </div>

        </div>
      </fieldset>

  </form>
  </div>
</div>

';

unset($homeurl_select,$url_ending_select);
?>

<style type="text/css">
#lang_hint {
	width: auto;
}
</style>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('#show-settings').toggle( 
			function() {
				$('#all-settings').slideDown(); 
				$('#show-settings').html('<?php echo $I18N->msg('seo42_settings_hide'); ?>');
			}, 
			function() { 
				$('#all-settings').slideUp(); 
				$('#show-settings').html('<?php echo $I18N->msg('seo42_settings_show'); ?>');
			} 
		);
	});
</script>


