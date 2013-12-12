<?php
/**
 * RexMarkitup for Redaxo
 *
 * @version 0.9.8
 * @link http://markitup.jaysalvat.com
 * @author Redaxo Addon: rexdev.de
 * @package redaxo 4.4.x/4.5.x
 */


// GET PARAMS
////////////////////////////////////////////////////////////////////////////////
$mypage     = 'rex_markitup';      
$myroot     = $REX['INCLUDE_PATH'].'/addons/'.$mypage.'/';
$subpage    = rex_request('subpage', 'string');
$func       = rex_request('func', 'string');


// PAGE HEAD
////////////////////////////////////////////////////////////////////////////////
require $REX['INCLUDE_PATH'] . '/layout/top.php';

rex_title($REX['ADDON']['name'][$mypage].' <span class="addonversion">'.$REX['ADDON']['version'][$mypage].'</span>');


// SAVE SETTINGS
////////////////////////////////////////////////////////////////////////////////
if($func=='save_settings'){
  $settings   = rex_request('settings', 'array');
  foreach($settings as $k => $v){
    $settings[$k] = stripslashes($v);
  }

  $content = '$REX["rex_markitup"]["settings"] = '.var_export($settings,true).';';
  if(rex_replace_dynamic_contents($myroot.'config.inc.php', $content)){
    $REX["rex_markitup"]["settings"] = $settings;
  }
  echo rex_info('Settings saved.');
}


// PAGE BODY
////////////////////////////////////////////////////////////////////////////////


// SUBSUB NAVI
#$subsubnavi = $subsubnavi == '' ? 'Es sind keine Plugins installiert/aktiviert.' : $subsubnavi;
  
echo '
<!--<div class="rex-addon-output im-plugins">
  <h2 class="rex-hl2" style="font-size:1em;border-bottom:0;">./*$subsubnavi*/.</h2>
</div>-->

<div class="rex-addon-output im-plugins">
  <div class="rex-form">

    <form action="index.php?page='.$mypage.'" method="post">
      <input type="hidden" name="page" value="'.$mypage.'" />
      <input type="hidden" name="func" value="save_settings" />
      <input type="hidden" name="codemirror_options" value="" />

      <fieldset class="rex-form-col-1">
        <legend style="font-size:1.2em">RexMarkitup Settings</legend>
        <div class="rex-form-wrapper">


          <div class="rex-form-row">
            <p class="rex-form-col-a rex-form-text">
              <label for="imm_sql_where">IMM type condition:</label>
              <input type="text" id="imm_sql_where" style="font-family:monospace;font-size:1.3em;width:98%;margin-left:5px;" class="rex-form-text" name="settings[imm_sql_where]" value="'.htmlspecialchars($REX[$mypage]['settings']['imm_sql_where']).'" />
              <span style="margin-left:5px;color:gray;font-size:10px;font-family:monospace;">MySQL WHERE syntax</span>
            </p>
          </div><!-- .rex-form-row -->



          <div class="rex-form-row">
            <p class="rex-form-col-a rex-form-textarea">
              <label for="buttoncss">Button CSS</label>
              <textarea id="buttoncss" style="min-height:100px;font-family:monospace;font-size:1.3em;width:98%;margin-left:5px;" class="rex-form-textarea rex-codemirror" name="settings[buttoncss]">'.htmlspecialchars($REX[$mypage]['settings']['buttoncss']).'</textarea>
            </p>
          </div><!-- .rex-form-row -->


          <div class="rex-form-row">
            <p class="rex-form-col-a rex-form-textarea">
              <label for="buttondefinitions" style="width:auto;">rex_markitup.buttondefinitions:&nbsp;{…}</label>
              <textarea id="buttondefinitions" style="min-height:100px;font-family:monospace;font-size:1.3em;width:98%;margin-left:5px;" class="rex-form-textarea rex-codemirror" name="settings[buttondefinitions]">'.htmlspecialchars($REX[$mypage]['settings']['buttondefinitions']).'</textarea>
              <span style="margin-left:5px;color:gray;font-size:10px;font-family:monospace;">JS obj property syntax</span>
            </p>
          </div><!-- .rex-form-row -->


          <div class="rex-form-row">
            <p class="rex-form-col-a rex-form-textarea">
              <label for="buttonsets">rex_markitup.buttonsets:&nbsp;{…}</label>
              <textarea id="buttonsets" style="min-height:100px;font-family:monospace;font-size:1.3em;width:98%;margin-left:5px;" class="rex-form-textarea rex-codemirror" name="settings[buttonsets]">'.htmlspecialchars($REX[$mypage]['settings']['buttonsets']).'</textarea>
              <span style="margin-left:5px;color:gray;font-size:10px;font-family:monospace;">JS obj property syntax</span>
            </p>
          </div><!-- .rex-form-row -->


          <div class="rex-form-row">
            <p class="rex-form-col-a rex-form-textarea">
              <label for="options">rex_markitup.options:&nbsp;{…}</label>
              <textarea id="options" style="min-height:100px;font-family:monospace;font-size:1.3em;width:98%;margin-left:5px;" class="rex-form-textarea rex-codemirror" name="settings[options]">'.htmlspecialchars($REX[$mypage]['settings']['options']).'</textarea>
              <span style="margin-left:5px;color:gray;font-size:10px;font-family:monospace;">JS obj property syntax</span>
            </p>
          </div><!-- .rex-form-row -->


          <div class="rex-form-row rex-form-element-v2">
            <p class="rex-form-submit">
              <input class="rex-form-submit" type="submit" id="sendit" name="sendit" value="Einstellungen speichern" />
            </p>
          </div><!-- /rex-form-row -->

        </div><!-- /rex-form-wrapper -->
      </fieldset>
    </form>
  </div><!-- /rex-form -->
</div><!-- /rex-addon-output -->
';


$help = rex_get_file_contents($REX['INCLUDE_PATH'].'/addons/rex_markitup/lang/help.'.$REX['LANG'].'.textile');
$help = OOAddon::isActivated('textile') ? rex_a79_textile($help) : '<pre>'.$help.'</pre>';

echo '<div class="rex-addon-output im-plugins">
    <h2 style="font-size:1.2em" class="rex-hl2">Infos</h2>

    <div class="rex-addon-content">

    '.$help.'

    </div><!-- /rex-addon-content -->
  </div>';


require $REX['INCLUDE_PATH'] . '/layout/bottom.php';
