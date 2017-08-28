<?php

function cptui_register_my_cpts_stellenangebot() {

	/**
	 * Post Type: Stellenangebote.
	 */

	$labels = array(
		"name" => __( 'Stellenangebote', 'euw-child' ),
		"singular_name" => __( 'Stellenangebot', 'euw-child' ),
	);

	$args = array(
		"label" => __( 'Stellenangebote', 'euw-child' ),
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
		"rewrite" => array( "slug" => "stellenangebot", "with_front" => true ),
		"query_var" => true,
		"supports" => array( "title", "revisions" ),
	);

	register_post_type( "stellenangebot", $args );
}

add_action( 'init', 'cptui_register_my_cpts_stellenangebot' );

function cptui_register_my_taxes_category_stellenangebot() {

	/**
	 * Taxonomy: Stellenangebot-Kategorien.
	 */

	$labels = array(
		"name" => __( 'Stellenangebot-Kategorien', 'euw-child' ),
		"singular_name" => __( 'Stellenangebot-Kategorie', 'euw-child' ),
	);

	$args = array(
		"label" => __( 'Stellenangebot-Kategorien', 'euw-child' ),
		"labels" => $labels,
		"public" => true,
		"hierarchical" => true,
		"label" => "Stellenangebot-Kategorien",
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => array( 'slug' => 'category_stellenangebot', 'with_front' => true, ),
		"show_admin_column" => false,
		"show_in_rest" => false,
		"rest_base" => "",
		"show_in_quick_edit" => false,
	);
	register_taxonomy( "category_stellenangebot", array( "stellenangebot" ), $args );
}

add_action( 'init', 'cptui_register_my_taxes_category_stellenangebot' );


