<?php
/**
 * Template Name: Gerichte
 */

$context         = Timber::get_context();
$post            = new TimberPost();
$context['post'] = $post;

$context['dish_categories'] = Timber::get_terms( 'dish_categories' );

Timber::render( array( 'page-gerichte.twig', 'page.twig' ), $context );