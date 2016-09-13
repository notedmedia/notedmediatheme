<?php
get_header();
?>
<div class="page-content">
    <h2 class="article-title"><?= get_the_title() ?></h2>
    <article class="big-post page-content">
	    <?= the_content() ?>
    </article>
    <article class="big-post">
    </article>
</div>

<?php
get_footer();
?>