<?php
get_header();

$table_name = $wpdb->prefix . 'nm_fblikes';
$likes = $wpdb->get_results('select * from ' . $table_name . ' where like_setting <> 3 and like_position = 0 order by like_id desc');
foreach ($likes as $like) {
	$showLike = false;
	if ($like->like_ids != '') { $ids = explode(',', $like->like_ids); }

	if ($like->like_setting == 1) {
		if (in_array(get_the_ID(), $ids)) {
			$showLike = true;
		} else {
			$showLike = false;
		}
	}
	if ($like->like_setting == 2) {
		if (!in_array(get_the_ID(), $ids)) {
			$showLike = true;
		} else {
			$showLike = false;
		}
	}
	if ($like->like_setting == 0) {
		$showLike = true;
	}
	if ($_COOKIE['fblike' . $like->like_id]) {
		$showLike = false;
	}
	if ($showLike) {
		$detect = new Mobile_Detect;
		if ($detect->isMobile()) {
			$popWidth = 200;
		} else {
			$popWidth = 450;
		}
		?>
        <div id="facebook-like-popup" class="facebook-popup" data-pop="<?= $like->like_id ?>" data-timer="<?= $like->like_timer ?>">
        	<div id="facebook-like-container">
            	<h2>
                	<?php if ($like->like_close == 1) { ?>
                	<button class="facebook-already-likes" data-popup="popup" id="facebook-close-top"></button>
                    <?php } ?>
                	<a href="<?= stripslashes($like->like_url) ?>"><img src="<?= get_template_directory_uri() ?>/images/facebook-like-logo.png"></a>
                </h2>
                <p><?= stripslashes($like->like_text) ?></p>
		        <div class="fb-like" data-href="<?= stripslashes($like->like_url) ?>" data-colorscheme="light" data-width="<?= $popWidth ?>" data-layout="standard" data-action="like" data-show-faces="true" data-share="false"></div>
                <?php if ($like->like_btn == 1) { ?>
                <button class="facebook-already-likes" data-popup="popup" id="facebook-close-bottom"><?= stripslashes($like->like_button) ?></button>
                <?php } ?>
        	</div>
        </div>
        <?php
		break;
	}
}
$likes = $wpdb->get_results('select * from ' . $table_name . ' where like_setting <> 3 and like_position = 1 order by like_id desc');
foreach ($likes as $like) {
	$showLike = false;
	if ($like->like_ids != '') { $ids = explode(',', $like->like_ids); }
	if ($like->like_setting == 1) {
		if (in_array(get_the_ID(), $ids)) {
			$showLike = true;
		} else {
			$showLike = false;
		}
	}
	if ($like->like_setting == 2) {
		if (!in_array(get_the_ID(), $ids)) {
			$showLike = true;
		} else {
			$showLike = false;
		}
	}
	if ($like->like_setting == 0) {
		$showLike = true;
	}
	if ($_COOKIE['fblike' . $like->like_id]) {
		$showLike = false;
	}
	if ($showLike) {
		$detect = new Mobile_Detect;
		if ($detect->isMobile()) {
			$popWidth = 200;
		} else {
			$popWidth = 450;
		}
		?>
        <div id="facebook-like-scroll" class="facebook-popup" data-pop="<?= $like->like_id ?>">
        	<div id="facebook-like-container">
            	<h2>
                	<?php if ($like->like_close == 1) { ?>
                	<button class="facebook-already-likes" data-popup="scroll" id="facebook-close-top"></button>
                    <?php } ?>
                	<a href="<?= stripslashes($like->like_url) ?>"><img src="<?= get_template_directory_uri() ?>/images/facebook-like-logo.png"></a>
                </h2>
                <p><?= stripslashes($like->like_text) ?></p>
		        <div class="fb-like" data-href="<?= stripslashes($like->like_url) ?>" data-colorscheme="light" data-width="<?= $popWidth ?>" data-layout="standard" data-action="like" data-show-faces="true" data-share="false"></div>
                <?php if ($like->like_btn == 1) { ?>
                <button class="facebook-already-likes" data-popup="scroll" id="facebook-close-bottom"><?= stripslashes($like->like_button) ?></button>
                <?php } ?>
        	</div>
        </div>
        <?php
		break;
	}
}
?>

<?php
if (have_posts()) {
	while (have_posts()) {
		the_post();
		$post = get_post();
		$thumb = wp_get_attachment_url(get_post_thumbnail_id($post->ID));
		$tags = get_the_tags();
		$tag = $tags[0]->name;
	}
} else {
	$post = get_post($_GET['post_id']);
	$thumb = wp_get_attachment_url(get_post_thumbnail_id($post->ID));
	$tags = get_the_tags();
	$tag = $tags[0]->name;
}
$thumb_file = basename($thumb);
$thumb = str_replace($thumb_file, urlencode($thumb_file), $thumb);
setup_postdata($post);
?>
<div class="article-point" data-url="<?= get_permalink() ?>" data-title="<?= the_title() ?>" data-id="<?= get_the_ID() ?>"></div>

<div class="top-side-area">
	<?php dynamic_sidebar('top-side'); ?>
</div>

<div class="page-content">
<?php
/*
	<div class="backwallpaper" style="background-image: url(<?= $thumb; ?>)" id="postimage<?= $post->ID ?>">
	</div>
*/
?>
</div>

<div class="page-content-small article article-single-page" data-article="<?= $post->ID ?>">
    <div class="column-narrow-article">
    	<div class="article-side-panel">
        	<?php dynamic_sidebar('left-side'); ?>
	        <div class="clear-all"></div>
        </div>
    </div>
    <div class="column-wide-article">
        <div id="article-before">
        	<?php dynamic_sidebar('before-article'); ?>
        </div>
        <article class="big-post">
        	<?php
			$content = apply_filters('the_content', $post->post_content);
			$regexp = '<img[^>]+src=(?:\"|\')\K(.[^">]+?)(?=\"|\')';
			if(preg_match_all("/$regexp/", $content, $matches, PREG_SET_ORDER)) {
				if( !empty($matches) ) {
					for ($i=0; $i <= count($matches); $i++) {
						$img = $matches[$i][0];
						$img_file = basename($img);
						$imgFixed = str_replace($img_file, urlencode($img_file), $img);
						$content = str_replace($img, $imgFixed, $content);
					}
				}
			}
			$content = str_replace('<p>&nbsp;</p>', '', $content);
			echo $content;
			$tA = get_post_meta( get_the_ID(), 'tracker-article' );
			if ($tA[0] != '') {
				echo $tA[0];
			}
			?>
        </article>
        <div id="article-after">
        	<?php dynamic_sidebar('after-article'); ?>
        </div>
    </div>
	<span class="clear-all"></span>
</div>
<div class="clear-all"></div>
<div class="content-splascher">
	    <?php dynamic_sidebar('between-articles'); ?>
</div>

<span style="display: none; padding: 6px;" class="shownab"><?= $shown_ab ?></span>


<div class="front_page_articles">
	<div class="page-content-small">
		<?php include 'index_content.php'; ?>
	</div>
	<div class="clear-all"></div>
</div>


<?php
get_footer();
?>