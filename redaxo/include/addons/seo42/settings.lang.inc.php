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

$REX['ADDON']['seo42']['settings']['lang'][42]['code'] = 'ru';
$REX['ADDON']['seo42']['settings']['lang'][42]['original_name'] = 'pусский';
$REX['ADDON']['seo42']['settings']['lang'][42]['rewrite_mode'] = SEO42_REWRITEMODE_URLENCODE;

$REX['ADDON']['seo42']['settings']['lang'][42]['code'] = 'zh';
$REX['ADDON']['seo42']['settings']['lang'][42]['original_name'] = '中国的';
$REX['ADDON']['seo42']['settings']['lang'][42]['rewrite_mode'] = SEO42_REWRITEMODE_INHERIT;
$REX['ADDON']['seo42']['settings']['lang'][42]['inherit_from_clang'] = 1;

$REX['ADDON']['seo42']['settings']['lang'][42]['code'] = 'es';
$REX['ADDON']['seo42']['settings']['lang'][42]['original_name'] = 'español';
$REX['ADDON']['seo42']['settings']['lang'][42]['rewrite_mode'] = SEO42_REWRITEMODE_SPECIAL_CHARS;
$REX['ADDON']['seo42']['settings']['lang'][42]['special_chars'] = 'Á|á|ç|É|é|Í|í|Ñ|ñ|Ó|ó|Ú|ú|ü|&';
$REX['ADDON']['seo42']['settings']['lang'][42]['special_chars_rewrite'] = 'A|a|c|E|e|I|i|N|n|O|o|U|u|u|y';

$REX['ADDON']['seo42']['settings']['lang'][42]['code'] = 'it';
$REX['ADDON']['seo42']['settings']['lang'][42]['original_name'] = 'italiano';
$REX['ADDON']['seo42']['settings']['lang'][42]['rewrite_mode'] = SEO42_REWRITEMODE_SPECIAL_CHARS;
$REX['ADDON']['seo42']['settings']['lang'][42]['special_chars'] = ' À|à|È|è|É|é|Ì|ì|Í|í|Ï|ï|Ò|ò|Ó|ó|Ù|ù|Ú|ú|&';
$REX['ADDON']['seo42']['settings']['lang'][42]['special_chars_rewrite'] = ' A|a|E|e|E|e|I|i|I|i|I|i|O|o|O|o|U|u|U|u|e';

$REX['ADDON']['seo42']['settings']['lang'][42]['code'] = 'da';
$REX['ADDON']['seo42']['settings']['lang'][42]['original_name'] = 'dansk';
$REX['ADDON']['seo42']['settings']['lang'][42]['rewrite_mode'] = SEO42_REWRITEMODE_SPECIAL_CHARS;
$REX['ADDON']['seo42']['settings']['lang'][42]['special_chars'] = 'Å|å|Æ|æ|Ø|ø|É|é|Á|á|&';
$REX['ADDON']['seo42']['settings']['lang'][42]['special_chars_rewrite'] = 'Aa|aa|Ae|ae|Oe|oe|E|e|A|a|og';

$REX['ADDON']['seo42']['settings']['lang'][42]['code'] = 'fr';
$REX['ADDON']['seo42']['settings']['lang'][42]['original_name'] = 'française';
$REX['ADDON']['seo42']['settings']['lang'][42]['rewrite_mode'] = SEO42_REWRITEMODE_SPECIAL_CHARS;
$REX['ADDON']['seo42']['settings']['lang'][42]['special_chars'] = 'À|à|Á|á|ç|È|è|É|é|ë|Ì|ì|Í|í|Ï|ï|Ò|ò|Ó|ó|Ù|ù|Ú|ú|&';
$REX['ADDON']['seo42']['settings']['lang'][42]['special_chars_rewrite'] = 'A|a|A|a|c|E|e|E|e|e|I|i|I|i|I|i|O|o|O|o|U|u|U|u|et';

$REX['ADDON']['seo42']['settings']['lang'][42]['code'] = 'pl';
$REX['ADDON']['seo42']['settings']['lang'][42]['original_name'] = 'polska';
$REX['ADDON']['seo42']['settings']['lang'][42]['rewrite_mode'] = SEO42_REWRITEMODE_SPECIAL_CHARS;
$REX['ADDON']['seo42']['settings']['lang'][42]['special_chars'] = 'À|à|Č|č|ć|È|è|É|é|Ì|ì|Í|í|Ï|ï|Ł|ł|Ò|ò|Ó|ó|Ù|ù|Ú|ú|ź|ż|ž|&';
$REX['ADDON']['seo42']['settings']['lang'][42]['special_chars_rewrite'] = 'A|a|C|c|c|E|e|E|e|I|i|I|i|I|i|L|l|O|o|O|o|U|u|U|u|z|z|z|i';

$REX['ADDON']['seo42']['settings']['lang'][42]['code'] = 'nl';
$REX['ADDON']['seo42']['settings']['lang'][42]['original_name'] = 'nederlands';
$REX['ADDON']['seo42']['settings']['lang'][42]['rewrite_mode'] = SEO42_REWRITEMODE_SPECIAL_CHARS;
$REX['ADDON']['seo42']['settings']['lang'][42]['special_chars'] = 'À|à|È|è|É|é|Ì|ì|Í|í|Ï|ï|Ò|ò|Ó|ó|Ù|ù|Ú|ú|&';
$REX['ADDON']['seo42']['settings']['lang'][42]['special_chars_rewrite'] = 'A|a|E|e|E|e|I|i|I|i|I|i|O|o|O|o|U|u|U|u|en';

$REX['ADDON']['seo42']['settings']['lang'][42]['code'] = 'cz';
$REX['ADDON']['seo42']['settings']['lang'][42]['original_name'] = 'české';
$REX['ADDON']['seo42']['settings']['lang'][42]['rewrite_mode'] = SEO42_REWRITEMODE_SPECIAL_CHARS;
$REX['ADDON']['seo42']['settings']['lang'][42]['special_chars'] = 'À|à|Č|č|ć|È|è|É|é|Ì|ì|Í|í|Ï|ï|Ł|ł|Ò|ò|Ó|ó|Ù|ù|Ú|ú|ź|ż|ž|&';
$REX['ADDON']['seo42']['settings']['lang'][42]['special_chars_rewrite'] = 'A|a|C|c|c|E|e|E|e|I|i|I|i|I|i|L|l|O|o|O|o|U|u|U|u|z|z|z|a';

$REX['ADDON']['seo42']['settings']['lang'][42]['code'] = 'pt';
$REX['ADDON']['seo42']['settings']['lang'][42]['original_name'] = 'português';
$REX['ADDON']['seo42']['settings']['lang'][42]['rewrite_mode'] = SEO42_REWRITEMODE_SPECIAL_CHARS;
$REX['ADDON']['seo42']['settings']['lang'][42]['special_chars'] = 'À|à|á|ã|ç|È|è|É|é|Ì|ì|Í|í|Ï|ï|Ò|ò|Ó|ó|Ù|ù|Ú|ú|&';
$REX['ADDON']['seo42']['settings']['lang'][42]['special_chars_rewrite'] = 'A|a|a|a|c|E|e|E|e|I|i|I|i|I|i|O|o|O|o|U|u|U|u|e';
*/

// ****************************************************************
// **  DELETE REDAXO CACHE AFTER YOU MADE CHANGES TO THIS FILE!  **
// ****************************************************************

