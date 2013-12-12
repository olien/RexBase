SEO42 - Changelog
=================

**Updatehinweis:** Von SEO42 2.x auf SEO42 2.6 und höher bitte [Updateanleitung](https://github.com/RexDude/seo42#update-von-seo42-2x-auf-seo42-26-und-h%C3%B6her) beachten!

### Version 2.9.0 DEV

* Fixed #86: Bei RewriteMode = Urlencode und Standalone-Benutzung von `rexseo_parse_article_name` werden mehrfach vorkommende `-` auf eins reduziert.

### Version 2.8.2 - 02. Dezember 2013

* Neu: `auto_redirects` Option um alte REDAXO Urls automatisch weiterzuleiten auf die Neuen. 0 = aus, 1 = Schema `index.php?article_id=1` weiterleiten, 2 = Schema `1-0-ArticleName.html` weiterleiten
* Entfernt: `allow_article_id` Option (ist nun `auto_redirects` = 1)

### Version 2.8.1 - 23. November 2013

* Fixed #85: `REXSEO_SITEMAP_ARRAY_CREATED` jetzt wieder ohne vorangestellte ServerUrl
* Fixed #84: Bessere Datumsausgabe für die `sitemap.xml`
* Verbessert: OnePageMode auf Mehrsprachigkeit hin optimiert (betrifft `Sitemap.xml` und Anzeigen-Link für Artikel)
* Verbessert: `seo42::getLangNavigation()` selektiert jetzt auch die aktuelle Sprache. Parameter `$currentClass` hinzugefügt.
* Verbessert: Beim Hinzufügen einer neuen Sprache werden die ganzen SEO Daten (Titel, Beschreibung etc.) nicht mehr übernommen von der Hauptsprache

### Version 2.8.0 - 13. November 2013

* Neu: Option `smart_redirects` hinzugefügt (per Defaulteinstellungen zuerst mal deaktiviert): Leitet den Benutzer der in der Adressleiste Urls von Hand verkürzt auf die korrekte Url um, wenn vorhanden (z.B. `domain.de/foo/bar/` > `domain.de/foo/bar.html`).
* Neu: Recht `seo42[redirects_only]` hinzugefügt um für normale Benutzer nur die Redirects Seite anzuzeigen. Muss mit Recht `seo42[]` gesetzt werden.
* Neu: Sortiermöglichkeiten für die Redirects Liste hinzugefügt
* Neu: Hinweis in der Readme.md und in der FAQ auf [HTML5 Boilerplate](http://html5boilerplate.com/) bzw. [Resource Includer](https://github.com/RexDude/resource_includer) als SEO-Maßnahme zur Verbesserung der Ladezeit der Website.
* Verbessert: Hinweis Icons jetzt inkl. Tooltips :)
* Verbessert: `.htaccess` Anweisung für die Deaktivierung der Verzeichnisauflistung optimiert. Leider gibt es trotzdem Server die einen 500 Server Error raushauen :(
* Verbessert: Neben der automatischen Einbindung der aktivierten Plugins für SEO42 werden nun auch die Language-Files der Plugins automatisch mit eingebunden
* Entfernt: Einstellungen `levenshtein` und `rewrite_params` komplett entfernt

### Version 2.7.0 - 02. November 2013

* Fixed: Redirects gingen manchmal auf die nicht-WWW Adresse, dadurch entstanden dann 2 Redirects
* Neu: Marvin, der Roboter ist nun in der Hilfe zu finden und gibt dort wertvolle Lebensweisheiten von sich ;)
* Neu: Es wird nun ein 404 Header gesendet wenn Fehlerartikel = Startartikel der Website ist. Standardverhalten von REDAXO ist hier nämlich sonst kein 404 zu senden was schlecht fürs SEO ist.
* Neu: FAQ-Eintrag: `Meine CSS Dateien werden nicht geladen?!`
* Verbessert: Im Setup Schritt 1 wird der Hinweis auf die `settings.lang.inc.php` gegeben, wo man aktuell noch die Spracheinstellungen vornehmen muss. Zusätzlich wird ein Status-Icon angezeigt falls die Anzahl der Sprachen mit den REDAXO Sprachen nicht übereinstimmt.
* Verbessert: Optionale Parameter `ulId`, `showLiClasses` und `hideLiIfOfflineArticle` für `seo42::getLangNavigation()` hinzugefügt
* Verbessert: Nach Wechsel von REDAXO Unterordner-Installation auf normale Installation wird im Setup die RewriteBase Checkbox weiterhin angezeigt bis `.htaccess` Datei neu kopiert
* Verbessert: Debug-Output inkl. REDAXO, SEO42 und PHP Versionen-Anzeige, `seo42::hasTemplateBaseTag()` hinzugefügt

### Version 2.6.1 - 31. Oktober 2013

* Fixed #65: WWW-Weiterleitung gilt nur noch für das Frontend. Dadurch wird bei manchen ein versehentliches Aussperren aus dem Backend vermieden.
* Fixed #64: Bei einem LangCode `en-US` lautet der LangSlug jetzt korrekt `en`. Methode `seo42::getLangSlug()` hinzugefügt.
* Neu: `seo42::getLangNavigation()` zur Ausgabe von einfachen Sprachnavigationen hinzugefügt
* Neu: Jeder Redirect kann nun direkt getestet werden über den entsprechenden Link
* Verbessert: Community Addon Unterstützung. `Sitemap.xml` reagiert nun auf gesperrte Artikel des Community Addons.

### Version 2.6.0 - 24. Oktober 2013

* Fixed: Bug bei Option `full_urls` in Kombination mit externen URLs 
* Fixed: Redirects funktionieren jetzt auch mit REDAXO Unterordner Installationen
* Fixed: bei `allow_articleid` = 0 und einer `article_id` URL wird jetzt ein 404 Fehler ausgegeben anstelle eines Redirects auf die Startseite
* Fixed: Autom. Canonical URL und Rel Alternate Tags werden bei einem 404 Fehler nicht mehr gesetzt
* Fixed: Website Manager Umschalter funktionierte nicht mehr, wenn man auf der Redirects Page war
* Fixed: Wenn `URL der Website` = `www.redaxo.org` wurde ein Unterordner entdeckt
* Teilweise Übersetzung in englisch (SEO- und URL-Page).
* Klasse `rex_navigation42` in `nav42` umbenannt. 
* Weitere Optionen für `nav42` hinzugefügt: `currentClass`, `firstUlId`, `firstUlClass`, `liIdFromMetaField`, `liClassFromMetaField`, `linkFromUserFunc`. Codebeispiele ergänzt und angepasst.
* Das Redirects Plugin wurde direkt in SEO42 integriert
* 3 RewriteModes für Sprachen hinzugefügt: `SEO42_REWRITEMODE_SPECIAL_CHARS`, `SEO42_REWRITEMODE_URLENCODE`, `SEO42_REWRITEMODE_INHERIT` (jeweils pro Sprache einstellbar)
* Optionen `urlencode_lowercase` und `urlencode_whitespace_replace` hinzugefügt
* Methoden `getLangName()` und `getOriginalLangName()` hinzugefügt
* Redirects werden nun im `generated` Ordner von SEO42 gespeichert
* `title_delimiter` auf Standardwert "-" gesetzt da Google anscheined gerne mal die Titles nachträglich abändert in den Suchergebnissen und dabei auch "|" in "-" konvertiert.
* Google Index Tool in seperatem Container und inkl. Domainfreischaltung
* Wenn Website Manager installiert, wird Schritt 1 im Setup deaktiviert
* Generelle Optimierungen für den Website Manager durchgeführt (z.B. `getImageManagerUrl()` verbessert)
* Extra Robots Einträge werden nun im `generated` Ordner von SEO42 angelegt. Website Manager Kompatibilität hergestellt.
* No-Caching Header für robots.txt und sitemap.xml hinzugefügt
* Skin Tuning für `simplerex` und `ppx_skin`
* ISO Lang-Datei hinzugefügt, damit Fehlermeldungen bei Install unter älteren REDAXO Versionen angezeigt werden
* `getLangTags()` berücksichtigt jetzt auch evtl. Query Params in der URL. Lässt sich über die neue Option `include_query_params` abschalten.
* Optionen `include_query_params` und `ignore_query_params` hinzugefügt
* Datei `settings.pagerank_checker.inc.php` umbenannt in `settings.domains.inc.php`
* Neuer FAQ Eintrag: `Warum kann kann man keine globalen Descriptions und Keywords eintragen, die dann für alle Seiten gültig sind?`
* URL Schema `url_rewrite` entfernt
* Finetuning, Codeoptimierungen, etc.

### Version 2.5.0 - 07. Oktober 2013

* Base-Tag wieder eingeführt ;) Damit ist es nun prinzipiell egal, wie die URLs beginnen, ausser bei REDAXO-Unterordnerinstallation. Dort dürfen Sie nicht mit `/` anfangen. Einfach trotzdem Codebeispiele 2 nutzen :P
* Für mehrsprachige Websites steht jetzt die PHP-Methode `seo42::getLangTags()` zur Verfügung um im Head-Bereich SEO-relevante `rel="alternate"` Tags hinzuzufügen.
* Fixed #62: Notices bei Installation des Community Addons beseitigt. Nötige Anpassungen in Zusammenhang mit der Klasse `rex_navigation42` zur Readme hinzugefügt.
* Fixed: Verschwundene Image Manager Bilder im Medienpool
* Automatische Canonical URL übernimmt jetzt mögliche Query Strings (z.B. seite=1, seite=2). Methoden-Parameter `$ignoreQueryParams` für `seo42::getCanonicalUrl()` hinzugefügt damit man Parameter (foo, foo=bar) als Array angeben kann, die eine eindeutige (kanonische) URL andeuten (z.B. seite=1).
* Automatische Weitereitung von `/langslug` nach `/langslug/`. 
* Neue Option `rewriter` um die URL-Umschreibung ein/auszuschalten, da dies über `REX['MOD_REWRITE']` ja nicht mehr geht.
* `Options -Indexes` (zum Abschalten des Directory Listings des Webservers) wird nur noch gesetzt, wenn im Setup angegeben. Inkl. Hinweis wegen möglichem 500 Servererror.
* Hilfesystem verbessert. Markdown Parser wird nun genutzt.
* Sonderzeichen wie z.B. der Middle dot `•` zu Anfang im Artikelnamen werden nun ausgeklammert aus der URL-Umschreibung. Davor wurden diese (bzw. das Leerzeichen danach) als `-` umgeschrieben.
* Redirects Plugin wird bei Installation des Addons nicht mehr automatisch mitinstalliert
* Sitemap.xml: Sortierung der Einträge anhand der Artikel-IDs
* Sitemap.xml: Neue Option `static_sitemap_priority` um die Priorität auf die festgelegten Werte 1.0 und 0.8 zu setzen. Sonst autom. Berechnung anhand des Kategorie-Levels.
* Option `subdir_force_full_urls` enfernt. Option `url_start_subdir` hinzugefügt.
* Redirects in die Debug-Ausgabe mit aufgenommen (wenn verfügbar) 
* Textkorrekturen, Feintuning, Codebeispiele erweitert, FAQ verbessert

