
    
    
    
    
    
  
    <!--footer-->
	<footer role="footer">
		<div class="footer_cell">
			<a href="<?php echo home_url();?>"><img class="f_logo" src="<?php echo get_template_directory_uri(); ?>/images/common/intrax_logo.png" alt=""></a>
			<p>〒105-0022 東京都港区海岸1-9-11マリンクスタワー7F</p>
			<div class="copyright">Copyright &copy; intrax Inc All Rights Reserved</div>
		</div>
		<div class="footer_link">
			<ul>
				<li><a href="/company#access">アクセスマップ</a></li>
				<li><a href="/privacy">プライバシーポリシー</a></li>
				<li><a href="/business">法人のお客様</a></li>
			</ul>
		</div>
		<div class="totop"><a href="#">Page Top</a></div>
	</footer>
	<!--/footer-->
<?php wp_footer(); ?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/jquery.matchHeight.js"></script>
	<?php if( is_page( 'home')  ):	 ?>
		<script>
		$(function(){
		  $('.txt_ttl').matchHeight();
		  $('#index_reason .articleInner ul li .txtArea').matchHeight();
		  $('#index_reason .reason_cell').matchHeight();
		  $('#index_service ul li .service_cell').matchHeight();
		  $('#index_content .content_cell').matchHeight();
		  $('.list_out li a').matchHeight();
		  $('.list_service01 li').matchHeight();
		  $('.list_service02 li').matchHeight();
		});
		</script>

	<?php else: ?>
		<script>
		$(function(){
		  $('.list_service01 li').matchHeight();
		  
		});
		</script>
	<?php 	endif; ?>
<?php
// 現在表示されているページIDを取得
//本番ページは4430
//テストは9577

$current_page_id = get_the_ID();
// 現在表示されているページの親ページIDを取得
$parent_page_id = wp_get_post_parent_id( $current_page_id );
if($current_page_id==6215 or $parent_page_id==6215){
?>
<script src="<?php echo get_template_directory_uri(); ?>/js/common_form.js"></script>
<?php }elseif($current_page_id==9577 or $parent_page_id==9577){ ?>
<script src="<?php echo get_template_directory_uri(); ?>/js/common_form_test.js"></script>
<?php }?>
	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/img_change.js"></script>
	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/add.js"></script>




<?php get_template_part('inc/common_netowork_footer'); ?>	
    
     
</body>
</html>

