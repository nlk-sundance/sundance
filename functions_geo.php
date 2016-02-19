<?php
/**
 * GEO Functions
 *
 */

add_action( 'init', 'set_geo_cookie' );
add_action( 'wp_head', 'geo_location_meta' );
//add_action( 'wp_enqueue_scripts', 'geo_enqueued_scripts' ); // We will not be using Google API for now


/**
 * Geo Data Cookie
 */
function set_geo_cookie() {
	if ( ! is_admin() && ( ! isset($_COOKIE['georesult']) || ( isset($_GET['geo']) && $_GET['geo'] == 'reset' ) ) ) {
		$a = geo_data(); // Re-run geo lookup if no cookie
		$json = json_encode( $a );
		setcookie("georesult", $json, time()+60*60*24*30, "/");
	}
	if ( ! is_admin() && isset($_COOKIE['georesult']) && isset($_GET['geo']) && $_GET['geo'] == 'remove' ) {
		setcookie("georesult", '', time() - 60*60*24); // remove cookie
	}
}

/**
 * Google API
 */
function geo_enqueued_scripts() {
	wp_enqueue_script('google_places_library', 'https://maps.googleapis.com/maps/api/js?libraries=places');// Google Geo Scripts
}

if ( ! function_exists('get_the_ip') ) :
/**
 * Get IP Address
 */
function get_the_ip() {
	$s = $_SERVER['QUERY_STRING'];
	parse_str($s, $o);
	if ( isset( $_GET['ip'] ) ) :
		$ip = $_GET['ip'];
	elseif ( array_key_exists('ip', $o) ) :
		$ip = $o['ip'];
	elseif ( !empty($_SERVER['HTTP_CLIENT_IP']) ) :
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	elseif ( !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ) :
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	else :
		$ip = $_SERVER['REMOTE_ADDR'];
	endif;
	if ( !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE) || !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_RES_RANGE) || $ip == '127.0.0.1' ) {
		return false;
	}
	return $ip;
}
endif;

if ( ! function_exists('geo_data') ) :
/**
 * GEO Lookup Database
 */
function geo_data( $zip = false, $debug = false ) {
	if ( is_admin() )
		return false; // do nothing if viewing admin pages (geo not needed)

	if ( isset($_COOKIE['georesult']) && !isset($_GET['geo']) ) {
		$a = json_decode(stripcslashes($_COOKIE['georesult']), true);
		return $a; // Geo Data already set in cookie so do not re-run lookup...
	}

	global $wpdb;
	$ip = get_the_ip();
	$zip = ( ( isset($_POST['zip']) && !empty($_POST['zip']) ) ? $_POST['zip'] : ( isset($_GET['zip']) && !empty($_GET['zip']) ? $_GET['zip'] : $zip ) );
	$zip = clean_zip( $zip ); // clean the zip for geo search
	$a = array();
	$rows = false;
	// Build lookup queries
	if ( !empty($zip) ) :
		$rows = $wpdb->get_results(
			"
			SELECT * 
			FROM geoip_locations 
			WHERE postalCode = '$zip'
			LIMIT 1
			"
		);
	elseif ( $ip ) :
		$rows = $wpdb->get_results(
			"
			SELECT gl.* 
			FROM geoip_locations gl 
			LEFT JOIN geoip_blocks gb 
				ON gb.locId = gl.locId 
			WHERE gb.startIpNum <= INET_ATON( '$ip' ) 
				AND gb.endIpNum >= INET_ATON( '$ip' ) 
			LIMIT 1
			"
		);
	endif;
	// Process results
	if ( $rows ) :
		foreach ($rows as $row) {
			$a = array(
				'locId'			=>	$row->locId,
				'country'		=>	$row->country,
				'region'		=>	$row->region,
				'city'			=>	$row->city,
				'postalCode'	=>	$row->postalCode,
				'latitude'		=>	$row->latitude,
				'longitude'		=>	$row->longitude,
				'metroCode'		=>	$row->metroCode,
				'areacode'		=>	$row->areaCode,
				'ip'			=>	$ip,
				);
		}
	else : 
		$a = array(
			'locId'				=>	0,
			'country'			=>	'US',
			'region'			=>	'',
			'city'				=>	'',
			'postalCode'		=>	( $zip ? $zip : '00000' ),
			'latitude'			=>	'',
			'longitude'			=>	'',
			'metroCode'			=>	'',
			'areacode'			=>	'',
			'ip'				=>	'',
			);

	endif;
	return $a;
}
endif;

if ( ! function_exists('clean_zip') ) :
/**
 * Prepare postal code for lookup
 */
