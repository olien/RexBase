<h1><?php echo $I18N->msg('seo42_help_codeexamples'); ?></h1>

<?php
$codeExample1 = '<?php
echo rex_getUrl(42);
// --> ' . seo42::getUrlStart() . 'questions/the-ultimate-answer.html

echo seo42::getFullUrl(42);
// --> ' . seo42::getServerUrl() . 'questions/the-ultimate-answer.html

echo seo42::getMediaFile("image.png");
// --> ' . seo42::getUrlStart() . $REX['MEDIA_DIR'] . '/image.png

echo seo42::getDownloadFile("doc.pdf");
// --> ' . seo42::getUrlStart() . seo42::downloadDir . '/doc.pdf
?>';

$codeExample2 = '
<link rel="stylesheet" href="<?php echo seo42::getCombinedCSSFile("combined.css", array("foo.css", "bar.scss", "batz.less")); ?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo seo42::getCSSFile("default.css"); ?>" type="text/css" media="screen,print" />
<link rel="stylesheet" href="<?php echo seo42::getCSSFile("theme.scss"); ?>" type="text/css" media="screen,print" />
<link rel="stylesheet" href="<?php echo seo42::getCSSFile("stuff.less", array("color" => "red", "base" => "960px")); ?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo seo42::getCSSFile("http://fonts.googleapis.com/css?family=Fjalla+One"); ?>" type="text/css" media="screen" />
<link rel="shortcut icon" href="<?php echo seo42::getImageFile("favicon.ico"); ?>" type="image/x-icon" />

<script type="text/javascript" src="<?php echo seo42::getCombinedJSFile("combined.js", array("jquery.magnific-popup.min.js", "jquery.nivo-slider.min.js")); ?>"></script>
<script type="text/javascript" src="<?php echo seo42::getJSFile("http://codeorigin.jquery.com/jquery-2.0.3.min.js"); ?>"></script>
<script type="text/javascript" src="<?php echo seo42::getJSFile("init.js"); ?>"></script>
<script type="text/javascript"><?php echo seo42::getJSCodeFromTemplate(5); ?></script>
';

$codeExample3 = '<?php
echo seo42::getImageManagerFile("image.png", "rex_mediapool_detail"); 
// --> ' . seo42::getUrlStart() . seo42::imageTypesDir . '/rex_mediapool_detail/image.png
?>';

$codeExample4 = '<!DOCTYPE html>
<html lang="<?php echo seo42::getLangCode(); ?>">';

$codeExample5 = '<title><?php echo seo42::getTitle(rex_string_table::getString("website_name")); ?></title>';

$codeExample6 = '<?php 
// ' . $I18N->msg('seo42_help_codeexamples_ex6_comment1') . '
echo seo42::getNavigationByLevel(0, 1);

// ' . $I18N->msg('seo42_help_codeexamples_ex6_comment2') . '
echo seo42::getNavigationByLevel(1, 3);

// ' . $I18N->msg('seo42_help_codeexamples_ex6_comment3_1') . '
// ' . $I18N->msg('seo42_help_codeexamples_ex6_comment3_2') . '
// ' . $I18N->msg('seo42_help_codeexamples_ex6_comment3_3') . '
// ' . $I18N->msg('seo42_help_codeexamples_ex6_comment3_4') . '
echo seo42::getNavigationByLevel(0, 2, true, false, true);

// ' . $I18N->msg('seo42_help_codeexamples_ex6_comment4_1') . '
// ' . $I18N->msg('seo42_help_codeexamples_ex6_comment4_2') . '
echo seo42::getNavigationByCategory(42, 2);

// ' . $I18N->msg('seo42_help_codeexamples_ex6_comment5_1') . '
// ' . $I18N->msg('seo42_help_codeexamples_ex6_comment5_2') . '
// ' . $I18N->msg('seo42_help_codeexamples_ex6_comment5_3') . '
// ' . $I18N->msg('seo42_help_codeexamples_ex6_comment5_4') . '
// ' . $I18N->msg('seo42_help_codeexamples_ex6_comment5_5') . '
// ' . $I18N->msg('seo42_help_codeexamples_ex6_comment5_6') . '
// ' . $I18N->msg('seo42_help_codeexamples_ex6_comment5_7') . '
// ' . $I18N->msg('seo42_help_codeexamples_ex6_comment5_8') . '
// ' . $I18N->msg('seo42_help_codeexamples_ex6_comment5_9') . '
// ' . $I18N->msg('seo42_help_codeexamples_ex6_comment5_10') . '
// ' . $I18N->msg('seo42_help_codeexamples_ex6_comment5_11') . '
echo seo42::getNavigationByCategory(42, 2, true, true, false, "current", "nav", "sf-menu", "cat_css_id", "cat_css_class", function($cat, $depth) {
	if ($depth == 1) {
		return htmlspecialchars($cat->getName());
	} else {
		return \'<a href="\' . $cat->getUrl() . \'">\' . htmlspecialchars($cat->getName()) . \'</a>\';
	}
});

