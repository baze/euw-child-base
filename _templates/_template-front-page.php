<?php
/**
 * Template Name: Startseite
 */

$context         = Timber::get_context();
$post            = new TimberPost();
$context['post'] = $post;

Timber::render( array( 'front-page.twig', 'home.twig', 'page.twig' ), $context);