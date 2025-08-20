<?php get_header(); ?>




<body class="kasou landc">
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WVP57Z"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<!-- Google Tag Manager (noscript) Ads-JP-->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T2RLF6F"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->



<?php if(have_posts()) : the_post(); endif;?>



    <header><!--header-->
        <?php get_template_part('parts/header_menu'); ?>
    </header><!--/header-->



    <!--main-->
    <main role="main" class="wrap">

        <section class="ttlArea">
            <h2><span><?php echo get_field( 'service_en_title'); ?></span><?php echo get_the_title(); ?></h2>
            <div class="breadcrumb">
                <a href="/">HOME </a> ＞ <a href="/service">サービス一覧 </a> ＞ <span><?php echo get_the_title(); ?></span>
            </div>
        </section>

        <?php $k_post_img = wp_get_attachment_image_src( get_field('service_main_visual'),'full'); ?>

        <section class="kasou_cell">
            <div class="box">
                <h3><?php echo get_field( 'service_header'); ?></h3>


                <div class="text">

                  <?php if( have_rows('contents_field2') ):
                    while( have_rows('contents_field2') ) : the_row();
                      $layout = get_row_layout();
                      switch( $layout){
                      case 'content_T': /*  テキストエリア  //*/ ?>
                        <p><?php the_sub_field('content_T_text'); ?></p>

                      <?php break; 
                      case "content_H": /*  見出し   //*/?>
                        </div>
                        <div class="text">
                        <h4><?php the_sub_field('content_H_header'); ?></h4>

                      <?php break; 
                      case 'content_IRT': /*  テキストエリア ＋ 右側に画像  //*/?>

                        <div class="landc_cell">
                            <div class="img02"><img src="<?php echo get_sub_field('content_IRT_img'); ?>" alt=""></div>
                            <div class="txtArea">
                                <h5><?php echo get_sub_field('content_IRT_img_head'); ?></h5>
                                <p><?php echo get_sub_field('content_IRT_text'); ?></p>
                            </div>
                        </div>

                      <?php break; 
                      case 'content_ILT': /*  見出し＋ 左側に画像 ＋ テキストエリア  //*/?>

                        <div class="landc_cell">
                            <div class="img"><img src="<?php echo get_sub_field('content_ILT_img'); ?>" alt=""></div>
                            <div class="txtArea">
                                <h5><?php echo get_sub_field('content_ILT_img_head'); ?></h5>
                                <p><?php echo get_sub_field('content_ILT_text'); ?></p>
                            </div>
                        </div>

                      <?php break; 
                      case 'content_3col': /*  3カラム  //*/ ?>
                            <ul class="list_service01">

                              <?php if( have_rows('content_3col') ):
                                while( have_rows('content_3col') ) : the_row(); ?>
                                    <li>
                                        <img src="<?php echo get_sub_field('content_3col_img'); ?>" alt="">
                                        <h5><?php echo get_sub_field('content_3col_head'); ?></h5>
                                        <p class="prog"><span><?php echo get_sub_field('content_3col_btext'); ?></span><br>
                                        <?php echo get_sub_field('content_3col_text'); ?></p>
                                    </li>


                                <?php endwhile; ?>
                              <?php endif;?>

                            </ul>

                            <p><?php echo get_sub_field('content_3col_notes'); ?></p>

                      <?php break; 
                      case 'content_list': /*  テキスト + リスト  //*/ ?>

                        <?php if( get_sub_field('content_list_text') ): ?>
                            <p><?php echo get_sub_field('content_list_text'); ?></p>
                        <?php endif; ?>

                        <?php if( have_rows('content_lists_set') ):
                          while( have_rows('content_lists_set') ) : the_row(); ?>


                                <?php if( get_sub_field('content_head_state') == "強調"): ?>
                                    <h5><?php echo get_sub_field('content_head'); ?></h5>
                                <?php else: ?>
                                    <p class="mb10"><?php echo get_sub_field('content_head'); ?></p>
                                <?php endif; ?>

                                <ul class="list_disc mb40">
                                  <?php if( have_rows('content_lists') ):
                                    while( have_rows('content_lists') ) : the_row(); ?>

                                        <li><?php echo get_sub_field('content_list_cell'); ?></li>

                                    <?php endwhile; ?>
                                  <?php endif;?>
                                </ul>


                          <?php endwhile; ?>
                        <?php endif;?>



                      <?php break; 
                      case 'content_flow': /*  「流れ」説明用  //*/ ?>



                        <?php if( get_sub_field('content_flow_text') ): ?>
                            <p><?php echo get_sub_field('content_flow_text'); ?></p>
                        <?php endif; ?>

                        <ul class="list_visa">

                            <?php if( have_rows('content_flow_set') ):
                              $k_cnt = 1;
                              while( have_rows('content_flow_set') ) : the_row(); ?>

                                <li>
                                    <div class="sp_txtarea txt_cell">
                                    <p class="num"><span><?php echo $k_cnt; ?></span></p>
                                    <p class="ttl"><?php echo get_sub_field('content_flow_head'); ?></p>
                                    </div>
                                    <div class="txt_cell">
                                    <p class="txt"><?php echo get_sub_field('content_flow_part_text'); ?></p>
                                    </div>
                                </li>

                                <?php $k_cnt++; ?>
                              <?php endwhile; ?>
                            <?php endif;?>
                        </ul>



                      <?php break; 
                      default:    ?>

                      <?php break;
                      } ?>

                    <?php endwhile; ?>
                  <?php endif;?>
                </div>


            </div>
        </section>

        <?php get_template_part('parts/footer_content_1'); ?>

    </main>
    <!--/main-->

        <script>
          $(function() {
            var main_visual = "<?php echo $k_post_img[0]; ?>";
            if( main_visual ){
//            var main_visual = "/wp-content/themes/intrax/images/ttl/bg_ttl_j1visa.jpg";
              $('.ttlArea').css('background-image', 'url(' + main_visual + ')' );
            }
          });
        </script>




<?php get_footer(); ?>