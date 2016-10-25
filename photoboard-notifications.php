<?php

/**
 * Plugin Name: Photoboard Notifications
 * Plugin URI: https://github.com/cferdinandi/photoboard-notifications
 * GitHub Plugin URI: https://github.com/cferdinandi/photoboard-notifications
 * Description: Add new album notifications to Photoboard
 * Version: 1.0.0
 * Author: Chris Ferdinandi
 * Author URI: http://gomakethings.com
 * License: All rights reserved
 */


require_once( plugin_dir_path( __FILE__ ) . 'includes/options.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/wp-session-manager/wp-session-manager.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/helpers.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/methods.php' );