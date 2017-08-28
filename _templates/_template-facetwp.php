<?php
/**
 * Template Name: FacetWP Template
 */

$context         = Timber::get_context();
$post            = new LocationPost();
$context['post'] = $post;

Timber::render( array( 'page-facetwp.twig', 'page.twig' ), $context );