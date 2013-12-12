<?php
class seo42 {
	protected static $curArticle;
	protected static $startArticleID;
	protected static $titleDelimiter;
	protected static $robotsFollowFlag;
	protected static $robotsArchiveFlag;
	protected static $mediaDir;
	protected static $mediaAddonDir;
	protected static $seoFriendlyImageManagerUrls;
	protected static $fullUrls;
	protected static $serverUrl;
	protected static $websiteName;
	protected static $server;
	protected static $serverProtocol;
	protected static $serverSubDir;
	protected static $isSubDirInstall;
	protected static $urlStart;
	protected static $rewriterEnabled;
	protected static $is404Response;
	protected static $ignoreQueryParams;
	
	public static function init() {
		// to be called before resolve()
		global $REX;

		// default inits
		self::$startArticleID = $REX['START_ARTICLE_ID'];
		self::$titleDelimiter = $REX['ADDON']['seo42']['settings']['title_delimiter'];
		self::$robotsFollowFlag = $REX['ADDON']['seo42']['settings']['robots_follow_flag'];
		self::$robotsArchiveFlag = $REX['ADDON']['seo42']['settings']['robots_archive_flag'];
		self::$mediaDir = $REX['MEDIA_DIR'];
		self::$mediaAddonDir = $REX['MEDIA_ADDON_DIR'];
		self::$seoFriendlyImageManagerUrls = $REX['ADDON']['seo42']['settings']['seo_friendly_image_manager_urls'];
		self::$serverUrl = $REX['SERVER'];
		self::$websiteName = $REX['SERVERNAME'];
		self::$rewriterEnabled = $REX['ADDON']['seo42']['settings']['rewriter'];
		self::$fullUrls = $REX['ADDON']['seo42']['settings']['full_urls'];
		self::$is404Response = false; // will be set from outside by set404ResponseFlag()
		self::$ignoreQueryParams = $REX['ADDON']['seo42']['settings']['ignore_query_params'];

		// pull apart server url
		$urlParts = parse_url(self::$serverUrl);

		if (isset($urlParts['scheme'])) {
			self::$serverProtocol = $urlParts['scheme'];
		} else {
			self::$serverProtocol = 'http';
		}

		if (isset($urlParts['host'])) {
			self::$server = $urlParts['host'];
		} else {
			self::$server = self::$serverUrl;
		}

		if (isset($urlParts['path']) && isset($urlParts['scheme'])) { // if scheme is empty don't count on path as possible subdir 
			self::$serverSubDir = trim($urlParts['path'], '/'); 
		} else {
			self::$serverSubDir = '';
		}

		// check for subdir install
		if (self::$serverSubDir == '') {
			self::$isSubDirInstall = false;
		} else {
			self::$isSubDirInstall = true;
		}

		// set url start 
		if (self::$fullUrls) {
			// full worpresslike urls
			self::$urlStart = self::$serverUrl;
		} else {
			// use url start specified in settings
			if (self::$isSubDirInstall) {
				// url start for subdirs
				self::$urlStart = $REX['ADDON']['seo42']['settings']['url_start_subdir'];
			} else {
				// url start for normal redaxo installations
				self::$urlStart = $REX['ADDON']['seo42']['settings']['url_start'];
			}
		}
	}

	public static function initArticle($articleId) {
		// to be called after resolve()
		global $REX;

		self::$curArticle = OOArticle::getArticleById($articleId);
	}

	public static function isArticleValid() {
		if (is_object(self::$curArticle)) {
			return true;
		} else {
			return false;
		}
	}

	public static function getBaseUrl() {
		return self::$serverUrl;
	}

	public static function getTitle($websiteName = '') {
		if ($websiteName == '') {
			// use default website name if user did not set different one
			$websiteName = self::getWebsiteName();
		}
	
		if (self::getArticleValue('seo_title') == '') {
			// use article name as title
			$titlePart = self::getArticleName();
		} else {
			// use title that user defined
			$titlePart = self::getArticleValue('seo_title');
		}
		
		if (self::getArticleValue('seo_ignore_prefix') == '1') {
			// no prefix, just the title
			$fullTitle = $titlePart;
		} else { 
			if (self::isStartArticle()) {
				// the start article shows the website name first
				$fullTitle = $websiteName . ' ' . self::getTitleDelimiter() . ' ' . $titlePart;
			} else {
				// all other articles will show title first
				$fullTitle = $titlePart . ' ' . self::getTitleDelimiter() . ' ' . $websiteName;
			}
		 }

		return htmlspecialchars($fullTitle);
	}

