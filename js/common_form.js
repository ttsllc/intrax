// JavaScript Document

$(function () {
  prog_check();
  setsumei_hope_check();
  kobetsu_hope_check();
  hope_check();
  //kari_check();//オペア仮登録の制御

  prog_check_confirm();
 

  //プログラムが変更された時
  $('[name="prog[data][]"]:checkbox').change(function () {
    prog_check();
	kari_check();
  });

  //説明会希望のチェックが変更された時
  $('[name="setsumei_hope[data][]"]:checkbox').change(function () {
    setsumei_hope_check();
  });

  //説明会希望のチェックが変更された時
  $('[name="kobetsu_hope[data][]"]:checkbox').change(function () {
    kobetsu_hope_check();
  });

  //資料郵送希望のチェックが変更された時
  $('[name="hope[data][]"]:checkbox').change(function () {
    hope_check();
  });

  //確認画面から送信したときの処理
  $("form").submit(function () {
    document.getElementById("Loading").style.display = "block";
    document.getElementById("Loading").style.top = 150;
    document.getElementById("Loading").style.left =
      (document.body.clientWidth - 300) / 2;
  });
  /*実行ボタン押下時の関数*/
});

//選択されているプログラムによって制御する
function prog_check() {
  var setsumei_check = false;
  var kobetsu_check = false;
  var ayusa         = false;
  var aupair        = false;
  var worktravel    = false;
  var intern_us     = false;
  var hostfamily    = false;

  var volunteer     = false;
  var teacher       = false;
  var J1            = false;
  var school_intern =false;
  var ayusa_spring  =false;
  var ayusa_summer  =false;

  var setsumei_hope = false;
  var kobetsu_hope = false;
  var kari         =false;

  //説明会希望にチェック？
  $("input:checkbox[name='setsumei_hope[data][]']:checked").each(function () {
    if ($(this).val() == "希望する") {
      setsumei_hope = true;

      kobetsu_hope = false;
    }
  });

  //確認画面用
  if ($("input:hidden[name='setsumei_hope[data]']").val() == "希望する") {

    setsumei_hope = true;
    kobetsu_hope = false;
  }

  //個別相談にチェック？
  $("input:checkbox[name='kobetsu_hope[data][]']:checked").each(function () {
    if ($(this).val() == "希望する") {
      kobetsu_hope = true;

      setsumei_hope = false;
    }
  });

  //確認画面用
  if ($("input:hidden[name='kobetsu_hope[data]']").val() == "希望する") {
    //alert('説明希望ですわ');
    kobetsu_hope = true;

    setsumei_hope = false;
  }

  //すべてのチェック済みvalue値を取得する
  $("input:checkbox[name='prog[data][]']:checked").each(function () {
    if (
      //説明会あり、且つ、個別相談OKなプログラムはここに設定
	  $(this).val() == "アユサ高校交換留学" ||
      $(this).val() == "オペアケア" ||
      $(this).val() == "ワークトラベル" ||
      $(this).val() == "インターンシップ（米国）" ||
	  $(this).val() == "教師派遣" ||
	  $(this).val() == "スクールインターン" ||
      $(this).val() == "ボランティア（タイ）" ||
	  $(this).val() == "アユサ春キャンプ" ||
	  $(this).val() == "アユサ夏キャンプ" 
    ) {
      setsumei_check = true;
      kobetsu_check = true;
	   $("tr.hope").show('slow');
     // $("tr.hope").toggle();
	}else if($(this).val() == "ホストファミリー（受け入れ）" ||  $(this).val() =="J1ビザ手配"){
		setsumei_check = true;
	
	}else{
		 $("tr.hope").hide('slow');
		}


    if ($(this).val() == "アユサ高校交換留学") ayusa = true;
    if ($(this).val() == "オペアケア"){aupair = true;kari=true;}
	
    if ($(this).val() == "ワークトラベル") worktravel = true;
    if ($(this).val() == "インターンシップ（米国）") intern_us = true;
	if ($(this).val() == "スクールインターン") school_intern = true;
    if ($(this).val() == "ボランティア（タイ）") volunteer = true;
	if ($(this).val() == "アユサ春キャンプ") ayusa_spring = true;
	if ($(this).val() == "アユサ夏キャンプ") ayusa_summer = true;
    if ($(this).val() == "教師派遣") teacher = true;
	if ($(this).val() == "スクールインターン") school_intern = true;
	if ($(this).val() == "ホストファミリー（受け入れ）") hostfamily = true;
	if ($(this).val() == "J1ビザ手配") J1 = true;
  });

  //教師派遣orボラんてぃア（タイ）は、個別説明受付
  if(volunteer || teacher || ayusa_summer) {
    kobetsu_check = true;
    $("tr.hope").hide('slow');
  }



  if (setsumei_check) {
    if ($(".setsumei").css("display") == "none") {
      $(".setsumei").show("slow");
    }
  } else {
    $(".setsumei").hide("slow");

    //説明会希望する　のチェックも外しておく
    $('[name="setsumei_hope[data][]"]:checkbox').prop("checked", false);
  }

  if (kobetsu_check) {
    if ($(".kobetsu").css("display") == "none") {
      $(".kobetsu").show("slow");
    }
  } else {
    $(".kobetsu").hide("slow");

    //説明会希望する　のチェックも外しておく
    $('[name="kobetsu_hope[data][]"]:checkbox').prop("checked", false);

    $("tr.kobetsu_date").hide("slow");
  }

  $("input:checkbox[name='setsumei_hope[data][]']:checked").each(function () {
    if ($(this).val() == "希望する") {
      setsumei_hope = true;
    }
  });
//console.log("hostfamilyの値：" + hostfamily);
//console.log("setsumei_hopeの値：" + setsumei_hope);
/*
説明会参加希望のチェックON/OFF時の制御
*/
// console.log("J1は->" + J1);
  //各日程を制御
  if (ayusa && setsumei_hope) {
    $("tr.ayusa_seminar").show("slow");
  } else {
    $("tr.ayusa_seminar").hide("slow");
    $('[name="ayusa_seminar_date"] option:first').prop("selected", true);
  }
  if (aupair && setsumei_hope) {
    $("tr.aupair_seminar").show("slow");
  } else {
    $("tr.aupair_seminar").hide("slow");
    $('[name="aupair_seminar_date"] option:first').prop("selected", true);
  }
  if (worktravel && setsumei_hope) {
    $("tr.work_travel_seminar").show("slow");
  } else {
    $("tr.work_travel_seminar").hide("slow");
    $('[name="worktravel_seminar_date"] option:first').prop("selected", true);
  }
  if (intern_us && setsumei_hope) {
    $("tr.intern_us_seminar").show("slow");
  } else {
    $("tr.intern_us_seminar").hide("slow");
    $('[name="intern_us_seminar_date"] option:first').prop("selected", true);
  }
  
   if (hostfamily && setsumei_hope) {
    $("tr.hostfamily_seminar").show("slow");
	
  } else {
    $("tr.hostfamily_seminar").hide("slow");
    $('[name="hostfamily_seminar_date"] option:first').prop("selected", true);
  }
  
  
  if(J1 && setsumei_hope){
	  $("tr.j1_seminar").show("slow");
	  }else{
	$("tr.j1_seminar").hide("slow");
    $('[name="J1_briefing_dates"] option:first').prop("selected", true);
		  }
  
  
    if(teacher && setsumei_hope){
	  $("tr.teacher_seminar").show("slow");
	  }else{
	$("tr.teacher_seminar").hide("slow");
    $('[name="teacher_briefing_dates"] option:first').prop("selected", true);
		  }
  
     if(school_intern && setsumei_hope){
	  $("tr.intern_seminar").show("slow");
	  }else{
	$("tr.intern_seminar").hide("slow");
    $('[name="intern_briefing_dates"] option:first').prop("selected", true);
		  }
   
    if(volunteer && setsumei_hope){
	  $("tr.thai_volunteer").show("slow");
	  }else{
	$("tr.thai_volunteer").hide("slow");
    $('[name="thai_volunteer_seminar_date"] option:first').prop("selected", true);
		  }
    if(ayusa_summer && setsumei_hope){
	  $("tr.summer_camp_seminar_date").show("slow");
	  }else{
	$("tr.summer_camp_seminar_date").hide("slow");
    $('[name="summer_camp_seminar_date"] option:first').prop("selected", true);
		  }

    if(ayusa_spring && setsumei_hope){
	  $("tr.springcamp2025_seminar_date").show("slow");
	  }else{
	$("tr.springcamp2025_seminar_date").hide("slow");
    $('[name="springcamp2025_seminar_date"] option:first').prop("selected", true);
		  }




//if(kari){$('.kari').show('slow');}else{$('.kari').hide('slow');}


} //prog_check



