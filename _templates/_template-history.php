<?php
/**
 * Template Name: Historie
 */

$context         = Timber::get_context();
$post            = new TimberPost();
$context['post'] = $post;

Timber::render( array( 'page-history.twig', 'page.twig' ), $context );

