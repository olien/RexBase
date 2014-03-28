<?php
/**
 * basecondition ~ less-framework ~ v3.1
 * 
 * @link       git.io/OJYZgw
 * @copyright  copyright 2013 ~ www.basecondition.com
 * @autor      Joachim Doerr ~ hello@basecondition.com
 * @license    licensed under MIT or GPLv3
 */
 
/**
 * set default timeszone
 */
if (function_exists("date_default_timezone_set")) {
    date_default_timezone_set('Europe/Berlin');
}

/**
 * check $_REQEUST and initialize process
 * 
 * @param  string $_REQUEST['f']    $folder . $filname
 * @return string                   getBscUnitFile::getIt
 */
if ($_REQUEST['f'] != '') {
    require_once(dirname( __FILE__ ) . "/processor/lib/getfile.php");
    $objFile = new getBscUnitFile();
    print $objFile->getIt($_REQUEST['f']);
}