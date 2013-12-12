<?php

$objForm = new mform();

$objForm->addHeadline('Überschrift');

$objForm->addTextAreaField(1,array('label'=>'Text','style'=>'width:515px;height:40px'));

$objForm->addSelectField(2,array('h1'=>'H1 (pro Seite nur einmal verwenden!)','h2'=>'H2','h3'=>'H3','h4'=>'H4','h5'=>'H5','h6'=>'H6'),array('label'=>'Grösse'));

echo $objForm->show_mform();

?>

