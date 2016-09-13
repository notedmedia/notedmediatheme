<?php
get_header();
global $wpdb;
$charset_collate = $wpdb->get_charset_collate();
$table_name = $wpdb->prefix . 'nm_errors';

$wpdb->show_errors;

$sql = "CREATE TABLE $table_name (
	error_id mediumint(9) NOT NULL AUTO_INCREMENT,
	error_created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
	error_url varchar(512) NOT NULL,
	error_from varchar(512) NOT NULL,
	error_data VARCHAR(256) NOT NULL,
	UNIQUE KEY error_id (error_id)
) $charset_collate;";

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
dbDelta( $sql );
?>
<div id="broken">
	<div id="content">
    	<h1>#404</h1>
        <p>Ett fel uppstod, vi är inte helt säkra på vad men vi har skickat meddelande till våra servertomtar som kommer kika på det så snabbt de kan!</p>
        <p><a href="/">Gå till startsidan</a></p>
        <p><small>Följande meddelande har skickats:</small></p>
        <div id="broken-message">
            <p><strong>Kom från</strong>: <?= $_SERVER['HTTP_REFERER'] ?></p>
            <p><strong>Besökte</strong>: <?= $_SERVER['REQUEST_URI'] ?></p>
            <p><strong>Med</strong>: <?= $_SERVER['HTTP_USER_AGENT'] ?></p>
            <p><strong>Datum</strong>: <?= date('Y-m-d H:i:s', time()) ?></p>
        </div>
		<?php
		$from = $_SERVER['HTTP_REFERER'] . '';
		$wpdb->insert(
			$table_name,
			array(
				'error_created' => date('Y-m-d H:i:s', time()),
				'error_url' => $_SERVER['REQUEST_URI'],
				'error_from' => $from,
				'error_data' => $_SERVER['HTTP_USER_AGENT']
			)
		);
        $args = array(
	        's' => str_replace('%20', ' ', substr($_SERVER['REQUEST_URI'], 1))
        );
        // The Query
        $the_query = new WP_Query( $args );
        if ( $the_query->have_posts() ) {
			?>
			<p>
			Försökte lista ut vad du KANSKE letade efter.. testa någon av dessa?
			</p>
			<?php
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				?>
				<li>
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</li>
				<?php
			}
        } else {
        }
        ?>
    </div>
</div>
<style type="text/css">
	header#main-header {
		-ms-transform: rotate(-13deg); /* IE 9 */
		-webkit-transform: rotate(-13deg); /* Chrome, Safari, Opera */
		transform: rotate(-13deg);
	}
	footer#main-footer {
		-ms-transform: rotate(27deg); /* IE 9 */
		-webkit-transform: rotate(27deg); /* Chrome, Safari, Opera */
		transform: rotate(27deg);
	}

</style>

<?php
get_footer();
?>