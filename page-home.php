<?php get_header(); ?>
<?php if(have_posts()) : the_post(); ?>
<?php /*the_content() */ ?>
<?php endif;wp_reset_postdata(); ?>

<body class="index">
<!-- kusanagi -->
<!-- Google Tag Manager (noscript) -->
<noscript>
<iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WVP57Z"
height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- End Google Tag Manager (noscript) --> 

<!-- Google Tag Manager (noscript) Ads-JP-->
<noscript>
<iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T2RLF6F"
height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- End Google Tag Manager (noscript) --> 

<!--header-->
<header>
  <?php get_template_part('parts/header_menu'); ?>
</header>
<!--/header--> 

<!--main-->
<main role="main" class="wrap">
  <article id="mv">
    <video muted autoplay loop id="movie">
      <source src="<?php echo get_template_directory_uri(); ?>/video/intrax_movie.mp4" type="video/mp4" >
    </video>
    <div class="ttl"><img src="<?php echo get_template_directory_uri(); ?>/images/index/ttl_mv_pc.png" alt="" class="pc"><img src="<?php echo get_template_directory_uri(); ?>/images/index/ttl_mv_sp.png" alt="" class="sp"></div>
    <div class="scroll"><a href="#index_news"><img src="<?php echo get_template_directory_uri(); ?>/images/index/btn_scroll.png" alt=""></a></div>
  </article>
  <article id="index_news"> 
    <!--    新型コロナウイルス対応-->
    <div class="message">
      <p class="lead-message">イントラックスでは、在宅勤務体制をとっております。<br>
        ご登録者・派遣生および保護者様は担当者までEメールにてご連絡ください。<br>
        なお、これからの留学についてのご相談につきましては、お電話 <a href="tel:0334342729">03-3434-2729 </a>または<a href="https://www.intraxjp.com/common_form/">こちら</a>からお問い合わせください。 </p>
    </div>
    <!--    end 新型コロナウイルス対応-->
    
    <h2><span>News</span>お知らせ</h2>
    <div class="articleInner">
      <ul class="content">
        <li>
          <dl class="list_news">
            <?php query_posts('post_type=news'); ?>
            <?php if(have_posts()): while(have_posts()): the_post(); ?>
            <?php
            $catname = '';
            if ( $terms = get_the_terms( $post->ID, 'newscat' ) ) {
              foreach ( $terms as $term ) {
                $catname = ( $term->name );
                break;
              }
              $catname = '<span class="cate">' . $catname . '</span>';
            } else {
              $catname = '<span class="catenone">' . $catname . '</span>';
            }

            $linkurl = get_field( 'linkurl' );
            $linktype = get_field( 'linktype' );

            $title = get_the_title();
            if ( $linkurl != "" ) {
              $blk = '';
              if ( $linktype != 'self' ) {
                $blk = ' target="_blank"';
              }
              $title = '<a href="' . $linkurl . '"' . $blk . '>' . $title . '</a>';
            }
            ?>
            <dd><span class="date">
              <?php the_time('Y/m/d'); ?>
              </span><?php echo $catname ?><?php echo $title; ?></dd>
            <?php endwhile; endif; ?>
            
            <!--
							<dd><span class="date">2017/3/01</span><span class="cate">カテゴリ</span><a href="#">ダミー文章です。内容が入ります。ダミー文章です。内容が入ります。ダミー文章です。</a></dd>
							<dd><span class="date">2017/3/01</span><span class="cate">カテゴリ</span><a href="#">ダミー文章です。内容が入ります。ダミー文章です。内容が入ります。ダミー文章です。</a></dd>
							<dd><span class="date">2017/3/01</span><span class="cate">カテゴリ</span><a href="#">ダミー文章です。内容が入ります。ダミー文章です。内容が入ります。ダミー文章です。</a></dd>
