<?php

// Shortcodes
add_shortcode( 'wpgeo_distance', array( 'WPGeo_Distances', 'wpgeo_distance_shortcode' ) );

/**
 * WP Geo Distances Class
 */
class WPGeo_Distances {

	/**
	 * Get Distance
	 *
	 * @since  0.1
	 *
	 * @param   array  $args  Distance display parameters.
	 * @return  string        Distance HTML.
	 */
	public static function get_distance( $args ) {
		$args = wp_parse_args( $args, array(
			'round' => false,
			'unit'  => 'km'
		) );

		$distance_query = new WPGeo_Distance_Query();
		$distance = $distance_query->get_distance();
		$format = __( '%skm', 'wp-geo-distances' );

		// Unit
		if ( $args['unit'] == 'miles' ) {
			$distance = WPGeo_Distances::convert_distance( $distance, 'miles' );
			$format = __( '%s miles', 'wp-geo-distances' );
		}

		// Round
		if ( is_numeric( $args['round'] ) ) {
			$distance = round( $distance, absint( $args['round'] ) );
		}

		// Format
		$distance = sprintf( $format, $distance );
		return $distance;
	}

	/**
	 * Shortcode: [wpgeo_distance]
	 *
	 * @since  0.1
	 *
	 * @param   array  $args  Distance display parameters.
	 * @return  string        Distance HTML.
	 */
	public static function wpgeo_distance_shortcode( $atts ) {
		return WPGeo_Distances::get_distance( $atts );
	}

	/**
	 * Get Distances Between Coords
	 *
	 * @since  0.1
	 *
	 * @param   number  $lat1  First coordinate latitude.
	 * @param   number  $lon1  First coordinate longitude.
	 * @param   number  $lat2  Second coordinate latitude.
	 * @param   number  $lon2  Second coordinate longitude.
	 * @return  number         Distance in km.
	 */
	public static function get_distance_between_coords( $lat1, $lon1, $lat2, $lon2 ) {
		$R = 6371; // Radius of the earth in km
		$dLat = WPGeo_Distances::deg2rad( $lat2 - $lat1 );
		$dLon = WPGeo_Distances::deg2rad( $lon2 - $lon1 );
		$a = 
			sin( $dLat / 2 ) * sin( $dLat / 2 ) +
			cos( WPGeo_Distances::deg2rad( $lat1 ) ) * cos( WPGeo_Distances::deg2rad( $lat2 ) ) *
			sin( $dLon / 2 ) * sin( $dLon / 2 )
		; 
		$c = 2 * atan2( sqrt( $a ), sqrt( 1 - $a ) );
		return $R * $c;
	}

	/**
	 * Degrees to Radians
	 *
	 * @since  0.1
	 *
	 * @param   number  $deg   Degrees.
	 * @return  number         Radians.
	 */
	public static function deg2rad( $deg ) {
		return $deg * ( pi() / 180 );
	}

	/**
	 * Convert Distance
	 *
	 * @since  0.1
	 *
	 * @param   number  $distance  Distance.
	 * @param   string  $to        Unit to convert to.
	 * @param   string  $from      Optional. Unit to convert from (defaults to km).
	 * @return  number             Converted distance.
	 */
	public static function convert_distance( $distance, $to, $from = 'km' ) {
		if ( $from == 'km' ) {
			switch ( $to ) {
				case 'miles' :
					$distance *= 0.621371192;
			}
		}
		return $distance;
	}

}
