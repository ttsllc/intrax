/*global $ */
$(document).ready(function () {

    "use strict";

    $('.menu > ul > li > ul:not(:has(ul))').addClass('normal-sub');


	$(".menu #header_btn").click(function(a) {
		  a.stopPropagation();
		  $(".gnavi").slideToggle(350);
	});

});