function clean_zip( $zip ) {
	if ( !$zip )
		return false;
	$zip = strtoupper( preg_replace( "/\s/", '', $zip ) );
	$valid_country = false;
	$reg	=	array(
		"US"	=>	"^\d{5}([\-]?\d{4})?$",
		"CA"	=>	"^([ABCEGHJKLMNPRSTVXY]\d[ABCEGHJKLMNPRSTVWXYZ])\ {0,1}(\d[ABCEGHJKLMNPRSTVWXYZ]\d)$",
		"UK"	=>	"^(GIR|[A-Z]\d[A-Z\d]??|[A-Z]{2}\d[A-Z\d]??)[ ]??(\d[A-Z]{2})$",
		"DE"	=>	"\b((?:0[1-46-9]\d{3})|(?:[1-357-9]\d{4})|(?:[4][0-24-9]\d{3})|(?:[6][013-9]\d{3}))\b",
		"FR"	=>	"^(F-)?((2[A|B])|[0-9]{2})[0-9]{3}$",
		"IT"	=>	"^(V-|I-)?[0-9]{5}$",
		"AU"	=>	"^(0[289][0-9]{2})|([1345689][0-9]{3})|(2[0-8][0-9]{2})|(290[0-9])|(291[0-4])|(7[0-4][0-9]{2})|(7[8-9][0-9]{2})$",
		"NL"	=>	"^[1-9][0-9]{3}\s?([a-zA-Z]{2})?$",
		"ES"	=>	"^([1-9]{2}|[0-9][1-9]|[1-9][0-9])[0-9]{3}$",
		"DK"	=>	"^([D-d][K-k])?( |-)?[1-9]{1}[0-9]{3}$",
		"SE"	=>	"^(s-|S-){0,1}[0-9]{3}\s?[0-9]{2}$",
		"BE"	=>	"^[1-9]{1}[0-9]{3}$"
	);
	// Check if we can validate the zip against one of the above countries
	foreach ( $reg as $k => $v ) {
		if ( preg_match( "/" . $v . "/i", $zip ) ) {
			$valid_country = $k;
			break;
		}
	}
	// For US or CA, clean the zip for geo search
	if ( $valid_country == 'US' ) :
		list($clean_zip) = explode('-', $zip);
	elseif ( $valid_country == 'CA' ) :
		$clean_zip = substr( $zip, 0, 3 );
	else :
		$clean_zip = $zip;
	endif;
	$clean_zip = strtolower( $clean_zip );
	return $clean_zip;
}
endif;

/**
 * Geo Location Meta Data
 */
function geo_location_meta() {
	$a = array();
	$str = '';
	if ( isset($_COOKIE['georesult']) ) {
		$a = json_decode(stripcslashes($_COOKIE['georesult']), true);
		$str = 'cookie:: ';
	} else {
		$a = geo_data();
		$str = 'database:: ';
	}
	echo '<meta name="ICBM" content="'.$a['latitude'].', '.$a['longitude'].'">';
	echo '<meta name="geo.position" content="'.$a['latitude'].';'.$a['longitude'].'">';
	echo '<meta name="geo.placename" content="'.$a['city'].', '.$a['region'].', '.$a['country'].' '.$a['postalCode'].'">';
	echo '<meta name="geo.region" content="'.$a['country'].'-'.$a['region'].'">';
	echo '<meta name="geo.language" content="'.$_SERVER['HTTP_ACCEPT_LANGUAGE'].'">';
	if ( is_array($a) ) {
		foreach ($a as $k => $v) {
			$str .= $k . ': ' . $v . '; ';
		}
		echo '<meta name="geo.lookup.result" content="'.$str.'">';
	}
}




// ------------------------------------------------------------------------------------------


/**
 * MSRP Test Market
 *
 * @param array			geo_data()
 * @return boolean		geo_data IN test_market
 */
