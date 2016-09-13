</div>

<div class="clear-all">
</div>

<footer id="main-footer">
	<div class="page-content">
		<?= get_option('nm_footer_content') ?>
    </div>
</footer>
<div id="dump"></div>
<?= stripslashes(get_option('nm_footer_meta')) ?>
<?php
wp_footer();
?>
<script type="text/javascript" src="http://notedmedia.se/cerebro/js/getstats.php"></script>
</body>
</html>
<!-- This theme and it's plugins are all written, 100%, by Daniel Melin - we shall all bask in his glory! -->