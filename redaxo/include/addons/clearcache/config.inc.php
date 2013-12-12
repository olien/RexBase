<?php
if ($REX['REDAXO']) {
	// init addon
	$REX['ADDON']['name']['clearcache'] = 'Cache löschen';
	$REX['ADDON']['page']['clearcache'] = 'clearcache';
	$REX['ADDON']['version']['clearcache'] = '1.0.1';
	$REX['ADDON']['author']['clearcache'] = 'RexDude';
	$REX['ADDON']['supportpage']['clearcache'] = 'forum.redaxo.de';
	$REX['ADDON']['perm']['clearcache'] = 'clearcache[]';

	// permissions
	$REX['PERM'][] = 'clearcache[]';

	// add lang file
	$I18N->appendFile($REX['INCLUDE_PATH'] . '/addons/clearcache/lang/');
}
?>
