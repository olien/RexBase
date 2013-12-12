<?php
if ($REX["ADDON"]["seo42"]["settings"]['drop_dbfields_on_uninstall']) {
	$sql = new rex_sql();
	//$sql->debugsql = true;

	// rex_article
	$sql->setQuery('ALTER TABLE `' . $REX['TABLE_PREFIX'] . 'article` DROP `seo_title`, DROP `seo_description`, DROP `seo_keywords`, DROP `seo_custom_url`, DROP `seo_canonical_url`, DROP `seo_noindex`, DROP `seo_ignore_prefix`');

	// rex_redirects
	$sql->setQuery('DROP TABLE IF EXISTS `' . $REX['TABLE_PREFIX'] . 'redirects`');
} else {
	echo rex_info($I18N->msg('seo42_uninstall_dbfields_not_removed'));
}

rex_generateAll();

$REX['ADDON']['install']['seo42'] = 0;
?>
