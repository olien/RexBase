<?php
class rex_slice_status {
	public static function getSliceStatus($sliceId) {
		global $REX;

		$sqlStatement = 'SELECT id, status FROM ' . $REX['TABLE_PREFIX'] . 'article_slice WHERE id = ' . $sliceId;
		$sql = rex_sql::factory();
		$sql->setQuery($sqlStatement);

		if ($sql->getRows() > 0) {
			return $sql->getValue('status');
		} else {
			return -1;
		}
	}

	public static function setSliceStatus($sliceId, $newStatus) {
		global $REX;

		if (!$REX['REDAXO']) {
			require_once($REX['INCLUDE_PATH'] . '/functions/function_rex_generate.inc.php');
		}

		$slice = OOArticleSlice::getArticleSliceById($sliceId);

		if (is_object($slice)) {
			self::updateSliceStatus($slice->getArticleId(), $slice->getClang(), $sliceId, $newStatus);
		}
	}

	public static function fetchSliceStatus() {
		global $REX, $I18N;

		$fetchedSliceStatus = array();
	
		$sqlStatement = 'SELECT id, status FROM ' . $REX['TABLE_PREFIX'] . 'article_slice';
		$sql = rex_sql::factory();
		$sql->setQuery($sqlStatement);

		// check for status db field
		if ($sql->getRows() == 0) {
			return array();
		}

		// fetch status array
		for ($i = 0; $i < $sql->getRows(); $i++) {
			$fetchedSliceStatus[$sql->getValue('id')] = $sql->getValue('status');
			$sql->next();
		}

		return $fetchedSliceStatus;
	}

	public static function modifySliceEditMenu($params) {
		global $REX, $I18N, $slices;
	
		extract($params);

		// get menu item position: left/right
		$menuItemPosition = trim($REX['ADDON']['slice_status']['menuitem_position']);

		// get status of current slice
		if (!isset($slices)) {
			$slices = self::fetchSliceStatus(); // now only one db query necessary

			if (count($slices) == 0) {
				// inform user that status db field is missing
				echo rex_warning($I18N->msg('status_dbfield_not_found'));
			}
		}
	
		if (isset($slices[$slice_id])) {
			$curStatus = $slices[$slice_id];

			// retrieve stuff for new status
			if ($curStatus == 1) {
				$aClass = 'slice-status ' . $menuItemPosition . ' slice-' . $slice_id . ' online';
				$aTitle = $I18N->msg('toggle_slice_offline');
				$newStatus = '0';
			} else {
				$aClass = 'slice-status ' . $menuItemPosition . ' slice-' . $slice_id . ' offline';
				$aTitle = $I18N->msg('toggle_slice_online');
				$newStatus = '1';
			}
	
			// construct href
			if ($REX['ADDON']['slice_status']['ajax_mode']) {
				$aHref = 'javascript:updateSliceStatus(' . $article_id . ',' . $clang . ',' . $slice_id . ',' . $curStatus . ');';
			} else {
				$aHref = 'index.php?page=content&article_id=' . $article_id . '&mode=edit&slice_id=' . $slice_id . '&clang=' . $clang . '&ctype=' . $ctype . '&function=updateslicestatus' . '&new_status=' . $newStatus . '#slice' . $slice_id;	
			}
	
			// inject link in slice menu
			$dataAttributes = 'data-title-online="' . $I18N->msg('toggle_slice_online') . '" data-title-offline="' . $I18N->msg('toggle_slice_offline') . '"';
			$statusLink = '<a class="' . $aClass . '" href="' . $aHref . '" title="' . $aTitle . '" ' . $dataAttributes . '><span>Slice Status</span></a>';
	
			if ($menuItemPosition == 'left') {
				array_unshift($subject, $statusLink);
			} else {
				$subject[] = $statusLink;
			}
		}
	
		return $subject;
	}

	public static function sliceShow($params) {
		global $REX;
	
		extract($params);
	
		$sqlStatement = 'SELECT status FROM '.$REX['TABLE_PREFIX'] . 'article_slice WHERE id = '. $slice_id;
		$sql = rex_sql::factory();
		$sql->setQuery($sqlStatement);
	
		if ($sql->getRows() == 0 || $sql->getValue('status') == 1 || $REX['REDAXO']) {
			return $subject;
		} else {
			return '<?php if (false) { ?>' . $subject . '<?php } ?>';
		}
	}

	public static function updateSliceStatus($articleID, $cLang, $sliceID, $newStatus) {
		global $REX;

		// update db
		$sql = rex_sql::factory();
		$sql->setQuery('UPDATE ' . $REX['TABLE_PREFIX'] . 'article_slice SET status = ' . $newStatus . ' WHERE id=' . $sliceID);
	
		// delete cached article (important!)
		rex_deleteCacheArticleContent($articleID, $cLang);
	}

	public static function appendToPageHeader($params) {
		global $REX;

		$insert = '<!-- BEGIN slice_status -->' . PHP_EOL;
		$insert .= '<link rel="stylesheet" type="text/css" href="../' . self::getMediaAddonDir() . '/slice_status/slice_status.css" />' . PHP_EOL;
		$insert .= '<script type="text/javascript" src="../' . self::getMediaAddonDir() . '/slice_status/slice_status.js"></script>' . PHP_EOL;
		$insert .= '<!-- END slice_status -->';
	
		return $params['subject'] . PHP_EOL . $insert;
	}

	public static function afterDBImport($params) {
		global $REX, $I18N;

		if (count(rex_slice_status::fetchSliceStatus()) == 0) {
			require($REX['INCLUDE_PATH'] . '/addons/slice_status/install.inc.php');
			echo rex_info($I18N->msg('status_dbfield_readded'));
		}
	}

	public static function getMediaAddonDir() {
		global $REX;

		// check for media addon dir var introduced in REX 4.5
		if (isset($REX['MEDIA_ADDON_DIR'])) {
			return $REX['MEDIA_ADDON_DIR'];
		} else {
			return 'files/addons';
		}
	}

	public static function versionAddonFix($params) {
		rex_deleteCacheArticleContent(rex_request('article_id'), rex_request('clang'));
	}
}

