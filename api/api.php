<?php
$ch = curl_init("http://imgur.com/");

curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.2 (KHTML, like Gecko) Chrome/18.6.872.0 Safari/535.2 UNTRUSTED/1.0 3gpp-gba UNTRUSTED/1.0");
curl_setopt($ch, CURLOPT_REFERER, "http://imgur.com");
// pretend that it user is chrome 18.6.872.0 from the homepage

curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$out = preg_replace('/[ \t]+/', ' ', preg_replace('/\s*$^\s*/m', "\n", curl_exec($ch)));
curl_close($ch);

$order   = array("\r\n", "\n", "\r");
$replace = '';

// Processes \r\n's first so they aren't converted twice.
$out = str_replace($order, $replace, $out);

// echo $out;

$content_explode1 = multipleExplode(array('<div id="view-images">', '</div> </div><div id="footer">'), $out);
echo "<pre>";
$links = array_filter(multipleExplode(array('<a ', '</a>'), $content_explode1[1]));
foreach ($links as $i => $html_element) {
	list($blank, $imgur[$i]['link'], $imgur[$i]['thumbnail'], $imgur[$i]['title']) = multipleExplode(array('href="', '"><img src="', '" title="', '" alt="" />'), $html_element);
	$imgur[$i]['link'] = "http://imgur.com".$imgur[$i]['link'];

	// get full res image
	// 12/25/11 - orig image is *b.jpg, orig image is *.png

	$image_array = explode("b.jpg", $imgur[$i]['thumbnail']);
	$imgur[$i]['orig_image'] = $image_array[0].".gif";

/*	$ch = curl_init($imgur[$i]['link']);

	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.2 (KHTML, like Gecko) Chrome/18.6.872.0 Safari/535.2 UNTRUSTED/1.0 3gpp-gba UNTRUSTED/1.0");
	curl_setopt($ch, CURLOPT_REFERER, "http://imgur.com");
	// pretend that it user is chrome 18.6.872.0 from the homepage

	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RANGE, 0-4096);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
*/
	// if ($i <= 5) {
	//	$out = preg_replace('/[ \t]+/', ' ', preg_replace('/\s*$^\s*/m', "\n", curl_exec($ch)));

		//$out = curl_exec($ch);
		//echo $out;
		// curl_close($ch);
/*
		$order   = array("\r\n", "\n", "\r");
		$replace = '';

		// Processes \r\n's first so they aren't converted twice.
		$out = str_replace($order, $replace, $out);

		list($blank, $imgur[$i]['image'], $blank) = multipleExplode(array('<link rel="image_src" href="', '" /> <link rel="stylesheet"'), $out);
	}
*/

}

print_r($imgur);
$json_imgur = json_encode($imgur);

$filename = "cache";

if (isset($imgur[5])) {
	file_put_contents($filename, $json_imgur);
}

function multipleExplode($delimiters = array(), $string = ''){ 

    $mainDelim=$delimiters[count($delimiters)-1]; // dernier 
    
    array_pop($delimiters); 
    
    foreach($delimiters as $delimiter){ 
        $string= str_replace($delimiter, $mainDelim, $string); 
    } 

    $result= explode($mainDelim, $string); 
    return $result; 
}
?>