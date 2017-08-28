<?php
/**
 * Template Name: Stellenangebote
 */

$context         = Timber::get_context();
$post            = new TimberPost();
$context['post'] = $post;

$posts            = Timber::get_posts( [
	'post_type' => 'stellenangebot',
	'order_by'  => 'menu_order',
	'order'     => 'DESC'
] );
$context['posts'] = $posts;

$context['categories'] = Timber::get_terms( 'category_stellenangebot', [
	'hide_empty' => true
] );

Timber::render( array( 'page-jobs.twig', 'page.twig' ), $context );

