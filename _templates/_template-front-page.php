<?php
/**
 * Template Name: Startseite
 */

$context         = Timber::get_context();
$post            = new TimberPost();
$context['post'] = $post;

add_action( 'wp_print_scripts', 'aa_deregister_javascript', 100 );
function aa_deregister_javascript() {
	wp_deregister_script( 'contact-form-7' );
}

add_action( 'wp_print_styles', 'aa_deregister_styles', 100 );
function aa_deregister_styles() {
	wp_deregister_style( 'contact-form-7' );
}

add_action( 'wp_enqueue_scripts', 'my_deregister_scripts' );
function my_deregister_scripts() {
	wp_deregister_script( 'responsive-lightbox' );
	wp_deregister_script( 'responsive-lightbox-swipebox' );
	wp_deregister_style( 'responsive-lightbox-swipebox' );
}

if ( $post->get_field( 'posts_limit' ) != 0) {
	$posts_limit = $post->get_field( 'posts_limit' ) ?: 3;

	$posts            = Timber::get_posts( [
		'post_type'        => 'post',
		'order_by'         => 'date',
		'order'            => 'DESC',
		'numberposts'      => $posts_limit + 1,
		'category__not_in' => []
	] );
	$context['posts'] = $posts;

	if ( count( $posts ) > $posts_limit ) {
		array_pop( $context['posts'] );
	}

	$context['show_posts_link'] = count( $posts ) > $posts_limit;
} else {
	$context['posts'] = null;
}



Timber::render( array( 'front-page.twig', 'home.twig', 'page.twig' ), $context );