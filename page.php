<?php get_header(); ?>
<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
<?php the_content() ?>
<?php endwhile;endif;wp_reset_postdata(); ?>
<!-- This is index -->
<?php get_footer(); ?>