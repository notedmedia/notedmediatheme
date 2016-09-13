<?php
$w = $_GET['width'];

$device = 'desktop';
if ($w < 600) {
	$device = 'mobile';
}

if ($_COOKIE['device'] != $device) {
	setcookie('device', $device, time() + (60*60*24), '/');
	echo 'new device set to ' . $_COOKIE['device'];
} else {
	echo 'ok - remember this as a ' . $_COOKIE['device'];
}
?>