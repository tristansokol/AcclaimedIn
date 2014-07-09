<?php
// // File and new size
$filename = 'http://m.c.lnkd.licdn.com/mpr/mpr/p/1/000/02f/263/0230e93.png';

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
	CURLOPT_USERPWD => '%%%%yourapiKe%%%%%:',
	CURLOPT_HTTPHEADER => array(
		Authorization => '%%%% fills this in too %%%%%',
		Content-type => 'application/json'
		),
	CURLOPT_POSTFIELDS => array(
		description => 'badge',
		name => 'curl badge',
		template_type => 'skill',
		image => "@scratch.png"
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
    var_dump($header);
// Send the request & save response to $resp
// Close request to clear up some resources

curl_close($curl);
?>
</pre>