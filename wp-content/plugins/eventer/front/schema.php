<?php
defined('ABSPATH') or die('No script kiddies please!');

/**
 * 
 */
class Eventer_Schema {

	/**
	 * Hold of the Instances Class
	 *
	 * @var array
	 */
	private static $_instance = null;

	/**
	 * Hold for the fetch data
	 *
	 * @var array
	 */
	protected static $_posts = [];

	/**
	 * Set the of the event structucred data
	 *
	 * @see https://developers.google.com/structured-data/rich-snippets/
	 * @var string
	 */
	public $type = 'Event';

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self;
		}

		return self::$_instance;
	}

	function __construct() {

		$eventer_event_schema = eventer_get_settings('eventer_event_schema');

		if ( 'on' !== $eventer_event_schema ) {
			return false;
		}

		add_action( 'wp_footer', [ $this, 'markup'] );
	}

	public function markup() {
		if ( ! is_singular( 'eventer' ) ) {
			return false;
		}

		global $post;

		$html = $this->get_markup( $post );

		/**
		 * Allows users to filter the markup of JSON-LD
		 *
		 * @param string The HTML for the JSON LD markup
		 */
		$html = apply_filters( 'eventer/json/markup', $html );
		echo $html;
	}

	public function get_markup( $post ) {

		$data = $this->get_data( $post );

		foreach ( $data as $post_id => $_data ) {
			$this->register( $post_id );
		}

		/**
		 * Allows to add or remove the eveneter schema data.
		 */
		$data = apply_filters( "eventer/json/event/data", $data );

		// Strip the post ID indexing before returning
		$data = array_values( $data );

		if ( ! empty( $data ) ) {
			$html[] = '<script type="application/ld+json">';
			$html[] = str_replace( '\/', '/', json_encode( $data ) );
			$html[] = '</script>';
		}

		return ! empty( $html ) ? implode( "\r\n", $html ) : '';
	}

	public function register( $post_id ) {

		if ( $this->exists( $post_id ) ) {
			return self::$_posts[ $id ];
		}

		self::$_posts[ $post_id ] = get_post( $post_id );
		return self::$_posts[ $post_id ];
	}

	private function format_event_dates( $date ) {

		try {
			$datetime = new DateTime( $date, new DateTimeZone( 'UTC' ) );
			return $datetime->format( 'Y-m-d\TH:i:sP' );
		} catch ( Exception $e ) {
			return $date;
		}
	}

	/**
	 * Get All Updated Data For Schema Markup.
	 *
	 * @param  int|WP_Post|null $post The post/event
	 *
	 * @return array
	 */
	public function get_data( $post = null ) {

		if ( is_numeric( $post ) ) {
			$post = get_post( $post );
		}

		// If we don't have a valid post object, skip to the next item
		if ( ! $post instanceof WP_Post ) {
			return false;
		}

		$data = $this->get_default_data( $post );

		if ( empty( $data ) ) {
			return false;
		}

		// Get Post Id From Object
		$post_id = key( $data );

		// Get schema Data From object
		$data = reset( $data );

		$event_start_date = get_post_meta( $post_id, 'eventer_event_start_dt', true);
        $event_end_date = get_post_meta( $post_id, 'eventer_event_end_dt', true);

        $event_end_date = ($event_end_date != '') ? $event_end_date : $event_start_date;

        $data->startDate = $this->format_event_dates( $event_start_date );
		$data->endDate   = $this->format_event_dates( $event_end_date );

		if ( $this->eventer_has_venue( $post_id ) ) {
			$venue_data     = $this->get_venue_data( $post_id );
			$data->location = $venue_data;
		}

		if ( $this->eventer_has_organizer( $post_id ) ) {
			$organizer_data  = $this->get_organizer_data( $post_id );
			$data->organizer = $organizer_data;
		}

		$data->performer = 'Organization';

		$return[ $post_id ] = $data;

		return $return;
	}

	public function get_venue_data( $post_id ) {
		$google_map_api = eventer_get_settings('google_apikey');
		$specific_address = get_post_meta( $post_id, 'eventer_event_specific_venue', true );

		$data = (object) [];

		$data->{'@type'} = 'Place';

		if ( ! empty( $specific_address ) ) {
			$data->name = $specific_address;
		}

		if ( $google_map_api ) {
			$eventer_venue = get_the_terms( $post_id, 'eventer-venue' );
			$venue_name = '';

			if ( empty( $eventer_venue ) || is_wp_error( $eventer_venue ) ) {
				return [];
			}

			foreach ( $eventer_venue as $venue ) {
				$location_address = get_term_meta( $venue->term_id, 'venue_address', true );
				$location_coordinates = get_term_meta( $venue->term_id, 'venue_coordinates', true );
				$address = '';

				if ( $location_address ) {
					$address = $this->geocode( $location_address );
				} elseif ( $location_coordinates ) {
					$address = $this->geocode( explode( ',', $location_coordinates ) );
				}

				if ( $address ) {
					$data->address = [];

					$data->address['@type'] = 'PostalAddress';
					if ( ! empty( $address['meta'] ) ) {
						$data->address['streetAddress'] = $address['meta']['street_number'];
						$data->address['addressLocality'] = $address['meta']['sublocality'];
						$data->address['addressRegion'] = $address['meta']['administrative_area_level_1'];
						$data->address['postalCode'] = $address['meta']['postal_code'];
						$data->address['addressCountry'] = $address['meta']['country'];
					}

					$data->address = (object) array_filter( $data->address );
				}
			}
		}

		return $data;
	}

	public function get_organizer_data( $post_id ) {
		$event_organizer = get_the_terms( $post_id, 'eventer-organizer' );
		$organizer_phone = get_term_meta($event_organizer[0]->term_id, 'organizer_phone', true);
		$organizer_website = get_term_meta($event_organizer[0]->term_id, 'organizer_website', true);

		$data = (object) [];

		$data->{'@type'} = 'Organization';

		$data->{'name'} = $event_organizer[0]->name;

		if ( $organizer_phone ) {
			$data->{'telephone'} = $organizer_phone;
		}

		if ( $organizer_website ) {
			$data->{'url'} = $organizer_website;
			$data->{'sameAs'} = $organizer_website;
		}

		return $data;
	}

	public function eventer_has_organizer( $post_id ) {
		$event_organizer = get_the_terms( $post_id, 'eventer-organizer' );

		if ( empty( $event_organizer ) || is_wp_error( $event_organizer ) ) {
			return false;
		}

		return true;
	}

	public function eventer_has_venue( $post_id ) {
		$eventer_venue = get_the_terms( $post_id, 'eventer-venue' );
		$specific_address = get_post_meta( $post_id, 'eventer_event_specific_venue', true );
		$venue_name = '';

		if ( empty( $eventer_venue ) || is_wp_error( $eventer_venue ) ) {
			return false;
		}

		foreach ( $eventer_venue as $venue ) {
			$location_address = get_term_meta( $venue->term_id, 'venue_address', true );
			$location_coordinates = get_term_meta( $venue->term_id, 'venue_coordinates', true );
			$address = '';

			$venue_name = $venue->name;
			if ( $location_coordinates != '' ) {
				$elocation = $location_coordinates;
			} elseif ( $location_address != '' ) {
				$elocation = $location_address;
			} else {
				$elocation = $venue->name;
			}

			$venue_name = $venue_name;
			break;
		}

		return ($venue_name != '') ? $venue_name : $specific_address;
	}

	public function geocode( $location ) {
		if ( is_array( $location ) ) {
			$latitude = $location[0] ?? null;
			$longitude = $location[1] ?? null;
			if ( ! is_numeric( $latitude ) || ! is_numeric( $longitude ) ) {
				return false;
			}
		} elseif ( ! is_string( $location ) || empty( trim( $location ) ) ) {
			return false;
		}

		$response = $this->client_geocode( $location );
		$address = $this->transform_response( $response );
		return $address;
	}

	protected function client_geocode( $location ) {
		$google_map_api = eventer_get_settings('google_apikey');

		$params = [
			'key' 		=> $google_map_api,
			'language' 	=> 'en',
		];

		if ( is_array( $location ) ) {
			$params['latlng'] = join( ',', array_map( 'floatval', $location ) );
		} else {
			$params['address'] = (string) $location;
		}

		$request = wp_remote_get( sprintf( 'https://maps.googleapis.com/maps/api/geocode/json?%s', http_build_query( $params ) ), [
			'httpversion' => '1.1',
		] );

		if ( is_wp_error( $request ) ) {
			return false;
		}

		$response = json_decode( wp_remote_retrieve_body( $request ) );

		if ( ! is_object( $response ) || $response->status !== 'OK' || empty( $response->results ) ) {
			return false;
			// throw new \Exception( sprintf(
			// 	'(%s) %s',
			// 	$response->status ?? 'REQUEST_FAILED',
			// 	$response->error_message ?? 'Geocoding request failed.'
			// ) );
		}

		return $response->results[0];
	}

	protected function transform_response( $response ) {
		$feature = [
			'latitude'  => $response->geometry->location->lat,
			'longitude' => $response->geometry->location->lng,
			'address'   => $response->formatted_address,
			'meta'      => [],
		];

		if ( ! empty( $response->address_components ) ) {
			foreach ( $response->address_components as $component ) {
				if ( empty( $component->types ) ) {
					continue;
				}

				foreach ( $component->types as $component_type ) {
					$feature['meta'][ $component_type ] = $component->long_name;
				}
			}
		}

		return $feature;
	}

	public function get_default_data( $post ) {
		if ( ! $post->ID ) {
			return false;
		}

		// This Stop when the page has already schema markup.
		if ( $this->exists( $post->ID ) ) {
			return false;
		}

		$data = (object) [];

		$data->{'@context'} = 'http://schema.org';

		$data->{'@type'} = $this->type;

		$data->name        = esc_js( get_the_title( $post ) );
		$data->description = esc_js( $this->has_post_excerpt( $post ) );

		if ( has_post_thumbnail( $post ) ) {
			$data->image = wp_get_attachment_url( get_post_thumbnail_id( $post ) );
		}

		$data->url = esc_url_raw( $this->get_link( $post ) );

		$type = strtolower( esc_attr( $this->type ) );

		return [ $post->ID => $data ];
	}

	protected function get_link( $post ) {
		return get_the_permalink( $post );
	}

	function has_post_excerpt( $post ) {
		if ( ! is_numeric( $post ) && ! $post instanceof WP_Post ) {
			$post = get_the_ID();
		}

		if ( is_numeric( $post ) ) {
			$post = WP_Post::get_instance( $post );
		}

		if ( ! $post instanceof WP_Post ) {
			return null;
		}

		$excerpt = has_excerpt( $post->ID )
			? $post->post_excerpt
			: wp_trim_words( $post->post_content );

		return wpautop( $excerpt );
	}

	public function exists( $post ) {
	    if ( is_object( $post ) && isset( $post->ID ) ) {
	        return isset( self::$_posts[ $post->ID ] );
	    } else {
	        // Handle the case where $post is not an object or doesn't have an "ID" property.
	        return false; // or perform an alternative action as needed
	    }
	}
}

Eventer_Schema::instance();
