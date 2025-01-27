<?php

if ( ! defined( 'WPINC' ) ) { die( "Don't mess with us." ); }
if ( ! defined( 'ABSPATH' ) ) { exit; }

global $WP_ADMIN_DIR, $WP_ADMIN_URL;

// Find wp-admin file path

if (!defined('WP_ADMIN_DIR')) {
	if( $WP_ADMIN_DIR ) {
		define('WP_ADMIN_DIR', $WP_ADMIN_DIR);
	} else {
		if( !$WP_ADMIN_URL ) {
			$WP_ADMIN_URL = admin_url('/');
		}
		
		if( strpos($WP_ADMIN_URL, 'wp-admin') !== false ) {
			$WP_ADMIN_DIR = rtrim(str_replace(home_url('/') , strtr(ABSPATH, '\\', '/'), $WP_ADMIN_URL) , '/\\');
		} else {
			$WP_ADMIN_DIR = dirname(WP_CONTENT_DIR) . DIRECTORY_SEPARATOR . 'wp-admin';
		}
		
		define('WP_ADMIN_DIR', $WP_ADMIN_DIR);
	}
}

// Current plugin version ( if change, clear also session cache )
global $missing_persons_version;
if (function_exists('get_file_data') && $plugin_data = get_file_data(MISSING_PERSONS_FILE, array(
    'Version' => 'Version'
) , false)) {
    $missing_persons_version = $plugin_data['Version'];
}

if (!$missing_persons_version && preg_match('/\*[\s\t]+?version:[\s\t]+?([0-9.]+)/i', file_get_contents(MISSING_PERSONS_FILE) , $v)) {
    $missing_persons_version = $v[1];
}

if (!defined('MISSING_PERSONS_VERSION')) {
    define('MISSING_PERSONS_VERSION', $missing_persons_version);
}

// Is plugin in development mode
if ( ! defined( 'MISSING_PERSONS_DEV_MODE' ) ) {
	define( 'MISSING_PERSONS_DEV_MODE', false );
}

// Set cache time in minutes
if ( ! defined( 'MISSING_PERSONS_CACHE_IN_MINUTES' ) ) {
	define( 'MISSING_PERSONS_CACHE_IN_MINUTES', 60 );
}

// Set filename in /uploads folder for missing persons
if ( ! defined( 'MISSING_PERSONS_IMG_UPLOAD_DIR' ) ) {
	define( 'MISSING_PERSONS_IMG_UPLOAD_DIR', '/registar-nestalih' );
}

// Set filename in /uploads folder for news
if ( ! defined( 'MISSING_PERSONS_NEWS_IMG_UPLOAD_DIR' ) ) {
	define( 'MISSING_PERSONS_NEWS_IMG_UPLOAD_DIR', '/registar-nestalih-news' );
}

