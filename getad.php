<?php
require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );
global $wpdb;
$table_name = $wpdb->prefix . 'nm_ads';
$sql = 'select * from ' . $table_name . ' where ad_id = ' . $_GET['ad'] . ' limit 0, 1';
$ads = $wpdb->get_row($sql);

if ($ads) {
	if ($ads->ad_unique == 1) {
		if (!$_COOKIE['uad_' . $ads->ad_id]) {
			setcookie('uad_' . $ads->ad_id, true, time() + 30);
			$html = stripslashes($ads->ad_content);
			$html = str_replace('#rnd#', time(), $html);
			$html = str_replace('#id#', $ads->ad_id, $html);
		} else {
			$sql = 'select * from ' . $table_name . ' where ad_id = ' . $ads->ad_reserve . ' limit 0, 1';
			$ads = $wpdb->get_row($sql);
			if ($ads) {
				$html = stripslashes($ads->ad_content);
				$html = str_replace('#rnd#', time(), $html);
				$html = str_replace('#id#', $ads->ad_id, $html);
			}
		}
	} else {
		$html = stripslashes($ads->ad_content);
		$html = str_replace('#rnd#', time(), $html);
		$html = str_replace('#id#', $ads->ad_id, $html);
	}
}

echo $html;
?>