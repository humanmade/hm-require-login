<?php

/*
Plugin Name: HM Require Login
Description: Only allow the site to be accessed by logged in users.
Author: Human Made Limited
Version: 1.0.0
Author URI: http://hmn.md
*/

namespace HM\Require_Login;

require_once __DIR__ . '/inc/namespace.php';

add_action( 'init', __NAMESPACE__ . '\\redirect_user', 999 );

// Avoid leaking data from theme header / footer.
add_filter( 'theme_root', __NAMESPACE__ . '\\modify_theme_root', 999 );
add_filter( 'template', __NAMESPACE__ . '\\modify_template', 999 );
