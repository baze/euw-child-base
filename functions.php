<?php

use Carbon\Carbon;

if ( ! class_exists( 'Timber' ) ) {
	add_action( 'admin_notices', function () {
		echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . admin_url( 'plugins.php#timber' ) . '">' . admin_url( 'plugins.php' ) . '</a></p></div>';
	} );

	return;
}

// parent theme
if ( ! defined( 'TEMPLATE_DIRECTORY' ) ) {
	define( 'TEMPLATE_DIRECTORY', get_template_directory() );
}

// child theme
if ( ! defined( 'STYLESHEET_DIRECTORY' ) ) {
	define( 'STYLESHEET_DIRECTORY', get_stylesheet_directory() );
}


if ( ! file_exists( TEMPLATE_DIRECTORY . '/functions.php' ) ) {
	die( "parent theme not found" );
}

require_once( TEMPLATE_DIRECTORY . '/functions.php' );

class ChildSite extends ParentSite {

	public function __construct( $site_name_or_id = null ) {
		parent::__construct( $site_name_or_id );

		add_image_size( 'header-home', 1100, 257, true );
		add_image_size( 'header', 1200, 320, true );
		add_image_size( 'slider', 1280, 515, true );
		add_image_size( 'tab-image', 550, 400, true );
		add_image_size( 'gallery-1', 1100, 440, true );
		add_image_size( 'gallery-2', 540, 300, true );
		add_image_size( 'gallery-3', 360, 360, true );
		add_image_size( 'gallery-4', 265, 265, true );

		add_action( 'init', array( $this, 'allowEditorsToEditMenuStructure' ) );
		add_action( 'init', array( $this, 'allowEditorsToEditOptions' ) );
		add_action( 'init', array( $this, 'disable_wp_emojicons' ) );
		add_action( 'init', array( $this, 'add_shortcodes' ) );
		add_action( 'init', array( $this, 'register_custom_fields' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'my_deregister_scripts' ) );
//		add_action( 'wp_enqueue_scripts', array( $this, 'my_deregister_styles' ) );

		add_filter( 'wpcf7_before_send_mail', array( $this, 'my_wpcf7_mod' ) );
//		add_filter( 'acf/update_value/name=related_posts', array( $this, 'bidirectional_acf_update_value'), 10, 3 );

		add_action( 'wpcf7_init', array( $this, 'wpcf7_add_shortcode_event_dates' ) );
		add_action( 'wpcf7_init', array( $this, 'wpcf7_add_shortcode_personalisierung_absender' ) );

		add_filter( 'posts_where', array( $this, 'allow_wildcards' ) );
	}

	public function add_to_context( $context ) {

		$context = parent::add_to_context( $context );

		if ( is_user_logged_in() ) {
			$user = wp_get_current_user();

			if ( $user->ID == 1 ) {
				$context['debug'] = true;
			}
		}

		/*$context['async_styles'] = [
			"/responsive-lightbox/assets/swipebox/css/swipebox.min.css",
			"/advanced-responsive-video-embedder/public/arve-public.css"
		];*/

		if ( function_exists( 'get_fields' ) ) {
			$context['options'] = get_fields( 'option' );
		}

		if ( is_array( $context['options'] ) && array_key_exists( 'fallback_images', $context['options'] ) && is_array(
				$context['options']['fallback_images'] )
		) {
			foreach ( $context['options']['fallback_images'] as $fb_image ) {
				$context[ 'fallback_image_' . $fb_image['post_type'] ] = $fb_image['image'];
			}
		}

		$events = $this->getUpcomingEvents( null, 3, [ 12 ], true);

		/*$context['global_posts'] = Timber::get_posts( [
			'post_type'   => 'stellenangebot',
			'order_by'    => 'date',
			'order'       => 'DESC',
			'numberposts' => 3,
		] );*/

		$context['global_posts'] = $events;

		$context['stylesheet'] = [
			'uri' => get_stylesheet_directory_uri()
		];

		$post = new TimberPost();

		$topmost_parent = get_topmost_parent( $post );

		$context['subpages']               = $topmost_parent->get_field( 'subpages' );
		$context['subpages_link']          = $topmost_parent->link;
		$context['subpages_logo']          = $topmost_parent->get_field( 'subpages_logo' );
		$context['subpages_cta_link']      = $topmost_parent->get_field( 'subpages_cta_link' );
		$context['subpages_cta_link_text'] = $topmost_parent->get_field( 'subpages_cta_link_text' );
		$context['trust_elements_items']   = $topmost_parent->get_field( 'trust_elements_items' );

		$context['theme_color'] = get_theme_color( $post );

		return $context;
	}

