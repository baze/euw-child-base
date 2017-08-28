<?php
/**
 * Template Name: Eltern-Seite
 */

$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;

$parents           = get_post_ancestors( $post->ID );
$topmost_parent_id = ( $parents ) ? $parents[ count( $parents ) - 1 ] : $post->ID;

$subpages = wp_list_pages( [
	'child_of' => $topmost_parent_id,
	'echo'     => false,
	'title_li' => null,
	'sort_column' => 'post_title'
] );
$context['subpages'] = $subpages;

$pages = get_pages( [
	'child_of' => $post->ID,
	'sort_order'  => 'asc',
	'sort_column' => 'title',
] );
$context['posts'] = $pages;


Timber::render(array('page-parent-' . $post->post_name . '.twig', 'page-parent.twig', 'page-' . $post->post_name . '.twig', 'page.twig'), $context);