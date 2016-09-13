<?php
session_start();

/*
require_once('Mobile_Detect.php');
$detect = new Mobile_Detect;
if ($detect->isMobile()) {
	header('devicetype-x: mobile');
} else {
	header('devicetype-x: desktop');
}
header('Vary: devicetype-x');
*/

$videotag = '';
add_filter('the_content', 'noted_media_content', 999);
function noted_media_content($content) {
	global $videotag;
	$pattern = "/<div class='embed-container'>(.*?)<\/div>/s";
    preg_match_all($pattern, $content, $matches);
	$videotag = $matches[0][0];
	$html = str_replace($videotag, '', $content);

	$embed_supported = array(
		'pinterest.com/pin' => '<a data-pin-do="embedPin" data-pin-lang="sv" data-pin-width="large" href="#URL#"></a>',
		'facebook.com/photo' => '<div class="fb-post" data-href="#URL#" data-width="500" data-show-text="true"></div>',
		'twitter.com/' => '<blockquote class="twitter-tweet" data-lang="en"><a href="#URL#"></a></blockquote>',
		'instagram.com/' => '<blockquote class="instagram-media" data-instgrm-captioned data-instgrm-version="7"><div><p><a href="#URL#"></a></p></div></blockquote>'
	);

	$pattern = "/[item](.*?)\[\/item]/";
    preg_match_all($pattern, $content, $matches);
	foreach ($matches[0] as $embed) {
		$embed_section = '[' . $embed;
		$embed_item = substr($embed, 5, -7);
		foreach ($embed_supported as $source => $value) {
			if (strpos($embed_item, $source)) {
				$html = str_replace($embed_section, str_replace('#URL#', $embed_item, $value), $html);
			}
		}
	}

	return $html;
}

function notedmediatheme_pages() {

}
function noted_media_theme() {
	?>
    <div class="wrap">
    </div>
    <?php
}
add_action('admin_menu', 'notedmediatheme_pages');

function noted_menus() {
  register_nav_menus(
    array(
      'top-menu' => __( 'Top menu' ),
      'bottom-menu' => __( 'Bottom menu' )
    )
  );
}
add_action( 'init', 'noted_menus' );

add_theme_support( 'post-thumbnails' );

function getTagIcon($tag) {
	switch ($tag) {
		case 'Djur':
			return get_template_directory_uri() . '/images/icons/dog56.png';
			break;
		default:
			return get_template_directory_uri() . '/images/icons/earth213.png';
			break;
	}
}

function noted_login( $redirect_to, $request, $user ) {
    if ( is_array( $user->roles ) ) {
		return admin_url( 'admin.php?page=notedmedia' );
	} else {
		return admin_url();
	}
}
add_filter( 'login_red', 'noted_login', 10, 3 );

add_action( 'widgets_init', 'noted_sidebars_init' );
function noted_sidebars_init() {

    register_sidebar( array(
        'name' => __( 'Top side (Article)', 'noted-top-side' ),
        'id' => 'top-side',
        'description' => __( 'Displayed on the top side above articles, desktop only', 'noted-top-side' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widgettitle">',
		'after_title'   => '</h3>',
    ) );
	
    register_sidebar( array(
        'name' => __( 'Left side (Article)', 'noted-left-side' ),
        'id' => 'left-side',
        'description' => __( 'Displayed on the left side (hidden smaller resolutions)', 'noted-left-side' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widgettitle">',
		'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name' => __( 'Before article', 'noted-before-article' ),
        'id' => 'before-article',
        'description' => __( 'Displayed before articles', 'noted-before-article' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widgettitle">',
		'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name' => __( 'After article', 'noted-after-article' ),
        'id' => 'after-article',
        'description' => __( 'Displayed after articles', 'noted-after-article' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widgettitle">',
		'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name' => __( 'Between articles', 'noted-between-articles' ),
        'id' => 'between-articles',
        'description' => __( 'Shown between articles', 'noted-between-articles' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widgettitle">',
		'after_title'   => '</h3>',
    ) );
}

add_action('admin_head', 'notedmedia_admin_css');
function notedmedia_admin_css() {
	?>
    <style type="text/css">
		textarea.widefat {
			resize: none;
		}
		textarea.widefat:focus {
			position: fixed;
			z-index: 9999;
			top: 100px;
			left: 100px;
			width: calc(100% - 200px);
			height: calc(100% - 200px);
			box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
			resize: none;
			font: 13px "Courier New", Courier, monospace;
		}
	</style>
    <script type="text/javascript">
	jQuery(document).delegate('textarea.widefat', 'keydown', function(e) {
	  var keyCode = e.keyCode || e.which;
	
	  if (keyCode == 9) {
		e.preventDefault();
		var start = jQuery(this).get(0).selectionStart;
		var end = jQuery(this).get(0).selectionEnd;
	
		// set textarea value to: text before caret + tab + text after caret
		jQuery(this).val(jQuery(this).val().substring(0, start)
					+ "\t"
					+ jQuery(this).val().substring(end));
	
		// put caret at right position again
		jQuery(this).get(0).selectionStart =
		jQuery(this).get(0).selectionEnd = start + 1;
	  }
	});
	</script>
    <?php
}

function get_first_p($id) {
	$post = get_post($id);
	$html = $post->post_content;
	$html = apply_filters('the_content', $html);

	preg_match_all('/(<p(>|\s+[^>]*>).*?<\/p>)/i', $html, $match);
	foreach ($match[0] as $p) {
		$html = strip_tags($p);
		if ($html != '') {
			$result = $html;
			break;
		}
	}
	return $result;
}
require_once('Mobile_Detect.php');

function nm_editor_styles() {
    add_editor_style( 'custom-editor-style.css' );
}
add_action( 'admin_init', 'nm_editor_styles' );

add_action('init', 'fbFeed');
function fbFeed(){
	add_feed('fb', 'fbInstantFeed');
}
function fbInstantFeed(){
	header("Content-Type: application/xml; charset=" . get_option('blog_charset'));
	echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>';
	?>
<rss name="Melin" version="2.0"
        xmlns:content="http://purl.org/rss/1.0/modules/content/"
        xmlns:wfw="http://wellformedweb.org/CommentAPI/"
        xmlns:dc="http://purl.org/dc/elements/1.1/"
        xmlns:atom="http://www.w3.org/2005/Atom"
        xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
        xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
        <?php do_action('rss2_ns'); ?>>
<channel>
        <title><?php bloginfo_rss('name'); ?> - Feed</title>
        <atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml" />
        <link><?php bloginfo_rss('url') ?></link>
        <description><?php bloginfo_rss('description') ?></description>
        <lastBuildDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_lastpostmodified('GMT'), false); ?></lastBuildDate>
        <language><?php echo get_option('rss_language'); ?></language>
        <sy:updatePeriod><?php echo apply_filters( 'rss_update_period', 'hourly' ); ?></sy:updatePeriod>
        <sy:updateFrequency><?php echo apply_filters( 'rss_update_frequency', '1' ); ?></sy:updateFrequency>
        <?php do_action('rss2_head'); ?>
        <?php while(have_posts()) : the_post(); ?>
                <item>
                        <title><?php the_title_rss(); ?></title>
                </item>
        <?php endwhile; ?>
</channel>
</rss>
    <?php
}
?>