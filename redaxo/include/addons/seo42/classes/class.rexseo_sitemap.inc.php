<?php

class rexseo_sitemap
{
  private $mode;
  private $db_articles;


  /**
   * GET SITEMAP ARTICLES FROM DB
   *
   * @return (array) sitemap articles
   */
  private function get_db_articles()
  {
    global $REX, $REXSEO_URLS;

	if ($REX['ADDON']['seo42']['settings']['rewriter']) {
		// use rexseo pathlist
		array_multisort($REXSEO_URLS);

		foreach ($REXSEO_URLS as $url)  {
			$article = OOArticle::getArticleById($url['id'], $url['clang']);

			if (OOArticle::isValid($article)) {
				$hasPermission = true;

				// community addon
				if (class_exists('rex_com_auth') && !rex_com_auth::checkPerm($article)) {
					$hasPermission = false;
				}

				// add sitemap block
				if ($article->isOnline() && !isset($url['status']) && $hasPermission) {
					$db_articles[$url['id']][$url['clang']] = array('loc'        => rex_getUrl($url['id'], $url['clang']),
												                   'lastmod'    => date('c', $article->getValue('updatedate')),
												                   'changefreq' => self::calc_article_changefreq($article->getValue('updatedate'), ''),
												                   'priority'   => self::calc_article_priority($url['id'], $url['clang'], $article->getValue('path'), ''),
																   'noindex'   => $article->getValue('seo_noindex')
												                   );	
				}
			}
		}
	} else {
		// at the moment: no sitemap urls if rewriter is turned off
	}

    // EXTENSIONPOINT REXSEO_SITEMAP_ARRAY_CREATED
    $db_articles = rex_register_extension_point('REXSEO_SITEMAP_ARRAY_CREATED',$db_articles);

    // EXTENSIONPOINT REXSEO_SITEMAP_ARRAY_FINAL (READ ONLY)
    rex_register_extension_point('REXSEO_SITEMAP_ARRAY_FINAL',$db_articles);

    $this->db_articles = $db_articles;
  }


  /**
   * CALCULATE ARTICLE PRIORITY
   *
   * @param $article_id           (int)     rex_article.article_id
   * @param $clang                (int)     rex_article.clang
   * @param $path                 (string)  rex_article.path
   * @param $seo_priority  (float)   rex_article.seo_priority
   *
   * @return                      (float)   priority
   */
  private function calc_article_priority($article_id,$clang,$path,$seo_priority='')
  {
    global $REX;

    if ($seo_priority != '')
      return $seo_priority;

    if ($article_id == $REX['START_ARTICLE_ID'] && $clang == $REX['START_CLANG_ID']) {
      return 1.0;
    }

    if (isset($REX['ADDON']['seo42']['settings']['static_sitemap_priority']) && $REX['ADDON']['seo42']['settings']['static_sitemap_priority']) {
      return 0.8;
    }

	$prio = 1.0 - (0.1 * (count(explode('|',$path))-1));

    if ($prio >= 0) {
      return $prio;
    } else {
      return 0.0;
    }
  }


  /**
   * CALCULATE ARTICLE CHANGEFREQ
   *
   * @param $updatedate            (int)    rex_article.updatedate
   * @param $seo_changefreq (string) rex_article.seo_changefreq
   *
   * @return                       (string) change frequency  [never|yearly|monthly|weekly|daily|hourly|always]
   */
  private function calc_article_changefreq($updatedate,$seo_changefreq='')
  {
    if($seo_changefreq!='')
      return $seo_changefreq;

    $age = time() - $updatedate;

    switch($age)
    {
      case($age<604800):
        return 'daily';
      case($age<2419200):
        return 'weekly';
      default:
        return 'monthly';
    }
  }


