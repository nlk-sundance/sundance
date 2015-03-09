<?php

$thedata = null;

if ( isset($_POST['leaddata']) && !empty($_POST['leaddata']) ) {

	$data = preg_replace("/[\r\n]+/", "", $_POST['leaddata']);

	$apiUrl = array(
		'live' => 'http://sundance.aimbase.com/FormBuilder/api/Lead',
		'test' => 'http://sundanceqa.aimbase.com/FormBuilder/api/Lead'
	);

	if ( isset($_POST['live']) && $_POST['live'] == "true" ) {
		$sendTo = $apiUrl['live'];		
	}
	else {
		$sendTo = $apiUrl['test'];
	}
	
	$result = null;
	
	// do curl
	$ch = curl_init($sendTo);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json', 'Content-Length: ' . strlen($data) ) );
	$apiResult = curl_exec($ch);
	$httpResult = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$result = 'JSON Result: ' . $httpResult . '->' . $apiResult;
	curl_close($ch);

	if ( $httpResult != 201 ) {
		$thedata = $data;
	}

}

?>

<html>
	<head>
		<title>Add Lead to Avala</title>
		<style type="text/css">
		</style>
	</head>
	<body>
		<h4>Enter raw lead data below. One lead at a time.</h4>
		<?php if ( isset($result) && !empty($result) ) {
			echo '<p>' . $result . '</p>';
		} ?>
		<form action="#" method="post" id="addLeads">
			<textarea id="leaddata" name="leaddata" cols="100" rows="10" required="required" wrap="soft"><?php echo $thedata; ?></textarea><br />
			<input type="checkbox" name="live" id="live" value="true" checked="checked" /> Submit to Live API?<br />
			<input type="submit" value="GO" />
		</form>
	</body>
</html>
