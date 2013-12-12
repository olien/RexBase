<?php

/**
 * Addonlist
 * @package redaxo4
 * @version svn:$Id$
 */

// ----------------- addons
unset($REX['ADDON']);
$REX['ADDON'] = array();

// ----------------- DONT EDIT BELOW THIS
// --- DYN
$REX['ADDON']['install']['__firephp'] = '0';
$REX['ADDON']['status']['__firephp'] = '0';

$REX['ADDON']['install']['be_dashboard'] = '0';
$REX['ADDON']['status']['be_dashboard'] = '0';

$REX['ADDON']['install']['be_search'] = '1';
$REX['ADDON']['status']['be_search'] = '1';

$REX['ADDON']['install']['be_style'] = '1';
$REX['ADDON']['status']['be_style'] = '1';

$REX['ADDON']['install']['clearcache'] = '0';
$REX['ADDON']['status']['clearcache'] = '0';

$REX['ADDON']['install']['cronjob'] = '0';
$REX['ADDON']['status']['cronjob'] = '0';

$REX['ADDON']['install']['decaf_piwik_tracker'] = '0';
$REX['ADDON']['status']['decaf_piwik_tracker'] = '0';

$REX['ADDON']['install']['developer'] = '0';
$REX['ADDON']['status']['developer'] = '0';

$REX['ADDON']['install']['downloadstats'] = '0';
$REX['ADDON']['status']['downloadstats'] = '0';

$REX['ADDON']['install']['image_manager'] = '1';
$REX['ADDON']['status']['image_manager'] = '1';

$REX['ADDON']['install']['imagecropper'] = '0';
$REX['ADDON']['status']['imagecropper'] = '0';

$REX['ADDON']['install']['import_export'] = '1';
$REX['ADDON']['status']['import_export'] = '1';

$REX['ADDON']['install']['metainfo'] = '1';
$REX['ADDON']['status']['metainfo'] = '1';

$REX['ADDON']['install']['mform'] = '0';
$REX['ADDON']['status']['mform'] = '0';

$REX['ADDON']['install']['mysql_tools'] = '0';
$REX['ADDON']['status']['mysql_tools'] = '0';

$REX['ADDON']['install']['phpmailer'] = '0';
$REX['ADDON']['status']['phpmailer'] = '0';

$REX['ADDON']['install']['rex_markitup'] = '0';
$REX['ADDON']['status']['rex_markitup'] = '0';

$REX['ADDON']['install']['rexpixel'] = '0';
$REX['ADDON']['status']['rexpixel'] = '0';

$REX['ADDON']['install']['seo42'] = '0';
$REX['ADDON']['status']['seo42'] = '0';

$REX['ADDON']['install']['sherlock'] = '0';
$REX['ADDON']['status']['sherlock'] = '0';

$REX['ADDON']['install']['slice_status'] = '0';
$REX['ADDON']['status']['slice_status'] = '0';

$REX['ADDON']['install']['textile'] = '0';
$REX['ADDON']['status']['textile'] = '0';

$REX['ADDON']['install']['url_rewrite'] = '0';
$REX['ADDON']['status']['url_rewrite'] = '0';

$REX['ADDON']['install']['version'] = '0';
$REX['ADDON']['status']['version'] = '0';

$REX['ADDON']['install']['watson'] = '0';
$REX['ADDON']['status']['watson'] = '0';

$REX['ADDON']['install']['xform'] = '0';
$REX['ADDON']['status']['xform'] = '0';

$REX['ADDON']['install']['xoutputfilter'] = '0';
$REX['ADDON']['status']['xoutputfilter'] = '0';
// --- /DYN
// ----------------- /DONT EDIT BELOW THIS

require $REX['INCLUDE_PATH']. '/plugins.inc.php';

foreach(OOAddon::getAvailableAddons() as $addonName)
{
  $addonConfig = rex_addons_folder($addonName). 'config.inc.php';
  if(file_exists($addonConfig))
  {
    require $addonConfig;
  }

  foreach(OOPlugin::getAvailablePlugins($addonName) as $pluginName)
  {
    $pluginConfig = rex_plugins_folder($addonName, $pluginName). 'config.inc.php';
    if(file_exists($pluginConfig))
    {
      rex_pluginManager::addon2plugin($addonName, $pluginName, $pluginConfig);
    }
  }
}

// ----- all addons configs included
rex_register_extension_point('ADDONS_INCLUDED');