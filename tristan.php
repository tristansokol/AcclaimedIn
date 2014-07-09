<?php 
ini_set('display_errors',1); 
error_reporting(E_ALL);
/*

*/?>
<head>
	<style type="text/css">
		h1{
			margin-top: 0px;
			padding-top: 0px;
			font-size: 2rem;
			padding-bottom:0px;
		}
		body{
			text-align: center;
			font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		}
	</style>
</head>
<body>
<!-- 	<a href="/hackathon/tristan.php?x=view">view badges</a>
-->	<br>
<!-- <h1>LinkedIn Acclaim Badger!</h1> -->
<?php
	//DUmmy variables
// $_GET['aname'] = "tristan.sokol@pearson.com";
// $_GET['apass'] = "password";
// $_GET['bname'] = "magic Demo Badge";
// $_GET['bdesc'] = "A really good badge";
if (!(isset($_GET['aname'])&&isset($_GET['apass'])&&isset($_GET['bname'])&&isset($_GET['bdesc']))){
	echo "get variables are wrong";
	var_dump($_GET);
	exit();
}
	var_dump($_GET);

?>

<?php
	//GET PROFILE
$context = stream_context_create(array(
	'http' => array(
		'header'  => "Authorization: Basic " . base64_encode($_GET['aname'].":".$_GET['apass'])
		)
	));
$json = file_get_contents('https://sandbox.youracclaim.com/api/v1/users/self', false, $context);
$data = json_decode($json);
	//var_dump($data);
$auserid = $data->{'data'}->{'id'};
$afname = $data->{'data'}->{'first_name'};
$alname = $data->{'data'}->{'last_name'};
//echo 'SUCCESFFULLY GOT USER DATA: ', $afname, ' ', $alname, ' ',$auserid;
//echo "Hi $afname, $alname!"
?>
<br>

<?php
	//MAKE A BADGE Template
// $context = stream_context_create(array(
// 	'http' => array(
// 		'method' => "POST",
// 		'content' => '{
// 			"template_type": "experience",
// 			"description": "'.addslashes($_GET['bdesc']).'",
// 			"name":"'. addslashes($_GET['bname']).'"
// 		}',
// 		'header'  => "Authorization: Basic %%%API KEY%%% \r\n".
// 		"Content-type: application/json \r\n"
// 		)
// 	));
// $json = file_get_contents('https://sandbox.youracclaim.com/api/v1/organizations/%%%apikey%%%/badge_templates', false, $context);
// $data = json_decode($json);
// $bid = $data->{'data'}->{'id'};
// //echo 'BADGE TEMPLATE ID GOT SUCCESFFULLY:'.$bid;
?>
<?php
// // File and new size
$filename = $_GET['biurl'];

// Content type
//header('Content-Type: image/png');

// Get new sizes
list($width, $height) = getimagesize($filename);
$newwidth = 352;
$newheight = 352;

// Load
$thumb = imagecreatetruecolor($newwidth, $newheight);
$source = imagecreatefrompng($filename);

// Resize
imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

// Output
imagepng($thumb,'scratch.png');
?>
<pre>
<?php
// Get cURL resource
$curl = curl_init();
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, array(
	CURLOPT_RETURNTRANSFER => 1,
	CURLOPT_URL => 'https://sandbox.youracclaim.com/api/v1/organizations/48e53e0c-3a70-4a32-939d-43d82567b145/badge_templates',
	CURLOPT_USERAGENT => 'Codular Sample cURL Request',
	CURLOPT_POST => 1,
	CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
	CURLOPT_USERPWD => 'jYBtmdf98KDhWpUhtwOg:',
	CURLOPT_HTTPHEADER => array(
		'Authorization' => 'Basic allCdG1kZjk4S0RoV3BVaHR3T2c6',
		'Content-type' => 'application/json'
		),
	CURLOPT_POSTFIELDS => array(
		'description' => $_GET['bdesc'],
		'name' => $_GET['bname'],
		'template_type' => 'experience',
		'image' => "@scratch.png"
		)
	));


    $content = curl_exec ( $curl );
    $err = curl_errno ( $curl );
    $errmsg = curl_error ( $curl );
    $header = curl_getinfo ( $curl );
    $httpCode = curl_getinfo ( $curl, CURLINFO_HTTP_CODE );


    $header ['errno'] = $err;
    $header ['errmsg'] = $errmsg;
    $header ['content'] = $content;
    //var_dump($header);
// Send the request & save response to $resp
// Close request to clear up some resources
//var_dump($content);
$bid = json_decode($content)->{'data'}->{'id'};
curl_close($curl);
?>
<br>
<?php
	//ISSUE A BADGE
$context = stream_context_create(array(
	'http' => array(
		'method' => "POST",
		'content' => '{
			"recipient_email": "'.$_GET['aname'].'",
			"user_id": "'.$auserid.'",
			"badge_template_id": "'.$bid.'",
			"issued_at": "'.date('Y-m-d G:i:s').' -0800",
			"issued_to_first_name": "'.$afname.'",
			"issued_to_last_name": "'.$alname.'",
			"expires_at": null
		}',
		'header'  => "Authorization: Basic %%%api key%%% \r\n".
		"Content-type: application/json \r\n"
		)
	));
$json = file_get_contents('https://sandbox.youracclaim.com/api/v1/organizations/%%%api key%%%/badges', false, $context);
$data = json_decode($json);
$badgeid = $data->{'data'}->{'id'};

//echo 'BADGE ASSIGNED SUCCESFULLY: ',$badgeid;
echo "you just got a new badge: ",$_GET['bname'];
?>
<br>
<?php
	//Accept The BADGE
$context = stream_context_create(array(
	'http' => array(
		'method' => "PUT",
		'content' => '{
			"recipient_email": "'.$_GET['aname'].'",
			"user_id": "'.$auserid.'",
			"badge_template_id": "'.$bid.'",
			"issued_at": "'.date('Y-m-d G:i:s').' -0800",
			"issued_to_first_name": "'.$afname.'",
			"issued_to_last_name": "'.$alname.'",
			"expires_at": null
		}',
		'header' => "Authorization: Basic " . base64_encode($_GET['aname'].":".$_GET['apass'])." \r\n".
		"Content-type: application/json \r\n"
		)
	));
$json = file_get_contents('https://sandbox.youracclaim.com/api/v1/users/self/badges/'.$badgeid.'/accept', false, $context);
$data = json_decode($json);
	//$badgeid = $data->{'data'}->{'id'};
	//var_dump($data);
//echo 'BADGE ACCEPTED SUCCESFULLY: ';
?>
<br>
<?php
	//GET PROFILE
$context = stream_context_create(array(
	'http' => array(
		'header'  => "Authorization: Basic " . base64_encode($_GET['aname'].":".$_GET['apass'])
		)
	));
$json = file_get_contents('https://sandbox.youracclaim.com/api/v1/users/self/badges', false, $context);
$data = json_decode($json);
	//var_dump($data);
foreach ($data->{'data'} as $item) {
	echo '<h1>'.$item->{'badge_template'}->{'name'},"</h1> ";
	echo '<img src="'.$item->{'badge_template'}->{'image_url'}.'"height=200 width=200/>';
	echo '<br>';
}

?>
</body>
<?php /