	function add_to_twig( $twig ) {

		$twig = parent::add_to_twig( $twig );

		$twig->addFilter( 'the_content', new Twig_Filter_Function( function ( $text ) {

			$res = apply_filters( 'the_content', $text );

			return $res;
		} ) );

		$twig->addFilter( 'excerptChars', new Twig_Filter_Function( function ( $text, $count = 140 ) {

			$res = substr( $text, 0, $count );

			if ( strlen( $text ) > $count ) {
				$res .= '...';
			}

			return $res;
		} ) );

		$twig->addFunction( new Twig_SimpleFunction( 'date_diff', function ( $start, $end ) {

			$start_date = new Carbon( $start );
			$end_date   = new Carbon( $end );

			return $start_date->diffInDays( $end_date );
		} ) );

		$twig->addFunction( new Twig_SimpleFunction( 'map', function ( $adr_title = null, $adr_arr = [] ) {


			if ( empty( $adr_title ) ) {
				$adr_title = get_field( 'firmenbezeichnung', 'option' );
			}

			if ( empty( $adr_arr ) ) {
				$adr_arr = [
					get_field( 'strasse_hausnummer', 'option' ),
					get_field( 'postleitzahl', 'option' ),
					get_field( 'ort', 'option' ),
					get_field( 'bundesland', 'option' ),
					get_field( 'land', 'option' )
				];
			}

			$adr_string = implode( ' ', $adr_arr );
			$adr_string = trim( $adr_string );

			if ( $adr_string ) {
				$map = new \Mappress_Map();

				$mypoi = new \Mappress_Poi( [
					"title"   => $adr_title,
					"address" => $adr_string,
					"body"    => $adr_string
				] );

				if ( ! empty( $coordinates ) ) {
					$mypoi->point = array(
						"lat" => $coordinates['latitude'],
						"lng" => $coordinates['longitude']
					);
				}

				$mypoi->geocode();
				$map->pois = array( $mypoi );

				$map->width                    = '100%';
				$map->height                   = 480;
				$map->options->adaptive        = true;
				$map->options->alignment       = 'default';
				$map->options->border['style'] = '';
				$map->options->directions      = 'google'; //'none';
				$map->options->initialOpenInfo = true;
				$map->options->onLoad          = true;
				$map->options->postTypes       = array();
				$map->options->noCSS           = true;

				return $map;
			}

			return null;
		} ) );

		$twig->addFunction( new Twig_SimpleFunction( 'show_subpages', function ( $post ) {

			$parents           = get_post_ancestors( $post->ID );
			$topmost_parent_id = ( $parents ) ? $parents[ count( $parents ) - 1 ] : $post->ID;

			$subpages = wp_list_pages( [
				'child_of'    => $topmost_parent_id,
				'echo'        => false,
				'title_li'    => null,
				'sort_column' => 'post_title'
			] );

			return $subpages;
		} ) );

		$twig->addFunction( new Twig_SimpleFunction( 'upcoming_events', function (
			$category = null, $limit = - 1,
			$exclude = [], $date_required = false
		) {

			$events = $this->getUpcomingEvents( $category, $limit, $exclude, $date_required );

			return $events;
		} ) );

		$twig->addFunction( new Twig_SimpleFunction( 'get_posts', function ( $limit = 10, $posttype = 'post' ) {
			$posts = Timber::get_posts( [
				'post_type'   => $posttype,
				'order_by'    => 'date',
				'order'       => 'DESC',
				'numberposts' => $limit,
			] );

			return $posts;
		} ) );

		return $twig;
	}

