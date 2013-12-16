<div id="sitemap">
<?PHP

if($REX['REDAXO'] != '1') {

function sitemapListe(&$openul,$lev,$PathIndex)        // UnterKategorie nur anzeigen wenn der aktuelle Eintrag auch aktiv ist
{
    $time = time();
    if(
        $lev->isOnline() AND rex_com_checkUserPerm($lev->getValue("art_com_perm"))           // Categorie online
 
       )
    {
        if($openul == 0)                // ul Liste muss noch eroeffnet werden
        {
            print '<ul class="sitemaplevel'.$PathIndex.'">';
            $openul = 1;
        }
        print '<li class="'.$lev->getValue('art_color').'main"><a class="level'.$PathIndex.'" href="'.$lev->getUrl().'">'.$lev->getName().'</a><p class="beschreibung">'.$lev->getValue('art_description').'</p>';
        // jetzt die Untereintraege
        $levSize = sizeof($lev->getChildren());
        if($levSize != 0)                          // es gibt Kinder
        {
            $opensubul = 0;
            foreach($lev->getChildren() as $sublevel)       // Subebene anzeigen
            {
                sitemapListe($opensubul,$sublevel,$PathIndex + 1);
            }
            if($opensubul == 1)
                echo "</ul>";
        }
        echo "</li>\n";
    }
}

function sitemapListe_2(&$openul,$lev,$PathIndex)   // UnterKategorie immer anzeigen wenn online
{
    $time = time();
    $closetag = 0;
    if( $lev->isOnline() AND rex_com_checkUserPerm($lev->getValue("art_com_perm")) )
    {
        if($openul == 0)                // ul Liste muss noch eroeffnet werden
        {
            print '<ul class="sitemaplevel'.$PathIndex.'">';
            $openul = 1;
        }
        print '<li class="main"><a class="level'.$PathIndex.'" href="'.$lev->getUrl().'">'.$lev->getName().'</a>';
        $closetag = 1;
    }
    // Unterkategorie immer bearbeiten
    $levSize = sizeof($lev->getChildren());
    if($levSize != 0)                          // es gibt Kinder
    {
        $opensubul = 0;
        foreach($lev->getChildren() as $sublevel)       // Subebene anzeigen
        {
            sitemapListe_2($opensubul,$sublevel,$PathIndex + 1);
        }
        if($opensubul == 1)
            echo "</ul>";
    }
    if($closetag)
        echo "</li>\n";
}

$openul = 0;
foreach (OOCategory::getRootCategories() as $lev1)
{
    sitemapListe($openul,$lev1,1);
}
if($openul == 1)
    echo "</ul>\n";

} else {
	echo "Sitemap wird angezeigt.";
}

?>
</div><!-- sitemap -->