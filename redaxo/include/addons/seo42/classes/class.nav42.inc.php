<?php

class nav42 {
	var $depth;
	var $open;
	var $ignore_offlines;
	var $path = array();
	var $callbacks = array();

	var $current_article_id = -1;
	var $current_category_id = -1;

	function nav42() {
		// do nothing
	}

	public static function getNavigationByLevel($levelStart = 0, $levelDepth = 2, $showAll = false, $ignoreOfflines = true, $hideWebsiteStartArticle = false, $currentClass = 'selected', $firstUlId = '', $firstUlClass = '', $liIdFromMetaField = '', $liClassFromMetaField = '', $linkFromUserFunc = '') {
		global $REX;
		
		$nav = new nav42();
		$navPath = explode('|', ('0' . $REX['ART'][$REX['ARTICLE_ID']]['path'][$REX['CUR_CLANG']] . $REX['ARTICLE_ID'] . '|'));

		return $nav->get($navPath[$levelStart], $levelDepth, $showAll, $ignoreOfflines, $hideWebsiteStartArticle, $currentClass, $firstUlId, $firstUlClass, $liIdFromMetaField, $liClassFromMetaField, $linkFromUserFunc);
	}

	public static function getNavigationByCategory($categoryId, $levelDepth = 2, $showAll = false, $ignoreOfflines = true, $hideWebsiteStartArticle = false, $currentClass = 'selected', $firstUlId = '', $firstUlClass = '', $liIdFromMetaField = '', $liClassFromMetaField = '', $linkFromUserFunc = '') {
		$nav = new nav42();

		return $nav->get($categoryId, $levelDepth, $showAll, $ignoreOfflines, $hideWebsiteStartArticle, $currentClass, $firstUlId, $firstUlClass, $liIdFromMetaField, $liClassFromMetaField, $linkFromUserFunc);
	}

	public static function getLangNavigation($ulId = '', $currentClass = 'selected', $showLiIds = false, $hideLiIfOfflineArticle = false, $useLangCodeAsLinkText = false, $upperCaseLinkText = false) {
		global $REX;

		// ul id
		if ($ulId == '') {
			$ulIdAttribute = '';
		} else {
			$ulIdAttribute = ' id="' . $ulId . '"';
		}

		$out = '<ul' . $ulIdAttribute . '>';

		foreach ($REX['CLANG'] as $clangId => $clangName) {
			$article = OOArticle::getArticleById($REX['ARTICLE_ID'], $clangId);

			// new article id
			if (OOArticle::isValid($article) && $article->isOnline()) {
				$newArticleId = $article->getId();
				$articleStatus = true;
			} else {
				$newArticleId = $REX['START_ARTICLE_ID'];
				$articleStatus = false;
			}

			if (!$articleStatus && $hideLiIfOfflineArticle) {
				// do nothing
			} else {
				if (class_exists('seo42')) {
					$langCode = seo42::getLangCode($clangId);
					$originalName = seo42::getOriginalLangName($clangId);
					$langSlug = seo42::getLangSlug($clangId);
				} else {
					$langCode = $clangName;
					$originalName = $clangName;
					$langSlug = $clangName;
				}		

				// link text
				if ($useLangCodeAsLinkText) {
					$linkText = $langCode;
				} else {
					$linkText = $originalName;
				}

				if ($upperCaseLinkText) {
					$linkText = strtoupper($linkText);
				}

				// li attribute
				if ($showLiIds) {
					$liIdAttribute = ' id="' . $langSlug . '"';
				} else {
					$liIdAttribute = '';
				}

				// class attribute
				if ($REX['CUR_CLANG'] == $clangId) {
					$liClassAttribute = ' class="' . $currentClass . '"';
				} else {
					$liClassAttribute = '';
				}
				
				// li out
				$out .= '<li' . $liIdAttribute . $liClassAttribute . '><a href="' . rex_getUrl($newArticleId, $clangId) . '">' . $linkText . '</a></li>';
			}
		}

		$out .= '</ul>';

		return $out;
	}

