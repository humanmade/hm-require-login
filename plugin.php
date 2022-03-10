<?php
/**
 * Plugin Name: HM Require Login
 * Description: Only allow the site to be accessed by logged in users.
 * Author: Human Made Limited
 * Version: 1.0.5
 * Author URI: https://humanmade.com
 */

namespace HM\Require_Login;

require_once __DIR__ . '/inc/namespace.php';

add_action( 'init', __NAMESPACE__ . '\\redirect_user', 999 );

// Ensure application passwords can be verified early for REST API requests.
if ( strpos( $_SERVER['REQUEST_URI'], '/' . rest_get_url_prefix() ) === 0 ) {
	add_filter( 'application_password_is_api_request', '__return_true' );
}