//確認画面　どのプログラムが選択されているか？

function prog_check_confirm() {
  var prog;

  prog = $("input:hidden[name='prog[data]']").val();

  if (prog) {
    ary_prog = prog.split(",");
	    ary_prog.forEach(function (val, index, ary_prog) {
		if(val=="オペアケア") $('.kari').show();
	})
  }

  //console.log(a===ar); //trueになる



  // 説明会==希望する　なら日程を表示
  if ($("input:hidden[name='setsumei_hope[data]']").val() == "希望する") {
    $(".setsumei").show();

    ary_prog.forEach(function (val, index, ary_prog) {
    
      if (val == "アユサ高校交換留学") {
        $("tr.ayusa_seminar").show();
      }
      if (val == "オペアケア"){
		  $("tr.aupair_seminar").show();
		  
		  }
      if (val == "ワークトラベル") $("tr.work_travel_seminar").show();
      if (val == "インターンシップ（米国）") $("tr.intern_us_seminar").show();
	  if (val == "ホストファミリー（受け入れ）"){$(".hostfamily_seminar").show();}
	  if (val == "J1ビザ手配"){$("tr.j1_seminar").show();}
	  if (val == "教師派遣"){$("tr.teacher_seminar").show();}
	  if (val == "スクールインターン"){$("tr.intern_seminar").show();}
	  if (val == "ボランティア（タイ）"){$("tr.thai_volunteer").show();}
	   if (val == "アユサ春キャンプ"){$("tr.springcamp2025_seminar_date").show();}
	  if (val == "アユサ夏キャンプ"){$("tr.summer_camp_seminar_date").show();
	  }
    });
  }




  // 相談かい==希望する　なら表示
  if ($("input:hidden[name='kobetsu_hope[data]']").val() == "希望する") {
    $(".kobetsu").show();

    ary_prog.forEach(function (val, index, ary_prog) {
		if(val=="オペアケア") $('.kari').show();
      if (
        val == "アユサ高校交換留学" ||
        val == "オペアケア" ||
        val == "ワークトラベル" ||
        val == "インターンシップ（米国）"||
        val == "教師派遣"||
		val == "スクールインターン"||
        val == "ボランティア（タイ）"
      ) {
        $("tr.kobetsu_date").show();
      }
    });
  }


}

