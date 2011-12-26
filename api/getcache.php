<?php
if (!isset($_GET['jsoncallback'])) {
	$_GET['jsoncallback'] = '';
}
echo $_GET['jsoncallback'] . '(' . file_get_contents("cache") . ');';
?>