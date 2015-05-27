<?php
/**
 * GEO Functions
 *
 */

if ( ! function_exists('get_the_ip') ) :
function get_the_ip() {

	$s = $_SERVER['QUERY_STRING'];
	parse_str($s, $o);

	if ( array_key_exists('ip', $o) ) :
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


function geo_data( $zip = false, $debug = false ) {

	if ( is_admin() )
		return false; // do nothing if viewing admin pages (geo not needed)

	global $wpdb;

	$ip = get_the_ip();
	$zip = ( isset( $_POST['PostalCode'] ) ) ? $_POST['PostalCode'] : ( isset( $_GET['zip'] ) ) ? $_GET['zip'] : $zip;

	$a = array();
	$rows = false;

	if ( $zip ) :
		
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
			WHERE gb.startIpNum <= INET_ATON( $ip ) 
				AND gb.endIpNum >= INET_ATON( $ip ) 
			LIMIT 1
			"
		);

	endif;

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
			'postalCode'		=>	'00000',
			'latitude'			=>	'',
			'longitude'			=>	'',
			'metroCode'			=>	'',
			'areacode'			=>	'',
			'ip'				=>	'',
			);

	endif;

	return $a;
}


?>