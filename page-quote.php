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
<p>Simply fill in this quick form to request pricing on your perfect hot tub. Your local authorized Sundance dealer will reach out to you with expert selection advice, pricing, and any current specials in your area. *Indicates required fields.<?php
if(count($errors) > 0) {
	echo '<br /><br /><span class="errors">';
	foreach( $errors as $err ) {
		echo "$err<br />";
	}
	echo '</span><br />';
}
?></p>

<div class="formWrap">
    <form action="<?php echo get_permalink(); ?>" method="post" id="leadForm">
        <?php avala_hidden_fields(13, 6, 5); ?>
        <table width="100%" cellspacing="0">
            <tr>
                <td width="50%">
                    <?php avala_field( 'first_name', '', true, 'label' ); ?><br />
                    <?php avala_field( 'first_name', 'text w240', true, 'field' ); ?>
                </td>
                <td width="50%">
                    <?php avala_field( 'last_name', '', true, 'label' ); ?><br />
                    <?php avala_field( 'last_name', 'text w240', true, 'field' ); ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?php avala_field( 'email', '', true, 'label' ); ?><br />
                    <?php avala_field( 'email', 'email w240', true, 'field' ); ?>
                </td>
                <td>
                    <table cellspacing="0" width="80%">
                        <tr>
                            <td>
                                <?php avala_field( 'phone', '', true, 'label' ); ?><br />
                                <?php avala_field( 'phone', 'phonenumber w110', true, 'field', array( 'maxlength' => 16, 'placeholder' => 'xxx-xxx-xxxx' ) ); ?>
                            </td>
                            <td>
                                <?php avala_field( 'postal_code', '', true, 'label' ); ?><br />
                                <?php avala_field( 'postal_code', 'w110', true, 'field', array( 'maxlength' => 10 ) ); ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <!--tr>
                <td>
                    <?php avala_field( 'address', '', false, 'label' ); ?><br />
                    <?php avala_field( 'address', 'text w240', false, 'field', array( 'placeholder' => 'Optional' ) ); ?>
                </td>
                <td>
                    <?php avala_field( 'city', '', true, 'label' ); ?><br />
                    <?php avala_field( 'city', 'text w240', true, 'field' ); ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?php avala_field( 'state', '', true, 'label' ); ?><br />
                    <?php avala_field( 'state', 'state w240', true, 'field', null, 'select' ); ?>
                </td>
                <td>
                    <?php avala_field( 'country', '', true, 'label' ); ?><br />
                    <?php avala_field( 'country', 'country w240', true, 'field', null, 'select' ); ?>
                </td>
            </tr-->
            <tr class="divider">
                <td colspan="2"><p>&nbsp;</p></td>
            </tr>
            <tr>
                <td>
                    <label> Do you currently own, or have you ever owned a hot tub? </label>
                </td>
                <td>
                    <select name="CustomData[CurrentOwner]" class="w240 select"> 
                        <option value="Yes">Yes</option> 
                        <option value="No" selected>No</option> 
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <label> Which product are you interested in purchasing most? </label>
                </td>
                <td>
                    <select name="ProductIdList" class="w270 select"> 
                        <option value="0">Not Specified</option> 
                        <option value="1">Constance</option> 
                        <option value="2">Victoria</option>
                        <option value="3">Maxxus</option> 
                        <option value="4">Aspen</option> 
                        <option value="5">Optima</option> 
                        <option value="6">Cameo</option> 
                        <option value="7">Majesta</option> 
                        <option value="8">Altamar</option> 
                        <option value="9">Marin</option> 
                        <option value="10">Capri</option> 
                        <option value="14">Chelsee</option> 
                        <option value="15">Hamilton</option> 
                        <option value="16">Certa</option> 
                        <option value="17">Camden</option> 
                        <option value="18">Dover</option> 
                        <option value="19">Hartford</option> 
                        <option value="20">Hawthorne</option> 
                        <option value="21">Peyton</option> 
                        <option value="22">Edison</option> 
                        <option value="23">Denali</option> 
                        <option value="24">Tacoma</option> 
                    </select> 
                </td>
            </tr>
            <tr class="divider">
                <td colspan="2"><p>&nbsp;</p></td>
            </tr>
            <tr>
                <td>
                    <label> When do you plan to purchase a hot tub? </label> <em></em> 
                    <fieldset class="control large">  
                            <label for="CustomData"><input name="CustomData[BuyTimeFrame]" value="Not sure" type="radio"/>  Not sure </label><br />
                            <label for="CustomData"><input name="CustomData[BuyTimeFrame]" value="Within 1 month" type="radio"/>  Within 1 month </label><br />
                            <label for="CustomData"><input name="CustomData[BuyTimeFrame]" value="1-3 months" type="radio"/>  1-3 months </label><br />
                            <label for="CustomData"><input name="CustomData[BuyTimeFrame]" value="4-6 months" type="radio"/>  4-6 months </label><br />
                            <label for="CustomData"><input name="CustomData[BuyTimeFrame]" value="6+ months" type="radio"/>  6+ months </label> 
                    </fieldset>
                </td>
                <td>
                    <label> What is the primary reason you are considering the purchase of a hot tub? </label> <em></em> 
                    <fieldset class="control large">  
                            <label for="CustomData"><input name="CustomData[ProductUse]" value="Relaxation" type="radio"/>  Relaxation </label><br />
                            <label for="CustomData"><input name="CustomData[ProductUse]" value="Pain Relief/Therapy" type="radio"/>  Pain Relief/Therapy </label><br />
                            <label for="CustomData"><input name="CustomData[ProductUse]" value="Bonding/Family" type="radio"/>  Bonding/Family </label><br />
                            <label for="CustomData"><input name="CustomData[ProductUse]" value="Backyard Entertaining" type="radio"/>  Backyard Entertaining </label> 
                    </fieldset>
                </td>
            </tr>
            <tr class="divider">
                <td colspan="2"><p>&nbsp;</p></td>
            </tr>
            <tr>
                <td colspan="2">
                    <label for="ReceiveEmailCampaigns"><input class="editor choice" name="ReceiveEmailCampaigns" value="true" type="checkbox" />  Please send me exclusive sale alerts from Sundance Spas</label>
                </td>
            </tr>
            <tr class="divider">
                <td colspan="2"><p>&nbsp;</p></td>
            </tr>
        </table>
        <input data-val="true" data-val-number="The field LeadTypeId must be a number." data-val-required="The LeadTypeId field is required." id="LeadTypeId" name="LeadTypeId" type="hidden" value="5" />
        <input type="submit" class="button conversion-button" value="GET YOUR QUOTE" />
        <?php /* <input type="image" src="<?php bloginfo('template_url'); ?>/images/icons/download-brochure.jpg" value="submit" /> */ ?>
    </form>
</div>
<br /><br /><br />
<div class="horzBorder"></div>
<p><small>Your privacy is very important to us.<br /><a href="http://www.sundancespas.com/about-us/privacy-policy" target="_blank" title="Policies">View our privacy policy</a>.</small></p>
</div>
</div>
</div>
<?php
endwhile;
//get_sidebar('generic');
?>
</div>
<br class="clear" />
<?php get_footer(); ?>
