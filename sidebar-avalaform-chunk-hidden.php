<?php

/* sidebar: avalaform-chunk-hidden 

// If value is not set by default on form page, let's do some GETs to control lead types and categories if passed in URL, otherwise use these defaults
    if ( !isset($leadCategoryId) )
        $leadCategoryId = 6;
    if ( !isset($leadSourceId) )
        $leadSourceId = (!empty($_GET['s_cid']) && $_GET['s_cid'] > 0) ? 12 : 11;
    $leadId = ( !empty($_GET['lid']) ) ? $_GET['lid'] : null;
    $campaignId = ( !empty($_GET['s_cid']) ) ? $_GET['s_cid'] : 5;
    $id = 84;
    $is_ppc = (!empty($_GET['s_cid']) && $_GET['s_cid'] > 0) ? 'ppc' : parse_google_referrers('utmcmd');

    //organic link versus ppc
    if (strtolower($_GET['sa']) == 'l') {
        //yes, it is ppc
        $leadSourceId = 12;
        $is_ppc = 'ppc';
    }
    if (strtolower($_GET['sa']) == 't') {
        //no, it is not ppc
        $leadSourceId = 11;
    }
    $pagesSoFar = !parse_history_tracker() ? '<a href=\''.get_permalink().'\' target=\'_blank\'>'.get_the_title().'</a>' : parse_history_tracker('url').'<a href=\''.get_permalink().'\' target=\'_blank\'>'.get_the_title().'</a>';
    $pageCountSoFar = parse_history_tracker('count') +1;
?>

<script>
//script compares moment of submit button click to first visit per history track computation
jQuery(document).ready(function($) { 
    $('form').submit(function(e){
        var tos1 = $('#tos1').val();
        var stamp = new Date();
        var tos2 = stamp.getTime() / 1000;
        var tos = tos2 - tos1;
        var mins = Math.floor(tos / 60);
        var secs = Math.floor(tos - (mins * 60));
        //var uitime = mins + " min, " + secs + " sec";
        var h = Math.floor(tos / 3600);
        var m = Math.floor(tos % 3600 / 60);
        var s = Math.floor(tos % 3600 % 60);
        var timestr = ((h > 0 ? h + ":" : "0:") + (m > 0 ? (m < 10 ? "0" : "") + m + ":" : "00:") + (s > 0 ? (s < 10 ? "0" : "") + s : "00"));
        $('#TimeOnSite').val(timestr);
        $('#tos2').val(tos2);
    });
})
</script>


    <input data-val="true" data-val-number="The field Id must be a number." data-val-required="The Id field is required." id="Id" name="Id" type="hidden" value="<?php echo $id; ?>" />
    <input data-val="true" data-val-number="The field CampaignId must be a number." data-val-required="The CampaignId field is required." id="CampaignId" name="CampaignId" type="hidden" value="<?php echo $campaignId; ?>" />
    <input data-val="true" data-val-number="The field LeadSourceId must be a number." data-val-required="The LeadSourceId field is required." id="LeadSourceId" name="LeadSourceId" type="hidden" value="<?php echo $leadSourceId; ?>" />
    <input data-val="true" data-val-number="The field LeadCategoryId must be a number." data-val-required="The LeadCategoryId field is required." id="LeadCategoryId" name="LeadCategoryId" type="hidden" value="<?php echo $leadCategoryId; ?>" />

    <input id="WebSessionData" name="WebSessionData" type="hidden" value="" />
    <input id="WebSessionData" name="WebSessionData[PayoffLeft]" type="hidden" value="" />
    <input id="WebSessionData" name="WebSessionData[Medium]" type="hidden" value="<?php echo $is_ppc; ?>" />
    <input id="WebSessionData" name="WebSessionData[KeyWords]" type="hidden" value="<?php echo parse_google_referrers('utmctr'); ?>" />
    <input id="WebSessionData" name="WebSessionData[VisitCount]" type="hidden" value="<?php echo parse_google_visits(); ?>" />
    <input id="WebSessionData" name="WebSessionData[PageViews]" type="hidden" value="<?php echo $pageCountSoFar; ?>" />
    <input id="TimeOnSite" name="WebSessionData[TimeOnSite]" type="hidden" value="" />
    <input id="WebSessionData" name="WebSessionData[PagesViewed]" type="hidden" value="<?php echo addslashes(htmlspecialchars(parse_history_tracker('url'))) ; ?>" />
    <input id="WebSessionData" name="WebSessionData[DeliveryMethod]" type="hidden" value="<?php echo parse_google_referrers('utmcsr'); ?>" />

    <input type="hidden" name="CustomData[ReferringPage]" value="<?php echo wp_get_referer(); ?>" />
    <input type="hidden" name="CustomData[FormPage]" value="<?php echo get_the_title(); ?>" />
    <input id="tos1" type="hidden" name="SiteEntryTimestamp" value="<?php $v = (parse_history_tracker('entry') == false) ? ( parse_google_tos()>0 ? parse_google_tos() : time() ) : parse_history_tracker('entry'); echo addslashes($v); ?>" />
    <input id="tos2" type="hidden" name="FormCompletedTimestamp" value="" />

    <input type="hidden" name="fromurl" value="<?php echo get_permalink(); ?>" />

    <input type="hidden" name="LeadId" id="LeadId" value="<?php echo $leadId; ?>" />
    <input type="hidden" name="DealerId" value="" />
    <input type="hidden" name="DealerNumber" value="" />
    <input type="hidden" name="ExactTargetOptInListIds" value="" />
    <input type="hidden" name="ExactTargetCustomAttributes" value="" />

    <input type="hidden" name="County" value="" />
    <input type="hidden" name="District" value="" />

    <input type="hidden" name="HomePhone" value="" />
    <input type="hidden" name="MobilePhone" value="" />
    <input type="hidden" name="WorkPhone" value="" />

    <input type="hidden" name="ReceiveSmsCampaigns" value="" />
    <input type="hidden" name="ReceiveNewsletter" value="" />

*/


avala_hidden_fields();

?>