	function _getNavigation($categoryId, $ignoreOfflines = true, $hideWebsiteStartArticle = false, $currentClass = 'selected', $firstUlId = '', $firstUlClass = '', $liIdFromMetaField = '', $liClassFromMetaField = '', $linkFromUserFunc = '') { 
		global $REX;

		static $depth = 0;
		
		if ($categoryId < 1) {
			$cats = OOCategory::getRootCategories($ignoreOfflines);
		} else {
			$cats = OOCategory::getChildrenById($categoryId, $ignoreOfflines);
		}

		$return = '';
		$ulIdAttribute = '';
		$ulClassAttribute = '';

		if (count($cats) > 0) {
			if ($depth == 0) {
				// this is first ul
				if ($firstUlId != '') {
					$ulIdAttribute = ' id="' . $firstUlId . '"';
				}

				if ($firstUlClass != '') {
					$ulClassAttribute = ' class="' . $firstUlClass . '"';
				}
			}

			$return .= '<ul' . $ulIdAttribute . $ulClassAttribute . '>';
		}
			
		foreach ($cats as $cat) {
			if ($this->_checkCallbacks($cat, $depth)) {

				$cssClasses = '';
				$idAttribute = '';

				// li class from meta infos
				if ($liClassFromMetaField != '' && $cat->getValue($liClassFromMetaField) != '') {
					$cssClasses .= ' ' . $cat->getValue($liClassFromMetaField);
				}

				// li id from meta infos
				if ($liIdFromMetaField != '' && $cat->getValue($liIdFromMetaField) != '') {
					$idAttribute = ' id="' . $cat->getValue($liIdFromMetaField) . '"';
				}

				// selected class
				if ($cat->getId() == $this->current_category_id) {
					// current menuitem
					$cssClasses .= ' ' . $currentClass;
				} elseif (in_array($cat->getId(), $this->path)) {
					// active menuitem in path
					$cssClasses .= ' ' . $currentClass;
				} else {
					// do nothing
				}

				$trimmedCssClasses = trim($cssClasses);

				// build class attribute
				if ($trimmedCssClasses != '') {
					$classAttribute = ' class="' . $trimmedCssClasses . '"';
				} else {
					$classAttribute = '';
				}

				if ($hideWebsiteStartArticle && ($cat->getId() == $REX['START_ARTICLE_ID'])) {
					// do nothing
				} else {
					$depth++;
					$urlType = 0; // default

					$return .= '<li' . $idAttribute . $classAttribute . '>';

					if ($linkFromUserFunc != '') {
						$defaultLink = call_user_func($linkFromUserFunc, $cat, $depth);
					} else {
						$defaultLink = '<a href="' . $cat->getUrl() . '">' . htmlspecialchars($cat->getName()) . '</a>';
					}

					if (!class_exists('seo42')) {
						// normal behaviour
						$return .= $defaultLink;
					} else {
						// only with seo42 2.0.0+
						$urlData = seo42::getCustomUrlData($cat);

						// check if default lang has url clone option (but only if current categoy has no url data set)
						if (count($REX['CLANG']) > 1 && !isset($urlData['url_type'])) {
							$defaultLangCat = OOCategory::getCategoryById($cat->getId(), $REX['START_CLANG_ID']);
							$urlDataDefaultLang = seo42::getCustomUrlData($defaultLangCat);
				
							if (isset($urlDataDefaultLang['url_clone']) && $urlDataDefaultLang['url_clone']) {
								// clone url data from default language to current language
								$urlData = $urlDataDefaultLang;
							}
						}

						if (isset($urlData['url_type'])) {
							switch ($urlData['url_type']) { 
								case 5: // SEO42_URL_TYPE_NONE
									$return .= htmlspecialchars($cat->getName());
									break;
								case 4: // SEO42_URL_TYPE_LANGSWITCH
									$newClangId = $urlData['clang_id'];
									$newArticleId = $REX['ARTICLE_ID'];
									$catNewLang = OOCategory::getCategoryById($newArticleId, $newClangId);

									// if category that should be switched is not online, switch to start article of website
									if (OOCategory::isValid($catNewLang) && !$catNewLang->isOnline()) {
										$newArticleId = $REX['START_ARTICLE_ID'];
									}

									// select li that is current language
									if ($REX['CUR_CLANG'] == $newClangId) {
										$return = substr($return, 0, strlen($return) - strlen('<li>'));
										$return .= '<li class="' . $currentClass . '">';
									}

									$return .= '<a href="' . rex_getUrl($newArticleId, $newClangId) . '">' . htmlspecialchars($cat->getName()) . '</a>';
									break;
								case 8: // SEO42_URL_TYPE_CALL_FUNC
									$return .= call_user_func($urlData['func'], $cat);
									break;
								default:
									$return .= $defaultLink;
									break;
							}
						} else {
							$return .= $defaultLink;
						}
					} 
				
					if (($this->open || $cat->getId() == $this->current_category_id || in_array($cat->getId(), $this->path)) && ($this->depth > $depth || $this->depth < 0)) {
						$return .= $this->_getNavigation($cat->getId(), $ignoreOfflines, $hideWebsiteStartArticle, $currentClass, $firstUlId, $firstUlClass, $liIdFromMetaField, $liClassFromMetaField, $linkFromUserFunc);
					}
				
					$depth--;

					$return .= '</li>';
				}
			}
		}

		if (count($cats) > 0) {
			$return .= '</ul>';
		}

		return $return;
	}

