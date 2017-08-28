<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * Methods for TimberHelper can be found in the /functions sub-directory
 *
 * @package    WordPress
 * @subpackage    Timber
 * @since        Timber 0.2
 */


$templates = array('archive.twig', 'index.twig');

$context = Timber::get_context();

//$context['subtitle'] = 'Archiv für';

if (is_day()) {
    $context['title'] = get_the_date('D M Y');
} else if (is_month()) {
    $context['title'] = get_the_date('M Y');
} else if (is_year()) {
    $context['title'] = get_the_date('Y');
} else if (is_tag()) {
    $context['title'] = single_tag_title('', false);
} else if (is_category()) {
    $context['title'] = single_cat_title('', false);
    array_unshift($templates, 'archive-' . get_query_var('cat') . '.twig');
} else if (is_post_type_archive()) {
    $context['title'] = post_type_archive_title('', false);
    $context['post_type'] = get_post_type();
    array_unshift($templates, 'archive-' . get_post_type() . '.twig');

    $page = get_page_by_path(get_post_type());
    
    if ($page) {
    	$page = new TimberPage($page);
    	$context['post'] = $page;
    }
}

$args = array(
	'post_type' => 'any',
	'category' => get_query_var( 'cat' )
);

$context['posts'] = Timber::get_posts($args);
$context['pagination'] = Timber::get_pagination();

Timber::render($templates, $context);
