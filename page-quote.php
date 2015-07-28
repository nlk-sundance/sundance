<?php
/**
 * Template Name: Get A Quote
 *
 * @package Sundance
 * @subpackage Sundance
 * @since Sundance 2.0
 */


avala_form_submit();

get_header(); ?>


<div class="cols">
    <?php while ( have_posts() ) : the_post(); ?>
    <div class="main col w960">
        <div class="main-title">
            <h1><?php the_title(''); ?></h1>
        </div>
        <div class="page">
            <div class="entry-content">
                <?php the_content(); ?>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
    
</div>

<br class="clear" />
<?php get_footer(); ?>