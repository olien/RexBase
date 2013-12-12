<?php
/**
 * Download Statistik - config.inc.php
 * @author m[dot]lorch[at]it-kult[dot]de Markus Lorch
 * @package redaxo4
 * @version 1.0
 */

#error_reporting(E_ALL);
#ini_set("display_errors",1);

$mypage = "downloadstats";
$REX['ADDON']['name'][$mypage] = 'Download Statistik';
$REX['ADDON']['version'][$mypage] = '1.0';
$REX['ADDON']['author'][$mypage] = 'Markus Lorch / ITKULT';
$REX['ADDON']['supportpage'][$mypage] = 'http://www.it-kult.de';
$REX['ADDON']['basedir'][$mypage] = dirname(__FILE__);

$REX['PERM'][] = $mypage.'[]';
?>