### Version 2.1.2 - 25. September 2013

* Fixed: Notices and bestimmte PHP-Fehler wenn z.B. ein Artikel entfernt wurde, auf den bereits per URL-Typ verwiesen war.
* Fixed: Problem mit der Pathlist wenn ein Artikel/Kategorie geändert wurde.

### Version 2.1.1 - 23. September 2013

* Fixed: Beim Löschen einer Kategorie oder eines Artikels kam ein PHP-Fehler
* Kleine optische Verbesserungen

### Version 2.1.0 - 22. September 2013

* Fixed #59: Es kam eine leere Seite bei Block speichern/übernehmen oder nach Cache löschen tauchte ein PHP-Memory Problem auf.
* Fixed: Die Startseite enthielt einen Lang Slug, auch wenn Option `Starseite` -> `http://domain.de/lang-slug/` mit Option `Lang Slug` -> `Kein Lang Slug für Sprache: xy` aktiv war.
* Fixed: Bei einem 404 Fehler sollte der Fehlerartikel nun in der richtigen Sprache erscheinen
* Wurde bereits vor Installation des Addons eine unvollständige URL unter System eingegeben (z.B. www.redaxo.org), erscheint im Setup direkt eine entsprechende Meldung.
* Klasse `rex_navigation42` inkl. Codebeispiele zu SEO42 hinzugefügt. Methode `getMenuByLevel()` in `getNavigationByLevel()` und `getMenuByCategory()` in `getNavigationByCategory()` umbenannt.
* Option `Starseite` standardmäßig auf `http://domain.de/lang-slug/` gesetzt. Greift nur bei mehrsprachigen Websites. Hier wird dann z.B. `/en/home.html` direkt in `/en/` umgeschrieben.
* Neue Optionen `global_special_chars` und `global_special_chars_rewrite` zur `settings.lang.inc.php` hinzugefügt (für die URL-Umschreibung). Damit lassen sich Sonderzeichen definieren die für alle Sprachen gültig sind. Die sprachabhängigen SpecialChars haben eine höhere Priorität bei der Ersetzung wie die sprachunabhängigen, globalen SpecialChars.
* RexSEO EP `REXSEO_SPECIAL_CHARS` entfernt, da nun die SepcialChars über die `settings.lang.inc.php` gesetzt werden.
* Neues Codebeispiel für: Sprachunabhängiger Website-Name im Titel
* Umleitungsvariante "WWW -> Nicht-WWW" hinzugefügt. Über die neuen Option `non_www_to_www` ist es möglich zu steuern welche Art von WWW-Umleitung man im Setup haben möchte.
* Es wird geprüft ob die URL schon existiert bei URL-Typen "Interne URL" sowie "Root-Katagorie entfernen"
* Beim URL-Typ "Interne URL" wird beim Setzen einer neuen URL diese korrekt umgeschrieben falls z.B. Sonderzeichen vorkommen

