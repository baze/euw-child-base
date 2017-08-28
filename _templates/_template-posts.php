<?php
/**
 * Template Name: Beitragsübersicht
 */

global $paged;
if ( ! isset( $paged ) || ! $paged ) {
	$paged = 1;
}

$context         = Timber::get_context();
$post            = new TimberPost();
$context['post'] = $post;

if ($posttype = $post->get_field('posttype')) {

	$args = [
		'post_type' => $posttype,
		'order_by'  => 'menu_order',
		'order'     => 'ASC',
		'paged'     => $paged
	];

	if ($posttype == 'baustelle') {
		$args['orderby'] = 'title';
	}

	/* THIS LINE IS CRUCIAL */
	/* in order for WordPress to know what to paginate */
	/* your args have to be the defualt query */
	query_posts( $args );
	/* make sure you've got query_posts in your .php file */
	$context['posts']      = Timber::get_posts();
	$context['pagination'] = Timber::get_pagination();

}

Timber::render( array( 'home.twig', 'page.twig' ), $context );