function setsumei_hope_check() {
	
	/*
	説明会参加希望 希望のON/OFF時の制御
	*/
	
	
  $("input:checkbox[name='setsumei_hope[data][]']:checked").each(function () {
    if ($(this).val() == "希望する") {
      $("input:checkbox[name='prog[data][]']:checked").each(function () {
        if ($(this).val() == "アユサ高校交換留学") {
          $("tr.ayusa_seminar").show("slow");
        }

        if ($(this).val() == "オペアケア") {
          $("tr.aupair_seminar").show("slow");
		
        }
        if ($(this).val() == "ワークトラベル") {
          $("tr.work_travel_seminar").show("slow");
        }

        if ($(this).val() == "インターンシップ（米国）") {
          $("tr.intern_us_seminar").show("slow");
        }
		  if ($(this).val() == "ホストファミリー（受け入れ）") {
          $("tr.hostfamily_seminar").show("slow");
        }
		
	if ($(this).val() == "J1ビザ手配") {
          $("tr.j1_seminar").show("slow");
        }
	if ($(this).val() == "教師派遣") {
          $("tr.teacher_seminar").show("slow");
        }
	if ($(this).val() == "スクールインターン") {
          $("tr.intern_seminar").show("slow");
        }
	if ($(this).val() == "ボランティア（タイ）") {
          $("tr.thai_volunteer").show("slow");
        }

	if ($(this).val() == "アユサ春キャンプ") {
          $("tr.springcamp2025_seminar_date").show("slow");
        }
		

	if ($(this).val() == "アユサ夏キャンプ") {
          $("tr.summer_camp_seminar_date").show("slow");
        }
		
		
      });

      //個別相談を消す
      if (
        $("input:checkbox[name='kobetsu_hope[data][]']").prop("checked") == true
      ) {
        //$('.kobetsu').hide('slow');

        //説明会希望する　のチェックも外しておく
        $('[name="kobetsu_hope[data][]"]:checkbox').prop("checked", false);
        kobetsu_hope_check();

        // $('tr.kobetsu_date').hide('slow');
      }
    }
  });

  if (
    $("input:checkbox[name='setsumei_hope[data][]']").prop("checked") == false
  ) {
    $('[name="ayusa_seminar_date"] option:first').prop("selected", true);
    $('[name="aupair_seminar_date"] option:first').prop("selected", true);
    $('[name="worktravel_seminar_date"] option:first').prop("selected", true);
    $('[name="intern_us_seminar_date"] option:first').prop("selected", true);
	$('[name="hostfamily_seminar_date"] option:first').prop("selected", true);
	$('[name="J1_briefing_dates"] option:first').prop("selected", true);
	$('[name="teacher_briefing_dates"] option:first').prop("selected", true);
	$('[name="intern_briefing_dates"] option:first').prop("selected", true);
	$('[name="thai_volunteer_seminar_date"] option:first').prop("selected", true);
	$('[name="springcamp2025_seminar_date"] option:first').prop("selected", true);
	$('[name="summer_camp_seminar_date"] option:first').prop("selected", true);
//↑option のnameで設定

    $("tr.ayusa_seminar").hide("slow");
    $("tr.aupair_seminar").hide("slow");
    $("tr.work_travel_seminar").hide("slow");
    $("tr.intern_us_seminar").hide("slow");
	$("tr.hostfamily_seminar").hide("slow");
	$("tr.j1_seminar").hide("slow");
	$("tr.teacher_seminar").hide("slow");
	$("tr.intern_seminar").hide("slow");
	$("tr.thai_volunteer").hide("slow");
	$("tr.springcamp2025_seminar_date").hide("slow");
	$("tr.summer_camp_seminar_date").hide("slow");
//↑tabel trのclassで設定
  }
}

