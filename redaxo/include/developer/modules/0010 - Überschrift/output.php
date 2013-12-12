<?php

$textile1 = htmlspecialchars_decode('REX_VALUE[1]');
$textile1 = str_replace('<br />', '', $textile1);

if(!$REX['REDAXO']) {
    echo '<REX_VALUE[2]>'.$textile1.'</REX_VALUE[2]>'."\r\n";
} else {
	echo '<b>Grösse: </b>REX_VALUE[2]';
	echo '<br/>';
	echo '<b>Text:</b> REX_VALUE[1]';
}
?>