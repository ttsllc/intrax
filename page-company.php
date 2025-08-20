<?php get_header(); ?>

<body class="kasou company">
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WVP57Z"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
  
<!-- Google Tag Manager (noscript) Ads-JP-->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T2RLF6F"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->


	<!--header-->
	<header>
		<?php get_template_part('parts/header_menu'); ?>
	</header>
	<!--/header-->

	<!--main-->
	<main role="main" class="wrap">

	<?php get_template_part( 'parts/page', 'content' ) ?>
	<?php get_template_part('parts/footer_content_1'); ?>

	</main>
	<!--/main-->




<?php get_footer(); ?>