	function register_strings() {
		parent::register_strings();

		if ( function_exists( 'pll_register_string' ) ) {
			$this->register_string( "Kategorien" );
			$this->register_string( "Schlagworte" );
			$this->register_string( "Verwandte Leistungen" );
			$this->register_string( "Beschreibung" );
			$this->register_string( "Es wurden keine passenden Inhalte gefunden." );
			$this->register_string( "Dauer:" );
			$this->register_string( "Ort:" );
			$this->register_string( "Gebühr:" );
			$this->register_string( "Anmelden" );
		}
	}

	function my_deregister_scripts() {
		wp_deregister_script( 'wp-embed' );
//		wp_deregister_script( 'responsive-lightbox' );
//		wp_deregister_script( 'responsive-lightbox-swipebox' );

		wp_deregister_script( 'jquery' );
		wp_register_script( 'jquery', "//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js", false, null, true );
		wp_enqueue_script( 'jquery' );

		wp_deregister_style( 'basecss' );
		wp_deregister_style( 'advanced-responsive-video-embedder' );
//		wp_deregister_style( 'responsive-lightbox-swipebox' );

		if ( function_exists( 'wpcf7_enqueue_scripts' ) ) {
//			wpcf7_enqueue_scripts();
		}

		if ( function_exists( 'wpcf7_enqueue_styles' ) ) {
			wpcf7_enqueue_styles();
		}
	}

	function my_wpcf7_mod( $contact_form ) {

		$submission = WPCF7_Submission::get_instance();

		if ( $submission ) {
			$mail   = $contact_form->prop( 'mail' );
			$mail_2 = $contact_form->prop( 'mail_2' );

			$data = $submission->get_posted_data();

			$url = $submission->get_meta( 'url' );

			// Super nice wordpress function
			$postid = url_to_postid( $url );
			$post   = get_post( $postid );

			// for personalization
			$key = 'countries';
			if ( key_exists( $key, $data ) && ! empty( $data[ $key ] ) ) {

				// for personalized forms
				$needle            = null;
				$key               = null;
				$mitarbeiter_found = false;

				// determine correct needle
				$key = 'countries';
				if ( key_exists( $key, $data ) && ! empty( $data[ $key ] ) ) {

					switch ( $data[ $key ] ) {

						case 'Deutschland' :

							$key = 'postal_codes';
							if ( key_exists( $key, $data ) && ! empty( $data[ $key ] ) ) {
								$needle = $data[ $key ];
							}

							break;

						default:
							$needle = $data[ $key ];

							break;
					}

				}

				if ( $needle ) {
					$mitarbeiter_found = $this->determineRecipient( $needle, $key );
				}

				$fallback_person      = new TimberPost( get_field( 'jurisdiction_fallback_person', 'option' ) );
				$fallback_always_send = get_field( 'jurisdiction_fallback_always_send', 'option' );


				// if found, get mitarbeiter's info.
				if ( $mitarbeiter_found ) {

					// add mitarbeiter to recipients of mail_1
					$email = $mitarbeiter_found->get_field( 'email' );

					if ( $email ) {
						$mail['recipient'] .= ',' . $email;
						$contact_form->set_properties( array( 'mail' => $mail ) );
					}

					if ( $mail_2['active'] ) {
						// compile timber view file with mitarbeiter's data in order to personalize
						$html = Timber::compile( '_mails/_mail--signature.twig', [
							'person' =>
								$mitarbeiter_found
						] );

						$mail_2['body'] = str_replace( '[personalisierung_absender]', $html, $mail_2['body'] );

						$contact_form->set_properties( array( 'mail_2' => $mail_2 ) );

						// send to fallback person, if desired
						if ( $fallback_person && $fallback_always_send ) {
							$fallback_email = $fallback_person->get_field( 'email' );

							if ( $fallback_email && ( $fallback_email != $email ) ) {
								$mail['recipient'] .= ',' . $fallback_email;
								$contact_form->set_properties( array( 'mail' => $mail ) );
							}
						}

					}
				} else {

					// send to fallback person
					if ( $fallback_person ) {
						$mitarbeiter_email = $fallback_person->get_field( 'email' );

						if ( $mitarbeiter_email ) {
							$mail['recipient'] .= ',' . $mitarbeiter_email;
							$contact_form->set_properties( array( 'mail' => $mail ) );
						}
					}

				}

			}

			// for event forms
			if ( $post->post_type == 'veranstaltung' ) {
				$mail['subject'] .= 'Veranstaltung: "' . $post->post_title . '""';
				$mail['body']    = $post->post_title . "\n" . $url . ' ' . "\n\n" . $mail['body'];
			}

			// for support forms
			if ( key_exists( 'hidden-contact_person', $data ) && $data['hidden-contact_person'] ) {
				$mitarbeiter_id = $data['hidden-contact_person'];

				$support_mitarbeiter = new TimberPost( $mitarbeiter_id );

				if ( $support_mitarbeiter ) {

					$mitarbeiter_email = $support_mitarbeiter->get_field( 'email' );

					if ( $mitarbeiter_email ) {

						$mail['body'] = $post->post_title . ' ' . $url . ' ' . "\n" . $mail['body'];

//					$mail['recipient'] .= ',' . 'digital@euw.de';
//					$mail['recipient'] .= ',' . $mitarbeiter_email;

						if ( function_exists( 'get_fields' ) ) {
							$recipient = get_field( 'e-mail', 'option' );

							if ( $mitarbeiter_email != $recipient ) {
								$mail['recipient'] .= ',' . $recipient;
							}
						}

						$contact_form->set_properties( array( 'mail' => $mail ) );
					}

				}
			}
		}

		// abort sending mail
//		add_filter( 'wpcf7_skip_mail', '__return_true' );
//		die( "don't send" );
	}