### Version 2.0.0 - 18. September 2013

* AddOn wurde von REXSEO42 in SEO42 umbenannt. Die Klasse `rexseo42` wurde in `seo42` umbenannt. Ein Update-Anleitung findet sich in der README.md.
* Neue URL-Page zum manipulieren von URLs. Einige Url-Typen greifen erst, wenn bei der Ausgabe der Navigation auf diese reagiert wird. Die Klasse `rex_navigation42` unterstützt diese Typen bereits (ab 2.1.0 in SEO42 beigelegt). Zusätzliche URL-Typen sind über die Option `all_url_types` abschaltbar.
* Neues Recht `url_default` hinzugefügt um normalen Benutzer die URL-Page ein bzw. auszuschalten.
* PHP-Methode `setWebsiteName()` hinzugefügt sowie `getTitle()` um Parameter `$websiteName` erweitert. Damit lässt sich z.B. über das String Table Addon einen anderen Website-Namen (der damit dann auch sprachunabhängig sein kann) zwecks Titel-Generierung setzen.
* Titel-Vorschau in der Seopage nach oben verschoben
* Neue Optionen `seopage` und `urlpage` um die beiden Seiten global abzuschalten, wenn nicht gebraucht.
* Plugins werden automatisch in das SEO42-Menü eingebunden, wenn installiert und aktiviert (nur für Entwickler interessant).
* Die NoIndex Checkbox in der SEO-Page wurde standardmäßig abgeschaltet. Über die Option `noindex_checkbox` wieder einzuschalten.
* Die No-Prefix/Suffix Checkbox in der SEO-Page wurde standardmäßig abgeschaltet. Über die Option `no_prefix_checkbox` wieder einzuschalten.
* PageRank Checker zu den Tools hinzugefügt. Lässt sich über die Option `pagerank_checker` ausschalten. Domain-Freischalt-Funktion ist Website Manager kompatibel.
* Auf der Debug Seite wird nun auch die Pathlist ausgegeben.
* Option `title_preview` hinzugefügt um die Titel-Vorschau abzuschalten, falls man sein eigenes Titel-Schema implementiert hat.
* Umbenennungen der Optionen: `userdef_canonical_url` -> `custom_canonical_url`, `hide_no_prefix_checkbox` -> `no_prefix_checkbox`.
* Updatedatum des Artikels wird nun automatisch aktualisiert, wenn Änderungen über die SEO-Page durchgeführt wurden.
* Redirects Plugin wird automatisch installiert und aktiviert sobald SEO42 installiert wird.
* Redirects Plugin hinzugefügt um 301 Weiterleitungen komfortabel über das Backend anlegen zu können. Bitte Urls immer mit einem Splash beginnen, die Ziel Url kann aber auch mit http:// beginnen. Plugin ist Website Manager kompatibel.

