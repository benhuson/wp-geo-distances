<?php

/*
Plugin Name: WP Geo Distances
Plugin URI: 
Description: Adds a distance shortcode to WP Geo.
Version: 0.1
Author: Ben Huson
Author URI: http://www.benhuson.co.uk/
Minimum WordPress Version Required: 3.1
Tested up to: 3.6.1
*/

define( 'WPGEO_DISTANCES_SUBDIR', '/' . str_replace( basename( __FILE__ ), '', plugin_basename( __FILE__ ) ) );
define( 'WPGEO_DISTANCES_URL', plugins_url( WPGEO_DISTANCES_SUBDIR ) );
define( 'WPGEO_DISTANCES_DIR', plugin_dir_path( __FILE__ ) );

// Language
load_plugin_textdomain( 'wp-geo-distances', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

/**
 * Load WP Geo Distances
 */
function wpgeo_distances_load() {
	if ( class_exists( 'WPGeo' ) ) {
		include_once( WPGEO_DISTANCES_DIR . 'includes/core.php' );
		include_once( WPGEO_DISTANCES_DIR . 'includes/cache.php' );
		include_once( WPGEO_DISTANCES_DIR . 'includes/query.php' );
	}
}

// Init.
add_action( 'plugins_loaded', 'wpgeo_distances_load' );
