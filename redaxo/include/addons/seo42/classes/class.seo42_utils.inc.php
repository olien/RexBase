<?php

define('SEO42_URL_TYPE_DEFAULT', 0); 
define('SEO42_URL_TYPE_INTERN_REPLACE_CLANG', 1); 
define('SEO42_URL_TYPE_USERDEF_INTERN', 2);
define('SEO42_URL_TYPE_MEDIAPOOL', 3);
define('SEO42_URL_TYPE_LANGSWITCH', 4); // should also be handled by navigation output.
define('SEO42_URL_TYPE_NONE', 5); // should also be handled by navigation output.
define('SEO42_URL_TYPE_REMOVE_ROOT_CAT', 6);
define('SEO42_URL_TYPE_INTERN_REPLACE', 7);
define('SEO42_URL_TYPE_CALL_FUNC', 8); // should also be handled by navigation output.
define('SEO42_URL_TYPE_USERDEF_EXTERN', 9);

define('SEO42_REWRITEMODE_SPECIAL_CHARS', 0);
define('SEO42_REWRITEMODE_URLENCODE', 1);
define('SEO42_REWRITEMODE_INHERIT', 2);

class seo42_utils {
	public static function appendToPageHeader($params) {
		global $REX;

		$insert = '<!-- BEGIN seo42 -->' . PHP_EOL;
		$insert .= '<link rel="stylesheet" type="text/css" href="../' . $REX['MEDIA_ADDON_DIR'] . '/seo42/seo42.css" />' . PHP_EOL;
		$insert .= '<link rel="stylesheet" type="text/css" href="../' . $REX['MEDIA_ADDON_DIR'] . '/seo42/qtip.css" />' . PHP_EOL;
		$insert .= '<script type="text/javascript" src="../' . $REX['MEDIA_ADDON_DIR'] . '/seo42/jquery.qtip.min.js"></script>' . PHP_EOL;
		$insert .= '<!-- END seo42 -->';
	
		return $params['subject'] . PHP_EOL . $insert;
	}

	public static function init($params) {
		global $REX;

		// init globals
		seo42::init();

		if ($REX['MOD_REWRITE']) {
			// includes
			require_once($REX['INCLUDE_PATH'] . '/addons/seo42/classes/class.rexseo_rewrite.inc.php');

			if ($REX['REDAXO']) { // this is only necessary for backend
				$extensionPoints = array(
					'CAT_ADDED',     'CAT_UPDATED',     'CAT_DELETED',
					'ART_ADDED',     'ART_UPDATED',     'ART_DELETED',        'ART_META_FORM_SECTION',
					'ART_TO_CAT',    'CAT_TO_ART',      'ART_TO_STARTPAGE',
					'CLANG_ADDED',   'CLANG_UPDATED',   'CLANG_DELETED',
					'ALL_GENERATED'
				);

				// generate pathlist on each extension point
				foreach($extensionPoints as $extensionPoint) {
					rex_register_extension($extensionPoint, 'rexseo_generate_pathlist');
				}
			}

			// init rewriter 
			$rewriter = new RexseoRewrite();
			$rewriter->resolve();

			// rewrite ep 
			rex_register_extension('URL_REWRITE', array ($rewriter, 'rewrite'));
		}

		// init current article
		seo42::initArticle($REX['ARTICLE_ID']);

		// controller
		include($REX['INCLUDE_PATH'] . '/addons/seo42/controller.inc.php');

		// rexseo post init
		rex_register_extension_point('REXSEO_INCLUDED');
	}

	public static function sendHeaders() {
		global $REX;

		// frontend
		if (!$REX['REDAXO']) {
			// set noindex header for robots if current article has noindex flag
			if (seo42::isArticleValid() && seo42::hasNoIndexFlag()) {
				header('X-Robots-Tag: noindex, noarchive');
			}

			// try fixing redaxo's errorarticle behaviour
			if (seo42::has404ResponseFlag() && $REX['START_ARTICLE_ID'] == $REX['NOTFOUND_ARTICLE_ID']) {
				header("HTTP/1.0 404 Not Found");
			}
		}
	}

