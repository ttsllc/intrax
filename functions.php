<?php
function is_active_plugin($path){
	$active_plugins = get_option('active_plugins');
	if(is_array($active_plugins)) {
		foreach($active_plugins as $value){
			if( $value == $path) return true;
		}
	}
	return false;
}

//旧スラッグからの自動補完リダイレクトを停止
//remove_action( 'template_redirect', 'wp_old_slug_redirect' );
//こちらは非推奨！（リダイレクトを担う関数自体を無効化する方法）
remove_action('template_redirect', 'redirect_canonical');



//  投稿にアイキャッチ画像の設定ができるようになる  
add_theme_support('post-thumbnails');


//  投稿記事一覧にアイキャッチがのるようにできる 下記で 一覧に アイキャッチの表示枠を設定
function add_thumbnail_column( $columns ){
	$post_type = isset( $_RECUEST[ 'post_type'] ) ? $_REQUEST[ 'post_type' ] : 'post';
	if( post_type_supports( $post_type, 'thumbnail' ) ){
		$columns['thumbnail'] = __( 'Featured Images');
	}
	return $columns;
}
add_filter( 'manage_posts_columns', 'add_thumbnail_column');

//  投稿記事一覧にアイキャッチがのるようにできる 下記で 一覧に アイキャッチ画像を表示
function display_thumbnail_column( $column_name, $post_id ){
	if( $column_name == 'thumbnail' ){
		if( has_post_thumbnail( $post_id ) ){
			echo get_the_post_thumbnail( $post_id, array( 50, 50) );
		}else{
			_e( 'none');
		}
	}
}
add_action( 'manage_posts_custom_column', 'display_thumbnail_column',10,2);
//*/


/* json 系の 禁止  */
//formの為、停止（sakai）
//remove_action('wp_head','rest_output_link_wp_head');
//remove_action('wp_head','wp_oembed_add_discovery_links');
//remove_action('wp_head','wp_oembed_add_host_js');
//remove_action('template_redirect', 'rest_output_link_header', 11 );


//----sakai add 2018.04.25

//お問い合わせのカスタムバリデーション
function contact_validation($Validation, $data, $Data){
	//名前の共通必須バリデ
	if(empty($data['nam1'])){
		$Validation->set_rule('nam1', 'noEmpty');
	}elseif(empty($data['nam2'])){
		$Validation->set_rule('nam2', 'noEmpty');
	}
	//ふりがなの共通必須、ひらがなバリデ
/*
	if(empty($data['furi1'])){
		$Validation->set_rule('furi1', 'noEmpty');
	}elseif(empty($data['furi2'])){
		$Validation->set_rule('furi2', 'noEmpty');
	}elseif(!preg_match("/^[ぁ-ゞ]+$/u",$data['furi1'])){
		$Validation->set_rule('furi1', 'hiragana');
	}elseif(!preg_match("/^[ぁ-ゞ]+$/u",$data['furi2'])){
		$Validation->set_rule('furi2', 'hiragana');
	}
*/

//プログラム　オペア選択時は「仮登録」を必須に
/*if(isset($data['prog'])){
if(in_array('オペアケア',$data['prog']['data'])){
$message = 'オペアケア選択時は仮登録希望の有無を選択してください';
$Validation->set_rule('aupair_kari','required',array('message' => $message));
}
	}*/


	//資料郵送希望なら住所必須
	if(isset($data['hope']['data']) && $data['hope']['data'][0] == '希望する'){
	//if(isset($data['hope']) && $data['hope']['data'][0] == '希望する'){
		$message = '資料郵送希望の方は入力してください';
		if(empty($data['zipcode'])){
			$Validation->set_rule('zipcode','noEmpty',array('message' => $message));
		}elseif(empty($data['pref'])){
			$Validation->set_rule('pref', 'noEmpty',array('message' => $message));
		}elseif(empty($data['address1'])){
			$Validation->set_rule('address1','noEmpty',array('message' => $message));
		}elseif(empty($data['address2'])){
			$Validation->set_rule('address2','noEmpty',array('message' => $message));
		}
	}
  return $Validation;
}
add_filter('mwform_validation_mw-wp-form-4429', 'contact_validation', 10, 3 );
add_filter('mwform_validation_mw-wp-form-4604', 'contact_validation', 10, 3 );

/***************************************************2024年2月13日対応*/
//フォーム 生年月日の選択肢を80年前からにする
function select_birth( $children, $atts ) {
	
// 現在の日時を取得
$now = new DateTime();
// 80年前に移動
$now->sub(new DateInterval('P80Y'));
// 西暦のみを取得
$year = $now->format('Y');
/***************************************************2024年2月13日対応 ここまで*/
	
	
  if ( $atts['name'] == 'birth_year' ) {
	  //1980年からは下記
		//for($i = 1980;$i < date("Y") + 1;$i++){
			for($i = $year;$i < date("Y") + 1;$i++){
			$children[$i] = $i;
		}
  }
  if ( $atts['name'] == 'birth_month' ) {
		for($i = 1;$i <= 12;$i++){
			$children[$i] = $i;
		}
  }
  if ( $atts['name'] == 'birth_day' ) {
		for($i = 1;$i <= 31;$i++){
			$children[$i] = $i;
		}
  }
  return $children;
}
add_filter( 'mwform_choices_mw-wp-form-4429', 'select_birth', 10, 2 );
add_filter( 'mwform_choices_mw-wp-form-4604', 'select_birth', 10, 2 );




//Sales force用データ生成
function set_data_to_salesforce ($data) {
//		'oid' => '00D7F000005VFZz',//sakai test ID

	//00N30000007BGW0 IntraxProgramOptions　作成
	$inprogOp = "AuPairCare";

	switch ($data['prog']) {
	    case "オペアケア":
		$inprogOp = "AuPairCare";
	        break;
	    case "海外インターンシップ":
		$inprogOp = "Internship";
	        break;
	    case "ワークトラベル":
		$inprogOp = "Work Travel";
	        break;
	    case "J1ビザ手配":
		$inprogOp = "Hospitality Abroad"; //
	        break;
	    case "語学＆キャリア準備":
		$inprogOp = "English and Professional Skills";
	        break;
	


	}

	//reference

	$refce = "";
	switch ($data['reference']) {
	    case "学校のポスター":
		$refce = "School - Poster"; break;
	    case "学校の先生":
		$refce = "School - Teacher"; break;
	    case "ホームページ":
		$refce = "Internet - Home Page"; break;
	    case "Yahoo!検索":
		$refce = "Internet - Yahoo Search"; break;
	    case "Google検索":
		$refce = "Internet - Google Search"; break;
	    case "友人":
		$refce = "Other - Friends"; break;
	    case "その他":
		$refce = "Other - Other"; break;
	}	
	$hope = "希望しない";
	if(isset($data['hope']['data']) && $data['hope']['data'][0] == '希望する'){
	$hope = "希望する";
	}



/* 本番用 */
	$array = array(
		'oid' => '00D30000000p3II',
		'00N300000068fwe' => $data['birth_month'].'/'.$data['birth_day'].'/'.$data['birth_year'], //birthOfDate
		'Primary Activity' => '',
		'00N30000008QnVM' => $data['affiliation'], // educationLevel
		'00N300000068JwE' => 'Participant', // unknown
		'00N300000068Glm' => 'Internship', // IntraxPrograms(ex.Internship->Internship)
		'00N3000000692oE' => 'Japan', // IntraxRegion
		'lead_source' => 'Web Form',
		'00N300000069Noq' => 'Web Form', // LeadSourceTag
		'00N3000000692rD' => 'Japanese', // WebFormLanguage
		'00N30000007BGW0' => $inprogOp, // IntraxProgramOptions
		'first_name' => $data['nam2'],
		'last_name' => $data['nam1'],
		'first_name_local' => $data['nam2'],
		'last_name_local' => $data['nam1'],
		'00N30000008QJrm' => $data['furi2'],
		'00N30000008QJrw' => $data['furi1'],
		'year' => '',
		'month' => '',
		'day' => '',
		'email' => $data['email'],
		'zip' => $data['zipcode'],
		'street' => $data['pref'].' '.$data['address1'].' '.$data['address2'],
		'phone' => $data['tel'],
		'mobile' => '',
		'yusoflg' => $hope,
		'00N300000068Glc' => $data['gender'], // gender
		'reference' => $refce,
		'00N400000021x8T' => '', // unknown
	  '00N300000068ZCr' => '', // How Heard
		'submit1' => 'submit',
	);
/* --- */

/* DEMOテスト用 */
/*
	$array = array(
		'oid' => '00D7F000005VFZz',
		'00N7F00000LF7Zx' => $data['birth_month'].'/'.$data['birth_day'].'/'.$data['birth_year'], //birthOfDate
		'00N7F00000LF7hN' => '',
		'00N7F00000LF7b0' => $data['affiliation'], // educationLevel
		//'00N300000068JwE' => 'Participant', // unknown
		'00N7F00000LF88x' => 'Internship', // IntraxPrograms(ex.Internship->Internship)
		'00N7F00000LF8Gc' => 'Japan', // IntraxRegion
		'00N7F00000LF8R6' => 'Web Form',
		'00N7F00000LF8ZF' => 'Web Form', // LeadSourceTag
		'00N7F00000LF8i2' => 'Japanese', // WebFormLanguage
		'00N7F00000LF8nH' => $inprogOp, // IntraxProgramOptions
		'first_name' => $data['nam2'],
		'last_name' => $data['nam1'],
		'first_name_local' => $data['nam2'],
		'last_name_local' => $data['nam1'],
		'00N7F00000LF7al' => $data['furi2'],
		'00N7F00000LF7aS' => $data['furi1'],
		'year' => '',
		'month' => '',
		'day' => '',
		'email' => $data['email'],
		'zip' => $data['zipcode'],
		'00N7F00000LF8nl' => $data['pref'].' '.$data['address1'].' '.$data['address2'],
		'phone' => $data['tel'],
		'mobile' => '',
		'00N7F00000LF7a2' => $hope,
		'00N7F00000LF7av' => $data['gender'], // gender
		'00N7F00000LF7bA' => $refce,
		//'00N400000021x8T' => '', // unknown
	  '00N7F00000LF8nW' => '', // How Heard
		'submit1' => 'submit',
	);
*/
	return $array;
}

