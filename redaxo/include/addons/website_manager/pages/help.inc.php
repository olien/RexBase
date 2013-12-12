<?php
$REX['WEBSITE_MANAGER_REXVARS']['SERVER'] = $REX['SERVER'];
$REX['WEBSITE_MANAGER_REXVARS']['SERVERNAME'] = $REX['SERVERNAME'];
$REX['WEBSITE_MANAGER_REXVARS']['START_ARTICLE_ID']= $REX['START_ARTICLE_ID'];
$REX['WEBSITE_MANAGER_REXVARS']['NOTFOUND_ARTICLE_ID'] = $REX['NOTFOUND_ARTICLE_ID'];
$REX['WEBSITE_MANAGER_REXVARS']['DEFAULT_TEMPLATE_ID'] = $REX['DEFAULT_TEMPLATE_ID'];
$REX['WEBSITE_MANAGER_REXVARS']['MEDIA_DIR'] = $REX['MEDIA_DIR'];
$REX['WEBSITE_MANAGER_REXVARS']['MEDIAFOLDER'] = $REX['MEDIAFOLDER'];
$REX['WEBSITE_MANAGER_REXVARS']['GENERATED_PATH'] = $REX['GENERATED_PATH'];
$REX['WEBSITE_MANAGER_REXVARS']['TABLE_PREFIX'] = $REX['TABLE_PREFIX'];
?>

<div class="rex-addon-output">
	<h2 class="rex-hl2"><?php echo $I18N->msg('website_manager_help'); ?></h2>
	<div class="rex-area-content">
		<p><?php echo $I18N->msg('website_manager_help_readme'); ?></p>
	</div>
</div>

<div class="rex-addon-output">
	<h2 class="rex-hl2"><?php echo $I18N->msg('website_manager_debug'); ?></h2>
	<div class="rex-area-content">
		<pre class="rex-code"><?php echo rex_website_manager_utils::print_r_pretty($REX['WEBSITE_MANAGER_REXVARS']); ?></pre>
	</div>
</div>

<style type="text/css">
.rex-addon-output pre.rex-code {
	
}
</style>

<?php
unset($REX['WEBSITE_MANAGER_REXVARS']);

