<?php
/**
 * Template Name: Downloads
 */

$context         = Timber::get_context();
$post            = new TimberPost();
$context['post'] = $post;

Timber::render( array( 'page-downloads.twig', 'page.twig' ), $context );