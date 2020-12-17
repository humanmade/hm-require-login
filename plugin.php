<?php

/*
Plugin Name: HM Require Login
Description: Only allow the site to be accessed by logged in users.
Author: Human Made Limited
Version: 1.0.4
Author URI: http://hmn.md
*/

namespace HM\Require_Login;

require_once __DIR__ . '/inc/namespace.php';

add_action( 'template_redirect', __NAMESPACE__ . '\\redirect_user', 1 );
