<?php
/**
 * Template Name: Kontakt
 */

$context         = Timber::get_context();
$post            = new LocationPost();
$context['post'] = $post;

if ( function_exists( 'wpcf7_enqueue_scripts' ) ) {
	wpcf7_enqueue_scripts();
}

if ( function_exists( 'wpcf7_enqueue_styles' ) ) {
	wpcf7_enqueue_styles();
}

$context['map'] = $post->map( get_field( 'firmenbezeichnung', 'option' ), [
	get_field( 'strasse_hausnummer', 'option' ),
	get_field( 'postleitzahl', 'option' ),
	get_field( 'ort', 'option' ),
	get_field( 'bundesland', 'option' ),
	get_field( 'land', 'option' )
] );

//echo "<pre>";
//dd($context['map']->zoom);

Timber::render( array( 'page-contact.twig', 'page.twig' ), $context );

