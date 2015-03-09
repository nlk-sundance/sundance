<?php
/**
 * Template Name: Download Brochure
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
                        <p>Please provide your information in the form below. *Indicates required fields.</p>
                        <div class="formWrap">
                              <form action="<?php echo get_permalink(); ?>" method="post" id="leadForm">
                                    <?php avala_hidden_fields(13, 6, 3); ?>
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
                                                            <?php avala_field( 'phone', '', false, 'label' ); ?><br />
                                                            <?php avala_field( 'phone', 'phonenumber w240 numbersonly', false, 'field', array( 'maxlength' => 16, 'placeholder' => 'Optional' ) ); ?>
                                                      </td>
                                                </tr>
                                                <tr>
                                                      <td>
                                                            <?php avala_field( 'postal_code', '', true, 'label' ); ?><br />
                                                            <?php avala_field( 'postal_code', 'w240', true, 'field', array( 'maxlength' => 10 ) ); ?>
                                                      </td>
                                                      <td>
                                                            <?php avala_field( 'country', '', true, 'label' ); ?><br />
                                                            <?php avala_field( 'country', 'country w240', true, 'field', null, 'select' ); ?>
                                                      </td>
                                                </tr>
                                                <tr class="divider">
                                                      <td colspan="2"><p>&nbsp;</p></td>
                                                </tr>
                                                <tr>
                                                      <td>
                                                            <?php avala_field( 'currently_own', '', false, 'label' ); ?>
                                                      </td>
                                                      <td>
                                                            <?php avala_field( 'currently_own', 'w240', false, 'field', null, 'select' ); ?>
                                                      </td>
                                                </tr>
                                                <tr>
                                                      <td>
                                                            <?php avala_field( 'product_id_list', '', false, 'label' ); ?>
                                                      </td>
                                                      <td>
                                                            <?php avala_field( 'product_id_list', 'w240', false, 'field', null, 'select' ); ?>
                                                      </td>
                                                </tr>
                                                <tr class="divider">
                                                      <td colspan="2"><p>&nbsp;</p></td>
                                                </tr>
                                                <tr>
                                                      <td>
                                                            <?php avala_field( 'buy_time_frame', '', false, 'label' ); ?>
                                                            <fieldset class="control large">  
                                                                  <label for="CustomData"><input name="CustomData[BuyTimeFrame]" value="Not sure" type="radio"/>  Not sure </label><br />
                                                                  <label for="CustomData"><input name="CustomData[BuyTimeFrame]" value="Within 1 month" type="radio"/>  Within 1 month </label><br />
                                                                  <label for="CustomData"><input name="CustomData[BuyTimeFrame]" value="1-3 months" type="radio"/>  1-3 months </label><br />
                                                                  <label for="CustomData"><input name="CustomData[BuyTimeFrame]" value="4-6 months" type="radio"/>  4-6 months </label><br />
                                                                  <label for="CustomData"><input name="CustomData[BuyTimeFrame]" value="6+ months" type="radio"/>  6+ months </label> 
                                                            </fieldset>
                                                      </td>
                                                      <td>
                                                            <?php avala_field( 'product_use', '', false, 'label' ); ?>
                                                            <fieldset class="control large">  
                                                                  <label for="CustomData"><input name="CustomData[ProductUse]" value="Relaxation" type="radio"/>  Relaxation </label><br />
                                                                  <label for="CustomData"><input name="CustomData[ProductUse]" value="Pain Relief/Therapy" type="radio"/>  Pain Relief/Therapy </label><br />
                                                                  <label for="CustomData"><input name="CustomData[ProductUse]" value="Bonding/Family" type="radio"/>  Bonding/Family </label><br />
                                                                  <label for="CustomData"><input name="CustomData[ProductUse]" value="Backyard Entertaining" type="radio"/>  Backyard Entertaining </label> 
                                                            </fieldset>
                                                      </td>
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
                                          <input type="hidden" id="DeliveryMethod[0]" name="DeliveryMethod" value="12" />
                                          <input data-val="true" data-val-number="The field LeadTypeId must be a number." data-val-required="The LeadTypeId field is required." id="LeadTypeId" name="LeadTypeId" type="hidden" value="12" />
                                          <input type="submit" class="button conversion-button" value="DOWNLOAD FREE BROCHURE" />
                                          <?php /*<input type="image" src="<?php bloginfo('template_url'); ?>/images/icons/download-brochure.jpg" value="submit" /> */ ?>
                                    </form>
                              </div>
                              <br /><br /><br />
                              <div class="horzBorder"></div>
                              <p><small>Your privacy is very important to us.<br /><a href="<?php echo get_bloginfo('url'); ?>/about-us/privacy-policy" target="_blank" title="Policies">View our privacy policy</a>.</small></p>
                              <?php the_content(); /* for tracking */ ?>
                        </div>
                  </div>
            </div>
      <?php endwhile; //get_sidebar('generic'); ?>
</div>

<br class="clear" />

<?php get_footer(); ?>
