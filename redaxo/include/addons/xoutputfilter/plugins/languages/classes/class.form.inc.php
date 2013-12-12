<?php

/**
 * languages Plugin f�r XOutputFilter
 *
 * @author andreaseberhard[at]gmail[dot]com Andreas Eberhard
 * @author <a href="http://www.redaxo.de">www.redaxo.de</a>
 *
 * @package redaxo4
 * @version svn:$Id$
 */

/*abstract*/ class rex_form_xo_languages extends rex_form
{

  /*protected*/ function validate()
  {
    global $I18N;
    $msg = '';

    $el =& $this->getElement($this->fieldset, 'marker');
    if (trim($el->getValue()) == '') {
      $msg .= $I18N->msg('xoutputfilter_lang_nomarker')."<br />";
    }

    $el =& $this->getElement($this->fieldset, 'html');
    if (trim($el->getValue()) == '') {
      $msg .= $I18N->msg('xoutputfilter_lang_nohtml')."<br />";
    }

    if ($msg<>'')
      return $msg;
    else
      return true;
  }

} // End class rex_form_xo_languages
