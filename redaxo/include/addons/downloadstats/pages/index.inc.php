<?php
$mypage = "downloadstats";
$table = $REX['TABLE_PREFIX'].'file';
$table2 = $REX['TABLE_PREFIX'].'file_category';

## Include Header
include $REX['INCLUDE_PATH'].'/layout/top.php';

rex_title('Download Statistik');
echo '<div class="rex-addon-output">';

$orderby = '';

if(!rex_request('sort','string'))
  $orderby = 'ORDER BY med_counter DESC';

$query = "SELECT file_id, category_id, med_counter, filename, title, $table2.name FROM $table LEFT JOIN $table2 ON $table.category_id = $table2.id WHERE med_counter > 0 $orderby";
$list = rex_list::factory($query);
//$list->debug = true;
  
$list->setColumnLabel('med_counter','Downloads');
$list->setColumnSortable('med_counter');
$list->setColumnLabel('filename','Dateiname');
$list->addLinkAttribute('filename','onclick','openMediaDetails(\'\',###file_id###,###category_id###);return false;');
$list->setColumnLabel('title','Titel');
$list->setColumnSortable('title');
$list->setColumnParams('filename',array(''));
$list->setColumnSortable('filename');
$list->setColumnLabel('name','Kategorie');
$list->setColumnSortable('name');

$list->removeColumn('file_id');
$list->removeColumn('category_id');

$list->show();

echo '</div>';
## Include Footer
include $REX['INCLUDE_PATH'].'/layout/bottom.php';

?>


