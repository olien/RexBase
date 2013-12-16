<?php

$objForm = new mform();

$objForm->addHeadline('Abstand einfügen');

$objForm->addTextField(1,array('label'=>'Höhe in Pixel','style'=>'width:150px'));

echo $objForm->show_mform();


?>