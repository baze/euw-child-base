<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * To generate specific templates for your pages you can use:
 * /mytheme/views/page-mypage.twig
 * (which will still route through this PHP file)
 * OR
 * /mytheme/page-mypage.php
 * (in which case you'll want to duplicate this file and save to the above path)
 *
 * Methods for TimberHelper can be found in the /functions sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$context         = Timber::get_context();
$post            = new TimberPost();
$context['post'] = $post;

if ( $post->get_field( 'aside_posts_post_type' ) ) {

	$posts                  = Timber::get_posts( [
		'post_type'      => $post->get_field( 'aside_posts_post_type' ),
		'posts_per_page' => $post->get_field( 'aside_posts_limit' ) ?: - 1
	] );
	$context['aside_posts'] = $posts;
}

/*$parents = get_post_ancestors( $post->ID );
$topmost_parent_id      = ( $parents ) ? $parents[ count( $parents ) - 1 ] : $post->ID;

$subpages = wp_list_pages( [
	'child_of'    => $topmost_parent_id,
	'echo'        => false,
	'title_li'    => null,
	'sort_column' => 'post_title'
] );
$context['subpages'] = $subpages;*/


Timber::render( array( 'page-' . $post->post_name . '.twig', 'page.twig' ), $context );