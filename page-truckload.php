<?php
/**
 * Template Name: Truckload
 *
 * @package Sundance
 * @subpackage Sundance
 * @since Sundance 2.0
 */

avala_form_submit();

$truckload_url = get_bloginfo('url') . '/hot-tub-dealer-locator/dealers/get_truckload_cities_json';
$json = file_get_contents($truckload_url);
$truckloadObj = json_decode($json);

get_header(); ?>
<div class="cols">
<?php while ( have_posts() ) : the_post(); ?>
<div class="main col w730">
<div class="main-title">
<?php the_post_thumbnail(); ?>
<div class="content">
<?php the_content(); ?>
</div>
</div>
<div class="page">
    <div class="entry-content">
    <h1>Sundance<span class="fx">&reg;</span> Hot Tub Sales Event</h1>
        <!-- Truckload Sales -->
        <?php if ( ! empty($truckloadObj) ) : ?>

            <?php foreach ($truckloadObj as $key => $dealer) { ?>

                <div class="sale">
                    <div class="date">
                        <?php echo $dealer->start_date . ' ' . $dealer->end_date; ?>
                    </div>
                    <div class="info">
                        <strong class="title">
                            <?php if ( !empty($dealer->tl_name) ) { ?>
                                <?php echo $dealer->tl_name; ?>
                            <?php } else { ?>
                                <?php esc_attr_e($row->name); ?>
                            <?php } ?>
                        </strong>
                        <br />
                        <?php echo ( ( !empty($dealer->tl_address) ) ? ucwords($dealer->tl_address) : ucwords($dealer->address1) ); ?> <?php echo $row->address2; ?>, <?php echo ( ( !empty($dealer->tl_city) ) ? ucwords($dealer->tl_city) : ucwords($dealer->city) ); ?> <?php echo ( ( !empty($dealer->tl_state) ) ? strtoupper($dealer->tl_state) : strtoupper($dealer->state) ); ?> <?php echo ( ( !empty($dealer->tl_zip) ) ? $dealer->tl_zip : $dealer->zip ); ?>
                        <br />
                        <?php echo $dealer->phone; ?>
                        <br /><br />
                        <?php echo $dealer->additional_html; ?>
                        <br />
                        <a href="<?php echo $dealer->website; ?>" target="_blank"><?php echo $dealer->website; ?></a>
                    </div>
                </div>
            
            <?php } ?>

        <?php else : ?>
            
            <?php echo '<div class="sale"><div class="info"><strong class="title">NO SALES FOUND AT THIS TIME</strong><br />Please check back soon!</div></div>'; ?>
        
        <?php endif; ?>

    </div>
</div>
</div>
<?php
endwhile;
//get_sidebar('generic');
?>
<div class="side col w230 last">
<div class="arrow"><h3>Request the Truckload Sale in your town</h3></div>

<div class="inner">
    <?php
    //API call to get two letter country code remotely
    //$IP=$_SERVER['REMOTE_ADDR']; 
    //$ipcountry = file_get_contents('http://api.hostip.info/country.php?ip='.$IP);
    echo do_shortcode('[gravityform id="12" title="false" description="false"]');
	echo '<p class="note"><span class="rqd">*</span> Fields with an asterisk are required.<br />&nbsp;</p>';
	?>

<h3>Share This</h3>
<div class="share">
<?php if(function_exists('sharethis_button')) sharethis_button(); ?>
</div>
</div>
</div>
</div>
<br class="clear" />
<?php get_footer(); ?>
