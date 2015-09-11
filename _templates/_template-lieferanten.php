<?php
/**
 * Template Name: Lieferanten
 */

$context         = Timber::get_context();
$post            = new TimberPost();
$context['post'] = $post;

$context['suppliers'] = Timber::get_posts( [
	'post_type' => 'supplier'
] );

Timber::render( array( 'page-lieferanten.twig', 'page.twig' ), $context );