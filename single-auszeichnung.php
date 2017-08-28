<?php

$context = Timber::get_context();

wp_redirect( $context['site']->home, 301 );
exit;