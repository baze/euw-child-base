<?php
/**
 * The main template file
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists
 *
 * Methods for TimberHelper can be found in the /functions sub-directory
 *
 * @package    WordPress
 * @subpackage    Timber
 * @since        Timber 0.1
 */

global $paged;
if ( ! isset( $paged ) || ! $paged ) {
	$paged = 1;
}

$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;

$args = [
	'post_type'      => 'post',
	'order_by'       => 'date',
	'order'          => 'DESC',
	'paged'          => $paged,
//	'category__not_in' => [ 5 ]
];

/* THIS LINE IS CRUCIAL */
/* in order for WordPress to know what to paginate */
/* your args have to be the defualt query */
query_posts( $args );

/* make sure you've got query_posts in your .php file */
$context['posts'] = Timber::get_posts();
$context['pagination'] = Timber::get_pagination();

$context['categories'] = Timber::get_terms( 'category' );

$templates = array('index.twig');
if (is_home()) {
    array_unshift($templates, 'home.twig');
}
Timber::render($templates, $context);


