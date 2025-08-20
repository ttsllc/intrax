<?php
/* Template Name: 確認 */
?>
<?php get_header() ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script>
	$(function(){
		$('.confirm_hidden').hide();
	});
</script>
<style type="text/css">
span.txt02.name {
	display:none!important;
}
</style>
<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
<?php the_content() ?>
<?php endwhile;endif;wp_reset_postdata(); ?>
<?php get_footer() ?>