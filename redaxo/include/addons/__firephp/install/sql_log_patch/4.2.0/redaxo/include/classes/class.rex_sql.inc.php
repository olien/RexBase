<?php
/**
* FirePHP Addon
*
* FirePHP Lib Copyright (c) 2006-2010, Christoph Dorn, http://firephp.org
* FirePHP Lib v 0.3.1 & 0.3.2rc1
*
* @author <a href="http://rexdev.de">rexdev.de</a>
*
* @package redaxo 4.3.x/4.4.x/4.5.x
* @version 1.1.0
*/

/**
 * FirePHP Addon - SQL QUERIES LOG PATCH
 * extended version of:
 * @see http://rexdev.de/dev_log/redaxo-zahl-der-sql-queries-mitloggen.html
 */

/**
 * Klasse zur Verbindung und Interatkion mit der Datenbank
 * @version svn:$Id$
 */

class rex_sql
{
  var $values; // Werte von setValue
  var $fieldnames; // Spalten im ResultSet

  var $table; // Tabelle setzen
  var $wherevar; // WHERE Bediengung
  var $query; // letzter Query String
  var $counter; // ResultSet Cursor
  var $rows; // anzahl der treffer
  var $result; // ResultSet
  var $last_insert_id; // zuletzt angelegte auto_increment nummer
  var $debugsql; // debug schalter
  var $identifier; // Datenbankverbindung
  var $DBID; // ID der Verbindung

  var $error; // Fehlertext
  var $errno; // Fehlernummer

  static $count = 0; /* log patch */
  static $err_count = 0; /* log patch */
  static $log = array(); /* log patch */
  static $writes    = 0; /* log patch */

  /*private*/ function rex_sql($DBID = 1)
  {
    global $REX;

    $this->debugsql = false;
    $this->selectDB($DBID);

    if($REX['MYSQL_VERSION'] == '')
    {
      // ggf. Strict Mode abschalten
      $this->setQuery('SET SQL_MODE=""');

      // MySQL Version bestimmen
      $res = $this->getArray('SELECT VERSION() as VERSION');
      if(preg_match('/([0-9]+\.([0-9\.])+)/', $res[0]['VERSION'], $matches))
      {
        $REX['MYSQL_VERSION'] = $matches[1];
      }
      else
      {
        exit('Could not identifiy MySQL Version!');
      }

      // connection auf UTF8 trimmen
      if (rex_lang_is_utf8())
      {
        // $this->setQuery('SET NAMES utf8');
      }
    }

    $this->flush();
  }

  /**
   * Stellt die Verbindung zur Datenbank her
   */
  function selectDB($DBID)
  {
    global $REX;

    $this->DBID = $DBID;

    if($REX['DB'][$DBID]['PERSISTENT'])
      $this->identifier = @mysql_pconnect($REX['DB'][$DBID]['HOST'], $REX['DB'][$DBID]['LOGIN'], $REX['DB'][$DBID]['PSW']);
    else
      $this->identifier = @mysql_connect($REX['DB'][$DBID]['HOST'], $REX['DB'][$DBID]['LOGIN'], $REX['DB'][$DBID]['PSW']);

    if (!@mysql_select_db($REX['DB'][$DBID]['NAME'], $this->identifier))
    {
      echo "<font style='color:red; font-family:verdana,arial; font-size:11px;'>Class SQL 1.1 | Database down. | Please contact <a href=mailto:" . $REX['ERROR_EMAIL'] . ">" . $REX['ERROR_EMAIL'] . "</a>\n | Thank you!\n</font>";
      exit;
    }
  }

  /**
   * Gibt die DatenbankId der Abfrage (SQL) zur?ck,
   * oder false wenn die Abfrage keine DBID enth?lt
   *
   * @param $query Abfrage
   */
  function getQueryDBID($qry = null)
  {
    if(!$qry)
    {
      if(isset($this)) // Nur bei angelegtem Object
        $qry = $this->query;
      else
        return null;
    }

    $qry = trim($qry);

    if(preg_match('/\(DB([1-9]){1}\)/i', $qry, $matches))
      return $matches[1];

    return false;
  }

