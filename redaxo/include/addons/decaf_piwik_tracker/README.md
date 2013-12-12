# [Piwik Tracker](http://www.redaxo.org/de/download/addons/?addon_id=774)

* Fügt den Tracking-Code in die Website ein, wahlweise als JavaScript-Schnipsel oder PHP-include.
* Zeigt eine grafische Statistik im REDAXO-Backend, die erstmal aufs Wesentliche reduziert ist (Besucher der letzten 14 Tage), aber recht frei konfiguriert werden kann.
* Bringt einen Direktlink auf die Piwik-Installation mit automatischem Login (optional).
* Zählt keine REDAXO-Redakteure mit. Auch nicht nach Ablauf der Sitzung (Cookie).

---

## Instructions (English only)

* Adds the tracking code to the website, either as JavaScript snippet or PHP include.
* Shows graphical statistics on the REDAXO backend being focused on essential output per default (last 14 days’ visits) but also being quite configurable.
* Adds a direct link on your Piwik installation with an auto login feature (optional).
* Does not track REDAXO authors. Either when session has ended (cookie).

### Requirements:

* Piwik Server
* PHP 5.2+
* REDAXO 4.1+
* If you want to track your visitors using PHP-Code and display the statistics in REDAXO `allow_url_fopen` needs to be turned on.

For more information on Piwik visit [http://piwik.org](http://piwik.org). They have good documentation how to setup the statistics server.

### Installation note for REDAXO 4.1 users:

If you want to use this addon with <strong>REDAXO 4.1</strong> you will need to manually create the folder `/files/addons/decaf_piwik_tracker/` and copy everything from `/redaxo/include/addons/decaf_piwik_tracker/files/` to the newly created folder.

### Configuration

Once the addon is installed you need to configure some parameters on the configuration page.

* **Tracker URL:** The URL to your Piwik-Server, no trailing slash please. E.g.: `http://stats.your-server.tpl`
* **Site Id:** The ID as shown in Piwik under [Settings » Websites].
* **Tracking Method:** Choose between Javascript (default) and PHP. The PHP Method is only available if `allow_url_fopen` is turned on. Javascript has the ability to track more information (e.g. screen sizes), while PHP is more dependable.
* **Auth Token:** The auth token is required if you want to include stats in the REDAXO backend. It's shown in Piwik under [Settings » Users.]
* **Username:** Optional parameter. Enables auto login at the Piwik stat server (requires username and password).
* **Password:** Optional parameter. Enables auto login at the Piwik stat server (requires username and password).

### Widget Configuration

To configure what statistics are displayed in the REDAXO backend you need to edit the `widgets.ini.php` in the `config/` folder. You can show multiple widgets by adding entries to the `widget.ini.php`.

* `api_period`: The period to display. Can be either `day`, `week`, `month` or `year`
* `api_date`: The date range to fetch. Right now only `lastX` is supportet. To fetch the last 6 weeks use `api_date= last6` and `api_period = week`.
* `columns`: What columns to display. You can use `nb_visits`, `nb_uniq_visitors` and `nb_actions`. Separate multiple values with commas (`,`) and **no spaces**.
* `width`: The width of the widget. Usually it's `745`. If you use smaller values the widgets will be displayed on the same row.
* `widget_title`: If you want to override the automatic title generation you can set your custom title here.