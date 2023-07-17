(function ($) {







	'use strict';















	// $('.cpm-Qtag').on('click', function() {







	$(document).on('click', '.cpm-Qtag', function (e) {







		e.preventDefault();







		// $(this).addClass("cpm-Qtag-hilight");







		// $(".cpm-Qtag").not(this).removeClass("cpm-Qtag-hilight");







		var buttonValue = $(this).val();







		var postID = $(this).data('pid');







		var termcolor = $(this).data('termcolor');







		// var termcol = hexToRgb(termcolor);







		// var highlight_style = " border: 2px solid " + termcolor + "; color: " + termcolor + "; "



		// var highlight_style = " border: 2px solid " + termcolor + "; color: " + termcolor + "; background: transparent !important; "



		var highlight_style = " border: 2px solid " + termcolor + "; color: " + termcolor + "; ";







		var active_class = "cpm-active-tag";







		$(this).attr("style", highlight_style);



		$(this).addClass(active_class);







		// $(this).attr("style", "background-color:" + termcolor + "; box-shadow:0 8px 16px 0 rgba(" + termcol.r + ", " + termcol.g + ", " + termcol.b + ", 0.6),0 6px 20px 0 rgba( " + termcol.r + ", " + termcol.g + ", " + termcol.b + ", 0.2)");







		$(".cpm-Qtag").not(this).removeAttr("style");



		$(".cpm-Qtag").not(this).removeClass(active_class);











		// Initiate AJAX call















		$.ajax({







			url: myAjax.ajax_url,







			type: 'POST',







			data: {







				action: 'ajax_save_tag_values',







				question_tag: buttonValue,







				question_id: postID







			},







			success: function (response) {







				// console.log(response);







			}







		});















	});















	function ajax_display_single_question(postID) {







		$.ajax({







			url: myAjax.ajax_url,







			type: 'POST',







			data: {







				action: 'ajax_display_single_question',







				question_id: postID







			},







			success: function (response) {















				$('#ajax-display-single-question').html(response);







			}







		});







	}















	$(document).on('click', '.med-radio-container', function (e) {







		var termcol = $("#med-questions-lists-id").data('termcol');







		$(".med-radio-container").not(this).removeClass("med-bg");



		$(this).addClass('med-bg');



		// med-checkmark-tag



		// console.log(termcol);



		var highlight_style = "background-color:" + termcol;



		$(this).children('.med-checkmark-tag').attr("style", highlight_style);



		$('.med-radio-container').not(this).children('.med-checkmark-tag').removeAttr("style");



		const postID = $(this).data('id');



		ajax_display_single_question(postID);

		if (window.innerWidth <= 915) {
			// The current view is a mobile view
			// Add your mobile-specific code here
			$('.mdSidebar').addClass('mdSidebar-collapse');

		}

	})















	$(document).on('click', '#tagged-single-question-next', function (e) {







		var termcol = $("#med-questions-lists-id").data('termcol');



		var highlight_style = "background-color:" + termcol;







		var $medLists = $('#med-questions-lists-id');







		var $currentElement = $medLists.find('.med-bg');







		var $nextElement = $currentElement.next();







		// if ($nextElement) {



		var nextDataId = $nextElement.data('id');











		if ($nextElement.length && nextDataId) {







			var postID = nextDataId;



			$medLists.find('.med-checkmark-tag').removeAttr('style');



			$medLists.find('.med-bg').removeClass('med-bg').find('input[type="radio"]').prop('checked', false);







			$nextElement.addClass('med-bg').find('input[type="radio"]').prop('checked', true);



			$nextElement.children('.med-checkmark-tag').attr('style', highlight_style); // Add style to the next element







			ajax_display_single_question(postID);







		}







	})















	$(document).on('click', '#tagged-single-question-prev', function (e) {







		var termcol = $("#med-questions-lists-id").data('termcol');



		var highlight_style = "background-color:" + termcol;







		var $medLists = $('#med-questions-lists-id');







		var $currentElement = $medLists.find('.med-bg');







		var $prevElement = $currentElement.prev();







		// if ($prevElement) {







		var prevDataId = $prevElement.data('id');







		if ($prevElement.length && prevDataId) {







			var postID = prevDataId;







			$medLists.find('.med-checkmark-tag').removeAttr('style');







			$medLists.find('.med-bg').removeClass('med-bg').find('input[type="radio"]').prop('checked', false);







			$prevElement.addClass('med-bg').find('input[type="radio"]').prop('checked', true);







			$prevElement.children('.med-checkmark-tag').attr('style', highlight_style);







			// $prevElement.addClass('med-bg');















			ajax_display_single_question(postID);







		}







	})















	$(document).on('click', '#tagged-single-question-check-ans', function (e) {







		console.log('cliced ans');







		// single and multiple choice



		$('.cpm-correct-ans').addClass('wpProQuiz_answerCorrect');



		$('.cpm-correct-ans').children('label').addClass('is-selected').find('input[type="radio"]').attr('checked', 'checked');



		$('.cpm-correct-ans').children('label').find('input[type="checkbox"]').prop('checked', true);







		// free choice



		$('.wpProQuiz_questionListItem .cpm-free-ans').parent('.wpProQuiz_questionListItem').addClass('wpProQuiz_answerCorrect');



		$('.wpProQuiz_questionListItem label span.wpProQuiz_freeCorrect').css('display', '');







		//fill in the blanks



		var fillValue = $("#cpm-cloze-ans").val();







		if (fillValue) {



			var valueLength = fillValue.length;



		} else {



			var valueLength = '5';



		}







		$('.wpProQuiz_cloze input').addClass('wpProQuiz_answerCorrect').val(fillValue);



		$('.wpProQuiz_cloze input')



			.addClass('wpProQuiz_answerCorrect')



			.attr('size', valueLength)



			.attr('maxlength', valueLength);







		// assesment 



		$('.wpProQuiz_questionListItem #cpm-asses-ans').css('display', '');







		//sort element



		$(".cpm-sort-element").parents(".wpProQuiz_questionListItem").addClass("wpProQuiz_answerCorrect");



		$(".cpm-sort-element").removeAttr("style");







		// sorting choice



		$(".wpProQuiz_sortable").parents(".wpProQuiz_questionListItem").addClass("wpProQuiz_answerCorrect");





		$('.learndash-wrapper .wpProQuiz_content .wpProQuiz_questionListItem').not('.wpProQuiz_answerCorrect').addClass('wpProQuiz_answerWrong');



	});







})(jQuery);















