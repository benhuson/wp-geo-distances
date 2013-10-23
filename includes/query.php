<?php

/**
 * WP Geo Distance Query
 */
class WPGeo_Distance_Query {

	var $query    = null;
	var $posts    = null;
	var $distance = 0;

	function WPGeo_Distance_Query( $query = null ) {
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

	function _calculate_distance() {
		$this->distance = 0;
		$previous_lat = '';
		$previous_lng = '';
		$hash = md5( serialize( $this->query ) );
		$wpgeo_distances = get_transient( 'wpgeo_distances' );
		if ( false === $wpgeo_distances || ! isset( $wpgeo_distances[$hash] ) ) {
			if ( $this->posts ) {
				foreach ( $this->posts as $p ) {
					$this_lat = get_post_meta( $p->ID, WPGEO_LATITUDE_META, true );
					$this_lng = get_post_meta( $p->ID, WPGEO_LONGITUDE_META, true );
					if ( $this_lat != '' && $this_lng != '' ) {
						if ( $previous_lat != '' && $previous_lng != '' ) {
							$delta = WPGeo_Distances_Function::get_distance_between_coords( $previous_lat, $previous_lng, $this_lat, $this_lng );
							$this->distance += $delta;
						}
						$previous_lat = $this_lat;
						$previous_lng = $this_lng;
					}
				}
			}
			$wpgeo_distances[$hash] = $this->distance;
			set_transient( 'wpgeo_distances', $wpgeo_distances );
		}
		$this->distance = $wpgeo_distances[$hash];
	}

	function get_distance() {
		return $this->distance;
	}

}