### Version 1.2.1 - 21. Mai 2013

* Fixed: Es kam ein PHP-Error wenn kein Artikel vorhanden

### Version 1.2.0 - 18. Mai 2013

* Neues Plugin `url_generate` von tbaddade in die README.md mit aufgenommen
* Neuer FAQ Eintrag wegen möglichen 500 Server Error, siehe auch Hinweise in der README.md
* Neues Recht: `seo42[tools_only]` für Zugriff auf die Tools-Page für Nicht-Admins (`seo42[]` muss mit ausgewählt werden)
* Wenn ein Artikel nicht indiziert werden soll wird zusätzl. noch ein `X-Robots Header` ausgegeben.
* Prefix/Suffix Unterscheidung für die Checkbox in der SEO-Page inkl. Option `hide_no_prefix_checkbox` um die Checkbox zu verstecken, wenn anderes Titelschema benötigt
* Beim WWW-Redirect in der `.htaccess` Datei werden jetzt Sudomains ausgeklammert (wichtig für Website Manager AddOn)
* `ignore_root_cats` Option verbessert
* Fixed #49: Wenn die Url einen Trennstrich hatte, wurde fälschlicherweise ein Unterordner entdeckt
* RewriteRules für Website Manager Addon verbessert

### Version 1.1.42 - 23. April 2013

