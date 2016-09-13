<?php
session_start();

$d = $_GET['d'];
$e = $_GET['e'];
$c = $_GET['c'];


if ($c == 'device') {
	$w = $d;
	$device = 'desktop';
	if ($w < 600) {
		$device = 'mobile';
	}
	
	if ($_SESSION['device'] != $device) {
		$_SESSION['device'] = $device;
		echo 'new device';
	} else {
		echo 'ok, set to ' . $device;
	}
}
?>