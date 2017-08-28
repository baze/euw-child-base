<?php

function cptui_register_my_cpts_veranstaltung() {

	/**
	 * Post Type: Veranstaltungen.
	 */

	$labels = array(
		"name"               => __( 'Veranstaltungen', 'euw-child-sul' ),
		"singular_name"      => __( 'Veranstaltung', 'euw-child-sul' ),
		"menu_name"          => __( 'Veranstaltungen', 'euw-child-sul' ),
		"add_new"            => __( 'Neu', 'euw-child-sul' ),
		"add_new_item"       => __( 'Neue Veranstaltung', 'euw-child-sul' ),
		"edit_item"          => __( 'Veranstaltung Bearbeiten', 'euw-child-sul' ),
		"new_item"           => __( 'Neue Veranstaltung', 'euw-child-sul' ),
		"view_item"          => __( 'Veranstaltung ansehen', 'euw-child-sul' ),
		"search_items"       => __( 'Veranstaltungen Durchsuchen', 'euw-child-sul' ),
		"not_found"          => __( 'Keine Veranstaltungen gefunden', 'euw-child-sul' ),
		"not_found_in_trash" => __( 'Keine Veranstaltungen im Papierkorb gefunden', 'euw-child-sul' ),
		"parent_item_colon"  => __( 'Übergeordnete Veranstaltung', 'euw-child-sul' ),
		"parent_item_colon"  => __( 'Übergeordnete Veranstaltung', 'euw-child-sul' ),
	);

	$args = array(
		"label"               => __( 'Veranstaltungen', 'euw-child-sul' ),
		"labels"              => $labels,
		"description"         => "",
		"public"              => true,
		"publicly_queryable"  => true,
		"show_ui"             => true,
		"show_in_rest"        => false,
		"rest_base"           => "",
		"has_archive"         => false,
		"show_in_menu"        => true,
		"exclude_from_search" => false,
		"capability_type"     => "post",
		"map_meta_cap"        => true,
		"hierarchical"        => false,
		"rewrite"             => array( "slug" => "veranstaltung", "with_front" => true ),
		"query_var"           => true,
		"supports"            => array(
			"title",
			"editor",
			"thumbnail",
			"excerpt",
			"revisions",
			"author",
			"page-attributes"
		),
	);

	register_post_type( "veranstaltung", $args );
}

add_action( 'init', 'cptui_register_my_cpts_veranstaltung' );

function cptui_register_my_taxes_category_event() {

	/**
	 * Taxonomy: Veranstaltungs-Kategorien.
	 */

	$labels = array(
		"name"          => __( 'Veranstaltungs-Kategorien', 'euw-child-sul' ),
		"singular_name" => __( 'Veranstaltungs-Kategorie', 'euw-child-sul' ),
	);

	$args = array(
		"label"              => __( 'Veranstaltungs-Kategorien', 'euw-child-sul' ),
		"labels"             => $labels,
		"public"             => true,
		"hierarchical"       => true,
		"label"              => "Veranstaltungs-Kategorien",
		"show_ui"            => true,
		"show_in_menu"       => true,
		"show_in_nav_menus"  => true,
		"query_var"          => true,
		"rewrite"            => array( 'slug' => 'category_event', 'with_front' => true, ),
		"show_admin_column"  => false,
		"show_in_rest"       => false,
		"rest_base"          => "",
		"show_in_quick_edit" => false,
	);
	register_taxonomy( "category_event", array( "veranstaltung" ), $args );
}

add_action( 'init', 'cptui_register_my_taxes_category_event' );


