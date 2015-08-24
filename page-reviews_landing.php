<?php
/**
 * Template Name: Reviews Landing Page
 *
 * @package Sundance
 * @subpackage Sundance
 * @since Sundance 2.0
 */

$bv = new BV(
    array(
        'deployment_zone_id' => 'ReadOnly-en_US',
        'product_id' => "SDS-ALL-REVIEWS", // must match ExternalID in the BV product feed
        'cloud_key' => 'sundancespas-486bb392da92b7b9f1e1628eec33b8ae',
        'staging' => false
        )
    );


get_header(); ?>

<div class="cols">
	<?php while ( have_posts() ) : the_post(); ?>
	<div class="main col w730">
		<div class="main-title">
			<?php the_post_thumbnail( array(729, 320) ); ?>
			<h1><?php sundance_pagetitle(); ?></h1>
		</div>
		<div class="page">
			<div class="entry-content">
				<?php the_content(); ?>

	            <div itemscope itemtype="http://schema.org/Product">
	                <meta itemprop="name" content="<?php echo the_title(); ?>" />
	                <div id="BVRRContainer">
	                    <?php echo $bv->reviews->getContent();?>
	                </div>
	            </div>

			</div>
		</div>
	</div>
	<?php
	endwhile;
	
	get_sidebar('generic');
	?>
	
</div>

<br class="clear" />

<script type="text/javascript">                  
$BV.configure("global", { "productId" : "JHT-ALL-REVIEWS" });
$BV.ui( 'rr', 'show_reviews', {
doShowContent : function () {
    // If the container is hidden (such as behind a tab), put code here to make it visible (open the tab).
}
});
function submitGeneric() {
    $BV.ui(
        "rr",
        "submit_generic",
        { "categoryId" : "JHT" }
    );
}
</script>


<?php get_footer(); ?>