function msrp_display() {
	$test_market = array(
		'postalCodes' => array(
				// San Francisco zips
				'93901', '93902', '93905', '93906', '93907', '93912', '93915', '93921', '93922', '93933', '93940', '93942', '93943', '93944', '93950', '93953', '93955', '93962', '94002', '94005', '94010', '94011', '94014', '94015', '94016', '94017', '94018', '94019', '94020', '94021', '94022', '94023', '94024', '94025', '94026', '94027', '94028', '94030', '94035', '94037', '94038', '94039', '94040', '94041', '94042', '94043', '94044', '94060', '94061', '94062', '94063', '94064', '94065', '94066', '94070', '94074', '94080', '94083', '94085', '94086', '94087', '94088', '94089', '94102', '94103', '94104', '94105', '94107', '94108', '94109', '94110', '94111', '94112', '94114', '94115', '94116', '94117', '94118', '94119', '94120', '94121', '94122', '94123', '94124', '94125', '94126', '94127', '94128', '94129', '94130', '94131', '94132', '94133', '94134', '94137', '94139', '94140', '94141', '94142', '94143', '94144', '94145', '94146', '94147', '94151', '94158', '94159', '94160', '94161', '94163', '94164', '94172', '94177', '94188', '94301', '94302', '94303', '94304', '94305', '94306', '94309', '94401', '94402', '94403', '94404', '94497', '94501', '94502', '94503', '94505', '94506', '94507', '94509', '94510', '94511', '94512', '94513', '94514', '94516', '94517', '94518', '94519', '94520', '94521', '94522', '94523', '94524', '94525', '94526', '94527', '94528', '94529', '94530', '94531', '94533', '94534', '94535', '94536', '94537', '94538', '94539', '94540', '94541', '94542', '94543', '94544', '94545', '94546', '94547', '94548', '94549', '94550', '94551', '94552', '94553', '94555', '94556', '94557', '94559', '94560', '94561', '94563', '94564', '94565', '94566', '94568', '94569', '94570', '94571', '94572', '94575', '94577', '94578', '94579', '94580', '94581', '94582', '94583', '94585', '94586', '94587', '94588', '94589', '94590', '94591', '94592', '94595', '94596', '94597', '94598', '94601', '94602', '94603', '94604', '94605', '94606', '94607', '94608', '94609', '94610', '94611', '94612', '94613', '94614', '94615', '94617', '94618', '94619', '94620', '94621', '94622', '94623', '94624', '94649', '94659', '94660', '94661', '94662', '94666', '94701', '94702', '94703', '94704', '94705', '94706', '94707', '94708', '94709', '94710', '94712', '94720', '94801', '94802', '94803', '94804', '94805', '94806', '94807', '94808', '94820', '94850', '94901', '94903', '94904', '94912', '94913', '94914', '94915', '94920', '94924', '94925', '94930', '94933', '94938', '94939', '94941', '94942', '94945', '94946', '94947', '94948', '94949', '94950', '94953', '94954', '94955', '94956', '94957', '94960', '94963', '94964', '94965', '94966', '94970', '94973', '94974', '94975', '94976', '94977', '94978', '94979', '94998', '94999', '95001', '95002', '95003', '95004', '95005', '95006', '95007', '95008', '95009', '95010', '95011', '95012', '95013', '95014', '95015', '95017', '95018', '95019', '95020', '95021', '95026', '95030', '95031', '95032', '95033', '95035', '95036', '95037', '95038', '95039', '95041', '95042', '95044', '95045', '95046', '95050', '95051', '95052', '95053', '95054', '95055', '95056', '95060', '95061', '95062', '95063', '95064', '95065', '95066', '95067', '95070', '95071', '95073', '95076', '95077', '95101', '95103', '95106', '95108', '95109', '95110', '95111', '95112', '95113', '95115', '95116', '95117', '95118', '95119', '95120', '95121', '95122', '95123', '95124', '95125', '95126', '95127', '95128', '95129', '95130', '95131', '95132', '95133', '95134', '95135', '95136', '95138', '95139', '95140', '95141', '95148', '95150', '95151', '95152', '95153', '95154', '95155', '95156', '95157', '95158', '95159', '95160', '95161', '95164', '95170', '95172', '95173', '95190', '95191', '95192', '95193', '95194', '95196', '95416', '95433', '95476', '95487', '95620', '95625', '95641', '95680', '95687', '95688', '95690', '95696',
				// Columbus zips
				'43001', '43002', '43003', '43004', '43007', '43009', '43010', '43013', '43015', '43016', '43017', '43018', '43021', '43026', '43029', '43031', '43032', '43035', '43036', '43040', '43041', '43044', '43045', '43047', '43054', '43060', '43061', '43062', '43064', '43065', '43066', '43067', '43068', '43069', '43073', '43074', '43077', '43081', '43082', '43084', '43085', '43086', '43103', '43106', '43109', '43110', '43112', '43116', '43117', '43119', '43123', '43125', '43126', '43136', '43137', '43140', '43143', '43146', '43147', '43151', '43153', '43162', '43164', '43194', '43195', '43199', '43201', '43202', '43203', '43204', '43205', '43206', '43207', '43209', '43210', '43211', '43212', '43213', '43214', '43215', '43216', '43217', '43218', '43219', '43220', '43221', '43222', '43223', '43224', '43226', '43227', '43228', '43229', '43230', '43231', '43232', '43234', '43235', '43236', '43240', '43251', '43260', '43266', '43268', '43270', '43271', '43272', '43279', '43287', '43291', '43319', '43336', '43344', '45368', '45369',
				// Testing
				//'92101', '92103', '91945', '90001', '91710', '92589', '92883', '10116', '06492', '10400', '01901', '91709',
			),
		'cities' => array(
				'CA' => array(
					'San Francisco',
					//'San Diego', //for testing
					//'Chino', //for testing
					),
				'OH' => array( 
					'Columbus'
					),
			),
		);
	$a = geo_data();
	if ( isset($test_market['cities'][ $a['region'] ][ $a['city'] ]) || in_array( $a['postalCode'], $test_market['postalCodes'] ) )
		return true;
	return false;
}




