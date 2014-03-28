<?php

$objForm = new mform();

$objForm->addHeadline('Linkliste');

$objForm->addLinklistField(1,array('label'=>'Seiten'));
echo $objForm->show_mform();

?>

