<?php
/**
 * The template for displaying the footer.
 *
 * @package Sundance
 * @subpackage Sundance
 * @since Sundance 2.0
 */
?>
	</div>
</div>
<div class="hd">
    <div class="wrap">
        <div class="logo">
            <h1><a href="<?php bloginfo('url'); ?>">Sundance Spas&reg;</a></h1>
        </div>
        <?php wp_nav_menu( array( 'container' => 'false', 'menu_class' => 'topMenu', 'theme_location' => 'topres' ) ); ?>
        <?php get_template_part( 'block', 'mainmenu' ); ?>
    </div>
</div>
    
<div class="ft">
    <div class="wrap">
        <div class="inner">
            <div class="logo">
                <p><br /><?php echo date('Y'); ?> all rights reserved</p>
            </div>
            <div class="menuWrap">
            <?php
            wp_nav_menu( array( 'container' => 'false', 'menu_class' => 'topMenu', 'theme_location' => 'ft1' ) );
            wp_nav_menu( array( 'container' => 'false', 'menu_class' => 'bottomMenu', 'theme_location' => 'ft2' ) );
            wp_nav_menu( array( 'container' => 'false', 'menu_class' => 'bottomMenu fresources', 'theme_location' => 'ftres' ) );
            ?>
            </div>
            <br class="clear" />
        </div>
    </div>
</div>
<div class="overlay" id="emailover">
	<div class="dialog">
    	<a id="x" href="#"></a>
    	<div class="inner">
        	<h2>Sign Up for our Newsletter</h2>
            <?php //echo do_shortcode('[gravityform id="4" name="Sign Up for our Newsletter" title="false" description="false" ajax="true"]'); ?>
            <script type="text/javascript" src="http://login.sendmetric.com/phase2/bhecho_files/smartlists/check_entry.js"></script>
<script type="text/javascript">
               <!--
                              function check_cdfs(form) {
                                             return true;
                              }
               -->
</script><script type="text/javascript">
<!--
    function doSubmit() {
        if (check_cdfs(document.survey)) {
                                             window.open('','signup','resizable=1,scrollbars=0,width=300,height=150');
            return true;
        }
        else { return false; }
    }
-->
</script>
<form action="http://login.sendmetric.com/phase2/bullseye/contactupdate1.php3" method="post" name="bullseye" id="bullseye" onsubmit="return doSubmit();" target="signup">
<table> 
<tr>
<td><label for="firstname">Name</label></td>
<td><input type="text" class="text" id="firstname" name="firstname" /></td>
</tr>
<tr>
<td><input type="hidden" name="cid" value="9bffaece0a39911668c51d8eb7af85c7" /><label for="email">Email</label></td>
<td><input type="text" class="text" id="email" name="email" /></td>
</tr>
<tr>
<td><label for="postal_code">Zip</label></td>
<td><input type="text" class="text zip" id="postal_code" name="postal_code" />
</td>
</tr>
<td><input type="hidden" name="message" value="Thank you. Your information has been submitted. To ensure delivery of your newsletter(s), please add donotreply@sundancespas.com to your address book, spam filter whitelist, or tell your company's IT group to allow this address to pass through any filtering software they may have set up." />&nbsp;</td>
<td><br /><input type="image" src="<?php bloginfo('template_url'); ?>/images/icons/dialogSubmit.png" value="submit" name="SubmitBullsEye" />
<input type="hidden" name="grp[]" value="575636" /> 
</td>
</tr>
</table>
</form>
        </div>
    </div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function(){
		var h1Height = jQuery('.bd .main .main-title h1').height();
		if(h1Height > 72)
			jQuery('.bd .main .main-title').css('min-height', h1Height);
	});
</script>
<?php
	/* Always have wp_footer() just before the closing </body>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to reference JavaScript files.
	 */

	wp_footer();
?>

</body>
</html>
