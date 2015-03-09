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
                        <a href="<?php echo $row->website; ?>" target="_blank"><?php echo $dealer->website; ?></a>
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
    $IP=$_SERVER['REMOTE_ADDR']; 
    $ipcountry = file_get_contents('http://api.hostip.info/country.php?ip='.$IP);
    ?>

                    <form action="<?php echo get_permalink(); ?>" method="post" id="requestform" class="truckloadform">
                        <?php avala_hidden_fields(13, 6, 10); ?>
                        <table cellpadding="0">
                            <tr>
                                <td>
                                    <label for="FirstName">First Name<span class="rqd"> *</span></label><br />
                                    <input id="person_first_name" name="FirstName" type="text" class="text w170 required" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="LastName">Last Name<span class="rqd"> *</span></label><br />
                                    <input id="person_last_name" name="LastName" type="text" class="text w170 required" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="EmailAddress">Email<span class="rqd"> *</span></label><br />
                                    <input id="person_email" name="EmailAddress" type="text" class="email text w170 required" placeholder="email@example.com"/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="Phone">Phone</label><br />
                                    <input id="person_phone" name="Phone" type="text" class="text w170 number" placeholder="Numbers only please"/>
                                </td>
                            </tr>
                            <?php /*
                            <tr>
                                <td>
                                    <label for="Address1">Address*</label><br />
                                    <input id="person_address" name="Address1" type="text" class="text w170 required" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="City">City*</label><br />
                                    <input name="City" type="text" class="text w170 required" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="State">State*</label><br />
                                    <select name="State" class="state w170 select" style="min-width: 150px;"> 
                                        <option value="">Select State</option> 
                                        <option data-country="AE" value="AD">Abu Dhabia</option> 
                                        <option data-country="AE" value="AJ">Ajman</option> 
                                        <option data-country="AE" value="DU">Dubai</option> 
                                        <option data-country="AE" value="FU">Fujairah</option> 
                                        <option data-country="AE" value="RK">Ras al-Khaimah</option> 
                                        <option data-country="AE" value="SH">Sharjah</option> 
                                        <option data-country="AE" value="UQ">Umm al-Quwain</option> 
                                        <option data-country="US" value="AL">Alabama</option> 
                                        <option data-country="FR" value="A">Alsace</option> 
                                        <option data-country="FR" value="B">Aquitaine</option> 
                                        <option data-country="US" value="AK">Alaska</option> 
                                        <option data-country="FR" value="C">Auvergne</option> 
                                        <option data-country="FR" value="P">Basse-Normandie</option> 
                                        <option data-country="US" value="AZ">Arizona</option> 
                                        <option data-country="FR" value="D">Bourgogne</option> 
                                        <option data-country="US" value="AR">Arkansas</option> 
                                        <option data-country="FR" value="E">Bretagne</option> 
                                        <option data-country="US" value="CA">California</option> 
                                        <option data-country="FR" value="F">Centre</option> 
                                        <option data-country="FR" value="G">Champagne-Ardenne</option> 
                                        <option data-country="US" value="CO">Colorado</option> 
                                        <option data-country="US" value="CT">Connecticut</option> 
                                        <option data-country="FR" value="H">Corse</option> 
                                        <option data-country="FR" value="I">Franche-Comt </option> 
                                        <option data-country="US" value="DE">Delaware</option> 
                                        <option data-country="FR" value="Q">Haute-Normandie</option> 
                                        <option data-country="US" value="DC">District Of Columbia</option> 
                                        <option data-country="FR" value="J"> le-de-France</option> 
                                        <option data-country="US" value="FL">Florida</option> 
                                        <option data-country="US" value="GA">Georgia</option> 
                                        <option data-country="FR" value="K">Languedoc-Roussillon</option> 
                                        <option data-country="FR" value="L">Limousin</option> 
                                        <option data-country="FR" value="M">Lorraine</option> 
                                        <option data-country="US" value="HI">Hawaii</option> 
                                        <option data-country="US" value="ID">Idaho</option> 
                                        <option data-country="FR" value="N">Midi-Pyr n es</option> 
                                        <option data-country="FR" value="O">Nord - Pas-de-Calais</option> 
                                        <option data-country="US" value="IL">Illinois</option> 
                                        <option data-country="US" value="IN">Indiana</option> 
                                        <option data-country="FR" value="R">Pays de la Loire</option> 
                                        <option data-country="FR" value="S">Picardie</option> 
                                        <option data-country="US" value="IA">Iowa</option> 
                                        <option data-country="US" value="KS">Kansas</option> 
                                        <option data-country="FR" value="T">Poitou-Charentes</option> 
                                        <option data-country="FR" value="U">Provence-Alpes-C te d'Azur</option> 
                                        <option data-country="US" value="KY">Kentucky</option> 
                                        <option data-country="US" value="LA">Louisiana</option> 
                                        <option data-country="FR" value="V">Rh ne-Alpes</option> 
                                        <option data-country="US" value="ME">Maine</option> 
                                        <option data-country="US" value="MD">Maryland</option> 
                                        <option data-country="US" value="MA">Massachusetts</option> 
                                        <option data-country="US" value="MI">Michigan</option> 
                                        <option data-country="US" value="MN">Minnesota</option> 
                                        <option data-country="US" value="MS">Mississippi</option> 
                                        <option data-country="US" value="MO">Missouri</option> 
                                        <option data-country="US" value="MT">Montana</option> 
                                        <option data-country="US" value="NE">Nebraska</option> 
                                        <option data-country="US" value="NV">Nevada</option> 
                                        <option data-country="US" value="NH">New Hampshire</option> 
                                        <option data-country="US" value="NJ">New Jersey</option> 
                                        <option data-country="US" value="NM">New Mexico</option> 
                                        <option data-country="US" value="NY">New York</option> 
                                        <option data-country="US" value="NC">North Carolina</option> 
                                        <option data-country="US" value="ND">North Dakota</option> 
                                        <option data-country="US" value="OH">Ohio</option> 
                                        <option data-country="US" value="OK">Oklahoma</option> 
                                        <option data-country="US" value="OR">Oregon</option> 
                                        <option data-country="US" value="PA">Pennsylvania</option> 
                                        <option data-country="US" value="RI">Rhode Island</option> 
                                        <option data-country="US" value="SC">South Carolina</option> 
                                        <option data-country="US" value="SD">South Dakota</option> 
                                        <option data-country="US" value="TN">Tennessee</option> 
                                        <option data-country="US" value="TX">Texas</option> 
                                        <option data-country="US" value="UT">Utah</option> 
                                        <option data-country="US" value="VT">Vermont</option> 
                                        <option data-country="US" value="VA">Virginia</option> 
                                        <option data-country="US" value="WA">Washington</option> 
                                        <option data-country="US" value="WV">West Virginia</option> 
                                        <option data-country="US" value="WI">Wisconsin</option> 
                                        <option data-country="US" value="WY">Wyoming</option> 
                                        <option data-country="US" value="AE">Armed Forces - Europe</option> 
                                        <option data-country="US" value="AP">Armed Forces - Pacific</option> 
                                        <option data-country="US" value="AA">Armed Forces - Americas</option> 
                                        <option data-country="AS" value="AS">American Samoa</option> 
                                        <option data-country="FM" value="FM">Micronesia</option> 
                                        <option data-country="GU" value="GU">Guam</option> 
                                        <option data-country="MH" value="MH">Marshall Islands</option> 
                                        <option data-country="MP" value="MP">N Mariana Islands</option> 
                                        <option data-country="PW" value="PW">Palau</option> 
                                        <option data-country="PR" value="PR">Puerto Rico</option> 
                                        <option data-country="VI" value="VI">Virgin Islands</option> 
                                        <option data-country="CA" value="AB">Alberta</option> 
                                        <option data-country="CA" value="BC">British Columbia</option> 
                                        <option data-country="CA" value="MB">Manitoba</option> 
                                        <option data-country="CA" value="NB">New Brunswick</option> 
                                        <option data-country="CA" value="NL">Newfoundland</option> 
                                        <option data-country="CA" value="NS">Nova Scotia</option> 
                                        <option data-country="CA" value="NT">Northwest Territory</option> 
                                        <option data-country="CA" value="NU">Nuavut</option> 
                                        <option data-country="CA" value="ON">Ontario</option> 
                                        <option data-country="CA" value="PE">Prince Edward Island</option> 
                                        <option data-country="CA" value="QC">Qu bec</option> 
                                        <option data-country="CA" value="SK">Saskatchewan</option> 
                                        <option data-country="CA" value="YT">Yukon Territories</option> 
                                        <option data-country="AU" value="SA">Southern Australia</option> 
                                        <option data-country="AU" value="QLD">Queensland</option> 
                                        <option data-country="AU" value="NSW">New South Wales</option> 
                                        <option data-country="AU" value="ACT">Australian Capital Territory</option> 
                                        <option data-country="AU" value="VIC">Victoria</option> 
                                        <option data-country="AU" value="WA">Western Australia</option> 
                                        <option data-country="AU" value="TAS">Tasmania</option> 
                                        <option data-country="AU" value="NT">Northern Territory</option> 
                                        <option data-country="BR" value="AC">Acre</option> 
                                        <option data-country="BR" value="AL">Alagoas</option> 
                                        <option data-country="BR" value="AM">Amazonas</option> 
                                        <option data-country="BR" value="AP">Amap </option> 
                                        <option data-country="BR" value="BA">Bahia</option> 
                                        <option data-country="BR" value="CE">Cear </option> 
                                        <option data-country="BR" value="DF">Distrito Federal</option> 
                                        <option data-country="BR" value="ES">Espirito Santo</option> 
                                        <option data-country="BR" value="GO">Goi s</option> 
                                        <option data-country="BR" value="MA">Maranh o</option> 
                                        <option data-country="BR" value="MG">Minas Gerais</option> 
                                        <option data-country="BR" value="MS">Mato Grosso do Sul</option> 
                                        <option data-country="BR" value="MT">Mato Grosso</option> 
                                        <option data-country="BR" value="PA">Par </option> 
                                        <option data-country="BR" value="PB">Para ba</option> 
                                        <option data-country="BR" value="PE">Pernambuco</option> 
                                        <option data-country="BR" value="PI">Piau </option> 
                                        <option data-country="BR" value="PR">Paran </option> 
                                        <option data-country="BR" value="RJ">Rio de Janeiro</option> 
                                        <option data-country="BR" value="RN">Rio Grande do Norte</option> 
                                        <option data-country="BR" value="RO">Rond nia</option> 
                                        <option data-country="BR" value="RR">Roraima</option> 
                                        <option data-country="BR" value="RS">Rio Grande do Sul</option> 
                                        <option data-country="BR" value="SC">Santa Catarina</option> 
                                        <option data-country="BR" value="SE">Sergipe</option> 
                                        <option data-country="BR" value="SP">S o Paulo</option> 
                                        <option data-country="BR" value="TO">Tocantins</option> 
                                    </select>
                                </td>
                            </tr>
                            */ ?>
                            <tr>
                                <td>
                                    <label for="PostalCode">Zip/Postal Code<span class="rqd"> *</span></label><br />
                                    <input id="person_postal_code" name="PostalCode" type="text" maxlength="10" class="text w170 required" /> 
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="CountryCode">Country</label><br />
                                    <select id="person_country" name="CountryCode" class="country w170 select"> 
                                        <option value="">Select Country</option>
                                        <option value=""></option>
                                        <option value="US" <?php if($ipcountry == 'US') { echo 'selected="selected"'; } ?> >United States</option> 
                                        <option value="CA" <?php if($ipcountry == 'CA') { echo 'selected="selected"'; } ?> >Canada</option>
                                        <option value=""></option> 
                                        <option value="AF" <?php if($ipcountry == 'AF') { echo 'selected="selected"'; } ?> >Afghanistan</option> 
                                        <option value="AL" <?php if($ipcountry == 'AL') { echo 'selected="selected"'; } ?> >Albania</option> 
                                        <option value="DZ" <?php if($ipcountry == 'DZ') { echo 'selected="selected"'; } ?> >Algeria</option> 
                                        <option value="AS" <?php if($ipcountry == 'AS') { echo 'selected="selected"'; } ?> >American Samoa</option> 
                                        <option value="AD" <?php if($ipcountry == 'AD') { echo 'selected="selected"'; } ?> >Andorra</option> 
                                        <option value="AO" <?php if($ipcountry == 'AO') { echo 'selected="selected"'; } ?> >Angola</option> 
                                        <option value="AI" <?php if($ipcountry == 'AI') { echo 'selected="selected"'; } ?> >Anguilla</option> 
                                        <option value="AQ" <?php if($ipcountry == 'AQ') { echo 'selected="selected"'; } ?> >ANTARCTICA</option> 
                                        <option value="AG" <?php if($ipcountry == 'AG') { echo 'selected="selected"'; } ?> >Antigua and Barbuda</option> 
                                        <option value="AR" <?php if($ipcountry == 'AR') { echo 'selected="selected"'; } ?> >Argentina</option> 
                                        <option value="AM" <?php if($ipcountry == 'AM') { echo 'selected="selected"'; } ?> >Armenia</option> 
                                        <option value="AW" <?php if($ipcountry == 'AW') { echo 'selected="selected"'; } ?> >Aruba</option> 
                                        <option value="AC" <?php if($ipcountry == 'AC') { echo 'selected="selected"'; } ?> >Ascension Island</option> 
                                        <option value="AU" <?php if($ipcountry == 'AU') { echo 'selected="selected"'; } ?> >Australia</option> 
                                        <option value="AT" <?php if($ipcountry == 'AT') { echo 'selected="selected"'; } ?> >Austria</option> 
                                        <option value="AZ" <?php if($ipcountry == 'AZ') { echo 'selected="selected"'; } ?> >Azerbaijan</option> 
                                        <option value="BS" <?php if($ipcountry == 'BS') { echo 'selected="selected"'; } ?> >Bahamas</option> 
                                        <option value="BH" <?php if($ipcountry == 'BH') { echo 'selected="selected"'; } ?> >Bahrain</option> 
                                        <option value="BD" <?php if($ipcountry == 'BD') { echo 'selected="selected"'; } ?> >Bangladesh</option> 
                                        <option value="BB" <?php if($ipcountry == 'BB') { echo 'selected="selected"'; } ?> >Barbados</option> 
                                        <option value="BY" <?php if($ipcountry == 'BY') { echo 'selected="selected"'; } ?> >Belarus</option> 
                                        <option value="BE" <?php if($ipcountry == 'BE') { echo 'selected="selected"'; } ?> >Belgium</option> 
                                        <option value="BZ" <?php if($ipcountry == 'BZ') { echo 'selected="selected"'; } ?> >Belize</option> 
                                        <option value="BJ" <?php if($ipcountry == 'BJ') { echo 'selected="selected"'; } ?> >Benin</option> 
                                        <option value="BM" <?php if($ipcountry == 'BM') { echo 'selected="selected"'; } ?> >Bermuda</option> 
                                        <option value="BT" <?php if($ipcountry == 'BT') { echo 'selected="selected"'; } ?> >Bhutan</option> 
                                        <option value="BO" <?php if($ipcountry == 'BO') { echo 'selected="selected"'; } ?> >Bolivia</option> 
                                        <option value="BA" <?php if($ipcountry == 'BA') { echo 'selected="selected"'; } ?> >Bosnia-Herzegovina</option> 
                                        <option value="BW" <?php if($ipcountry == 'BW') { echo 'selected="selected"'; } ?> >Botswana</option> 
                                        <option value="BV" <?php if($ipcountry == 'BV') { echo 'selected="selected"'; } ?> >BOUVET ISLAND</option> 
                                        <option value="BR" <?php if($ipcountry == 'BR') { echo 'selected="selected"'; } ?> >Brazil</option> 
                                        <option value="IO" <?php if($ipcountry == 'IO') { echo 'selected="selected"'; } ?> >BRITISH INDIAN OCEAN TERRITORY</option> 
                                        <option value="BN" <?php if($ipcountry == 'BN') { echo 'selected="selected"'; } ?> >Brunei Darussalam</option> 
                                        <option value="BG" <?php if($ipcountry == 'BG') { echo 'selected="selected"'; } ?> >Bulgaria</option> 
                                        <option value="BF" <?php if($ipcountry == 'BF') { echo 'selected="selected"'; } ?> >Burkina Faso</option> 
                                        <option value="BI" <?php if($ipcountry == 'BI') { echo 'selected="selected"'; } ?> >Burundi</option> 
                                        <option value="KH" <?php if($ipcountry == 'KH') { echo 'selected="selected"'; } ?> >Cambodia</option> 
                                        <option value="CM" <?php if($ipcountry == 'CM') { echo 'selected="selected"'; } ?> >Cameroon</option> 
                                        <option value="CV" <?php if($ipcountry == 'CV') { echo 'selected="selected"'; } ?> >Cape Verde</option> 
                                        <option value="KY" <?php if($ipcountry == 'KY') { echo 'selected="selected"'; } ?> >Cayman Islands</option> 
                                        <option value="CF" <?php if($ipcountry == 'CF') { echo 'selected="selected"'; } ?> >Central African Republic</option> 
                                        <option value="TD" <?php if($ipcountry == 'TD') { echo 'selected="selected"'; } ?> >Chad</option> 
                                        <option value="CL" <?php if($ipcountry == 'CL') { echo 'selected="selected"'; } ?> >Chile</option> 
                                        <option value="CN" <?php if($ipcountry == 'CN') { echo 'selected="selected"'; } ?> >China</option> 
                                        <option value="CX" <?php if($ipcountry == 'CX') { echo 'selected="selected"'; } ?> >Christmas Island</option> 
                                        <option value="CC" <?php if($ipcountry == 'CC') { echo 'selected="selected"'; } ?> >Cocos (Keeling) Islands</option> 
                                        <option value="CO" <?php if($ipcountry == 'CO') { echo 'selected="selected"'; } ?> >Colombia</option> 
                                        <option value="KM" <?php if($ipcountry == 'KM') { echo 'selected="selected"'; } ?> >Comoros</option> 
                                        <option value="CD" <?php if($ipcountry == 'CD') { echo 'selected="selected"'; } ?> >Congo, Democratic Republic of the</option> 
                                        <option value="CG" <?php if($ipcountry == 'CG') { echo 'selected="selected"'; } ?> >Congo, Republic of</option> 
                                        <option value="CK" <?php if($ipcountry == 'CK') { echo 'selected="selected"'; } ?> >Cook Islands</option> 
                                        <option value="CR" <?php if($ipcountry == 'CR') { echo 'selected="selected"'; } ?> >Costa Rica</option> 
                                        <option value="CI" <?php if($ipcountry == 'CI') { echo 'selected="selected"'; } ?> >Cote d'Ivoire</option> 
                                        <option value="HR" <?php if($ipcountry == 'HR') { echo 'selected="selected"'; } ?> >Croatia</option> 
                                        <option value="CU" <?php if($ipcountry == 'CU') { echo 'selected="selected"'; } ?> >Cuba</option> 
                                        <option value="CY" <?php if($ipcountry == 'CY') { echo 'selected="selected"'; } ?> >Cyprus</option> 
                                        <option value="CZ" <?php if($ipcountry == 'CZ') { echo 'selected="selected"'; } ?> >Czech Republic</option> 
                                        <option value="DK" <?php if($ipcountry == 'DK') { echo 'selected="selected"'; } ?> >Denmark</option> 
                                        <option value="DJ" <?php if($ipcountry == 'DJ') { echo 'selected="selected"'; } ?> >Djibouti</option> 
                                        <option value="DM" <?php if($ipcountry == 'DM') { echo 'selected="selected"'; } ?> >Dominica</option> 
                                        <option value="DO" <?php if($ipcountry == 'DO') { echo 'selected="selected"'; } ?> >Dominican Republic</option> 
                                        <option value="TL" <?php if($ipcountry == 'TL') { echo 'selected="selected"'; } ?> >East Timor</option> 
                                        <option value="EC" <?php if($ipcountry == 'EC') { echo 'selected="selected"'; } ?> >Ecuador</option> 
                                        <option value="EG" <?php if($ipcountry == 'EG') { echo 'selected="selected"'; } ?> >Egypt</option> 
                                        <option value="SV" <?php if($ipcountry == 'SV') { echo 'selected="selected"'; } ?> >El Salvador</option> 
                                        <option value="GQ" <?php if($ipcountry == 'GQ') { echo 'selected="selected"'; } ?> >Equatorial Guinea</option> 
                                        <option value="ER" <?php if($ipcountry == 'ER') { echo 'selected="selected"'; } ?> >Eritrea</option> 
                                        <option value="EE" <?php if($ipcountry == 'EE') { echo 'selected="selected"'; } ?> >Estonia</option> 
                                        <option value="ET" <?php if($ipcountry == 'ET') { echo 'selected="selected"'; } ?> >Ethiopia</option> 
                                        <option value="FK" <?php if($ipcountry == 'FK') { echo 'selected="selected"'; } ?> >Falkland Islands</option> 
                                        <option value="FO" <?php if($ipcountry == 'FO') { echo 'selected="selected"'; } ?> >Faroe Islands</option> 
                                        <option value="FJ" <?php if($ipcountry == 'FJ') { echo 'selected="selected"'; } ?> >Fiji</option> 
                                        <option value="FI" <?php if($ipcountry == 'FI') { echo 'selected="selected"'; } ?> >Finland</option> 
                                        <option value="" <?php if($ipcountry == '') { echo 'selected="selected"'; } ?> >Foreign</option> 
                                        <option value="FR" <?php if($ipcountry == 'FR') { echo 'selected="selected"'; } ?> >France</option> 
                                        <option value="GF" <?php if($ipcountry == 'GF') { echo 'selected="selected"'; } ?> >French Guiana</option> 
                                        <option value="PF" <?php if($ipcountry == 'PF') { echo 'selected="selected"'; } ?> >French Polynesia</option> 
                                        <option value="TF" <?php if($ipcountry == 'TF') { echo 'selected="selected"'; } ?> >French Southern Territories</option> 
                                        <option value="GA" <?php if($ipcountry == 'GA') { echo 'selected="selected"'; } ?> >Gabon</option> 
                                        <option value="GM" <?php if($ipcountry == 'GM') { echo 'selected="selected"'; } ?> >Gambia</option> 
                                        <option value="GE" <?php if($ipcountry == 'GE') { echo 'selected="selected"'; } ?> >Georgia</option> 
                                        <option value="DE" <?php if($ipcountry == 'DE') { echo 'selected="selected"'; } ?> >Germany</option> 
                                        <option value="GH" <?php if($ipcountry == 'GH') { echo 'selected="selected"'; } ?> >Ghana</option> 
                                        <option value="GI" <?php if($ipcountry == 'GI') { echo 'selected="selected"'; } ?> >Gibraltar</option> 
                                        <option value="GR" <?php if($ipcountry == 'GR') { echo 'selected="selected"'; } ?> >Greece</option> 
                                        <option value="GL" <?php if($ipcountry == 'GL') { echo 'selected="selected"'; } ?> >Greenland</option> 
                                        <option value="GD" <?php if($ipcountry == 'GD') { echo 'selected="selected"'; } ?> >Grenada</option> 
                                        <option value="GP" <?php if($ipcountry == 'GP') { echo 'selected="selected"'; } ?> >Guadeloupe</option> 
                                        <option value="GU" <?php if($ipcountry == 'GU') { echo 'selected="selected"'; } ?> >Guam</option> 
                                        <option value="GT" <?php if($ipcountry == 'GT') { echo 'selected="selected"'; } ?> >Guatemala</option> 
                                        <option value="GG" <?php if($ipcountry == 'GG') { echo 'selected="selected"'; } ?> >Guernsey</option> 
                                        <option value="GN" <?php if($ipcountry == 'GN') { echo 'selected="selected"'; } ?> >Guinea</option> 
                                        <option value="GW" <?php if($ipcountry == 'GW') { echo 'selected="selected"'; } ?> >Guinea-Bissau</option> 
                                        <option value="GY" <?php if($ipcountry == 'GY') { echo 'selected="selected"'; } ?> >Guyana</option> 
                                        <option value="HT" <?php if($ipcountry == 'HT') { echo 'selected="selected"'; } ?> >Haiti</option> 
                                        <option value="HM" <?php if($ipcountry == 'HM') { echo 'selected="selected"'; } ?> >Heard and McDonald Islands</option> 
                                        <option value="VA" <?php if($ipcountry == 'VA') { echo 'selected="selected"'; } ?> >HOLY SEE (VATICAN CITY STATE)</option> 
                                        <option value="HN" <?php if($ipcountry == 'HN') { echo 'selected="selected"'; } ?> >Honduras</option> 
                                        <option value="HK" <?php if($ipcountry == 'HK') { echo 'selected="selected"'; } ?> >Hong Kong</option> 
                                        <option value="HU" <?php if($ipcountry == 'HU') { echo 'selected="selected"'; } ?> >Hungary</option> 
                                        <option value="IS" <?php if($ipcountry == 'IS') { echo 'selected="selected"'; } ?> >Iceland</option> 
                                        <option value="IN" <?php if($ipcountry == 'IN') { echo 'selected="selected"'; } ?> >India</option> 
                                        <option value="ID" <?php if($ipcountry == 'ID') { echo 'selected="selected"'; } ?> >Indonesia</option> 
                                        <option value="IR" <?php if($ipcountry == 'IR') { echo 'selected="selected"'; } ?> >Iran</option> 
                                        <option value="IQ" <?php if($ipcountry == 'IQ') { echo 'selected="selected"'; } ?> >Iraq</option> 
                                        <option value="IE" <?php if($ipcountry == 'IE') { echo 'selected="selected"'; } ?> >Ireland</option> 
                                        <option value="IM" <?php if($ipcountry == 'IM') { echo 'selected="selected"'; } ?> >Isle of Man</option> 
                                        <option value="IL" <?php if($ipcountry == 'IL') { echo 'selected="selected"'; } ?> >Israel</option> 
                                        <option value="IT" <?php if($ipcountry == 'IT') { echo 'selected="selected"'; } ?> >Italy</option> 
                                        <option value="JM" <?php if($ipcountry == 'JM') { echo 'selected="selected"'; } ?> >Jamaica</option> 
                                        <option value="JP" <?php if($ipcountry == 'JP') { echo 'selected="selected"'; } ?> >Japan</option> 
                                        <option value="JE" <?php if($ipcountry == 'JE') { echo 'selected="selected"'; } ?> >Jersey</option> 
                                        <option value="JO" <?php if($ipcountry == 'JO') { echo 'selected="selected"'; } ?> >Jordan</option> 
                                        <option value="KZ" <?php if($ipcountry == 'KZ') { echo 'selected="selected"'; } ?> >Kazakhstan</option> 
                                        <option value="KE" <?php if($ipcountry == 'KE') { echo 'selected="selected"'; } ?> >Kenya</option> 
                                        <option value="KI" <?php if($ipcountry == 'KI') { echo 'selected="selected"'; } ?> >Kiribati</option> 
                                        <option value="KW" <?php if($ipcountry == 'KW') { echo 'selected="selected"'; } ?> >Kuwait</option> 
                                        <option value="KG" <?php if($ipcountry == 'KG') { echo 'selected="selected"'; } ?> >Kyrgyzstan</option> 
                                        <option value="LA" <?php if($ipcountry == 'LA') { echo 'selected="selected"'; } ?> >Laos</option> 
                                        <option value="LV" <?php if($ipcountry == 'LV') { echo 'selected="selected"'; } ?> >Latvia</option> 
                                        <option value="LB" <?php if($ipcountry == 'LB') { echo 'selected="selected"'; } ?> >Lebanon</option> 
                                        <option value="LS" <?php if($ipcountry == 'LS') { echo 'selected="selected"'; } ?> >Lesotho</option> 
                                        <option value="LR" <?php if($ipcountry == 'LR') { echo 'selected="selected"'; } ?> >Liberia</option> 
                                        <option value="LY" <?php if($ipcountry == 'LY') { echo 'selected="selected"'; } ?> >Libya</option> 
                                        <option value="LI" <?php if($ipcountry == 'LI') { echo 'selected="selected"'; } ?> >Liechtenstein</option> 
                                        <option value="LT" <?php if($ipcountry == 'LT') { echo 'selected="selected"'; } ?> >Lithuania</option> 
                                        <option value="LU" <?php if($ipcountry == 'LU') { echo 'selected="selected"'; } ?> >Luxembourg</option> 
                                        <option value="MO" <?php if($ipcountry == 'MO') { echo 'selected="selected"'; } ?> >Macao</option> 
                                        <option value="MK" <?php if($ipcountry == 'MK') { echo 'selected="selected"'; } ?> >Macedonia</option> 
                                        <option value="MG" <?php if($ipcountry == 'MG') { echo 'selected="selected"'; } ?> >Madagascar</option> 
                                        <option value="MW" <?php if($ipcountry == 'MW') { echo 'selected="selected"'; } ?> >Malawi</option> 
                                        <option value="MY" <?php if($ipcountry == 'MY') { echo 'selected="selected"'; } ?> >Malaysia</option> 
                                        <option value="MV" <?php if($ipcountry == 'MV') { echo 'selected="selected"'; } ?> >Maldives</option> 
                                        <option value="ML" <?php if($ipcountry == 'ML') { echo 'selected="selected"'; } ?> >Mali</option> 
                                        <option value="MT" <?php if($ipcountry == 'MT') { echo 'selected="selected"'; } ?> >Malta</option> 
                                        <option value="MH" <?php if($ipcountry == 'MH') { echo 'selected="selected"'; } ?> >Marshall Islands</option> 
                                        <option value="MQ" <?php if($ipcountry == 'MQ') { echo 'selected="selected"'; } ?> >Martinique</option> 
                                        <option value="MR" <?php if($ipcountry == 'MR') { echo 'selected="selected"'; } ?> >Mauritania</option> 
                                        <option value="MU" <?php if($ipcountry == 'MU') { echo 'selected="selected"'; } ?> >Mauritius</option> 
                                        <option value="YT" <?php if($ipcountry == 'YT') { echo 'selected="selected"'; } ?> >Mayotte</option> 
                                        <option value="MX" <?php if($ipcountry == 'MX') { echo 'selected="selected"'; } ?> >Mexico</option> 
                                        <option value="FM" <?php if($ipcountry == 'FM') { echo 'selected="selected"'; } ?> >Micronesia, Federal State of</option> 
                                        <option value="MD" <?php if($ipcountry == 'MD') { echo 'selected="selected"'; } ?> >Moldova, Republic of</option> 
                                        <option value="MC" <?php if($ipcountry == 'MC') { echo 'selected="selected"'; } ?> >Monaco</option> 
                                        <option value="MN" <?php if($ipcountry == 'MN') { echo 'selected="selected"'; } ?> >Mongolia</option> 
                                        <option value="MS" <?php if($ipcountry == 'MS') { echo 'selected="selected"'; } ?> >Montserrat</option> 
                                        <option value="MA" <?php if($ipcountry == 'MA') { echo 'selected="selected"'; } ?> >Morocco</option> 
                                        <option value="MZ" <?php if($ipcountry == 'MZ') { echo 'selected="selected"'; } ?> >Mozambique</option> 
                                        <option value="MM" <?php if($ipcountry == 'MM') { echo 'selected="selected"'; } ?> >Myanmar</option> 
                                        <option value="NA" <?php if($ipcountry == 'NA') { echo 'selected="selected"'; } ?> >Namibia</option> 
                                        <option value="NR" <?php if($ipcountry == 'NR') { echo 'selected="selected"'; } ?> >Nauru</option> 
                                        <option value="NP" <?php if($ipcountry == 'NP') { echo 'selected="selected"'; } ?> >Nepal</option> 
                                        <option value="NL" <?php if($ipcountry == 'NL') { echo 'selected="selected"'; } ?> >Netherlands</option> 
                                        <option value="AN" <?php if($ipcountry == 'AN') { echo 'selected="selected"'; } ?> >Netherlands Antilles</option> 
                                        <option value="NC" <?php if($ipcountry == 'NC') { echo 'selected="selected"'; } ?> >New Caledonia</option> 
                                        <option value="NZ" <?php if($ipcountry == 'NZ') { echo 'selected="selected"'; } ?> >New Zealand</option> 
                                        <option value="NI" <?php if($ipcountry == 'NI') { echo 'selected="selected"'; } ?> >Nicaragua</option> 
                                        <option value="NE" <?php if($ipcountry == 'NE') { echo 'selected="selected"'; } ?> >Niger</option> 
                                        <option value="NG" <?php if($ipcountry == 'NG') { echo 'selected="selected"'; } ?> >Nigeria</option> 
                                        <option value="NU" <?php if($ipcountry == 'NU') { echo 'selected="selected"'; } ?> >Niue</option> 
                                        <option value="NF" <?php if($ipcountry == 'NF') { echo 'selected="selected"'; } ?> >Norfolk Island</option> 
                                        <option value="KP" <?php if($ipcountry == 'KP') { echo 'selected="selected"'; } ?> >North Korea</option> 
                                        <option value="MP" <?php if($ipcountry == 'MP') { echo 'selected="selected"'; } ?> >Northern Mariana Islands</option> 
                                        <option value="NO" <?php if($ipcountry == 'NO') { echo 'selected="selected"'; } ?> >Norway</option> 
                                        <option value="OM" <?php if($ipcountry == 'OM') { echo 'selected="selected"'; } ?> >Oman</option> 
                                        <option value="PK" <?php if($ipcountry == 'PK') { echo 'selected="selected"'; } ?> >Pakistan</option> 
                                        <option value="PW" <?php if($ipcountry == 'PW') { echo 'selected="selected"'; } ?> >Palau</option> 
                                        <option value="PS" <?php if($ipcountry == 'PS') { echo 'selected="selected"'; } ?> >Palestinian Territories</option> 
                                        <option value="PA" <?php if($ipcountry == 'PA') { echo 'selected="selected"'; } ?> >Panama</option> 
                                        <option value="PG" <?php if($ipcountry == 'PG') { echo 'selected="selected"'; } ?> >Papua New Guinea</option> 
                                        <option value="PY" <?php if($ipcountry == 'PY') { echo 'selected="selected"'; } ?> >Paraguay</option> 
                                        <option value="PE" <?php if($ipcountry == 'PE') { echo 'selected="selected"'; } ?> >Peru</option> 
                                        <option value="PH" <?php if($ipcountry == 'PH') { echo 'selected="selected"'; } ?> >Philippines</option> 
                                        <option value="PN" <?php if($ipcountry == 'PN') { echo 'selected="selected"'; } ?> >Pitcairn Island</option> 
                                        <option value="PL" <?php if($ipcountry == 'PL') { echo 'selected="selected"'; } ?> >Poland</option> 
                                        <option value="PT" <?php if($ipcountry == 'PT') { echo 'selected="selected"'; } ?> >Portugal</option> 
                                        <option value="PR" <?php if($ipcountry == 'PR') { echo 'selected="selected"'; } ?> >Puerto Rico</option> 
                                        <option value="QA" <?php if($ipcountry == 'QA') { echo 'selected="selected"'; } ?> >Qatar</option> 
                                        <option value="RE" <?php if($ipcountry == 'RE') { echo 'selected="selected"'; } ?> >Reunion Island</option> 
                                        <option value="RO" <?php if($ipcountry == 'RO') { echo 'selected="selected"'; } ?> >Romania</option> 
                                        <option value="RU" <?php if($ipcountry == 'RU') { echo 'selected="selected"'; } ?> >Russian Federation</option> 
                                        <option value="RW" <?php if($ipcountry == 'RW') { echo 'selected="selected"'; } ?> >Rwanda</option> 
                                        <option value="KN" <?php if($ipcountry == 'KN') { echo 'selected="selected"'; } ?> >Saint Kitts and Nevis</option> 
                                        <option value="LC" <?php if($ipcountry == 'LC') { echo 'selected="selected"'; } ?> >Saint Lucia</option> 
                                        <option value="VC" <?php if($ipcountry == 'VC') { echo 'selected="selected"'; } ?> >Saint Vincent and the Grenadines</option> 
                                        <option value="SM" <?php if($ipcountry == 'SM') { echo 'selected="selected"'; } ?> >San Marino</option> 
                                        <option value="ST" <?php if($ipcountry == 'ST') { echo 'selected="selected"'; } ?> >Sao Tome and Principe</option> 
                                        <option value="SA" <?php if($ipcountry == 'SA') { echo 'selected="selected"'; } ?> >Saudi Arabia</option> 
                                        <option value="SN" <?php if($ipcountry == 'SN') { echo 'selected="selected"'; } ?> >Senegal</option> 
                                        <option value="RS" <?php if($ipcountry == 'RS') { echo 'selected="selected"'; } ?> >Serbia</option> 
                                        <option value="CS" <?php if($ipcountry == 'CS') { echo 'selected="selected"'; } ?> >Serbia & Montenegro</option> 
                                        <option value="SC" <?php if($ipcountry == 'SC') { echo 'selected="selected"'; } ?> >Seychelles</option> 
                                        <option value="SL" <?php if($ipcountry == 'SL') { echo 'selected="selected"'; } ?> >Sierra Leone</option> 
                                        <option value="SG" <?php if($ipcountry == 'SG') { echo 'selected="selected"'; } ?> >Singapore</option> 
                                        <option value="SK" <?php if($ipcountry == 'SK') { echo 'selected="selected"'; } ?> >Slovakia</option> 
                                        <option value="SI" <?php if($ipcountry == 'SI') { echo 'selected="selected"'; } ?> >Slovenia</option> 
                                        <option value="SB" <?php if($ipcountry == 'SB') { echo 'selected="selected"'; } ?> >Solomon Islands</option> 
                                        <option value="SO" <?php if($ipcountry == 'SO') { echo 'selected="selected"'; } ?> >Somalia</option> 
                                        <option value="ZA" <?php if($ipcountry == 'ZA') { echo 'selected="selected"'; } ?> >South Africa</option> 
                                        <option value="GS" <?php if($ipcountry == 'GS') { echo 'selected="selected"'; } ?> >South Georgia and the South Sandwich Islands</option> 
                                        <option value="KR" <?php if($ipcountry == 'KR') { echo 'selected="selected"'; } ?> >South Korea</option> 
                                        <option value="ES" <?php if($ipcountry == 'ES') { echo 'selected="selected"'; } ?> >Spain</option> 
                                        <option value="LK" <?php if($ipcountry == 'LK') { echo 'selected="selected"'; } ?> >Sri Lanka</option> 
                                        <option value="SH" <?php if($ipcountry == 'SH') { echo 'selected="selected"'; } ?> >St. Helena</option> 
                                        <option value="WL" <?php if($ipcountry == 'WL') { echo 'selected="selected"'; } ?> >St. Lucia</option> 
                                        <option value="PM" <?php if($ipcountry == 'PM') { echo 'selected="selected"'; } ?> >St. Pierre & Miquelon</option> 
                                        <option value="WV" <?php if($ipcountry == 'WV') { echo 'selected="selected"'; } ?> >St. Vincent & The Grenadines</option> 
                                        <option value="SD" <?php if($ipcountry == 'SD') { echo 'selected="selected"'; } ?> >Sudan</option> 
                                        <option value="SR" <?php if($ipcountry == 'SR') { echo 'selected="selected"'; } ?> >Suriname</option> 
                                        <option value="SJ" <?php if($ipcountry == 'SJ') { echo 'selected="selected"'; } ?> >Svalbard and Jan Mayen Islands</option> 
                                        <option value="SZ" <?php if($ipcountry == 'SZ') { echo 'selected="selected"'; } ?> >Swaziland</option> 
                                        <option value="SE" <?php if($ipcountry == 'SE') { echo 'selected="selected"'; } ?> >Sweden</option> 
                                        <option value="CH" <?php if($ipcountry == 'CH') { echo 'selected="selected"'; } ?> >Switzerland</option> 
                                        <option value="SY" <?php if($ipcountry == 'SY') { echo 'selected="selected"'; } ?> >Syrian Arab Republic</option> 
                                        <option value="TW" <?php if($ipcountry == 'TW') { echo 'selected="selected"'; } ?> >Taiwan</option> 
                                        <option value="TJ" <?php if($ipcountry == 'TJ') { echo 'selected="selected"'; } ?> >Tajikistan</option> 
                                        <option value="TZ" <?php if($ipcountry == 'TZ') { echo 'selected="selected"'; } ?> >Tanzania</option> 
                                        <option value="TH" <?php if($ipcountry == 'TH') { echo 'selected="selected"'; } ?> >Thailand</option> 
                                        <option value="TG" <?php if($ipcountry == 'TG') { echo 'selected="selected"'; } ?> >Togo</option> 
                                        <option value="TK" <?php if($ipcountry == 'TK') { echo 'selected="selected"'; } ?> >Tokelau</option> 
                                        <option value="TO" <?php if($ipcountry == 'TO') { echo 'selected="selected"'; } ?> >Tonga</option> 
                                        <option value="TT" <?php if($ipcountry == 'TT') { echo 'selected="selected"'; } ?> >Trinidad and Tobago</option> 
                                        <option value="TA" <?php if($ipcountry == 'TA') { echo 'selected="selected"'; } ?> >Tristan da Cunha</option> 
                                        <option value="TN" <?php if($ipcountry == 'TN') { echo 'selected="selected"'; } ?> >Tunisia</option> 
                                        <option value="TR" <?php if($ipcountry == 'TR') { echo 'selected="selected"'; } ?> >Turkey</option> 
                                        <option value="TM" <?php if($ipcountry == 'TM') { echo 'selected="selected"'; } ?> >Turkmenistan</option> 
                                        <option value="TC" <?php if($ipcountry == 'TC') { echo 'selected="selected"'; } ?> >Turks and Caicos Islands</option> 
                                        <option value="TV" <?php if($ipcountry == 'TV') { echo 'selected="selected"'; } ?> >Tuvalu</option> 
                                        <option value="UG" <?php if($ipcountry == 'UG') { echo 'selected="selected"'; } ?> >Uganda</option> 
                                        <option value="UA" <?php if($ipcountry == 'UA') { echo 'selected="selected"'; } ?> >Ukraine</option> 
                                        <option value="AE" <?php if($ipcountry == 'AE') { echo 'selected="selected"'; } ?> >United Arab Emirates</option> 
                                        <option value="GB" <?php if($ipcountry == 'GB') { echo 'selected="selected"'; } ?> >United Kingdom</option> 
                                        <option value="UY" <?php if($ipcountry == 'UY') { echo 'selected="selected"'; } ?> >Uruguay</option> 
                                        <option value="UM" <?php if($ipcountry == 'UM') { echo 'selected="selected"'; } ?> >US Minor Outlying Islands</option> 
                                        <option value="UZ" <?php if($ipcountry == 'UZ') { echo 'selected="selected"'; } ?> >Uzbekistan</option> 
                                        <option value="VU" <?php if($ipcountry == 'VU') { echo 'selected="selected"'; } ?> >Vanuatu</option> 
                                        <option value="VE" <?php if($ipcountry == 'VE') { echo 'selected="selected"'; } ?> >Venezuela</option> 
                                        <option value="VN" <?php if($ipcountry == 'VN') { echo 'selected="selected"'; } ?> >Vietnam</option> 
                                        <option value="VG" <?php if($ipcountry == 'VG') { echo 'selected="selected"'; } ?> >Virgin Islands (British)</option> 
                                        <option value="VI" <?php if($ipcountry == 'VI') { echo 'selected="selected"'; } ?> >Virgin Islands (USA)</option> 
                                        <option value="WF" <?php if($ipcountry == 'WF') { echo 'selected="selected"'; } ?> >Wallis and Futuna Islands</option> 
                                        <option value="EH" <?php if($ipcountry == 'EH') { echo 'selected="selected"'; } ?> >Western Sahara</option> 
                                        <option value="WS" <?php if($ipcountry == 'WS') { echo 'selected="selected"'; } ?> >Western Samoa</option> 
                                        <option value="YE" <?php if($ipcountry == 'YE') { echo 'selected="selected"'; } ?> >Yemen</option> 
                                        <option value="YU" <?php if($ipcountry == 'YU') { echo 'selected="selected"'; } ?> >Yugoslavia</option> 
                                        <option value="ZM" <?php if($ipcountry == 'ZM') { echo 'selected="selected"'; } ?> >Zambia</option> 
                                        <option value="ZW" <?php if($ipcountry == 'ZW') { echo 'selected="selected"'; } ?> >Zimbabwe</option> 
                                        <option value="ME" <?php if($ipcountry == 'ME') { echo 'selected="selected"'; } ?> >Montenegro</option> 
                                        <option value="MF" <?php if($ipcountry == 'MF') { echo 'selected="selected"'; } ?> >Saint Martin</option> 
                                        <option value="BL" <?php if($ipcountry == 'BL') { echo 'selected="selected"'; } ?> >Saint Barth</option> 
                                    </select>
                                </td>
                            </tr>
                            <?php /*
                            <tr>
                                <td>
                                    <fieldset onclick="var selected = [];
                                        $('[name=DeliveryMethod]:checked').each( function() { 
                                            selected[selected.length] = $(this).val();
                                        });
                                        if (selected.length == 1) { 
                                            $('#LeadTypeId').val(selected[0]) 
                                        } 
                                        else if (selected.length == 2) {
                                            if (($.inArray('12', selected, 0) > -1) && ($.inArray('16', selected, 0) > -1)) { 
                                                $('#LeadTypeId').val(19) 
                                            } else if (($.inArray('12', selected, 0) > -1) && ($.inArray('21', selected, 0) > -1)) {
                                                $('#LeadTypeId').val(22) 
                                            } else if (($.inArray('16', selected, 0) > -1) && ($.inArray('21', selected, 0) > -1)) {
                                                $('#LeadTypeId').val(18) 
                                            } 
                                        } else if (selected.length == 3) { 
                                            $('#LeadTypeId').val(20) 
                                        } else { 
                                            $('#LeadTypeId').val('') 
                                        };"> 
                                        <label> Please select the method of delivery for the free brochure and dvd </label>
                                        <label class="inline selected" for="DeliveryMethod[0]"><input type="checkbox" checked="checked" id="DeliveryMethod[0]" name="DeliveryMethod" class="mailCheck" value="12" /> Download Brochure</label><br />
                                        <label class="inline" for="DeliveryMethod[1]"><input id="DeliveryMethod[1]" name="DeliveryMethod" type="checkbox" value="21" class="mailCheck" /> Send me a Brochure</label><br />
                                        <label class="inline" for="DeliveryMethod[2]"><input id="DeliveryMethod[2]" name="DeliveryMethod" type="checkbox" value="16" class="mailCheck" /> Send me a DVD</label><br />
                                    </fieldset>
                                </td>
                            </tr>
                            */ ?>
                            <tr>
                                <td class="gaptop">
                                    <label class="w170"> Do you currently own, or have you ever owned a hot tub? </label><br />
                                    <input type="radio" id="custom_current_owner" name="CustomData[CurrentOwner]" value="Yes" /> Yes &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="radio" id="custom_current_owner" name="CustomData[CurrentOwner]" value="No" /> No
                                    <?php /*
                                    <select name="CustomData[CurrentOwner]" class="w170 select"> 
                                        <option value="Yes">Yes</option> 
                                        <option value="No" selected>No</option> 
                                    </select>
                                    */ ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="gaptop">
                                    <label class="w170"> Which product are you interested in purchasing most? </label><br />
                                    <select name="ProductIdList" class="w170 select"> 
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
                            <tr>
                                <td class="gaptop">
                                    <label class="w170"> When do you plan to purchase a hot tub? </label><br />
                                    <fieldset class="control large">  
                                            <label for="CustomData"><input name="CustomData[BuyTimeFrame]" value="Not sure" type="radio"/>  Not sure </label> <br />
                                            <label for="CustomData"><input name="CustomData[BuyTimeFrame]" value="Within 1 month" type="radio"/>  Within 1 month </label> <br />
                                            <label for="CustomData"><input name="CustomData[BuyTimeFrame]" value="1-3 months" type="radio"/>  1-3 months </label> <br />
                                            <label for="CustomData"><input name="CustomData[BuyTimeFrame]" value="4-6 months" type="radio"/>  4-6 months </label> <br />
                                            <label for="CustomData"><input name="CustomData[BuyTimeFrame]" value="6+ months" type="radio"/>  6+ months </label> <br />
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <td class="gaptop">
                                    <label class="w170"> What is the primary reason you are considering the purchase of a hot tub? </label><br />
                                    <fieldset class="control large">  
                                            <label for="CustomData"><input name="CustomData[ProductUse]" value="Relaxation" type="radio"/>  Relaxation </label> <br />
                                            <label for="CustomData"><input name="CustomData[ProductUse]" value="Pain Relief/Therapy" type="radio"/>  Pain Relief/Therapy </label> <br />
                                            <label for="CustomData"><input name="CustomData[ProductUse]" value="Bonding/Family" type="radio"/>  Bonding/Family </label> <br />
                                            <label for="CustomData"><input name="CustomData[ProductUse]" value="Backyard Entertaining" type="radio"/>  Backyard Entertaining </label> <br />
                                    </fieldset>
                                </td>
                            </tr>
                            <tr class="divider">
                                <td></td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="ReceiveEmailCampaigns" class="w170"><input class="editor choice" name="ReceiveEmailCampaigns" value="true" type="checkbox" />  Please send me exclusive sale alerts from Sundance Spas</label>
                                </td>
                            </tr>
                            <tr class="divider">
                                <td></td>
                            </tr>
                            <tr>
                                <td>
                                    <input data-val="true" data-val-number="The field LeadTypeId must be a number." data-val-required="The LeadTypeId field is required." id="LeadTypeId" name="LeadTypeId" type="hidden" value="10" />
                                    <input type="submit" class="button" value="SUBMIT" />
                                </td>
                            </tr>
                        </table>
                        <p class="note"><span class="rqd">*</span> Fields with an asterisk are required.<br />&nbsp;</p>
                    </form>

<h3>Share This</h3>
<div class="share">
<?php if(function_exists('sharethis_button')) sharethis_button(); ?>
</div>
</div>
</div>
</div>
<br class="clear" />
<?php get_footer(); ?>
