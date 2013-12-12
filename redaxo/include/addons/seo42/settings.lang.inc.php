<?php

// ****************************************************************
// **  DELETE REDAXO CACHE AFTER YOU MADE CHANGES TO THIS FILE!  **
// ****************************************************************

// GLOBAL SPECIAL CHAR REWRITE
// used for rewriting special chars that are language dependent. valid for all languages.
// separate values by | (pipe) symbol

$REX['ADDON']['seo42']['settings']['global_special_chars'] = '';
$REX['ADDON']['seo42']['settings']['global_special_chars_rewrite'] = '';

// URLENCODE SETTINGS
// only used when lang settings are set to SEO42_REWRITEMODE_URLENCODE

$REX['ADDON']['seo42']['settings']['urlencode_lowercase'] = false;
$REX['ADDON']['seo42']['settings']['urlencode_whitespace_replace']  = '_';

// LANGUAGE SPECIFIC SETTINGS
// if no additional languages are defined in this array, redaxo language settings will be taken
// rewrite modes: 
//   * SEO42_REWRITEMODE_SPECIAL_CHARS --> specify also: special_chars, special_chars_rewrite
//   * SEO42_REWRITEMODE_INHERIT --> specify also: inherit_from_clang
//   * SEO42_REWRITEMODE_URLENCODE
// separate special_chars values by | (pipe) symbol
// special chars language tables: http://unicode.e-workers.de/
// collection of language presets: https://github.com/RexDude/seo42/issues/61

$REX['ADDON']['seo42']['settings']['lang'][0]['code'] = 'de';
$REX['ADDON']['seo42']['settings']['lang'][0]['original_name'] = 'deutsch';
$REX['ADDON']['seo42']['settings']['lang'][0]['rewrite_mode'] = SEO42_REWRITEMODE_SPECIAL_CHARS;
$REX['ADDON']['seo42']['settings']['lang'][0]['special_chars'] = 'Ä|ä|Ö|ö|Ü|ü|ß|&';
$REX['ADDON']['seo42']['settings']['lang'][0]['special_chars_rewrite'] = 'Ae|ae|Oe|oe|Ue|ue|ss|und';

/*
$REX['ADDON']['seo42']['settings']['lang'][1]['code'] = 'en';
$REX['ADDON']['seo42']['settings']['lang'][1]['original_name'] = 'english';
$REX['ADDON']['seo42']['settings']['lang'][1]['rewrite_mode'] = SEO42_REWRITEMODE_SPECIAL_CHARS;
$REX['ADDON']['seo42']['settings']['lang'][1]['special_chars'] = '&';
$REX['ADDON']['seo42']['settings']['lang'][1]['special_chars_rewrite'] = 'and';

$REX['ADDON']['seo42']['settings']['lang'][2]['code'] = 'el';
$REX['ADDON']['seo42']['settings']['lang'][2]['original_name'] = 'ελληνικά';
$REX['ADDON']['seo42']['settings']['lang'][2]['rewrite_mode'] = SEO42_REWRITEMODE_URLENCODE;

$REX['ADDON']['seo42']['settings']['lang'][3]['code'] = 'zh';
$REX['ADDON']['seo42']['settings']['lang'][3]['original_name'] = '中国的';
$REX['ADDON']['seo42']['settings']['lang'][3]['rewrite_mode'] = SEO42_REWRITEMODE_INHERIT;
$REX['ADDON']['seo42']['settings']['lang'][3]['inherit_from_clang'] = 1;

$REX['ADDON']['seo42']['settings']['lang'][4]['code'] = 'es';
$REX['ADDON']['seo42']['settings']['lang'][4]['original_name'] = 'español';
$REX['ADDON']['seo42']['settings']['lang'][4]['rewrite_mode'] = SEO42_REWRITEMODE_SPECIAL_CHARS;
$REX['ADDON']['seo42']['settings']['lang'][4]['special_chars'] = 'Á|á|ç|É|é|Í|í|Ñ|ñ|Ó|ó|Ú|ú|ü|&';
$REX['ADDON']['seo42']['settings']['lang'][4]['special_chars_rewrite'] = 'A|a|c|E|e|I|i|N|n|O|o|U|u|u|y';

$REX['ADDON']['seo42']['settings']['lang'][5]['code'] = 'it';
$REX['ADDON']['seo42']['settings']['lang'][5]['original_name'] = 'italiano';
$REX['ADDON']['seo42']['settings']['lang'][5]['rewrite_mode'] = SEO42_REWRITEMODE_SPECIAL_CHARS;
$REX['ADDON']['seo42']['settings']['lang'][5]['special_chars'] = ' À|à|È|è|É|é|Ì|ì|Í|í|Ï|ï|Ò|ò|Ó|ó|Ù|ù|Ú|ú|&';
$REX['ADDON']['seo42']['settings']['lang'][5]['special_chars_rewrite'] = ' A|a|E|e|E|e|I|i|I|i|I|i|O|o|O|o|U|u|U|u|e';
*/

// ****************************************************************
// **  DELETE REDAXO CACHE AFTER YOU MADE CHANGES TO THIS FILE!  **
// ****************************************************************

