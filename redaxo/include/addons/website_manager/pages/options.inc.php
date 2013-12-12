
<div class="rex-addon-output">
	<h2 class="rex-hl2"><?php echo $I18N->msg('website_manager_options'); ?></h2>
	<div class="rex-area-content">
		<p><?php echo $I18N->msg('website_manager_settings_msg'); ?></p>
		<p><code>/website_manager/settings.inc.php</code></p>
		<pre class="rex-code"><?php echo rex_website_manager_utils::print_r_pretty($REX['WEBSITE_MANAGER_SETTINGS']); ?></pre>
	</div>
</div>

<style type="text/css">
.rex-addon-output p {
	margin-bottom: 5px;
}

.rex-addon-output pre.rex-code {
	margin-top: 12px;
}
</style>
