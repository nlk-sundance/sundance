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
<div class="hd ds">
    <div class="wrap">
        <div class="logo">
            <h1><a href="<?php bloginfo('url'); ?>">Sundance Spas&reg;</a></h1>
            <span>Feel the Sundance Difference</span>
        </div>
        <div>
            <a id="show-locator" class="awareness-button b225">Locate Your Nearest Dealer</a>
        </div>
    </div>
</div>
<div class="dd ds">
    <div class="wrap drop-down">
        <form action="/hot-tub-dealer-locator/locators/cities/" method="post" name="locform" id="locform" onsubmit="document.getElementById('zipcodeSearch').value=1;">
            <input type="hidden" name="zipcodeSearch" id="zipcodeSearch" value="0">

            <div>
                <label for="zip">Zip / Postal Code</label>
                <input type="text" maxlength="7" id="zip" class="text" name="data[Dealer][zip]">
            </div>

            <div>
                <label for="country">Country</label>
                <select name="data[Dealer][country_id]" id="country">
                <option value="1" selected="selected">United States</option>
                <option value="3">Canada</option>
                <option value="70"></option>
                <option value="101"></option>
                <option value="112">Algeria</option>
                <option value="81">Andorra</option>
                <option value="34">Argentina</option>
                <option value="5">Australia</option>
                <option value="45">Austria</option>
                <option value="21">Barbados</option>
                <option value="79">Belarus</option>
                <option value="11">Belgium</option>
                <option value="26">Bermuda</option>
                <option value="93">Bosnia / Herzegovina</option>
                <option value="43">Bulgaria</option>
                <option value="3">Canada</option>
                <option value="66">Canary Islands</option>
                <option value="103">Caribbean Island Region</option>
                <option value="32">Chile</option>
                <option value="4">China</option>
                <option value="60">Costa Rica</option>
                <option value="49">Croatia</option>
                <option value="63">Cyprus</option>
                <option value="46">Czech Republic</option>
                <option value="22">Denmark</option>
                <option value="54">Egypt</option>
                <option value="96">Estonia</option>
                <option value="50">Finland</option>
                <option value="105">Finland</option>
                <option value="41">France</option>
                <option value="59">French Polynesia</option>
                <option value="39">Germany</option>
                <option value="98">Ghana</option>
                <option value="44">Greece</option>
                <option value="83">Guadeloupe</option>
                <option value="106">Guam</option>
                <option value="84">Guyane Francaise</option>
                <option value="108">Hong Kong</option>
                <option value="47">Hungary</option>
                <option value="18">Iceland</option>
                <option value="56">India</option>
                <option value="76">Indonesia</option>
                <option value="53">Ireland</option>
                <option value="40">Israel</option>
                <option value="35">Italy</option>
                <option value="6">Japan</option>
                <option value="99">Kenya</option>
                <option value="62">Korea</option>
                <option value="85">La Reunion</option>
                <option value="19">Latvia</option>
                <option value="95">Liechtenstein</option>
                <option value="78">Lithuania</option>
                <option value="71">Luxembourg</option>
                <option value="69">Malaysia</option>
                <option value="64">Maldives</option>
                <option value="58">Malta</option>
                <option value="82">Martinique</option>
                <option value="2">Mexico</option>
                <option value="80">Monaco</option>
                <option value="102">Montenegro</option>
                <option value="100">Morocco</option>
                <option value="33">New Zealand</option>
                <option value="111">Nigeria</option>
                <option value="15">Norway</option>
                <option value="104">Pakistan</option>
                <option value="38">Philippines</option>
                <option value="30">Poland</option>
                <option value="109">Portugal</option>
                <option value="27">Puerto Rico</option>
                <option value="92">Republic of Panama</option>
                <option value="20">Romania</option>
                <option value="7">Russia</option>
                <option value="37">Singapore</option>
                <option value="48">Slovakia</option>
                <option value="89">Slovenia</option>
                <option value="16">Spain</option>
                <option value="65">Sri Lanka</option>
                <option value="55">St. Vincent &amp; The Grenadines</option>
                <option value="42">Sweden</option>
                <option value="31">Switzerland</option>
                <option value="57">Taiwan</option>
                <option value="36">Thailand</option>
                <option value="91">The Netherlands</option>
                <option value="97">The Netherlands Antilles</option>
                <option value="110">Turkey</option>
                <option value="86">Ukraine</option>
                <option value="107">United Arab Emirates</option>
                <option value="23">United Kingdom</option>
                <option value="1">United States</option>
                <option value="13">Uruguay</option>
                <option value="94">US Virgin Islands</option>
                <option value="77">Vietnam</option>
                </select>
            </div>

            <div>
                <input src="<?php echo get_template_directory_uri(); ?>/images/dealer-locator/locate-now-btn.png" value="submit" type="image">
            </div>

            <span class="close">
                <a id="hide-locator" src="<?php echo get_template_directory_uri(); ?>/images/dealer-locator/close.png">Close</a>
            </span>
        </form>
    </div>
</div>
    
<div class="ft ds">
    <div class="wrap">
        <div class="inner">
            <div class="logo">
                <p>&#169;<?php echo date('Y'); ?> Sundance Spas - all rights reserved</p>
            </div>
            <br class="clear" />
        </div>
    </div>
</div>
<?php get_footer('block_newsletter'); ?>

<?php
	/* Always have wp_footer() just before the closing </body>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to reference JavaScript files.
	 */

	wp_footer();
?>

</body>
</html>