	public static function afterDBImport($params) {
		global $REX, $I18N;

		$sqlStatement = 'SELECT seo_title, seo_description, seo_keywords, seo_custom_url, seo_canonical_url, seo_noindex, seo_ignore_prefix FROM ' . $REX['TABLE_PREFIX'] . 'article';
		$sql = rex_sql::factory();
		$sql->setQuery($sqlStatement);

		// check for db fields
		if ($sql->getRows() == 0) {
			require($REX['INCLUDE_PATH'] . '/addons/seo42/install.inc.php');
			echo rex_info($I18N->msg('seo42_dbfields_readded', $REX['ADDON']['name']['seo42']));
			echo rex_info($I18N->msg('seo42_dbfields_readded_check_setup', $REX['ADDON']['name']['seo42']));
		}
	}

	public static function showMsgAfterClangModified($params) {
		global $I18N, $REX;

		echo rex_info($I18N->msg('seo42_check_lang_msg', $REX['ADDON']['name']['seo42']));
	}

	public static function addSEOPageToPageContentMenu($params) {
		global $I18N;
			
		$class = "";

		if ($params['mode']  == 'seo') {
			$class = 'class="rex-active"';
		}

		$seoLink = '<a '.$class.' href="index.php?page=content&amp;article_id=' . $params['article_id'] . '&amp;mode=seo&amp;clang=' . $params['clang'] . '&amp;ctype=' . rex_request('ctype') . '">' . $I18N->msg('seo42_seopage_linktext') . '</a>';
		array_splice($params['subject'], '1', '0', $seoLink);

		return $params['subject'];
	}

	public static function addSEOPageToPageContentOutput($params) {
		global $REX, $I18N;

		if ($params['mode']  == 'seo') {
			include($REX['INCLUDE_PATH'] . '/addons/seo42/pages/seopage.inc.php');
		}
	}

	public static function addURLPageToPageContentMenu($params) {
		global $I18N;
			
		$class = "";

		if ($params['mode']  == 'url') {
			$class = 'class="rex-active"';
		}

		$seoLink = '<a ' . $class . ' href="index.php?page=content&amp;article_id=' . $params['article_id'] . '&amp;mode=url&amp;clang=' . $params['clang'] . '&amp;ctype=' . rex_request('ctype') . '">' . $I18N->msg('seo42_urlpage_linktext') . '</a>';
		array_splice($params['subject'], '1', '0', $seoLink);

		return $params['subject'];
	}

	public static function addURLPageToPageContentOutput($params) {
		global $REX, $I18N;

		if ($params['mode']  == 'url') {
			include($REX['INCLUDE_PATH'] . '/addons/seo42/pages/urlpage.inc.php');
		}
	}

	public static function enableSEOPage() {
		global $REX;

		if ($REX['ADDON']['seo42']['settings']['seopage']) {
			rex_register_extension('PAGE_CONTENT_MENU', 'seo42_utils::addSEOPageToPageContentMenu');
			rex_register_extension('PAGE_CONTENT_OUTPUT', 'seo42_utils::addSEOPageToPageContentOutput');
		}
	}

	public static function enableURLPage() {
		global $REX;

		if ($REX['ADDON']['seo42']['settings']['urlpage'] && $REX['ADDON']['seo42']['settings']['rewriter']) {
			rex_register_extension('PAGE_CONTENT_MENU', 'seo42_utils::addURLPageToPageContentMenu');
			rex_register_extension('PAGE_CONTENT_OUTPUT', 'seo42_utils::addURLPageToPageContentOutput');
		}
	}