function kobetsu_hope_check() {
  if ($("input:checkbox[name='kobetsu_hope[data][]']").prop("checked")) {
    $("tr.kobetsu_date").show("slow");

    //説明会側のチェックを外して、チェックfunction実行

    //チェック外す
    $('[name="setsumei_hope[data][]"]:checkbox').prop("checked", false);

    //各説明会の選択値を空白に
    $('[name="ayusa_seminar_date"] option:first').prop("selected", true);
    $('[name="aupair_seminar_date"] option:first').prop("selected", true);
    $('[name="worktravel_seminar_date"] option:first').prop("selected", true);
    $('[name="intern_us_seminar_date"] option:first').prop("selected", true);
	$('[name="j1_seminar_date"] option:first').prop("selected", true);
	$('[name="teacher_seminar_date"] option:first').prop("selected", true);
	$('[name="intern_seminar_date"] option:first').prop("selected", true)

    //setsumei_hope_check();
    $("tr.ayusa_seminar").hide("slow");
    $("tr.aupair_seminar").hide("slow");
    $("tr.work_travel_seminar").hide("slow");
    $("tr.intern_us_seminar").hide("slow");
	$("tr.j1_seminar").hide("slow");
	$("tr.teacher_seminar").hide("slow");
	$("tr.intern_seminar").hide("slow");
  } else {
    //$('[name="doc_t[data][]"]:checkbox').attr('checked', false);
    $("tr.kobetsu_date").hide("slow");
  }
}

function hope_check() {
  if ($("input:checkbox[name='hope[data][]']").prop("checked")) {
    $("tr.doc_type").show("slow");
    $("tr.hope").show("slow");
  } else {
    $('[name="doc_type[data][]"]:checkbox').prop("checked", false);
    $("tr.doc_type").hide("slow");
  }

  // $("input:hidden[name='prog[data]']").val();

  //if($("input:hidden[name='hope[data]']").val()=='希望する'){

  if ($(".mw_wp_form_confirm").length) {
    //alert('確認画面です');
    if ($("input:hidden[name='hope[data]']").val() == "希望する") {
      //$('tr.doc_type').show('slow');
      $("tr.hope").show();
      $("tr.doc_type").show();
    } else {
      $("tr.hope").hide("slow");
    }
  }
}
function kari_check() {
	
	var kari=false;

      $("input:checkbox[name='prog[data][]']:checked").each(function () {
    if ($(this).val() == "オペアケア"){kari=true;}
      		
      });

        if (kari) {
          $("tr.kari").show("slow");
		
        }else{
			$("input[name='aupair_kari']").prop('checked', false);
		$("tr.kari").hide("slow");
			}



}