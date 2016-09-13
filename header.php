<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta property="fb:pages" content="<?= get_option('nm_facebook_page_id') ?>" />
    <?php
	if (is_single()) {
		$post = get_post();
		$thumb = wp_get_attachment_url(get_post_thumbnail_id($post->ID));
		$thumbImage = basename($thumb);
		$thumb = str_replace($thumbImage, urlencode($thumbImage), $thumb);
		
		$time1 = strtotime($post->post_date);
		$time2 = strtotime($post->post_date_gmt);
		$timeDiff = ($time1 - $time2) / 3600;
		$timeDiff = sprintf("%02d", $timeDiff);
		if ($timeDiff < 0) {
			$timeDiff = '-' . $timeDiff . ':00:00';
		} else {
			$timeDiff = '+' . $timeDiff . ':00:00';
		}
		?>
		<meta property="article:published_time" content="<?= date('Y-m-d', $time2) ?>T<?= date('H:i:s', $time2) ?><?= $timeDiff ?>" />
		<meta property="article:published_time_local" content="<?= $post->post_date ?>" />
        <!-- TWITTER SHARE -->
        <meta name="twitter:card" content="summary" />
        <meta name="twitter:url" content="<?= get_permalink() ?>" />
        <meta name="twitter:title" content="<?= get_the_title() ?>" />
        <meta name="twitter:description" content="<?= get_first_p(get_the_ID()) ?>" />
        <meta name="twitter:image" content="<?= $thumb ?>" />
		<!-- FACEBOOK -->
        <meta property="og:url" content="<?= get_permalink() ?>" />
        <meta property="og:type" content="article" />
        <meta property="og:title" content="<?= get_the_title() ?>" />
        <meta property="og:description" content="<?= get_first_p(get_the_ID()) ?>" />
        <meta property="og:image" content="<?= $thumb ?>" />
		<meta property="article:author" content="<?= the_author_meta('user_url', $post->post_author) ?>">
        <?php
	} else {
		?>
        <!-- TWITTER SHARE -->
        <meta name="twitter:card" content="summary">
        <meta name="twitter:url" content="<?= get_site_url() ?>">
        <meta name="twitter:title" content="<?= get_bloginfo('name') ?>">
        <meta name="twitter:description" content="<?= get_option('nm_front_header') ?>">
        <meta name="twitter:image" content="<?= get_template_directory_uri() ?>/images/owl-phone.png">
		<!-- FACEBOOK -->
        <meta property="og:url" content="<?= get_site_url() ?>" />
        <meta property="og:type" content="article" />
        <meta property="og:title" content="<?= get_bloginfo('name') ?>" />
        <meta property="og:description" content="<?= get_option('nm_front_header') ?>" />
        <meta property="og:image" content="<?= get_template_directory_uri() ?>/images/owl-phone.png" />
        <?php
	}
	?>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?= get_template_directory_uri() ?>/style.css" type="text/css" class="base-class" />
    <link rel="stylesheet" href="<?= get_template_directory_uri() ?>/style-desktop.css" media="screen and (min-width: 1050px)" class="desktop-class" type="text/css" />
    <link rel="stylesheet" href="<?= get_template_directory_uri() ?>/style-mobile.css" media="screen and (max-width: 600px)" class="mobile-class" type="text/css" />
    <link rel="stylesheet" href="<?= get_template_directory_uri() ?>/style-tablet.css" media="screen and (min-width: 600px) and (max-width: 1050px)" class="tablet-class" type="text/css" />
	<script type="text/javascript">
		var tmpDate = new Date();
		var loadTime = tmpDate.getTime();
	</script>
	<script charset="UTF-8" src="//cdn.sendpulse.com/js/push/7eea4d0a6faed9e2afc51e53fe7af42d_0.js" async></script>
    <script src="//code.jquery.com/jquery-1.12.0.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="//connect.facebook.net/en_US/sdk.js"></script>
	<script async defer src="//platform.instagram.com/en_US/embeds.js"></script>
	<script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
	<script async defer src="//assets.pinterest.com/js/pinit.js"></script>
    <script src="<?= get_template_directory_uri() ?>/js/checkres.js?ver=2" type="text/javascript"></script>
	<?php wp_head(); ?>
    <title>
    <?php
	if (is_home()) {
		echo get_bloginfo('name');
	} elseif (is_single() || is_page()) {
		if (have_posts()) {
			while (have_posts()) {
				the_post();
				echo get_the_title();
			}
		}
	} else {
		echo get_bloginfo('name');
	}
	?>
    </title>
    <?= stripslashes(get_option('nm_header_meta')) ?>
