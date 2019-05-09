$(document).ready(function() {
	
	// menu
	$(function() {

		var menu       = $('.menu'),
				menuBtn    = $('#menu-btn'),
				flagBtn    = true,
				heightMenu = $('.menu__first').outerHeight(true);

		$('.menu__second').each(function() {
			$(this).height(heightMenu);
		});

		function clickMenuBtn() {
			$(this).next().slideToggle('slow', function() {
				hideSubMenu($('.menu__second'));
				$(this).next().show();
			}).removeClass('none');
		};

		menuBtn.on('click', clickMenuBtn);
		menu.on('click', '#menu-close', function(){
			menuBtn.click();
		});

		function hideSubMenu(obj) {
			obj.each(function() {
				$(this).hide();
			});
		};

		hideSubMenu($('.menu__second:not(:first)'));

		function mouseShow() {
			hideSubMenu($('.menu__second'));
			$(this).children('.menu__second').show();
		};

		menu.on('mouseenter', '.menu__first > li', mouseShow);

		// когда открыто меню при нажатии в любом месте кроме самого меню скрываем меню
		$(document).mouseup(function (e){ // событие клика по веб-документу
			var div = $('.menu'); // тут указываем ID элемента
			if (!div.is(e.target) // если клик был не по нашему блоку
			    && div.has(e.target).length === 0 // и не по его дочерним элементам
			    && $('.menu__first').is(':visible')) { // и когда меню открыто
				menuBtn.click(); // скрываем его
			}
		});

		// когда открыто меню при нажатии на esc скрываем меню
		$(document).keyup(function(evt){
			if (evt.keyCode == 27 && $('.menu__first').is(':visible')) menuBtn.click();
		});

	});


});