  /**
   * Entfernt die DBID aus einer Abfrage (SQL) und gibt die DBID zur?ck falls
   * vorhanden, sonst false
   *
   * @param $query Abfrage
   */
  function stripQueryDBID(&$qry)
  {
    $qry = trim($qry);

    if(($qryDBID = rex_sql::getQueryDBID($qry)) !== false)
      $qry = substr($qry, 6);

    return $qryDBID;
  }

  /**
   * Gibt den Typ der Abfrage (SQL) zur?ck,
   * oder false wenn die Abfrage keinen Typ enth?lt
   *
   * M?gliche Typen:
   * - SELECT
   * - SHOW
   * - UPDATE
   * - INSERT
   * - DELETE
   * - REPLACE
   *
   * @param $query Abfrage
   */
  function getQueryType($qry = null)
  {
    if(!$qry)
    {
      if(isset($this)) // Nur bei angelegtem Object
        $qry = $this->query;
      else
        return null;
    }

    $qry = trim($qry);
    // DBID aus dem Query herausschneiden, falls vorhanden
    rex_sql::stripQueryDBID($qry);

    if(preg_match('/^(SELECT|SHOW|UPDATE|INSERT|DELETE|REPLACE)/i', $qry, $matches))
      return strtoupper($matches[1]);

    return false;
  }

  /**
   * Setzt eine Abfrage (SQL) ab, wechselt die DBID falls vorhanden
   *
   * @param $query Abfrage
   * @return boolean True wenn die Abfrage erfolgreich war (keine DB-Errors
   * auftreten), sonst false
   */
  function setDBQuery($qry)
  {
    if(($qryDBID = rex_sql::stripQueryDBID($qry)) !== false)
      $this->selectDB($qryDBID);

    return $this->setQuery($qry);
  }

  /**
   * Setzt eine Abfrage (SQL) ab
   *
   * @param $query Abfrage
   * @return boolean True wenn die Abfrage erfolgreich war (keine DB-Errors
   * auftreten), sonst false
   */
  function setQuery($qry)
  {
    // Alle Werte zur?cksetzen
    $this->flush();
    self::$count++; /* log patch */

    $qry = trim($qry);

    self::$log[self::$count]['query'] = $qry; /* log patch */

    $this->query = $qry;
    $this->result = @ mysql_query($qry, $this->identifier);

    if ($this->result)
    {
      if (($qryType = $this->getQueryType()) !== false)
      {
        switch ($qryType)
        {
          case 'SELECT' :
          case 'SHOW' :
          {
            $this->rows = mysql_num_rows($this->result);
            break;
          }
          case 'REPLACE' :
          case 'DELETE' :
          case 'UPDATE' :
          {
            self::$writes++; /* log patch */
            $this->rows = mysql_affected_rows($this->identifier);
            break;
          }
          case 'INSERT' :
          {
            self::$writes++; /* log patch */
            $this->rows = mysql_affected_rows($this->identifier);
            $this->last_insert_id = mysql_insert_id($this->identifier);
            break;
          }
        }
      }

    self::$log[self::$count]['rows'] = $this->rows; /* log patch */
    self::$log[self::$count]['backtrace'] = debug_backtrace(); /* log patch */

    }
    else
    {
      $this->error = mysql_error($this->identifier);

      self::$log[self::$count]['error'] = $this->error; /* log patch */
      self::$log[self::$count]['backtrace'] = debug_backtrace(); /* log patch */
      self::$err_count++; /* log patch */

      $this->errno = mysql_errno($this->identifier);

      self::$log[self::$count]['errno'] = $this->errno; /* log patch */
    }

    if ($this->debugsql || $this->error != '')
    {
      $this->printError($qry);
    }

    return $this->getError() === '';
  }

  /**
   * Setzt den Tabellennamen
   *
   * @param $table Tabellenname
   */
  function setTable($table)
  {
    $this->table = $table;
  }

  /**
   * Setzt den Wert eine Spalte
   *
   * @param $feldname Spaltenname
   * @param $wert Wert
   */
  function setValue($feldname, $wert)
  {
    $this->values[$feldname] = $wert;
  }

