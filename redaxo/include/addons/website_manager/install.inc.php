<?php

// append lang file
$I18N->appendFile($REX['INCLUDE_PATH'] . '/addons/website_manager/lang/');

// check redaxo version
if (version_compare($REX['VERSION'] . '.' . $REX['SUBVERSION'] . '.' . $REX['MINORVERSION'], '4.4.1', '<=')) {
	// version incorrect
	$REX['ADDON']['installmsg']['website_manager'] = $I18N->msg('website_manager_install_rex_version'); 
} elseif (OOPlugin::isAvailable('be_utilities', 'colorizer')) {
	// colorizer plugin
	$REX['ADDON']['installmsg']['website_manager'] = $I18N->msg('website_manager_install_colorizer');
} elseif (OOPlugin::isAvailable('be_utilities', 'frontend_link')) {
	// prontend_link plugin
	$REX['ADDON']['installmsg']['website_manager'] = $I18N->msg('website_manager_install_frontend_link');  
} elseif (OOPlugin::isAvailable('be_style', 'customizer') && (isset($REX['ADDON']['be_style']['plugin_customizer']['labelcolor']) && $REX['ADDON']['be_style']['plugin_customizer']['labelcolor'] != '') || (isset($REX['ADDON']['be_style']['plugin_customizer']['showlink']) && $REX['ADDON']['be_style']['plugin_customizer']['showlink'] == 1)) {
	// customizer plugin
	$REX['ADDON']['installmsg']['website_manager'] = $I18N->msg('website_manager_install_customizer'); 
} else {
	// version correct. proceed...
	require_once($REX['INCLUDE_PATH'] . '/addons/website_manager/classes/class.rex_website.inc.php');
	require_once($REX['INCLUDE_PATH'] . '/addons/website_manager/classes/class.rex_website_manager.inc.php');
	require_once($REX['INCLUDE_PATH'] . '/addons/website_manager/classes/class.rex_website_manager_utils.inc.php');

	$firstWebsiteId = rex_website::firstId;
	$firstWebsiteColor = rex_website::defaultColor;
	$firstTablePrefix = rex_website::tablePrefix;
	$firstWebsiteProtocol = rex_website::defaultProtocol;
	$defaultThemeId = rex_website::defaultThemeId;

	$sql = new rex_sql();
	//$sql->debugsql = true;

	$sql->setQuery('CREATE TABLE IF NOT EXISTS `rex_website` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`domain` varchar(255) NOT NULL, 
		`title` varchar(255) NOT NULL,
		`start_article_id` int(11) NOT NULL,
		`notfound_article_id` int(11) NOT NULL,
		`default_template_id` int(11) NOT NULL,
		`table_prefix` varchar(255) NOT NULL,
		`protocol` varchar(255) NOT NULL,
		`color` varchar(255) NOT NULL,
		`priority` INT(11) NOT NULL,
		`theme_id` int(11) default 0,
		PRIMARY KEY (`id`)
	) ENGINE=MyISAM;');

	$sql->setQuery('INSERT INTO `rex_website` VALUES (1, "' . rex_website_manager_utils::sanitizeUrl($REX['SERVER']) . '", "' . $REX['SERVERNAME'] . '", ' . $REX['START_ARTICLE_ID'] . ', ' . $REX['NOTFOUND_ARTICLE_ID'] . ', ' . $REX['DEFAULT_TEMPLATE_ID'] . ', "' . $firstTablePrefix . '", "' . $firstWebsiteProtocol  . '", "' . $firstWebsiteColor . '", 1, ' . $defaultThemeId . ')');                                                                                

	$error = $sql->getError();

	//if ($error == '') {
		rex_website_manager::updateInitFile();
		rex_website_manager::fixClang(null);

		$REX['ADDON']['install']['website_manager'] = 1;
	//} else {
	//	$REX['ADDON']['installmsg']['website_manager'] = $error;
	//}
}
?>