// ' . $I18N->msg('seo42_help_codeexamples_ex6_comment6_1') . '
// ' . $I18N->msg('seo42_help_codeexamples_ex6_comment6_2') . '
// ' . $I18N->msg('seo42_help_codeexamples_ex6_comment6_3') . '
// ' . $I18N->msg('seo42_help_codeexamples_ex6_comment6_4') . '
// ' . $I18N->msg('seo42_help_codeexamples_ex6_comment6_5') . '
// ' . $I18N->msg('seo42_help_codeexamples_ex6_comment6_6') . '
// ' . $I18N->msg('seo42_help_codeexamples_ex6_comment6_7') . '
echo seo42::getLangNavigation("lang-nav", "current", true, false, true, true);
?>';

$codeExample7 = '<?php
// --> ' . strtoupper($I18N->msg('seo42_help_codeexamples_ex7_comment1')) . '
class seo42_ex extends seo42
	public static function getTitle($websiteName = "") {
		if ($websiteName == "") {
			// use default website name if user did not set different one
			$websiteName = self::getWebsiteName();
		}

		if (self::getArticleValue("seo_title") == "") {
			// use article name as title
			$titlePart = self::getArticleName();
		} else {
			// use title that user defined
			$titlePart = self::getArticleValue("seo_title");
		}
		
		if (self::getArticleValue("seo_ignore_prefix") == "1") {
			// no prefix, just the title
			$fullTitle = $titlePart;
		} else { 
			if (self::isStartArticle()) {
				// the start article shows the website name first
				$fullTitle = $websiteName . " " . self::getTitleDelimiter() . " " . $titlePart;
			} else {
				// all other articles will show title first
				$fullTitle = $titlePart . " " . self::getTitleDelimiter() . " " . $websiteName;
			}
		 }

		// --> ' . strtoupper($I18N->msg('seo42_help_codeexamples_ex7_comment2')) . '
		return strtolower(htmlspecialchars($fullTitle));
	}
}

// --> ' . strtoupper($I18N->msg('seo42_help_codeexamples_ex7_comment3')) . '
echo seo42_ex::getTitle(); // ' . $I18N->msg('seo42_help_codeexamples_ex7_comment4') . '
?>';

$codeExample8 = '<?php echo seo42::getDebugInfo(); ?>';

?>

<h2>1) <?php echo $I18N->msg('seo42_help_codeexamples_title1'); ?></h2>
<p><?php echo $I18N->msg('seo42_help_codeexamples_description1'); ?></p>
<?php rex_highlight_string($codeExample1); ?>

<h2>2) <?php echo $I18N->msg('seo42_help_codeexamples_title2'); ?></h2>
<p><?php echo $I18N->msg('seo42_help_codeexamples_description2'); ?></p>
<?php rex_highlight_string($codeExample2); ?>

<h2>3) <?php echo $I18N->msg('seo42_help_codeexamples_title3'); ?></h2>
<p><?php echo $I18N->msg('seo42_help_codeexamples_description3'); ?></p>
<?php rex_highlight_string($codeExample3); ?>

<h2>4) <?php echo $I18N->msg('seo42_help_codeexamples_title4'); ?></h2>
<p><?php echo $I18N->msg('seo42_help_codeexamples_description4'); ?></p>
<?php rex_highlight_string($codeExample4); ?>

<h2>5) <?php echo $I18N->msg('seo42_help_codeexamples_title5'); ?></h2>
<p><?php echo $I18N->msg('seo42_help_codeexamples_description5'); ?></p>
<?php rex_highlight_string($codeExample5); ?>

<h2>6) <?php echo $I18N->msg('seo42_help_codeexamples_title6'); ?></h2>
<p><?php echo $I18N->msg('seo42_help_codeexamples_description6'); ?></p>
<?php rex_highlight_string($codeExample6); ?>

<h2>7) <?php echo $I18N->msg('seo42_help_codeexamples_title7'); ?></h2>
<p><?php echo $I18N->msg('seo42_help_codeexamples_description7'); ?></p>
<?php rex_highlight_string($codeExample7); ?>

<h2>8) <?php echo $I18N->msg('seo42_help_codeexamples_title8'); ?></h2>
<p><?php echo $I18N->msg('seo42_help_codeexamples_description8'); ?></p>
<?php rex_highlight_string($codeExample8); ?>



