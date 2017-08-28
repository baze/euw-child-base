<?php
/**
 * Template Name: Standorte
 */

$context         = Timber::get_context();
$post            = new LocationPost();
$context['post'] = $post;

if ( function_exists( 'wpcf7_enqueue_scripts' ) ) {
	wpcf7_enqueue_scripts();
}

$map                            = getMap();
$map->options->draggable        = true;
$map->options->scrollwheel      = false;
$map->options->initialOpenInfo      = false;
$map->pois                      = getMapLocations( [ 'ansprechpartner' ] );
$context['map_planungsberater'] = $map;

$ansprechpartner            = Timber::get_posts( [
	'post_type' => 'ansprechpartner',
	'order_by'  => 'menu_order',
	'order'     => 'DESC'
] );
$context['ansprechpartner'] = $ansprechpartner;

$context['standorte'] = Timber::get_terms( 'standort' );

Timber::render( array( 'page-standorte.twig', 'page.twig' ), $context );

