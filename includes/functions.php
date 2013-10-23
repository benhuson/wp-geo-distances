<?php

function wpgeo_distance_clear_cache( $post_id ) {
	if ( wp_is_post_revision( $post_id ) )
		return;
	delete_transient( 'wpgeo_distances' );
}
add_action( 'save_post', 'wpgeo_distance_clear_cache' );

function wpgeo_distance_shortcode( $atts ) {
	$atts = wp_parse_args( $atts, array(
		'round' => false,
		'unit'  => 'km'
	) );

	$distance_query = new WPGeo_Distance_Query();
	$distance = $distance_query->get_distance();
	$format = __( '%skm', 'wp-geo-distances' );

	// Unit
	if ( $atts['unit'] == 'miles' ) {
		$distance = WPGeo_Distances_Function::convert_distance( $distance, 'miles' );
		$format = __( '%s miles', 'wp-geo-distances' );
	}

	// Round
	if ( is_numeric( $atts['round'] ) ) {
		$distance = round( $distance, absint( $atts['round'] ) );
	}

	// Format
	$distance = sprintf( $format, $distance );
	return $distance;
}
add_shortcode( 'wpgeo_distance', 'wpgeo_distance_shortcode' );

class WPGeo_Distances_Function {

	/**
	 * Get Distances Between Coords
	 *
	 * @param   number  $lat1  First coordinate latitude.
	 * @param   number  $lon1  First coordinate longitude.
	 * @param   number  $lat2  Second coordinate latitude.
	 * @param   number  $lon2  Second coordinate longitude.
	 * @return  number         Distance in km.
	 */
	function get_distance_between_coords( $lat1, $lon1, $lat2, $lon2 ) {
		$R = 6371; // Radius of the earth in km
		$dLat = WPGeo_Distances_Function::deg2rad( $lat2 - $lat1 );
		$dLon = WPGeo_Distances_Function::deg2rad( $lon2 - $lon1 );
		$a = 
			sin( $dLat / 2 ) * sin( $dLat / 2 ) +
			cos( WPGeo_Distances_Function::deg2rad( $lat1 ) ) * cos( WPGeo_Distances_Function::deg2rad( $lat2 ) ) *
			sin( $dLon / 2 ) * sin( $dLon / 2 )
		; 
		$c = 2 * atan2( sqrt( $a ), sqrt( 1 - $a ) );
		return $R * $c;
	}

	/**
	 * Degrees to Radians
	 *
	 * @param   number  $deg   Degrees.
	 * @return  number         Radians.
	 */
	function deg2rad( $deg ) {
		return $deg * ( pi() / 180 );
	}

	/**
	 * Convert Distance
	 *
	 * @param   number  $distance  Distance.
	 * @param   string  $to        Unit to convert to.
	 * @param   string  $from      Optional. Unit to convert from (defaults to km).
	 * @return  number             Converted distance.
	 */
	function convert_distance( $distance, $to, $from = 'km' ) {
		if ( $from == 'km' ) {
			switch ( $to ) {
				case 'miles' :
					$distance *= 0.621371192;
			}
		}
		return $distance;
	}

}