// curlダメそうなので、file_put_contentで
function send_post ($data) {
	$url = 'https://webto.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8';
//	$url = 'https://test.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8';
	$content = http_build_query($data);
	$options = array('http' => array('method' => 'POST','content' => $content));
	$contents = file_get_contents($url,false,stream_context_create($options));
}

//Sales forceとの連携

//お問い合わせテスト
function send_contact_data_to_salesforce ($data) {
	$data = $data->gets();

/* 本番用 */

	$data['hope'] = (isset($data['hope']))? '希望する':'希望しない';
	$array = set_data_to_salesforce($data);
	$array['retURL'] = home_url().'/contact/complete/';
	$array['00N300000068mQw'] = 'Contact'; // WebFormType
	$array['formflg'] = 'Contact';
	$array['00N30000007ClGQ'] = "法人名：".$data['company'].'\n\n'.$data['content']; // content
	$array['00N30000008QK6r'] = ''; // CounselingRequest1
	$array['00N30000008QK7B'] = ''; // CounselingRequestType1
	$array['00N30000008QK6m'] = ''; // infoSessionDate
	$array['00N30000008QK6c'] = ''; // infoSessionLocation
/* */

/* Demoテスト用 */
/********
	$array = set_data_to_salesforce($data);
	$array['retURL'] = home_url().'/contact/complete/';
	$array['formflg'] = 'Contact';
********/


	send_post($array);


}
add_action('mwform_after_send_mw-wp-form-4429','send_contact_data_to_salesforce');
//-----------sakai edit end-------------

/*20190801 法人用*/
function send_contact_data_to_salesforce_j1 ($data) {
	$data = $data->gets();

/* 本番用 */


$data['prog']='Internship - J1';


	$data['hope'] = (isset($data['hope']))? '希望する':'希望しない';
	$array = set_data_to_salesforce_j1($data);
	$array['retURL'] = home_url().'/corp_form/complete/';
	$array['00N300000068mQw'] = 'Contact'; // WebFormType
	$array['formflg'] = 'Contact';
	//$array['00N30000007ClGQ'] = $data['content'].' [郵送希望]'.$data['hope']; // content
	$array['00N30000007ClGQ'] = "法人名：".$data['company']."\n".$data['nam1']." ".$data['nam2']."（".$data['furi1']." ".$data['furi2']."）"."\n\n".$data['content']; // content
	$array['00N30000008QK6r'] = ''; // CounselingRequest1
	$array['00N30000008QK7B'] = ''; // CounselingRequestType1
	$array['00N30000008QK6m'] = ''; // infoSessionDate
	$array['00N30000008QK6c'] = ''; // infoSessionLocation
/* */

/* Demoテスト用 */
/********
	$array = set_data_to_salesforce($data);
	$array['retURL'] = home_url().'/contact/complete/';
	$array['formflg'] = 'Contact';
********/


	send_post($array);


}
add_action('mwform_after_send_mw-wp-form-5142','send_contact_data_to_salesforce_j1');


/*
if( function_exists('acf_add_options_page') ) {

//	オプションページの追加  注目記事
//    $option_page2 = acf_add_options_page(array(
//        'page_title' => '注目記事登録', 		  // 設定ページで表示される名前
//        'menu_title' => '注目記事', // ダッシュボードに表示される名前
//        'menu_slug' => 'pickup_slider',
//        'capability' => 'edit_posts',
//        'redirect' => false
//    ));


    acf_add_options_page(array(
        'page_title' => 'トップメイン画像', 		  // 設定ページで表示される名前
        'menu_title' => 'トップメイン画像', // ダッシュボードに表示される名前
        'menu_slug' => 'top_imgs',
        'capability' => 'edit_posts',
        'redirect' => false,
        'position' => 33 //  管理画面 の 左側 で表示される時の優先度 menu_position の値 
    ));
}
//*/







//  wordpress の リサイズによる画像 の劣化を防ぐ  画像の圧縮率を 100％ にしているため処理が犠牲になる 
add_filter( 'jpeg_quality', function( $arg ){ return 90; } );

//	the_content  の後で 自動的に br が入るのを防ぐ
remove_filter('the_content', 'wpautop');


//短縮URL取得 ボタンを表示する   PHP5.3 以上の場合
//add_filter('get_shortlink', function($shortlink){return $shortlink;});


require_once(get_template_directory().'/lib/common_short_codes.php');
require_once(get_template_directory().'/lib/ki_functions.php');


//tinyMCEが勝手にタグを消すのを防ぐ
function my_tiny_mce_before_init( $init_array ) {
    $init_array['valid_elements']          = '*[*]';
    $init_array['extended_valid_elements'] = '*[*]';

    return $init_array;
}
add_filter( 'tiny_mce_before_init' , 'my_tiny_mce_before_init' );


/*
2019 Form統合
*/

///*
//固定ページにGETパラメータを渡す為の記述
//*/
//function set_org_query_vars( $query_vars ) {
//  $query_vars[] = 'prog';       // 独自のパラメータ prm1を配列最後尾に追加する。
//  $query_vars[] = 'prm2';       // 独自のパラメータ prm2を配列最後尾に追加する。
//  return $query_vars;
//}
////add_filter('query_vars', 'set_org_query_vars');



/**
 * @param  String  $value  valueの初期値
 * @param  String  $name  name属性値
 */
