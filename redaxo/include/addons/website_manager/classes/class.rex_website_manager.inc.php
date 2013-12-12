<?php
class rex_website_manager {
	protected $websites;
	protected $currentWebsiteId;
	
	public function __construct() {
		$this->websites = array();
		$this->currentWebsiteId = rex_website::firstId;
	}

	public function setCurrentWebsiteId($id) {
		$this->currentWebsiteId = $id;
	}

	public function getCurrentWebsiteId() {
		return $this->currentWebsiteId;
	}

	public function getMasterWebsiteId() {
		return rex_website::firstId;
	}

	public function getWebsiteCount() {
		return count($this->websites);
	}

	function addWebsite($site) {
		$this->websites[$site->getId()] = $site;
	}

	public function getWebsites() {
		return $this->websites;
	}

	public function getWebsite($id) {
		 return $this->websites[$id];
	}

	public function getCurrentWebsite() {
		return $this->websites[$this->currentWebsiteId];
	}

	public function getMasterWebsite() {
		return $this->websites[$this->getMasterWebsiteId()];
	}

	public function init($websiteId = 0) {
		global $REX;

		if ($websiteId == 0) {
			// auto detect website id
			if ($REX['REDAXO']) {
				// backend
				$websiteId = $this->getWebsiteIdForBackend();
			} else {
				// frontend
				$websiteId = $this->getWebsiteIdForFrontend();
			}
		}

		$this->setCurrentWebsiteId($websiteId);
		$this->setRexVars();
		$this->includeClangFile();
		$this->addPermissions();

		$this->getCurrentWebsite()->getTheme()->init();
	}

	protected function addPermissions() {
		global $REX;

		foreach ($this->websites as $website) {
			$REX['EXTRAPERM'][] = $website->getPermission();
		}
	}

	protected function includeClangFile() {
		global $REX;

		require($REX['INCLUDE_PATH'] . '/addons/website_manager/generated/' . $this->getCurrentWebsite()->getClangFile());
	}

	protected function getWebsiteIdForFrontend() {
		foreach ($this->websites as $website) {
			if ($website->getDomain() == $_SERVER['SERVER_NAME']) {
				// website found :)
				return $website->getId();
			}
		}

		// website not found :(
		header('HTTP/1.0 404 Not Found');
		echo "Website not found!";
		exit;

		//return rex_website::firstId;
	}

	protected function getWebsiteIdForBackend() {
		$this->initSessionVar();

		return rex_session('current_website_id');
	}

	protected function initSessionVar() {
		if (session_id() == '') {
			session_start();
		}

		if (rex_request('new_website_id') >= rex_website::firstId) {
			// user switched website
			rex_set_session('current_website_id', rex_request('new_website_id'));
		} elseif (rex_session('current_website_id') < rex_website::firstId) {
			// first time running
			rex_set_session('current_website_id', rex_website::firstId);
		} else {
			// session var is set correctly, nothing todo
		}
	}

	protected function setRexVars() {
		global $REX;

		$curWebsite = $this->getCurrentWebsite();

		$REX['SERVER'] = $curWebsite->getUrl();
		$REX['SERVERNAME'] = $curWebsite->getTitle();
		$REX['START_ARTICLE_ID'] = $curWebsite->getStartArticleId();
		$REX['NOTFOUND_ARTICLE_ID'] = $curWebsite->getNotFoundArticleId();
		$REX['DEFAULT_TEMPLATE_ID'] = $curWebsite->getDefaultTemplateId();
		$REX['MEDIA_DIR'] = $curWebsite->getMediaDir();
		$REX['MEDIAFOLDER'] = $curWebsite->getMediaPath();
		$REX['GENERATED_PATH'] = $curWebsite->getGeneratedPath();
		$REX['TABLE_PREFIX'] = $curWebsite->getTablePrefix();
	}

	public function generateAll() {
		foreach ($this->websites as $website) {
			$website->generateAll();
		}
	}

