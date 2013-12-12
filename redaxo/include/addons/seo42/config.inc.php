<?php

// register addon
$REX['ADDON']['rxid']['seo42'] = '0';
$REX['ADDON']['name']['seo42'] = 'SEO42';
$REX['ADDON']['version']['seo42'] = '2.9.0 DEV';
$REX['ADDON']['author']['seo42'] = 'Markus Staab, Wolfgang Huttegger, Dave Holloway, Jan Kristinus, jdlx, RexDude';
$REX['ADDON']['supportpage']['seo42'] = 'forum.redaxo.de';
$REX['ADDON']['perm']['seo42'] = 'seo42[]';

// permissions
$REX['PERM'][] = 'seo42[]';
$REX['PERM'][] = 'seo42[tools_only]';
$REX['PERM'][] = 'seo42[redirects_only]';
$REX['EXTPERM'][] = 'seo42[seo_default]';
$REX['EXTPERM'][] = 'seo42[seo_extended]';
$REX['EXTPERM'][] = 'seo42[url_default]';

// includes
require_once($REX['INCLUDE_PATH'] . '/addons/seo42/classes/class.seo42.inc.php');
require_once($REX['INCLUDE_PATH'] . '/addons/seo42/classes/class.seo42_utils.inc.php');
require_once($REX['INCLUDE_PATH'] . '/addons/seo42/classes/class.nav42.inc.php');
require_once($REX['INCLUDE_PATH'] . '/addons/seo42/settings.dyn.inc.php');
require_once($REX['INCLUDE_PATH'] . '/addons/seo42/settings.advanced.inc.php');
require_once($REX['INCLUDE_PATH'] . '/addons/seo42/settings.lang.inc.php');

// robots settings (can be different when website manager is installed)
seo42_utils::includeRobotsSettings();

// fix for iis webserver: set request uri manually if not available
seo42_utils::requestUriFix();

// do redirect for frontend if necessary
if (!$REX['REDAXO']) {
	seo42_utils::redirect();
}

// init
if (!$REX['SETUP']) {
	// auto mod rewrite, but not for redaxo system page
	if ($REX['REDAXO'] && rex_request('page') == 'specials') {
		// don't touch mod rewrite var
	} else {
		if ($REX['ADDON']['seo42']['settings']['rewriter']) {
			$REX['MOD_REWRITE'] = true;
		} else {
			$REX['MOD_REWRITE'] = false;
		}
	}

	// init 42
	rex_register_extension('ADDONS_INCLUDED', 'seo42_utils::init', '', REX_EXTENSION_EARLY);

	// send additional headers if necessary
	rex_register_extension('OUTPUT_FILTER_CACHE', 'seo42_utils::sendHeaders');
}

if ($REX['REDAXO']) {
	// append lang file
	$I18N->appendFile($REX['INCLUDE_PATH'] . '/addons/seo42/lang/');

	// handels ajax request for google pagerank checker in tools section
	if ($REX['ADDON']['seo42']['settings']['pagerank_checker'] && isset($REX['USER']) && rex_request('function') == 'getpagerank') {
		require($REX['INCLUDE_PATH'] . '/addons/seo42/classes/class.google_pagerank_checker.inc.php');
		echo GooglePageRankChecker::getRank(rex_request('url'));
		exit;
	}

	// subpages
	if (isset($REX['USER']) && !$REX['USER']->isAdmin() && ($REX['USER']->hasPerm('seo42[tools_only]') || $REX['USER']->hasPerm('seo42[redirects_only]'))) {
		// add subpages for non admin users
		if ($REX['USER']->hasPerm('seo42[tools_only]')) {
			// add tools page only
			$REX['ADDON']['seo42']['SUBPAGES'][] = array('tools', $I18N->msg('seo42_tools'));
		}

		if ($REX['USER']->hasPerm('seo42[redirects_only]')) {
			// add redirects page only
			$REX['ADDON']['seo42']['SUBPAGES'][] = array('redirects', $I18N->msg('seo42_redirects'));
		}

		$REX['ADDON']['seo42']['SUBPAGES'][0][0] = '';
	} else {
		// add subpages
		$REX['ADDON']['seo42']['SUBPAGES'] = array(
			array('', $I18N->msg('seo42_welcome')),
			array('tools', $I18N->msg('seo42_tools')),
			array('redirects', $I18N->msg('seo42_redirects'))
		);

		// plugins (will be autoloaded incl. language file)
		$plugins = OOPlugin::getAvailablePlugins('seo42');

		for ($i = 0; $i < count($plugins); $i++) {
			$I18N->appendFile($REX['INCLUDE_PATH'] . '/addons/seo42/plugins/' . $plugins[$i] . '/lang/'); // make msg for subpage available at this point 
			array_push($REX['ADDON']['seo42']['SUBPAGES'], array($plugins[$i], $I18N->msg('seo42_' . $plugins[$i])));
		}

		// rest of sub pages
		array_push($REX['ADDON']['seo42']['SUBPAGES'], 
			array('options', $I18N->msg('seo42_settings')),
			array('setup', $I18N->msg('seo42_setup')),
			array('help', $I18N->msg('seo42_help'))
		);
	}

	// add css/js files to page header
	if (rex_request('page') == 'seo42') {
		rex_register_extension('PAGE_HEADER', 'seo42_utils::appendToPageHeader');
	}

	// check if seopage/urlpage needs to be enabled
	if (!$REX['ADDON']['seo42']['settings']['one_page_mode'] || ($REX['ADDON']['seo42']['settings']['one_page_mode'] && $REX['ARTICLE_ID'] == $REX['START_ARTICLE_ID'])) {
		if (isset($REX['USER']) && ($REX['USER']->isAdmin())) {
			// admins get everything :)
			seo42_utils::enableURLPage(); // injection order is important
			seo42_utils::enableSEOPage();
		} else {
			if (isset($REX['USER']) && $REX['USER']->hasPerm('seo42[url_default]')) {
				seo42_utils::enableURLPage();
			}

			if (isset($REX['USER']) && ($REX['USER']->hasPerm('seo42[seo_default]') || $REX['USER']->hasPerm('seo42[seo_extended]') || $REX['USER']->hasPerm('editContentOnly[]'))) {
				seo42_utils::enableSEOPage();
			}
		}
	}

	// fix article preview link as othewise not url types will show correct preview url
	rex_register_extension('PAGE_CONTENT_MENU', 'seo42_utils::fixArticlePreviewLink');

	// check for missing db fields after db import
	if (!$REX['SETUP']) {
		rex_register_extension('A1_AFTER_DB_IMPORT', 'seo42_utils::afterDBImport');
	}

	// if clang is added/deleted show message to the user that he should check his lang settings
	rex_register_extension('CLANG_ADDED', 'seo42_utils::showMsgAfterClangModified');
	rex_register_extension('CLANG_DELETED', 'seo42_utils::showMsgAfterClangModified');
	rex_register_extension('CLANG_UPDATED', 'seo42_utils::showMsgAfterClangModified');

	// don't clone seo data when new clang is added
	rex_register_extension('CLANG_ADDED', 'seo42_utils::emptySEODataAfterClangAdded');

	// inform user when article hat different url type
	if (rex_request('page') == 'content' && rex_request('mode') == 'edit' && rex_request('function') == '') {
		rex_register_extension('PAGE_CONTENT_OUTPUT', 'seo42_utils::showUrlTypeMsg');
	}
}

