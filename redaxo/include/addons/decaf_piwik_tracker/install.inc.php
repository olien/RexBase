<?php
/**
 * piwikTracker Addon
 *
 * @author DECAF
 * @version $Id$
 */

$mypage = 'decaf_piwik_tracker';
$base_path = $REX['INCLUDE_PATH'] .'/addons/'.$mypage;
require_once($base_path.'/compat.inc.php');

if ($REX['REDAXO'])
{
  if (!isset($lang) || $lang == 'default') 
  {
    $be_lang = 'de_de_utf8';
  } 
  else 
  {
    $be_lang = $lang;
  }
  $piwik_I18N = new i18n($be_lang, $REX['INCLUDE_PATH'].'/addons/'.$mypage.'/lang/');
  $piwik_I18N->loadTexts();
}


$error = false;

// check if /config is writable
if (!is_writable($base_path.'/config/'))
{
  echo rex_warning($piwik_I18N->msg('piwik_config_dir_locked'));
  $error = true;
}
else 
{
  // check if config.ini exists
  $file = $base_path.'/config/config.ini.php';
  if (!file_exists($file)) 
  {
    $cfg = parse_ini_file($base_path.'/config/_config.ini.php');
    $tpl = rex_get_file_contents($base_path.'/config/_config.ini.php');
    foreach($cfg as $key => $val)
    {
      $search[]   = '@@'.$key.'@@';
      $replace[]  = '';
    }
    $config_str = str_replace($search, $replace, $tpl);
    file_put_contents($base_path.'/config/config.ini.php', $config_str);
  }
  // now copy widgets.ini.php (if not exists)
  $file = $base_path.'/config/widgets.ini.php';
  if (!file_exists($file)) 
  {
    $content = rex_get_file_contents($base_path.'/config/_widgets.ini.php');
    file_put_contents($file, $content);
  }
}

if (!$error) 
{
  $REX['ADDON']['install'][$mypage] = true;
}