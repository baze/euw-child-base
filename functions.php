<?php

if ( ! class_exists( 'Timber' ) ) {
	add_action( 'admin_notices', function () {
		echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . admin_url( 'plugins.php#timber' ) . '">' . admin_url( 'plugins.php' ) . '</a></p></div>';
	} );

	return;
}

// parent theme
define( 'TEMPLATE_DIRECTORY', get_template_directory() );

// child theme
define( 'STYLESHEET_DIRECTORY', get_stylesheet_directory() );

if ( ! file_exists( TEMPLATE_DIRECTORY . '/functions.php')) {
	die("parent theme not found");
}

require_once( TEMPLATE_DIRECTORY . '/functions.php' );

class ChildSite extends ParentSite {

	public function __construct( $options = array() ) {
		parent::__construct($options);

//		add_filter( 'wpcf7_before_send_mail', array( $this, 'my_wpcf7_mod' ) );
	}

	public function add_to_context( $context ) {

		$context = parent::add_to_context( $context );

		$context['highlights'] = $context['options']['global_dishes'];

		return $context;

	}

	function my_wpcf7_mod( $contact_form ) {

		$submission = WPCF7_Submission::get_instance();

		if ( $submission ) {
			$mail = $contact_form->prop( 'mail' );

//			$mail['body'] .= '<p>An extra paragraph at the end!</p>';
			$mail['recipient'] .= ',bjoern.martensen@gmail.com';

			$contact_form->set_properties( array( 'mail' => $mail ) );
		}
	}

}

$site = new ChildSite();

$site->creationDate->setDate( 2015, 8, 1 );
$site->analyticsProfile = null;