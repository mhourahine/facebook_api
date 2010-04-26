<?php
/**
 * Elgg Facebook Services
 * This service plugin allows users to authenticate their Elgg account with Facebook.
 * 
 * @package FacebookService
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright Curverider Ltd 2010
 */

require_once "{$CONFIG->pluginspath}facebookservice/facebookservice_lib.php";

register_elgg_event_handler('init', 'system', 'facebookservice_init');
function facebookservice_init() {
	elgg_extend_view('css', 'facebookservice/css');
	//elgg_extend_view('metatags', 'facebookservice/metatags');
	
	// register page handler
	register_page_handler('facebookservice', 'facebookservice_pagehandler');
	
	// Facebook Connect
	if (facebookservice_use_fbconnect()) {
		elgg_extend_view('account/forms/login', 'facebookservice/login');
	}
}

function facebookservice_pagehandler($page) {
	global $CONFIG;
	
	if (!isset($page[0])) {
		forward();
	}
	
	// @hack Current htaccess rewrite rules do not preserve $_GET. Refs #2115
	$_GET['session'] = $CONFIG->input['session'];
	
	switch ($page[0]) {
		case 'authorize':
			facebookservice_authorize();
			break;
		case 'revoke':
			facebookservice_revoke();
			break;
		case 'login':
			facebookservice_login();
			break;
		default:
			forward();
			break;
	}
}