  /**
   * Setzt ein Array von Werten zugleich
   *
   * @param $valueArray Ein Array von Werten
   * @param $wert Wert
   */
  function setValues($valueArray)
  {
    if(is_array($valueArray))
    {
      foreach($valueArray as $name => $value)
      {
        $this->setValue($name, $value);
      }
      return true;
    }
    return false;
  }

  /**
   * Pr?ft den Wert einer Spalte der aktuellen Zeile ob ein Wert enthalten ist
   * @param $feld Spaltenname des zu pr?fenden Feldes
   * @param $prop Wert, der enthalten sein soll
   */
  function isValueOf($feld, $prop)
  {
    if ($prop == "")
    {
      return TRUE;
    }
    else
    {
      return strpos($this->getValue($feld), $prop) !== false;
    }
  }

  /**
   * Setzt die WHERE Bedienung der Abfrage
   */
  function setWhere($where)
  {
    $this->wherevar = "WHERE $where";
  }

  /**
   * Gibt den Wert einer Spalte im ResultSet zur?ck
   * @param $value Name der Spalte
   * @param [$row] Zeile aus dem ResultSet
   */
  function getValue($feldname, $row = null)
  {
  	if(isset($this->values[$feldname]))
  		return $this->values[$feldname];

    $_row = $this->counter;
    if (is_int($row))
    {
      $_row = $row;
    }

    $res = mysql_result($this->result, $_row, $feldname);
    if($res === false)
    {
      $sendWarnings = (error_reporting() & E_WARNING) == E_WARNING;

      if($sendWarnings && function_exists('debug_backtrace'))
      {
        $trace = debug_backtrace();
        $loc = $trace[0];
        echo '<b>Warning</b>:  mysql_result('. $feldname .'): Initial error found in file <b>'. $loc['file'] .'</b> on line <b>'. $loc['line'] .'</b><br />';
      }
    }
    return $res;
  }

  /**
   * Pr?ft, ob eine Spalte im Resultset vorhanden ist
   * @param $value Name der Spalte
   */
  function hasValue($feldname)
  {
    return in_array($feldname, $this->getFieldnames());
  }

  /**
   * Pr?ft, ob das Feld mit dem Namen $feldname Null ist.
   *
   * Falls das Feld nicht vorhanden ist,
   * wird Null zur?ckgegeben, sonst True/False
   */
  function isNull($feldname)
  {
    if($this->hasValue($feldname))
      return $this->getValue($feldname) === null;

    return null;
  }

  /**
   * Gibt die Anzahl der Zeilen zur?ck
   */
  function getRows()
  {
    return $this->rows;
  }

  /**
   * Gibt die Zeilennummer zur?ck, auf der sich gerade der
   * interne Z?hler befindet
   */
  function getCounter()
  {
    return $this->counter;
  }

  /**
   * Gibt die Anzahl der Felder/Spalten zur?ck
   */
  function getFields()
  {
    return mysql_num_fields($this->result);
  }

  /**
   * Baut den SET bestandteil mit der
   * verf?gbaren values zusammen und gibt diesen zur?ck
   *
   * @see setValue
   */
  function buildSetQuery()
  {
    $qry = '';
    if (is_array($this->values))
    {
      foreach ($this->values as $fld_name => $value)
      {
        if ($qry != '')
        {
          $qry .= ',';
        }

        // Bei <tabelle>.<feld> Notation '.' ersetzen, da sonst `<tabelle>.<feld>` entsteht
        if(strpos($fld_name, '.') !== false)
          $fld_name = str_replace('.', '`.`', $fld_name);

        if($value === null)
          $qry .= '`' . $fld_name . '`= NULL';
        else
          $qry .= '`' . $fld_name . '`=\'' . $value .'\'';

// Da Werte via POST/GET schon mit magic_quotes escaped werden,
// brauchen wir hier nicht mehr escapen
//        $qry .= '`' . $fld_name . '`=' . $this->escape($value);
      }
    }

    return $qry;
  }

  /**
   * Setzt eine Select-Anweisung auf die angegebene Tabelle
   * mit den WHERE Parametern ab
   *
   * @see #setTable()
   * @see #setWhere()
   */
  function select($fields)
  {
    return $this->setQuery('SELECT '. $fields .' FROM `' . $this->table . '` '. $this->wherevar);
  }

