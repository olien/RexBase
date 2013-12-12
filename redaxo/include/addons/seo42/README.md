SEO42 AddOn für REDAXO 4.5+
===========================

Ein intergalaktischer Fork des original RexSEO AddOns für REDAXO mit alternativer Benutzerführung und noch ein paar weiteren SEO-Goodies...

Features
--------

* Generierung von suchmaschinenfreundlichen URLs (Webserver benötigt Modul `mod_rewrite`)
* Automatische Umschreibung der Startseite der Website in `/` (für alle Sprachen möglich)
* Verschiedene URL-Endungen einstellbar (z.B. Endung `.html` oder `/`)
* Automatische Titel-Generierung. Mitgeliefertes Titel-Schema aus [Google-PDF](http://www.google.de/webmasters/docs/einfuehrung-in-suchmaschinenoptimierung.pdf) entnommen.
* Extra SEO-Page für jeden Artikel inkl. Titel-Vorschau und Zeichen/Wörter Zähler
* Extra URL-Page für jeden Artikel inkl 10 URL-Typen zur Manipulierung der generierten URL
* Automatische `sitemap.xml` und `robots.txt` Generierung
* Neue vereinfachte Setup-Routine, benötigt keine MetaInfos mehr
* Option um vollständige URLs inkl. Domainname wie bei WordPress zu erzeugen
* One Page Mode für Websites die nur über eine Seite verfügen (z.B. Parallax-Websites etc.)
* Suchmaschinenfreundliche Image Manager Urls durch Verwendung der verfügbaren PHP-Methoden
* SEO Tools inkl. Live PageRank Checker sowie Anzeige des Google Index der aktuellen Website
* Klasse `nav42` zum Bauen von Navigationen. Alle URL-Typen werden hierbei berücksichtigt.
* Einrichtung von 301 Weiterleitungen. Parameter in der alten URL sind ohne Probleme möglich.
* Spezielle sprachabhängige sowie sprachunabhängige Sonderzeichen-Umschreibungen einstellbar
* Pro Sprache kann Urlencode genutzt oder auch die URLs einer anderen Sprache übernommen werden
* Lang Slugs (de, en) können unabhängig von den REDAXO Sprachnamen gesetzt werden
* Automatische `rel="alternate"` Tags für mehrsprachige Websites
* Option um die Indizierung von Seiten durch Suchmaschinen zu verhindern
* Automatische sowie individuelle Canonical URLs
* Nicht-WWW zu WWW Umleitung (und umgekehrt). Lässt sich auch über das Setup aktivieren.
* Smart Redirects: Automatische Umleitungen für falsch eingegebene Urls z.B. von `/` nach `.html`
* Weitere Einstellungen (vorerst) in der `settings.advanced.inc.php` und `settings.lang.inc.php`
* Keine Abhängigkeiten zu weiteren Addons wie Textile oder XForm
* Kompatibel zum [Website Manager](https://github.com/RexDude/website_manager) sowie [Community](https://github.com/dergel/redaxo4_community) AddOn
* Enthält die Antwort auf die eine Frage ;)

Features der Klasse nav42
-------------------------

* Klasse `nav42` ist eine abgeleitete `rex_navigation` mit Zusatzfeatures
* Ausgabe der Navigation von einer Katagorie aus oder über Kategorie-Levels
* Es wird zuerst eine nackte UL-Liste ohne Klassen oder Ids ausgegeben
* Startartikel der Website (z.B. Home) kann ausgeblendet werden
* Einstellen der CSS-Klasse für selektierte Menüpunkte (z.B. `current`)
* Die erste UL kann eine Klasse und/oder ID zugewiesen bekommen (Suckerfish/Superfish)
* Angabe von MetaInfo Felder aus denen Klassen und IDs für die LI's herausgezogen werden
* Aufruf einer benutzerdef. PHP-Funktion möglich, die den Inhalt der LI's zurückgibt
* Unterstützung für alle URL-Typen von SEO42
* Ausgabe einer einfachen Sprachnavigation möglich
* Vollständige Codebeispiele in der Hilfe von SEO42

Ladezeit Ihrer Website verbessern
---------------------------------

Da eine schnelle Ladezeit der Website sich auch positiv auf das SEO auswirkt, wird empfohlen sowas wie das [HTML5 Boilerplate](http://html5boilerplate.com/) oder aber das [Resource Includer](https://github.com/RexDude/resource_includer) AddOn für REDAXO zu nutzen.

Verfügbare Plugins für SEO42
----------------------------

* [url_control](https://github.com/tbaddade/redaxo_plugin_url_control) - Plugin zur URL-Generierung für eigene AddOns

Update von SEO42 2.x auf SEO42 2.6 und höher
--------------------------------------------

* AddOn-Ordner der alten Version löschen (vorher sichern!).
* AddOn-Ordner der neuen Version einspielen.
* SEO42 über die AddOn-Verwaltung von REDAXO reinstallieren.
* AddOn-Einstellungen von Hand nachprüfen und ggf. korrigieren (`settings.lang.inc.php` hat ein neues Format!).
* Evtl. Klasse `rex_navigation42` in `nav42` umbenennen. 
* Ggf. Cache löschen.

Update von REXSEO42 1.1/1.2 auf SEO42 2.x
-----------------------------------------

* In der `settings.advanced.inc.php` von REXSEO42 die Option `drop_dbfields_on_uninstall` auf `false` setzen.
* REXSEO42 deinstallieren und AddOn-Ordner löschen.
* SEO42 installieren.
* In allen Templates den Klassennamen von `rexseo42` nach `seo42` umbenennen.
* AddOn-Einstellungen von Hand nachprüfen und ggf. korrigieren (ab 2.6.0 hat die `settings.lang.inc.php` ein neues Format!).
* Ggf. Cache löschen.

Alle URL-Typen aktivieren
-------------------------

* Einige Url-Typen greifen erst, wenn bei der Ausgabe der Navigation auf diese reagiert wird.
* Die Klasse `nav42` (ehemals `rex_navigation42`) unterstützt diese Typen bereits.
* Über die Option `all_url_types` können diese bei Bedarf aber auch deaktiviert werden.

Anpassungen für das Community Addon
-----------------------------------

Diese Anpassungen sind nur nötig, wenn man die `nav42` Klasse verwenden will:

* [Diese Zeile](https://github.com/dergel/redaxo4_community/blob/master/plugins/auth/config.inc.php#L19) auskommentieren
* Und [diese Zeile](https://github.com/RexDude/seo42/blob/master/classes/class.nav42.inc.php#L3) umschreiben in `class nav42 extends rex_com_navigation`

Entwicklung von Plugins für SEO42
---------------------------------

* SEO42 bindet automatisch seine installierten und aktvierten Plugins in das Addon-Menü ein.
* Es wird ausserdem automatisch die Sprachdatei des Plugins eingebunden. Im Plugin selbst muss man also nichts weiter tun.

Hinweise
--------

* Läuft nur mit REDAXO 4.5+
* AddOn-Ordner lautet: `seo42`
* AddOn wurde seit Version 2.0.0 von REXSEO42 in SEO42 umbenannt.
* Wenn der Webserver einen 500 Server Error meldet, die Zeile `Options -Indexes` in der `.htaccess` auskommentieren.
* Geändertes Verhalten für REDAXO Unterordner-Installationen. Bitte FAQ in der Hilfe des AddOns anschauen für weitere Infos.
* Der Fehlerartikel unter REDAXO > System sollte nicht gleich dem Startartikel der Website entsprechen. Es sollte aufjedenfall ein eigener Fehlerartikel angelegt werden.
* Implementiert man sein eigenes Titel-Schema, ist es vielleicht sinnvoll die Optionen `title_preview` und `no_prefix_checkbox` auf `false` zu setzen.
* `$REX["MOD_REWRITE"]` braucht nicht mehr auf `true` gesetzt werden (z.B. über die System-Page von REDAXO). Wenn SEO42 aktiv, wird es automatisch gesetzt.
* Eine hilfreiche Sprach-Sonderzeichen-Tabelle für die Ermittlung der Sonderzeichen-Umschreibungen für die `settings.lang.inc.php` findet man hier: <http://unicode.e-workers.de/>
* Vorläufige Sammlung der Lang-Presets hier: <https://github.com/RexDude/seo42/issues/61>
* Momentan muss man noch von Hand benötigte Einstellungen in den Dateien `settings.advanced.inc.php` und `settings.lang.inc.php` vornehmen. Danach sollte der Cache gelöscht werden. Ab Version 3.0.0 sollten diese Dateien dann der Vergangenheit angehören ;)
* Getestete Skins: `agk_skin` von REDAXO und `ppx_skin` von [polarpixel](https://github.com/polarpixel).

Links
-----

* BugTracker: <https://github.com/RexDude/seo42/issues>
* Klasse nav42: <https://github.com/RexDude/seo42/blob/master/classes/class.nav42.inc.php>


FAQ
---

siehe [FAQ.md](FAQ.md)

Changelog
---------

siehe [CHANGELOG.md](CHANGELOG.md)

Lizenz
------

siehe [LICENSE.md](LICENSE.md)

Credits
-------

* [GN2](https://github.com/gn2netwerk) und [jdlx](https://github.com/jdlx) für das original RexSEO AddOn
* [Markus Staab](https://github.com/staabm) für das zugrundeliegende url_rewrite AddOn
* [Jan Kristinus](http://github.com/dergel) für REDAXO und den neuen EP in REDAXO 4.5
* [Gregor Harlan](https://github.com/gharlan) und [Thomas Blum](https://github.com/tbaddade) für Hilfe, Code und Bugmeldungen :)
* [Peter Bickel](https://github.com/polarpixel) für generelle Unterstützung und die Hilfe bei der englischen Übersetzung
* Danke ausserdem an alle die sich mit Ideen, Tests und Bugmeldungen eingebracht haben :)
* Google PageRank Checker Class by David Walsh and Jamie Scott
* PHP Markdown Lib by Michel Fortin
* [QTip2](http://qtip2.com/) by Craig Thompson
* Hitchhiker's Guide to the Galaxy Icons by [Iconshock](http://www.iconarchive.com/artist/iconshock.html)
* Status Icons from [FamFamFam Silk Icons](http://www.famfamfam.com/lab/icons/silk/) and [Oxygen Icons](http://www.oxygen-icons.org/)
* Macht’s gut und danke für den Fisch ;)

