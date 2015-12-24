<?php
/**
 * Template Name: Pods Page
 *
 * @package Sundance
 * @subpackage Sundance
 * @since Sundance 2.0
 */
get_header(); 
while ( have_posts() ) : the_post();
global $post;

$custom = get_post_meta($post->ID, 's_cat_tubs');
$cat_tubs = $custom[0];
if($cat_tubs=='') $cat_tubs = array();

//$serieslanding = ( $post->ID == 1894 );

?>
 <div class="cols">
    <div class="main col w730"><div class="main-title">
        <?php the_post_thumbnail();
		?></div>
        <div class="inner">
        <?php //if ( $serieslanding )  {
			
		//} else { ?>
            <div class="headline">
                <div class="description w670 noline">
                	<h1><?php the_title(''); ?></h1>
                    <?php echo sundance_shortdesc($post->post_content, true); ?>
                </div>
                <br class="clear" />
            </div>
            <div class="tubSeries">
                <!--div class="overhead">
                <?php
				if (class_exists('MultiPostThumbnails')) {
					$firsttub = true;
					foreach ( $cat_tubs as $i => $t ) {
						if ( $firsttub ) {
						MultiPostThumbnails::the_post_thumbnail('s_spa', 'overhead-large', $i, 'overhead-mid');
						$firsttub = false;
						break;
						}
					}
				}
				?>
                </div-->
                <div class="description">
                    <div class="details">
                       <?php echo sundance_series_tubs($cat_tubs, $post->ID); ?>
                    </div>
                </div>
                <br class="clear" />
            </div>
            <?php //} ?>                        
        </div>
    </div>
    <?php get_sidebar('pods'); ?>
</div>
<br class="clear" />
<?php
endwhile;
get_footer(); ?>