  /**
   * Setzt eine Update-Anweisung auf die angegebene Tabelle
   * mit den angegebenen Werten und WHERE Parametern ab
   *
   * @see #setTable()
   * @see #setValue()
   * @see #setWhere()
   */
  function update($successMessage = null)
  {
    return $this->statusQuery('UPDATE `' . $this->table . '` SET ' . $this->buildSetQuery() .' '. $this->wherevar, $successMessage);
  }

  /**
   * Setzt eine Insert-Anweisung auf die angegebene Tabelle
   * mit den angegebenen Werten ab
   *
   * @see #setTable()
   * @see #setValue()
   */
  function insert($successMessage = null)
  {
    return $this->statusQuery('INSERT INTO `' . $this->table . '` SET ' . $this->buildSetQuery(), $successMessage);
  }

  /**
   * Setzt eine Replace-Anweisung auf die angegebene Tabelle
   * mit den angegebenen Werten ab
   *
   * @see #setTable()
   * @see #setValue()
   * @see #setWhere()
   */
  function replace($successMessage = null)
  {
    return $this->statusQuery('REPLACE INTO `' . $this->table . '` SET ' . $this->buildSetQuery() .' '. $this->wherevar, $successMessage);
  }

  /**
   * Setzt eine Delete-Anweisung auf die angegebene Tabelle
   * mit den angegebenen WHERE Parametern ab
   *
   * @see #setTable()
   * @see #setWhere()
   */
  function delete($successMessage = null)
  {
    return $this->statusQuery('DELETE FROM `' . $this->table . '` ' . $this->wherevar, $successMessage);
  }

  /**
   * Setzt den Query $query ab.
   *
   * Wenn die Variable $successMessage gef?llt ist, dann wird diese bei
   * erfolgreichem absetzen von $query zur?ckgegeben, sonst die MySQL
   * Fehlermeldung
   *
   * Wenn die Variable $successMessage nicht gef?llt ist, verh?lt sich diese
   * Methode genauso wie setQuery()
   *
   * Beispiel:
   *
   * <code>
   * $sql = new rex_sql();
   * $message = $sql->statusQuery(
   *    'INSERT  INTO abc SET a="ab"',
   *    'Datensatz  erfolgreich eingef?gt');
   * </code>
   *
   *  anstatt von
   *
   * <code>
   * $sql = new rex_sql();
   * if($sql->setQuery('INSERT INTO abc SET a="ab"'))
   *   $message  = 'Datensatz erfolgreich eingef?gt');
   * else
   *   $message  = $sql- >getError();
   * </code>
   */
  function statusQuery($query, $successMessage = null)
  {
    $res = $this->setQuery($query);
    if($successMessage)
    {
      if($res)
        return $successMessage;
      else
        return $this->getError();
    }
    return $res;
  }

  /**
   * Stellt alle Werte auf den Ursprungszustand zur?ck
   */
  function flush()
  {
    $this->flushValues();
    $this->fieldnames = array ();

    $this->table = '';
    $this->wherevar = '';
    $this->query = '';
    $this->counter = 0;
    $this->rows = 0;
    $this->result = '';
    $this->last_insert_id = '';
    $this->error = '';
    $this->errno = '';
  }

  /**
   * Stellt alle Values, die mit setValue() gesetzt wurden, zur?ck
   *
   * @see #setValue(), #getValue()
   */
  function flushValues()
  {
    $this->values = array ();
  }


  /**
   * Setzt den Cursor des Resultsets auf die n?chst niedrigere Stelle
   */
  function previous()
  {
    $this->counter--;
  }

  /**
   * Setzt den Cursor des Resultsets auf die n?chst h?here Stelle
   */
  function next()
  {
    $this->counter++;
  }

  /*
   * Pr?ft ob das Resultset weitere Datens?tze enth?lt
   */
  function hasNext()
  {
    return $this->counter != $this->rows;
  }

  /**
   * Setzt den Cursor des Resultsets zur?ck zum Anfang
   */
  function reset()
  {
    $this->counter = 0;
  }

