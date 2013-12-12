<?php
/**
 * piwikTracker Addon
 *
 * @author DECAF
 * @version $Id$
 */

rex_register_extension('OUTPUT_FILTER', 'decaf_piwik_tracker');

/**
 * adds the js code to the html <head> section
 */
function decaf_piwik_tracker($params) 
{
  $mypage = 'decaf_piwik_tracker';
  global $REX;
  if (file_exists($REX['INCLUDE_PATH']. '/addons/'.$mypage.'/config/config.ini.php')) 
  {
    $piwik_config = parse_ini_file($REX['INCLUDE_PATH']. '/addons/'.$mypage.'/config/config.ini.php', true);
    $content = $params['subject'];

    // Frontend
    $debugMsg = $trackingSnippet = NULL;
    if ($piwik_config['piwik']['tracker_url'] && $piwik_config['piwik']['site_id'])
    {
      // JavaScript tracking
      if ($piwik_config['piwik']['tracking_method'] == 'Javascript') 
      {
        if($_SESSION[$REX['INSTNAME']]['UID'] || isset($_COOKIE['redaxo_piwiktracker_ignore']))
        {
          $debugMsg = 'Piwik Tracker: JavaScript. Did not track REDAXO user.';
        } else {
          $debugMsg = 'Piwik Tracker: JavaScript.';
          $trackingSnippet = "
            <!-- Piwik -->
            <script type=\"text/javascript\">
            var pkBaseURL = \"".$piwik_config['piwik']['tracker_url']."\" + \"/\";
            document.write(unescape(\"%3Cscript src='\" + pkBaseURL + \"piwik.js' type='text/javascript'%3E%3C/script%3E\"));
            </script><script type=\"text/javascript\">
            try {
            var piwikTracker = Piwik.getTracker(pkBaseURL + \"piwik.php\", ".$piwik_config['piwik']['site_id'].");
            piwikTracker.trackPageView();
            piwikTracker.enableLinkTracking();
            } catch( err ) {}
            </script><noscript><p><img src=\"".$piwik_config['piwik']['tracker_url']."piwik.php?idsite=".$piwik_config['piwik']['site_id']."\" style=\"border:0\" alt=\"\" /></p></noscript>
            <!-- End Piwik Tag -->
          ";
        }
      }

      // PHP tracking
      if ($piwik_config['piwik']['tracking_method'] == 'PHP' && ini_get('allow_url_fopen')) 
      {
        if($_SESSION[$REX['INSTNAME']]['UID'] || isset($_COOKIE['redaxo_piwiktracker_ignore']))
        {
          $debugMsg = 'Piwik Tracker: PHP. Did not track REDAXO user.';
        } else {
          $debugMsg = 'Piwik Tracker: PHP.';
          require_once($REX['INCLUDE_PATH']. '/addons/'.$mypage.'/classes/PiwikTracker.php');
          PiwikTracker::$URL = $piwik_config['piwik']['tracker_url'];
          $piwikTracker = new PiwikTracker(  $idSite = $piwik_config['piwik']['site_id']);
          preg_match('/<title>(.*)<\/title>/U', $content, $hits);
          if ($piwikTracker->doTrackPageView($hits[1]) != true)
          {
            $debugMsg .= ' FAILURE.';
          } else {
            $debugMsg .= ' SUCCESS.';
          }
        }
      }

      $content = str_replace("</body>", $trackingSnippet.PHP_EOL."<!-- ".$debugMsg." -->".PHP_EOL."</body>", $content);
      return $content;
    }
  }
}
