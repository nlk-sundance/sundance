<?php
/**
 * Template Name: Trade In
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
    <p>Learn what your current hot tub is worth towards the purchase of a new Sundance hot tub! Upon completing this form, your local authorized dealer will contact you to learn more about your current hot tub and provide a competitive, no obligation, free trade-in estimate.</p>
    <p style="font-size: 11px;">Please provide your information in the form below. * Indicates required fields.<?php
        if(count($errors) > 0) {
        	echo '<br /><br /><span class="errors">';
        	foreach( $errors as $err ) {
        		echo "$err<br />";
        	}
        	echo '</span><br />';
        }
        ?>
    </p>

<div class="formWrap">
    <form action="<?php echo get_permalink(); ?>" method="post" id="leadForm">
        <?php avala_hidden_fields(13, 6, 7); ?>
        <table width="100%" cellspacing="0">
            <tr>
                <td width="50%">
                    <label for="person_first_name">First Name*</label><br />
                    <input class="w240 required" id="person_first_name" name="FirstName" type="text" />
                </td>
                <td width="50%"><label for="person_last_name">Last Name*</label><br />
                    <input class="w240 required" id="person_last_name" name="LastName" type="text" />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="person_email">Email*</label><br />
                    <input class="email w240 required" id="person_email" name="EmailAddress" type="text" />
                </td>
                <td>
                    <table cellspacing="0" width="80%">
                        <tr>
                            <td>
                                <label for="person_phone">Phone*</label><br />
                                <input id="person_phone" name="HomePhone" type="text" class="numbersonly w110 required" maxlength="16" />
                            </td>
                            <td><label for="person_zip">Zip*</label><br />
                                <input id="person_zip" name="PostalCode" type="text" class="w110 required" maxlength="10" />
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="divider">
                <td colspan="2"><p>&nbsp;</p></td>
            </tr>

            <tr>
                <td>
                    <label>When do you plan to purchase a hot tub?* </label>
                    <select id="when_purchased" name="CustomData[BuyTimeFrame]" class="w240 select required">
                            <option value="">  Choose One </option> 
                            <option value=""> </option> 
                            <option value="Not sure">  Not sure </option> 
                            <option value="Within 1 month">  Within 1 month </option> 
                            <option value="1-3 months">  1-3 months </option> 
                            <option value="4-6 months">  4-6 months </option> 
                            <option value="6+ months">  6+ months </option> 
                    </select>
                </td>
                <td></td>
            </tr>
            <tr class="divider">
                <td colspan="2"></td>
            </tr>
            <tr>
                <td>
                    <label>Trade-In Condition?* </label>
                    <select id="trade_condition" name="CustomData[Condition]" class="w240 select required">
                            <option value="">  Choose One </option> 
                            <option value=""> </option> 
                            <option value="Excellent">  Excellent </option> 
                            <option value="Very Good">  Very Good </option> 
                            <option value="Good">  Good </option> 
                            <option value="Average">  Average </option> 
                            <option value="Poor">  Poor </option>
                            <option value="Very Poor">  Very Poor </option>
                    </select>
                </td>
                <td>
                    <table cellspacing="0" width="80%">
                        <tr>
                            <td>
                                <label for="trade_year">Trade-In Year*</label>
                                <input id="trade_year" name="CustomData[TradeInYear]" type="text" class="numbersonly w110 required" maxlength="4" />
                            </td>
                            <td>
                                <label for="trade_brand">Trade-In Brand*</label>
                                <input id="trade_brand" name="CustomData[TradeInMake]" type="text" maxlength="40" class="w110 required" /> 
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="divider">
                <td colspan="2"></td>
            </tr>
            <tr>
                <td colspan="2">
                    <label>Additional comments about the hot tub you would like to trade in?</label>
                    <textarea name="Comments" style="width:92%;"></textarea>
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
        <input data-val="true" data-val-number="The field LeadTypeId must be a number." data-val-required="The LeadTypeId field is required." id="LeadTypeId" name="LeadTypeId" type="hidden" value="7" />
        <input type="hidden" name="returnUrl" value="<?php echo get_permalink(); ?>trade-in-thanks/" />
        <input type="submit" class="button conversion-button" value="TRADE-IN VALUE" />
        <?php /* <input type="image" src="<?php bloginfo('template_url'); ?>/images/icons/download-brochure.jpg" value="submit" /> */ ?>
    </form>
</div>
<br /><br /><br />
<div class="horzBorder"></div>
<p><small>Your privacy is very important to us.<br /><a href="<?php echo get_bloginfo('url'); ?>/about-us/privacy-policy" target="_blank" title="Policies">View our privacy policy</a>.</small></p>
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