function my_mwform_value( $value, $name ) {
   
   if(!preg_match("/common_form/", wp_get_referer())){
   
   
   
    // $_GET['hoge']があったら、name属性がhogeの項目の初期値に設定
    if ( isset($_GET['prog']) && $name === 'prog' && !empty( $_GET['prog'] ) && !is_array( $_GET['prog'] ) ) {
        return $_GET['prog'];
    }elseif(isset($_POST['prog']) && $name === 'prog' && !empty( $_POST['prog'] ) && !is_array( $_POST['prog'] )){
		return $_POST['prog'];
		}
	
	
	 if( isset($_POST['prog']) && is_array( $_POST['prog']) && $name === 'prog' ){
		 
		 return $_POST['prog'];
		 
		 }
	
	
	
	
	if( isset($_GET['hope']) && $name === 'hope' && !empty( $_GET['hope'] ) && !is_array( $_GET['hope'] ) ) 
	{
		return $_GET['hope'];
}elseif( isset($_POST['hope']) && $name === 'hope' && !empty( $_POST['hope'] ) && !is_array( $_POST['hope'] ) ){
	    return $_POST['hope'];
	}
		
	if( isset($_GET['kobetsu_hope']) && $name === 'kobetsu_hope' && !empty( $_GET['kobetsu_hope'] ) && !is_array( $_GET['kobetsu_hope'] ) ) 
	{
		return $_GET['kobetsu_hope'];
}elseif( isset($_POST['kobetsu_hope']) && $name === 'kobetsu_hope' && !empty( $_POST['kobetsu_hope'] ) && !is_array( $_POST['kobetsu_hope'] ) ){
	   return $_POST['kobetsu_hope'];
	}
	
		elseif(  isset($_GET['setsumei_hope']) && $name === 'setsumei_hope' && !empty( $_GET['setsumei_hope'] ) && !is_array( $_GET['setsumei_hope'] ) ) 
	{
		return $_GET['setsumei_hope'];
		}
		
elseif(  isset($_POST['setsumei_hope']) && $name === 'setsumei_hope' && !empty( $_POST['setsumei_hope'] ) && !is_array( $_POST['setsumei_hope'] ) ){
	return $_POST['setsumei_hope'];
	}
		
		
    return $value;
	
   }//if wp_get_referer()
}
 
// 管理画面で作成したフォームの場合、フック名の後のフォーム識別子は「mw-wp-form-xxx」
add_filter( 'mwform_value_mw-wp-form-4604', 'my_mwform_value', 10, 2 );





/*
オペアの説明会予約フォーム 日程の選択肢

*/
//
//function aupair_select_briefing_dates( $children, $atts ) {
//
//
//
//  if ( $atts['name'] == 'briefing_date' ) {
//	  
//	  switch_to_blog(2);
//	  
//    $query = new WP_Query(array('page_id' => 4338));
//    if($query->have_posts()) : $query->the_post();
//      if(have_rows('briefing')):while(have_rows('briefing')):the_row(); 
//        $select = trim(get_sub_field('city')) . " " . trim(get_sub_field('time'));
//        $children[$select] = $select;
//
//      endwhile;endif;
//    endif;
//	restore_current_blog();
//	wp_reset_postdata();
//  }
//
//  return $children;
//}
//add_filter( 'mwform_choices_mw-wp-form-4604', 'aupair_select_briefing_dates', 10, 2 );

























//Sales force用データ生成
function set_data_to_salesforce2019 ($data) {
//		'oid' => '00D7F000005VFZz',//sakai test ID
//$inprogOp = $data['prog'];

//$prgArray = explode(',',$data['prog']['data']);

	//00N30000007BGW0 IntraxProgramOptions　作成
//	$inprogOp = "AuPairCare";
//
//	switch ($data['prog']) {
//	    case "オペアケア":
//		$inprogOp = "AuPairCare";
//	        break;
//	    case "海外インターンシップ":
//		$inprogOp = "Internship";
//	        break;
//	    case "ワークトラベル":
//		$inprogOp = "Work Travel";
//	        break;
//	    case "J1ビザ":
//		$inprogOp = "Hospitality Abroad"; //
//	        break;
//	    case "語学＆キャリア準備":
//		$inprogOp = "English and Professional Skills";
//	        break;
//
//	}


//if(is_array($data['prog']['data'])){

$prog = explode(',',$data['prog']['data']);


foreach($prog as $value){

//	switch ($value) {
//
//	    case "アユサ高校交換留学":
//		$inprogOp[] = "Ayusa";
//
//	    case "オペアケア":
//		$inprogOp[] = "AuPairCare";
//	       
//	    case "海外インターンシップ":
//		$inprogOp[] = "Internship";
//	        
//	    case "ワークトラベル":
//		$inprogOp[] = "Work Travel";
//	        
//	    case "J1ビザ":
//		$inprogOp[] = "Hospitality Abroad"; //
//
//	}
//
//
if('アユサ高校交換留学' == $value){ $inprogOpArray[] = "Ayusa";}
if('オペアケア'==$value) {$inprogOpArray[] = "AuPairCare";}

if('インターンシップ（米国）'==$value ){ 

$inprogOpArray[] = "Internship";

}

if('ボランティア（タイ）'==$value){$inprogOpArray[]='ProWorld';}

if('アユサ夏キャンプ'==$value){$inprogOpArray[]='AyusaSummerCamp2025';}
if('アユサ春キャンプ'==$value){$inprogOpArray[]='AyusaSpringCamp2025';}

if('ワークトラベル'==$value) {$inprogOpArray[] = "Work Travel";}

if('J1ビザ手配'==$value) {$inprogOpArray[] = "J1";}
if('教師派遣'==$value) {$inprogOpArray[] = "Teacher";}
if('スクールインターン'==$value) {$inprogOpArray[] = "School Intern";}
if('ホストファミリー（受け入れ）'==$value) {$inprogOpArray[] = "Host Family";}
//$inprogOp=$value;
}//foreach

//イントラックス （J1ビザ）
//

/*
20190513 メインの方へ入れる様に変更
*/

//$inprogOp = implode(',',$inprogOpArray);
/*
20220601 セミコロンで連結に変更
*/
$inprogOp = implode(';',$inprogOpArray);

if($inprogOp){
	
	$programTxt= $inprogOp;

	
	}else{
	
	$programTxt='Intrax';
	
	}

//$inprogOp=$data['prog']['data'][0];

//print__r($inprogOp);
//}else{

//if('アユサ高校交換留学' == $data['prog']['data']) $inprogOp = "Ayusa";
//if('オペアケア'==$data['prog']['data']) $inprogOp = "AuPairCare";
//if('インターンシップ（米国）'==$data['prog']['data'] or 'インターンシップ（アジア）'==$data['prog']['data'] ) $inprogOp = "Internship";
//if('ワークトラベル'==$data['prog']['data']) $inprogOp = "Work Travel";
//if('イントラックス （J1ビザ）'==$data['prog']['data']) $inprogOp = "Hospitality Abroad";

//}
//$inprogOp = implode(',',$inprogOpArray);

	//reference

	$refce = "";
	switch ($data['reference']) {
	    case "学校のポスター":
		$refce = "School - Poster"; break;
	    case "学校の先生":
		$refce = "School - Teacher"; break;
	    case "ホームページ":
		$refce = "Internet - Home Page"; break;
	    case "Yahoo!検索":
		$refce = "Internet - Yahoo Search"; break;
	    case "Google検索":
		$refce = "Internet - Google Search"; break;
	    case "友人":
		$refce = "Other - Friends"; break;
	    case "その他":
		$refce = "Other - Other"; break;
	}	
	$hope = "希望しない";
	if(isset($data['hope']['data']) && $data['hope']['data'][0] == '希望する'){
	$hope = "希望する";
	}
	
	$kobetsu_hope = "希望しない";
	if(isset($data['kobetsu_hope']['data']) && $data['kobetsu_hope']['data'][0] == '希望する'){
	$kobetsu_hope = "希望する";
	}



/* 本番用 */
	$array = array(
		'oid' => '00D30000000p3II',
		'00N300000068fwe' => $data['birth_month'].'/'.$data['birth_day'].'/'.$data['birth_year'], //birthOfDate
		'Primary Activity' => '',
		'00N30000008QnVM' => $data['affiliation'], // educationLevel
		'00N300000068JwE' => 'Participant', // unknown
		'00N300000068Glm' => $programTxt, // IntraxPrograms(ex.Internship->Internship)
		'00N3000000692oE' => 'Japan', // IntraxRegion
		'lead_source' => 'Web Form',
		'00N300000069Noq' => 'Web Form', // LeadSourceTag
		'00N3000000692rD' => 'Japanese', // WebFormLanguage
		/*'00N30000007BGW0' => $inprogOp, // IntraxProgramOptions*/
		'00N30000007BGW0' => '', // IntraxProgramOptions
		'first_name' => $data['nam2'],
		'last_name' => $data['nam1'],
		'first_name_local' => $data['nam2'],
		'last_name_local' => $data['nam1'],
		'00N30000008QJrm' => $data['furi2'],
		'00N30000008QJrw' => $data['furi1'],
		'year' => '',
		'month' => '',
		'day' => '',
		'email' => $data['email'],
		'zip' => $data['zipcode'],
		'street' => $data['pref'].' '.$data['address1'].' '.$data['address2'],
		'phone' => $data['tel'],
		'mobile' => '',
		'yusoflg' => $hope,
		'00N300000068Glc' => $data['gender'], // gender
		'reference' => $refce,
		'00N400000021x8T' => '', // unknown
	  '00N300000068ZCr' => '', // How Heard
		'submit1' => 'submit',
	);
/* --- */

/* DEMOテスト用 */
/*
	$array = array(
		'oid' => '00D7F000005VFZz',
		'00N7F00000LF7Zx' => $data['birth_month'].'/'.$data['birth_day'].'/'.$data['birth_year'], //birthOfDate
		'00N7F00000LF7hN' => '',
		'00N7F00000LF7b0' => $data['affiliation'], // educationLevel
		//'00N300000068JwE' => 'Participant', // unknown
		'00N7F00000LF88x' => 'Internship', // IntraxPrograms(ex.Internship->Internship)
		'00N7F00000LF8Gc' => 'Japan', // IntraxRegion
		'00N7F00000LF8R6' => 'Web Form',
		'00N7F00000LF8ZF' => 'Web Form', // LeadSourceTag
		'00N7F00000LF8i2' => 'Japanese', // WebFormLanguage
		'00N7F00000LF8nH' => $inprogOp, // IntraxProgramOptions
		'first_name' => $data['nam2'],
		'last_name' => $data['nam1'],
		'first_name_local' => $data['nam2'],
		'last_name_local' => $data['nam1'],
		'00N7F00000LF7al' => $data['furi2'],
		'00N7F00000LF7aS' => $data['furi1'],
		'year' => '',
		'month' => '',
		'day' => '',
		'email' => $data['email'],
		'zip' => $data['zipcode'],
		'00N7F00000LF8nl' => $data['pref'].' '.$data['address1'].' '.$data['address2'],
		'phone' => $data['tel'],
		'mobile' => '',
		'00N7F00000LF7a2' => $hope,
		'00N7F00000LF7av' => $data['gender'], // gender
		'00N7F00000LF7bA' => $refce,
		//'00N400000021x8T' => '', // unknown
	  '00N7F00000LF8nW' => '', // How Heard
		'submit1' => 'submit',
	);
*/
	return $array;
}