	function determineRecipient( $needle, $key ) {
		$mitarbeiter_found = false;

		$jurisdiction_entries = get_field( 'jurisdiction_entries', 'option' );

		if ( $jurisdiction_entries && $needle ) {

			foreach ( $jurisdiction_entries as $entry ) {

				if ( ! $mitarbeiter_found ) {
					// get mitarbeiter's postal codes
					$haystackRaw = $entry[ $key ];
					$haystack    = [];

					// prepare haystack
					if ( is_array( $haystackRaw ) ) {

						foreach ( $haystackRaw as $item ) {

							switch ( count( $item ) ) {

								case 1 :
									$haystack[] = $item['name'];
									break;

								// calculate postal codes on the fly
								case 2 :
									for ( $i = $item['from']; $i <= $item['to']; $i ++ ) {

										// handle eastern german postal codes '01000' etc.
										$val = (string) $i;

										switch ( strlen( $val ) ) {
											case 1 :
												$val = '0000' . $val;
												break;

											case 2 :
												$val = '000' . $val;
												break;

											case 3 :
												$val = '00' . $val;
												break;

											case 4 :
												$val = '0' . $val;
												break;

											default:
												break;
										}

										$haystack[] = $val;
									}
									break;

								default:
									break;
							}

						}

					} elseif ( is_string( $haystackRaw ) ) {
						$haystack = explode( "\n", str_replace( "\r", "", $haystackRaw ) );
					}

					// check if submitted needle is in mitarbeiter's data
					if ( in_array( $needle, $haystack ) ) {
						$mitarbeiter_found = $entry;
					}
				}
			}

			if ( $mitarbeiter_found ) {
				return new TimberPost( $mitarbeiter_found['person'] );
			}
		}

		return $mitarbeiter_found;
	}

	function wpse_form_response_output( $output, $class, $content, $object ) {

		/*$submission = WPCF7_Submission::get_instance();
		$data = $submission->get_posted_data();

		$key = 'jurisdiction_countries';
		if ( $data[ $key ] == 'Deutschland' ) {
			$key = 'jurisdiction_postal_codes';

			if ( key_exists( $key, $data ) && ! empty( $data[ $key ] ) ) {
				dd("has plz");
			} else {

				return sprintf(
					'<div class="wpcf7-response-output wpcf7-display-none wpcf7-validation-errors" 
            role="alert" style="display: block;">%s</div>',
					__( 'Postleitzahl nicht angegeben' )
				);
			}

		}*/

		return sprintf(
			'<div class="wpcf7-response-output wpcf7-display-none wpcf7-validation-errors" 
            role="alert" style="display: block;">%s</div>',
			__( 'SOAP ERROR - Mail not sent!' )
		);
	}