* Fixed #41: Parameter in der URL (test.html?foo=bar) führten zu einem 404 Fehler
* Neues Feature: man kann nun direkt aus dem Backend heraus sich alle Einstellungen anschauen die gerade aktiv sind
* Verbesserte Debug Info: es werden jetzt auch alle Settings und die .htaccess Datei mit ausgegeben
* `REX['MOD_REWRITE']` wird on the fly aktiviert sobald AddOn aktiv (master.inc.php wird dafür nicht angepasst)
* Unterordner Verhalten (hoffentlich) verbessert: `subdir_force_full_urls` hinzugefügt, Base Tag komplett entfernt
* Setup für Unterordner-Installationen angepasst: u.a. Option hinzugefügt um die RewriteBase automatisch zu setzen
* Kleine kosmetische Änderungen sowie Textänderungen
* Einstellungsseite lässt sich wieder speichern

### Version 1.0.42 - 10. April 2013

* Änderungen am Datenbank-Schema: `seo_canonical_url` hinzugefügt, `seo_url` in `seo_custom_url` umbenannt
* Neues Feature: Die Canonical Url kann nun auch per Artikel gesetzt werden. Allerdings muss dies explizit in der `settings.advanced.inc.php` aktiviert werden.
* Neues Feature: Es ist nun mögliche "volle" Urls (also inkl. Domain, wie bei WordPress) über die Option `full_urls` zu erhalten
* Neues Feature: `ignore_root_cats` (experimentell)
* Bei normalen Installationen: Base-Tag kann weggelassen werden.
* "Normale" 404-Seite des Webservers, wenn eine Datei unter `files` oder `redaxo` nicht gefunden wurde
* Neue Debug Seite in der Hilfe
* Neue Hilfe Unterpunkte: Codebeispiele und Links, Faq überarbeitet
* Verzeichnis-Auflistung z.B. für files und `files/addon`s wird per .htaccess Datei unterbunden
* Neues Feature: One Page Mode, für Websites die nur über eine einzige Seite verfügen 
* Neues Feature: SEO Tools - eine Linksammlung zu wichtigen SEO-Tools im Netz
* Neue Permission: `seo42[seo_default]` und `seo42[seo_extended]` um für Nicht-Admins die Sichtbarkeit der SEO-Page zu steuern
* editContentOnly[] wird nun berücksichtigt
* Neues Feature: Checkbox zum setzen der WWW-Umleitung im Setup
* `REX['MEDIA_DIR']` wird genutzt
* Robots noIndex Option:  Bug gefixt der bei Mehrsprachigkeit auftrat
* Lang Codes für die Lang Slugs können nun vorläufig in der `settings.lang.inc.php` definiert werden
* Auto DB Fix nach DB-Import wenn die DB Felder fehlen sollten
* SEO-Page: Title, Description und Keywords abgesichert. Keywords werden z.B. nur kleingeschrieben übernommen.
* SEO-Page: Html und CSS aufgeräumt und verbessert
* seo42 Klasse aufgeräumt und ergänzt: `getHtml()`, `getImageTag()`, `getImageManagerUrl()`, etc.
* Änderungen bis RexSEO 1.5.4 reingenommen
* I18N Support: Alles Strings in die Lang-Datei gepackt
* .htaccess Datei aufgeräumt und vereinfacht, `redaxo/.htaccess` entfernt
* Weitere Einstellungen wurden in die `settings.advanced.inc.php` gepackt
* Code wurde generell vereinfacht und aufgeräumt 
* Lizendatei hinzugefügt
* Installation wird bei bei diesen installierten Addons verweigert: `rexseo`, `url_rewrite`, `yrewrite`
* Bei REDAXO 4.5 Beta Versionen wird die Installation nun nicht mehr verweigert
* Aufruf von ADDONS_INCLUDED auf early gesetzt

