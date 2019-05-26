$(document).ready(function() {
	
	// tabs and js-none
	function singleTab(event) {

		let speed = 200,
				arrow = true;

		if (event.data !== null) {
			if (event.data.arrow !== undefined) arrow = event.data.arrow
			if (event.data.speed !== undefined) speed = event.data.speed
		}

		if (arrow) {
			$(this).children('.arrow-open').toggleClass('arrow-close');
		}

		$(this).next().slideToggle(speed);
	};

	$('.js-none').hide().removeClass('js-none');

	// menu
	$(function() {

		let menu       = $('.menu'),
				menuBtn    = $('#menu-btn'),
				cityBtn    = $('#city-btn'),
				menuClose  = $('#menu-close'),
				flagBtn    = true,
				heightMenu = $('.menu__first').outerHeight(true);

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
			if ($('.city').is(':visible')) {
				$('#city-btn').click();
			}
			$('.menu').slideToggle('slow', function() {// callback функция 
				hideSubMenu($('.menu__second'));// скрываем все категории второго уровня
				$(this).find('.menu__second:first').show();// открываем самую первую категорию второго уровня 
			});
		};

		menuBtn.on('click', clickMenuBtn);// клик на кнопку меню вызывает функцию скрытия(открытия) меню
		
		// функция для плавного открытия и закрытия меню городов
		function clickCityBtn() {
			$('.city').slideToggle();// удаляем класс none который задан для скрытия меню
		};

		cityBtn.on('click', clickCityBtn);

    // клик на кнопку крестика(#menu-close) вызываем вункцию в которой
		menuClose.on('click', function(){
			menuBtn.click();// эмитируем клик на кнопку меню для закрытия меню
		});

		// когда открыто меню при нажатии в любом месте кроме самого меню скрываем меню
		$(document).mouseup(function (e){ // событие клика по веб-документу
			let div = $('.menu, #menu-btn'); // тут указываем ID элемента
			if (!div.is(e.target) // если клик был не по нашему блоку
			    && div.has(e.target).length === 0 // и не по его дочерним элементам
			    && $('.menu__first').is(':visible')) { // и когда меню открыто
				menuBtn.click(); // скрываем его
			}
		});

		// при нажатии на esc делаем проверки и отрабатываем функционал
		$(document).keyup(function(evt){
			if (evt.keyCode == 27 && $('.menu__first').is(':visible')) menuBtn.click();// если меню открыто скрываем его
			if (evt.keyCode == 27 && $('.city').is(':visible')) cityBtn.click();// если меню городов открыто скрываем его
			if (evt.keyCode == 27 && $('#sign-in').is(':visible')) $('#sign-in').addClass('none');// если окно попап входа открыто скрываем его
			if (evt.keyCode == 27 && $('#sign-up').is(':visible')) $('#sign-up').addClass('none');// если окно попап регистрации открыто скрываем его
		});

		// функция при наведении на категорию 1 уровня (эфект hover)
		function mouseShow(self) {
			hideSubMenu($('.menu__second'));// скрываем открытые категории 2 уровня
			self.siblings('.menu__second').show();// и открываем категорию 2 уровня на которой наведен курсор
		};

		let startX,
				x,
				flagMenuActive = true,
				set = setInterval(funInterval,4),
				flagMainMenu = false;

				clearInterval(set);

		// событие для вычесления позиции курсора по оси x 
		menu.on('mousemove', '.menu__first > li > a', function(evt) {
			 x = Math.round(evt.pageX - $(this).offset().left);
		});

		// функция для запуска в setInterval 
		function funInterval(self) {// передаем аргумент this элемента на который был наведен курсор
			if (startX > x && flagMenuActive) {// проверяем если startX++ догнал x(коор. курсора) и курсор находиться в пределах ссылки
				mouseShow(self);// запускаем функция эфекта hover
				clearInterval(set);// и останавливаем setInterval
			}
			startX++;// добавляем +1 при каждом вызове функции
		};

		// при наведении курсора на menu__second(то есть курсор покинул menu__first)
		menu.on('mouseenter', '.menu__second', function(){
			flagMenuActive = false;// изминяем флаг, для запрета отработки функции mouseShow
		});

		// отслеживаем покидания курсора со всего меню
		menu.on('mouseleave', '.menu__first', function() {
			flagMainMenu = true;// изменяем флаг, для разрешения отработки функции mouseShow
		});

		// при наведении на любую из ссылок(1 уровня)
		menu.on('mouseenter', '.menu__first > li > a', function(evt) {

			startX = Math.round(evt.pageX - $(this).offset().left);// считываем коор. где вошел курсор на элемент и записываем в переменную
			flagMenuActive = true;// изминяем флаг, для разрешения отработки функции mouseShow
			if (flagMainMenu) {// если разрешено
				mouseShow($(this));// запускаем hover эффект
			}
			flagMainMenu = false;// запрещаем отработку функции mouseShow
			clearInterval(set);// останавливаем предыдущий setInterval 
			set = setInterval(funInterval, 15, $(this));// запускаем новый setInterval

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

	// load line
	$(function() {

		var load        = $('.load'),
				btn         = $('.btn-load'),
				loadOpacity = $('.load-opacity'),
				flag        = false,
				options     = {
					speedStart: 300,
					speedJump: 20,
					speedEnd: 300
				};

		function loads() {
			let set = setInterval(setLoads, options.speedStart + options.speedJump),
					i   = 1;
			function setLoads() {
				if (flag) return clearInterval(set);
				loadOpacity.show().removeClass('none');
				load.animate({
					width: i * 10 + '%'
				}, options.speedStart, 'linear');
				if(i <= 7) i++;
			};
		};

		function endLoads() {
			flag = true;
			load.animate({
				width: '100%'
			}, options.speedEnd, function() {
				load.css('width', '0%');
				loadOpacity.hide();
				flag = false;
			});
		};
		btn.on('click', loads);

	});

	// filter
	$(function() {

		let contentFilter  = $('.content__filter-category p'),
		    typeFilter     = $('.content__filter-type p'),
				btn            = $('.content__filter-btn');


		contentFilter.click(singleTab);

		typeFilter.click(singleTab);

		btn.click(function() {

			let inputId    = $('#filter-type input'),
					data = {
						arrInputId: [],
						min: $('.price-filter input').eq(0).val(),
						max: $('.price-filter input').eq(1).val()
					};

			inputId.each(function() {
				if ($(this).prop('checked')) {
					data.arrInputId.push($(this).attr('data-id'));
				}
			});

			$.ajax({

				type: "POST",
				data: data,
				success: function(resp) {
					$('.content__wrap').html(resp);
				},
				error: function(resp) {
					alert(resp);
				}

			});

		});

	});

	// смена вида
	$(function() {

		let line   = $('#view-line'),
				square = $('#view-square'),
				wrap   = $('.content__wrap');

		line.click(function(){
			wrap.removeClass('square-wrap');
		});
		square.click(function(){
			wrap.addClass('square-wrap');
		});

	});

});