	function get($categoryId = 0, $depth = 3, $open = false, $ignoreOfflines = true, $hideWebsiteStartArticle = false, $currentClass = 'selected', $firstUlId = '', $firstUlClass = '', $liIdFromMetaField = '', $liClassFromMetaField = '', $linkFromUserFunc = '') { 
		if (!$this->_setActivePath()) {
			return false;
		}

		$this->depth = $depth;
		$this->open = $open;
		$this->ignore_offlines = $ignoreOfflines;

		if (class_exists('rex_com_auth')) {
			$this->addCallback("nav42::checkPerm");
		}
		
		return $this->_getNavigation($categoryId, $this->ignore_offlines, $hideWebsiteStartArticle, $currentClass, $firstUlId, $firstUlClass, $liIdFromMetaField, $liClassFromMetaField, $linkFromUserFunc);
	}

	function _setActivePath() {
		global $REX;

		$article_id = $REX["ARTICLE_ID"];
		
		if ($OOArt = OOArticle::getArticleById($article_id)) {
			$path = trim($OOArt->getValue("path"), '|');
			$this->path = array();

			if	($path != "") {
				$this->path = explode("|", $path);
			}

			$this->current_article_id = $article_id;
			$this->current_category_id = $OOArt->getCategoryId();
	
			return TRUE;
		}

		return FALSE;
	}

	function checkPerm($nav, $depth) {
		return rex_com_auth::checkPerm($nav);
	}

	function addCallback($callback = "", $depth = "") {
		if ($callback != "") {
			$this->callbacks[] = array("callback" => $callback, "depth" => $depth);
		}
	}

	function _checkCallbacks($category, $depth) {
		foreach($this->callbacks as $c) {
			if ($c["depth"] == "" || $c["depth"] == $depth) {
				$callback = $c['callback'];
			
				if (is_string($callback)) {
					$callback = explode('::', $callback, 2);

					if (count($callback) < 2) {
						$callback = $callback[0];
					}
				}

				if (is_array($callback) && count($callback) > 1) {
					list($class, $method) = $callback;

					if (is_object($class)) {
						$result = $class->$method($category, $depth);
					} else {
						$result = $class::$method($category, $depth);
					}
				} else {
					$result = $callback($category, $depth);
				}

				if (!$result) {
					return false;
				}
			}
		}

		return true;
	}
}