	public function checkPermissions() {
		global $REX;

		if ($REX['WEBSITE_MANAGER_SETTINGS']['ignore_permissions'] || (isset($REX['USER']) && $REX['USER']->isAdmin()) || (isset($REX['USER']) && $REX['USER']->hasPerm($this->getCurrentWebsite()->getPermission()))) {
			// do nothing, everything is already set properly
		} else {
			$websiteId = 0;

			foreach ($this->websites as $website) {
				if (isset($REX['USER']) && $REX['USER']->hasPerm($website->getPermission())) {
					// permission for user found
					$websiteId = $website->getId();
					break;
				}
			}

			if ($websiteId > 0) {
				// reinit website to start up with correct website for user
				$this->init($websiteId);
			} else {
				// user has no rights
				header('Location:index.php?rex_logout=1'); // how to display msg to user at this point?
			}
		}
	}

	public static function updateInitFile() {
		global $REX;

		$initContent = '';
		$initFile = $REX['INCLUDE_PATH'] . '/addons/website_manager/generated/init.inc.php';

		if (!file_exists($initFile)) {
			rex_website_manager_utils::createDynFile($initFile);
		}

		// inludes
		$initContent .= 'require_once($REX[\'INCLUDE_PATH\'] . \'/addons/website_manager/settings.inc.php\');' . PHP_EOL;
		$initContent .= 'require_once($REX[\'INCLUDE_PATH\'] . \'/addons/website_manager/classes/class.rex_website.inc.php\');' . PHP_EOL;
		$initContent .= 'require_once($REX[\'INCLUDE_PATH\'] . \'/addons/website_manager/classes/class.rex_website_manager.inc.php\');' . PHP_EOL;
		$initContent .= 'require_once($REX[\'INCLUDE_PATH\'] . \'/addons/website_manager/plugins/themes/classes/class.rex_website_theme.inc.php\');' . PHP_EOL . PHP_EOL;

		// create website manager
		$initContent .= '$REX[\'WEBSITE_MANAGER\'] = new rex_website_manager();' . PHP_EOL . PHP_EOL;

		// websites
		$sql = rex_sql::factory();
		//$sql->debugsql = true;
		$sql->setQuery('SELECT * FROM rex_website ORDER BY priority');

		for ($i = 0; $i < $sql->getRows(); $i++) {
			$initContent .= '$REX[\'WEBSITE_MANAGER\']->addWebsite(new rex_website(' . $sql->getValue('id') . ', \'' . $sql->getValue('domain') . '\', \'' . $sql->getValue('title') . '\', ' . $sql->getValue('start_article_id') . ', ' . $sql->getValue('notfound_article_id') . ', ' . $sql->getValue('default_template_id') . ', \'' . $sql->getValue('color') . '\', \'' . $sql->getValue('table_prefix') . '\', \'' . $sql->getValue('protocol') . '\', ' . $sql->getValue('theme_id') . '));' . PHP_EOL;
			$sql->next();	
		}
		
		$initContent .= PHP_EOL;

		// init current website
		$initContent .= '$REX[\'WEBSITE_MANAGER\']->init();';

	  	rex_replace_dynamic_contents($initFile, $initContent);
	}

	public static function fixClang($params) {
		global $REX;

		// get clangs from db
		$sql = new rex_sql();
		//$sql->debugsql = true;
		$sql->setQuery('SELECT * FROM `' . $REX['TABLE_PREFIX'] . 'clang`');

		unset($REX['CLANG']);

		for ($i = 0; $i < $sql->getRows(); $i++) {
			$REX['CLANG'][$sql->getValue('id')] = $sql->getValue('name');

			$sql->next();
		}

		// write clangs to website specific clang file
		if (isset($REX['WEBSITE_MANAGER'])) {
			$clangFile = $REX['INCLUDE_PATH'] . '/addons/website_manager/generated/' . $REX['WEBSITE_MANAGER']->getCurrentWebsite()->getClangFile();
		} else {
			// this is when addon is getting installed
			$clangFile = $REX['INCLUDE_PATH'] . '/addons/website_manager/generated/' . rex_website::constructClangFile(1);
		}

		if (!file_exists($clangFile)) {
			rex_website_manager_utils::createDynFile($clangFile);
		}

	  	rex_replace_dynamic_contents($clangFile, "\$REX['CLANG'] = ". var_export($REX['CLANG'], TRUE) .";\n");
	}

