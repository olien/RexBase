<?php

class rex_prio_switch {
	protected static $ajaxFunctionName;

	public static function handleAjaxCall($page, $func, $table, $idField, $useLike) {
		if (rex_request('page') == $page) {
			self::$ajaxFunctionName = $func;
		
			if (rex_request('func') == self::$ajaxFunctionName) {
				// update prio in db
				self::updatePrio(rex_request('order'), $table, $idField, $useLike);

				// stop executing rest of redaxo stuff
				exit;
			}
		}
	}

	protected static function updatePrio($order, $table, $idField, $useLike) {
		global $REX;

		$sql = rex_sql::factory();

		foreach ($order as $prio => $keyname) {
			$sql->setQuery('UPDATE ' . $table . ' SET priority = ' . ($prio + 1) . ' ' . self::getWhere($useLike, $idField, $keyname));
		}
	}

	protected function getWhere($useLike, $idField, $value) {
		if ($useLike) {
			return 'WHERE ' . $idField . ' like "' . $value . '"';
		} else {
			return 'WHERE ' . $idField . ' = ' . $value . '';
		}
	}

	public static function printSwitch($strings, $adminOnly = true) {
		global $REX;

		$out = '';

		if (!$adminOnly || (isset($REX['USER']) && $REX['USER']->isAdmin())) {
			$out = '
				<div class="onoffswitch-outer">
					<span>' . $strings[0] . '</span> 
					<div class="onoffswitch">
						<input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch">
						<label class="onoffswitch-label" for="myonoffswitch">
							<div class="onoffswitch-inner"></div>
							<div class="onoffswitch-switch"></div>
						</label>
					</div> 
				</div>

				<style type="text/css">
				.rex-table tbody tr {
					cursor: auto;
				}

				.rex-table tbody tr.move {
					cursor: move;
				}

				.rex-table tbody tr:hover.move {
					background-color: #e3e3e3;
				}

				div#rex-website tr.move a {
					text-decoration: none;
					cursor: move;
				}

				.onoffswitch-outer {
					float: right;
					min-width: 148px;
				}

				.onoffswitch-outer span { 
					margin-right: 7px; 
					margin-top: 17px;
					float: left;
				}

				.onoffswitch {
					position: relative; width: 61px;
					-webkit-user-select:none; -moz-user-select:none; -ms-user-select: none;
					margin-top: 13px;float: right;
				}

				.onoffswitch-checkbox {
					display: none;
				}

				.onoffswitch-label {
					display: block; overflow: hidden; cursor: pointer;
					border: 2px solid #bbb; border-radius: 17px;
				}

				.onoffswitch-inner {
					width: 200%; margin-left: -100%;
					-moz-transition: margin 0.3s ease-in 0s; -webkit-transition: margin 0.3s ease-in 0s;
					-o-transition: margin 0.3s ease-in 0s; transition: margin 0.3s ease-in 0s;
				}

				.onoffswitch-inner:before, .onoffswitch-inner:after {
					float: left; width: 50%; height: 17px; padding: 0; line-height: 17px;
					font-size: 13px; color: white; font-family: Trebuchet, Arial, sans-serif; font-weight: bold;
					-moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box;
					border-radius: 17px;
					box-shadow: 0px 8.5px 0px rgba(0,0,0,0.08) inset;
				}

				.onoffswitch-inner:before {
					content: "' . $strings[1] . '";
					padding-left: 9px;
					background-color: #4BC21F; color: #FFFFFF;
					border-radius: 17px 0 0 17px;
				}

				.onoffswitch-inner:after {
					content: "' . $strings[2] . '";
					padding-right: 9px;
					background-color: #EEEEEE; color: #828282;
					text-align: right;
					border-radius: 0 17px 17px 0;
				}

				.onoffswitch-switch {
					width: 17px; margin: 0px;
					background: #FFFFFF;
					border: 2px solid #bbb; border-radius: 17px;
					position: absolute; top: 0; bottom: 0; right: 40px;
					-moz-transition: all 0.3s ease-in 0s; -webkit-transition: all 0.3s ease-in 0s;
					-o-transition: all 0.3s ease-in 0s; transition: all 0.3s ease-in 0s;
					background-image: -moz-linear-gradient(center top, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0) 80%);
					background-image: -webkit-linear-gradient(center top, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0) 80%);
					background-image: -o-linear-gradient(center top, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0) 80%);
					background-image: linear-gradient(center top, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0) 80%);
					box-shadow: 0 1px 1px white inset;
				}

				.onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-inner {
					margin-left: 0;
				}

				.onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-switch {
					right: 0px;
				}
				</style>

				<script type="text/javascript">

				jQuery(document).ready( function($) {
					if (!jQuery.ui) {
						$("head").append("<script type=\'text/javascript\' src=\'../' . $REX['MEDIA_ADDON_DIR'] . '/' . rex_request('page') . '/jquery-ui.min.js\' />");
					}

					if (jQuery.ui) {
						$(".rex-table tbody").sortable({
							helper: function(e, tr) {
								var $originals = tr.children();
								var $helper = tr.clone();

								$helper.children().each(function(index) {
									$(this).width($originals.eq(index).width());
								});

								return $helper;
							},
							cursor: "move",
							stop:function(i) {
								var order = [];

								$(".rex-table tbody tr").each(function() {
									order.push($(this).find("td:nth-child(2)").html());
								});

								$.ajax({
									type: "POST",
									url: window.location.pathname + "?page=' . rex_request('page') . '&func=' . self::$ajaxFunctionName . '",
									data: { "order[]": order },
									success: function() {
									}               
								});
							}
						});

						$(".onoffswitch-checkbox").change(function() {
					  		if ($(this).attr("checked")){
								$(".rex-table tbody").sortable("enable");
								$(".rex-table tbody tr").addClass("move");
							} else {
								$(".rex-table tbody").sortable("disable");
								$(".rex-table tbody tr").removeClass("move");
							}
						});

						$(".onoffswitch-checkbox").change();
						$(".rex-table tbody").sortable("disable");
					}
				});
				</script>
			';
		}

		echo $out;
	}
}
