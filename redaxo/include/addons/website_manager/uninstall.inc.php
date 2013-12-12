<?php

// add lang file
$I18N->appendFile($REX['INCLUDE_PATH'] . '/addons/website_manager/lang/');

// don't allow uninstall if website manager is active. otherwise codeline hint msg won't be shown.
if (!OOAddon::isActivated('website_manager')) {
	echo rex_warning($I18N->msg('website_manager_uninstall_activate_first'));	
	exit;
}

// don't allow uninstall if user still has code line in master.inc.php
if (isset($REX['WEBSITE_MANAGER_DO_UNINSTALL']) && !$REX['WEBSITE_MANAGER_DO_UNINSTALL']) {
	echo rex_warning($I18N->msg('website_manager_uninstall_codeline_hint'));
	exit;
}

$sql = new rex_sql();
//$sql->debugsql = true;

$sql->setQuery('DROP TABLE IF EXISTS `rex_website`');

$REX['ADDON']['install']['website_manager'] = 0;