jQuery(document).ready(function ($) {







	//$("button.wpProQuiz_button").wrapAll("<div class='cpm-buttn-wrapper'></div>");



	//$(".cpm-Qtag.cpm-easy, .cpm-Qtag.cpm-medium, .cpm-Qtag.cpm-hard").wrapAll("<div class='cpm-middle-btn'></div>");







	$('#med-question').on('change', function () {







		var selectedValue = $(this).val();







		var currentURL = window.location.href;







		var url = new URL(currentURL);







		var tagValue = url.searchParams.get('tag');























		if (tagValue) {







			url.searchParams.set('tag', selectedValue);















			// Redirect to the updated URL







			window.location.href = url.toString();







		} else {







			// "?tag=" value not found in the URL







			// Add the "tag" parameter with a value







			url.searchParams.append('tag', selectedValue);















			// Redirect to the updated URL







			window.location.href = url.toString();







		}















		// if (currentURL.indexOf('?tag') > -1) {







		// 	// Update the value of the parameter







		// 	console.log('here');







		// 	var updatedURL = currentURL.replace(/tag=([^&]*)/, 'tag=' + selectedValue);















		// } else {







		// 	console.log('else');







		// 	var updatedURL = currentURL + "?tag=" + selectedValue;







		// }







		// window.location.href = updatedURL;































		// Perform actions based on the selected value







		// console.log('Selected value: ' + selectedValue);















		// $.ajax({







		// 	url: myAjax.ajax_url,







		// 	type: 'POST',







		// 	data: {







		// 		action: 'ajax_display_tagged_questions_list',







		// 		question_tag: selectedValue







		// 	},







		// 	success: function (response) {







		// 		// console.log(response);







		// 		$('#med-questions-lists-id').html(response);















		// 		var response = $($.parseHTML(response));















		// 		$.each(response, function (key, value) {







		// 			console.log(value);







		// 			// var element = value.find('.med-bg');







		// 			// if (value.classList.contains('.med-bg')) {







		// 			// 	console.log(value);















		// 			// 	var qid = value.data('id');







		// 			// 	console.log(qid);















		// 			// }







		// 		})







		// 	},







		// 	error: function (error) {







		// 		console.log(error);















		// 	}







		// });















	});































	var homeUrl = getUrl.homeUrl;







	var customElement = `<div class="ld-lesson-item-preview custom-focus-sidebar-item">







	<a class="ld-lesson-item-preview-heading ld-primary-color-hover" href="`+ homeUrl + `tagged-question/">







	<div class="ld-status-icon ld-secondary-background">







	<i class="fa-solid fa-tag"></i></div>







	<div class="ld-lesson-title">Tagged Questions</div></a></div>`;















	$('.ld-course-navigation').append(customElement);























});























function hexToRgb(hex) {







	var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);







	return result ? {







		r: parseInt(result[1], 16),







		g: parseInt(result[2], 16),







		b: parseInt(result[3], 16)







	} : null;







}







// Niresh JS

jQuery(document).on('click', '.mdSidebar_trigger', function (e) {

	jQuery('.mdSidebar').toggleClass('mdSidebar-collapse');

	jQuery('.mdSidebar_trigger i').toggleClass('fa-chevron-right fa-chevron-left');

	}

)