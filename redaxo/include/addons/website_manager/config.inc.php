<?php
// init addon
$REX['ADDON']['name']['website_manager'] = 'Website Manager';
$REX['ADDON']['page']['website_manager'] = 'website_manager';
$REX['ADDON']['version']['website_manager'] = '1.3.0';
$REX['ADDON']['author']['website_manager'] = "RexDude";
$REX['ADDON']['supportpage']['website_manager'] = 'forum.redaxo.de';
$REX['ADDON']['perm']['website_manager'] = 'website_manager[]';

// permissions
$REX['PERM'][] = 'website_manager[]';

// front and backend includes
require_once($REX['INCLUDE_PATH'] . '/addons/website_manager/classes/class.rex_website_manager_utils.inc.php');

if ($REX['REDAXO'] && !$REX['SETUP']) {
	// backend includes
	require_once($REX['INCLUDE_PATH'] . '/addons/website_manager/classes/class.klogger.inc.php');
	require_once($REX['INCLUDE_PATH'] . '/addons/website_manager/settings.inc.php');
	
	// add lang file
	$I18N->appendFile($REX['INCLUDE_PATH'] . '/addons/website_manager/lang/');

	// check for existence of website manager object
	if (isset($REX['WEBSITE_MANAGER'])) {
		// used for addon uninstall to stop user from uninstallig when wm codeline ist still in master.inc.php
		$REX['WEBSITE_MANAGER_DO_UNINSTALL'] = false;

		// add subpages
		$REX['ADDON']['website_manager']['SUBPAGES'] = array(
			array('', $I18N->msg('website_manager_websites'))
		);

		if (OOPlugin::isAvailable('website_manager', 'themes')) {
			array_push($REX['ADDON']['website_manager']['SUBPAGES'], array('themes', $I18N->msg('website_manager_themes')));
		}

		array_push($REX['ADDON']['website_manager']['SUBPAGES'], 
			array('tools', $I18N->msg('website_manager_tools')),
			array('options', $I18N->msg('website_manager_options')),
			array('setup', $I18N->msg('website_manager_setup')),
			array('help', $I18N->msg('website_manager_help'))
		);
	} else {
		// this is only neccesary until user has put this code line in master.inc.php
		require_once($REX['INCLUDE_PATH'] . '/addons/website_manager/generated/init.inc.php');

		// used for addon uninstall to stop user from uninstallig when wm codeline ist still in master.inc.php
		$REX['WEBSITE_MANAGER_DO_UNINSTALL'] = true;
	
		// add only setup subpage
		$REX['ADDON']['website_manager']['SUBPAGES'] = array(
			array('', $I18N->msg('website_manager_setup'))
		);
	}

	if (rex_request('page') != '') { // login
		// check permissions (has to be done here because $REX['USER'] is not availabe in master.inc.php)
		$REX['WEBSITE_MANAGER']->checkPermissions();

		// add css/js to page header
		rex_register_extension('PAGE_HEADER', 'rex_website_manager_utils::appendToPageHeader');

		if (rex_request('install') != '1') { // this shoudn't go off when addon gets installed
			// add website select and other stuff
			rex_register_extension('OUTPUT_FILTER', 'rex_website_manager_utils::addToOutputFilter');
		}

		// frontend link in meta menu 
		if ($REX['WEBSITE_MANAGER_SETTINGS']['show_metamenu_frontend_link']) {
			rex_register_extension('META_NAVI', 'rex_website_manager_utils::addFrontendLinkToMetaMenu');
		}

		// fix article preview link
		rex_register_extension('PAGE_CONTENT_MENU', 'rex_website_manager_utils::fixArticlePreviewLink');

		// fix clang
		rex_register_extension('CLANG_ADDED', 'rex_website_manager::fixClang');
		rex_register_extension('CLANG_DELETED', 'rex_website_manager::fixClang');
	}

	// init sortable rex list with prio switch
	rex_website_manager_utils::initPrioSwitch();
}
