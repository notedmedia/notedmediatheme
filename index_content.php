<?php
require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );
$offset = $_GET['startoffset'];
$offset = intval($offset);
$show_posts = 9;

require_once('Mobile_Detect.php');
$detect = new Mobile_Detect;
if ($detect->isMobile()) {
	$device = 0;
} else {
	$device = 1;
}

if ($_GET['abs']) {
	$shown_ab = $_GET['abs'];
}

if (is_single()) {
	$exclude = get_the_ID();
	if (get_option('nm_puff_ad') != 0 && get_option('nm_puff_ad_page') == 0 && (get_option('nm_puff_ad_device') == $device || get_option('nm_puff_ad_device') == 2)) { $show_posts = 8; }
	
}
if (isset($_GET['exclude_id'])) {
	$exclude = intval($_GET['exclude_id']);
	if (get_option('nm_puff_ad') != 0 && get_option('nm_puff_ad_page') == 1 && (get_option('nm_puff_ad_device') == $device || get_option('nm_puff_ad_device') == 2)) { $show_posts = 8; }
}
if (get_option('nm_puff_ad') != 0 && get_option('nm_puff_ad_page') == 2 && (get_option('nm_puff_ad_device') == $device || get_option('nm_puff_ad_device') == 2)) { $show_posts = 8; }

if ($shown_ab != '' && $exclude != '') {
	$exclude .= ',' . $shown_ab;
}

if (is_user_logged_in()) {
	$args = array(
		'posts_per_page'   => $show_posts,
		'offset'           => $offset,
		'orderby'          => 'date',
		'order'            => 'DESC',
		'include'          => '',
		'exclude'          => $exclude,
		'post_type'        => 'post',
		'post_status'      => 'publish,private',
		'suppress_filters' => true 
	);
} else {
	$args = array(
		'posts_per_page'   => $show_posts,
		'offset'           => $offset,
		'orderby'          => 'date',
		'order'            => 'DESC',
		'include'          => '',
		'exclude'          => $exclude,
		'post_type'        => 'post',
		'post_status'      => 'publish',
		'suppress_filters' => true 
	);
}
$posts_array = get_posts( $args );
$i = 0;

foreach ($posts_array as $post) {
	$i++;
	if ($i == get_option('nm_puff_ad_position') && $show_posts == 8) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'nm_ads';
		$sql = 'select * from ' . $table_name . ' where ad_id = ' . get_option('nm_puff_ad') . ' limit 0, 1';
		$ads = $wpdb->get_row($sql);
		?>
		<div class="front-page-post-ad">
			<div class="ad-content">
				<?php echo stripslashes($ads->ad_content) ?>
			</div>
		</div>
		<?php
	}
	$thumb = wp_get_attachment_url(get_post_thumbnail_id($post->ID));
	$thumb_file = basename($thumb);
	$thumb = str_replace($thumb_file, urlencode($thumb_file), $thumb);
	$tags = get_the_tags();
	$tag = $tags[0]->name;
	$excerpt = $post->post_content;
	$excerpt = implode(' ', array_slice(explode(' ', $excerpt), 0, 20));
	$abtable = $wpdb->prefix . 'ab_titles';
	$abdata = $wpdb->get_row('select * from ' . $abtable . ' where article_id = ' . $post->ID . ' and title_views < title_maxviews order by title_views asc limit 0, 1');
	if ($abdata && 1==2) {
		$title = $abdata->title_text;
		$image = $abdata->title_image;
		$class = 'ab-link';
		$articleID = $abdata->title_id;
		$wpdb->update(
			$abtable,
			array(
				'title_views' => $abdata->title_views + 1
			),
			array(
				'title_id' => $abdata->title_id
			)
		);
	} else {
		$title = get_the_title();
		$image = $thumb;
		$class = '';
		$articleID = '';
	}
	$tA = get_post_meta( get_the_ID(), 'tracker-article' );
	$tP = get_post_meta( get_the_ID(), 'tracker-puff' );
	$alias = get_post_meta(get_the_ID(), 'post_alias');
	if ($alias[0] != '1') {
		$author = get_the_author_meta('display_name', $post->post_author);
	} else {
		$author = '';
	}
	?>
    <a href="<?= the_permalink() ?>" class="front-page-post <?= $class ?>" data-article="<?= $articleID ?>">
		<?php if (is_user_logged_in()) {
			?>
			<div class="quick-edit" data-article="<?= $post->ID ?>">Edit</div>
			<?php
		} ?>
    	<div class="thumbnail" style="background-image: url(<?= $image ?>);"></div>
        <h2><div><?= $title ?></div></h2>
        <p class="author"><span><?= $author ?><?= $tP[0] ?></span></p>
    </a>
	<?php
}
?>