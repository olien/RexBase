<?php
/*
uninstall.inc.php

@copyright Copyright (c) 2012 by Doerr Softwaredevelopment
@author mail[at]joachim-doerr[dot]com Joachim Doerr

@package redaxo4
@version 1.3
*/

// PLUGIN IDENTIFIER AUS GET PARAMS
////////////////////////////////////////////////////////////////////////////////
$strPluginName = rex_request('pluginname','string');


$REX['ADDON']['install'][$strPluginName] = 0;