	public static function fixArticlePreviewLink($params) {
		global $REX;

		$lastElement = count($params['subject']) - 1;

		if ($REX['ADDON']['seo42']['settings']['one_page_mode'] && $REX['ARTICLE_ID'] != $REX['START_ARTICLE_ID']) {
			// one page mode link to frontend
			$newUrl = seo42::getFullUrl($REX['START_ARTICLE_ID']);
		} else {
			$newUrl = seo42::getFullUrl();
		}

		$params['subject'][$lastElement] = preg_replace("/(?<=href=(\"|'))[^\"']+(?=(\"|'))/", $newUrl, $params['subject'][$lastElement]);

		return $params['subject'];
	}

	public static function sanitizeString($string) {
		return trim(preg_replace("/\s\s+/", " ", $string));
	}

	public static function sanitizeUrl($url) {
		return preg_replace('@^https?://|/.*|[^\w.-]@', '', $url);
	}

	// untested
	public static function getServerUrl() {
		$url = $_SERVER['REQUEST_URI'];
		$parts = explode('/',$url);
		$serverUrl = 'http://' . $_SERVER['SERVER_NAME'];

		for ($i = 0; $i < count($parts) - 2; $i++) {
			$serverUrl .= $parts[$i] . "/";
		}

		return $serverUrl;
	}

	public static function detectSubDir() {
		if ($_SERVER['PHP_SELF'] == '/redaxo/index.php') {
			return false;
		} else {
			return true;
		}
	}

	public static function print_r_pretty($arr, $first = true, $tab=0) {
		$output = "";
		$tabsign = ($tab) ? str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;',$tab) : '';

		if ($first) $output .= "<pre>";

		foreach($arr as $key => $val)
		{
		    switch (gettype($val))
		    {
		        case "array":
					if (empty($val)) {
			            $output .= $tabsign."[".htmlspecialchars($key)."] => ".$tabsign."";
					} else {
			            $output .= $tabsign."[".htmlspecialchars($key)."] => <br>".$tabsign."(<br>";
					}

		            $tab++;
		            $output .= self::print_r_pretty($val,false,$tab);
		            $tab--;

					if (empty($val)) {
			            $output .= $tabsign."<br>";
					} else {
			            $output .= $tabsign.")<br>";
					}
		        break;
		        case "boolean":
		            $output .= $tabsign."[".htmlspecialchars($key)."] => ".($val?"true":"false")."<br>";
		        break;
		        case "integer":
		            $output .= $tabsign."[".htmlspecialchars($key)."] => ".htmlspecialchars($val)."<br>";
		        break;
		        case "double":
		            $output .= $tabsign."[".htmlspecialchars($key)."] => ".htmlspecialchars($val)."<br>";
		        break;
		        case "string":
		            $output .= $tabsign."[".htmlspecialchars($key)."] => ".((stristr($key,'passw')) ? str_repeat('*', strlen($val)) : htmlspecialchars($val))."<br>";
		        break;
		        default:
		            $output .= $tabsign."[".htmlspecialchars($key)."] => ".htmlspecialchars(gettype($val))."<br>";
		        break;
		    }
		}

		if ($first) $output .= "</pre>";

		return $output;
	}

	public static function autoInstallPlugins($addon, $plugins) {
		// take from xform install routine :)
		$msg = '';

		// GET ALL ADDONS & PLUGINS
		$all_addons = rex_read_addons_folder();
		$all_plugins = array();

		foreach ($all_addons as $_addon) {
			$all_plugins[$_addon] = rex_read_plugins_folder($_addon);
		}

		// DO AUTOINSTALL
		$pluginManager = new rex_pluginManager($all_plugins, $addon);

		foreach ($plugins as $pluginname) {
			if (in_array($pluginname, $all_plugins[$addon])) { // check if plugin that should be auto install really exists in addon folder
				// INSTALL PLUGIN
				if (($instErr = $pluginManager->install($pluginname)) !== true) {
					$msg = $instErr;
				}

				// ACTIVATE PLUGIN
				if ($msg == '' && ($actErr = $pluginManager->activate($pluginname)) !== true) {
					$msg = $actErr;
				}

				if ($msg != '') {
					break;
				}
			}
		}

		return $msg;
	}