	public static function getDescription() {
		return htmlspecialchars(self::$curArticle->getValue('seo_description'));
	}

	public static function getKeywords() {	
		return htmlspecialchars(self::$curArticle->getValue('seo_keywords'));
	}

	public static function hasNoIndexFlag() {
		$startArticle = OOArticle::getArticleById(self::$startArticleID);

		if (OOArticle::isValid($startArticle) && $startArticle->getValue('seo_noindex') == '1') {
			$noIndexSite = true;
		} else {
			$noIndexSite = false;
		}

		if (self::$curArticle->getValue('seo_noindex') == '1' || $noIndexSite) { 
			return true;
		} else {
			return false;
		}
	}

	public static function getRobotRules() { 
		if (self::has404ResponseFlag()) {
			return 'noindex, nofollow, noarchive';
		}

		if (self::hasNoIndexFlag()) { 
			$robots = "noindex";
		} else {
			$robots = "index";
		}
		
		if (self::$robotsFollowFlag != '') {
			$robots .= ", " . self::$robotsFollowFlag;
		}

		if (self::$robotsArchiveFlag != '') {
			$robots .= ", " . self::$robotsArchiveFlag;
		}

		return $robots;
	}

	public static function getCanonicalUrl() {
		global $REX;

		if (self::has404ResponseFlag()) {
			return '';
		}

		if (self::$curArticle->getValue('seo_canonical_url') != '') {
			// userdef canonical url
			return self::$curArticle->getValue('seo_canonical_url');
		}

		// automatic canonical url
		return self::getFullUrl(self::$curArticle->getId()) . self::getQueryString();
	}