// curlダメそうなので、file_put_contentで
function send_post2019 ($data) {
	$url = 'https://webto.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8';
//	$url = 'https://test.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8';
	$content = http_build_query($data);
	$options = array('http' => array('method' => 'POST','content' => $content));
	$contents = file_get_contents($url,false,stream_context_create($options));
}



//Sales forceとの連携

//お問い合わせテスト
function send_contact_data_to_salesforce2019 ($data) {
	$data = $data->gets();


$setsumeikai=NULL;
if($data['ayusa_seminar_date']) $setsumeikai .= 'Ayusa説明会：' . $data['ayusa_seminar_date'].'  ';
if($data['aupair_seminar_date']) $setsumeikai .= 'オペア説明会：' . $data['aupair_seminar_date'].'  ';
if($data['worktravel_seminar_date']) $setsumeikai .= 'ワークトラベルWEB説明会：' . $data['worktravel_seminar_date'].'  ';
if($data['intern_us_seminar_date']) $setsumeikai .= 'インターンシップ（米国）WEB説明会：' . $data['intern_us_seminar_date'].'  ';
if($data['hostfamily_seminar_date']) $setsumeikai .= 'ホストファミリーWEB説明会：' . $data['hostfamily_seminar_date'].'  ';
if($data['J1_briefing_dates']) $setsumeikai .= 'J1ビザ手配WEB説明会：' . $data['J1_briefing_dates'].'  ';
if($data['intern_briefing_dates']) $setsumeikai .= 'スクールインターンWEB説明会：' . $data['intern_briefing_dates'].'  ';
if($data['teacher_briefing_dates']) $setsumeikai .= '教師派遣WEB説明会：' . $data['teacher_briefing_dates'].'  ';
if($data['thai_volunteer_seminar_date']) $setsumeikai .= 'ボランティア（タイ）WEB説明会：' . $data['thai_volunteer_seminar_date'].'  ';
if($data['summer_camp_seminar_date']) $setsumeikai .= 'アユサ夏キャンプWEB説明会：' . $data['summer_camp_seminar_date'].'  ';
if($data['springcamp2025_seminar_date']) $setsumeikai .= 'アユサ春キャンプWEB説明会：' . $data['springcamp2025_seminar_date'].'  ';
if($setsumeikai){ $content.=' [希望説明会] '.$setsumeikai;}



/*資料希望のプログラムを整形*/
$prog = explode(',',$data['doc_type']['data']);


foreach($prog as $value){

//	switch ($value) {
//
//	    case "アユサ高校交換留学":
//		$inprogOp[] = "Ayusa";
//
//	    case "オペアケア":
//		$inprogOp[] = "AuPairCare";
//	       
//	    case "海外インターンシップ":
//		$inprogOp[] = "Internship";
//	        
//	    case "ワークトラベル":
//		$inprogOp[] = "Work Travel";
//	        
//	    case "J1ビザ":
//		$inprogOp[] = "Hospitality Abroad"; //
//
//	}
//
//
if('アユサ高校交換留学' == $value){ $inprogOpArray[] = "Ayusa";}
if('オペアケア'==$value) {$inprogOpArray[] = "AuPairCare";

//$content.="オペア仮登録：".$data['aupair_kari'].'\n\n';
}

if('インターンシップ（米国）'==$value){ 

$inprogOpArray[] = "Internship";

}

if('インターンシップ（アジア）'==$value) {$inprogOpArray[] = "Proword";}

if('ワークトラベル'==$value) {$inprogOpArray[] = "Work Travel";}

if('J1ビザ'==$value) {$inprogOpArray[] = "J1";}

if('ホストファミリー（受け入れ）'==$value) {$inprogOpArray[] = "Host Family";}
//$inprogOp=$value;
}//foreach

//イントラックス （J1ビザ）
//

/*
20190513 メインの方へ入れる様に変更
*/

$inprogOp = implode(',',$inprogOpArray);

if($inprogOp){	
	$programDoc= $inprogOp;

	}

/*整形ここまで*/
$data['hope'] = (isset($data['hope']))? '希望する':'希望しない';

$data['kobetsu_hope'] = (isset($data['kobetsu_hope']))? '希望する':'希望しない';

if( $data['hope']=='希望する'){
//$content = $data['content'].' [郵送希望]'.$data['hope'].'/[資料希望プログラム]'.$programDoc;
$content .= ' [郵送希望]'.$data['hope'].'/[資料希望プログラム]'.$programDoc;
}


if($data['kobetsu_hope']=='希望する'){
$content.=' [相談方法・ご予約日]'.$data['consultation'].'/'.$data['consultation_year'].'年 '.$data['consultation_month'].'月 '.$data['consultation_day'].'日 '.$data['consultation_hour'].'時 '.$data['consultation_minute'].'分';
}

//$content.=' [郵送希望]'.$data['hope']; // content
	
	
/* 本番用 */

	//$data['hope'] = (isset($data['hope']))? '希望する':'希望しない';
	$array = set_data_to_salesforce2019($data);
	$array['retURL'] = home_url().'/common_form/complete/';
	$array['00N300000068mQw'] = 'Contact'; // WebFormType
	$array['formflg'] = 'Contact';
	//$array['00N30000007ClGQ'] = $data['content'].' [郵送希望]'.$data['hope']; // content
	
	$array['00N30000007ClGQ'] =$content.'\n\n'.$data['content'];
	
	//$array['00N30000008QK6r'] = ''; // CounselingRequest1
	$data['consultation_year'].'年 '.$data['consultation_month'].'月 '.$data['consultation_day'].'日 '.$data['consultation_hour'].'時 '.$data['consultation_minute'].'分';
	$array['00N30000008QK7B'] = ''; // CounselingRequestType1
	
	
	
	
	$array['00N30000008QK6m'] = $setsumeikai; // infoSessionDate
	$array['00N30000008QK6c'] = ''; // infoSessionLocation
/* */

/* Demoテスト用 */
/********
	$array = set_data_to_salesforce($data);
	$array['retURL'] = home_url().'/contact/complete/';
	$array['formflg'] = 'Contact';
********/


	send_post2019($array);


}

