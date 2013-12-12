<?php

// this addons will be reinstalled if new website is added
$REX['WEBSITE_MANAGER_SETTINGS']['reinstall_addons'] = array(); // e.g.: array('seo42', 'slice_status', 'string_table', 'tracking_code');

// this plugins will be reinstalled if new website is added
$REX['WEBSITE_MANAGER_SETTINGS']['reinstall_plugins'] = array(); // e.g.: array(array('be_utilities', 'hide_startarticle'));

// if true all websites will have one mediapool
$REX['WEBSITE_MANAGER_SETTINGS']['identical_media'] = false;

// if true all websites will have the same templates
$REX['WEBSITE_MANAGER_SETTINGS']['identical_templates'] = true;

// if true all websites will have the same modules and same actions
$REX['WEBSITE_MANAGER_SETTINGS']['identical_modules'] = true;

// >>>>> "true" is CURRENTLY UNSUPPORTED <<<<< if true all websites will have the same clangs
$REX['WEBSITE_MANAGER_SETTINGS']['identical_clangs'] = false;

// >>>>> "true" is CURRENTLY UNSUPPORTED <<<<< if true all websites will have the same meta infos. if false meta info addon will be reinstalled automatically if available.
$REX['WEBSITE_MANAGER_SETTINGS']['identical_meta_infos'] = false;

// if true all websites will have the same image types. if false image manager addon will be reinstalled automatically if available.
$REX['WEBSITE_MANAGER_SETTINGS']['identical_image_types'] = true;

// if true every user will have access to all websites
$REX['WEBSITE_MANAGER_SETTINGS']['ignore_permissions'] = false;

// if false admins won't be allowed to delete websites
$REX['WEBSITE_MANAGER_SETTINGS']['allow_website_delete'] = true;

// if true link to frontend will be shown in meta menu of redaxo backend
$REX['WEBSITE_MANAGER_SETTINGS']['show_metamenu_frontend_link'] = true;

// if true name of the website including a link to the frontend will be shown in redaxo header
$REX['WEBSITE_MANAGER_SETTINGS']['show_website_name_frontend_link'] = true;

// if true a color bar will be shown in redaxo header
$REX['WEBSITE_MANAGER_SETTINGS']['show_color_bar'] = true;

// if true favicon will be auto colorized too when adding new website
$REX['WEBSITE_MANAGER_SETTINGS']['colorize_favicon'] = true;

