<?php
/*	AVALA / AIMBASE FORM - SUBMIT TO API
 *
 *	avala_form_submit() to be included at beginning of any Form pages to submit
 *		- Update URL defines in function as needed
 *	avala_db_backup() called in form submit function
 *		- Update database connection details as necessary
 *	avala_hidden_fields() to be included/required inside <form> tags
 *
 */

/* - - - - - - - - - - SUNDANCE SPECIFICS - - - - - - - - - - */
	// Default ID #s for Source, Category, Type
	define( 'LEAD_SOURCE_ID', 13 );
	define( 'LEAD_CATEGORY_ID', 6 );
	define( 'LEAD_TYPE_ID', 13 );
	define( 'LEAD_SOURCE_ID_PPC', 12 );
	define( 'LEAD_SOURCE_ID_ORG', 11 );

	// Generic ID #s for Source, Category, Type
	define( 'LEAD_SOURCE_GENERIC', 10 );
	define( 'LEAD_CATEGORY_GENERIC', 6 );
	define( 'LEAD_TYPE_GENERIC', 13 );

	// More defines
	define( 'HOT_TUB_BRAND', 'Sundance Spas' );
	define( 'OPT_IN_LIST_IDS', 2754925 );

	define( 'LIVEAPI', 'http://sundance.aimbase.com/FormBuilder/api/Lead' );
	define( 'TESTAPI', 'http://sundanceqa.aimbase.com/formbuilder/api/lead' );

	$LIVEHOST = array( 'www.sundancespas.com', 'www.sundancespas.ca' );
	$TESTHOST = array( 'beta.sundancespas.com', 'sundancespas.ninthlink.me', 'local.sundance' );

	$BETAHOST = array( 'beta.sundancespas.com' );
	$DEVHOST = array( 'sundancespas.ninthlink.me' );
	$LOCALHOST = array( 'local.sundance' );

	date_default_timezone_set('America/Los_Angeles');

	
	/* - - - - - Site Specific Functions - - - - - */
		function jht_avala_chunk_hidden() {
			avala_hidden_fields();
		}
		// Array of Lead Source ID's
		function avala_get_lead_source( $id = 13 ) {
			$leadSource = array(
				1 => 'Billboard',
				2 => 'BuyerZone - Qualified',
				3 => 'Call Center',
				4 => 'Co-Brand Out of Market',
				5 => 'Dealer Import',
				6 => 'Direct Mail',
				7 => 'Display Advertising',
				8 => 'Email',
				9 => 'Historical',
				10 => 'Other',
				11 => 'Search - Organic',
				12 => 'Search - Paid',
				13 => 'Sundancespas.com',
				14 => 'TS Affiliate',
				);
			if ( array_key_exists($id, $leadSource) )
				return $leadSource[$id];
			return $leadSource[ LEAD_SOURCE_GENERIC ];
		}
		// Array of Lead Category ID's
		function avala_get_lead_category( $id = 6 ) {
			$leadCategory = array( 
				1 => 'Affiliate',
				2 => 'BuyerZone',
				3 => 'Co-Brand',
				4 => 'Dealer Entry',
				5 => 'Historical',
				6 => 'Sundancespas.com',
				7 => 'Display Advertising',
				);
			if ( array_key_exists($id, $leadCategory) )
				return $leadCategory[$id];
			return $leadCategory[ LEAD_CATEGORY_GENERIC ];
		}
		// Array of Lead Type ID's
		function avala_get_lead_type( $id = 13 ) {
			$leadType = array( 
				1 => 'Campaign', 
			  	2 => 'Contact Dealer', 
			  	3 => 'Request Brochure', 
			  	4 => 'Request Financing', 
			  	5 => 'Request Quote', 
			  	7 => 'Request Trade In',
			    8 => 'Subscriber', 
			    9 => 'Sweepstakes', 
			    10 => 'Truck Load', 
			    11 => 'Request Appointment', 
			    12 => 'Request Brochure Download', 
			    13 => 'Other',
			    16 => 'Request DVD', 
			    17 => 'Request Brochure & DVD', 
			    18 => 'Request Brochure Mail & DVD', 
			    19 => 'Request Brochure Download & DVD', 
			    20 => 'Request Brochure Mail & Download & DVD',
			    21 => 'Request Brochure Mail', 
			    22 => 'Request Brochure Mail & Download' 
				);
			if ( array_key_exists($id, $leadType) )
				return $leadType[$id];
			return $leadType[ LEAD_TYPE_GENERIC ];
		}
	/* - - - - End Site Specific Functions - - - - */

/* - - - - - - - - - END SUNDANCE SPECIFICS - - - - - - - - - */



