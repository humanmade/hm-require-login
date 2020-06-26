<?php

namespace HM\Require_Login;

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
			header( 'Access-Control-Allow-Headers: Authorization, Content-Type' );
			header( 'Access-Control-Allow-Expose-Headers: X-WP-Total, X-WP-TotalPages' );
			exit;
		}
	}

	$page = $GLOBALS['pagenow'] ?? null;
	$allowed = [
		'wp-login.php',
	];

	/**
	 * Filter pages allowed to pass through the login wall.
	 *
	 * Filters which pages are allowed through the login wall. "Pages" in this
	 * sense is the value of the `$pagenow` global.
	 *
	 * By default, only the login form is allowed to pass through; to allow
	 * registration on multisite, `wp-activate.php` and `wp-signup.php` need
	 * to be allowed too. Note that these use your theme, so may leak private
	 * theme data.
	 */
	$allowed = apply_filters( 'hm-require-login.allowed_pages', $allowed, $page );

	if ( $page && in_array( $page, $allowed, true ) ) {
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
