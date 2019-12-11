<?php
// phpcs:ignorefile

namespace HM\Require_Login;

// Don't index any of these forms
add_action( 'login_head', 'wp_sensitive_page_meta' );

add_action( 'login_head', 'wp_login_viewport_meta' );

function wpmu_signup_stylesheet() {
	?>
	<style type="text/css">
		.mu_register { width: 90%; margin:0 auto; }
		.mu_register form { margin-top: 2em; }
		.mu_register h2 ~ p { margin: 10px 0; }
		.mu_register .error { font-weight: 600; padding: 10px; margin: 5px 0; color: #333333; background: #FFEBE8; border: 1px solid #CC0000; }
		.mu_register #blog_title,
			.mu_register #user_email,
			.mu_register #blogname,
			.mu_register #user_name { width:100%; font-size: 24px; margin:5px 0; line-height: 1.33333333; border-width: .0625rem; padding: .1875rem .3125rem; }
		.mu_register input[type="submit"] { margin: 10px 0 0; }
		.mu_register #site-language { display: block; }
		.mu_register .prefix_address,
			.mu_register .suffix_address { font-size: 18px; display:inline; }
		.mu_register label:first-of-type { margin: 0; }
		.mu_register label { display: block; margin: 10px 0 0; }
		.mu_register label.checkbox { display:inline; }
		.mu_register .mu_alert { font-weight: 600; padding: 10px; color: #333333; background: #ffffe0; border: 1px solid #e6db55; }
	</style>
	<?php
}

function wpmu_activate_stylesheet() {
	?>
	<style type="text/css">
		#language { margin-top: .5em; }
		#signup-welcome { margin: 10px 0; }
		span.h3 { padding: 0 8px; }
	</style>
	<?php
}

// Map WPMU signup specific actions.
if ( function_exists( 'wpmu_signup_stylesheet' ) ) {
	add_action( 'login_head', __NAMESPACE__ . '\\wpmu_signup_stylesheet' );
	add_action( 'login_head', 'do_signup_header' );
	$title = __( 'Signup' );
	$action = 'signup';
}

// Map WPMU activate specific actions.
if ( function_exists( 'wpmu_activate_stylesheet' ) ) {
	add_action( 'login_head', __NAMESPACE__ . '\\wpmu_activate_stylesheet' );
	add_action( 'login_head', 'do_activate_header' );
	$title = __( 'Activate' );
	$action = 'activate';
}

$login_title = get_bloginfo( 'name', 'display' );

/* translators: Login screen title. 1: Login screen name, 2: Network or site name. */
$login_title = sprintf( __( '%1$s &lsaquo; %2$s &#8212; WordPress' ), $title, $login_title );

/**
 * Filters the title tag content for login page.
 *
 * @since 4.9.0
 *
 * @param string $login_title The page title, with extra context added.
 * @param string $title       The original page title.
 */
$login_title = apply_filters( 'login_title', $login_title, $title );

?><!DOCTYPE html>
<!--[if IE 8]>
	<html xmlns="http://www.w3.org/1999/xhtml" class="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 8) ]><!-->
	<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
<title><?php echo $login_title; ?></title>
<?php

wp_enqueue_style( 'login' );

/**
 * Enqueue scripts and styles for the login page.
 *
 * @since 3.1.0
 */
do_action( 'login_enqueue_scripts' );

/**
 * Fires in the login page header after scripts are enqueued.
 *
 * @since 2.1.0
 */
do_action( 'login_head' );

$login_header_url = __( 'https://wordpress.org/' );

/**
 * Filters link URL of the header logo above login form.
 *
 * @since 2.1.0
 *
 * @param string $login_header_url Login header logo URL.
 */
$login_header_url = apply_filters( 'login_headerurl', $login_header_url );

$login_header_title = '';

/**
 * Filters the title attribute of the header logo above login form.
 *
 * @since 2.1.0
 * @deprecated 5.2.0 Use login_headertext
 *
 * @param string $login_header_title Login header logo title attribute.
 */
$login_header_title = apply_filters_deprecated(
	'login_headertitle',
	[ $login_header_title ],
	'5.2.0',
	'login_headertext',
	__( 'Usage of the title attribute on the login logo is not recommended for accessibility reasons. Use the link text instead.' )
);

$login_header_text = empty( $login_header_title ) ? __( 'Powered by WordPress' ) : $login_header_title;

/**
 * Filters the link text of the header logo above the login form.
 *
 * @since 5.2.0
 *
 * @param string $login_header_text The login header logo link text.
 */
$login_header_text = apply_filters( 'login_headertext', $login_header_text );

$classes = [ 'login-action-' . $action, 'wp-core-ui' ];

if ( is_rtl() ) {
	$classes[] = 'rtl';
}

$classes[] = ' locale-' . sanitize_html_class( strtolower( str_replace( '_', '-', get_locale() ) ) );

/**
 * Filters the login page body classes.
 *
 * @since 3.5.0
 *
 * @param array  $classes An array of body classes.
 * @param string $action  The action that brought the visitor to the login page.
 */
$classes = apply_filters( 'login_body_class', $classes, $action );

?>
</head>
<body class="login no-js <?php echo esc_attr( implode( ' ', $classes ) ); ?>">
<script type="text/javascript">
	document.body.className = document.body.className.replace('no-js','js');
</script>
<?php
/**
 * Fires in the login page header after the body tag is opened.
 *
 * @since 4.6.0
 */
do_action( 'login_header' );

?>
<div id="login">
	<h1><a href="<?php echo esc_url( $login_header_url ); ?>"><?php echo $login_header_text; ?></a></h1>

<?php
// Add submit button classes.
ob_start( function ( $output ) {
	$output = str_replace( 'type="submit" ', 'type="submit" class="button button-primary button-large"', $output );
	return $output;
} );