-->
          </dl>
        </li>
      </ul>
      <?php
      /* 一覧はなし
					<a href="#" class="more">More</a>
				//*/
      ?>
    </div>
  </article>
  <article id="index_reason">
    <div class="ttlArea">
      <div class="img"><img src="<?php echo get_template_directory_uri(); ?>/images/index/img_reason.jpg" alt=""></div>
      <div class="ttl">
        <h2><span>Reason</span>選ばれる理由</h2>
        <p>イントラックスは1980年にアメリカのカリフォルニア州サンフランシスコに設立され、米国国務省に認可されたJ1ビザのスポンサーとして、若い世代がプログラムの経験を通して、異文化理解を深め、真の国際人として活躍できるようサポートしています。</p>
      </div>
    </div>
    <div class="articleInner">
      <ul>
        <li> <img src="<?php echo get_template_directory_uri(); ?>/images/index/img_reason01_pc.jpg" alt="" class="reason_cell imgChange">
          <div class="txtArea">
            <div class="sp_txtArea reason_cell">
              <p class="txt_num">1<span>Sponsor</span></p>
              <p class="txt_ttl">J-1ビザ<br class="sp">
                （交流訪問者ビザ）の<br class="sp">
                スポンサー</p>
            </div>
            <p class="txt_detail">イントラックスは米国国務省に認可されたJ1ビザのスポンサーです。イントラックスが皆さんのビザのスポンサーとなり、受入先（ホストファミリー、ホストスクール、ホストカンパニー等）の審査および事前準備や渡航先でのサポートも行っています。</p>
          </div>
        </li>
        <li> <img src="<?php echo get_template_directory_uri(); ?>/images/index/img_reason02_pc.jpg" alt="" class="reason_cell imgChange">
          <div class="txtArea">
            <div class="sp_txtArea reason_cell">
              <p class="txt_num">2<span>Program</span></p>
              <p class="txt_ttl">進学や就職に活かせる<br>
                プログラム</p>
            </div>
            <p class="txt_detail">オペア、インターンシップ、ワークトラベルなどの実施研修では、異文化でさまざまな国籍の人々と働くことで、グローバルに活躍するためには言語だけでなく、異文化を理解して尊重することが必要なことを学びます。</p>
          </div>
        </li>
        <li> <img src="<?php echo get_template_directory_uri(); ?>/images/index/img_reason03_pc.jpg" alt="" class="reason_cell imgChange">
          <div class="txtArea">
            <div class="sp_txtArea reason_cell">
              <p class="txt_num">3<span>Get ready</span></p>
              <p class="txt_ttl">渡航前の準備</p>
            </div>
            <p class="txt_detail">渡航先で充実した日々を過ごすためには、事前の準備が必要です。オペア、インターンシップ、ワークトラベルなどでは、アメリカの受入先との面接に備え、ネイティブ講師との個別面接練習を提供し、英文履歴書の添削やアドバイスを行っています。</p>
          </div>
        </li>
      </ul>
      <a href="/reason/" class="more">More</a> </div>
  </article>
  <article id="index_service">
    <h2><span>Service</span>サービス一覧</h2>
    <p class="lead">イントラックスは、米国国務省認定のJ-1ビザのスポンサーとして、<br>
      アメリカの高校留学やインターンシップなどを運営している教育機関です。<br>
      若い世代の国際理解を深め、真の国際人として活躍してもらうことを目的に様々なプログラムを提供しています。</p>
    <ul>
      <li><!--<a href="https://aupaircare.intraxjp.com/">--> 
        <a href="<?php echo home_url()?>/aupaircare/"> 
        <!--<a href="/service/#anc01">--> 
        <img src="<?php echo get_template_directory_uri(); ?>/images/index/img_service01.jpg" alt="">
        <div class="service_cell">
          <h3>オペアケア<span>AuPairCare</span></h3>
          <p>保育経験ゼロからでもはじめられる有給チャイルドケアプログラム</p>
        </div>
        </a> </li>
      <li> 
        <!--<a href="https://internships.intraxjp.com/" target="_blank">--> 
        <a href="<?php echo home_url()?>/internships/"> <img src="<?php echo get_template_directory_uri(); ?>/images/index/img_service_internships.png" alt="">
        <div class="service_cell">
          <h3>インターンシップ<span>Internship</span></h3>
          <p>J-1ビザの申請手続きに必要となる承認書（DS-2019）の発行。受入先となるホストカンパニーが決まっている方が対象</p>
        </div>
        </a> </li>
      <li> 
        <!--<a href="https://worktravel.intraxjp.com/" target="_blank">--> 
        <a href="<?php echo home_url()?>/worktravel/"> <img src="<?php echo get_template_directory_uri(); ?>/images/index/img_service03.jpg" alt="">
        <div class="service_cell">
          <h3>ワークトラベル<span>Work Travel</span></h3>
          <p>アメリカで世界の大学生と一緒にアルバイト。大学の夏休み・春休み期間で参加</p>
        </div>
        </a> </li>
      <li> 
        <!-- <a href="<?php echo home_url()?>/service/j1-visa/"> --> 
        <a href="<?php echo home_url()?>/ayusa/" target="_blanc"> <img src="<?php echo get_template_directory_uri(); ?>/images/index/img_service_ayusa.png" alt="">
        <div class="service_cell">
          <h3>アユサ高校交換留学<span>Ayusa High School Exchange</span></h3>
          <p>J-1ビザのスポンサーとして
            高校交換留学を運営している米国非営利教育法人</p>
        </div>
        </a> </li>
    </ul>
<!--    <a href="<?php echo home_url()?>/service/" class="more">More</a> -->
	<a href="https://www.intraxjp.com/footer-links/" target="_blank" rel=”noopener” class="more">More</a> 
	</article>
  <article id="index_content">
    <div class="content_cell btnArea">
      <div class="btn"><a href="<?php echo home_url()?>/company/">
        <p>会社情報<span>Company</span></p>
        </a></div>
      <div class="btn"><a href="<?php echo home_url()?>/recruit/">
        <p>採用情報<span>Recruit</span></p>
        </a></div>
    </div>
    <div class="content_cell ayusaArea">
      <div class="ayusalogo"><a href="<?php echo home_url()?>/ayusa/" target="_blank"><img class="logo_ayusa" src="https://www.intraxjp.com/ayusa/wp-content/themes/ayusa/img/ayusa_logo_white.png" alt=""></a></div>
      <div class="ayusatxt">
        <h3>アユサ高校交換留学<span>Ayusa</span></h3>
        <p>アユサインターナショナルは、アメリカのカリフォルニア州サンフランシスコに1980年に設立された、J1ビザのスポンサーとして高校交換留学を運営している米国非営利教育法人です。</p>
        <div class="link"><a href="<?php echo home_url()?>/ayusa/" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/images/index/link_ayusa.png" alt=""></a></div>
      </div>
    </div>
  </article>
  <?php get_template_part('parts/footer_content_1'); ?>
</main>
<!--/main-->

<?php get_footer(); ?>
