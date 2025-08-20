<?php


//	任意の日と 今日 の日付を比べる為の関数	
$k_TODAY = strtotime( date('Y/m/d') );
function k_check_new_post($date) {

  global $k_TODAY;
  $date = strtotime($date);
  $dayDiff = abs($k_TODAY - $date) / 86400; //(60 * 60 * 24)
  return ($dayDiff <= 2);  //  2= 指定の期間   2日以内なら TRUE 
}



//  関数作成のテスト  ほしい記事を取得する関数
function ki_get_posts( $num, $post_type_name){
	$args = array(
		'numberposts' => $num,             //表示（取得）する記事の数 
		'post_type' => $post_type_name     //投稿タイプ の 指定 
	);
	return( get_posts( $args ) );
}

//require_once(get_template_directory().'/function-elcom.php');



function k_get_fullybooked_text(){
	if( get_field('vacancy') == 'full' ){
		return "現在、定員に達したため、新規のお受け入れを一時見合わせております";
	}else{
		return "";
	}
}
add_shortcode('k_get_fullybook_text','k_get_fullybooked_text');


function k_get_the_seminar_texts(){ //セミナーの開催時間など 表示する MWWP用    

						$args = array(
							'post_type' => 'seminar_date', //記事データを取得  
							'posts_per_page' => -1,//取得数は3に制限
							'post_status'    => array( 'publish' )
						);
						$k_texts = array();

						$k_query = new WP_Query( $args );
						if ( 	$k_query->have_posts() ) : 
							while ( 	$k_query->have_posts() ) : 	$k_query->the_post();

							if( have_rows('event_detes' , get_the_ID()) ) :
								while( have_rows('event_detes' ,get_the_ID()) ) : the_row( );
									$k_time = get_sub_field( 'event_time' ,get_the_ID() );

									$k_sw = get_sub_field('event_day') . $k_time . get_the_title( get_the_ID() ); 
									array_push( $k_texts, $k_sw);

								endwhile;
							endif;

							foreach( $k_texts as $k_text){
								echo "<p> $k_text </p>";
							}

						

							endwhile; //endforeach; // ループの終了
						
						else : 
							echo "現在、開催予定のセミナーがございません。<br>";
						endif;
						wp_reset_postdata(); // 直前のクエリを復元する
							
}
add_shortcode('k_get_the_seminar','k_get_the_seminar_texts');