/*
本番のとき下記を有効
*/

add_action('mwform_after_send_mw-wp-form-4604','send_contact_data_to_salesforce2019');


//-----------sakai edit end-------------


//function testfunc ($data) {
//
//print_r($data);
//
//}
//add_action('mwform_after_send_mw-wp-form-4604','testfunc');


//お問い合わせのカスタムバリデーション
/*
20190515 資料請求しない場合質問を必須
*/
function contact_validation2($Validation, $data, $Data){
		//資料郵送希望なら住所必須
	if($data['hope']['data'][0] != '希望する'){
	//if(isset($data['hope']) && $data['hope']['data'][0] == '希望する'){
		$message = 'お問合せ内容をご入力ください';
		if(empty($data['content'])){
			$Validation->set_rule('content','noEmpty',array('message' => $message));
		
		}
	}
  return $Validation;
}
//add_filter('mwform_validation_mw-wp-form-4604', 'contact_validation2', 10, 3 );







/*
説明会等をフォームへ統合 20190521
*/

//AyusaサイトTOPから日程を取得
require_once(get_template_directory().'/lib/phpQuery-onefile.php');
function get_ayusa_schedule(){
$url = 'https://www.intraxjp.com/ayusa/';
$html = @file_get_contents($url);

// ファイルの取得に失敗した場合や空の場合は空の配列を返す
if ($html === false || empty($html)) {
    return array();
}

$dom = phpQuery::newDocument($html);

$table_data = $dom->find('table.table01');

$articleDate = array(); // 配列を初期化

foreach( $dom["table.table01 tr"] as $value)
            { 
			
			//echo $value;
			
             // get table rows    
             $articleDate[]  .= pq($value)->find('th:eq(0)')->text() . ' '.pq($value)->find('td:eq(0)')->text();  // maybe first:a or something - don't know

            }

	 return $articleDate;
	}

// Ayusaの日程セレクト生成
function add_ayusa_seminar_list( $children, $atts ) {

    if ( 'ayusa_seminar_date' == $atts['name'] ) {
$sche = get_ayusa_schedule();
//$children['']= '';

// スケジュールが空でない場合のみ処理
if (!empty($sche) && is_array($sche)) {
    foreach($sche as $val){
        $children[$val] = $val;
    }
}

  array_unshift($children,'');
    }

    return $children;
}
add_filter( 'mwform_choices_mw-wp-form-4604', 'add_ayusa_seminar_list', 1, 2 ); 
//Ayusa日程生成ここまで

function get_hostfamily_schedule(){
$url = 'https://www.intraxjp.com/ayusa/hostfamily/';
$html = @file_get_contents($url);

// ファイルの取得に失敗した場合や空の場合は空の配列を返す
if ($html === false || empty($html)) {
    return array();
}

$dom = phpQuery::newDocument($html);

$table_data = $dom->find('table.table01');

$articleDate = array(); // 配列を初期化

foreach( $dom["table.table01 tr"] as $value)
            { 
             // get table rows    
             $articleDate[]  .= pq($value)->find('th:eq(0)')->text() . ' '.pq($value)->find('td:eq(0)')->text();  // maybe first:a or something - don't know
            }
	 return $articleDate;
	}


// Ayusaの日程セレクト生成
function add_hostfamily_seminar_list( $children, $atts ) {
    if ( 'hostfamily_seminar_date' == $atts['name'] ) {
$sche = get_hostfamily_schedule();

//$children['']= '';

// スケジュールが空でない場合のみ処理
if (!empty($sche) && is_array($sche)) {
    foreach($sche as $val){
        $children[$val] = $val;
    }
}

  array_unshift($children,'');

    }

    return $children;
}
add_filter( 'mwform_choices_mw-wp-form-4604', 'add_hostfamily_seminar_list', 1, 2 ); 
//hostfamily日程生成ここまで




function get_springcamp2025_schedule(){
$url = 'https://www.intraxjp.com/springcamp2025/';
$html = @file_get_contents($url);

// ファイルの取得に失敗した場合や空の場合は空の配列を返す
if ($html === false || empty($html)) {
    return array();
}

$dom = phpQuery::newDocument($html);

$table_data = $dom->find('table.table01');

$articleDate = array(); // 配列を初期化

foreach( $dom["table.table01 tr"] as $value)
            { 
             // get table rows    
             $articleDate[]  .= pq($value)->find('th:eq(0)')->text() . ' '.pq($value)->find('td:eq(0)')->text();  // maybe first:a or something - don't know
            }
	 return $articleDate;
	}


// Ayusaの日程セレクト生成
function add_springcamp2025_seminar_list( $children, $atts ) {
    if ( 'springcamp2025_seminar_date' == $atts['name'] ) {
$sche = get_springcamp2025_schedule();

//$children['']= '';

// スケジュールが空でない場合のみ処理
if (!empty($sche) && is_array($sche)) {
    foreach($sche as $val){
        $children[$val] = $val;
    }
}

  array_unshift($children,'');

    }

    return $children;
}
add_filter( 'mwform_choices_mw-wp-form-4604', 'add_springcamp2025_seminar_list', 1, 2 ); 
//hostfamily日程生成ここまで



//オペアReactの画面から日程取得
function get_aupairReact_schedule(){
//$url = 'https://aupair.intraxjp.com/';
// APIのURL
$url = 'https://api-aupaircare.intraxjp.com/api/v1/calendars/current';

// cURLを使用してAPIからデータを取得
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

// JSONデータをデコード
$data = json_decode($response, true);

foreach($data['records'] as $value){
	
	$dateTime = new DateTime($value['date']);
	setlocale(LC_TIME, 'ja_JP.UTF-8'); // 日本語のロケールを設定
	
	// 曜日を日本語で表示するための配列
//$weekdays = ['日', '月', '火', '水', '木', '金', '土'];
// 曜日を取得し、配列から対応する日本語を割り当てる
$weekdayNum = $dateTime->format('w'); // 0:日曜日, 1:月曜日, ...
$japaneseWeekday = $weekdays[$weekdayNum];

//日時
$formattedDate = strftime("%m月%d日(%a)", $dateTime->getTimestamp());
//echo $formattedDate;

// 正規表現パターン: 【と】で囲まれた任意の文字列
$pattern = "/(【.*?】)/";

// preg_match_all関数で全てのマッチする部分を抽出
preg_match_all($pattern, $value['content'], $matches);
$desc = $matches[1][0];
//echo $desc;
$articleDate[]  .=$desc.' '.$formattedDate.' '.$value['timeFrom'].'～'.$value['timeTo'];

	}
//print_r($data);
return $articleDate;

	}


