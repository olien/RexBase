<?php

$ausgabe = '';

$arr = explode(',','REX_LINKLIST[1]');

$letztesElement = end($arr); 

$aktuelleArtikelID = $REX['ARTICLE_ID'];

foreach ($arr as $value)
{
  $article = OOArticle::getArticleById($value);
 
  if(is_object($article))
  {
	$artikelid = $article->getID();
	
	if ($artikelid == $letztesElement) { $klasse_last = 'class="last"';} else { $klasse_last = '';}
	if ($artikelid == $aktuelleArtikelID ) { $klasse_active = 'class="aktiv"';} else { $klasse_active = '';}
	
	$ausgabe .= '<li><a '.$klasse_active.' href="'.rex_getUrl($value, $REX['CUR_CLANG']).'" title="'.$article->getName().'">'.$article->getName().'</a></li>'.PHP_EOL;		

  }
}

echo '<ul>'."\r\n";
echo $ausgabe;
echo '</ul>'."\r\n";
?>