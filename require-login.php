<?php
namespace HM\Stack\Auth;
use Aws\Ssm\SsmClient;
function redirect_user() {
	if ( ( defined( 'WP_CLI' ) && WP_CLI ) || defined( 'DOING_CRON' ) ) {
		return;
	}

	/*
	 * Allow access to the connect oauth auth
	 * to go through.
	 */
	if ( strpos( $_SERVER['REQUEST_URI'], '/' . rest_get_url_prefix() . '/oauth2/' ) === 0 ) {
		return;
	}

	/*
	 * Allow pre-flight checks to the REST API.
	 */
	if ( 'OPTIONS' === $_SERVER['REQUEST_METHOD'] && strpos( $_SERVER['REQUEST_URI'], '/' . rest_get_url_prefix() ) === 0 ) {
		$origin = get_http_origin();
		if ( $origin ) {
			header( 'Access-Control-Allow-Origin: ' . esc_url_raw( $origin ) );
			header( 'Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE' );
			header( 'Access-Control-Allow-Credentials: true' );
			header( 'Access-Control-Allow-Headers: Authorization' );
			header( 'Access-Control-Allow-Expose-Headers: X-WP-Total, X-WP-TotalPages' );
			exit;
		}
	}

	if ( ! empty( $GLOBALS['pagenow'] ) && $GLOBALS['pagenow'] === 'wp-login.php' ) {
		return;
	}

	if ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) {
		return;
	}

	if ( current_user_can( 'read' ) ) {
		return;
	}

	if ( strpos( $_SERVER['REQUEST_URI'], '/' . rest_get_url_prefix() ) === 0 ) {
		http_response_code( 401 );
		exit;
	} else {
		auth_redirect();
	}
}

add_action( 'init', __NAMESPACE__ . '\\redirect_user', 999 );