//オペアReactの画面から日程取得
function get_worktravelReact_schedule(){
//$url = 'https://aupair.intraxjp.com/';
// APIのURL
$url = 'https://api-worktravel.intraxjp.com/api/v1/briefings/public';

// cURLを使用してAPIからデータを取得
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

// JSONデータをデコード
$data = json_decode($response, true);

foreach($data['records'] as $value){
	
	$dateTime = new DateTime($value['date']);
	setlocale(LC_TIME, 'ja_JP.UTF-8'); // 日本語のロケールを設定
	
	// 曜日を日本語で表示するための配列
//$weekdays = ['日', '月', '火', '水', '木', '金', '土'];
// 曜日を取得し、配列から対応する日本語を割り当てる
$weekdayNum = $dateTime->format('w'); // 0:日曜日, 1:月曜日, ...
$japaneseWeekday = $weekdays[$weekdayNum];

//日時
$formattedDate = strftime("%m月%d日(%a)", $dateTime->getTimestamp());
//echo $formattedDate;

// 正規表現パターン: 【と】で囲まれた任意の文字列
$pattern = "/(【.*?】)/";

// preg_match_all関数で全てのマッチする部分を抽出
preg_match_all($pattern, $value['content'], $matches);
$desc = $matches[1][0];
//echo $desc;
$articleDate[]  .=$desc.' '.$formattedDate.' '.$value['timeFrom'].'～'.$value['timeTo'];

	}
/*	if(is_user_logged_in()){
print_r($data);
	}*/
return $articleDate;

	}












/*
if(is_user_logged_in()){
	echo "here<br>";
print_r(get_worktravelReact_schedule());
}

*/









//オペアの日程セレクト生成
/*
function add_aupair_seminar_list($children, $atts){
//オペアの日程　blog_id=2
	switch_to_blog( 2 );
	//$children['']= '';
    if ( $atts['name'] == 'aupair_seminar_date' ) {
    $query = new WP_Query(array('page_id' => 4338));
    if($query->have_posts()) : $query->the_post();
      if(have_rows('briefing')):while(have_rows('briefing')):the_row(); 
        $select = get_sub_field('city') . " " . get_sub_field('time');
        $children[$select] = $select;

      endwhile;endif;
    endif;
	wp_reset_postdata();
    array_unshift($children,'');
	}
	restore_current_blog();
	 return $children;	
	}*/


function add_aupair_seminar_list($children, $atts){
//オペアの日程　blog_id=2
$sche = get_aupairReact_schedule();
    if ( $atts['name'] == 'aupair_seminar_date' ) {
foreach($sche as $val){
	$children[$val] = $val;
	}

  array_unshift($children,'');
    
	}
	
	 return $children;	
	}

add_filter( 'mwform_choices_mw-wp-form-4604', 'add_aupair_seminar_list', 1, 2 ); 





//ワークトラベルの日程セレクト生成
function add_worktravel_seminar_list($children, $atts){	
//ワークトラベルの日程　blog_id=3	
/*	
WordPressから取得するパターン
switch_to_blog( 3 );
  if ( $atts['name'] == 'worktravel_seminar_date' ) {
    if( have_rows('dates','option') ) :
      while( have_rows('dates','option') ) : the_row();
        $select = get_sub_field('date','option'); 
        $children[$select] = $select;
      endwhile;
    endif;
 array_unshift($children,'');
  }
	
	restore_current_blog();
	 return $children;	
	Wordpressから取得するパターン終わり
	*/
//ReactAPIから取得する
$sche = get_worktravelReact_schedule();
    if ( $atts['name'] == 'worktravel_seminar_date' ) {
foreach($sche as $val){
	$children[$val] = $val;
	}

  array_unshift($children,'');
    
	}
	
	 return $children;		
	
	}
add_filter( 'mwform_choices_mw-wp-form-4604', 'add_worktravel_seminar_list', 1, 2 ); 



//ワークトラベルの日程セレクト生成

function add_intern_us_seminar_list($children, $atts){
		//インターンシップ（米）の日程　blog_id=4
	switch_to_blog( 4 );
	//$children['']= '';
   if ( $atts['name'] == 'intern_us_seminar_date' ) {
    if( have_rows('webdates2','option') ) :
      while( have_rows('webdates2','option') ) : the_row();
        $select = get_sub_field('webdate2','option'); 
        $children[$select] = $select;
      endwhile;
    endif;
  array_unshift($children,'');
  }
	restore_current_blog(); 
	 return $children;	
	}
add_filter( 'mwform_choices_mw-wp-form-4604', 'add_intern_us_seminar_list', 1, 2 ); 



//タイvolunteerの日程セレクト（WP外部の静的ページ

function add_thai_volunteer_seminar_list($children, $atts){
	//イントラックスの管理画面で設定しているので
	switch_to_blog( 1 );
	//$children['']= '';
   if ( $atts['name'] == 'thai_volunteer_seminar_date' ) {
    if( have_rows('thai_volunteer','option') ) :
      while( have_rows('thai_volunteer','option') ) : the_row();
        $select = get_sub_field('thai_volunteer','option'); 
        $children[$select] = $select;
      endwhile;
    endif;
  array_unshift($children,'');
  }
	restore_current_blog(); 
	 return $children;	
	}
add_filter( 'mwform_choices_mw-wp-form-4604', 'add_thai_volunteer_seminar_list', 1, 2 ); 



//2024サマーキャンプの日程セレクト（WP外部の静的ページ

function add_ayusa_summer04_seminar_list($children, $atts){
	//イントラックスの管理画面で設定しているので
	switch_to_blog( 1 );
	//$children['']= '';
   if ( $atts['name'] == 'summer_camp_seminar_date' ) {
    if( have_rows('ayusa_summer04_dates','option') ) :
      while( have_rows('ayusa_summer04_dates','option') ) : the_row();
        $select = get_sub_field('ayusa_summer04_dates','option'); 
        $children[$select] = $select;
      endwhile;
    endif;
  array_unshift($children,'');
  }
	restore_current_blog(); 
	 return $children;	
	}
add_filter( 'mwform_choices_mw-wp-form-4604', 'add_ayusa_summer04_seminar_list', 1, 2 ); 








//フォーム 予約日時の選択肢
function select_reserve( $children, $atts) {
  if ( $atts['name'] == 'consultation_year' ) {
	  
	 // 現在の日付を取得する
$today = date('Y-m-d');


//条件開始
$from_date = '2023-12-28';
// 条件の日付を指定
$target_date = '2023-12-31';

// 比較する
//※12月28日～元旦直前までは、来年の年号のみ出力

if ($today >= $from_date && $today < $target_date) {
  // 今日が条件の日付より前
  		for($i = date("Y")+1;$i <= date("Y") + 1;$i++){
			$children[$i] = $i;
		}
} else {
  // 今日が条件の日付より後
  		for($i = date("Y");$i <= date("Y") + 1;$i++){
			$children[$i] = $i;
		}
}
	  

  }
  if ( $atts['name'] == 'consultation_month' ) {
		for($i = 1;$i <= 12;$i++){
			$children[$i] = $i;
		}
  }
  if ( $atts['name'] == 'consultation_day' ) {
		for($i = 1;$i <= 31;$i++){
			$children[$i] = $i;
		}
  }
  if ( $atts['name'] == 'consultation_hour' ) {
		for($i = 10;$i <= 18;$i++){
			$children[$i] = $i;
		}
  }
  if ( $atts['name'] == 'consultation_minute' ) {
		$children['00'] = '00';
		$children['30'] = '30';
  }
  return $children;
}
add_filter( 'mwform_choices_mw-wp-form-4604', 'select_reserve', 10, 2 );