// submit and process the form
function avala_form_submit( $redirect = true ) {
	// We do these if this is a form POST
	if ( isset($_POST['EmailAddress']) && !empty($_POST['EmailAddress']) && (strpos( $_POST['EmailAddress'], '@' ) !== false) && empty($_POST['Address']) )
	{
		$api_url		= $_POST['api_url'];
		$thanks_page	= $_POST['thanks_page'];

		// send json
		$jsonArray	= avala_json_array();
		$result	= avala_api_curl( $api_url, $jsonArray['json_dump'] );	
		
		// save in database
		if ( $result[0] != 500 ) {
			$db_error = avala_db_backup( $jsonArray, $result );
		}
		else {
			$error = 'We are unable to process your request at this time. Please ensure all required fields are filled out correctly and try again.';
		}

		if ( $result[0] != 201 ) {
			mail( 'leaderror@ninthlink.com', 'SDS FORM ERROR :: ' . date( 'd/m/Y H:i:s', time() ), implode( " => ", $result ) . "\n\r\n\r" . $jsonArray['json_dump'] );
		}

		// do not track this kind of lead...
		setcookie('jhtsession', avala_track_lead(), time() + ( 60 * 5 ), '/' );

		// debug mode
		if ( !empty($_POST['debug']) )
		{
				$err_str = NULL;
				
				$err_str .= 'API URL: ' . $api_url . "\n\r\n\r";
				$err_str .= 'Thanks Page: ' . $thanks_page . "\n\r\n\r";
				$err_str .= 'JSON Result: ' . $result[0] . '->' . $result[1] . "\n\r\n\r";
				$err_str .= 'JSON String: ' . $jsonArray['json_dump'] . "\n\r\n\r";
				$err_str .= 'Raw Array Data: ' . "\n\r";
				foreach ( $jsonArray as $k => $v )
				{
					$err_str .= '[ ' . $k . ' ] ' . $v . "\n\r";
				}
				$err_str .= "\n\r\n\r";
				$err_str .= 'Database Error(s): ';
				printf( $error );
				$err_str .= "\n\r";
				mail( $_POST['debug'], 'Form Debug ' . date( 'd/m/Y H:i:s', time() ), $err_str );
		}

		if ( $result[0] == 201 && empty( $error ) ) {
			if ( $redirect === true ) {
				wp_redirect( $thanks_page );	// redirect to thanks page on success
			}
			else {
				return true;
			}
		}
		// if failed submit with errors, put error info in hidden div

		$error_block = '<div id="avalaFormErrors">' . $error . '<pre style="display: none !important;"><code>';
		$error_block .= implode("|", $jsonArray) . '<br />';
		$error_block .= 'Database Errors:' . implode("|", $db_error);
		$error_block .= '</code></pre></div>';

		return $error_block;
	}
	return false;
}
/* Hidden fields included on all pages. be sure to include Vars */
function avala_hidden_fields( $leadSourceId = LEAD_SOURCE_ID, $leadCategoryId = LEAD_CATEGORY_ID, $leadTypeId = LEAD_TYPE_ID, $id = NULL, $campaignId = NULL ) {
	$geo = geo_data();
	//organic link versus ppc
	if ( isset( $_GET['sa'] ) ) {
		if (strtolower($_GET['sa']) == 'l') 
		{
			//yes, it is ppc
			$leadSourceId = LEAD_SOURCE_ID_PPC;
		}
		if (strtolower($_GET['sa']) == 't') 
		{
			//no, it is not ppc
			$leadSourceId = LEAD_SOURCE_ID_ORG;
		}
	}
	$leadSourceId 		= ( !empty($_GET['s_cid']) && $_GET['s_cid'] > 0) ? LEAD_SOURCE_ID_PPC : $leadSourceId;
	$lead_source_meta	= get_post_meta( get_the_ID(), 'lead_source', true );
	$lead_category_meta	= get_post_meta( get_the_ID(), 'lead_category', true );
	$lead_type_meta		= get_post_meta( get_the_ID(), 'lead_type', true );
	$lead_source_get	= isset( $_GET['lead_source'] ) ? $_GET['lead_source'] : '';
	$lead_category_get	= isset( $_GET['lead_category'] ) ? $_GET['lead_category'] : '';
	$lead_type_get		= isset( $_GET['lead_type'] ) ? $_GET['lead_type'] : '';
	$leadSourceId		= ( !empty($lead_source_get) ) ? $lead_source_get : ( !empty($lead_source_meta) ? $lead_source_meta : $leadSourceId );
	$leadCategoryId		= ( !empty($lead_category_get) ) ? $lead_category_get : ( !empty($lead_category_meta) ? $lead_category_meta : $leadCategoryId );
	$leadTypeId			= ( !empty($lead_type_get) ) ? $lead_type_get : ( !empty($lead_type_meta) ? $lead_type_meta : $leadTypeId );
	$thanks_page		= get_post_meta( get_the_ID(), 'thanks_page', true );
    $leadId = ( !empty($_GET['lid']) ) ? $_GET['lid'] : null;
    $the_keywords = ( isset($_GET['kw']) ? $_GET['kw'] : '' );
    $the_keywords .= get_search_keywords();

    $uri = $_SERVER[REQUEST_URI];
    $uri = str_replace("/", "", $uri);
    $uri_a = explode("-", $uri);
    $cc = null;
    if ( $uri_a[0] == 'sundance' && $uri_a[1] == 'brochure' && isset($uri_a[2]) ) {
    	$cc = strtoupper($uri_a[2]);
    }

	echo '<input data-val="true" data-val-number="The field Id must be a number." data-val-required="The Id field is required." id="Id" name="Id" type="hidden" value="' . $id . '" />';
	echo '<input data-val="true" data-val-number="The field CampaignId must be a number." data-val-required="The CampaignId field is required." id="CampaignId" name="CampaignId" type="hidden" value="' . $campaignId . '" />';
	echo '<input data-val="true" data-val-number="The field LeadSourceId must be a number." data-val-required="The LeadSourceId field is required." id="LeadSourceId" name="LeadSourceId" type="hidden" value="' . $leadSourceId . '" />';
	echo '<input data-val="true" data-val-number="The field LeadCategoryId must be a number." data-val-required="The LeadCategoryId field is required." id="LeadCategoryId" name="LeadCategoryId" type="hidden" value="' . $leadCategoryId . '" />';
	echo '<input data-val="true" data-val-number="The field LeadTypeId must be a number." data-val-required="The LeadTypeId field is required." id="LeadTypeId" name="LeadTypeId" type="hidden" value="' . $leadTypeId . '" />';
	// we will use this ID to get other info on submit page
	echo '<input type="hidden" name="form_url_id" value="' . get_the_ID() . '" />';
	echo '<input type="hidden" name="the_keywords" value="' .  $the_keywords . '" />';
	echo '<input type="hidden" name="api_url" value="' . avala_get_api_url() . '" />';
	echo '<input type="hidden" name="thanks_page" value="' . avala_get_thanks_page() . '" />';
	// debug mode
	if ( isset($_GET['debug']) ) { echo '<input type="hidden" name="debug" value="' . $_GET['debug'] . '" />'; }
	elseif ( isset($_POST['debug']) ) { echo '<input type="hidden" name="debug" value="' . $_POST['debug'] . '" />'; }
	echo '<input type="hidden" name="CustomData[UserAgent]" value="' . $_SERVER['HTTP_USER_AGENT'] . '" />';
	// fields to be included in json array as necessary
	echo '<input type="hidden" name="DealerId" value="" />';
	echo '<input type="hidden" name="DealerNumber" value="" />';
	echo '<input type="hidden" name="ExactTargetOptInListIds" value="" />';
	echo '<input type="hidden" name="ExactTargetCustomAttributes" value="" />';
	echo '<input type="hidden" name="County" value="" />';
	echo '<input type="hidden" name="District" value="" />';
	echo '<input type="hidden" name="CountryCode" value="' . $cc . '" />';
	echo '<input type="hidden" name="HomePhone" value="" />';
	echo '<input type="hidden" name="MobilePhone" value="" />';
	echo '<input type="hidden" name="WorkPhone" value="" />';
	echo '<input type="hidden" name="ReceiveSmsCampaigns" value="" />';
	echo '<input type="hidden" name="ReceiveNewsletter" value="" />';
	echo '<input type="hidden" name="CustomData[IPaddress]" value="' . get_the_ip() . '" />';
	// Honey Pot field below :: note "Address1" is correct address field for avala submits
	echo '<input type="text" class="honey" name="Address" value="" placeholder="Leave this field blank, persons verification" />';
	// end honey bucket
	
	/* * * * Debugging * * * */
		if ( isset($_POST['debug']) || isset($_GET['debug']) )
		{
			$ip = get_the_ip();
			echo '<div style="display: none; visibility: hidden;"><pre>';
			echo '<!--Geo Function Return-->';
			print_r( $geo );
			echo '<!--Geo Function Curl Return-->';
			print_r( geo_data_curl( $ip ) );
			echo '<!--Geo Function MySql IP Return-->';
			print_r( geo_data_mysql_ip( $ip ) );
			echo '<!--Geo Function MySql ZIP Return-->';
			print_r( geo_data_mysql_zip( $_POST['PostalCode'] ) );
			echo '<!--Geo Session Return-->';
			if ($_SESSION['geoDbLookupData'])
				print_r( $_SESSION['geoDbLookupData'] );
			echo '</pre></div>';
		}
	/* * * * * * * * * * * * */

	// url strings added to custom data fields for tracking
	$dont_include = array( 'lead_source', 'lead_category', 'lead_type' );
	foreach ( $_GET as $k => $v ) {
		if ( !in_array( $k, $dont_include) )
		{
			echo '<input type="hidden" name="CustomData[' . $k . ']" value="' . $v . '" />';
		}
	}
}
// JSON array of all Avala fields/data to be submitted
function avala_json_array() {
    //	Get PPC info if exists
    $ppc = get_ppc_data();
    // Get Geo data
    $geo = geo_data();
    // Build the Array...
	$jsonArray	= array(
		'LeadSourceName'				=> avala_get_lead_source( ( !empty($_POST['LeadSourceId']) ? $_POST['LeadSourceId'] : NULL ) ),
		'LeadTypeName'					=> avala_get_lead_type( ( !empty($_POST['LeadTypeId']) ? $_POST['LeadTypeId'] : NULL ) ),
		'LeadCategoryName'				=> avala_get_lead_category( ( !empty($_POST['LeadCategoryId']) ? $_POST['LeadCategoryId'] : NULL ) ),
		'Brand'							=> HOT_TUB_BRAND,
		'FirstName'						=> '',
		'LastName'						=> '',
		'Address1'						=> '',
		'Address2'						=> '',
		'City'							=> $geo['city'],
		'State'							=> $geo['region'],
		'County'						=> '',
		'District'						=> '',
		'PostalCode'					=> '',
		'CountryCode'					=> $geo['country'],
		'EmailAddress'					=> '',
		'HomePhone'						=> ( !empty( $_POST['HomePhone'] ) ) ? format_phone_us( $_POST['HomePhone'] ) : format_phone_us( $_POST['Phone'] ),
		'MobilePhone'					=> '',
		'WorkPhone'						=> '',
		'ReceiveEmailCampaigns'			=> false,
		'ReceiveSmsCampaigns'			=> false,
		'ReceiveNewsletter'				=> false,
		'ProductCode'					=> '',
		'ProductIdList'					=> '',
		'DealerId'						=> '',
		'DealerNumber'					=> '',
		'Comments'						=> '',
		'ExactTargetOptInListIds'		=> ( $_POST['ReceiveEmailCampaigns'] ) ? OPT_IN_LIST_IDS : '' ,
		'ExactTargetCustomAttributes'	=> '',
		'CustomData'					=> array(
			'HomeOwner'				=> '',
			'ProductUse'			=> '',
			'InterestedInOwning'	=> '',
			'BuyTimeFrame'			=> '',
			'FormPage'				=> avala_get_form_url(),
			'IPaddress'				=> '',
			),
		'WebSessionData'				=> array(
			'DeliveryMethod'		=> ( $ppc['utmcsr'] ) ? $ppc['utmcsr'] : 'Unknown',
			'KeyWords'				=> $_POST['the_keywords'],
			'Medium'				=> ( $ppc['utmcmd'] ) ? $ppc['utmcmd'] : 'Unknown',
			'PagesViewed'			=> ws_track('pages_viewed'),
			'PageViews'				=> ws_track('page_count'),
			'PayoffLeft'			=> '',
			'TimeOnSite'			=> time_on_site(),
			'VisitCount'			=> ( $ppc['utma'] ) ? $ppc['utma'] : 'Unknown',
			),
		'AccountId'						=> '',
		'LeadDate'						=> date( "m/d/Y h:i:s A", time() ),
		'Campaign'						=> '',
		'TriggeredSend'					=> '',
		'Event'							=> '',
		);
	// add post data to array
	foreach ($_POST as $k => $v) {
		if ( array_key_exists( $k, $jsonArray ) && !empty( $v ) ) {
			$jsonArray[$k] =  $v ;
		}
	}
	// overwrite post data with get data if any, but protect ourselves
	foreach ($_GET as $k => $v) {
		if ( array_key_exists( $k, $jsonArray ) ) {
			$jsonArray[$k] = mysql_real_escape_string( $v );
		}
	}
	// Remove empty ARRAY fields so we do not submit blank data
	$jsonArray = array_filter( $jsonArray );
	// JSON encode ARRAY ( Aimbase requires [] grouped json encoded data )
	$jsonString = '[' . json_encode( $jsonArray ) . ']';
	// And add the raw JSON string to the array for processing
	$jsonArray['json_dump'] = $jsonString;
	// Return the final Array
	return $jsonArray;
}
// Get the API URL string
function avala_get_api_url() {
	global $TESTHOST;
	$api_url = LIVEAPI; //get_post_meta( get_the_ID(), 'api_url', true );
	if ( isset( $_GET['api_url'] ) && strtolower( $_GET['api_url'] ) == 'test' ) {
		$api_url = TESTAPI;
	}
	elseif ( in_array( $_SERVER['SERVER_NAME'], $TESTHOST ) || in_array( $_SERVER['HTTP_HOST'], $TESTHOST ) ) {
		$api_url = TESTAPI;
	}
	else {
		$api_url = LIVEAPI;
	}
	return $api_url;
}
// Get the thank you page url as set in post custom fields
function avala_get_thanks_page( $thanks_page = false ) {
	if ( $thanks_page )
		return $thanks_page;
	$thanks_page = get_permalink();
	$thanks_meta = get_post_meta( get_the_ID(), 'thanks_page', true );
	if ( substr( $thanks_meta, 0, 1 ) === '/')
	{
		$thanks_page = site_url() . $thanks_meta;
	}
	else if ( substr( $thanks_meta, 0, 4 ) === 'http')
	{
		$thanks_page = $thanks_meta;
	}
	else
	{
		$thanks_page = get_permalink() . $thanks_meta;
	}
	if ( ( substr( $thanks_meta, -1, 1 ) !== '/' ) && ( strpos( $thanks_page,'?' ) == false) )
	{
		$thanks_page .= '/';
	}
	return $thanks_page;
}
// submit API via curl
function avala_api_curl( $sendTo, $json_string ) {
	$ch = curl_init($sendTo);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $json_string);
	curl_setopt($ch, CURLOPT_PROXY, null);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION,true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json', 'Content-Length: ' . strlen($json_string) ) );
	$apiResult = curl_exec($ch);
	$httpResult = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	$a = array( 0 => $httpResult, 1 => $apiResult );
	return $a;
}
// lead tracking based on interest. if true, include tracking pixels in footer. see sidebar-trackingcode.php
function avala_track_lead() {
	// If form InterestedInOwning is No, do not track
	if ( isset( $_POST['CustomData']['InterestedInOwning'] ) && $_POST['CustomData']['InterestedInOwning'] == 'No' )
	{
		return 0;
	}
	else
	{
		return 1;
	}
}
/* * * * * * * * Database Backup of Avala Form submits (dependent function) * * * * * * * * * * * */
function avala_db_backup( $jsonArray, $curl_results ) {
	// where did this data come from (what form?)
	$formurl = ( isset( $_POST['formurl'] ) ) ? $_POST['formurl'] : ( ( get_permalink() ) ? get_permalink() : NULL ); 
	// insert into db
	$mysqli = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
	// Unable to MySQL? Return false
	if ($mysqli->connect_errno) {
		$error = "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
		return $error;
	}
	$a = array(
		'api_response' => $curl_results[0],
		'api_url' => ( isset($_POST['api_url']) ? $_POST['api_url'] : '' ),
		'form_url' => $formurl,
		'LeadSourceName' => '',
		'LeadTypeName' => '',
		'LeadCategoryName' => '',
		'Brand' => '',
		'FirstName' => '',
		'LastName' => '',
		'Address1' => '',
		'Address2' => '',
		'City' => '',
		'State' => '',
		'County' => '',
		'District' => '',
		'PostalCode' => '',
		'CountryCode' => '',
		'EmailAddress' => '',
		'HomePhone' => '',
		'MobilePhone' => '',
		'WorkPhone' => '',
		'ReceiveEmailCampaigns' => '',
		'ReceiveSmsCampaigns' => '',
		'ReceiveNewsletter' => '',
		'ProductCode' => '',
		'ProductIdList' => '',
		'DealerId' => '',
		'DealerNumber' => '',
		'Comments' => '',
		'ExactTargetOptInListIds' => '',
		'ExactTargetCustomAttributes' => '',
		'CustomData' => array(
			'HomeOwner' => '',
			'ProductUse' => '',
			'InterestedInOwning' => '',
			'BuyTimeFrame' => '',
			'FormPage' => '',
			'IPaddress' => '',
			),
		'WebSessionData' => array(
			'DeliveryMethod' => '',
			'KeyWords' => '',
			'Medium' => '',
			'PagesViewed' => '',
			'PageViews' => '',
			'PayoffLeft' => '',
			'TimeOnSite' => '',
			'VisitCount' => '',
			),
		'AccountId' => '',
		'LeadDate' => '',
		'Campaign' => '',
		'TriggeredSend' => '',
		'Event' => '',
		'json_dump' => '',
		);
	foreach ( $jsonArray as $k => $v) 
	{
		if ( array_key_exists( $k, $a ) && !empty( $v ) ) 
		{
			$a[$k] =  $v;
		}
	}
	
	$query = "INSERT INTO `avala_lead_details` (
		`api_response`,
		`api_url`,
		`form_url`,
		`LeadSourceName`,
		`LeadTypeName`,
		`LeadCategoryName`,
		`Brand`,
		`FirstName`,
		`LastName`,
		`Address1`,
		`Address2`,
		`City`,
		`State`,
		`County`,
		`District`,
		`PostalCode`,
		`CountryCode`,
		`EmailAddress`,
		`HomePhone`,
		`MobilePhone`,
		`WorkPhone`,
		`ReceiveEmailCampaigns`,
		`ReceiveSmsCampaigns`,
		`ReceiveNewsletter`,
		`ProductCode`,
		`ProductIdList`,
		`DealerId`,
		`DealerNumber`,
		`Comments`,
		`ExactTargetOptInListIds`,
		`ExactTargetCustomAttributes`,
		`HomeOwner`,
		`ProductUse`,
		`InterestedInOwning`,
		`BuyTimeFrame`,
		`FormPage`,
		`IPaddress`,
		`DeliveryMethod`,
		`KeyWords`,
		`Medium`,
		`PagesViewed`,
		`PageViews`,
		`PayoffLeft`,
		`TimeOnSite`,
		`VisitCount`,
		`AccountId`,
		`LeadDate`,
		`Campaign`,
		`TriggeredSend`,
		`Event`,
		`json_dump` )
		VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

	if ( $stmt = $mysqli->prepare( $query ) ) {

		$stmt->bind_param("sssssssssssssssssssssssssssssssssssssssssssssssssss",$a['api_response'],$a['api_url'],$a['form_url'],$a['LeadSourceName'],$a['LeadTypeName'],$a['LeadCategoryName'],$a['Brand'],$a['FirstName'],$a['LastName'],$a['Address1'],$a['Address2'],$a['City'],$a['State'],$a['County'],$a['District'],$a['PostalCode'],$a['CountryCode'],$a['EmailAddress'],$a['HomePhone'],$a['MobilePhone'],$a['WorkPhone'],$a['ReceiveEmailCampaigns'],$a['ReceiveSmsCampaigns'],$a['ReceiveNewsletter'],$a['ProductCode'],$a['ProductIdList'],$a['DealerId'],$a['DealerNumber'],$a['Comments'],$a['ExactTargetOptInListIds'],$a['ExactTargetCustomAttributes'],$a['CustomData']['HomeOwner'],$a['CustomData']['ProductUse'],$a['CustomData']['InterestedInOwning'],$a['CustomData']['BuyTimeFrame'],$a['CustomData']['FormPage'],$a['CustomData']['IPaddress'],$a['WebSessionData']['DeliveryMethod'],$a['WebSessionData']['KeyWords'],$a['WebSessionData']['Medium'],$a['WebSessionData']['PagesViewed'],$a['WebSessionData']['PageViews'],$a['WebSessionData']['PayoffLeft'],$a['WebSessionData']['TimeOnSite'],$a['WebSessionData']['VisitCount'],$a['AccountId'],$a['LeadDate'],$a['Campaign'],$a['TriggeredSend'],$a['Event'],$a['json_dump']);

		$stmt->execute();

		$e = $mysqli->error;

		$stmt->close();

		$mysqli->close();

		return $e;

	}

	$e = $mysqli->error;

	$mysqli->close();

	return $e;
}
/* * * * * * * * Arguments passed to Avala form fields * * * * * * * * * * * * * * * * * * * * * */
function avala_args( $field = NULL, $content = NULL ) {
	$geo = geo_data();

	$a = array( 'city', 'state', 'country', 'postal_code' );

	$pickyPostal = ( isset($geo['country']) && $geo['country'] == 'US' ) ? 'Zip Code' : 'Postal Code' ;

	$args = array(
		'first_name'			=> array(
			'id'			=> 'person_first_name',
			'classes'		=> '',
			'name'			=> 'FirstName',
			'text'			=> 'First Name',
			'input_type'	=> 'text',
			'default_value'	=> '',
			),
		'last_name'				=> array(
			'id'			=> 'person_last_name',
			'classes'		=> '',
			'name'			=> 'LastName',
			'text'			=> 'Last Name',
			'input_type'	=> 'text',
			'default_value'	=> '',
			),
		'email'					=> array(
			'id'			=> 'person_email',
			'classes'		=> '',
			'name'			=> 'EmailAddress',
			'text'			=> 'Email',
			'input_type'	=> 'text',
			'default_value'	=> '',
			),
		'phone'					=> array(
			'id'			=> 'person_phone',
			'classes'		=> 'phonenumber',
			'name'			=> 'Phone',
			'text'			=> 'Phone',
			'input_type'	=> 'text',
			'default_value'	=> '',
			),
		'address'				=> array(
			'id'			=> 'person_address',
			'classes'		=> '',
			'name'			=> 'Address1',
			'text'			=> 'Adress',
			'input_type'	=> 'text',
			'default_value'	=> '',
			),
		'city'					=> array(
			'id'			=> 'person_city',
			'classes'		=> '',
			'name'			=> 'City',
			'text'			=> 'City',
			'input_type'	=> 'text',
			'default_value'	=> '',
			),
		'state'					=> array(
			'id'			=> 'person_state',
			'classes'		=> '',
			'name'			=> 'State',
			'text'			=> 'State',
			'input_type'	=> 'select',
			'options'		=> array(
				'Abu Dhabia' => array( 'value' => 'AD', 'data-country' => 'AE' ),
				'Ajman' => array( 'value' => 'AJ', 'data-country' => 'AE' ),
				'Dubai' => array( 'value' => 'DU', 'data-country' => 'AE' ),
				'Fujairah' => array( 'value' => 'FU', 'data-country' => 'AE' ),
				'Ras al-Khaimah' => array( 'value' => 'RK', 'data-country' => 'AE' ),
				'Sharjah' => array( 'value' => 'SH', 'data-country' => 'AE' ),
				'Umm al-Quwain' => array( 'value' => 'UQ', 'data-country' => 'AE' ),
				'Alabama' => array( 'value' => 'AL', 'data-country' => 'US' ),
				'Alsace' => array( 'value' => 'A', 'data-country' => 'FR' ),
				'Aquitaine' => array( 'value' => 'B', 'data-country' => 'FR' ),
				'Alaska' => array( 'value' => 'AK', 'data-country' => 'US' ),
				'Auvergne' => array( 'value' => 'C', 'data-country' => 'FR' ),
				'Basse-Normandie' => array( 'value' => 'P', 'data-country' => 'FR' ),
				'Arizona' => array( 'value' => 'AZ', 'data-country' => 'US' ),
				'Bourgogne' => array( 'value' => 'D', 'data-country' => 'FR' ),
				'Arkansas' => array( 'value' => 'AR', 'data-country' => 'US' ),
				'Bretagne' => array( 'value' => 'E', 'data-country' => 'FR' ),
				'California' => array( 'value' => 'CA', 'data-country' => 'US' ),
				'Centre' => array( 'value' => 'F', 'data-country' => 'FR' ),
				'Champagne-Ardenne' => array( 'value' => 'G', 'data-country' => 'FR' ),
				'Colorado' => array( 'value' => 'CO', 'data-country' => 'US' ),
				'Connecticut' => array( 'value' => 'CT', 'data-country' => 'US' ),
				'Corse' => array( 'value' => 'H', 'data-country' => 'FR' ),
				'Franche-Comt' => array( 'value' => 'I', 'data-country' => 'FR' ),
				'Delaware' => array( 'value' => 'DE', 'data-country' => 'US' ),
				'Haute-Normandie' => array( 'value' => 'Q', 'data-country' => 'FR' ),
				'District Of Columbia' => array( 'value' => 'DC', 'data-country' => 'US' ),
				'le-de-France' => array( 'value' => 'J', 'data-country' => 'FR' ),
				'Florida' => array( 'value' => 'FL', 'data-country' => 'US' ),
				'Georgia' => array( 'value' => 'GA', 'data-country' => 'US' ),
				'Languedoc-Roussillon' => array( 'value' => 'K', 'data-country' => 'FR' ),
				'Limousin' => array( 'value' => 'L', 'data-country' => 'FR' ),
				'Lorraine' => array( 'value' => 'M', 'data-country' => 'FR' ),
				'Hawaii' => array( 'value' => 'HI', 'data-country' => 'US' ),
				'Idaho' => array( 'value' => 'ID', 'data-country' => 'US' ),
				'Midi-Pyr n es' => array( 'value' => 'N', 'data-country' => 'FR' ),
				'Nord - Pas-de-Calais' => array( 'value' => 'O', 'data-country' => 'FR' ),
				'Illinois' => array( 'value' => 'IL', 'data-country' => 'US' ),
				'Indiana' => array( 'value' => 'IN', 'data-country' => 'US' ),
				'Pays de la Loire' => array( 'value' => 'R', 'data-country' => 'FR' ),
				'Picardie' => array( 'value' => 'S', 'data-country' => 'FR' ),
				'Iowa' => array( 'value' => 'IA', 'data-country' => 'US' ),
				'Kansas' => array( 'value' => 'KS', 'data-country' => 'US' ),
				'Poitou-Charentes' => array( 'value' => 'T', 'data-country' => 'FR' ),
				'Provence-Alpes-C te d\'Azur' => array( 'value' => 'U', 'data-country' => 'FR' ),
				'Kentucky' => array( 'value' => 'KY', 'data-country' => 'US' ),
				'Louisiana' => array( 'value' => 'LA', 'data-country' => 'US' ),
				'Rh ne-Alpes' => array( 'value' => 'V', 'data-country' => 'FR' ),
				'Maine' => array( 'value' => 'ME', 'data-country' => 'US' ),
				'Maryland' => array( 'value' => 'MD', 'data-country' => 'US' ),
				'Massachusetts' => array( 'value' => 'MA', 'data-country' => 'US' ),
				'Michigan' => array( 'value' => 'MI', 'data-country' => 'US' ),
				'Minnesota' => array( 'value' => 'MN', 'data-country' => 'US' ),
				'Mississippi' => array( 'value' => 'MS', 'data-country' => 'US' ),
				'Missouri' => array( 'value' => 'MO', 'data-country' => 'US' ),
				'Montana' => array( 'value' => 'MT', 'data-country' => 'US' ),
				'Nebraska' => array( 'value' => 'NE', 'data-country' => 'US' ),
				'Nevada' => array( 'value' => 'NV', 'data-country' => 'US' ),
				'New Hampshire' => array( 'value' => 'NH', 'data-country' => 'US' ),
				'New Jersey' => array( 'value' => 'NJ', 'data-country' => 'US' ),
				'New Mexico' => array( 'value' => 'NM', 'data-country' => 'US' ),
				'New York' => array( 'value' => 'NY', 'data-country' => 'US' ),
				'North Carolina' => array( 'value' => 'NC', 'data-country' => 'US' ),
				'North Dakota' => array( 'value' => 'ND', 'data-country' => 'US' ),
				'Ohio' => array( 'value' => 'OH', 'data-country' => 'US' ),
				'Oklahoma' => array( 'value' => 'OK', 'data-country' => 'US' ),
				'Oregon' => array( 'value' => 'OR', 'data-country' => 'US' ),
				'Pennsylvania' => array( 'value' => 'PA', 'data-country' => 'US' ),
				'Rhode Island' => array( 'value' => 'RI', 'data-country' => 'US' ),
				'South Carolina' => array( 'value' => 'SC', 'data-country' => 'US' ),
				'South Dakota' => array( 'value' => 'SD', 'data-country' => 'US' ),
				'Tennessee' => array( 'value' => 'TN', 'data-country' => 'US' ),
				'Texas' => array( 'value' => 'TX', 'data-country' => 'US' ),
				'Utah' => array( 'value' => 'UT', 'data-country' => 'US' ),
				'Vermont' => array( 'value' => 'VT', 'data-country' => 'US' ),
				'Virginia' => array( 'value' => 'VA', 'data-country' => 'US' ),
				'Washington' => array( 'value' => 'WA', 'data-country' => 'US' ),
				'West Virginia' => array( 'value' => 'WV', 'data-country' => 'US' ),
				'Wisconsin' => array( 'value' => 'WI', 'data-country' => 'US' ),
				'Wyoming' => array( 'value' => 'WY', 'data-country' => 'US' ),
				'Armed Forces - Europe' => array( 'value' => 'AE', 'data-country' => 'US' ),
				'Armed Forces - Pacific' => array( 'value' => 'AP', 'data-country' => 'US' ),
				'Armed Forces - Americas' => array( 'value' => 'AA', 'data-country' => 'US' ),
				'American Samoa' => array( 'value' => 'AS', 'data-country' => 'AS' ),
				'Micronesia' => array( 'value' => 'FM', 'data-country' => 'FM' ),
				'Guam' => array( 'value' => 'GU', 'data-country' => 'GU' ),
				'Marshall Islands' => array( 'value' => 'MH', 'data-country' => 'MH' ),
				'N Mariana Islands' => array( 'value' => 'MP', 'data-country' => 'MP' ),
				'Palau' => array( 'value' => 'PW', 'data-country' => 'PW' ),
				'Puerto Rico' => array( 'value' => 'PR', 'data-country' => 'PR' ),
				'Virgin Islands' => array( 'value' => 'VI', 'data-country' => 'VI' ),
				'Alberta' => array( 'value' => 'AB', 'data-country' => 'CA' ),
				'British Columbia' => array( 'value' => 'BC', 'data-country' => 'CA' ),
				'Manitoba' => array( 'value' => 'MB', 'data-country' => 'CA' ),
				'New Brunswick' => array( 'value' => 'NB', 'data-country' => 'CA' ),
				'Newfoundland' => array( 'value' => 'NL', 'data-country' => 'CA' ),
				'Nova Scotia' => array( 'value' => 'NS', 'data-country' => 'CA' ),
				'Northwest Territory' => array( 'value' => 'NT', 'data-country' => 'CA' ),
				'Nuavut' => array( 'value' => 'NU', 'data-country' => 'CA' ),
				'Ontario' => array( 'value' => 'ON', 'data-country' => 'CA' ),
				'Prince Edward Island' => array( 'value' => 'PE', 'data-country' => 'CA' ),
				'Qu bec' => array( 'value' => 'QC', 'data-country' => 'CA' ),
				'Saskatchewan' => array( 'value' => 'SK', 'data-country' => 'CA' ),
				'Yukon Territories' => array( 'value' => 'YT', 'data-country' => 'CA' ),
				'Southern Australia' => array( 'value' => 'SA', 'data-country' => 'AU' ),
				'Queensland' => array( 'value' => 'QLD', 'data-country' => 'AU' ),
				'New South Wales' => array( 'value' => 'NSW', 'data-country' => 'AU' ),
				'Australian Capital Territory' => array( 'value' => 'ACT', 'data-country' => 'AU' ),
				'Victoria' => array( 'value' => 'VIC', 'data-country' => 'AU' ),
				'Western Australia' => array( 'value' => 'WA', 'data-country' => 'AU' ),
				'Tasmania' => array( 'value' => 'TAS', 'data-country' => 'AU' ),
				'Northern Territory' => array( 'value' => 'NT', 'data-country' => 'AU' ),
				'Acre' => array( 'value' => 'AC', 'data-country' => 'BR' ),
				'Alagoas' => array( 'value' => 'AL', 'data-country' => 'BR' ),
				'Amazonas' => array( 'value' => 'AM', 'data-country' => 'BR' ),
				'Amap' => array( 'value' => 'AP', 'data-country' => 'BR' ),
				'Bahia' => array( 'value' => 'BA', 'data-country' => 'BR' ),
				'Cear' => array( 'value' => 'CE', 'data-country' => 'BR' ),
				'Distrito Federal' => array( 'value' => 'DF', 'data-country' => 'BR' ),
				'Espirito Santo' => array( 'value' => 'ES', 'data-country' => 'BR' ),
				'Goi s' => array( 'value' => 'GO', 'data-country' => 'BR' ),
				'Maranh o' => array( 'value' => 'MA', 'data-country' => 'BR' ),
				'Minas Gerais' => array( 'value' => 'MG', 'data-country' => 'BR' ),
				'Mato Grosso do Sul' => array( 'value' => 'MS', 'data-country' => 'BR' ),
				'Mato Grosso' => array( 'value' => 'MT', 'data-country' => 'BR' ),
				'Par' => array( 'value' => 'PA', 'data-country' => 'BR' ),
				'Para ba' => array( 'value' => 'PB', 'data-country' => 'BR' ),
				'Pernambuco' => array( 'value' => 'PE', 'data-country' => 'BR' ),
				'Piau' => array( 'value' => 'PI', 'data-country' => 'BR' ),
				'Paran' => array( 'value' => 'PR', 'data-country' => 'BR' ),
				'Rio de Janeiro' => array( 'value' => 'RJ', 'data-country' => 'BR' ),
				'Rio Grande do Norte' => array( 'value' => 'RN', 'data-country' => 'BR' ),
				'Rond nia' => array( 'value' => 'RO', 'data-country' => 'BR' ),
				'Roraima' => array( 'value' => 'RR', 'data-country' => 'BR' ),
				'Rio Grande do Sul' => array( 'value' => 'RS', 'data-country' => 'BR' ),
				'Santa Catarina' => array( 'value' => 'SC', 'data-country' => 'BR' ),
				'Sergipe' => array( 'value' => 'SE', 'data-country' => 'BR' ),
				'S o Paulo' => array( 'value' => 'SP', 'data-country' => 'BR' ),
				'Tocantins' => array( 'value' => 'TO', 'data-country' => 'BR' ),
				),
			'default_value'	=> '',
			),
		'postal_code'			=> array(
			'id'			=> 'person_postal_code',
			'classes'		=> '',
			'name'			=> 'PostalCode',
			'text'			=> $pickyPostal,
			'input_type'	=> 'text',
			'default_value'	=> '',
			),
		'country'				=> array(
			'id'			=> 'person_country',
			'classes'		=> '',
			'name'			=> 'CountryCode',
			'text'			=> 'Country',
			'input_type'	=> 'select',
			'options'		=> array(
				'United States' => array( 'value' => 'US' ),
				'Canada' => array( 'value' => 'CA' ), 
				'Afghanistan' => array( 'value' => 'AF' ),
				'Albania' => array( 'value' => 'AL' ),
				'Algeria' => array( 'value' => 'DZ' ),
				'American Samoa' => array( 'value' => 'AS' ),
				'Andorra' => array( 'value' => 'AD' ),
				'Angola' => array( 'value' => 'AO' ),
				'Anguilla' => array( 'value' => 'AI' ),
				'ANTARCTICA' => array( 'value' => 'AQ' ),
				'Antigua and Barbuda' => array( 'value' => 'AG' ),
				'Argentina' => array( 'value' => 'AR' ),
				'Armenia' => array( 'value' => 'AM' ),
				'Aruba' => array( 'value' => 'AW' ),
				'Ascension Island' => array( 'value' => 'AC' ),
				'Australia' => array( 'value' => 'AU' ),
				'Austria' => array( 'value' => 'AT' ),
				'Azerbaijan' => array( 'value' => 'AZ' ),
				'Bahamas' => array( 'value' => 'BS' ),
				'Bahrain' => array( 'value' => 'BH' ),
				'Bangladesh' => array( 'value' => 'BD' ),
				'Barbados' => array( 'value' => 'BB' ),
				'Belarus' => array( 'value' => 'BY' ),
				'Belgium' => array( 'value' => 'BE' ),
				'Belize' => array( 'value' => 'BZ' ),
				'Benin' => array( 'value' => 'BJ' ),
				'Bermuda' => array( 'value' => 'BM' ),
				'Bhutan' => array( 'value' => 'BT' ),
				'Bolivia' => array( 'value' => 'BO' ),
				'Bosnia-Herzegovina' => array( 'value' => 'BA' ),
				'Botswana' => array( 'value' => 'BW' ),
				'BOUVET ISLAND' => array( 'value' => 'BV' ),
				'Brazil' => array( 'value' => 'BR' ),
				'BRITISH INDIAN OCEAN TERRITORY' => array( 'value' => 'IO' ),
				'Brunei Darussalam' => array( 'value' => 'BN' ),
				'Bulgaria' => array( 'value' => 'BG' ),
				'Burkina Faso' => array( 'value' => 'BF' ),
				'Burundi' => array( 'value' => 'BI' ),
				'Cambodia' => array( 'value' => 'KH' ),
				'Cameroon' => array( 'value' => 'CM' ),
				'Cape Verde' => array( 'value' => 'CV' ),
				'Cayman Islands' => array( 'value' => 'KY' ),
				'Central African Republic' => array( 'value' => 'CF' ),
				'Chad' => array( 'value' => 'TD' ),
				'Chile' => array( 'value' => 'CL' ),
				'China' => array( 'value' => 'CN' ),
				'Christmas Island' => array( 'value' => 'CX' ),
				'Cocos (Keeling) Islands' => array( 'value' => 'CC' ),
				'Colombia' => array( 'value' => 'CO' ),
				'Comoros' => array( 'value' => 'KM' ),
				'Congo, Democratic Republic of the' => array( 'value' => 'CD' ),
				'Congo, Republic of' => array( 'value' => 'CG' ),
				'Cook Islands' => array( 'value' => 'CK' ),
				'Costa Rica' => array( 'value' => 'CR' ),
				'Cote d\'Ivoire' => array( 'value' => 'CI' ),
				'Croatia' => array( 'value' => 'HR' ),
				'Cuba' => array( 'value' => 'CU' ),
				'Cyprus' => array( 'value' => 'CY' ),
				'Czech Republic' => array( 'value' => 'CZ' ),
				'Denmark' => array( 'value' => 'DK' ),
				'Djibouti' => array( 'value' => 'DJ' ),
				'Dominica' => array( 'value' => 'DM' ),
				'Dominican Republic' => array( 'value' => 'DO' ),
				'East Timor' => array( 'value' => 'TL' ),
				'Ecuador' => array( 'value' => 'EC' ),
				'Egypt' => array( 'value' => 'EG' ),
				'El Salvador' => array( 'value' => 'SV' ),
				'Equatorial Guinea' => array( 'value' => 'GQ' ),
				'Eritrea' => array( 'value' => 'ER' ),
				'Estonia' => array( 'value' => 'EE' ),
				'Ethiopia' => array( 'value' => 'ET' ),
				'Falkland Islands' => array( 'value' => 'FK' ),
				'Faroe Islands' => array( 'value' => 'FO' ),
				'Fiji' => array( 'value' => 'FJ' ),
				'Finland' => array( 'value' => 'FI' ),
				'France' => array( 'value' => 'FR' ),
				'French Guiana' => array( 'value' => 'GF' ),
				'French Polynesia' => array( 'value' => 'PF' ),
				'French Southern Territories' => array( 'value' => 'TF' ),
				'Gabon' => array( 'value' => 'GA' ),
				'Gambia' => array( 'value' => 'GM' ),
				'Georgia' => array( 'value' => 'GE' ),
				'Germany' => array( 'value' => 'DE' ),
				'Ghana' => array( 'value' => 'GH' ),
				'Gibraltar' => array( 'value' => 'GI' ),
				'Greece' => array( 'value' => 'GR' ),
				'Greenland' => array( 'value' => 'GL' ),
				'Grenada' => array( 'value' => 'GD' ),
				'Guadeloupe' => array( 'value' => 'GP' ),
				'Guam' => array( 'value' => 'GU' ),
				'Guatemala' => array( 'value' => 'GT' ),
				'Guernsey' => array( 'value' => 'GG' ),
				'Guinea' => array( 'value' => 'GN' ),
				'Guinea-Bissau' => array( 'value' => 'GW' ),
				'Guyana' => array( 'value' => 'GY' ),
				'Haiti' => array( 'value' => 'HT' ),
				'Heard and McDonald Islands' => array( 'value' => 'HM' ),
				'HOLY SEE (VATICAN CITY STATE)' => array( 'value' => 'VA' ),
				'Honduras' => array( 'value' => 'HN' ),
				'Hong Kong' => array( 'value' => 'HK' ),
				'Hungary' => array( 'value' => 'HU' ),
				'Iceland' => array( 'value' => 'IS' ),
				'India' => array( 'value' => 'IN' ),
				'Indonesia' => array( 'value' => 'ID' ),
				'Iran' => array( 'value' => 'IR' ),
				'Iraq' => array( 'value' => 'IQ' ),
				'Ireland' => array( 'value' => 'IE' ),
				'Isle of Man' => array( 'value' => 'IM' ),
				'Israel' => array( 'value' => 'IL' ),
				'Italy' => array( 'value' => 'IT' ),
				'Jamaica' => array( 'value' => 'JM' ),
				'Japan' => array( 'value' => 'JP' ),
				'Jersey' => array( 'value' => 'JE' ),
				'Jordan' => array( 'value' => 'JO' ),
				'Kazakhstan' => array( 'value' => 'KZ' ),
				'Kenya' => array( 'value' => 'KE' ),
				'Kiribati' => array( 'value' => 'KI' ),
				'Kuwait' => array( 'value' => 'KW' ),
				'Kyrgyzstan' => array( 'value' => 'KG' ),
				'Laos' => array( 'value' => 'LA' ),
				'Latvia' => array( 'value' => 'LV' ),
				'Lebanon' => array( 'value' => 'LB' ),
				'Lesotho' => array( 'value' => 'LS' ),
				'Liberia' => array( 'value' => 'LR' ),
				'Libya' => array( 'value' => 'LY' ),
				'Liechtenstein' => array( 'value' => 'LI' ),
				'Lithuania' => array( 'value' => 'LT' ),
				'Luxembourg' => array( 'value' => 'LU' ),
				'Macao' => array( 'value' => 'MO' ),
				'Macedonia' => array( 'value' => 'MK' ),
				'Madagascar' => array( 'value' => 'MG' ),
				'Malawi' => array( 'value' => 'MW' ),
				'Malaysia' => array( 'value' => 'MY' ),
				'Maldives' => array( 'value' => 'MV' ),
				'Mali' => array( 'value' => 'ML' ),
				'Malta' => array( 'value' => 'MT' ),
				'Marshall Islands' => array( 'value' => 'MH' ),
				'Martinique' => array( 'value' => 'MQ' ),
				'Mauritania' => array( 'value' => 'MR' ),
				'Mauritius' => array( 'value' => 'MU' ),
				'Mayotte' => array( 'value' => 'YT' ),
				'Mexico' => array( 'value' => 'MX' ),
				'Micronesia, Federal State of' => array( 'value' => 'FM' ),
				'Moldova, Republic of' => array( 'value' => 'MD' ),
				'Monaco' => array( 'value' => 'MC' ),
				'Mongolia' => array( 'value' => 'MN' ),
				'Montserrat' => array( 'value' => 'MS' ),
				'Morocco' => array( 'value' => 'MA' ),
				'Mozambique' => array( 'value' => 'MZ' ),
				'Myanmar' => array( 'value' => 'MM' ),
				'Namibia' => array( 'value' => 'NA' ),
				'Nauru' => array( 'value' => 'NR' ),
				'Nepal' => array( 'value' => 'NP' ),
				'Netherlands' => array( 'value' => 'NL' ),
				'Netherlands Antilles' => array( 'value' => 'AN' ),
				'New Caledonia' => array( 'value' => 'NC' ),
				'New Zealand' => array( 'value' => 'NZ' ),
				'Nicaragua' => array( 'value' => 'NI' ),
				'Niger' => array( 'value' => 'NE' ),
				'Nigeria' => array( 'value' => 'NG' ),
				'Niue' => array( 'value' => 'NU' ),
				'Norfolk Island' => array( 'value' => 'NF' ),
				'North Korea' => array( 'value' => 'KP' ),
				'Northern Mariana Islands' => array( 'value' => 'MP' ),
				'Norway' => array( 'value' => 'NO' ),
				'Oman' => array( 'value' => 'OM' ),
				'Pakistan' => array( 'value' => 'PK' ),
				'Palau' => array( 'value' => 'PW' ),
				'Palestinian Territories' => array( 'value' => 'PS' ),
				'Panama' => array( 'value' => 'PA' ),
				'Papua New Guinea' => array( 'value' => 'PG' ),
				'Paraguay' => array( 'value' => 'PY' ),
				'Peru' => array( 'value' => 'PE' ),
				'Philippines' => array( 'value' => 'PH' ),
				'Pitcairn Island' => array( 'value' => 'PN' ),
				'Poland' => array( 'value' => 'PL' ),
				'Portugal' => array( 'value' => 'PT' ),
				'Puerto Rico' => array( 'value' => 'PR' ),
				'Qatar' => array( 'value' => 'QA' ),
				'Reunion Island' => array( 'value' => 'RE' ),
				'Romania' => array( 'value' => 'RO' ),
				'Russian Federation' => array( 'value' => 'RU' ),
				'Rwanda' => array( 'value' => 'RW' ),
				'Saint Kitts and Nevis' => array( 'value' => 'KN' ),
				'Saint Lucia' => array( 'value' => 'LC' ),
				'Saint Vincent and the Grenadines' => array( 'value' => 'VC' ),
				'San Marino' => array( 'value' => 'SM' ),
				'Sao Tome and Principe' => array( 'value' => 'ST' ),
				'Saudi Arabia' => array( 'value' => 'SA' ),
				'Senegal' => array( 'value' => 'SN' ),
				'Serbia' => array( 'value' => 'RS' ),
				'Serbia & Montenegro' => array( 'value' => 'CS' ),
				'Seychelles' => array( 'value' => 'SC' ),
				'Sierra Leone' => array( 'value' => 'SL' ),
				'Singapore' => array( 'value' => 'SG' ),
				'Slovakia' => array( 'value' => 'SK' ),
				'Slovenia' => array( 'value' => 'SI' ),
				'Solomon Islands' => array( 'value' => 'SB' ),
				'Somalia' => array( 'value' => 'SO' ),
				'South Africa' => array( 'value' => 'ZA' ),
				'South Georgia and the South Sandwich Islands' => array( 'value' => 'GS' ),
				'South Korea' => array( 'value' => 'KR' ),
				'Spain' => array( 'value' => 'ES' ),
				'Sri Lanka' => array( 'value' => 'LK' ),
				'St. Helena' => array( 'value' => 'SH' ),
				'St. Lucia' => array( 'value' => 'WL' ),
				'St. Pierre & Miquelon' => array( 'value' => 'PM' ),
				'St. Vincent & The Grenadines' => array( 'value' => 'WV' ),
				'Sudan' => array( 'value' => 'SD' ),
				'Suriname' => array( 'value' => 'SR' ),
				'Svalbard and Jan Mayen Islands' => array( 'value' => 'SJ' ),
				'Swaziland' => array( 'value' => 'SZ' ),
				'Sweden' => array( 'value' => 'SE' ),
				'Switzerland' => array( 'value' => 'CH' ),
				'Syrian Arab Republic' => array( 'value' => 'SY' ),
				'Taiwan' => array( 'value' => 'TW' ),
				'Tajikistan' => array( 'value' => 'TJ' ),
				'Tanzania' => array( 'value' => 'TZ' ),
				'Thailand' => array( 'value' => 'TH' ),
				'Togo' => array( 'value' => 'TG' ),
				'Tokelau' => array( 'value' => 'TK' ),
				'Tonga' => array( 'value' => 'TO' ),
				'Trinidad and Tobago' => array( 'value' => 'TT' ),
				'Tristan da Cunha' => array( 'value' => 'TA' ),
				'Tunisia' => array( 'value' => 'TN' ),
				'Turkey' => array( 'value' => 'TR' ),
				'Turkmenistan' => array( 'value' => 'TM' ),
				'Turks and Caicos Islands' => array( 'value' => 'TC' ),
				'Tuvalu' => array( 'value' => 'TV' ),
				'Uganda' => array( 'value' => 'UG' ),
				'Ukraine' => array( 'value' => 'UA' ),
				'United Arab Emirates' => array( 'value' => 'AE' ),
				'United Kingdom' => array( 'value' => 'GB' ),
				'Uruguay' => array( 'value' => 'UY' ),
				'US Minor Outlying Islands' => array( 'value' => 'UM' ),
				'Uzbekistan' => array( 'value' => 'UZ' ),
				'Vanuatu' => array( 'value' => 'VU' ),
				'Venezuela' => array( 'value' => 'VE' ),
				'Vietnam' => array( 'value' => 'VN' ),
				'Virgin Islands (British)' => array( 'value' => 'VG' ),
				'Virgin Islands (USA)' => array( 'value' => 'VI' ),
				'Wallis and Futuna Islands' => array( 'value' => 'WF' ),
				'Western Sahara' => array( 'value' => 'EH' ),
				'Western Samoa' => array( 'value' => 'WS' ),
				'Yemen' => array( 'value' => 'YE' ),
				'Yugoslavia' => array( 'value' => 'YU' ),
				'Zambia' => array( 'value' => 'ZM' ),
				'Zimbabwe' => array( 'value' => 'ZW' ),
				'Montenegro' => array( 'value' => 'ME' ),
				'Saint Martin' => array( 'value' => 'MF' ),
				'Saint Barth' => array( 'value' => 'BL' ),
				),
			'default_value' => '',
			),
		'currently_own'			=> array(
			'id'			=> 'custom_data_CurrentlyOwn',
			'classes'		=> '',
			'name'			=> 'CustomData[CurrentlyOwn]',
			'text'			=> 'Do you currently own, or have you ever owned a hot tub?',
			'input_type'	=> 'select',
			'options'		=> array(
				'Yes' => array( 'value' => 'Yes' ),
				'No' => array( 'value' => 'No' ),
				),
			'default_value'	=> '',
			),
		'interested_in_owning'	=> array(
			'id'			=> 'custom_data_InterestedInOwning',
			'classes'		=> '',
			'name'			=> 'CustomData[InterestedInOwning]',
			'text'			=> 'Are you interested in owning a Jacuzzi Hot Tub?',
			'input_type'	=> 'radio',
			'options'		=> array(
				'Yes' => array( 'value' => 'Yes' ),
				'No' => array( 'value' => 'No' ),
				),
			'default_value'	=> '',
			),
		'home_owner'			=> array(
			'id'			=> 'custom_data_HomeOwner',
			'classes'		=> '',
			'name'			=> 'CustomData[HomeOwner]',
			'text'			=> 'Do you own your home?',
			'input_type'	=> 'radio',
			'options'		=> array(
				'Yes' => array( 'value' => 'Yes' ),
				'No' => array( 'value' => 'No' ),
				),
			'default_value'	=> '',
			),
		'buy_time_frame'		=> array(
			'id'			=> 'custom_data_BuyTimeFrame',
			'classes'		=> '',
			'name'			=> 'CustomData[BuyTimeFrame]',
			'text'			=> 'When do you plan to purchase a hot tub?',
			'input_type'	=> 'radio',
			'options'		=> array(
				'Not sure' => array( 'value' => 'Not sure' ),
				'Within 1 month' => array( 'value' => 'Within 1 month' ),
				'1-3 months' => array( 'value' => '1-3 months' ),
				'4-6 months' => array( 'value' => '4-6 months' ),
				'6+ months' => array( 'value' => '6+ months' ),
				),
			'default_value'	=> '',
			),
		'product_use'			=> array(
			'id'			=> 'custom_data_ProductUse',
			'classes'		=> '',
			'name'			=> 'CustomData[ProductUse]',
			'text'			=> 'What is the primary reason you are considering the purchase of a hot tub?',
			'input_type'	=> 'radio',
			'options'		=> array(
				'Relaxation' => array( 'value' => 'Relaxation' ),
				'Pain Relief/Therapy' => array( 'value' => 'Pain Relief/Therapy' ),
				'Bonding/Family' => array( 'value' => 'Bonding/Family' ),
				'Backyard Entertaining' => array( 'value' => 'Backyard Entertaining' ),
				),
			'default_value'	=> '',
			),
		'condition'				=> array(
			'id'			=> 'custom_data_Condition',
			'classes'		=> '',
			'name'			=> 'CustomData[Condition]',
			'text'			=> 'Trade-In Condition?',
			'input_type'	=> 'select',
			'options'		=> array(
				'Excellent' => array( 'value' => 'Excellent' ),
				'Very Good' => array( 'value' => 'Very Good' ),
				'Good' => array( 'value' => 'Good' ),
				'Average' => array( 'value' => 'Average' ),
				'Poor' => array( 'value' => 'Poor' ),
				'Very Poor' => array( 'value' => 'Very Poor' ),
				),
			'default_value'	=> '',
			),
		'trade_in_year'			=> array(
			'id'			=> 'custom_data_TradeInYear',
			'classes'		=> '',
			'name'			=> 'CustomData[TradeInYear]',
			'text'			=> 'Trade-In Year',
			'input_type'	=> 'text',
			),
		'trade_in_make'			=> array(
			'id'			=> 'custom_data_TradeInMake',
			'classes'		=> '',
			'name'			=> 'CustomData[TradeInMake]',
			'text'			=> 'Trade-In Brand',
			'input_type'	=> 'text',
			'default_value'	=> '',
			),
		'product_id_list'		=> array(
			'id'			=> 'custom_data_ProductIdList',
			'classes'		=> '',
			'name'			=> 'CustomData[ProductIdList]',
			'text'			=> 'Which product are you interested in purchasing most?',
			'input_type'	=> 'select',
			'options'		=> array(
					'Not Specified' => array( 'value' => 0 ),
					'Constance' => array( 'value' => 1 ),
					'Victoria' => array( 'value' => 2 ),
					'Maxxus' => array( 'value' => 3 ),
					'Aspen' => array( 'value' => 4 ),
					'Optima' => array( 'value' => 5 ),
					'Cameo' => array( 'value' => 6 ),
					'Majesta' => array( 'value' => 7 ),
					'Altamar' => array( 'value' => 8 ),
					'Marin' => array( 'value' => 9 ),
					'Capri' => array( 'value' => 10 ),
					'Chelsee' => array( 'value' => 14 ),
					'Hamilton' => array( 'value' => 15 ),
					'Certa' => array( 'value' => 16 ),
					'Camden' => array( 'value' => 17 ),
					'Dover' => array( 'value' => 18 ),
					'Hartford' => array( 'value' => 19 ),
					'Hawthorne' => array( 'value' => 20 ),
					'Peyton' => array( 'value' => 21 ),
					'Edison' => array( 'value' => 22 ),
					'Denali' => array( 'value' => 23 ),
					'Tacoma' => array( 'value' => 24 ),
				),
			'default_value'	=> '',
			),
		'newsletter'			=> array(
			'id'			=> 'receive_email_campaigns',
			'classes'		=> 'editor choice',
			'name'			=> 'ReceiveEmailCampaigns',
			'text'			=> 'Please send me exclusive sale alerts from Sundance Spas',
			'input_type'	=> 'checkbox',
			'default_value'	=> 'Yes',
			),
	);
	
	if ( array_key_exists( $field, $args ) )
	{
		return $args[$field];
	}
	else
	{
		return $args;
	}
}
/* * * * * * * * Use this function to add Avala form fields to pages * * * * * * * * * * * * * * * * * *
 *
 *	Function to generate Avala Form Fields - requires avala_args() function
 *
 *	Usage:
 *
 *	<?php 							:
 *		avala_field(				:	start function
 *		'first_name',				:	field to display
 *		array(						:
 *			'classes for labels',	:	additional classes in array as needed
 *			'classes for fields'	:
 *		),							:
 *		true,						:	is this a required field?
 *		'label'						:	Show just the 'label', or the 'field', or both (NULL)
 *		'appended'					:	array of appended attributes, ie array('checked'=>"checked") would output checked="checked"
 *		'input_type'				:	see $input_type_array below for choices
 *		'text'						:	some label text to replace default text
 *		'select_default'			:	change the defaulted select option if needed
 *		);							:
 *	?>
 *
 *	Example simple usage without being a required field:
 *
 *		<?php avala_field( 'phone' ); ?>
 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
function avala_field( $avala_field, $additional_classes = NULL, $is_required = false, $show = NULL, $appended = NULL, $input_type = false, $text = false, $select_default = false ) {
	// field info as array
	$field				= avala_args( $avala_field );
	// field output by input type
	$input_type_array	= array('checkbox','color','date','datetime','datetime-local','email','hidden','month','number','password','radio','range','search','select','tel','text','time','url','week');
	$input_type			= ( in_array( $input_type, $input_type_array ) ? $input_type : $field['input_type'] );
	// additional classes to add to labels and fields/inputs
	if ( is_array( $additional_classes ) )
	{
		$class_label	= $additional_classes[0];
		$class_field	= $additional_classes[1];
	}
	else
	{
		$class_label	= $additional_classes;
		$class_field	= $additional_classes;
	}
	// CSS class for required fields
	$required		= 'required';

	// the label
	$str_label = '<label ';
	$str_label .= 'for="' . $field['id'] . '" ';
	$str_label .= 'class="' . $class_label . '" ';
	$str_label .= '>' . ( $text ? $text : $field['text'] );
	$str_label .= $is_required == true ? ' *' : '';
	$str_label .= '</label>';

	// the form field
	if ( $input_type != 'select' && $input_type != 'radio' && $input_type != 'checkbox' )
	{
		$str_field = '<input ';
		$str_field .= 'type="' . $input_type . '" ';
		$str_field .= 'id="' . $field['id'] . '" ';
		$str_field .= 'name="' . $field['name'] . '" ';
		$str_field .= 'class="' . $field['classes'] . ' ' . $class_field . ' ';
		$str_field .= $is_required == true ? $required . '" required="required"' : '" ' ;
		$str_field .= isset( $_POST[$field['name']] ) ? 'value="' . $_POST[$field['name']] . '" ' : 'value="' . $field['default_value'] . '" ' ;
		if ( is_array( $appended ) )
		{
			foreach ( $appended as $k => $v )
			{
				$str_field .= $k . '="' . $v . '" ';
			}
		}
		$str_field .= '/>';
	}

	if ( $input_type == 'select' )
	{
		$str_field = '<select ';
		$str_field .= 'id="' . $field['id'] . '" ';
		$str_field .= 'name="' . $field['name'] . '" ';
		$str_field .= 'class="' . $field['classes'] . ' ' . $class_field . ' ';
		$str_field .= $is_required == true ? $required . '" required="required"' : '" ' ;
		if ( is_array( $appended ) )
		{
			foreach ( $appended as $k => $v )
			{
				$str_field .= $k . '="' . $v . '" ';
			}
		}
		if ( isset( $field['default_value'] ) )
		{
			$str_field .= 'default_val="' . $field['default_value'] . '" ';
		}
		$str_field .= '>';
		$str_field .= '<option value="">' . ( !$select_default ? 'Please Select' : $select_default ) . '</option>';
		foreach ( $field['options'] as $k => $v )
		{
			$str_field .= '<option ';
			foreach ( $v as $m => $n )
			{
				$str_field .= $m . '="' . $n . '" ';
			}
			if ( isset( $_POST[$field['name']] ) && $v['value'] == $_POST[$field['name']] )
			{
				$str_field .= 'selected="selected" ';
			}
			else if ( $field['default_value'] && $v['value'] == $field['default_value'] )
			{
				$str_field .= 'selected="selected" ';
			}
			else {}
			$str_field .= '>' . $k . '</option>';
		}
		$str_field .= '</select>';
	}

	if ( $input_type == 'radio' )
	{
		$str_field = '<fieldset ';
		$str_field .= 'id="' . $field['id'] . '" ';
		$str_field .= 'class="' . $field['classes'] . ' ' . $class_field . ' " ';
		$str_field .= $field['classes'] == true ? $args['class_required'] . '" ' : '" ' ;
		if ( is_array( $appended ) )
		{
			foreach ( $appended as $k => $v )
			{
				$str_field .= $k . '="' . $v . '" ';
			}
		}
		$str_field .= '>';
		$i = 0;
		foreach ( $field['options'] as $k => $v )
		{
			$str_field .= '<label for="' . $field['id'] . '_' . $i . '" >';
			$str_field .= '<input type="radio" ';
			$str_field .= 'id="' . $field['id'] . '_' . $i . '" ';
			$str_field .= 'name="' . $field['name'] . '" ';
			$str_field .= $is_required == true ? 'required="required" ' : '' ;
			foreach ( $v as $m => $n )
			{
				$str_field .= ' ' . $m . '="' . $n . '"';
			}
			if ( isset( $_POST[$field['name']] ) && $v['value'] == $_POST[$field['name']] )
			{
				$str_field .= 'checked="checked" ';
			}
			else if ( $field['default_value'] && $v['value'] == $field['default_value'] )
			{
				$str_field .= 'checked="checked" ';
			}
			else {}
			$str_field .= '>' . $k . '</label>';
			$i++;
		}
		$str_field .= '</fieldset>';
	}
	if ( $input_type == 'checkbox' )
	{
		$str_field = '<label for="' . $field['id'] . '" class="' . $field['classes'] . ' ' . $class_label . '">';
		$str_field .= '<input id="' . $field['id'] . '" class="' . $field['classes'] . ' ' . $class_field . '" name="' . $field['name'] . '" value="' . $field['default_value'] . '" type="' . $input_type . '" ';
		$str_field .= ( $select_default == true ? 'checked="checked"' : '/>&nbsp;' );
		$str_field .= ( $text ? $text : $field['text'] );
		$str_field .= '</label>';
	}
	$str = $str_label . $str_field;
	if ( $show == 'label' )
	{
		print( $str_label );
	}
	else if ( $show == 'field' )
	{
		print( $str_field );
	}
	else
	{
		print( $str );
	}
}
function avala_get_form_url( $return = 'string' ) {
	$url = get_permalink();
	$title = get_the_title();
	if ( $_POST['form_url_id'] && !empty( $_POST['form_url_id'] ) ) {
		$url = get_permalink( $_POST['form_url_id'] );
		$title = get_the_title( $_POST['form_url_id'] );
	}
	$link_string = '<a href="' . $url . '" target="_blank">' . $title . '</a>';
	if ( $return == 'string' )
		return $link_string;
	if ( $return == 'url' )
		return $url;
	if ( $return == 'title' )
		return $title;
}
function get_geo_api_info() {
	$a = geo_data();
	return $a;
}
/* * * * * * * * * * * * PPC Campaign Parsing * * * * * * * * * * * * * *
 *																		*
 *	The following functions are used to parse PPC Campaign info			*
 *																		*
 *	(1) ppc_campaign_override( $k )										*
 *			Edit this function to add campaign IDs and Names			*
 *	(2) get_ppc_data()													*
 *			Edit this function to parse cookies for diff campaigns		*
 *																		*
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
function ppc_campaign_override( $k ) {
	// Edit this array with PPC campaign IDs and names as needed
	$a = array(
		7149 => 'Adwords',
		7150 => 'Bing',
		);
	if ( array_key_exists( $k , $a ) )
	{
		return $a[$k];
	}
	return false;
}
function get_ppc_data() {
    $a = array();
    // Parse Google Cookie
    if ( $_COOKIE['__utmz'] ) 
	{
		$cCookie = explode( '.', $_COOKIE['__utmz'] );
		$cValues = explode( '|', $cCookie[4] );
		foreach ( $cValues as $cv )
		{
			$parts = explode( '=', $cv );
			$a[$parts[0]] = $parts[1];
		}
		$a_utma = explode( '.', $_COOKIE['__utma'] );
		$a['utma'] = $a_utma[5];
	}
	// if URL string 's_cid' is passed, use that instead for campaign ID
	if ( isset( $_POST['CustomData']['s_cid'] ) )
	{
		$a['utmcmd'] = ( ppc_campaign_override( $_POST['CustomData']['s_cid'] ) ) ? ppc_campaign_override( $_POST['CustomData']['s_cid'] ) : $a['utmcmd'];
	}
	return $a;
}