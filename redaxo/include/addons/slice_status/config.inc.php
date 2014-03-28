<?php
// slice status addon
$REX['ADDON']['rxid']['slice_status'] = '1022';
$REX['ADDON']['name']['slice_status'] = 'Slice Status'; // <-- comment out this line if you don't want to see the backend page
$REX['ADDON']['page']['slice_status'] = 'slice_status';
$REX['ADDON']['version']['slice_status'] = '2.1.0';
$REX['ADDON']['author']['slice_status'] = "RexDude";
$REX['ADDON']['supportpage']['slice_status'] = 'forum.redaxo.de';
$REX['ADDON']['perm']['slice_status'] = 'slice_status[]';

$REX['PERM'][] = 'slice_status[]';
$REX['EXTPERM'][] = 'publishSlice[]';

// includes
require_once($REX['INCLUDE_PATH'] . '/addons/slice_status/settings.inc.php');
require_once($REX['INCLUDE_PATH'] . '/addons/slice_status/classes/class.rex_slice_status.inc.php');

if ($REX['REDAXO']) {
	// add lang file
	$I18N->appendFile($REX['INCLUDE_PATH'] . '/addons/slice_status/lang/');

	// update slice status in db if necessary (used for ajax and non-ajax status switching)
	if (isset($REX['USER']) && rex_request('function') == 'updateslicestatus') {
		rex_slice_status::updateSliceStatus(rex_get('article_id'), rex_get('clang'), rex_get('slice_id'), rex_get('new_status'));
	
		if (rex_request('mode') == 'ajax') {
			exit;
		}
	}

	// handle slice menu
	if (is_object($REX['USER']) && ((!$REX['USER']->hasPerm('editContentOnly[]') && $REX['USER']->hasPerm('publishSlice[]') || $REX['USER']->isAdmin()))) {
		rex_register_extension('ART_SLICE_MENU', 'rex_slice_status::modifySliceEditMenu');
	}

	// add css/js files to page header
	if (rex_request('page') == 'content' && rex_request('mode') == 'edit') {
		rex_register_extension('PAGE_HEADER', 'rex_slice_status::appendToPageHeader');
	}

	// check for missing db field after db import
	if (!$REX['SETUP']) {
		rex_register_extension('A1_AFTER_DB_IMPORT', 'rex_slice_status::afterDBImport');
	}

	// fix for version addon
	if (rex_request('rex_version_func') != '') {
		rex_register_extension('ARTICLE_GENERATED', 'rex_slice_status::versionAddonFix');
	}
}

// handle slice visibility in frontend
rex_register_extension('SLICE_SHOW', 'rex_slice_status::sliceShow');