</head>

<body>
<?php
$table_name = $wpdb->prefix . 'nm_popups';
$sql = 'select * from ' . $table_name . ' where popup_align <> -1 order by popup_created asc limit 0, 1';

$ads = $wpdb->get_results($sql);
foreach ($ads as $ad) {
	global $wpdb;
	$table_name = $wpdb->prefix . 'nm_ads';
	$html = stripslashes($ad->popup_content);
	preg_match_all("/\[ad:[^\]]*\]/", $html, $matches);
	foreach ($matches[0] as $match) {
		$adname = substr($match, 4, -1);
		$sql = 'select * from ' . $table_name . ' where ad_name = "' . $adname . '" limit 0, 1';
		$adresults = $wpdb->get_row($sql);
		if ($adresults) {
			$adHtml = stripslashes($adresults->ad_content);
		} else {
			$adHtml = '';
		}

		$html = str_replace($match, $adHtml, $html);
	}
	$articles = $ad->popup_articles;
	$showPopup = true;
	if ($articles != '') {
		$articles = explode(',', $articles);
		if (!in_array($post->ID, $articles)) {
			$showPopup = false;
		}
	}
	$buttonMob = '<button id="popup_close" class="popup_close_mob" data-popup="' . $ad->popup_key . '" data-lifespan="' . $ad->popup_span . '" style="display: block; float: right; border-radius: 3px; margin: 10px 10px 10px 20px; height: 100%; box-sizing: border-box; padding: 4px 10px; border: none; background: ' . $ad->popup_button_color . ';">' . $ad->popup_button_text . '</button>';
	$buttonDes = '<button id="popup_close" class="popup_close_des" data-popup="' . $ad->popup_key . '" data-lifespan="' . $ad->popup_span . '" style="display: block; border-radius: 3px; margin: 10px 20px; height: 100%; box-sizing: border-box; padding: 4px 10px; border: none; background: ' . $ad->popup_button_color . ';">' . $ad->popup_button_text . '</button>';
	$html = '<div class="popup_desktop popup popup-' . $ad->popup_align . '" data-popup="' . $ad->popup_key . '">' . $buttonMob . str_replace('#button#', $buttonDes, $html) . '</div>';
	if ($ad->popup_align != 3) {
		if (is_single() && $showPopup) {
			echo $html;
		}
	} else {
		$topPopupDesktop = $html;
	}
	break;
}
?>

<?php
$table_name = $wpdb->prefix . 'nm_popups';
$sql = 'select * from ' . $table_name . ' where popup_align_mobile <> -1 order by popup_created asc limit 0, 1';

