<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Sundance
 * @subpackage Sundance
 * @since Sundance 2.0
 */

get_header();
?>
<div class="cols">
<div class="main col w730">
<div class="main-title">
<h1>Page Not Found</h1>
</div>
<div class="page">
<div class="entry-content">
<h1>Looking for something at Sundance Spas?</h1>
                    <p>Sorry but the page you requested could not be found.</p>
                    <p><a href="<?php bloginfo('url'); ?>">Return to our home page</a></p>
</div>
</div>
</div>
<?php
get_sidebar('generic');
?>
</div>
<br class="clear" />
<?php get_footer(); ?>