	public static function isJson($string) {
		json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE);
	}

	public static function containsString($haystack, $needle) {
		$pos = strpos($haystack, $needle);

		if ($pos === false) {
			return false;
		} else {
			return true;
		}
	}

	public static function stringStartsWith($haystack, $startString) {
		if (strpos($haystack, $startString) === 0) {
			return true;
		} else {
			return false;
		}
	}

	public static function showUrlTypeMsg($params) {
		global $REX, $I18N;

		$currentArticle = OOArticle::getArticleById($REX['ARTICLE_ID']);
		$urlField = $currentArticle->getValue('seo_custom_url');
		$articleId = $currentArticle->getValue('id');
		$clangId = $currentArticle->getValue('clang');
		$msg = '';
		$isError = false;

		$urlData = seo42_utils::getUrlTypeData($urlField);
		$jsonData = json_decode($urlData, true);	

		if ($REX['CUR_CLANG'] != $REX['START_CLANG_ID']) {
			$currentArticleDefaultLang = OOArticle::getArticleById($REX['ARTICLE_ID'], $REX['START_CLANG_ID']);
			$data = json_decode($currentArticleDefaultLang->getValue('seo_custom_url'), true);

			if (isset($data['url_clone']) && $data['url_clone'] == true) {
				$jsonData = $data;
			}
		}
		
		if (isset($jsonData['url_type'])) {
			switch ($jsonData['url_type']) {
				case SEO42_URL_TYPE_INTERN_REPLACE:
					$customArticleId = $jsonData['article_id'];
					$article = OOArticle::getArticleById($customArticleId);

					if (OOArticle::isValid($article)) {
						$msg = $I18N->msg('seo42_urltype_intern') . ' <a href="index.php?page=content&article_id=' . $customArticleId . '&mode=edit&clang=' . $REX['CUR_CLANG'] . '">' . $article->getName() . '</a>';
					} else {
						$msg = $I18N->msg('seo42_urltype_error');
						$isError = true;
					}

					break;
				case SEO42_URL_TYPE_INTERN_REPLACE_CLANG:
					$customArticleId = $jsonData['article_id'];
					$customClangId = $jsonData['clang_id'];
					$article = OOArticle::getArticleById($customArticleId, $customClangId);

					if (OOArticle::isValid($article)) {
						$msg = $I18N->msg('seo42_urltype_intern_plus_clang', '<a href="index.php?page=content&article_id=' . $customArticleId . '&mode=edit&clang=' . $customClangId . '">' . $article->getName() . '</a>', $REX['CLANG'][$customClangId]);
					} else {
						$msg = $I18N->msg('seo42_urltype_error');
						$isError = true;
					}

					break;
				case SEO42_URL_TYPE_USERDEF_INTERN:
					// do nothing
					break;
				case SEO42_URL_TYPE_USERDEF_EXTERN:
					$customUrl = $jsonData['custom_url'];

					if (seo42_utils::stringStartsWith($customUrl, 'javascript:')) {
						$msg = $I18N->msg('seo42_urltype_userdef_javascript');
					} else {
						$msg = $I18N->msg('seo42_urltype_userdef') . ': <a href="' . $customUrl . '" target="_blank">' . $customUrl . '</a>';
					}

					break;
				case SEO42_URL_TYPE_MEDIAPOOL:
					$customUrl = '/' . $REX['MEDIA_DIR'] . '/' . $jsonData['file'];

					$msg = $I18N->msg('seo42_urltype_mediapool', '<a href="' . $customUrl . '" target="_blank">' . $jsonData['file'] . '</a>');;

					break;
				case SEO42_URL_TYPE_LANGSWITCH:
					$newClangId = $jsonData['clang_id'];

					$msg = $I18N->msg('seo42_urltype_langswitch', $REX['CLANG'][$newClangId]);

					break;
				case SEO42_URL_TYPE_NONE:
					$msg = $I18N->msg('seo42_urltype_none');

					break;
				case SEO42_URL_TYPE_REMOVE_ROOT_CAT:
					// do nothing

					break;
				case SEO42_URL_TYPE_CALL_FUNC:
					if (isset($jsonData['no_url']) && $jsonData['no_url']) {
						$msg = $I18N->msg('seo42_urltype_none');
					}

					break;
				default:
				case SEO42_URL_TYPE_DEFAULT:
					// do nothing

					break;
			}
		}

		if ($msg != '') {
			echo '	<style type=text/css>
						.rex-form-content-editmode, 
						.rex-content-editmode-module-name { 
							display: none; 
						} 

						div.rex-content-editmode-slice-output { 
							border-bottom-width: 0; 
						} 

						.rex-info p,
						.rex-warning p { 
							padding: 4px 0 2px 0; 
						}
					</style>';

			if ($isError) {
				echo rex_warning($msg);
			} else {
				echo rex_info($msg);
			}
		}
	}

	public static function isInternalCustomUrl($url) {
		$urlParts = parse_url($url);

		if (isset($urlParts['scheme'])) {
			return false;
		} else {
			return true;
		}
	}

	public static function getUrlTypeData($urlField) {
		if (seo42_utils::isJson($urlField)) {
			return $urlField;
		} else {
			// compat
			return json_encode(array('url_type' => SEO42_URL_TYPE_USERDEF_INTERN, 'custom_url' => $urlField));
		}
	}

	public static function isAllowedDomain() {
		global $REX;

		$domains = json_decode($REX['ADDON']['seo42']['settings']['allowed_domains'], true);
		
		if ($domains != NULL && in_array(seo42::getServerWithSubDir(), $domains)) {
			return true;
		} else {
			return false;
		}
	}

	public static function removeRootCatFromUrl($curUrl, $clangId) {
		global $REX;

		$newUrl = '';

		if ($REX['ADDON']['seo42']['settings']['hide_langslug'] == $clangId) {
			$pos = strpos($curUrl, '/');

			if ($pos !== false) {
				$newUrl = substr($curUrl, $pos + 1);
			}
		} else {
			$pos1 = strpos($curUrl, '/');
			$pos2 = strpos($curUrl, '/', $pos1 + 1);

			if ($pos2 !== false) {
				$langSlug = substr($curUrl, 0, $pos1 + 1);
				$restUrl = substr($curUrl, $pos2 + 1);
				$newUrl = $langSlug . $restUrl;
			}
		}
		
		return $newUrl;
	}

	public static function getHtmlFromMDFile($mdFile, $search = array(), $replace = array()) {
		global $REX;

		$curLocale = strtolower($REX['LANG']);

		if ($curLocale == 'de_de') {
			$file = $REX['INCLUDE_PATH'] . '/addons/seo42/' . $mdFile;
		} else {
			$file = $REX['INCLUDE_PATH'] . '/addons/seo42/lang/' . $curLocale . '/' . $mdFile;
		}

		if (file_exists($file)) {
			$md = file_get_contents($file);
			$md = str_replace($search, $replace, $md);
			$md = seo42_utils::makeHeadlinePretty($md);

			$parser = new Michelf\Markdown;
			return $parser->transform($md);
		} else {
			return '[translate:' . $file . ']';
		}
	}

	public static function makeHeadlinePretty($md) {
		return str_replace('SEO42 - ', '', $md);
	}

	public static function strposa($haystack, $needle, $offset = 0) {
		if (!is_array($needle)) $needle = array($needle);

		foreach ($needle as $query) {
		    if (strpos($haystack, $query, $offset) !== false) return true; // stop on first true result
		}

		return false;
	}

	public static function createDynFile($file) {
		$fileHandle = fopen($file, 'w');

		fwrite($fileHandle, "<?php\r\n");
		fwrite($fileHandle, "// --- DYN\r\n");
		fwrite($fileHandle, "// --- /DYN\r\n");

		fclose($fileHandle);
	}

	public static function updateRobotsFile($content) {
		global $REX;

		$robotsContent = '';
		$robotsFile = self::getRobotsFile();

		if (!file_exists($robotsFile)) {
			self::createDynFile($robotsFile);
		}

		// file content
		$robotsContent .= '$REX[\'ADDON\'][\'seo42\'][\'settings\'][\'robots\'] = \'' . $content . '\';' . PHP_EOL;

	  	rex_replace_dynamic_contents($robotsFile, $robotsContent);
	}

	public static function includeRobotsSettings() {
		global $REX;

		$robotsFile = self::getRobotsFile();

		if (file_exists($robotsFile)) {
			include($robotsFile);
		} else {
			$REX['ADDON']['seo42']['settings']['robots'] = '';
		}
	}

	public static function updateRedirectsFile() {
		global $REX;

		$redirectsContent = '';
		$redirectsFile = self::getRedirectsFile();

		if (!file_exists($redirectsFile)) {
			self::createDynFile($redirectsFile);
		}

		// file content
		$redirectsContent .= '$REX[\'SEO42_REDIRECTS\'] = array(' . PHP_EOL;

		$sql = rex_sql::factory();
		//$sql->debugsql = true;
		$sql->setQuery('SELECT * FROM ' . $REX['TABLE_PREFIX'] . 'redirects');

		for ($i = 0; $i < $sql->getRows(); $i++) {
			$redirectsContent .= "\t" . '"' . $sql->getValue('source_url') . '" => "' . $sql->getValue('target_url') . '"';
		
			if ($i < $sql->getRows() - 1) {
				$redirectsContent .= ', ' . PHP_EOL;
			}

			$sql->next();
		}

		$redirectsContent .= PHP_EOL . ');' . PHP_EOL;

	  	if (rex_replace_dynamic_contents($redirectsFile, $redirectsContent)) {
			return true;
		} else {
			return false;
		}
	}

	public static function redirect() {
		global $REX;

		$redirectsFile = self::getRedirectsFile();

		if (file_exists($redirectsFile)) {
			include($redirectsFile);

			if (isset($REX['SEO42_REDIRECTS']) && count($REX['SEO42_REDIRECTS']) > 0) {
				seo42::init(); 

				if (seo42::isSubDirInstall()) {
					// remove subdir from request uri
					$requestUri = '/' . ltrim($_SERVER['REQUEST_URI'], '/' . seo42::getServerSubDir());
				} else {
					$requestUri = $_SERVER['REQUEST_URI'];
				}

				if (array_key_exists($requestUri, $REX['SEO42_REDIRECTS'])) {
					if (seo42::isSubDirInstall()) {
						// add subdir to target url
						$targetUrl = '/' . seo42::getServerSubDir() . $REX['SEO42_REDIRECTS'][$requestUri];
					} else {
						$targetUrl = $REX['SEO42_REDIRECTS'][$requestUri];
					}

					if (strpos($targetUrl, 'http') === false) {
						$location = seo42::getServerUrl()  . ltrim($targetUrl, '/');
					} else {
						$location = $targetUrl;
					}
		
					header('HTTP/1.1 301 Moved Permanently');
				 	header('Location: ' . $location);

					exit;
				}
			}
		}
	}

	public static function getCacheFile($cacheFile) {
		global $REX;

		if (isset($REX['WEBSITE_MANAGER']) && $REX['WEBSITE_MANAGER']->getCurrentWebsiteId() != 1) {
			$file = $cacheFile . $REX['WEBSITE_MANAGER']->getCurrentWebsiteId() . '.inc.php';
		} else {
			$file = $cacheFile . '.inc.php';
		}

		return $REX['INCLUDE_PATH'] . '/addons/seo42/generated/' . $file;
	}

	public static function getRedirectsFile() {
		return self::getCacheFile('redirects');
	}

	public static function getRobotsFile() {
		return self::getCacheFile('robots');
	}

	public static function requestUriFix() { // for iis only
		if (!isset($_SERVER['REQUEST_URI'])) {
			$_SERVER['REQUEST_URI'] = substr($_SERVER['PHP_SELF'], 1);

			if (isset($_SERVER['QUERY_STRING'])) {
				$_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
			}
		}
	}

	public static function getInheritedClang($clang) {
		global $REX;

		if (isset($REX['ADDON']['seo42']['settings']['lang'][$clang]['rewrite_mode']) && isset($REX['ADDON']['seo42']['settings']['lang'][$clang]['inherit_from_clang']) && $REX['ADDON']['seo42']['settings']['lang'][$clang]['rewrite_mode'] == SEO42_REWRITEMODE_INHERIT) {
			return $REX['ADDON']['seo42']['settings']['lang'][$clang]['inherit_from_clang'];
		} else {
			return $clang;
		}
	}

	public static function checkForRedirectsFile() {
		global $REX, $I18N;

		$sql = rex_sql::factory();
		//$sql->debugsql = true;
		$sql->setQuery('SELECT * FROM ' . $REX['TABLE_PREFIX'] . 'redirects');

		if ($sql->getRows() > 0 && !file_exists(self::getRedirectsFile())) {
			if (self::updateRedirectsFile()) {
				echo rex_info($I18N->msg('seo42_redirect_restore_cachefile_ok', '/seo42/generated/' . basename(self::getRedirectsFile())));
			} else {
				echo rex_warning($I18N->msg('seo42_redirect_restore_cachefile_fail', '/seo42/generated/' . basename(self::getRedirectsFile())));
			}
		}
	}

	public static function str_replace_first($search, $replace, $subject) {
		$pos = strpos($subject, $search);

		if ($pos !== false) {
		    $subject = substr_replace($subject, $replace, $pos, strlen($search));
		}

		return $subject;
	}

	public static function str_replace_last($search, $replace, $subject) {
		$pos = strrpos($subject, $search);

		if ($pos !== false) {
		    $subject = substr_replace($subject, $replace, $pos, strlen($search));
		}

		return $subject;
	}

	public static function getCustomUrl($urlWithoutSlash) {
		return '/' . $urlWithoutSlash;
	}

	public static function hasHtaccessSubDirRewriteBase() {
		global $REX;

		$rewriteBaseWithoutSubDir = 'RewriteBase /';
		$rewriteBaseStartString = 'RewriteBase';
		$htaccessRoot = $REX['FRONTEND_PATH'] . '/.htaccess';

		if (file_exists($htaccessRoot)) {
			$line = self::getLineWithString($htaccessRoot, $rewriteBaseStartString);

			if ($line != -1) {
				$htaccessRewriteBase = trim($line, " \t\r\n");
				$commentPos = strpos($htaccessRewriteBase, '#');

				if ($commentPos === false && $rewriteBaseWithoutSubDir != $htaccessRewriteBase) {
					return true;
				}
			}
		}

		return false;
	}

	public static function getLineWithString($fileName, $str) {
		$lines = file($fileName);

		foreach ($lines as $lineNumber => $line) {
		    if (strpos($line, $str) !== false) {
		        return $line;
		    }
		}

		return -1;
	}

	public static function getLangSettingsFile() {
		global $REX, $I18N;

		if (!isset($REX['ADDON']['seo42']['settings']['lang']) || seo42::getLangCount() != count($REX['ADDON']['seo42']['settings']['lang'])) {
			$icon = '<span title="' . $I18N->msg('seo42_setup_langfile_error') . '" class="seo42-tooltip status exclamation">&nbsp;</span>';
		} else {
			$icon = '';
		}

		return '<span class="rex-form-read" id="lang_hint"><code>/seo42/settings.lang.inc.php</code></span>' . $icon;
	}

	public static function emptySEODataAfterClangAdded($params) {
		global $REX;

		$newClangId = $params['id'];
		
		$sql = rex_sql::factory();
		//$sql->debugsql = true;
		$sql->setQuery('UPDATE ' . $REX['TABLE_PREFIX'] . 'article SET seo_title = "", seo_description = "", seo_keywords = "", seo_custom_url = "", seo_canonical_url = "", seo_noindex = "", seo_ignore_prefix = "" WHERE clang = ' . $newClangId);
	}
}
