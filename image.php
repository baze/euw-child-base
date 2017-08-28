<?php 

$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;

wp_redirect($context['site']->home, 301 ); exit;