	public static function createClangFile($websiteId) {
		global $REX;

		$clangFile = $REX['INCLUDE_PATH'] . '/addons/website_manager/generated/' . rex_website::constructClangFile($websiteId);

		if (!file_exists($clangFile)) {
			rex_website_manager_utils::createDynFile($clangFile);
		}

	  	rex_replace_dynamic_contents($clangFile, "\$REX['CLANG'] = array (0 => 'deutsch');");
	}

	public static function deleteClangFile($websiteId) {
		global $REX;

		$clangFile = $REX['INCLUDE_PATH'] . '/addons/website_manager/generated/' . rex_website::constructClangFile($websiteId);

		if (file_exists($clangFile)) {
			unlink($clangFile);
		}
	}

	public static function updateTablePrefix($websiteId) {
		$tablePrefix = rex_website::constructTablePrefix($websiteId);

		$sql = new rex_sql();
		//$sql->debugsql = true;
		$sql->setQuery("UPDATE rex_website SET table_prefix = '" . $tablePrefix . "' WHERE id = " . $websiteId);
	}

	public static function createIcon($hexColor) {
		global $REX;

		$path =  realpath($REX['HTDOCS_PATH'] . $REX['MEDIA_ADDON_DIR']) . DIRECTORY_SEPARATOR . 'website_manager/';
		$rgbColor = rex_website_manager_utils::hex2rgb($hexColor);

		$favIconOriginal = $path . 'favicon.png';
		$favIconNew = $path . rex_website::constructIconFile($hexColor);

		$im = imagecreatefrompng($favIconOriginal);
		imagealphablending($im, false);

		imagesavealpha($im, true);

		if ($im && imagefilter($im, IMG_FILTER_COLORIZE, $rgbColor[0], $rgbColor[1], $rgbColor[2], 0)) {
			imagepng($im, $favIconNew);
			imagedestroy($im);
		}
	}

