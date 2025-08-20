

<?php get_header(); ?>


<body class="kasou service">
	<header><!--header-->
		<?php get_template_part('parts/header_menu'); ?>
	</header><!--/header-->

	<!--main-->
	<main role="main" class="wrap">
		
		<section class="ttlArea">
			<h2><span>Service</span>サービス一覧</h2>
			<div class="breadcrumb">
				<a href="../">HOME </a> ＞ <span>サービス一覧</span>
			</div>
		</section>
		
		<section class="kasou_cell">
			<div class="box">
				<div class="text">
				<p>イントラックスは、米国国務省認定のJ-1ビザのスポンサーとして、アメリカの高校留学やインターンシップなどを運営している教育機関です。1980年の設立以来、世界100カ国以上の若者に数々の文化交流・教育プログラムを提供してきました。プログラムを通じて、若い世代の国際理解を深め、真の国際人として活躍してもらうことを目的としています。</p>
				
				<ul class="subnavi">
        <?php 
                $args = array(
                    'post_type' => 'service', //記事データを取得  
                    'post_status'    => array( 'publish' ),
                    'posts_per_page' => -1 //取得数は 制限なし

                );


                $k_query = new WP_Query( $args );
                if( $k_query->have_posts() ): 
                    $k_cnt = 1;
                    $k_all_post_num = $k_query->found_posts;//全件数
                    while( $k_query->have_posts() ): $k_query->the_post();
                        // $terms = get_the_terms( get_the_ID(),'news_cat'); 
        ?>
						<li><a href="#anc0<?php echo $k_cnt; ?>"><?php echo get_the_title(); ?></a></li>

                    	<?php $k_cnt++;  ?>
                    <?php endwhile; ?>
                <?php else: ?>  
                <?php endif; wp_reset_query();?>

<?php /* 	
					<li><a href="#anc01">オペアケア</a></li>
					<li><a href="#anc02">海外インターンシップ</a></li>
					<li><a href="#anc03">ワークトラベル</a></li>
					<li><a href="#anc04">J1ビザ取得</a></li>
					<li><a href="#anc05">東南アジアインターンシップ</a></li>
					<li><a href="#anc06">語学&キャリア準備</a></li>
  //*/ ?>
				</ul>
				</div>



        <?php 
                $args = array(
                    'post_type' => 'service', //記事データを取得  
                    'post_status'    => array( 'publish' ),
                    'posts_per_page' => -1 //取得数は 制限なし

                );


                $k_query = new WP_Query( $args );
                if( $k_query->have_posts() ): 
                    $k_cnt = 1;
                    $k_all_post_num = $k_query->found_posts;//全件数
                    while( $k_query->have_posts() ): $k_query->the_post();
                        // $terms = get_the_terms( get_the_ID(),'news_cat'); 
        ?>

				<div id="anc0<?php echo $k_cnt; ?>" class="text">
    			    <?php $k_post_img = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ),'full'); ?>


<!--					<div class="service_img"><img src="<?php echo $k_post_img[0]; ?>" alt="" width="100%" ></div>-->
					<div class="service_img">
            <a  href="<?php the_permalink(); ?>">
              <img src="<?php echo $k_post_img[0]; ?>" alt="" width="100%" >
            </a>
          </div>
          
					<div class="service_index">
						<div class="service_L">
							<p><?php echo get_the_title(); ?><span><?php echo get_field('service_en_title'); ?></span></p>
						</div>
						<div class="service_R">
							<p class="ttl"><?php echo get_field('service_archive_head'); ?></p>
							<p class="txt"><?php the_content() ?></p>

							<?php if (	get_field('service_link_url') ): ?>
								<?php if (  preg_match("/^http/" , get_field('service_link_url') )	): ?>
									<a href="<?php echo get_field('service_link_url'); ?>" class="more" target="_blank">More</a>
								<?php else: ?>	
									<a href="<?php echo get_field('service_link_url'); ?>?>" class="more" >More</a>
								<?php endif ?>

							<?php else: ?>
								<a href="<?php echo get_the_permalink(); ?>" class="more">More</a>
							<?php endif ?>
						</div>
					</div>
				</div>

                    	<?php $k_cnt++;  ?>
                    <?php endwhile; ?>
                <?php else: ?>  
                <?php endif; wp_reset_query();?>



			</div>
		</section>

		<?php get_template_part('parts/footer_content_1'); ?>

	</main>
	<!--/main-->



<?php get_footer(); ?>


