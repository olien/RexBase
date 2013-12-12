Slice Status - Changelog
========================

### Version 2.0.0 - 24. April 2013

* CSS und JS Dateien werden nur noch in der Content-Ansicht inkludiert
* Neue Backend Page mit Einstellungsmöglichkeiten
* DB Feld Auto Fixer: erkennt wenn nach einem DB Import die DB Felder fehlen und stellt sie wieder her
* Neues Benutzerrecht publishSlice[] für Nicht-Admins hinzugefügt
* AddOn reagiert nun auch auf das Benutzerrecht editContentOnly[]
* Settings aus config.inc.php nach setting.inc.php verlagert
* Lizenz-Datei hinzugefügt

### Version 1.2.5 - 17. Feburar 2013

* $REX['MEDIA_ADDON_DIR'] wird genutzt wenn vorhanden
* Changelog hinzugefügt
* Dateirechte korrekt eingestellt
* PHP-Funktion `updateSliceStatusInDB` in `updateSliceStatus` geändert
* Fixed #8: Slice Status gibt nun Fehlermeldung aus wenn `status` DB-Feld nicht gefunden
* Fixed #7: Ajax Mode läuft jetzt auch wenn REDAXO in einem Unterordner installiert wurde
* Funktionen in statische Klasse `rex_slice_status` verschoben

### Version 1.2.2 - 20. Dezember 2012

* Fixed #5: Cache löschen bei install/uninstall, Kompatibilität zu REDAXO 4.3.3 hergestellt

### Version 1.2.1 - 27. November 2012

* Slice Status AddOn übernimmt nun bei Installation Daten des alte slice_onoff AddOns wenn vorhanden
* DB Zugriffe auf ein minimum reduziert

### Version 1.2.0 - 25. November 2012

* AJAX Modus hinzugefügt (ausschaltbar über config.inc.php)
* Offline Slices lassen sich komplett über CSS stylen
* Code Optimierungen vorgenommen
* Fixed #3: TypeError: $ is not a function
* Icons als Sprite um Nachladen des Hover-Icons zu verhindern

### Version 1.0.5 - 08. November 2012

* Fixed #1: Kompatibilität mit REDAXO 4.4.1

### Version 1.0.0

Erstes Release mit folgenden Änderungen/Features gegenüber dem alten slice_onoff:

* Offline Blöcke werden im Backend mit anderer Farbe und geringerer Opacity dargestellt
* Läuft auch ohne AJAX Modus
* Kompletter Code Rewrite
* Radiobuttons innerhalb der Blöcke (rechts unten) entfernt
* On/Off Button hinter die Move Up/Down Buttons gesetzt
* Es wird nur noch eine If-Abfrage pro Offline-Slice generiert