//お問い合わせのカスタムバリデーション
function contact_validation3($Validation, $data, $Data){

	//資料郵送希望なら住所必須
	if(isset($data['hope']['data']) && $data['hope']['data'][0] == '希望する'){
	//if(isset($data['hope']) && $data['hope']['data'][0] == '希望する'){
		$message = 'ご希望の資料のプログラムを選択してください（複数可）';
		if(empty($data['doc_type']['data'])){
			$Validation->set_rule('doc_type','required',array('message' => $message));
		}
	}
  return $Validation;
}

add_filter('mwform_validation_mw-wp-form-4604', 'contact_validation3', 10, 3 );



/* MW WP From 説明会参加時のバリデーション */

function mwform_validation_rule_setsumei( $validation_rules ) {
	if ( ! class_exists("MW_Validation_Rule_Test") ) {
		class MW_Validation_Rule_Test extends MW_WP_Form_Abstract_Validation_Rule {
			/**
			 * バリデーションルール名を指定
			 *
			 * @var string
			 */
			protected $name = 'setsumei';
			/**
			 * バリデーションチェック
			 *
			 * @param string $key name属性
			 * @param array  $option
			 *
			 * @return string エラーメッセージ
			 */
			//public function rule( $key, array $options = array() ) {
//				// 追加したいバリデーションの処理
//			
//			
//				if($data['setsumei_hope']['data'][0] != '希望する'){
//	
//		$message = 'ご希望の説明会日程を最低1つ選択してください。';
//	
//        $check=false;
//	
//		if(empty($data['content'])){
//			$Validation->set_rule('content','noEmpty',array('message' => $message));
//		
//		}
//	}
//			
//			
//			
//			
//			
//			
//			}
			
			
			
			
			
			
			
			
			
public function rule( $key, array $options = array() ) {
    
	$value = $this->Data->get( $key );
	
    if ( ! MWF_Functions::is_empty( $value ) ) {
	$check=false;	
	
	
	if(
	!$this->Data->get( 'ayusa_seminar_date' ) && !$this->Data->get( 'aupair_seminar_date' ) 
	&& !$this->Data->get( 'worktravel_seminar_date' ) 
	&& !$this->Data->get( 'intern_us_seminar_date' ) 
	&& !$this->Data->get( 'hostfamily_seminar_date' ) 
	&& !$this->Data->get( 'J1_briefing_dates' )
	&& !$this->Data->get( 'teacher_briefing_dates' )
	&& !$this->Data->get( 'intern_briefing_dates' )
	&& !$this->Data->get( 'thai_volunteer_seminar_date' )
	&& !$this->Data->get( 'summer_camp_seminar_date' )
	&& !$this->Data->get( 'springcamp2025_seminar_date' )
	
	) {
	$check=true;	
	}		
        if ( $check ) {
            $defaults = array(
                'message' => __( 'ご希望の説明会日程を最低1つ選択してください。', 'mw-wp-form' ),
            );
            $options  = array_merge( $defaults, $options );
            return $options['message'];
        }
    }
}		
			
			/**
			 * 設定パネルに追加
			 *
			 * @param int   $key   バリデーションルールセットの識別番号
			 * @param array $value バリデーションルールセットの内容
			 */
			public function admin( $key, $value ) {
				?>
				<label>
					<input type="checkbox" <?php checked( $value[ $this->getName() ], 1 ); ?> name="<?php echo MWF_Config::NAME; ?>[validation][<?php echo $key; ?>][<?php echo esc_attr( $this->getName() ); ?>]" value="1" />
					<?php esc_html_e( '説明バリデ', 'mw-wp-form' ); ?>
				</label>
				<?php
			}
		}
	}
	$instance = new MW_Validation_Rule_Test();
	$validation_rules[$instance->getName()] = $instance;
	//$validation_rules[MW_WP_Form_Abstract_Validation_Rule::getName()] = $instance;
	return $validation_rules;
}
add_filter( 'mwform_validation_rules', 'mwform_validation_rule_setsumei' );


//Sales force用データ生成
function set_data_to_salesforce_j1 ($data) {
//		'oid' => '00D7F000005VFZz',//sakai test ID
	//00N30000007BGW0 IntraxProgramOptions　作成
	$inprogOp = "Internship - J1";
	//reference
	$refce = "";
	switch ($data['reference']) {
	    case "ホームページ":
		$refce = "Internet - Home Page"; break;
	    case "Facebook":
		$refce = "Internet - Facebook"; break;
	    case "Youtube":
		$refce = "Internet - Youtube"; break;
	    case "Twitter":
		$refce = "Internet - Twitter"; break;
	    case "その他":
		$refce = "Other - Other"; break;
	}	

/* 本番用 */
	$array = array(
		'oid' => '00D30000000p3II',
		'00N300000068fwe' => $data['birth_month'].'/'.$data['birth_day'].'/'.$data['birth_year'], //birthOfDate
		'Primary Activity' => '',
		'00N30000008QnVM' => $data['affiliation'], // educationLevel
		'00N300000068JwE' => 'Participant', // unknown
		'00N300000068Glm' => 'Internship', // IntraxPrograms(ex.Internship->Internship)
		'00N3000000692oE' => 'Japan', // IntraxRegion
		'lead_source' => 'Web Form',
		'00N300000069Noq' => 'Web Form', // LeadSourceTag
		'00N3000000692rD' => 'Japanese', // WebFormLanguage
		'00N30000007BGW0' => $inprogOp, // IntraxProgramOptions
		'first_name' => $data['nam2'],
		'last_name' => $data['nam1'],
		'first_name_local' => $data['nam2'],
		'last_name_local' => $data['nam1'],
		'00N30000008QJrm' => $data['furi2'],
		'00N30000008QJrw' => $data['furi1'],
		'year' => '',
		'month' => '',
		'day' => '',
		'email' => $data['email'],
		'zip' => $data['zipcode'],
		'street' => $data['pref'].' '.$data['address1'].' '.$data['address2'],
		'phone' => $data['tel'],
		'mobile' => '',
		'yusoflg' => $hope,
		'00N300000068Glc' => $data['gender'], // gender
		'reference' => $refce,
		'00N400000021x8T' => '', // unknown
	  '00N300000068ZCr' => '', // How Heard
		'submit1' => 'submit',
	);

	return $array;
}

// menu_title
add_theme_support( 'title-tag' );



// Web説明会用設定ページ
if( function_exists('acf_add_options_page') ) {
  $option_page = acf_add_options_page(
  array(
    'page_title'  => 'J1ビザ説明会日程',
    'menu_title'  => 'J1ビザ説明会日程',
    'menu_slug'   => 'J1_briefing_dates',
    'capability'  => 'edit_posts',
    'redirect'  => false
  ));

}


if( function_exists('acf_add_options_page') ) {
  $option_page = acf_add_options_page(
array(
    'page_title'  => '教師派遣説明会日程',
    'menu_title'  => '教師派遣説明会日程',
    'menu_slug'   => 'teacher_briefing_dates',
    'capability'  => 'edit_posts',
    'redirect'  => false
  ));
}


if( function_exists('acf_add_options_page') ) {
  $option_page = acf_add_options_page(
array(
    'page_title'  => 'インターン説明会日程',
    'menu_title'  => 'インターン説明会日程',
    'menu_slug'   => 'intern_briefing_dates',
    'capability'  => 'edit_posts',
    'redirect'  => false
  ));
}



//Web説明会予約フォーム 日程の選択肢
function j1_select_briefing_dates( $children, $atts ) {
  if ( $atts['name'] == 'J1_briefing_dates' ) {
    if( have_rows('j1_dates','option') ) :
      while( have_rows('j1_dates','option') ) : the_row();
        $select = get_sub_field('j1_date','option'); 
        $children[$select] = $select;
      endwhile;
    endif;
	array_unshift($children,'');
  }
  //array_unshift($children,'');
  return $children;
}
add_filter( 'mwform_choices_mw-wp-form-4604', 'j1_select_briefing_dates', 10, 2 );

