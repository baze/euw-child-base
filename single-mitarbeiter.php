<?php

$context         = Timber::get_context();
$post            = new TimberPost();
$context['post'] = $post;

if (! $post->get_field('show_link')) {
	wp_redirect( $context['site']->home, 301 );
	exit;
}

$context['wp_title'] .= ' - ' . $post->title();
$context['comment_form'] = TimberHelper::get_comment_form();

if ( post_password_required( $post->ID ) ) {
	Timber::render( 'single-password.twig', $context );
} else {
	Timber::render( array(
		'single-' . $post->ID . '.twig',
		'single-' . $post->post_type . '.twig',
		'single.twig'
	), $context );
}