$ads = $wpdb->get_results($sql);
foreach ($ads as $ad) {
	global $wpdb;
	$table_name = $wpdb->prefix . 'nm_ads';
	$html = stripslashes($ad->popup_content);
	preg_match_all("/\[ad:[^\]]*\]/", $html, $matches);
	foreach ($matches[0] as $match) {
		$adname = substr($match, 4, -1);
		$sql = 'select * from ' . $table_name . ' where ad_name = "' . $adname . '" limit 0, 1';
		$adresults = $wpdb->get_row($sql);
		if ($adresults) {
			$adHtml = stripslashes($adresults->ad_content);
		} else {
			$adHtml = '';
		}

		$html = str_replace($match, $adHtml, $html);
	}
	$showPopup = true;
	$articles = $ad->popup_articles;
	if ($articles != '') {
		$articles = explode(',', $articles);
		if (!in_array($post->ID, $articles)) {
			$showPopup = false;
		}
	}
	$button = '<button id="popup_close" data-popup="' . $ad->popup_key . '" data-lifespan="' . $ad->popup_span . '" style="box-sizing: border-box; padding: 4px 10px; border: none; background: ' . $ad->popup_button_color . '; width: 100%;">' . $ad->popup_button_text . '</button>';
	$buttonDes = '<button id="popup_close" class="popup_close_des" data-popup="' . $ad->popup_key . '" data-lifespan="' . $ad->popup_span . '" style="display: block; border-radius: 3px; margin: 10px 20px; height: 100%; box-sizing: border-box; padding: 4px 10px; border: none; background: ' . $ad->popup_button_color . ';">' . $ad->popup_button_text . '</button>';
	$html = str_replace('#button#', $buttonDes, $html);
	$html = '<div class="popup_mobile popup popup-' . $ad->popup_align_mobile . '" data-popup="' . $ad->popup_key . '">' . $button . $html . '</div>';
	if ($ad->popup_align != 3) {
		if (is_single() && $showPopup) {
			echo $html;
		}
	} else {
		$topPopupMobile = $html;
	}
	break;
}
?>

<header id="main-header">
	<?php
	if (is_single() && $showPopup) {
		?>
		<?= $topPopupDesktop ?>
		<?= $topPopupMobile ?>
		<?php
	}
	?>
    <button id="go-home"><i class="fa fa-home"></i></button>
   	<div class="page-width content">
		<h1>
			<?php
			$mediapath = '/home/httpd/uppskattat/notedmedia.se/media';
			$subject = get_bloginfo('url');
			$result = preg_split('/(?=\.[^.]+$)/', $subject);
			$site_name = str_replace('http://', '', $result[0]);
			$domain = str_replace('http://', '', $subject);
			if (is_file($mediapath . '/logos/' . $domain . '_owl.svg')) {
				$owl_image = 'http://notedmedia.se/media/logos/' . $domain . '_owl.svg';
			} else {
				$owl_image = 'http://notedmedia.se/media/logos/owl.svg';
			}
			?>
			<a href="<?= get_bloginfo('url') ?>">
				<img src="<?= $owl_image ?>" class="owl" title="Noted Media brand, the Owl">
				<img src="http://notedmedia.se/media/logos/<?= $site_name ?>_black.svg" class="logo" title="Website logo">
			</a>
		</h1>
    </div>
	<menu id="top-menu">
		<button id="menu-toggle" class="item-toggle" data-target="ul#top-menu"><i class="fa fa-bars"></i></button>
		<?php
		$args = array(
			'menu' => 'top-menu',
			'menu_id' => 'top-menu',
			'container' => false			
		);
		wp_nav_menu($args);
		?>
	</menu>
</header>
<?php /*

<div id="news-line" class="page-width">
	<?php
	$weekdays = array('Söndag', 'Måndag', 'Tisdag', 'Onsdag', 'Torsdag', 'Fredag', 'Lördag');
	echo $weekdays[date('w', time())] . ' ' . date('j/n Y', time());
	echo ' | ';
	$tags = get_tags();
	foreach ($tags as $tag) {
		echo '<a href="' . get_tag_link($tag->ID) . '">' . $tag->name . '</a> ';
	}
	?>
</div>

<div id="latest-posts" class="page-width">
	<ul>
	<?php
	$recent_posts = wp_get_recent_posts();
	foreach( $recent_posts as $recent ){
		$post = get_post($recent['ID']);
		$thumb = wp_get_attachment_image_src(get_post_thumbnail_id($recent['ID']), 'thumbnail');
		?>
        <li>
        	<a href="<?= get_permalink() ?>" style="background-image: url(<?= $thumb[0] ?>);">
	        	<div class="excerpt"><?= substr(strip_tags($post->post_title), 0, 70) ?>...</div>
            </a>
        </li>
        <?php
	}
	?>
    </ul>
</div>
*/ ?>

<?php
if (is_single()) {
	?>
<div id="preloader"><div><i class="fa fa-spin fa-spinner fa-fw"></i> <?= stripslashes(get_option('nm_preloader')) ?></div></div>
	<?php
}
?>

<div id="main-page" class="page-width">