  /**
   * BUILD SINGLE XML LOC FRAGMENT
   *
   * @param $loc        (string) full article url  [including lang]
   * @param $lastmod    (string) article last modified date  [UNIX date]
   * @param $changefreq (string) change frequency  [never|yearly|monthly|weekly|daily|hourly|always]
   * @param $priority   (float)  priority  [maximum: 1.0]
   *
   * @return            (string) xml location fragment
   */
  private function xml_loc_fragment($loc,$lastmod,$changefreq,$priority)
  {
    $xml_loc = "\t" . '<url>'.PHP_EOL.
    "\t\t" . '<loc>'.seo42::getServerUrl().seo42::trimUrl($loc).'</loc>'.PHP_EOL.
    "\t\t" . '<lastmod>'.$lastmod.'</lastmod>'.PHP_EOL.
    "\t\t" . '<changefreq>'.$changefreq.'</changefreq>'.PHP_EOL.
    "\t\t" . '<priority>'.number_format($priority, 1, ".", "").'</priority>'.PHP_EOL.
    "\t" . '</url>'.PHP_EOL;

    return $xml_loc;
  }


  /**
   * CONSTRUCTOR
   */
  public function rexseo_sitemap()
  {
    global $REX;

    $this->db_articles = array();
    $this->mode         = 'xml';

    self::get_db_articles();
  }


  /**
   * SET MODE
   *
   * @param $mode  (string)  [xml|json]
   */
  public function setMode($mode)
  {
    $this->mode = $mode;
  }


  /**
   * RETURN SITEMAP
   *
   * @return            (string) sitemap [xml|json]
   */
  public function get()
  {

    switch($this->mode)
    {
      case'json':
        return json_encode($this->db_articles);
      break;

      default:
        $xml_sitemap = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL.
        '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.PHP_EOL;
		global $REX;

		if ($REX['ADDON']['seo42']['settings']['one_page_mode']) {
			foreach($REX['CLANG'] as $clangId => $clangName) {
				$art = $this->db_articles[$REX['START_ARTICLE_ID']][$clangId];

				if ($art['noindex'] != true) {
				   	$xml_sitemap .= self::xml_loc_fragment($art['loc'],$art['lastmod'],$art['changefreq'],$art['priority']);
				}
			}	
		} else {
			if ($this->db_articles[$REX['START_ARTICLE_ID']][$REX['START_CLANG_ID']]['noindex'] != true) { // RexDude: Do not add Articles to Sitemap if Start Article has NoIndex Flag set.
				foreach($this->db_articles as $id=>$clangs)
				{
				  foreach($clangs as $art)
				  { 
					if ($art['noindex'] != true) {
				    	$xml_sitemap .= self::xml_loc_fragment($art['loc'],$art['lastmod'],$art['changefreq'],$art['priority']);
					}
				  }
				}
			}
		}

        // EXTENSIONPOINT REXSEO_SITEMAP_INJECT
        $inject = rex_register_extension_point('REXSEO_SITEMAP_INJECT');
        if(is_array($inject) && count($inject)>0)
        {
          foreach($inject as $key => $art)
          {
            $xml_sitemap .= self::xml_loc_fragment($art['url'],$art['lastmod'],$art['changefreq'],$art['priority']);
          }
        }

        $xml_sitemap .= '</urlset>';

        return $xml_sitemap;
    }
  }


  /**
   * SEND SITEMAP
   */
  public function send()
  {
    $map = self::get();

    while(ob_get_level()){
      ob_end_clean();
    } 

    switch($this->mode)
    {
      case'json':
        header('Content-Type: application/json; charset=utf-8');
      break;
      case'xml':
        header('Content-Type: application/xml; charset=utf-8');
      break;
      default:
    }

	// content length
    header('Content-Length: '.strlen($map));

	// no indexing
	header('X-Robots-Tag: noindex, noarchive');

	// no caching
	header('Cache-Control: must-revalidate, proxy-revalidate, post-check=0, pre-check=0, private'); 
    header('Pragma: no-cache');
    header('Expires: -1');

    echo $map;

    die();
  }


}

