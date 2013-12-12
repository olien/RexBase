<?php

$REX['REDAXO'] = false;
$REX['ADDON']['seo42']['settings']['include_query_params'] = false;

$debugOut = seo42::getDebugInfo($REX['ADDON']['seo42']['settings']['debug_article_id']);

$REX['REDAXO'] = true;
$REX['ADDON']['seo42']['settings']['include_query_params'] = true;

if ($debugOut) {
	echo $debugOut;
} else {
	echo '<strong>' . $I18N->msg('seo42_help_debug_article_wrong') . '</strong>';
}

