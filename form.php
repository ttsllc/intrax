<?php
/* Template Name: フォーム */
?>
<?php get_header() ?>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>




<script type="text/javascript" src="//code.jquery.com/jquery-2.1.0.min.js"></script>
<script type="text/javascript" src="//jpostal-1006.appspot.com/jquery.jpostal.js"></script>



<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
<?php the_content() ?>
<?php endwhile;endif;wp_reset_postdata(); ?>




<script>
	$(function(){
		$('.radio_inner').each(function() {
			$(this).parent().addClass('r_button');
		});

		$('.check_inner').each(function(){
			$(this).parent().addClass('c_button');
		});

	});



	$('#zipcode').jpostal({
		click : '.btnsrch',
		postcode : [
			'#zipcode'
		],
		address : {
			'#pref' : '%3',
			'#address1' : '%4%5',

		}
	});
</script>


<?php get_footer() ?>