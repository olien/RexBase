<?php
class rex_website_manager_utils {
	public static function addFrontendLinkToMetaMenu($params) {
		global $REX, $I18N;

		// meta menu frontend link
		$params['subject'][] = '<li><a id="frontend-link" href="' . $REX['WEBSITE_MANAGER']->getCurrentWebsite()->getUrl() . '" target="_blank">' . $I18N->msg('website_manager_frontend_link') . '</a></li>';

		return $params['subject'];
	}

	public static function addToOutputFilter($params) {
		global $REX, $I18N;

		// website select
		if (rex_request('page') != 'mediapool' && rex_request('page') != 'linkmap') {
			$params['subject']  = str_replace('<div id="rex-website">', '<div id="rex-website">' . self::getWebsiteSelect(), $params['subject']);
		}

		// website name frontend link
		if ($REX['WEBSITE_MANAGER_SETTINGS']['show_website_name_frontend_link']) {
			// don't show if there is only one mediapool
			if (!(rex_request('page') == 'mediapool' && $REX['WEBSITE_MANAGER_SETTINGS']['identical_media'])) {
				$params['subject']  = str_replace('<div id="rex-website">', '<div id="rex-website">' . self::getWebsiteNameFrontendLink(), $params['subject']);
			}
		}

		// colorpicker
		if (rex_request('page') == 'website_manager' && (rex_request('func') == 'edit' || rex_request('func') == 'add')) {
			if (rex_request('func') == 'add') {
				$color = 'color: "' . rex_website::defaultColor . '", ';
				$colorInit = 'jQuery("#color-picker").attr("value", "' . rex_website::defaultColor . '"); ';
			} else {
				$color = '';
				$colorInit = '';
			}

			$replace = PHP_EOL . '<!-- BEGIN website_manager -->' . PHP_EOL;
			$replace .= '<link rel="stylesheet" type="text/css" href="../' . $REX['MEDIA_ADDON_DIR'] . '/website_manager/spectrum.css" />' . PHP_EOL;
			$replace .= '<script type="text/javascript" src="../' . $REX['MEDIA_ADDON_DIR'] . '/website_manager/spectrum.js"></script>' . PHP_EOL;
			$replace .= '<script type="text/javascript">' . $colorInit . 'jQuery("#color-picker").spectrum({ ' . $color . ' showInput: true,  preferredFormat: "hex", clickoutFiresChange: true, showPalette: true, palette: [ ["' . rex_website::defaultColor . '", "#d1513c", "#8eb659", "#dfaa3c", "#cb41d2"] ],  chooseText: "' . $I18N->msg('website_manager_website_colorpicker_choose') . '", cancelText: "' . $I18N->msg('website_manager_website_colorpicker_cancel') . '" });</script>' . PHP_EOL;
			$replace .= '<!-- END website_manager -->';

			$params['subject']  = str_replace('</body>', $replace . '</body>', $params['subject']);
		}

		// website specific favicon
		if ($REX['WEBSITE_MANAGER_SETTINGS']['colorize_favicon'] && $REX['WEBSITE_MANAGER']->getCurrentWebsite()->getColor() != '') {
			$replace = '<link rel="shortcut icon" href="../' . $REX['MEDIA_ADDON_DIR'] . '/website_manager/' . $REX['WEBSITE_MANAGER']->getCurrentWebsite()->getIcon() . '" />' . PHP_EOL;

			$params['subject']  = str_replace('<link rel="shortcut icon" href="media/favicon.ico" />', $replace, $params['subject']);
		}
		
		return $params['subject'];
	}

	protected static function getWebsiteSelect() {
		global $REX;

		$websiteSelectOptions = '';

		foreach ($REX['WEBSITE_MANAGER']->getWebsites() as $website) {
			$selected = '';

			if ($REX['WEBSITE_MANAGER']->getCurrentWebsiteId() == $website->getId()) {
				$selected = 'selected="selected"';
			}

			if ($REX['WEBSITE_MANAGER_SETTINGS']['ignore_permissions'] || (isset($REX['USER']) && $REX['USER']->isAdmin()) || (isset($REX['USER']) && $REX['USER']->hasPerm($website->getPermission()))) {
				$websiteSelectOptions .= '<option value="' . $website->getId() . '" ' . $selected . ' data-imagesrc="' . $website->getIconUrl() . '" data-description="' . $website->getTitle() . '">' . $website->getDomain() . '</option>';
			}
		}

		// https://github.com/RexDude/website_manager/issues/30
		if (rex_request('page') != 'content') {
			$inputValuePage = rex_request('page');
			$inputValueSubpage = rex_request('subpage');
			$inputValueChapter = rex_request('chapter');
		} else {
			$inputValuePage = '';
			$inputValueSubpage = '';
			$inputValueChapter = '';
		}

		$websiteSelect = '
			<div id="website-select">
				<form method="post" action="index.php">
					<input type="hidden" name="page" value="' . $inputValuePage . '" />
					<input type="hidden" name="subpage" value="' . $inputValueSubpage . '" />
					<input type="hidden" name="chapter" value="' . $inputValueChapter . '" />
					<input type="hidden" name="new_website_id" id="new_website_id" value="" />
					<fieldset>
						<select id="website-selector" size="1" name="website-selector">' . $websiteSelectOptions . '</select>			
					</fieldset>
				</form>
			</div>';

		return $websiteSelect;
	}

