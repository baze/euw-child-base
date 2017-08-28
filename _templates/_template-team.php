<?php
/**
 * Template Name: Team
 */

$context         = Timber::get_context();
$post            = new TimberPost();
$context['post'] = $post;

Timber::render( array( 'page-team.twig', 'page.twig' ), $context );