	public static function createWebsite($websiteId) {
		global $REX, $I18N, $curAddonCount, $curPluginCount;

		$sql = rex_sql::factory();
		$tablePrefix = rex_website::constructTablePrefix($websiteId);
		$generatedDir = rex_website::constructGeneratedDir($websiteId);
		$mediaDir = rex_website::constructMediaDir($websiteId);

		// init logger
		$logFile = $REX['INCLUDE_PATH'] . '/addons/website_manager/generated/log';
		$log = KLogger::instance($logFile, KLogger::INFO);

		$log->logInfo('======================================== CREATE WEBSITE WITH ID = ' . $websiteId . ' ========================================');

		// just for security reasons
		if ($websiteId == rex_website::firstId) {
			$log->logError('Website ID = 1 detected --> Exit!');
			echo rex_warning($I18N->msg('website_manager_create_website_security_msg')); 

			exit;
		}

		// ***************************************************************************************************
		// database tables
		// ***************************************************************************************************

		// users (always identical)
		rex_website_manager_utils::logQuery($log, $sql, 'CREATE VIEW ' . $tablePrefix . 'user AS SELECT * FROM rex_user');

		// articles (always different)
		rex_website_manager_utils::logQuery($log, $sql, 'CREATE TABLE `' . $tablePrefix . 'article` ( `pid` int(11) NOT NULL  auto_increment, `id` int(11) NOT NULL  , `re_id` int(11) NOT NULL  , `name` varchar(255) NOT NULL  , `catname` varchar(255) NOT NULL  , `catprior` int(11) NOT NULL  , `attributes` text NOT NULL  , `startpage` tinyint(1) NOT NULL  , `prior` int(11) NOT NULL  , `path` varchar(255) NOT NULL  , `status` tinyint(1) NOT NULL  , `createdate` int(11) NOT NULL  , `updatedate` int(11) NOT NULL  , `template_id` int(11) NOT NULL  , `clang` int(11) NOT NULL  , `createuser` varchar(255) NOT NULL  , `updateuser` varchar(255) NOT NULL  , `revision` int(11) NOT NULL  , PRIMARY KEY (`pid`)) ENGINE=MyISAM;');
		rex_website_manager_utils::logQuery($log, $sql, 'CREATE TABLE `' . $tablePrefix . 'article_slice` ( `id` int(11) NOT NULL  auto_increment, `clang` int(11) NOT NULL  , `ctype` int(11) NOT NULL  , `re_article_slice_id` int(11) NOT NULL  ,`value1` text  NULL  , `value2` text NULL  , `value3` text NULL  , `value4` text NULL  , `value5` text NULL  , `value6` text NULL  , `value7` text NULL  , `value8` text NULL  , `value9` text NULL  , `value10` text NULL  , `value11` text NULL  , `value12` text NULL  , `value13` text NULL  , `value14` text NULL  , `value15` text NULL  , `value16` text NULL  , `value17` text NULL  , `value18` text NULL  , `value19` text NULL  , `value20` text NULL  , `file1` varchar(255) NULL  , `file2` varchar(255) NULL  , `file3` varchar(255) NULL  , `file4` varchar(255) NULL  , `file5` varchar(255) NULL  , `file6` varchar(255) NULL  , `file7` varchar(255) NULL  , `file8` varchar(255) NULL  , `file9` varchar(255) NULL  , `file10` varchar(255) NULL  , `filelist1` text NULL  , `filelist2` text NULL  , `filelist3` text NULL  , `filelist4` text NULL  , `filelist5` text NULL  , `filelist6` text NULL  , `filelist7` text NULL  , `filelist8` text NULL  , `filelist9` text NULL  , `filelist10` text NULL  , `link1` varchar(10) NULL  , `link2` varchar(10) NULL  , `link3` varchar(10) NULL  , `link4` varchar(10) NULL  , `link5` varchar(10) NULL  , `link6` varchar(10) NULL  , `link7` varchar(10) NULL  , `link8` varchar(10) NULL  , `link9` varchar(10) NULL  , `link10` varchar(10) NULL  , `linklist1` text NULL  , `linklist2` text NULL  , `linklist3` text NULL  , `linklist4` text NULL  , `linklist5` text NULL  , `linklist6` text NULL  , `linklist7` text NULL  , `linklist8` text NULL  , `linklist9` text NULL  , `linklist10` text NULL  , `php` text NULL  , `html` text NULL  ,`article_id` int(11) NOT NULL  , `modultyp_id` int(11) NOT NULL  , `createdate` int(11) NOT NULL  , `updatedate` int(11) NOT NULL  , `createuser` varchar(255) NOT NULL  , `updateuser` varchar(255) NOT NULL  , `next_article_slice_id` int(11) NULL  , `revision` int(11) NOT NULL  , PRIMARY KEY (`id`,`re_article_slice_id`,`article_id`,`modultyp_id`)) ENGINE=MyISAM;');
		rex_website_manager_utils::logQuery($log, $sql, 'ALTER TABLE ' . $tablePrefix . 'article ADD INDEX `id` (`id`), ADD INDEX `clang` (`clang`), ADD UNIQUE INDEX `find_articles` (`id`, `clang`), ADD INDEX `re_id` (`re_id`);');
		rex_website_manager_utils::logQuery($log, $sql, 'ALTER TABLE ' . $tablePrefix . 'article_slice ADD INDEX `id` (`id`), ADD INDEX `clang` (`clang`), ADD INDEX `re_article_slice_id` (`re_article_slice_id`), ADD INDEX `article_id` (`article_id`), ADD INDEX `find_slices` (`clang`, `article_id`);');

		// clangs
		if ($REX['WEBSITE_MANAGER_SETTINGS']['identical_clangs']) {
			// identical
			rex_website_manager_utils::logQuery($log, $sql, 'CREATE VIEW ' . $tablePrefix . 'clang AS SELECT * FROM rex_clang');
		} else {
			// different
			rex_website_manager_utils::logQuery($log, $sql, 'CREATE TABLE `' . $tablePrefix . 'clang` ( `id` int(11) NOT NULL  , `name` varchar(255) NOT NULL  , `revision` int(11) NOT NULL  , PRIMARY KEY (`id`)) ENGINE=MyISAM;');
			rex_website_manager_utils::logQuery($log, $sql, 'INSERT INTO `' . $tablePrefix . 'clang` VALUES ("0", "deutsch", 0);');
		}

		// media
		if ($REX['WEBSITE_MANAGER_SETTINGS']['identical_media']) {
			// identical
			rex_website_manager_utils::logQuery($log, $sql, 'CREATE VIEW ' . $tablePrefix . 'file AS SELECT * FROM rex_file');
			rex_website_manager_utils::logQuery($log, $sql, 'CREATE VIEW ' . $tablePrefix . 'file_category AS SELECT * FROM rex_file_category');
		} else {
			// different
			rex_website_manager_utils::logQuery($log, $sql, 'CREATE TABLE `' . $tablePrefix . 'file` ( `file_id` int(11) NOT NULL  auto_increment, `re_file_id` int(11) NOT NULL  , `category_id` int(11) NOT NULL  , `attributes` text NULL  , `filetype` varchar(255) NULL  , `filename` varchar(255) NULL  , `originalname` varchar(255) NULL  , `filesize` varchar(255) NULL  , `width` int(11) NULL  , `height` int(11) NULL  , `title` varchar(255) NULL  , `createdate` int(11) NOT NULL  , `updatedate` int(11) NOT NULL  , `createuser` varchar(255) NOT NULL  , `updateuser` varchar(255) NOT NULL  , `revision` int(11) NOT NULL  , PRIMARY KEY (`file_id`)) ENGINE=MyISAM;');
			rex_website_manager_utils::logQuery($log, $sql, 'CREATE TABLE `' . $tablePrefix . 'file_category` ( `id` int(11) NOT NULL  auto_increment, `name` varchar(255) NOT NULL  , `re_id` int(11) NOT NULL  , `path` varchar(255) NOT NULL  , `createdate` int(11) NOT NULL  , `updatedate` int(11) NOT NULL  , `createuser` varchar(255) NOT NULL  , `updateuser` varchar(255) NOT NULL  , `attributes` text NULL  , `revision` int(11) NOT NULL  , PRIMARY KEY (`id`,`name`)) ENGINE=MyISAM;');
			rex_website_manager_utils::logQuery($log, $sql, 'ALTER TABLE ' . $tablePrefix . 'file ADD INDEX `re_file_id` (`re_file_id`), ADD INDEX `category_id` (`category_id`);');
			rex_website_manager_utils::logQuery($log, $sql, 'ALTER TABLE ' . $tablePrefix . 'file_category DROP PRIMARY KEY, ADD PRIMARY KEY (`id`), ADD INDEX `re_id` (`re_id`);');
		}
		
		// modules
		if ($REX['WEBSITE_MANAGER_SETTINGS']['identical_modules']) {
			// identical
			rex_website_manager_utils::logQuery($log, $sql, 'CREATE VIEW ' . $tablePrefix . 'module AS SELECT * FROM rex_module');
			rex_website_manager_utils::logQuery($log, $sql, 'CREATE VIEW ' . $tablePrefix . 'module_action AS SELECT * FROM rex_module_action');
			rex_website_manager_utils::logQuery($log, $sql, 'CREATE VIEW ' . $tablePrefix . 'action AS SELECT * FROM rex_action');
		} else {
			// different
			rex_website_manager_utils::logQuery($log, $sql, 'CREATE TABLE `' . $tablePrefix . 'module` ( `id` int(11) NOT NULL  auto_increment, `name` varchar(255) NOT NULL  , `category_id` int(11) NOT NULL  , `ausgabe` text NOT NULL  , `eingabe` text NOT NULL  , `createuser` varchar(255) NOT NULL  , `updateuser` varchar(255) NOT NULL  , `createdate` int(11) NOT NULL  , `updatedate` int(11) NOT NULL  , `attributes` text NULL  , `revision` int(11) NOT NULL  , PRIMARY KEY (`id`,`category_id`)) ENGINE=MyISAM;');
			rex_website_manager_utils::logQuery($log, $sql, 'CREATE TABLE `' . $tablePrefix . 'module_action` ( `id` int(11) NOT NULL  auto_increment, `module_id` int(11) NOT NULL  , `action_id` int(11) NOT NULL  , `revision` int(11) NOT NULL  , PRIMARY KEY (`id`)) ENGINE=MyISAM;');
			rex_website_manager_utils::logQuery($log, $sql, 'CREATE TABLE `' . $tablePrefix . 'action` ( `id` int(11) NOT NULL  auto_increment, `name` varchar(255) NOT NULL  , `preview` text NULL  , `presave` text NULL  , `postsave` text NULL  , `previewmode` tinyint(4) NULL  , `presavemode` tinyint(4) NULL  , `postsavemode` tinyint(4) NULL  , `createuser` varchar(255) NOT NULL  , `createdate` int(11) NOT NULL  , `updateuser` varchar(255) NOT NULL  , `updatedate` int(11) NOT NULL  , `revision` int(11) NOT NULL  , PRIMARY KEY (`id`)) ENGINE=MyISAM;');
			rex_website_manager_utils::logQuery($log, $sql, 'ALTER TABLE ' . $tablePrefix . 'module DROP PRIMARY KEY, ADD PRIMARY KEY (`id`), ADD INDEX `category_id` (`category_id`);');
		}

		// templates
		if ($REX['WEBSITE_MANAGER_SETTINGS']['identical_templates']) {
			// identical
			rex_website_manager_utils::logQuery($log, $sql, 'CREATE VIEW ' . $tablePrefix . 'template AS SELECT * FROM rex_template');
		} else {
			// different
			rex_website_manager_utils::logQuery($log, $sql, 'CREATE TABLE `' . $tablePrefix . 'template` ( `id` int(11) NOT NULL  auto_increment, `label` varchar(255) NULL  , `name` varchar(255) NULL  , `content` text NULL  , `active` tinyint(1) NULL  , `createuser` varchar(255) NOT NULL  , `updateuser` varchar(255) NOT NULL  , `createdate` int(11) NOT NULL  , `updatedate` int(11) NOT NULL  , `attributes` text NULL  , `revision` int(11) NOT NULL  , PRIMARY KEY (`id`)) ENGINE=MyISAM;');
		}

		// meta infos
		if ($REX['WEBSITE_MANAGER_SETTINGS']['identical_meta_infos']) {
			// identical
			rex_website_manager_utils::logQuery($log, $sql, 'CREATE VIEW ' . $tablePrefix . '62_type AS SELECT * FROM rex_62_type');
			rex_website_manager_utils::logQuery($log, $sql, 'CREATE VIEW ' . $tablePrefix . '62_params AS SELECT * FROM rex_62_params');
		} else {
			// different (addon will be reinstalled)
			if (!in_array('metainfo', $REX['WEBSITE_MANAGER_SETTINGS']['reinstall_addons'])) {
				$REX['WEBSITE_MANAGER_SETTINGS']['reinstall_addons'][] = 'metainfo';
			}
		}

		// image types
		if ($REX['WEBSITE_MANAGER_SETTINGS']['identical_image_types']) {
			// identical
			rex_website_manager_utils::logQuery($log, $sql, 'CREATE VIEW ' . $tablePrefix . '679_types AS SELECT * FROM rex_679_types');
			rex_website_manager_utils::logQuery($log, $sql, 'CREATE VIEW ' . $tablePrefix . '679_type_effects AS SELECT * FROM rex_679_type_effects');
		} else {
			// different (addon will be reinstalled)
			if (!in_array('image_manager', $REX['WEBSITE_MANAGER_SETTINGS']['reinstall_addons'])) {
				$REX['WEBSITE_MANAGER_SETTINGS']['reinstall_addons'][] = 'image_manager';
			}
		}

		// ***************************************************************************************************
		// directories
		// ***************************************************************************************************

		$includePath = realpath($REX['HTDOCS_PATH'] . 'redaxo/include/') . DIRECTORY_SEPARATOR;
		$htdocsPath = realpath($REX['HTDOCS_PATH']) . DIRECTORY_SEPARATOR;

		rex_website_manager_utils::logMkDir($log, $includePath . $generatedDir);
		rex_website_manager_utils::logMkDir($log, $includePath . $generatedDir . '/articles');
		rex_website_manager_utils::logMkDir($log, $includePath . $generatedDir . '/files');
		rex_website_manager_utils::logMkDir($log, $includePath . $generatedDir . '/templates');

		if (!$REX['WEBSITE_MANAGER_SETTINGS']['identical_media']) {
			rex_website_manager_utils::logMkDir($log, $htdocsPath . $mediaDir);
		}

		// ***************************************************************************************************
		// addons/plugins
		// ***************************************************************************************************

		$reinstallAddons = $REX['WEBSITE_MANAGER_SETTINGS']['reinstall_addons'];
		$reinstallPlugins = $REX['WEBSITE_MANAGER_SETTINGS']['reinstall_plugins'];

		// set stuff for new website
		$REX['TABLE_PREFIX'] = $tablePrefix;
		$REX['GENERATED_PATH'] = realpath($REX['HTDOCS_PATH'] . 'redaxo/include/' . $generatedDir);

		// reinstall addons
		for ($curAddonCount = 0; $curAddonCount < count($reinstallAddons); $curAddonCount++) {
			if (OOAddon::isInstalled($reinstallAddons[$curAddonCount])) {
				require_once($REX['INCLUDE_PATH'] . '/addons/' . $reinstallAddons[$curAddonCount] . '/install.inc.php');

				$log->logInfo('[REINSTALL ADDON] ' . $reinstallAddons[$curAddonCount]);

				$sqlFile = $REX['INCLUDE_PATH'] . '/addons/' . $reinstallAddons[$curAddonCount] . '/install.sql';
	
				if (file_exists($sqlFile)) {
					$returnValue = rex_install_dump($sqlFile);
					
					if ($returnValue) {
						$log->logInfo('[REINSTALL ADDON SQL DUMP] ' . $sqlFile);
					} else {
						$log->logError('[REINSTALL ADDON SQL DUMP] ' . $sqlFile);
					}
				}
			}
		}

		// reinstall plugins
		for ($curPluginCount = 0; $curPluginCount < count($reinstallPlugins); $curPluginCount++) {
			if (OOPlugin::isInstalled($reinstallPlugins[$curPluginCount][0], $reinstallPlugins[$curPluginCount][1])) {
				require_once($REX['INCLUDE_PATH'] . '/addons/' . $reinstallPlugins[$curPluginCount][0] . '/plugins/' . $reinstallPlugins[$curPluginCount][1] . '/install.inc.php');

				$log->logInfo('[REINSTALL PLUGIN] ' . $reinstallPlugins[$curPluginCount][1]);

				$sqlFile = $REX['INCLUDE_PATH'] . '/addons/' . $reinstallPlugins[$curPluginCount][0] . '/plugins/' . $reinstallPlugins[$curPluginCount][1] . '/install.sql';
	
				if (file_exists($sqlFile)) {
					$returnValue = rex_install_dump($sqlFile);
					
					if ($returnValue) {
						$log->logInfo('[REINSTALL PLUGIN SQL DUMP] ' . $sqlFile);
					} else {
						$log->logError('[REINSTALL PLUGIN SQL DUMP] ' . $sqlFile);
					}
				}
			}
		}

		// put back stuff for master website
		$REX['TABLE_PREFIX'] = rex_website::tablePrefix;
		$REX['GENERATED_PATH'] = realpath($REX['HTDOCS_PATH'] . 'redaxo/include/' . rex_website::generatedDir);

	}

