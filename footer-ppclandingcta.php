<?php
/**
 * The template for displaying the footer with CTA 
 *
 * @package Sundance
 * @subpackage Sundance
 * @since Sundance 2.0
 */
?>
	</div>
</div>
<div class="hd">
    <div class="wrap ppc-landing">
        <div class="logo">
            <h1><a href="<?php bloginfo('url'); ?>">Sundance Spas&reg;</a></h1>
            <?php
            global $post;
            $custom = get_post_meta($post->ID,'top-bar-slogan');
            $slogan = $custom[0];
            if ( !$slogan || empty($slogan) ) {
            ?>
            <h4>Feel the Sundance Difference</h4>
            <?php } else { ?>
            <h4><?=$slogan; ?></h4>
            <?php } ?>
        </div>
        <div>
            <a rel="download-brochure" class="bigBlueBtn scrollTo blueFade">Free Brochure</a>
        </div>
    </div>
</div>
    
<div class="ft cta">
    <div class="wrap">
        <div class="inner">
            <a rel="download-brochure" class="scrollTo"></a>
            <div class="logo">
                <p><br /><?php echo date('Y'); ?> all rights reserved</p>
            </div>
            <br class="clear" />
        </div>
    </div>
</div>

<?php
	/* Always have wp_footer() just before the closing </body>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to reference JavaScript files.
	 */

	wp_footer();
?>

</body>
</html>
