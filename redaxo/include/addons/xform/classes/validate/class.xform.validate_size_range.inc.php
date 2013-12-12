<?php

/**
 * XForm
 * @author jan.kristinus[at]redaxo[dot]org Jan Kristinus
 * @author <a href="http://www.yakamara.de">www.yakamara.de</a>
 */

class rex_xform_validate_size_range extends rex_xform_validate_abstract
{

  function enterObject()
  {
    if ($this->params['send'] == '1') {

      // Wenn leer, dann alles ok
      if ($this->obj_array[0]->getValue() == '')
        return;

      $w = false;

      $minsize = -1;
      if ($this->getElement(3) != '')
        $minsize = (int) $this->getElement(3);

      $maxsize = -1;
      if ($this->getElement(4) != '')
        $maxsize = (int) $this->getElement(4);

      $size = strlen($this->obj_array[0]->getValue());

      if ($minsize > -1 && $minsize > $size)
        $w = true;

      if ($maxsize > -1 && $maxsize < $size)
        $w = true;

      if ($w) {
        $id = $this->obj_array[0]->getId();
        $this->params['warning'][$id] = $this->params['error_class'];
        $this->params['warning_messages'][$id] = $this->getElement(5);
      }
    }
  }

  function getDescription()
  {
    return 'size_range -> Laenge der Eingabe muss mindestens und/oder maximal sein, beispiel: validate|size_range|label|[minsize]|[maxsize]|Fehlermeldung';
  }

  function getDefinitions()
  {
    return array(
      'type' => 'validate',
      'name' => 'size_range',
      'values' => array(
        array( 'type' => 'getName', 'label' => 'Name' ),
        array( 'type' => 'text', 'label' => 'Minimale Anzahl der Zeichen (opt)'),
        array( 'type' => 'text', 'label' => 'Maximale Anzahl der Zeichen (opt)'),
        array( 'type' => 'text', 'label' => 'Fehlermeldung'),
      ),
      'description' => 'Hiermit wird ein Name überprüft ob er eine bestimmte Anzahl von Zeichen hat',
    );

  }


}