  /**
   * Setzt den Cursor des Resultsets aufs Ende
   */
  function last()
  {
    $this->counter = ($this->rows - 1);
  }

  /**
   * Gibt die letzte InsertId zur?ck
   */
  function getLastId()
  {
    return $this->last_insert_id;
  }

  /**
   * L?dt das komplette Resultset in ein Array und gibt dieses zur?ck und
   * wechselt die DBID falls vorhanden
   *
   * @access public
   * @param string $sql Abfrage
   * @param string $fetch_type Default: MYSQL_ASSOC; weitere: MYSQL_NUM, MYSQL_BOTH
   * @return array
   */
  function getDBArray($sql = '', $fetch_type = MYSQL_ASSOC)
  {
    return $this->_getArray($sql, $fetch_type, 'DBQuery');
  }

  /**
   * L?dt das komplette Resultset in ein Array und gibt dieses zur?ck
   *
   * @access public
   * @param string $sql Abfrage
   * @param string $fetch_type Default: MYSQL_ASSOC; weitere: MYSQL_NUM, MYSQL_BOTH
   * @return array
   */
  function getArray($sql = '', $fetch_type = MYSQL_ASSOC)
  {
    return $this->_getArray($sql, $fetch_type);
  }

  /**
   * Hilfsfunktion
   *
   * @access private
   * @see getArray()
   * @see getDBArray()
   * @param string $sql Abfrage
   * @param string $fetch_type Default: MYSQL_ASSOC, MYSQL_NUM, MYSQL_BOTH
   * @param string $qryType void oder DBQuery
   * @return array
   */
  function _getArray($sql, $fetch_type, $qryType = 'default')
  {
    if ($sql != '')
    {
      switch($qryType)
      {
        case 'DBQuery': $this->setDBQuery($sql); break;
        default       : $this->setQuery($sql);
      }
    }


    $data = array();

    while ($row = @ mysql_fetch_array($this->result, $fetch_type))
    {
      $data[] = $row;
    }

    return $data;
  }

  /**
   * Gibt die zuletzt aufgetretene Fehlernummer zur?ck
   */
  function getErrno()
  {
    return $this->errno;
  }

  /**
   * Gibt den zuletzt aufgetretene Fehlernummer zur?ck
   */
  function getError()
  {
    return $this->error;
  }

  /**
   * Pr?ft, ob ein Fehler aufgetreten ist
   */
  function hasError()
  {
    return $this->error != '';
  }

  /**
   * Gibt die letzte Fehlermeldung aus
   */
  function printError($query)
  {
    if ($this->debugsql == true)
    {
      echo '<hr />' . "\n";
      echo 'Query: ' . nl2br(htmlspecialchars($query)) . "<br />\n";

      if (strlen($this->getRows()) > 0)
      {
        echo 'Affected Rows: ' . $this->getRows() . "<br />\n";
      }
      if (strlen($this->getError()) > 0)
      {
        echo 'Error Message: ' . htmlspecialchars($this->getError()) . "<br />\n";
        echo 'Error Code: ' . $this->getErrno() . "<br />\n";
      }
    }
  }

  /**
   * Setzt eine Spalte auf den n?chst m?glich auto_increment Wert
   * @param $field Name der Spalte
   */
  function setNewId($field)
  {
    // setNewId muss neues sql Objekt verwenden, da sonst bestehende informationen im Objekt ?berschrieben werden
    $sql = new rex_sql();
    if($sql->setQuery('SELECT `' . $field . '` FROM `' . $this->table . '` ORDER BY `' . $field . '` DESC LIMIT 1'))
    {
      if ($sql->getRows() == 0)
        $id = 0;
      else
        $id = $sql->getValue($field);

      $id++;
      $this->setValue($field, $id);

      return $id;
    }

    return false;
  }

  /**
   * Gibt die Spaltennamen des ResultSets zur?ck
   */
  function getFieldnames()
  {
    if(empty($this->fieldnames))
    {
      for ($i = 0; $i < $this->getFields(); $i++)
      {
        $this->fieldnames[] = mysql_field_name($this->result, $i);
      }
    }
    return $this->fieldnames;
  }

