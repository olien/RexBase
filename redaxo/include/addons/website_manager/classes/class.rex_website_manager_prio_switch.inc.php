<?php

class rex_website_manager_prio_switch extends rex_prio_switch {
	// overwritten to also update init file of website manager
	public static function handleAjaxCall($page, $func, $table, $idField, $useLike) {
		if (rex_request('page') == $page) {
			self::$ajaxFunctionName = $func;
		
			if (rex_request('func') == self::$ajaxFunctionName) {
				// update prio in db
				self::updatePrio(rex_request('order'), $table, $idField, $useLike);

				// update init file to reflect changes
				rex_website_manager::updateInitFile();

				// stop executing rest of redaxo stuff
				exit;
			}
		}
	}
}
