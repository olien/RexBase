<?php
class rex_website {
	protected $id;
	protected $domain;
	protected $title;
	protected $startArticleId;
	protected $notFoundArticleId;
	protected $defaultTemplateId;
	protected $color;
	protected $tablePrefix;
	protected $protocol;
	protected $themeId;
	protected $theme; 
	protected $permission;

	const firstId = 1;
	const mediaDir = 'files';
	const generatedDir = 'generated';
	const tablePrefix = 'rex_';
	const permissionPrefix = 'website';
	const defaultColor = '#47a0ce';
	const defaultProtocol = 'http';
	const defaultThemeId = 0; // no theme specified

	public function __construct($id, $domain, $title, $startArticleId, $notFoundArticleId, $defaultTemplateId, $color, $tablePrefix = self::tablePrefix, $protocol = self::defaultProtocol, $themeId = self::defaultThemeId) {
		$this->id = $id;
		$this->domain = $domain;
		$this->title = $title;
		$this->startArticleId = $startArticleId;
		$this->notFoundArticleId = $notFoundArticleId;
		$this->defaultTemplateId = $defaultTemplateId;
		$this->color = $color;
		$this->tablePrefix = $tablePrefix;
		$this->protocol = $protocol;
		$this->themeId = $themeId;
		$this->theme = new rex_website_theme($themeId);
		$this->permission = self::permissionPrefix . '[' . $id . ']';
	}

	public function getId() {
		return $this->id;
	}

	public function getDomain() {
		return $this->domain;
	}

	public function getTitle() {
		return $this->title;
	}

	public function getStartArticleId() {
		return $this->startArticleId;
	}

	public function getNotFoundArticleId() {
		return $this->notFoundArticleId;
	}

	public function getDefaultTemplateId() {
		return $this->defaultTemplateId;
	}

	public function getGeneratedDir() {
		return self::constructGeneratedDir($this->id);
	}

	public function getGeneratedPath() {
		global $REX;

		return realpath($REX['HTDOCS_PATH'] . 'redaxo/include/' . $this->getGeneratedDir()); // path needs to exist, otherwise realpath won't return anything
	}

	public function getMediaDir() {
		return self::constructMediaDir($this->id);
	}

	public function getMediaPath() {
		global $REX;

		return realpath($REX['HTDOCS_PATH'] . $this->getMediaDir());
	}

	public function getClangFile() {
		return self::constructClangFile($this->id);
	}

	public function getTablePrefix() {
		return $this->tablePrefix;
	}

	public function getProtocol() {
		return $this->protocol;
	}

	public function getUrl() {
		return $this->getProtocol() . '://' . $this->getDomain() . '/';
	}

	public function getIcon() {
		return self::constructIconFile($this->color);
	}

	public function getIconUrl() {
		global $REX;

		return '../' . $REX['MEDIA_ADDON_DIR'] . '/website_manager/' . $this->getIcon();
	}

	public function getColor() {
		return $this->color;
	}

	public function getPermission() {
		return $this->permission;
	}

	public static function constructGeneratedDir($websiteId) {
		if ($websiteId == self::firstId) {
			return self::generatedDir;
		} else {
			return self::generatedDir . $websiteId;
		}
	}

	public static function constructMediaDir($websiteId) {
		global $REX;

		if ($websiteId == self::firstId || $REX['WEBSITE_MANAGER_SETTINGS']['identical_media']) {
			return self::mediaDir;
		} else {
			return self::mediaDir . $websiteId;
		}
	}

	public static function constructClangFile($websiteId) {
		global $REX;

		if ($websiteId == self::firstId || $REX['WEBSITE_MANAGER_SETTINGS']['identical_clangs']) {
			return 'fix.clang.inc.php';
		} else {
			return 'fix.clang' . $websiteId . '.inc.php';
		}
	}

	public static function constructIconFile($color) {
		return 'favicon-' . ltrim($color, '#') . '.png';
	}

	public static function constructTablePrefix($websiteId) {
		if ($websiteId == self::firstId) {
			return self::tablePrefix;
		} else {
			return str_replace('_', $websiteId . '_', self::tablePrefix);
		}
	}

	public function generateAll() {
		global $REX;

		// set rex vars for this website
		$this->switchRexVars();

		// do generate all
		rex_generateAll();

		// restore rex vars for current website
		$REX['WEBSITE_MANAGER']->getCurrentWebsite()->switchRexVars();

		// temporary workaround for rexseo/seo42 master website empty pathlist problem
		rex_generateAll();
	}

	public function getArticle($articleId, $clangId = null) {
		global $REX;

		// set rex vars for this website
		$this->switchRexVars();

		// get article content
		$article = new rex_article($articleId, $clangId);
		$articleContent = $article->getArticle();

		// restore rex vars for current website
		$REX['WEBSITE_MANAGER']->getCurrentWebsite()->switchRexVars();

		return $articleContent;
	}

	public function getSlice($sliceId, $clangId = false) {
		global $REX;

		// set rex vars for this website
		$this->switchRexVars();

		// get article content
		$slice = OOArticleSlice::getArticleSliceById($sliceId, $clangId);
		$sliceContent = $slice->getSlice();

		// restore rex vars for current website
		$REX['WEBSITE_MANAGER']->getCurrentWebsite()->switchRexVars();

		return $sliceContent;
	}

	public function switchRexVars() {
		global $REX;

		$REX['MEDIA_DIR'] = $this->getMediaDir();
		$REX['MEDIAFOLDER'] = $this->getMediaPath();
		$REX['GENERATED_PATH'] = $this->getGeneratedPath();
		$REX['TABLE_PREFIX'] = $this->getTablePrefix();
	}

	public function getTheme() {
		return $this->theme;
	}
}