	function bidirectional_acf_update_value( $value, $post_id, $field ) {

		// vars
		$field_name  = $field['name'];
		$global_name = 'is_updating_' . $field_name;


		// bail early if this filter was triggered from the update_field() function called within the loop below
		// - this prevents an inifinte loop
		if ( ! empty( $GLOBALS[ $global_name ] ) ) {
			return $value;
		}


		// set global variable to avoid inifite loop
		// - could also remove_filter() then add_filter() again, but this is simpler
		$GLOBALS[ $global_name ] = 1;


		// loop over selected posts and add this $post_id
		if ( is_array( $value ) ) {

			foreach ( $value as $post_id2 ) {

				// load existing related posts
				$value2 = get_field( $field_name, $post_id2, false );


				// allow for selected posts to not contain a value
				if ( empty( $value2 ) ) {

					$value2 = array();

				}


				// bail early if the current $post_id is already found in selected post's $value2
				if ( in_array( $post_id, $value2 ) ) {
					continue;
				}


				// append the current $post_id to the selected post's 'related_posts' value
				$value2[] = $post_id;


				// update the selected post's value
				update_field( $field_name, $value2, $post_id2 );

			}

		}


		// find posts which have been removed
		$old_value = get_field( $field_name, $post_id, false );

		if ( is_array( $old_value ) ) {

			foreach ( $old_value as $post_id2 ) {

				// bail early if this value has not been removed
				if ( is_array( $value ) && in_array( $post_id2, $value ) ) {
					continue;
				}


				// load existing related posts
				$value2 = get_field( $field_name, $post_id2, false );


				// bail early if no value
				if ( empty( $value2 ) ) {
					continue;
				}


				// find the position of $post_id within $value2 so we can remove it
				$pos = array_search( $post_id, $value2 );


				// remove
				unset( $value2[ $pos ] );


				// update the un-selected post's value
				update_field( $field_name, $value2, $post_id2 );

			}

		}


		// reset global varibale to allow this filter to function as per normal
		$GLOBALS[ $global_name ] = 0;


		// return
		return $value;

	}

	function really_block_users() {
		$isAjax = ( defined( 'DOING_AJAX' ) && true === DOING_AJAX ) ? true : false;

		if ( ! $isAjax ) {
			if ( is_admin() && ! current_user_can( 'publish_posts' ) ) {
				wp_redirect( home_url() );
				exit;
			}
		}
	}

	function add_shortcodes() {
		parent::add_shortcodes();

		function userquote_fn( $atts ) {
			if ( isset( $atts['user'] ) && isset( $atts['quote'] ) ) {
				$user  = get_page_by_path( sanitize_text_field( $atts['user'] ), OBJECT, 'mitarbeiter' );
				$quote = sanitize_text_field( $atts['quote'] );
			} else {
				$user  = false;
				$quote = false;
			}

			// this time we use Timber::compile since shorttags should return the code
			return Timber::compile( '_snippets/_snippet-employee-quote.twig', array(
				'person' => new TimberPost(
					$user ),
				'quote'  => $quote
			) );
		}

		add_shortcode( 'userquote', 'userquote_fn' );

	}

	function disable_wp_emojicons() {

		// all actions related to emojis
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );

