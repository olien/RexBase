Slice Status Addon (aka Slice On/Off) für REDAXO 4
===================================================

Dieses REDAXO-Addon fügt einen On/Offline-Schalter für Artikelblöcke (Slices) hinzu. 
Es ist der Nachfolger des slice_onoff Addons und wurde von Grund auf neu geschrieben.

Features
--------

* Fügt einen Button zum on/offline schalten von Blöcken (Slices) hinzu.
* Offline Blöcke werden im Backend mit anderer Farbe und geringerer Opacity dargestellt
* Aussehen kann komplett über CSS geändert werden
* AJAX Modus ein/ausschaltbar
* Komplett neuer und vereinfachter Code

API
---

```php
// ausgabe des status von slice mit id = 11
// liefert 0 wenn offline, 1 wenn online
echo rex_slice_status::getSliceStatus(11);

// setzen des status von slice mit id = 11 auf offline
rex_slice_status::setSliceStatus(11, 0);

// gibt ein array zurück mit allen slices und deren aktueller status
$sliceStatus = rex_slice_status::fetchSliceStatus();
```

Wechsel von slice_onoff auf slice_status
----------------------------------------

1. `slice_status` installieren. Es werden automatisch die Daten von `slice_onoff` importiert.
2. `slice_onoff` deinstallieren/löschen.
3. Cache löschen.

Will man keinen Import so deinstalliert man `slice_onoff` zuvor.

Hinweise
--------

* Getestet mit REDAXO 4.5, 4.4, 4.3
* Addon-Ordner lautet: `slice_status`
* Farbe/Opacity der Offline-Slices änderbar in `/files/addons/slice_status/slice_status.css`
* Nicht-Admins benötigen dieses Benutzerrecht um die Slices on/off schalten zu können: publicSlice[]
* Backend Page lässt sich in der config.inc.php abschalten. Dazu Zeile 4 auskommentieren
* Alte Version wurde als Branch archiviert

Changelog
---------

siehe [CHANGELOG.md](CHANGELOG.md)

Lizenz
------

siehe [LICENSE.md](LICENSE.md)

Credits
-------

* Dank gilt dem ursprünglichen Autor von slice_onoff für die Idee und die Vorlage
* Icons von hier entnommen: <a href="http://www.famfamfam.com/lab/icons/silk/">FamFamFam Silk Icons</a>
