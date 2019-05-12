$(document).ready(function() {
	
	// menu
	$(function() {

		let menu       = $('.menu'),
				menuBtn    = $('#menu-btn'),
				flagBtn    = true,
				heightMenu = $('.menu__first').outerHeight(true);

		// задаем всем menu__second высоту родителя, что-бы при сворачивании и разворачивании menu__second не перепрыгивали на новую строку 
		$('.menu__second').each(function() {
			$(this).height(heightMenu);
		});

		// функция для скрытия всех категория 2 уровня
		function hideSubMenu(obj) {// передаем массив обьектов которые нужно скрыть
			obj.each(function() {// проходим циклом по нему
				$(this).hide();// и скрываем их
			});
		};

		// при загрузке страницы запускаем функцию скрытия категория 2 уровня
		hideSubMenu($('.menu__first li:not(:first) .menu__second'));// передаем в аргумент все категории 2 уровня кроме первого

		// функция для плавного открытия и закрытия меню
		function clickMenuBtn() {
			$(this).next().slideToggle('slow', function() {// callback функция 
				hideSubMenu($('.menu__second'));// скрываем все категории второго уровня
				$(this).find('.menu__second:first').show();// открываем самую первую категорию второго уровня 
			}).removeClass('none');// удаляем класс none который задан для скрытия меню
		};

		menuBtn.on('click', clickMenuBtn);// клик на кнопку меню вызывает функцию скрытия(открытия) меню

    // клик на кнопку крестика(#menu-close) вызываем вункцию в которой
		menu.on('click', '#menu-close', function(){
			menuBtn.click();// эмитируем клик на кнопку меню для закрытия меню
		});

		// когда открыто меню при нажатии в любом месте кроме самого меню скрываем меню
		$(document).mouseup(function (e){ // событие клика по веб-документу
			let div = $('.menu'); // тут указываем ID элемента
			if (!div.is(e.target) // если клик был не по нашему блоку
			    && div.has(e.target).length === 0 // и не по его дочерним элементам
			    && $('.menu__first').is(':visible')) { // и когда меню открыто
				menuBtn.click(); // скрываем его
			}
		});

		// когда открыто меню при нажатии на esc скрываем меню
		$(document).keyup(function(evt){
			if (evt.keyCode == 27 && $('.menu__first').is(':visible')) menuBtn.click();
			if (evt.keyCode == 27 && $('#sign-in').is(':visible')) $('#sign-in').addClass('none');
			if (evt.keyCode == 27 && $('#sign-up').is(':visible')) $('#sign-up').addClass('none');
		});

		// функция при наведении на категорию 1 уровня (эфект hover)
		function mouseShow(self) {
			hideSubMenu($('.menu__second'));// скрываем открытые категории 2 уровня
			self.siblings('.menu__second').show();// и открываем категорию 2 уровня на которой наведен курсор
		};

		let startX,
				x,
				flagMenuActive = true,
				set = setInterval(fn,4);
				clearInterval(set);

		menu.on('mousemove', '.menu__first > li > a', function(evt) {
			 x = Math.round(evt.pageX - $(this).offset().left);
		});

		function fn(self) {
			if (startX > x && flagMenuActive) {
				mouseShow(self);
				clearInterval(set);
			}
			startX++;
		};

		menu.on('mouseenter', '.menu__second', function(){
			flagMenuActive = false;
		});

		menu.on('mouseenter', '.menu__first > li > a', function(evt) {

			startX = Math.round(evt.pageX - $(this).offset().left);
			flagMenuActive = true;
			
			clearInterval(set);
			set = setInterval(fn, 15, $(this));

		});

	});

	// auth
	$(function() {

		let auth = $('.auth');

		auth.on('click', '#auth', function(){
			$('#sign-in').toggleClass('none');
		});

		auth.on('click', '#sign-in span', function() {

			$('#sign-in').addClass('none');
			$('#sign-up').removeClass('none');

		});
		auth.on('click', '#sign-up span', function() {

			$('#sign-up').addClass('none');
			$('#sign-in').removeClass('none');

		});

		auth.on('click', '#auth-user', function(){
			$('.auth__user').slideToggle().removeClass('none');
		});

	});

});