	public static function destroyWebsite($websiteId) {
		global $REX, $I18N;

		$sql = rex_sql::factory();
		$tablePrefix = rex_website::constructTablePrefix($websiteId);
		$generatedDir = rex_website::constructGeneratedDir($websiteId);
		$mediaDir = rex_website::constructMediaDir($websiteId);

		// init logger
		$logFile = $REX['INCLUDE_PATH'] . '/addons/website_manager/generated/log';
		$log = KLogger::instance($logFile, KLogger::INFO);

		$log->logInfo('======================================== DESTROY WEBSITE WITH ID = ' . $websiteId . ' ========================================');

		// just for security reasons
		if ($websiteId == rex_website::firstId) {
			$log->logError('Website ID = 1 detected --> Exit!');
			echo rex_warning($I18N->msg('website_manager_destroy_website_security_msg'));

			exit;
		}

		// ***************************************************************************************************
		// database views
		// ***************************************************************************************************

		// users
		rex_website_manager_utils::logQuery($log, $sql, 'DROP VIEW ' . $tablePrefix . 'user');

		// clangs
		if ($REX['WEBSITE_MANAGER_SETTINGS']['identical_clangs']) {
			rex_website_manager_utils::logQuery($log, $sql, 'DROP VIEW ' . $tablePrefix . 'clang');
		} 

		// media
		if ($REX['WEBSITE_MANAGER_SETTINGS']['identical_media']) {
			rex_website_manager_utils::logQuery($log, $sql, 'DROP VIEW ' . $tablePrefix . 'file');
			rex_website_manager_utils::logQuery($log, $sql, 'DROP VIEW ' . $tablePrefix . 'file_category');
		}
		
		// modules
		if ($REX['WEBSITE_MANAGER_SETTINGS']['identical_modules']) {
			rex_website_manager_utils::logQuery($log, $sql, 'DROP VIEW ' . $tablePrefix . 'module');
			rex_website_manager_utils::logQuery($log, $sql, 'DROP VIEW ' . $tablePrefix . 'module_action');
			rex_website_manager_utils::logQuery($log, $sql, 'DROP VIEW ' . $tablePrefix . 'action');
		}

		// templates
		if ($REX['WEBSITE_MANAGER_SETTINGS']['identical_templates']) {
			rex_website_manager_utils::logQuery($log, $sql, 'DROP VIEW ' . $tablePrefix . 'template');
		}

		// meta infos
		if ($REX['WEBSITE_MANAGER_SETTINGS']['identical_meta_infos']) {
			rex_website_manager_utils::logQuery($log, $sql, 'DROP VIEW ' . $tablePrefix . '62_type');
			rex_website_manager_utils::logQuery($log, $sql, 'DROP VIEW ' . $tablePrefix . '62_params');
		}

		// image types
		if ($REX['WEBSITE_MANAGER_SETTINGS']['identical_image_types']) {
			rex_website_manager_utils::logQuery($log, $sql, 'DROP VIEW ' . $tablePrefix . '679_types');
			rex_website_manager_utils::logQuery($log, $sql, 'DROP VIEW ' . $tablePrefix . '679_type_effects');
		}

		// ***************************************************************************************************
		// database tables
		// ***************************************************************************************************

		$tables = $sql->showTables(1, $tablePrefix);

		for ($i = 0; $i < count($tables); $i++) {
			rex_website_manager_utils::logQuery($log, $sql, 'DROP TABLE ' . $tables[$i]);
		}

		// ***************************************************************************************************
		// directories
		// ***************************************************************************************************

		$generatedPath = realpath($REX['HTDOCS_PATH'] . 'redaxo/include/') . DIRECTORY_SEPARATOR . $generatedDir;
		$mediaPath = realpath($REX['HTDOCS_PATH']) . DIRECTORY_SEPARATOR . $mediaDir;

		rex_website_manager_utils::rrmdir($generatedPath);

		if (is_dir($generatedPath)) {
			$log->logError('[REMOVE DIR] ' . $generatedPath);
		} else {
			$log->logInfo('[REMOVE DIR] ' . $generatedPath);
		}

		if (!$REX['WEBSITE_MANAGER_SETTINGS']['identical_media']) {
			rex_website_manager_utils::rrmdir($mediaPath);

			if (is_dir($mediaPath)) {
				$log->logError('[REMOVE DIR] ' . $mediaPath);
			} else {
				$log->logInfo('[REMOVE DIR] ' . $mediaPath);
			}
		}
	}

	public function websiteSwitch($switchedWebsiteId, $func) {
		// switch rex vars to new website
		$this->getWebsite($switchedWebsiteId)->switchRexVars();

		// do user stuff
		$func();

		// restore rex vars for original website
		$this->getCurrentWebsite()->switchRexVars();
	}

	public function masterWebsiteSwitch($func) {
		 $this->websiteSwitch($this->getMasterWebsiteId(), $func);
	}
}