### Version 1.0.1 - 17. Feburar 2013

* Changelog eingeführt
* Entfernt: Restore Config/Settings Backup
* Einstellungen aus config.inc.php in settings.inc.php verschoben
* Option in settings.inc.php: bei Deinstallation DB-Felder inkl. Daten erhalten 

### Version 1.0.0 - 09. Feburar 2013

Erstes Release mit folgenden Änderungen/Features gegenüber dem original RexSEO:

* Läuft nur mit REDAXO 4.5+
* Entfernt: Selten gebrauchte Features aus der Benutzeroberfläche
* Entfernt: Hilfe-Icons
* Entfernt: Textile Abhängigkeit 
* Entfernt: Default Beschreibung und Suchbegriffe
* Entfernt: Checkbox "Erweiterte Einstellungen"
* Entfernt: GitHub Anbindung
* Entfernt: JavaScript/jQuery Stuff
* Entfernt: Sitemap Changefreq und Priotity
* Canonical URL wird automatisch pro Domain ermittelt, nicht mehr pro Artikel
* Neue Setup-Routine (Quickstart) die in 3 übersichtliche Schritte aufgeteilt ist
* Backups der .htaccess-Dateien werden in das Backup-Verzeichnis geschoben
* Kommt ohne MetaInfos aus stattdessen extra SEO-Page für jeden Artikel
* SEO-Page: Titel Schema laut Google Empfehlung
* SEO-Page: Titel lässt sich mit der Option "Kein Prefix" komplett selber bestimmen
* SEO-Page: Live Titel Vorschau inkl. Titelkürzung a la Google-Suchergebnis
* SEO-Page: Live Anzeige der Buchstaben/Wörter-Anzahl für Beschreibung/Suchbegriffe
* SEO-Page: Live Vorschau der benutzerdefinierten URL
* SEO-Page: noIndex-Option um Seiten aus dem Suchindex auszuschließen
* Klasse `rexseo_meta` durch statische Klasse `seo42` ersetzt
* sitemap.xml sowie robots.txt erhalten die Headeranweisung "X-Robots-tag: noindex"
* Links zur robots.txt und sitemap.xml in der Einstellungen-Seite
* .htaccess-Datei enthält Rewrite-Regel für suchmaschinenfreundliche Image-Manager-URLs 
* PHP Notices entfernt, Strict-Kompatibel
* REX['GENERATED_PATH'] aus REDAXO 4.5 wird genutzt
* FAQ mit der Antwort auf die Fragen aller Fragen ;)
