<?php

// Actions
add_action( 'save_post', array( 'WPGeo_Distance_Cache', 'clear_cache' ) );

/**
 * WP Geo Distance Cache
 *
 * A collection of functions for managing and caching distance results.
 */
class WPGeo_Distance_Cache {

	/**
	 * Get Query Key
	 *
	 * @since  0.1
	 *
	 * @param   array  $query  Query array to convert into a key string.
	 * @return  string         Key string.
	 */
	public static function get_query_key( $query ) {
		return md5( serialize( $query ) );
	}

	/**
	 * Get Cache
	 *
	 * @since  0.1
	 *
	 * @param   string  $key  Cache key.
	 * @return  string        Cached value.
	 */
	public static function get_cache( $key ) {
		$wpgeo_distances = get_transient( 'wpgeo_distances' );
		if ( isset( $wpgeo_distances[$key]) )
			return $wpgeo_distances[$key];
		return false;
	}

	/**
	 * Set Cache
	 *
	 * @since  0.1
	 *
	 * @param  string  $key    Cache key.
	 * @param  string  $value  Value.
	 */
	public static function set_cache( $key, $value ) {
		$wpgeo_distances = get_transient( 'wpgeo_distances' );
		$wpgeo_distances[$key] = $value;
		set_transient( 'wpgeo_distances', $wpgeo_distances );
	}

	/**
	 * Clear Cache
	 *
	 * Deletes the transient that stores cached distance query results.
	 * $post_id is only passed when used by the 'save_post' action.
	 * Calling this function with no parameters will just clear the transient.
	 *
	 * @since  0.1
	 *
	 * @param  int  $post_id  Optional. Post ID.
	 */
	public static function clear_cache( $post_id = 0 ) {
		if ( wp_is_post_revision( $post_id ) )
			return;
		delete_transient( 'wpgeo_distances' );
	}

}
