Magnific Popup AddOn für REDAXO 4
=================================

Bindet das jQuery Lightbox Plugin [Magnific Popup](http://dimsemenov.com/plugins/magnific-popup/) in REDAXO Websites ein.

Features
--------

* Automatische Enbindung von Magnific Popup im Frontend inklusive Fade Effekt und deutscher Lokalisierung
* Zusätzliche optionale Einbindung von jQuery
* Benötigte Bildtypen werden direkt mitgeliefert und automatisch installiert
* Galerie und Einzelbild Modul inkl. komfortabler Installation
* Titel und Beschreibungstext für die Bilder werden aus dem Medienpool geholt
* Automatische suchmaschinenfreundliche Image Manager Urls wenn [SEO42](http://github.com/RexDude/seo42) installiert

Codebeispiel Einzelbild
-----------------------

```
<a class="magnific-popup-image" href="./files/full.jpg" title="Die Bildbeschreibung">
	<image src="./files/thumb.jpg" width="200" height="200" alt="" />
</a>
```

Codebeispiel Galerie
--------------------

```
<div class="magnific-popup-gallery">
	<a href="./files/full1.jpg" title="Die Bildbeschreibung #1">
		<image src="./files/thumb1.jpg" width="200" height="200" alt="" />
	</a>
	<a href="./files/full2.jpg" title="Die Bildbeschreibung #2">
		<image src="./files/thumb2.jpg" width="200" height="200" alt="" />
	</a>
	<a href="./files/full3.jpg" title="Die Bildbeschreibung #3">
		<image src="./files/thumb3.jpg" width="200" height="200" alt="" />
	</a>
</div>
```

Hinweise
--------

* Getestet mir REDAXO 4.5
* AddOn-Ordner lautet: `magnific_popup`
* Medienpool-Bildtitel ergbit das `alt` Attribute
* Medienpool-Bildbeschreibung ergbit das `title` Attribute und somit den Bilduntertitel für die Lightbox

Changelog
---------

siehe [CHANGELOG.md](CHANGELOG.md)

Lizenz
------

* Magnific Popup: siehe `/magnific_popup/files/LICENSE.md` (MIT Lizenz)
* Magnific Popup REDAXO AddOn: [LICENSE.md](LICENSE.md) (MIT Lizenz)

Credits
-------

* Magnific Popup Lightbox Plugin by Dmitry Semenov
* Parsedown Class by Emanuil Rusev