  /**
   * Escaped den ?bergeben Wert f?r den DB Query
   *
   * @param $value den zu escapenden Wert
   * @param [$delimiter] Delimiter der verwendet wird, wenn es sich bei $value
   * um einen String handelt
   */
  function escape($value, $delimiter = '')
  {
    // Quote if not a number or a numeric string
    if (!is_numeric($value))
    {
      $value = $delimiter . mysql_real_escape_string($value, $this->identifier) . $delimiter;
    }
    return $value;
  }

  function showTables($DBID=1)
  {
    global $REX;

    $sql = new rex_sql($DBID);
    $sql->setQuery('SHOW TABLES');

    $tables = array();
    for($i = 0; $i < $sql->getRows(); $i++)
    {
      $tables[] = $sql->getValue('Tables_in_'.$REX['DB'][$DBID]['NAME']);
      $sql->next();
    }

    return $tables;
  }

  function showColumns($table, $DBID=1)
  {
    $sql = new rex_sql($DBID);
    $sql->setQuery('SHOW COLUMNS FROM '.$table);

    $columns = array();
    for($i = 0; $i < $sql->getRows(); $i++)
    {
      $columns [] = array(
        'name' => $sql->getValue('Field'),
        'type' => $sql->getValue('Type'),
        'null' => $sql->getValue('Null'),
        'key' => $sql->getValue('Key'),
        'default' => $sql->getValue('Default'),
        'extra' => $sql->getValue('Extra')
      );
      $sql->next();
    }

    return $columns;
  }

  /**
   * Gibt die Serverversion zur?ck
   */
  function getServerVersion()
  {
    global $REX;
    return $REX['MYSQL_VERSION'];
  }

  /**
   * Gibt ein SQL Singelton Objekt zur?ck
   */
  function getInstance($DBID=1, $createInstance = true)
  {
    static $instance = null;

    if ($instance)
      $instance->flush();
    else if($createInstance)
      $instance = new rex_sql($DBID);

    return $instance;
  }

  /**
   * Gibt den Speicher wieder frei
   */
  function freeResult()
  {
    if(is_resource($this->result))
      mysql_free_result($this->result);
  }

  /**
   * Prueft die uebergebenen Zugangsdaten auf gueltigkeit und legt ggf. die
   * Datenbank an
   */
  function checkDbConnection($host, $login, $pw, $dbname, $createDb = false)
  {
    global $I18N;

    $err_msg = true;
    $link = @ mysql_connect($host, $login, $pw);
    if (!$link)
    {
      $err_msg = $I18N->msg('setup_021');
    }
    elseif (!@ mysql_select_db($dbname, $link))
    {
      if($createDb)
      {
        mysql_query('CREATE DATABASE `'. $dbname .'`', $link);
        if(mysql_error($link) != '')
        {
          $err_msg = $I18N->msg('setup_022');
        }
      }
      else
      {
        $err_msg = $I18N->msg('setup_022');
      }
    }

    if($link)
    {
      mysql_close($link);
    }
    return $err_msg;
  }

  /**
   * Schlie?t die Verbindung zum DB Server
   */
  function disconnect($DBID=1)
  {
    global $REX;

    // Alle Connections schlie?en
    if($DBID === null)
    {
      foreach($REX['DB'] as $DBID => $DBSettings)
        rex_sql::disconnect($DBID);

      return;
    }

    if(!$REX['DB'][$DBID]['PERSISTENT'])
    {
      $db = rex_sql::getInstance($DBID, false);

      if(rex_sql::isValid($db) && is_resource($db->identifier))
        mysql_close($db->identifier);
    }
  }

  function addGlobalUpdateFields($user = null)
  {
    global $REX;

    if(!$user) $user = $REX['USER']->getValue('login');

    $this->setValue('updatedate', time());
    $this->setValue('updateuser', $user);
  }

  function addGlobalCreateFields($user = null)
  {
    global $REX;

    if(!$user) $user = $REX['USER']->getValue('login');

    $this->setValue('createdate', time());
    $this->setValue('createuser', $user);
  }

  function isValid($object)
  {
    return is_object($object) && is_a($object, 'rex_sql');
  }
}
