<?php
/**
 * The Template for displaying all single posts
 *
 * Methods for TimberHelper can be found in the /functions sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$context         = Timber::get_context();
$post            = Timber::query_post();
$context['post'] = $post;
//$context['wp_title'] .= ' - ' . $post->title();

$post->show_sidebar = true;

$context['lastPosts'] = Timber::get_posts( [
	'post_type'   => 'post',
	'order_by'    => 'date',
	'order'       => 'DESC',
	'numberposts' => 10,
	'category__not_in' => [ 5 ]
] );

if ( post_password_required( $post->ID ) ) {
	Timber::render( 'single-password.twig', $context );
} else {
	Timber::render( array(
		'single-' . $post->ID . '.twig',
		'single-' . $post->post_type . '.twig',
		'single.twig'
	), $context );
}


