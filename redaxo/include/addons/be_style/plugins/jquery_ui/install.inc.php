<?php
/*
install.inc.php

@copyright Copyright (c) 2012 by Doerr Softwaredevelopment
@author mail[at]joachim-doerr[dot]com Joachim Doerr

@package redaxo4
@version 1.3
*/

// PLUGIN IDENTIFIER AUS GET PARAMS
////////////////////////////////////////////////////////////////////////////////
$strPluginName = rex_request('pluginname','string');


// LOAD I18N FILE
////////////////////////////////////////////////////////////////////////////////
$I18N->appendFile(dirname(__FILE__) . '/lang/');


// INSTALL CONDITIONS
////////////////////////////////////////////////////////////////////////////////
$requiered_REX = '4.2.0';
$requiered_PHP = 5;
$do_install = true;


// CHECK REDAXO VERSION
////////////////////////////////////////////////////////////////////////////////
$this_REX = $REX['VERSION'].'.'.$REX['SUBVERSION'].'.'.$REX['MINORVERSION'] = "1";
if(version_compare($this_REX, $requiered_REX, '<'))
{
	$REX['ADDON']['installmsg'][$strPluginName] = str_replace('###version###', $requiered_REX, $I18N->msg($strPluginName.'_install_need_rex'));
	$REX['ADDON']['install'][$strPluginName] = 0;
	$do_install = false;
}


// CHECK PHP VERSION
////////////////////////////////////////////////////////////////////////////////
if (intval(PHP_VERSION) < $requiered_PHP)
{
	$REX['ADDON']['installmsg'][$strPluginName] = str_replace('###version###', $requiered_PHP, $I18N->msg($strPluginName.'_install_need_php'));
	$REX['ADDON']['install'][$strPluginName] = 0;
	$do_install = false;
}


// DO INSTALL
////////////////////////////////////////////////////////////////////////////////
if ($do_install)
{
	$REX['ADDON']['install'][$strPluginName] = 1;
}
