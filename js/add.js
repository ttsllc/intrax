	$(function() {
		//クリックしたときのファンクションをまとめて指定
		$('.tabs li').click(function() {

			//.index()を使いクリックされたタブが何番目かを調べ、
			//indexという変数に代入します。
			var index = $('.tabs li').index(this);

			//コンテンツを一度すべて非表示にし、
			$('.content li').css('display','none');

			//クリックされたタブと同じ順番のコンテンツを表示します。
			$('.content li').eq(index).css('display','block');

			//一度タブについているクラスselectを消し、
			$('.tabs li').removeClass('select');

			//クリックされたタブのみにクラスselectをつけます。
			$(this).addClass('select');
		});
	});	
	
	$(function(){
		$('.thumb li').click(function(){var class_name=$(this).attr("class");var num=class_name.slice(5);$('.thumb_list li').hide();$('.item'+num).fadeIn();});
	});
	
	
	$(function(){
		$('a.img_change img').hover(function(){
			$(this).attr('src', $(this).attr('src').replace('_off', '_on'));
			}, function(){
			 if (!$(this).hasClass('currentPage')) {
			 $(this).attr('src', $(this).attr('src').replace('_on', '_off'));
			}
		});
	});
	
	$(function() {
		var topBtn = $('.totop');    
		topBtn.hide();
		//スクロールが100に達したらボタン表示
		$(window).scroll(function () {
			if ($(this).scrollTop() > 100) {
				topBtn.fadeIn();
			} else {
				topBtn.fadeOut();
			}
		});
		//スクロールしてトップ
		topBtn.click(function () {
			$('body,html').animate({
				scrollTop: 0
			}, 500);
			return false;
		});
	});
	
	$(function(){
	   // #で始まるアンカーをクリックした場合に処理
	   $('a[href^=#]').click(function() {
		  // スクロールの速度
		  var speed = 400; // ミリ秒
		  // アンカーの値取得
		  var href= $(this).attr("href");
		  // 移動先を取得
		  var target = $(href == "#" || href == "" ? 'html' : href);
		  // 移動先を数値で取得
		  var position = target.offset().top;
		  // スムーススクロール
		  $('body,html').animate({scrollTop:position}, speed, 'swing');
		  return false;
	   });
	});

	$(function() {
		$("#panel-btn").click(function() {
			$("#panel").slideToggle(200);
			$("#panel-btn").toggleClass("open");
			$("#panel-btn-icon").toggleClass("close");
			return false;
		});
	});
/*	
	$(function() {
		$('.slider').slick({
			  infinite: true,
			  dots:false,
			  arrow:true,
			  slidesToShow: 3,
			  slidesToScroll: 3,
			  responsive: [{
				   breakpoint: 768,
						settings: {
							 slidesToShow: 1,
							 slidesToScroll: 1,
				   }
			  }]
		 });
	});*/
	
	$(function() {

  var tabMenu = function() {

    /**
     * 変数の指定
     * $tabs              : tabの親要素のjQueryオブジェクト
     * $content           : tabによって切り替わる要素のjQueryオブジェクト
     * TAB_ACTIVE_CLASS   : tabが選択されたスタイルを変更するclass名
     * CONTENT_SHOW_CLASS : contentを表示させるためのclass名
     * id_arr             : $contentのIDを配列に格納
     */
    var $tabs              = $('.tab_power');
    var $content           = $('.tab-content');
    var TAB_ACTIVE_CLASS   = 'is_active';
    var CONTENT_SHOW_CLASS = 'is_show';
    var id_arr             = $content.map(function() { return '#' + $(this).attr('id');}).get();


    /**
     * 該当するhashデータがある場合、hashを返す
     * 該当とは id_arr[] に含まれるもの
     * @return {string} 該当する場合
     * @return {false} 該当しない（存在しない）場合
     */
    var getHash = function() {
      var hash = window.location.hash;
      var index = id_arr.indexOf(hash);

      if (index === -1) {
        return false;
      } else {
        return id_arr[index];
      }
    };


    /**
     * ページ読み込み時に実行
     * 1. hashがあれば、hashをhrefに持つタブのスタイル変更（専用のclass付与）
     * 2. hashがあれば、hashをidに持つコンテンツを表示（専用のclassを付与）
     * 3. hashがなければ、タブの先頭が選択された状態とする
     */
    var initialize = function() {
      var hash = getHash();

      if (hash) {
        $tabs.find('a[href="'+hash+'"]').addClass(TAB_ACTIVE_CLASS); // 1
        $(hash).addClass(CONTENT_SHOW_CLASS); // 2
      } else {
        $tabs.find('li:first > a').addClass(TAB_ACTIVE_CLASS); // 3
        $($content[0]).addClass(CONTENT_SHOW_CLASS); // 3
      }
    };


    /**
     * タブのクリックイベント
     * 1. クリックされたタブのhref, 該当するcontentを取得
     * 2. 既にクリック済みの状態であればスキップ
     * 3. 一旦タブ・contentの専用classを全削除
     * 4. クリックしたタブのスタイルを変更、該当するcontentを表示（それぞれ専用のclassを付与）
     */
    var addEvent = function() {
      $tabs.find('a').on('click', function() {
        var href = $(this).attr('href'); // 1
        var $targetContent = $(href); // 1

        // 2
        if ($(this).hasClass(TAB_ACTIVE_CLASS)) {
          return false;
        }

        // 3
        $tabs.find('a').removeClass(TAB_ACTIVE_CLASS);
        $content.removeClass(CONTENT_SHOW_CLASS);

        // 4
        $(this).addClass(TAB_ACTIVE_CLASS);
        $targetContent.addClass(CONTENT_SHOW_CLASS);

        return false;
      });
    };

    return [initialize(), addEvent()];
  };

  // 実行
  tabMenu();

});
