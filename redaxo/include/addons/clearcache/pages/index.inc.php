<?php
// post vars
$page = rex_request('page', 'string');
$func = rex_request('func', 'string');

// layout top
require($REX['INCLUDE_PATH'] . '/layout/top.php');

// title
rex_title($REX['ADDON']['name']['clearcache'] . ' <span style="font-size:14px; color:silver;">' . $REX['ADDON']['version']['clearcache'] . '</span>');
?>

<?php


if ($func == 'generate_all') {
	rex_generateAll();
	echo rex_info($I18N->msg('clearcache_cache_deleted'));
}
?>

<div class="rex-addon-output">
	<h2 class="rex-hl2"><?php echo $I18N->msg('clearcache_headline'); ?> <?php echo preg_replace('@^https?://|/.*|[^\w.-]@', '', $REX['SERVER']); ?></h2>
	<div class="rex-area-content">
		<form action="index.php" method="post">		
			<p class="button">
				<input type="submit" class="rex-form-submit" name="sendit" value="<?php echo $I18N->msg('clearcache_button'); ?>" />
			</p>
			<input type="hidden" name="page" value="clearcache" />
			<input type="hidden" name="subpage" value="tools" />
			<input type="hidden" name="func" value="generate_all" />
		</form>
	</div>
</div>

<style type="text/css">
.rex-addon-output p {
	margin-bottom: 5px;
	text-align: center;
}

.rex-addon-output .button {
	margin: 10px 0;
}

.rex-addon-output .button input {
	padding: 0 10px;
}
</style>

<?php 
// layout bottom
require($REX['INCLUDE_PATH'] . '/layout/bottom.php');
?>
