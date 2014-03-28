<?php
$marvinMsgs[] = $I18N->msg('seo42_help_marvin_msg1');
$marvinMsgs[] = $I18N->msg('seo42_help_marvin_msg2');
$marvinMsgs[] = $I18N->msg('seo42_help_marvin_msg3');
$marvinMsgs[] = $I18N->msg('seo42_help_marvin_msg4');
$marvinMsgs[] = $I18N->msg('seo42_help_marvin_msg5');
$marvinMsgs[] = $I18N->msg('seo42_help_marvin_msg6');
$marvinMsgs[] = $I18N->msg('seo42_help_marvin_msg7');
$marvinMsgs[] = $I18N->msg('seo42_help_marvin_msg8');
$marvinMsgs[] = $I18N->msg('seo42_help_marvin_msg9');
$marvinMsgs[] = $I18N->msg('seo42_help_marvin_msg10');
$marvinMsgs[] = $I18N->msg('seo42_help_marvin_msg11');
?>

<p id="marvin"><a href="index.php?page=seo42&amp;subpage=<?php echo rex_request('subpage'); ?>&amp;chapter=<?php echo rex_request('chapter'); ?>"><img width="256" height="256" src="../<?php echo $REX['MEDIA_ADDON_DIR']; ?>/seo42/marvin.png" alt="" /></a></p>
<p id="marvin-says">&quot;<?php echo $marvinMsgs[mt_rand(0, count($marvinMsgs) - 1)]; ?>&quot;</p>

<style type="text/css">
#rex-page-seo42 p#marvin {
	text-align: center;
	margin-top: 30px;
}

#rex-page-seo42 p#marvin-says {
	text-align: center;	
	font-size: 15px;
	font-weight: bold;
	color: gray;
	margin: 30px 30px 30px 30px;
	line-height: 20px;
}
</style>
