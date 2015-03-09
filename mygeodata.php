<?php

function geo_data_mysql( $ip ) {

	$a = false;

	$LIVEHOST		= array( 'www.sundancespas.com', 'www.sundancespas.ca', 'beta.sundancespas.com' );
	if ( !in_array( $_SERVER['SERVER_NAME'], $LIVEHOST ) ) {
		// local
		$mysqli = new mysqli("localhost", "root", "", "nlk_geoip");
	}
	else {
		// live or beta
		$mysqli = new mysqli("localhost", "sundance_geoip", "r4e3w2q1!", "sundance_geoip");
	}

	if ($mysqli->connect_errno) {

		$error = "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;

		return $error;
	}

	if ( !filter_var( $ip, FILTER_VALIDATE_IP ) || $ip == '127.0.0.1' ) {
		$ip = '68.105.255.166';		// ninthlink ip
	}
	else {
		// IP is valid
	}

	$query = "SELECT gl.* FROM geoip_locations gl LEFT JOIN geoip_blocks gb ON gb.locId = gl.locId WHERE gb.startIpNum <= INET_ATON( ? ) AND gb.endIpNum >= INET_ATON( ? ) LIMIT 1";

	if ( $stmt = $mysqli->prepare( $query ) ) {

		$stmt->bind_param( "ss", $ip, $ip );

		$stmt->execute();

		$stmt->bind_result( $locId, $country, $region, $city, $postalCode, $latitude, $longitude, $metroCode, $areaCode );

		while ( $stmt->fetch() ) {

			$a = array(

				'locId'			=>	$locId,
				'country'		=>	$country,
				'region'		=>	$region,
				'city'			=>	$city,
				'postalCode'	=>	$postalCode,
				'latitude'		=>	$latitude,
				'longitude'		=>	$longitude,
				'metroCode'		=>	$metroCode,
				'areacode'		=>	$areaCode,
				'ip'			=>	$ip,

				);

		}

		$stmt->close();

	}

	$mysqli->close();

	return $a;

}


//MaxMind GeoIP2 API call using CURL
function geo_data( $ip ) {
	
	$username = '66659';
	$password = 'FJv62Mz6ezIB';

	if ( $ip == '127.0.0.1' )
		$ip = 'me';

	// Use CURL to get MaxMind Geo Data
	$ch = curl_init('https://geoip.maxmind.com/geoip/v2.0/city/' . $ip . '');
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($ch);
	$a = json_decode($result, true);
	return $a;
}

if ( isset($_COOKIE['geoData']) ) {
	$cookie = unserialize( urldecode( $_COOKIE['geoData'] ) );
}

$ip = isset($_GET['ip']) ? 
		$_GET['ip'] : 
		( ( isset( $cookie ) && filter_var( $cookie['ip'], FILTER_VALIDATE_IP ) ) ?
			$cookie['ip'] :
			$_SERVER['REMOTE_ADDR'] );

?>
<!DOCTYPE html>
<html>
<body>
	<details>
		<summary>MySQL DB Results</summary>
		<pre>
<?php print_r( geo_data_mysql( $ip ) ); ?>
		</pre>
	</details>
	<details>
		<summary>Web Service API Results</summary>
		<pre>
<?php print_r( geo_data( $ip ) ); ?>
		</pre>
	</details>
</body>
</html>

