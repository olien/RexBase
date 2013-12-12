<?php

$search = array('(CHANGELOG.md)', '(LICENSE.md)', '(FAQ.md)');
$replace = array('(index.php?page=seo42&subpage=help&chapter=changelog)', '(index.php?page=seo42&subpage=help&chapter=license)', '(index.php?page=seo42&subpage=help&chapter=faq)');

echo seo42_utils::getHtmlFromMDFile('README.md', $search, $replace);

