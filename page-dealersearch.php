<?php
/**
 * Template Name: Dealer Search Landing Page
 *
 * @package Sundance
 * @subpackage Sundance
 * @since Sundance 2.0
 */

get_header(); ?>
<div class="cols">

<?php while ( have_posts() ) : the_post(); ?>
<?php /* All my content goes here... */?>

	<?php the_content(); ?>

<?php
endwhile;
//get_sidebar('generic');
?>
</div>
<br class="clear" />
<?php get_footer('dealersearch'); ?>