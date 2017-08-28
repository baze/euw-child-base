<?php

function cptui_register_my_cpts_mitarbeiter() {

	/**
	 * Post Type: Mitarbeiter.
	 */

	$labels = array(
		"name" => __( 'Mitarbeiter', 'euw-child' ),
		"singular_name" => __( 'Mitarbeiter', 'euw-child' ),
	);

	$args = array(
		"label" => __( 'Mitarbeiter', 'euw-child' ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => false,
		"rest_base" => "",
		"has_archive" => false,
		"show_in_menu" => true,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => array( "slug" => "team", "with_front" => true ),
		"query_var" => true,
		"supports" => array( "title", "editor", "thumbnail", "revisions" ),
	);

	register_post_type( "mitarbeiter", $args );
}

add_action( 'init', 'cptui_register_my_cpts_mitarbeiter' );
