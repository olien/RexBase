<?php
header('Content-Type: text/html; charset=utf-8');

  error_reporting(E_ALL); // error_reporting(0);
  

/* Artikel/Kategorie online? Wenn nein dann auf die Startseite */

if(!isset($_SESSION)) { session_start(); }

 if (!isset($_SESSION[$REX['INSTNAME']]['UID'])) // aber nur wenn nicht im Backend angemeldet
 { if ($this->getValue('status') == 0)
   { if ($this->getValue('startpage') == 0)
     {  // Weiterleitung für Artikel
      header('Location: ' . $REX['SERVER']);
      exit;
     }
    else
   {
    // Weiterleitung für Kategorien
    header('Location: ' . $REX['SERVER']);
    exit;
   }
  }
 }
  
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <?php
  echo seo42::getHtml();
  echo seo42::getLangTags();
  ?> 

  <link rel="shortcut icon" href="assets/img/icons/favicon.ico">
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/img/icons/apple-touch-icon-144x144-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/img/icons/apple-touch-icon-114x114-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/img/icons/apple-touch-icon-72x72-precomposed.png">
  <link rel="apple-touch-icon-precomposed" href="assets/img/icons/apple-touch-icon-57x57-precomposed.png">
  
  
  <meta name="MSSmartTagsPreventParsing" content="no" >
  <meta name="robots" content="index, follow">
  <meta http-equiv="cleartype" content="on">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <!-- http://t.co/dKP3o1e -->
  <meta name="HandheldFriendly" content="True">
  <meta name="MobileOptimized" content="320">
  <meta name="viewport" content="width=device-width, target-densitydpi=160dpi, initial-scale=1.0">
  <!-- Facebook -->
  <meta content="de_DE" />
  <meta content="article" />
  <meta content="Social Meta-Tags in WordPress – Allgemein -" />
  <meta content="Das ist die Facebook-Beschreibung des Artikels über Social Meta-Tags." />
  <meta content="SEO Book" />
  <meta content="assets/img/icons/facebook_twitter-590x434.png" />
  <!-- Twitter -->
  <meta name="twitter:card" content="summary" />
  <meta name="twitter:site" content="@eric108" />
  <meta name="twitter:domain" content="SEO Book" />
  <meta name="twitter:creator" content="@vanseo" />
  <meta name="twitter:image:src" content="assets/img/icons/facebook_twitter-590x434.png" />
  <!-- http://www.geo-tag.de/generator/en.html -->
  <meta name="geo.placename" content="" />
  <meta name="geo.position" content="" />
  <meta name="geo.region" content="" />
  <meta name="ICBM" content="" />
 
  <link rel="stylesheet" href="assets/css/screen.min.cc.css" media="screen, print">
  <link rel="stylesheet" href="assets/css/responsive.min.cc.css" media="screen">
  <link rel="stylesheet" href="assets/css/print.min.cc.css" media="print">
  <!--[if IE]><![endif]-->

  </head>
<?php if ($REX['START_ARTICLE_ID'] == $this->getValue("article_id")) {
echo '<body id="home">'.PHP_EOL;
} else {
echo '<body>'.PHP_EOL;
}

?>
<!--[if lt IE 7]>
<div class="iehinweis"><p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a>.</p> </div>
<![endif]-->

<a href="top"></a>

<?php echo nav42::getNavigationByLevel(0, 2, false, true, false); ?>

REX_ARTICLE[]

<script src="assets/js/vendor/jquery.1.10.2.min.js"></script>
<script src="assets/js/vendor/modernizr.2.7.0.min.js"></script>
<script src="assets/js/vendor/jquery.easing.1.3.min.js"></script>
<script src="assets/js/domscript.js"></script>

</body>
</html>