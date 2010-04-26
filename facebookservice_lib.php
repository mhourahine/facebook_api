<?php
/**
 * 
 */

require_once "{$CONFIG->pluginspath}facebookservice/vendors/facebook-php-sdk/src/facebook.php";

function facebookservice_use_fbconnect() {
	return get_plugin_setting('sign_on', 'facebookservice') == 'yes';
}

function facebookservice_authorize() {
	var_dump('authorize');
}

function facebookservice_revoke() {
	var_dump('revoke');
}

function facebookservice_api() {
	return new Facebook(array(
		'appId' => get_plugin_setting('api_key', 'facebookservice'),
		'secret' => get_plugin_setting('api_secret', 'facebookservice'),
	));
}

function facebookservice_login() {
	global $CONFIG;
	
	// sanity check
	if (!facebookservice_use_fbconnect()) {
		forward();
	}
	
	// @hack Current htaccess rewrite rules do not preserve $_GET. Refs #2115
	$_GET['session'] = $CONFIG->input['session'];
	
	$facebook = facebookservice_api();
	if (!$session = $facebook->getSession()) {
		forward();
	}
	
	// attempt to find user
	$values = array(
		'plugin:settings:facebookservice:access_token' => $session['access_token'],
		'plugin:settings:facebookservice:uid' => $session['uid'],
	);
	
	if (!$users = get_entities_from_private_setting_multi($values, 'user', '', 0, '', 0)) {
		var_dump($session);exit;
	} elseif (count($users) == 1) {
		login($users[0]);
		
		system_message(elgg_echo('facebookservice:login:success'));
		forward();
	}
	
	// register login error
	register_error(elgg_echo('facebookservice:login:error'));
	forward();
}