	public static function getQueryString() {
		global $REX;

		// check if query string exists and parameters that shoud be ignored are not in params array
		if ($REX['ADDON']['seo42']['settings']['include_query_params'] && isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] != '' && seo42_utils::strposa($_SERVER['QUERY_STRING'], self::$ignoreQueryParams) === false) {
			return '?' . htmlspecialchars($_SERVER['QUERY_STRING']);
		} else {
			return '';
		}
	}  

	public static function getLangTags($indent = "\t") {
		global $REX;

		$out = '';
		$i = 0;

		if (self::has404ResponseFlag()) {
			return '';
		}

		foreach ($REX['CLANG'] as $clangId => $clangName) {
			$article = OOArticle::getArticleById(self::$curArticle->getId(), $clangId);

			if ($article->isOnline() || $REX['CUR_CLANG'] == $clangId) {
				$hreflang = self::getLangCode($clangId);

				if ($i > 0) {
					$out .= $indent;
				}

				$out .= '<link rel="alternate" href="' . self::getFullUrl(self::$curArticle->getId(), $clangId)  . self::getQueryString() . '" hreflang="' . $hreflang . '" />' . PHP_EOL;

				$i++;
			}
		}

		return $out;
	}

	public static function getLangCount() {
		global $REX;

		return count($REX['CLANG']);
	}

	public static function isMultiLangInstall() {
		if (self::getLangCount() > 1) {
			return true;
		} else {
			return false;
		}
	}

	public static function getImageTag($imageFile, $imageType = '', $width = 0, $height = 0) {
		$media = OOMedia::getMediaByFileName($imageFile);

		// make sure media object is valid
		if (OOMedia::isValid($media)) {
			$mediaWidth = $media->getWidth();
			$mediaHeight = $media->getHeight();
			$altAttribute = $media->getTitle();
		} else {
			$mediaWidth = '';
			$mediaHeight = '';
			$altAttribute = '';
		}

		// image width
		if ($width == 0) {
			$imgWidth = $mediaWidth;
		} else {
			$imgWidth = $width;
		}

		// image height
		if ($height == 0) {
			$imgHeight = $mediaHeight;
		} else {
			$imgHeight = $height;
		}

		// get url
		if ($imageType == '') {
			$url = self::getMediaFile($imageFile);
		} else {
			$url = self::getImageManagerUrl($imageFile, $imageType);
		}

		return '<img src="' . $url . '" width="' . $imgWidth . '" height="' . $imgHeight . '" alt="' . $altAttribute . '" />';
	}

	public static function getImageManagerUrl($imageFile, $imageType) {
		global $REX;
	
		if ($REX['REDAXO']) {
			// important for website manager: run image manager through backend index.php
			return $REX['HTDOCS_PATH'] . 'redaxo/index.php?rex_img_type=' . $imageType . '&amp;rex_img_file=' . $imageFile;
		} else {
			if (self::$seoFriendlyImageManagerUrls && self::$rewriterEnabled) {
				return self::getUrlStart() . self::$mediaDir . '/imagetypes/' . $imageType . '/' . $imageFile;
			} else {
				return self::getUrlStart() . 'index.php?rex_img_type=' . $imageType . '&amp;rex_img_file=' . $imageFile;
			}
		}
	}

	public static function getHtml($indent = "\t") {
		$out = '';

		$out .= '<base href="' . self::getBaseUrl() . '" />' . PHP_EOL;
		$out .= $indent . '<title>' . self::getTitle() . '</title>' . PHP_EOL;
		$out .= $indent . '<meta name="description" content="' . self::getDescription() . '" />' . PHP_EOL;
		$out .= $indent . '<meta name="keywords" content="' . self::getKeywords() . '" />' . PHP_EOL;
		$out .= $indent . '<meta name="robots" content="' . self::getRobotRules() . '" />' . PHP_EOL;
		$out .= $indent . '<link rel="canonical" href="' . self::getCanonicalUrl() . '" />' . PHP_EOL;

		if (self::isMultiLangInstall()) {
			$out .= $indent . self::getLangTags($indent);
		}

		return $out;
	}

	public static function getTitleDelimiter() {
		return self::$titleDelimiter;
	}

	public static function getArticleName() {
		return self::$curArticle->getName();
	}

	public static function getArticleValue($key) {
		return self::$curArticle->getValue($key);
	}
	
	public static function getWebsiteName() {
		return self::$websiteName;
	}

	public static function setWebsiteName($name) {
		self::$websiteName = $name;
	}
	
	public static function getLangCode($clangId = -1) {
		global $REX;

		if ($clangId == -1) {
			$clangId = $REX['CUR_CLANG'];
		}

		if (isset($REX['ADDON']['seo42']['settings']['lang'][$clangId]['code'])) {
			return $REX['ADDON']['seo42']['settings']['lang'][$clangId]['code'];
		} else {
			return $REX['CLANG'][$clangId];
		}
	}

	public static function getLangSlug($clangId) {
		global $REX;

		if (isset($REX['ADDON']['seo42']['settings']['lang'][$clangId]['code'])) {
			$langSlug = self::getLangCode($clangId);
	
			if (strlen($langSlug) > 2) {
				$langSlug = substr($langSlug, 0, 2);
			}
		} else {
			$langSlug = self::getLangName($clangId);
		}

		return strtolower($langSlug);
	}

	public static function getLangName($clangId = -1) {
		global $REX;

		if ($clangId == -1) {
			$clangId = $REX['CUR_CLANG'];
		}

		return $REX['CLANG'][$clangId];
	}

	public static function getOriginalLangName($clangId = -1) {
		global $REX;

		if ($clangId == -1) {
			$clangId = $REX['CUR_CLANG'];
		}

		if (isset($REX['ADDON']['seo42']['settings']['lang'][$clangId]['original_name'])) {
			return $REX['ADDON']['seo42']['settings']['lang'][$clangId]['original_name'];
		} else {
			return $REX['CLANG'][$clangId];
		}
	}

	public static function set404ResponseFlag($enabled = true) {
		self::$is404Response = $enabled;
	}

	public static function has404ResponseFlag() {
		return self::$is404Response;
	}

	public static function hasFullUrlFlag() {
		return self::$fullUrls;
	}

	public static function getServer() {
		return self::$server;
	}

	public static function getServerUrl() {
		return self::$serverUrl;
	}

	public static function getServerProtocol() {
		return self::$serverProtocol;
	}

	public static function getServerSubDir() {
		return self::$serverSubDir;
	}

	public static function isSubDirInstall() {
		return self::$isSubDirInstall;
	}

	public static function getServerWithSubDir() {
		if (self::$isSubDirInstall) {
			return self::$server . '/' . self::$serverSubDir;
		} else {
			return self::$server;
		}
	}

	public static function getUrlStart() {
		return self::$urlStart;
	}

	public static function setUrlStart($urlStart) {
		self::$urlStart = $urlStart;
	}

	public static function getMediaDir() {
		global $REX;

		if ($REX['REDAXO']) {
			return $REX['HTDOCS_PATH'] . self::$mediaDir . '/';
		} else {
			return self::getUrlStart() . self::$mediaDir . '/';
		}
	}

	public static function getMediaFile($file) {
		return self::getMediaDir() . $file;
	}

	public static function getMediaAddonDir() {
		global $REX;

		return self::$urlStart . self::$mediaAddonDir . '/';
	}

	public static function isStartArticle() {
		if (self::$curArticle->getId() == self::$startArticleID) {
			return true;
		} else {
			return false;
		}
	}

	public static function getFullUrl($id = '', $clang = '', $params = '', $divider = '&amp;') {
		$url = self::getTrimmedUrl($id, $clang, $params, $divider);
		
		if (seo42_utils::isInternalCustomUrl($url)) {
			return self::getBaseUrl() . $url;
		} else {
			return $url;
		}
	}

	public static function getTrimmedUrl($id = '', $clang = '', $params = '', $divider = '&amp;') {
		return self::trimUrl(rex_getUrl($id, $clang, $params, $divider));
	}

	public static function trimUrl($url) {
		if (self::$fullUrls) {
			return str_replace(self::getServerUrl(), '', $url);
		} else {
			return ltrim($url, "./");
		}
	} 

	public static function getDebugInfo($articleId = 0) {
		global $I18N, $REX;

		if ($articleId != 0) {
			self::initArticle($articleId);			
		}

		if (!OOArticle::isValid(self::$curArticle)) {
			return '';
		}

		$out = '<div id="seo42-debug">';

		$out .= '<h1>---------- SEO42 DEBUG BEGIN ----------<h1>';

		// general information
		$out .= '<h2>General Information</h2>';
		$out .= '<table>';
		$out .= '<tr><td class="left"><code>REDAXO Version</code></td><td class="right"><code>' . $REX['VERSION'] . '.' . $REX['SUBVERSION'] . '.' . $REX['MINORVERSION'] . '</code></td></tr>';
		$out .= '<tr><td class="left"><code>SEO42 Version</code></td><td class="right"><code>' . $REX['ADDON']['version']['seo42'] . '</code></td></tr>';
		$out .= '<tr><td class="left"><code>PHP Version</code></td><td class="right"><code>' . phpversion() . '</code></td></tr>';
		$out .= '</table>';

		// methods
		$out .= '<h2>Class Methods</h2>';
		$out .= '<table>';

		$out .= self::getDebugInfoRow('rex_getUrl', array(self::$curArticle->getId()));
		$out .= self::getDebugInfoRow('seo42::getTrimmedUrl', array(self::$curArticle->getId()));
		$out .= self::getDebugInfoRow('seo42::getFullUrl', array(self::$curArticle->getId()));
		$out .= self::getDebugInfoRow('seo42::getTitle');
		$out .= self::getDebugInfoRow('seo42::getDescription');
		$out .= self::getDebugInfoRow('seo42::getKeywords');
		$out .= self::getDebugInfoRow('seo42::getRobotRules');
		$out .= self::getDebugInfoRow('seo42::getCanonicalUrl');
		$out .= self::getDebugInfoRow('seo42::getArticleName');
		$out .= self::getDebugInfoRow('seo42::isStartArticle');
		$out .= self::getDebugInfoRow('seo42::getWebsiteName');
		$out .= self::getDebugInfoRow('seo42::getLangCode', array('0'));
		$out .= self::getDebugInfoRow('seo42::getLangSlug', array('0'));
		$out .= self::getDebugInfoRow('seo42::getLangName', array('0'));
		$out .= self::getDebugInfoRow('seo42::getOriginalLangName', array('0'));
		$out .= self::getDebugInfoRow('seo42::getServerProtocol');
		$out .= self::getDebugInfoRow('seo42::getBaseUrl');
		$out .= self::getDebugInfoRow('seo42::getServerUrl');
		$out .= self::getDebugInfoRow('seo42::getServer');
		$out .= self::getDebugInfoRow('seo42::getServerWithSubDir');
		$out .= self::getDebugInfoRow('seo42::getServerSubDir');
		$out .= self::getDebugInfoRow('seo42::hasTemplateBaseTag');
		$out .= self::getDebugInfoRow('seo42::isSubDirInstall');
		$out .= self::getDebugInfoRow('seo42::isMultiLangInstall');
		$out .= self::getDebugInfoRow('seo42::getLangCount');
		$out .= self::getDebugInfoRow('seo42::getTitleDelimiter');
		$out .= self::getDebugInfoRow('seo42::getUrlStart');
		$out .= self::getDebugInfoRow('seo42::has404ResponseFlag');
		$out .= self::getDebugInfoRow('seo42::getQueryString');
		$out .= self::getDebugInfoRow('seo42::getMediaDir');
		$out .= self::getDebugInfoRow('seo42::getMediaFile', array('image.png'));
		$out .= self::getDebugInfoRow('seo42::getMediaAddonDir');
		$out .= self::getDebugInfoRow('seo42::getLangTags');
		$out .= self::getDebugInfoRow('seo42::getHtml');
		$out .= self::getDebugInfoRow('seo42::getImageTag', array('image.png', 'rex_mediapool_detail', '150', '100'));
		$out .= self::getDebugInfoRow('seo42::getImageManagerUrl', array('image.png', 'rex_mediapool_detail'));
		$out .= self::getDebugInfoRow('seo42::getAnswer');

		$out .= '</table>';

		// settings
		$out .= '<h2>Settings</h2>';

		$out .= '<pre class="rex-code">';
		$out .= seo42_utils::print_r_pretty($REX['ADDON']['seo42']['settings'], true);
		$out .= '</pre>';

		// pathlist
		$out .= '<h2>Pathlist</h2>';

		$pathlistRoot = REXSEO_PATHLIST;

		if (file_exists($pathlistRoot)) {
			$content = rex_get_file_contents($pathlistRoot);
			$out .= rex_highlight_string($content, true);
		} else {
			$out .= 'File not found: ' . $pathlistRoot;
		}

		// redirects
		$redirectsRoot = seo42_utils::getRedirectsFile();

		if (file_exists($redirectsRoot)) {
			$out .= '<h2>Redirects</h2>';
			$content = rex_get_file_contents($redirectsRoot);
			$out .= rex_highlight_string($content, true);
		}

		// .htaccess
		$out .= '<h2>.htaccess</h2>';

		$htaccessRoot = $REX['FRONTEND_PATH'] . '/.htaccess';

		if (file_exists($htaccessRoot)) {
			$content = rex_get_file_contents($htaccessRoot);
			$out .= rex_highlight_string($content, true);
		} else {
			$out .= 'File not found: ' . $htaccessRoot;
		}

		$out .= '<h1>---------- SEO42 DEBUG END ----------</h1>';

		$out .= '</div>';

		$out .= '<style type="text/css">
					#seo42-debug h1 {
						font-size: 16px;
						margin: 10px 0;
					}

					#seo42-debug h2 {
						margin: 15px 0;
						font-size: 14px;
					}

					#seo42-debug .rex-code {
						border: 1px solid #F2353A;
					}

					#seo42-debug code,
					#seo42-debug .rex-code {
						color: #000;
						background: #FAF9F5;
					}

					#seo42-debug table {
						width: 100%;
						border-collapse: collapse;
						border-spacing: 0;
						background: #FAF9F5;
					}

					#seo42-debug table th,
					#seo42-debug table thead td {
						font-weight: bold;
					}

					#seo42-debug table td, 
					#seo42-debug table th {
						padding: 12px;
						border: 1px solid #F2353A;
						text-align: left;
					}

					#seo42-debug table td.left {
						width: 280px;
					}
				</style>';

		return $out;
	}

	protected static function getDebugInfoRow($func, $params = array()) {
		$out = '';

		// build function and params for function call
		$function = $func . '(';

		for ($i = 0; $i < count($params); $i++) {
			if (!is_numeric($params[$i])) {
				$function .= '"';
			}

			$function .= $params[$i];

			if (!is_numeric($params[$i])) {
				$function .= '"';
			}


			if (isset($params[$i + 1])) {
				$function .= ', ';
			}
		}

		$function .= ')';

		// convert for bool values to more human readable
		$returnValue = call_user_func_array($func, $params);
		
		if (is_bool($returnValue)) {
			if ($returnValue) {
				$returnValue = 'true';
			} else {
				$returnValue = 'false';
			}
		}

		$out .= '<tr>';
		$out .= '<td class="left"><code>' . $function . '</code></td>';
		$out .= '<td class="right"><code>' . htmlspecialchars($returnValue) . '</code></td>';
		$out .= '</tr>';

		return $out;
	}

	public static function getCustomUrlData($catObj) {
		return json_decode($catObj->getValue('seo_custom_url'), true);
	}

	public static function hasTemplateBaseTag() {
		global $REX;

		$sqlStatement = 'SELECT * FROM ' . $REX['TABLE_PREFIX'] . 'template WHERE `content` LIKE "%seo42::getBaseUrl()%" OR `content` LIKE "%seo42::getHtml()%"';
		$sql = rex_sql::factory();
		//$sql->debugsql = true;
		$sql->setQuery($sqlStatement);

		if ($sql->getRows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public static function getAnswer() {
		return '42';
	}
}