//Web説明会予約フォーム 日程の選択肢
function teacher_select_briefing_dates( $children, $atts ) {
  if ( $atts['name'] == 'teacher_briefing_dates' ) {
    if( have_rows('teacher_dates','option') ) :
      while( have_rows('teacher_dates','option') ) : the_row();
        $select = get_sub_field('teacher_date','option'); 
        $children[$select] = $select;
      endwhile;
    endif;
	array_unshift($children,'');
  }
  //array_unshift($children,'');
  return $children;
}
add_filter( 'mwform_choices_mw-wp-form-4604', 'teacher_select_briefing_dates', 10, 2 );


//Web説明会予約フォーム 日程の選択肢
function intern_select_briefing_dates( $children, $atts ) {
  if ( $atts['name'] == 'intern_briefing_dates' ) {
    if( have_rows('intern_dates','option') ) :
      while( have_rows('intern_dates','option') ) : the_row();
        $select = get_sub_field('intern_date','option'); 
        $children[$select] = $select;
      endwhile;
    endif;
	array_unshift($children,'');
  }
  //array_unshift($children,'');
  return $children;
}
add_filter( 'mwform_choices_mw-wp-form-4604', 'intern_select_briefing_dates', 10, 2 );





//----------夏キャンプをAPIで公開
add_action('rest_api_init', function () {
  register_rest_route('my-api/v1', '/ayusa-summer04_dates', array(
    'methods' => 'GET',
    'callback' => 'get_acf_data_ayusa_summer04_dates',
  ));
});

function get_acf_data_ayusa_summer04_dates() {
  //$post_id = get_the_ID(); // 投稿 ID を取得
  //$acf_data = get_fields($post_id); // ACF の値を取得
  switch_to_blog( 1 );
    if( have_rows('ayusa_summer04_dates','option') ) :
      while( have_rows('ayusa_summer04_dates','option') ) : the_row();
        $select = get_sub_field('ayusa_summer04_dates','option'); 
        $children[] = $select;
      endwhile;
    endif;
	restore_current_blog(); 

   if (empty($children)) {
    return new WP_Error('no_acf_data', 'ACF data not found', ['status' => 404]);
  }
  
  // 配列になっている ACF フィールドの値を API レスポンスに含める
  $response = $children;
  foreach ($children as $key => $value) {
    if (is_array($value)) {
      $response[$key] = $value;
    }
  }

  return $response;
}
//----------韓国文化体験プログラム日程をAPIで公開
add_action('rest_api_init', function () {
  register_rest_route('my-api/v1', '/ayusa_korea_dates', array(
    'methods' => 'GET',
    'callback' => 'get_acf_data_ayusa_korea_dates',
  ));
});

function get_acf_data_ayusa_korea_dates() {
  //$post_id = get_the_ID(); // 投稿 ID を取得
  //$acf_data = get_fields($post_id); // ACF の値を取得
  switch_to_blog( 1 );
    if( have_rows('korea_camp','option') ) :
      while( have_rows('korea_camp','option') ) : the_row();
        $select = get_sub_field('korea_camp','option'); 
        $children[] = $select;
      endwhile;
    endif;
	restore_current_blog(); 

   if (empty($children)) {
    return new WP_Error('no_acf_data', 'ACF data not found', ['status' => 404]);
  }
  
  // 配列になっている ACF フィールドの値を API レスポンスに含める
  $response = $children;
  foreach ($children as $key => $value) {
    if (is_array($value)) {
      $response[$key] = $value;
    }
  }

  return $response;
}



//----------ボランティア（タイ）プログラム日程をAPIで公開
add_action('rest_api_init', function () {
  register_rest_route('my-api/v1', '/thai_volunteer_dates', array(
    'methods' => 'GET',
    'callback' => 'get_acf_data_thai_volunteer_dates',
  ));
});

function get_acf_data_thai_volunteer_dates() {
  //$post_id = get_the_ID(); // 投稿 ID を取得
  //$acf_data = get_fields($post_id); // ACF の値を取得
  switch_to_blog( 1 );
    if( have_rows('thai_volunteer','option') ) :
      while( have_rows('thai_volunteer','option') ) : the_row();
        $select = get_sub_field('thai_volunteer','option'); 
        $children[] = $select;
      endwhile;
    endif;
	restore_current_blog(); 

   if (empty($children)) {
    return new WP_Error('no_acf_data', 'ACF data not found', ['status' => 404]);
  }
  
  // 配列になっている ACF フィールドの値を API レスポンスに含める
  $response = $children;
  foreach ($children as $key => $value) {
    if (is_array($value)) {
      $response[$key] = $value;
    }
  }

  return $response;
}




//----------J1ビザ手配　プログラム日程をAPIで公開
add_action('rest_api_init', function () {
  register_rest_route('my-api/v1', '/j1_dates', array(
    'methods' => 'GET',
    'callback' => 'get_acf_data_j1_dates',
  ));
});

function get_acf_data_j1_dates() {
  //$post_id = get_the_ID(); // 投稿 ID を取得
  //$acf_data = get_fields($post_id); // ACF の値を取得
  switch_to_blog( 1 );
    if( have_rows('j1_dates','option') ) :
      while( have_rows('j1_dates','option') ) : the_row();
        $select = get_sub_field('j1_date','option'); 
        $children[] = $select;
      endwhile;
    endif;
	restore_current_blog(); 

   if (empty($children)) {
    return new WP_Error('no_acf_data', 'ACF data not found', ['status' => 404]);
  }
  
  // 配列になっている ACF フィールドの値を API レスポンスに含める
  $response = $children;
  foreach ($children as $key => $value) {
    if (is_array($value)) {
      $response[$key] = $value;
    }
  }

  return $response;
}
//教師派遣の説明会日程をAPI公開
//teacher_briefing_dates
add_action('rest_api_init', function () {
  register_rest_route('my-api/v1', '/teacher_briefing_dates', array(
    'methods' => 'GET',
    'callback' => 'get_acf_teacher_briefing_dates',
  ));
});

function get_acf_teacher_briefing_dates() {
  //$post_id = get_the_ID(); // 投稿 ID を取得
  //$acf_data = get_fields($post_id); // ACF の値を取得
  switch_to_blog( 1 );
    if( have_rows('teacher_dates','option') ) :
      while( have_rows('teacher_dates','option') ) : the_row();
        $select = get_sub_field('teacher_date','option'); 
        $children[] = $select;
      endwhile;
    endif;
	restore_current_blog(); 

   if (empty($children)) {
    return new WP_Error('no_acf_data', 'ACF data not found', ['status' => 404]);
  }
  
  // 配列になっている ACF フィールドの値を API レスポンスに含める
  $response = $children;
  foreach ($children as $key => $value) {
    if (is_array($value)) {
      $response[$key] = $value;
    }
  }

  return $response;
}
//スクールインターン日程をAPI公開
//intern_briefing_dates
add_action('rest_api_init', function () {
  register_rest_route('my-api/v1', '/intern_briefing_dates', array(
    'methods' => 'GET',
    'callback' => 'get_acf_intern_briefing_dates',
  ));
});

function get_acf_intern_briefing_dates() {
  //$post_id = get_the_ID(); // 投稿 ID を取得
  //$acf_data = get_fields($post_id); // ACF の値を取得
  switch_to_blog( 1 );
    if( have_rows('intern_dates','option') ) :
      while( have_rows('intern_dates','option') ) : the_row();
        $select = get_sub_field('intern_date','option'); 
        $children[] = $select;
      endwhile;
    endif;
	restore_current_blog(); 

   if (empty($children)) {
    return new WP_Error('no_acf_data', 'ACF data not found', ['status' => 404]);
  }
  
  // 配列になっている ACF フィールドの値を API レスポンスに含める
  $response = $children;
  foreach ($children as $key => $value) {
    if (is_array($value)) {
      $response[$key] = $value;
    }
  }

  return $response;
}