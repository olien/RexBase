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
<html class="no-js">
  <head>
    <meta charset="utf-8">
    <?php
      echo seo42::getHtml();
      echo seo42::getLangTags();
    ?>
  <meta name="MSSmartTagsPreventParsing" content="no" >
  <meta name="robots" content="index, follow">
  <meta http-equiv="cleartype" content="on">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <!-- http://t.co/dKP3o1e -->
  <meta name="HandheldFriendly" content="True">
  <meta name="MobileOptimized" content="320">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
 
  <!-- Place favicon.ico and apple-touch-icon(s) in the root directory -->
        
  <link rel="stylesheet" href="assets/css/screen.min.cc.css" media="screen, print">
  <link rel="stylesheet" href="assets/css/responsive.min.cc.css" media="screen">
  <link rel="stylesheet" href="assets/css/print.min.cc.css" media="print">
  <!--[if IE]><![endif]-->
  
  <script src="assets/js/vendor/modernizr-2.7.1.min.js"></script>

  </head>
<?php if ($REX['START_ARTICLE_ID'] == $this->getValue("article_id")) {
echo '<body id="home">'.PHP_EOL;
} else {
echo '<body>'.PHP_EOL;
}

?>
<!--[if lt IE 9]>
  <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

<div id="page">
  <div id="main">
    <header>
      <figure><!-- logo --></figure>
      <nav>
        <?php echo nav42::getNavigationByLevel(0, 2, false, true, false); ?>
      </nav>
    </header>
    <article>
    REX_ARTICLE[]
      <section><!-- article part 1 --></section>
      <section><!-- article part 2 --></section>
    </article>
    <aside>
      <section><!-- aside --></section>
    </aside>
  </div>
  <footer>
    <section><!-- footer --></section>
  </footer>
</div>

<script src="assets/js/vendor/jquery.1.11.0.min.js"></script>
<script src="assets/js/vendor/jquery.easing.1.3.min.js"></script>
<script src="assets/js/domscript.js"></script>

<!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
<!-- script>
 (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
            function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
            e=o.createElement(i);r=o.getElementsByTagName(i)[0];
            e.src='//www.google-analytics.com/analytics.js';
            r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
            ga('create','UA-XXXXX-X');ga('send','pageview');
</script -->
</body>
</html>