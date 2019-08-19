$(function(){

	$('.inline').popup();

	$('form').not('.no-ajax').submitForm({
		success: function($f) {
			var goal = $f.find('input[name=goal]').val();
			$.sendGoals(goal);
		}
	});

	$('.clickgoal').on('click', function() {
		var goal = $(this).data('goal');
		$.sendGoals(goal);
		return true;
	});

	$('.gotoform').gotoForm();

	$('.slider ul').slider();

	$('.zoom').colorbox({
		fixed: true,
		transition: 'fade',
		closeButton: true,
		maxWidth: '90%',
		maxHeight: '90%',
	});

	// program compressor blocks
	function Block() {
		this.open = false;
		this.open_text = '';
		this.closed_text = '';
		this.colors = ['#f5ab99', '#feb47b', '#ff7e5f', '#765285', '#351C4D'];
		this.toggleOpen = function() {
			this.open = (!this.open) ? true : false;
		}
		this.scrollOpen = function() {
			$('#compressors_form').slideDown('slow', function() {
				$('html,body').animate({
					scrollTop: $('#compressors_form').offset().top - 150
				});
			});
		}		
	}

	var block_1 = new Block();
	block_1.open_text = '<ul class="compressors"><li>Altas copco</li><li>Comprag</li><li>ЗИФ</li></ul>'; 
	block_1.closed_text = '<span>Компрессор дизельный малой мощности 7-12 Бар/2-5,5 м3.мин</span>'; 

	var block_2 = new Block();
	block_2.open_text = '<ul class="compressors"><li>Altas copco</li><li>Comprag</li><li>ЗИФ</li></ul>'; 
	block_2.closed_text = '<span>Компрессор дизельный средней мощности 7-14 Бар/7,5-12 м3.мин</span>'; 

	var block_3 = new Block();
	block_3.open_text = '<ul class="compressors"><li>Altas copco</li><li>Comprag</li><li>ЗИФ</li></ul>'; 
	block_3.closed_text = '<span>Компрессор дизельной высокой мощности 8,6-35Бар/22-63 м3.мин</span>';

  var block_4 = new Block();
  block_4.open_text = '<ul class="compressors"><li>Altas copco</li><li>Comprag</li><li>ЗИФ</li></ul>';
  block_4.closed_text = '<span>Компрессор дизельной высокой мощности 8,6-35Бар/22-63 м3.мин</span>';

  $choice = [];

	$('#compressors .art .items .item:eq(0)').click(function(e) {
		$choice = [];
		$('[name="choice"]').val('');
		block_1.toggleOpen();
		$(this).find('.desc').toggleClass('bounce animated');
		if (block_1.open) {		
			$('#compressors .art .items .item:eq(0) .desc').find('>:first-child').replaceWith(block_1.open_text);
		} else {

			if (e.target.className !== 'desc') {
				$choice.push([block_1.closed_text.replace('<span>','').replace('</span>',''), e.target.textContent]);
			} else {
				$choice.push([block_1.closed_text.replace('<span>','').replace('</span>','')]);
			}
			block_1.scrollOpen();
			$('#compressors .art .items .item:eq(0) .desc').find('>:first-child').replaceWith(block_1.closed_text);
			$('[name="choice"]').val(JSON.stringify($choice));
		}

	});

	$('#compressors .art .items .item:eq(1)').click(function(e) {
		$choice = [];
		$('[name="choice"]').val('');
		block_2.toggleOpen();
		$(this).find('.desc').toggleClass('bounce animated');

		if (block_2.open) {		
			$('#compressors .art .items .item:eq(1) .desc').find('>:first-child').replaceWith(block_2.open_text);
		} else {
			if (e.target.className !== 'desc') {
				$choice.push([block_2.closed_text.replace('<span>','').replace('</span>',''), e.target.textContent]);
			} else {
				$choice.push([block_2.closed_text.replace('<span>','').replace('</span>','')]);
			}	
			block_2.scrollOpen();
			$('#compressors .art .items .item:eq(1) .desc').find('>:first-child').replaceWith(block_2.closed_text);
			$('[name="choice"]').val(JSON.stringify($choice));
		}

	});	

	$('#compressors .art .items .item:eq(2)').click(function(e) {
		$choice = [];
		$('[name="choice"]').val('');
		block_3.toggleOpen();
		$(this).find('.desc').toggleClass('bounce animated');

		if (block_3.open) {		
			$('#compressors .art .items .item:eq(2) .desc').find('>:first-child').replaceWith(block_3.open_text);
		} else {
			if (e.target.className !== 'desc') {
				$choice.push([block_3.closed_text.replace('<span>','').replace('</span>',''), e.target.textContent]);
			} else {
				$choice.push([block_3.closed_text.replace('<span>','').replace('</span>','')]);
			}			
			$('#compressors .art .items .item:eq(2) .desc').find('>:first-child').replaceWith(block_3.closed_text);
			block_3.scrollOpen();
			$('[name="choice"]').val(JSON.stringify($choice));
		}

	});

	$('#compressors .art .items .item:eq(3)').click(function(e) {
		$choice = [];
		$('[name="choice"]').val('');
		block_4.toggleOpen();
		$(this).find('.desc').toggleClass('bounce animated');

		if (block_4.open) {
			$('#compressors .art .items .item:eq(3) .desc').find('>:first-child').replaceWith(block_4.open_text);
		} else {
			if (e.target.className !== 'desc') {
				$choice.push([block_4.closed_text.replace('<span>','').replace('</span>',''), e.target.textContent]);
			} else {
				$choice.push([block_4.closed_text.replace('<span>','').replace('</span>','')]);
			}
			$('#compressors .art .items .item:eq(3) .desc').find('>:first-child').replaceWith(block_4.closed_text);
			block_4.scrollOpen();
			$('[name="choice"]').val(JSON.stringify($choice));
		}

	});

	$(".option").wrap("<div class='new'></div>");
	$('.option input[type="checkbox"]').after('<span class="check-box"></span>');
	$('.option input[type="radio"]').after('<span class="check-box"></span>');

	/*$('.iframe').each(function() {
		$(this).removeAttr('rel');
	}).colorbox({
		fixed: true,
		transition: 'fade',
		closeButton: true,
		iframe: true,
		width: '1150px',
		height: '90%'
	});*/

	/*$('.youtube').each(function() {
		$(this).removeAttr('rel');
	}).colorbox({
		fixed: true,
		transition: 'fade',
		closeButton: true,
		iframe: true,
		width: '90%',
		maxWidth: '980px',
		height: '70%'
	});*/

	$('.close').click(function() {
		$.colorbox.close();
		return false;
	});

	/*$('.projects .tab').tabs({
		target: '.projects .item',
		init: function($el, $tar) {
			$tar.find('.cost li a').tabs({
				target: $tar.find('.cost li span'),
				selectClass: 'active',
			});
		},
	});*/

	//$('.toggle').toggleBlock();

	/*
	$('.open').toggleBlock({
		onlyOpen: true,
	});
	*/

	$('.scroll').appendScroll({deltaY: -80});

	/*$('header').fixedBlock({
		deltaY: 300,
	});*/

	/*$('.playlist').playlist({
		itemSelector: 'li',
		playImg: '/styles/img/video_play.png',
		showMenuTitle: false,
		showThumb: true,
	});*/

	//if (!$.isMobile()) new WOW().init();

	//$('.sec01').backgroundVideo();

	/*
	$('.bg').backgroundSlider({
		speed: 1000,
		delay: 8000,
	});
	*/

	$('.ymap').ymap();

	$.showgoals({
		target: '#goals',
		console: false,
		noduplicate: true,
	});

});