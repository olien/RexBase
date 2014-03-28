<?php
$myself = 'seo42';
$myroot = $REX['INCLUDE_PATH'].'/addons/'.$myself;
$error = array();

// append lang file
$I18N->appendFile($REX['INCLUDE_PATH'] . '/addons/seo42/lang/');

// includes
require_once($REX['INCLUDE_PATH'] . '/addons/seo42/classes/class.seo42_utils.inc.php');

// check redaxo version
if (version_compare($REX['VERSION'] . '.' . $REX['SUBVERSION'] . '.' . $REX['MINORVERSION'], '4.4.1', '<=')) {
	$error[] = $I18N->msg('seo42_install_rex_version');
}

// check for concurrent addons
$disable_addons = array('url_rewrite', 'yrewrite', 'rexseo', 'rexseo42', 'resource_includer');

foreach ($disable_addons as $a) {
	if (OOAddon::isInstalled($a) || OOAddon::isAvailable($a)) {
		$error[] = $I18N->msg('seo42_install_concurrent') . ' ' . $a;
	}
}

// auto install plugins
$returnmsg = seo42_utils::autoInstallPlugins('seo42', array()); // no plugins yet to auto install

if ($returnmsg != '') {
	$error[] = $returnmsg;
}

// setup seo db fields
if (count($error) == 0) {
	$sql = new rex_sql();
	//$sql->debugsql = true;

	// IMPORTANT: if adding/removing db fields here, check also for uninstall.inc.php and seo42_utils::afterDBImport() and seo42::emptySEODataAfterClangAdded()
	$sql->setQuery('ALTER TABLE `' . $REX['TABLE_PREFIX'] . 'article` ADD `seo_title` TEXT, ADD `seo_description` TEXT, ADD `seo_keywords` TEXT, ADD `seo_custom_url` TEXT, ADD `seo_canonical_url` TEXT, ADD `seo_noindex` VARCHAR(1), ADD `seo_ignore_prefix` VARCHAR(1)');

	// redirects
	$sql->setQuery("
		CREATE TABLE IF NOT EXISTS `" . $REX['TABLE_PREFIX'] . "redirects` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`source_url` varchar(255) NOT NULL,
		`target_url` varchar(255) NOT NULL,
		PRIMARY KEY (`id`)
	);");

	// delete cache
	rex_generateAll();

	// restore redirects file if necessary
	seo42_utils::checkForRedirectsFile();

	// done!
	$REX['ADDON']['install'][$myself] = 1;
} else {
	$REX['ADDON']['installmsg'][$myself] = '<br />' . implode($error, '<br />');
	$REX['ADDON']['install'][$myself] = 0;
}

