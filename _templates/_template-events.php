<?php
/*
Template Name: Veranstaltungen
*/

$context         = Timber::get_context();
$post            = new TimberPost();
$context['post'] = $post;

$context['categories'] = $post->get_field( 'category_event' );
//$context['events'] = $context['site']->getUpcomingEvents(null, -1);

Timber::render( array( 'page-events.twig', 'page.twig' ), $context );