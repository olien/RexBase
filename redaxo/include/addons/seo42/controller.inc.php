<?php
global $REX;

if (rex_request('rexseo_func')!='')
{
  $path = $REX['INCLUDE_PATH'].'/addons/rexseo';

  switch (rex_request('rexseo_func'))
  {
    case 'sitemap':
      require_once $REX['INCLUDE_PATH'].'/addons/seo42/classes/class.rexseo_sitemap.inc.php';
      $map = new rexseo_sitemap;

      switch(rex_request('mode'))
      {
        case'json':
          $map->setMode('json');
          $map->send();
        break;
        default:
          $map->send();
      }

      die();
    break;


  case 'robots':
      require_once $REX['INCLUDE_PATH'].'/addons/seo42/classes/class.rexseo_robots.inc.php';

      $robots = new rexseo_robots;
      $robots->setContent($REX['ADDON']['seo42']['settings']['robots']);
      $robots->addSitemapLink();
      $robots->send();

      die();
    break;

	case 'download':
		error_reporting(0);
		@ini_set('display_errors', 0);

		if ((isset($REX['ADDON']['seo42']['settings']['force_download_for_filetypes']) && is_array($REX['ADDON']['seo42']['settings']['force_download_for_filetypes']) && count($REX['ADDON']['seo42']['settings']['force_download_for_filetypes']) > 0) && isset($_GET["file"])) {
			$file = strtolower(preg_replace("/[^a-zA-Z0-9.\-\$\+]/","_", rex_get('file', 'string')));
			$file = urlencode(basename($file));
			$fileWithPath = realpath('./' . $REX['MEDIA_DIR'] . '/' . $file);
			$pathInfo = pathinfo($fileWithPath);

			if (isset($pathInfo['extension']) && in_array($pathInfo['extension'], $REX['ADDON']['seo42']['settings']['force_download_for_filetypes']) && file_exists($fileWithPath)) {
				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename=' . $file);
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
				header('Link: <' . seo42::getServerUrl() . ltrim(seo42::getMediaFile($file), "./") . '>; rel="canonical"');
				header('Content-Length: ' . filesize($fileWithPath));

				ob_clean();
				flush();

				readfile($fileWithPath);
				exit;
			}
		}
		break;
	default:
		break;

	}
}

