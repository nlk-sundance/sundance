<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package Sundance
 * @subpackage Sundance
 * @since Sundance 2.0
 */

get_header(); ?>
<div class="cols">
<div class="main col w730">
<div class="main-title">
<h1>Search Results</h1>
</div>
<div class="page">
<div class="entry-content">
<?php if ( have_posts() ) : ?>
<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'sundance' ), '<span>' . get_search_query() . '</span>' ); ?></h1>

<?php while ( have_posts() ) : the_post(); ?>
		<div class="entry-summary">
			<!--h2 class="entry-title"--><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'sundance' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a><br /><!--/h2-->
				<?php the_excerpt(); ?>
			</div><!-- .entry-summary -->
<?php endwhile; // End the loop. Whew. ?>

<?php /* Display navigation to next/previous pages when applicable */ ?>
<?php if (  $wp_query->max_num_pages > 1 ) : ?>
				<div id="nav-below" class="navigation">
					<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> More Results', 'sundance' ) ); ?></div>
					<div class="nav-next"><?php previous_posts_link( __( 'Previous Results <span class="meta-nav">&rarr;</span>', 'sundance' ) ); ?></div>
				</div><!-- #nav-below -->
<?php endif; ?>
<?php else : ?>
<h1 class="entry-title"><?php _e( 'Nothing Found', 'sundance' ); ?></h1>
<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'sundance' ); ?></p>
<?php endif; ?>
</div>
</div>
</div>
<?php get_sidebar('generic'); ?>
</div>
<br class="clear" />
<?php get_footer(); ?>
