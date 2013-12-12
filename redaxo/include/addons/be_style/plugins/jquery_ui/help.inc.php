<?php
/*
help.inc.php

@copyright Copyright (c) 2012 by Doerr Softwaredevelopment
@author mail[at]joachim-doerr[dot]com Joachim Doerr

@package redaxo4
@version 1.3
*/


// LOAD I18N FILE
////////////////////////////////////////////////////////////////////////////////
if (!OOPlugin::isInstalled('be_style', 'jquery_ui') or !OOPlugin::isActivated('be_style', 'jquery_ui')) {
  $I18N->appendFile(dirname(__FILE__) . '/lang/');
}


// HELP CONTENT
////////////////////////////////////////////////////////////////////////////////
?>
<h2 style="margin-bottom:10px;"><?php echo $I18N->msg('jquery_ui_help_headline'); ?></h2>
<h3 style="margin:10px 0 0 0"><?php echo $I18N->msg('jquery_ui_system_prerequisites_headline'); ?></h3>
<p><?php echo $I18N->msg('jquery_ui_system_info_text'); ?></p>
<h3 style="margin:10px 0 0 0"><?php echo $I18N->msg('jquery_ui_install_headline'); ?></h3>
<p><?php echo $I18N->msg('jquery_ui_install_text'); ?></p>
<ol style="margin-left:20px;">
<li><?php echo $I18N->msg('jquery_ui_install_1'); ?></li>
<li><?php echo $I18N->msg('jquery_ui_install_2'); ?></li>
</ol>
<h3 style="margin:10px 0 0 0"><?php echo $I18N->msg('jquery_ui_errors_headline'); ?></h3>
<p><?php echo $I18N->msg('jquery_ui_default_errors_headline'); ?></p>
<ol style="margin-left:20px;">
<li><?php echo $I18N->msg('jquery_ui_default_errors_text'); ?>
<ul style="margin-left:20px;">
<li><?php echo $I18N->msg('jquery_ui_default_errors_1'); ?></li>
<li><?php echo $I18N->msg('jquery_ui_default_errors_2'); ?></li>
</ul>
</li>
</ol>
<p><?php echo $I18N->msg('jquery_ui_default_errors_solution_text'); ?></p>
<h3 style="margin:10px 0 0 0"><?php echo $I18N->msg('jquery_ui_plugin_function_headline'); ?></h3>
<p><?php echo $I18N->msg('jquery_ui_plugin_function_text'); ?></p>
<?php


// INCLUDE DEMO OUTPUT
////////////////////////////////////////////////////////////////////////////////
if ($REX['ADDON']['plugins']['be_style']['status']['jquery_ui'] == true)
{
  echo '<h3 style="margin:30px 0 0 0">jQuery UI Demos</h3>';
  require_once( 'pages/jquery-ui-demo-modul.php' );
}
