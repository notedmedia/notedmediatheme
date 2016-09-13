<?php
get_header();
?>
<style type="text/css">
body {
	background: #f2f2f4 !important;
	margin: 0 !important;
}
header#main-header {
	display: none !important;
}
</style>
<?php
$path = $_SERVER['DOCUMENT_ROOT'];
$domain = $_SERVER['SERVER_NAME'];
$path = str_replace($domain, '', $path);
$files = scandir($path . 'notedmedia.se/media/wallpapers');
$wallpaper = get_template_directory_uri() . '/images/wallpaper.png';
foreach($files as $file) {
	$info = explode('.', $file);
	if ($info[0] == str_replace('.', '_', $domain)) {
		$today = date('Ymd', time());
		$info = explode('-', $info[1]);
		if ($today >= $info[0] && $today <= $info[1]) {
			$wallpaper = 'http://notedmedia.se/media/wallpapers/' . $file;
		}
	}
}
$screenshot = $path . 'notedmedia.se/media/screenshots/' . $domain . '.png';
if (is_file($screenshot)) {
	$screenshot = 'http://notedmedia.se/media/screenshots/' . $domain . '.png';
} else {
	$screenshot = get_template_directory_uri() . '/images/owl-phone.png';
}
?>
<div id="wallpaper" style="background: url(<?= $wallpaper ?>) 50% 50% no-repeat;">
</div>

<table id="front-page-header">
	<tbody>
    	<tr>
        	<td rowspan="2" width="*">
            </td>
            <td rowspan="2" id="front-page-header-phone">
            	<a href="<?= get_option('nm_facebook_page') ?>"><img src="<?= $screenshot ?>"></a>
            </td>
            <td id="front-page-header-logo">
            	<div id="balloon_area">
                	<div id="balloon"></div>
                </div>
            	<h1><img src="http://notedmedia.se/media/logos/logo_<?= $domain ?>.png"></h1>
            </td>
        	<td rowspan="2" width="*">
            </td>
        </tr>
        <tr>
        	<td id="front-page-header-text"><?= stripslashes(get_option('nm_front_header')) ?></td>
        </tr>
    </tbody>
</table>

<?php
/*
<div id="front_page">
	<div id="balloon_area"></div>
    <div id="front_image">
        <a href="<?= get_option('nm_facebook_page') ?>"><img src="<?= get_template_directory_uri() ?>/images/owl-phone.png"></a>
    </div>
    <article>
        <h1><img src="<?= get_template_directory_uri() ?>/images/logo_front.png"></h1>
        <p>
            <?= stripslashes(get_option('nm_front_header')) ?>
        </p>
    </article>
</div>
<div class="clear-all"></div>
*/
?>

<div class="front_page_articles">
	<div class="page-content-small">
	<?php include 'index_content.php'; ?>
    </div>
	<div class="clear-all"></div>
</div>
<div id="front_page_space">
    <div class="page-content-small" id="front_page_more_fun">
        <button id="front_page_more_fun"><?= stripslashes(get_option('nm_front_button')) ?></button>
    </div>
</div>


<?php
get_footer();
?>