<?php
/**
 * 
 */

$api = facebookservice_api();
$url = $api->getLoginUrl(array(
	'next' => "{$vars['url']}pg/facebookservice/login",
	'req_perms' => 'offline_access,email',
));

$login = <<<__HTML
<div id="facebook_conenct">
	<a href="$url">
		<img src="{$vars['url']}mod/facebookservice/graphics/login-button.png" />
	</a>
</div>
__HTML;

echo $login;
