<?php

// ****************************************************************
// **  DELETE REDAXO CACHE AFTER YOU MADE CHANGES TO THIS FILE!  **
// ****************************************************************

// if true url rewriter for pretty, seo friendly urls will be active (recommended!).
$REX['ADDON']['seo42']['settings']['rewriter'] = true;

// if true seo page will be shown for articles. for non admins user right seo_default has to be given too.
$REX['ADDON']['seo42']['settings']['seopage'] = true;

// if true url page will be shown for articles. for non admins user right url_default has to be given too.
$REX['ADDON']['seo42']['settings']['urlpage'] = true;

// hides the title preview in seopage if false. only necessary if a different title schema is used and therefore title preview is unwanted
$REX['ADDON']['seo42']['settings']['title_preview'] = true;

// hides the no prefix/suffix checkbox in seopage if false. only necessary if a different title schema is used and therefore no prefix/suffix checkbox is needed
$REX['ADDON']['seo42']['settings']['no_prefix_checkbox'] = false;

// if true user can change canonical url via seo page. please use this only if you exactly know what you are doing or know that your redaxo users and admins exactly know what they are doing ;)
$REX['ADDON']['seo42']['settings']['custom_canonical_url'] = false;

// if true a noindex checkbox in seopage will be shown so that user will be able to set noindex robots flag for his articles
$REX['ADDON']['seo42']['settings']['noindex_checkbox'] = false;

// ATTENTION: only set to true if your website is live and domain of website should be indexed by google! if true page rank checker tool will be shown in tools section.
$REX['ADDON']['seo42']['settings']['pagerank_checker'] = true;

// if true alls available url types will be shown in select box in url page. set to false to hide url types that need to be treated in navigation code
$REX['ADDON']['seo42']['settings']['all_url_types'] = true;

// if true you get full urls like in wordpress :) seo42::getUrlStart() and co. needs to be used consequently for all extra urls (like urls to media files, etc.) | url_start option will be ignored by this
$REX['ADDON']['seo42']['settings']['full_urls'] = false;

// if true smart redirects like domain.de/foo/bar/ -> domain.de/foo/bar.html etc. will be enabled. default is false at it is still a experimental feature
$REX['ADDON']['seo42']['settings']['smart_redirects'] = true;

// array with category ids. all articles in this categoryies will get urltype "remove root cat". but only for articles added after setting was made.
$REX['ADDON']['seo42']['settings']['remove_root_cats_for_categories'] = array();

// default title delimiter (including whitespace chars) for seperating name of website and page title
$REX['ADDON']['seo42']['settings']['title_delimiter'] = '-';

// if true query params will be added to canonical url and rel alternate tags, but only if certain params not in ignore_query_params array
$REX['ADDON']['seo42']['settings']['include_query_params'] = true;

// array with query params that indicate a canonical url. possible notation: foo, foo=bar
$REX['ADDON']['seo42']['settings']['ignore_query_params'] = array();

// url start piece for all urls returned from rex_getUrl(), seo42::getUrlStart() and co.
$REX['ADDON']['seo42']['settings']['url_start'] = '/';

// for redaxo subdir installations: url start piece for all urls returned from rex_getUrl(), seo42::getUrlStart() and co.
$REX['ADDON']['seo42']['settings']['url_start_subdir'] = './';

// if true seo42::getImageManagerFile() and seo42::getImageTag() will produce seo friendly urls
$REX['ADDON']['seo42']['settings']['seo_friendly_image_manager_urls'] = true;

// if true seopage will be only visible at start article of website. also the frontend links will all point to start article and sitemap.xml will show only one url
$REX['ADDON']['seo42']['settings']['one_page_mode'] = false;

// if true root categories will be completly ignored and not be visible in generated urls (experimental)
$REX['ADDON']['seo42']['settings']['ignore_root_cats'] = false;

// character to replace whitespaces with in urls
$REX['ADDON']['seo42']['settings']['url_whitespace_replace']  = '-';

// default follow flag for robots meta tag, can be empty
$REX['ADDON']['seo42']['settings']['robots_follow_flag'] = 'follow';

// default archive flag for robots meta tag, can be empty
$REX['ADDON']['seo42']['settings']['robots_archive_flag'] = 'noarchive';

// if true website startarticle will have 1.0, all other articles will have 0.8 priority. if false priority gets calculated by category level.
$REX['ADDON']['seo42']['settings']['static_sitemap_priority'] = true;

// you can force download of certain filetypes. put file in files dir, add filetype to array e.g. 'pdf' and link to file like this: /download/foo.pdf or use seo42::getDownloadFile($file)
$REX['ADDON']['seo42']['settings']['force_download_for_filetypes'] = array();

// cache control header for image manager files is normally set through .htaccess, but on some servers (1und1) it won't be set correctly so we do it manually
$REX['ADDON']['seo42']['settings']['fix_image_manager_cache_control_header'] = false;

// if false seo database fields won't be dropped if seo42 will be uninstalled. perhaps someday interesting when updateing seo42...
$REX['ADDON']['seo42']['settings']['drop_dbfields_on_uninstall'] = true; 

// used to control which article should be used for debug output in help section, default is $REX['START_ARTICLE_ID']
$REX['ADDON']['seo42']['settings']['debug_article_id']  = $REX['START_ARTICLE_ID'];

// ****************************************************************
// **  DELETE REDAXO CACHE AFTER YOU MADE CHANGES TO THIS FILE!  **
// ****************************************************************

