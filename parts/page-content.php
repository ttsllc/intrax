<?php 
if (have_posts()):while(have_posts()):the_post();

	remove_filter ('the_content', 'wpautop'); 
	the_content();

endwhile;endif;
?>