	protected static function getWebsiteNameFrontendLink() {
		global $REX;

		if (strlen($REX['WEBSITE_MANAGER']->getCurrentWebsite()->getTitle()) > 40) {
			$class = ' small';
		} else {
			$class = '';
		}

		return '<h1 class="website-name-frontend-link' . $class . '"><a href="' . $REX['WEBSITE_MANAGER']->getCurrentWebsite()->getUrl() . '" onclick="window.open(this.href); return false">' . $REX['SERVERNAME'] . '</a></h1>';
	}

	protected static function addJS() {
		global $REX;

		$out = '';
		$longestDomainName = '';

		foreach($REX['WEBSITE_MANAGER']->getWebsites() as $website) {
			if (strlen($website->getDomain()) > strlen($longestDomainName)) {
				$longestDomainName = $website->getDomain();
			}
		}

		// disable fields in redaxo system page
		if (rex_request('page') == 'specials') {
			$out .= '
				<script type="text/javascript">
				jQuery(function($) {
					$("#rex-form-servername,#rex-form-server,#LINK_1_NAME,#LINK_2_NAME,#rex-form-default-template-id").attr("disabled", true);
					$(".rex-widget a").attr("onclick", "");
				});
				</script>
			';
		}

		// ddslick website selector
		$out .= '
			<script type="text/javascript">
			jQuery(function($) {
				$("body").append(\'<span id="longest-domainname" style="font-weight: bold; font-size: 13px; display: none;">' . $longestDomainName . '</span>\');

				$("#website-selector").ddslick({
					width: Math.max(260, jQuery("#longest-domainname").width() + 60),
					truncateDescription: true,
					imagePosition: "left",
					onSelected: function(data) { 
						if (data.selectedIndex >= 0) {
							$("#new_website_id").val(data.selectedData.value); // fix as otherwise value of select field wont be accepted
							$("#website-select form").submit();
						}  
					}
				});

				$("#website-select").show();
			});
			</script>
		';

		return $out;
	}

	public static function fixArticlePreviewLink($params) {
		global $REX;

		$lastElement = count($params['subject']) - 1;

		$params['subject'][$lastElement] = preg_replace("/(?<=href=(\"|'))[^\"']+(?=(\"|'))/", $REX['WEBSITE_MANAGER']->getCurrentWebsite()->getUrl() . self::getTrimmedUrl(), $params['subject'][$lastElement]);

		return $params['subject'];
	}

	protected static function getTrimmedUrl($id = '', $clang = '', $params = '', $divider = '&amp;') {
		return ltrim(rex_getUrl($id, $clang, $params, $divider), "./");
	}

	public static function appendToPageHeader($params) {
		global $REX;

		$insert = '<!-- BEGIN website_manager -->' . PHP_EOL;

		// color bar
		if ($REX['WEBSITE_MANAGER_SETTINGS']['show_color_bar']) {
			// don't show if there is only one mediapool
			if (!(rex_request('page') == 'mediapool' && $REX['WEBSITE_MANAGER_SETTINGS']['identical_media'])) {
				$insert .= '<style>#rex-navi-logout { border-bottom: 10px solid ' . $REX['WEBSITE_MANAGER']->getCurrentWebsite()->getColor() . '; }</style>' . PHP_EOL;
			}
		}

		// color of links in website select box
		$insert .= '<style>.dd-selected-text { color: ' . $REX['WEBSITE_MANAGER']->getCurrentWebsite()->getColor() . '; }</style>' . PHP_EOL;

		// general css file
		$insert .= '<link rel="stylesheet" type="text/css" href="../' . $REX['MEDIA_ADDON_DIR'] . '/website_manager/website_manager.css" />' . PHP_EOL;

		// ddslick js plugin for website select box
		$insert .= '<script type="text/javascript" src="../' . $REX['MEDIA_ADDON_DIR'] . '/website_manager/jquery.ddslick.min.js"></script>' . PHP_EOL;

		// js inits and stuff
		$insert .= self::addJS();

		$insert .= '<!-- END website_manager -->';
	
		return $params['subject'] . PHP_EOL . $insert;
	}

	public static function createDynFile($file) {
		$fileHandle = fopen($file, 'w');

		fwrite($fileHandle, "<?php\r\n");
		fwrite($fileHandle, "// --- DYN\r\n");
		fwrite($fileHandle, "// --- /DYN\r\n");

		fclose($fileHandle);
	}

	public static function sanitizeUrl($url) {
		return preg_replace('@^https?://|/.*|[^\w.-]@', '', $url);
	}

	public static function getFormValues($form, $fields) {
		$values = array();

		$elements = $form->getFieldsetElements();

		foreach($elements as $fieldsetName => $fieldsetElements) {
			foreach($fieldsetElements as $field) {
				if (in_array($field->getFieldName(), $fields)) {
					$values[$field->getFieldName()] = $field->getValue();
				}
			}
		}

		return $values;
	}

	public static function getLastInsertedId($sql) {
		return $sql->last_insert_id;
	}

	public static function rrmdir($dir) {
		foreach(glob($dir . '/*') as $file) {
		    if (is_dir($file)) {
		        self::rrmdir($file);
			} else {
		        unlink($file);
			}
		}

		rmdir($dir);
	}

	public static function initPrioSwitch() {
		global $REX;

		// include main class
		if (!class_exists('rex_prio_switch')) {
			include($REX['INCLUDE_PATH'] . '/addons/website_manager/classes/class.rex_prio_switch.inc.php');
		}

		// include extended class for use in this addon
		include($REX['INCLUDE_PATH'] . '/addons/website_manager/classes/class.rex_website_manager_prio_switch.inc.php');

		// for ajax call: update prio in db if necessary
		rex_website_manager_prio_switch::handleAjaxCall('website_manager', 'update_websites_prio', 'rex_website', 'id', false);
	}

	public static function hex2rgb($hex) {
	   $hex = str_replace("#", "", $hex);

	   if(strlen($hex) == 3) {
		  $r = hexdec(substr($hex,0,1).substr($hex,0,1));
		  $g = hexdec(substr($hex,1,1).substr($hex,1,1));
		  $b = hexdec(substr($hex,2,1).substr($hex,2,1));
	   } else {
		  $r = hexdec(substr($hex,0,2));
		  $g = hexdec(substr($hex,2,2));
		  $b = hexdec(substr($hex,4,2));
	   }
	   $rgb = array($r, $g, $b);
	   //return implode(",", $rgb); // returns the rgb values separated by commas
	   return $rgb; // returns an array with the rgb values
	}

	public static function rgb2hex($rgb) {
	   $hex = "#";
	   $hex .= str_pad(dechex($rgb[0]), 2, "0", STR_PAD_LEFT);
	   $hex .= str_pad(dechex($rgb[1]), 2, "0", STR_PAD_LEFT);
	   $hex .= str_pad(dechex($rgb[2]), 2, "0", STR_PAD_LEFT);

	   return $hex; // returns the hex value including the number sign (#)
	}

	public static function print_r_pretty($arr, $first = true, $tab = 0) {
		$output = "";
		$tabsign = ($tab) ? str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;',$tab) : '';

		if ($first) $output .= "<pre>";

		foreach($arr as $key => $val)
		{
		    switch (gettype($val))
		    {
		        case "array":
		            $output .= $tabsign."[".htmlspecialchars($key)."] => <br>".$tabsign."(<br>";
		            $tab++;
		            $output .= self::print_r_pretty($val,false,$tab);
		            $tab--;
		            $output .= $tabsign.")<br>";
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

	public static function logQuery(&$log, &$sql, $query) {
		$sql->setQuery($query);

		if ($sql->getError() == '') {
			$log->logInfo('[EXECUTE QUERY] ' . $query);
		} else {
			$log->logError('[EXECUTE QUERY] ' . $query);
		}
	}

	public static function logMkDir(&$log, $path) {
		global $REX;

		if (mkdir($path, $REX['DIRPERM'])) {
			$log->logInfo('[CREATE DIR] ' . $path);
		} else {
			$log->logError('[CREATE DIR] ' . $path);
		}
	}
}
