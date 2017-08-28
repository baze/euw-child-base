<?php
/**
 * Template Name: Landingpage
 */

$context         = Timber::get_context();
$post            = new TimberPost();
$context['post'] = $post;

Timber::render( array( 'page-landingpage.twig', 'page.twig' ), $context );