		// filter to remove TinyMCE emojis
//		add_filter( 'tiny_mce_plugins', 'disable_emojicons_tinymce' );
	}

	function wpcf7_add_shortcode_event_dates() {
		wpcf7_add_form_tag( 'event_dates', array( $this, 'wpcf7_add_shortcode_event_dates_handler' ) );
	}

	function wpcf7_add_shortcode_event_dates_handler() {
		global $post;
		$post             = new EventPost( $post );
		$context['dates'] = $post->upcomingDates();

		$html = Timber::compile( array( '_form-tags/_form-tag--event_dates.twig', 'blank.twig' ), $context );

		return $html;
	}

	function allow_wildcards( $where ) {

		$where = str_replace( "meta_key = 'dates_%", "meta_key LIKE 'dates_%", $where );

		if ( strpos( $where, 'dates_0' ) !== false ) {
//			echo "<pre>";
//			dd( $where );
		}

		return $where;
	}

	function getUpcomingEvents( $category = null, $limit = - 1, $exclude = [], $date_required = false ) {

		$nowDate = Carbon::now();

		$args = [
			'post_type' => 'veranstaltung',
//			'showposts' => $limit,
			'showposts' => -1,

			'meta_query' => array(
			),

			'orderby'      => 'meta_value_num',
			'order'        => 'ASC',
			'tax_query'    => []
		];

		if ($date_required) {
			$args['meta_query'][] = array(
				'key'     => 'dates_%_event-start',
				'value'   => '',
				'compare' => '!=',
			);
		} else {
			$args['meta_query']['relation'] = 'OR';

			$args['meta_query'][] = array(
				'key'     => 'dates_%_event-start',
				'value'   => $nowDate->format( 'Ymd' ),
				'type'    => 'NUMERIC',
				'compare' => '>=',
			);

			$args['meta_query'][] = array(
				'key'     => 'dates_%_event-start',
				'compare' => 'NOT EXISTS',
			);
		}

//		echo "<pre>";
//		dd($args['meta_query']);

		if ( $category ) {
			$category = new TimberTerm( $category );

			$args['tax_query'][] = [
				'taxonomy' => $category->taxonomy,
				'field'    => 'term_id',
				'terms'    => $category->term_id
			];

		}

		if ( $exclude ) {
			$categories = [];

			foreach ( $exclude as $cat ) {
				$category = new TimberTerm( $cat );

				$categories[] = $category->term_id;
			}

			$args['tax_query'][] = [
				'taxonomy' => $category->taxonomy,
				'field'    => 'term_id',
				'terms'    => $categories,
				'operator' => 'NOT IN',
			];

		}

		$events = Timber::get_posts( $args, 'EventPost' );

		/*
		 * calculate objects from array of dates in order to display all upcoming dates for each event
		 */
		$calculatedEvents = [];
//		echo "<pre>";
		foreach ( $events as $event ) {
//			var_dump($event->id);
			$calculatedEvents = array_merge( $calculatedEvents, $event->calculateEventsFromDates() );
		}

		/*
		 * sort the calculated events by start date
		 */
		usort( $calculatedEvents, function ( $item1, $item2 ) {
			if ( $item1->nextDate['event-start'] == $item2->nextDate['event-start'] ) {
				return 0;
			}

			return $item1->nextDate['event-start'] < $item2->nextDate['event-start'] ? - 1 : 1;
		} );

		setlocale( LC_TIME, 'de_DE.UTF-8', 'German_Germany', 'German' );

		return $calculatedEvents;
	}

}

$site = new ChildSite();
$site->creationDate->setDate( 2017, 5, 3 );

$custom_files = [

//	'acf-_linked-object.php',
//	'acf-flexible-content.php',
	'acf-historie.php',

	'cpt-mitarbeiter.php',
//	'acf-mitarbeiter.php',

	'cpt-stellenangebot.php',
	'acf-stellenangebot.php',

	'cpt-veranstaltung.php',
//	'acf-veranstaltung.php'
];

foreach ( $custom_files as $file ) {

	$path = STYLESHEET_DIRECTORY . '/_inc/' . $file;

	if ( file_exists( $path ) ) {
		require_once( $path );
	}
}

if ( ! function_exists( 'get_topmost_parent' ) ) {
	function get_topmost_parent( $post ) {
		$parents           = get_post_ancestors( $post->ID );
		$topmost_parent_id = ( $parents ) ? $parents[ count( $parents ) - 1 ] : $post->ID;
		$topmost_parent    = new TimberPost( $topmost_parent_id );

		return $topmost_parent;
	}
}
/*
 * checks if the current post has a specific colors set.
 * if not, use the topmost parent's color (if set).
 * if no color has been set, null is returned.
 */
if ( ! function_exists( 'get_theme_color' ) ) {
	function get_theme_color( $post ) {


		if ( $post->get_field( 'color_scheme' )) {
			$color_scheme = $post->get_field( 'color_scheme' );
		} else {
			$topmost_parent = get_topmost_parent( $post );
			$color_scheme = $topmost_parent->get_field( 'color_scheme' );
		}

		$cpt_color_scheme = new TimberPost( $color_scheme );
		$colors           = $cpt_color_scheme->get_field( 'colors' )[0];

		if ( $colors) {
			return $colors['primary'];
		}

		return null;

	}
}