<?php

add_shortcode('dir_uri','get_dir_uri');
function get_dir_uri(){
	return get_template_directory_uri();
}

add_shortcode('home_url','get_home_uri');
function get_home_uri(){
	return get_home_url();
}


// menu の表示 
function show_head_menu(){
	ob_start();
		get_template_part('parts/header_menu');
  	return ob_get_clean();

}
add_shortcode('header_menu','show_head_menu');



function show_footer_content_1(){
	ob_start();
		get_template_part('parts/footer_content_1');
  	return ob_get_clean();

}
add_shortcode('footer_content_1','show_footer_content_1');


/*
//top

//page
//banner_section
add_shortcode('banner_section','get_banner_section');
function get_banner_section(){
	ob_start();
  get_template_part('templates/banner_section');
  return ob_get_clean();
}

//*/