<?php

/**
 * WP Geo Distance Query
 */
class WPGeo_Distance_Query {

	var $query    = null;
	var $posts    = null;
	var $distance = 0;

	/**
	 * Constructor
	 *
	 * @since  0.1
	 *
	 * @param  array  $query  Query parameters.
	 */
	public function WPGeo_Distance_Query( $query = null ) {
		$query = wp_parse_args( $query, array(
			'numberposts'  => -1,
			'meta_key'     => WPGEO_LATITUDE_META,
			'meta_value'   => '',
			'meta_compare' => '!='
		) );
		$this->query = $query;
		$this->posts = get_posts( $query );
		$this->_calculate_distance();
	}

	/**
	 * Calculate Distance
	 *
	 * Calculates the distance based on the query.
	 *
	 * @internal
	 * @since  0.1
	 */
	private function _calculate_distance() {
		$this->distance = 0;
		$previous_lat = '';
		$previous_lng = '';
		$hash = WPGeo_Distance_Cache::get_query_key( $this->query );
		$distance = WPGeo_Distance_Cache::get_cache( $hash );
		if ( false === $distance ) {
			if ( $this->posts ) {
				foreach ( $this->posts as $p ) {
					$this_lat = get_post_meta( $p->ID, WPGEO_LATITUDE_META, true );
					$this_lng = get_post_meta( $p->ID, WPGEO_LONGITUDE_META, true );
					if ( $this_lat != '' && $this_lng != '' ) {
						if ( $previous_lat != '' && $previous_lng != '' ) {
							$delta = WPGeo_Distances::get_distance_between_coords( $previous_lat, $previous_lng, $this_lat, $this_lng );
							$this->distance += $delta;
						}
						$previous_lat = $this_lat;
						$previous_lng = $this_lng;
					}
				}
			}
			WPGeo_Distance_Cache::set_cache( $hash, $this->distance );
		} else {
			$this->distance = $distance;
		}
	}

	/**
	 * Get Distance
	 *
	 * @since  0.1
	 *
	 * @param  number  Distance.
	 */
	public function get_distance() {
		return $this->distance;
	}

}
