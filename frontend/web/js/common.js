$(document).ready(function() {

	// когда открыто меню при нажатии в любом месте кроме самого меню скрываем меню
	function closeCLick(e, elements, btn) {
		let div = elements; // тут указываем ID элемента
		if (!div.is(e.target) // если клик был не по нашему блоку
		    && div.has(e.target).length === 0) { // и не по его дочерним элементам
				btn.click(); // скрываем его
		} 
	};

	// menu
	$(function() {

		let menu       = $('.menu'),
				city       = $('.city'),
				menuBtn    = $('#menu-btn'),
				cityBtn    = $('#city-btn'),
				menuClose  = $('#menu-close'),
				cityClose  = $('#city-close'),
				flagBtn    = true,
				heightMenu = $('.menu__first').outerHeight(true);

		// функция для скрытия всех категория 2 уровня
		function hideSubMenu(obj) {// передаем массив обьектов которые нужно скрыть
			obj.each(function() {// проходим циклом по нему
				$(this).hide();// и скрываем их
			});
		};

		// при загрузке страницы запускаем функцию скрытия категорий 2 уровня
		hideSubMenu($('.menu__first li:not(:first) .menu__second'));// передаем в аргумент все категории 2 уровня кроме первого

		// menuBtn.on('click', clickMenuBtn);// клик на кнопку меню вызывает функцию скрытия(открытия) меню
		
		// функция для плавного открытия и закрытия меню городов
		function clickCityBtn() {
			city.slideToggle();// удаляем класс none который задан для скрытия меню
		};

		cityBtn.on('click', clickCityBtn);

    // клик на кнопку крестика(#menu-close) вызываем вункцию в которой
		menuClose.on('click', function(){
			menuBtn.click();// эмитируем клик на кнопку меню для закрытия меню
		});

    // клик на кнопку крестика(#city-close) вызываем вункцию в которой
		cityClose.on('click', function(){
			cityBtn.click();// эмитируем клик на кнопку меню для закрытия меню
		});

		$(document).mouseup(function (e){ // событие клика по веб-документу
			if (menu.is(':visible')) {
				closeCLick(e, $('.menu, #menu-btn'), menuBtn);
			}
			if (city.is(':visible')) {
				closeCLick(e, $('.city, #city-btn'), cityBtn);
			}
		});

		// при нажатии на esc делаем проверки и отрабатываем функционал
		$(document).keyup(function(evt){
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

	var load        = $('.load'),
			loadOpacity = $('.load-opacity'),
			flagLoad        = false,
			options     = {
				speedStart: 300,
				speedJump: 20,
				speedEnd: 800
			};

	function loads() {
		let set = setInterval(setLoads, options.speedStart + options.speedJump),
				i   = 1;
		loadOpacity.show();
		function setLoads() {
			if (flagLoad) return clearInterval(set);
			load.animate({
				width: i * 10 + '%'
			}, options.speedStart, 'linear');
			if(i <= 7) i++;
		};
	};

	function endLoads() {
		flagLoad = true;
		load.animate({
			width: '100%'
		}, options.speedEnd, function() {
			load.css('width', '0%');
			loadOpacity.hide();
			flagLoad = false;
		});
	};


	// filter работа с визуализацией
	$(function() {

		let categoryUl           = $('.content__filter-category ul'),
				categoryFilter       = categoryUl.children(),
		    countCategory        = $('.content__filter div'),
		    btnCategory          = countCategory.not('.content__filter-price').children('p'),
		    catFilters           = $('.content__filter').children(':not(.content__filter-category, .content__filter-btn)').find('ul');

		categoryFilter.click(function(event) {

			let self = $(this);
					lvl  = self.attr('data-lvl');

			if (self.hasClass('active__filter-category')) {
				event.preventDefault();
				self.toggleClass('lvl-' + lvl);

				self.toggleClass('active__filter-open');
				categoryFilter.filter(':not(.active__filter-category)').toggle();
			} else {
				self.removeClass();
				categoryUl.find('.active__filter-category').removeClass('active__filter-category active__filter-open');
				self.addClass('active__filter-category');
				categoryFilter.filter(':not(.active__filter-category)').hide();
			}
			let top = self.position().top;
			self.parent().scrollTop(top);

		});

		$(document).mouseup(function (e){ // событие клика по веб-документу
			if (categoryFilter.filter(':visible').length > 1) {
				closeCLick(e, countCategory, categoryFilter.filter('.active__filter-category'));
			}
			
		});

	});

	// filter сбор строки фильтров
	$(function() {

		let btnFilter = $('.content__filter-btn'),
				priceMin  = $('#price__filter-min'),
				priceMax  = $('#price__filter-max'),
				price     = $('#price__filter-min, #price__filter-max'),
				checkType = $('.content__filter-type').find('input'),
				filterStr = '',
				options   = {
					max: 12
				};

		function changeTitle() {

			let url = decodeURI(location.pathname).slice(1).replace(/\/фильтры[^]*/gi, '');

			let title = url;

			$('title').text(decodeURI(title));

		};

		// Функция загрузки контента
		function getContent(url, addEntry) {
			loads();
			changeTitle(url);
			$.ajax({

				type: 'GET',
				url: url,
				success: function(resp) {
			    $('.content__wrap').html(resp);
			    endLoads();
				},
				error: function() {
					console.error('error')
				}
			});

      if(addEntry == true) {
          // Добавляем запись в историю, используя pushState
          history.pushState(null, null, url); 
      }

		};

		window.addEventListener("popstate", function(e) {
		    // Передаем текущий URL
		    getContent(location.pathname, false);
		});

		function isNumber(value) {
			let val = value.replace(/\s/g, '');
			if (val == '') {
				return '';
			} else if (val.length > options.max) {
				if (!isNaN(val)) {
					return Math.round(val.slice(0, options.max));
				} else{
					return val.replace(/\D/g, '');
				}
				
			} else if (!isNaN(val)) {
				return Math.round(val);
			} else {
				return val.replace(/\D/g, '');
			}

		};

		price.keyup(function() {

			let val    = $(this).val().trim(),
			    str    = isNumber(val) + '',
			    result = str.replace(/(?=\B(?:\d{3})+(?!\d))/g, ' ');
			$(this).val(result);

		});

		function priceMinMax() {

			let min = +priceMin.val().replace(/\s/gi, ''),
			    max = +priceMax.val().replace(/\s/gi, '');
			if (!min && !max || min == 0 && max == 0) {
				return '';
			} else if(!min || min == 0 && max) {
				return 'цена=0-' + max;
			} else if (min >= max) {
				return 'цена=' + min + '-' + priceMax.attr('data-max');
			} else{
				return 'цена=' + min + '-' + max;
			}
		};

		function filterCheckbox(arr) {
			let nameType  = arr.eq(0).parents('ul').siblings('p').text().slice(0, -1) + '=',
					str       = '';
			arr.each(function() {
				if ($(this).prop('checked')) {
					str += $(this).siblings('label').text() + ',';
				}
			});
			if (str) {
				return ';' + nameType + str.slice(0, -1);
			} else{
				return '';
			}
		};

		btnFilter.on('click', function() {

			let minMax    = priceMinMax().replace(/\s/g, '').toLowerCase(),
					locHref   = decodeURI(document.location.href),
			    arrFilter = $('.filter-js'),
			    arrString = '',
					url;

			arrFilter.each(function() {
				arrString += filterCheckbox($(this).find('input'));
			});

			if (minMax || arrString) {
				url = locHref.replace(/\/фильтры[^]*/gi, '') + '/фильтры/' + minMax + arrString;
			} else {
				return;
			}

			if (locHref == url) {
				return;
			}

			if (/фильтры/gi.test(locHref)) {
				url = url.replace(/\/фильтры[^]*/gi, '');
				url = locHref.replace(/\/фильтры[^]*/gi, '') + '/фильтры/' + minMax + arrString;
			}

			url = url.replace(/\s/g, '-').toLowerCase();
			url = url.replace(/(фильтры\/)(;)/, '$1');
			url = url.replace(/(\[.*?\]|\(.*?\)) */gi, '');

			getContent(url, true);

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

function Tabs(options) {

	let arrNode      = options.arrNodeClick,
			nodeNotHide  = options.nodeNotHide,
			nodeHide     = options.nodeHide;


	this.toggleBlock = function(obj) {

		let arrow        = obj.arrow || false,
				animClass    = options.animClass || 'none';
		function toggleClass(el, ind) {
			obj.nodeHide[ind].classList.toggle(animClass);
			if (typeof arrow === 'string' || arrow instanceof String) {
				el.firstElementChild.classList.toggle(arrow);
			}
		}

		for (let i = 0; i < obj.arrNode.length; i++) {
			obj.arrNode[i].addEventListener('click', function() {
				toggleClass(this, i)
			});
		}

	};

	// глобальная функция для скрытия элементов при клике на esc
	this.esc = function(obj) {// с обьектом содержащих два обезательных аргумента
		
		document.addEventListener('keyup', function(e) {// при клике на любую кнопку на клавиатуре
			if (e.keyCode == 27) {// если клик был на esc
				for (let i = 0; i < obj.nodeHide.length; i++) {// запускаем цикл с длиной массива передающего в обьект массива
					if (obj.nodeHide[i].offsetHeight > 0) {// если один из этих элеметов массива открыт(тоесть высота больше 0)
						obj.nodeClick[i].click();// тогда производим клик(и) по элементу(там) передаваемого(мых) в обьекте
					}
				}		
			}
		});

	}

	document.addEventListener('mouseup', function(e) {

		function parent(el) {
			let bool = false;
			for (var i = 0; i < el.getElementsByTagName('*').length; i++) {
				if (e.target == el.getElementsByTagName('*')[i]) {
					bool = true;
					break;
				}
			}
			return bool;
		}

		function close(el) {
			let bool = true;
			for (let i = 0; i < el.length; i++) {
				if (e.target == el[i] || parent(el[i])) {
					bool = false;
					break;
				}
			}
			return bool;
		}

		if (close(nodeNotHide)) {
			for (let i = 0; i < arrNode.length; i++) {
				if (nodeHide[i].offsetHeight > 0) {
					arrNode[i].click();
				}
			}
		}

	});
};

let tab = new Tabs({
	arrNodeClick: document.querySelector('#filter').querySelectorAll('.filter-js p'),
	nodeNotHide: document.querySelectorAll('.multitype-filter'),
	nodeHide: document.querySelectorAll('.filter-js ul')
});

// вызываем при клике функцию скрытия открытия блоков фильтров
tab.toggleBlock({
	nodeHide: document.querySelectorAll('.filter-js ul'),// элементы которые нужно анимировать или просто скрывать
	arrNode: document.querySelector('#filter').querySelectorAll('.filter-js p'),// элементы на которые кликаем
	arrow: 'arrow-close'// анимируем стрелку если она есть
});

// вызываем при клике функцию скрытия открытия блоков фильтров
tab.toggleBlock({
	nodeHide: document.querySelectorAll('.menu'),// элементы которые нужно анимировать или просто скрывать
	arrNode: document.querySelectorAll('#menu-btn')// элементы на которые кликаем
});

// при клике на esc скрываем блоки с фильтрами
tab.esc({
	nodeHide: document.querySelectorAll('.filter-js ul'),// элементы которые нужно скрывать
	nodeClick: document.querySelector('#filter').querySelectorAll('.filter-js p')// элементы на которые нужно кликать
});

// при клике на esc скрываем блок с подкатегориями
tab.esc({
	nodeHide: document.querySelectorAll('.content__filter-category li:not(.active__filter-category)'),// элементы которые нужно скрывать
	nodeClick: document.querySelectorAll('.active__filter-category')// элементы на которые нужно кликать
});

// при клике на esc скрываем блок с меню
tab.esc({
	nodeHide: document.querySelectorAll('.menu'),// элементы которые нужно скрывать
	nodeClick: document.querySelectorAll('#menu-btn')// элементы на которые нужно кликать
});

// при клике на esc скрываем блок с городами
tab.esc({
	nodeHide: document.querySelectorAll('.city'),// элементы которые нужно скрывать
	nodeClick: document.querySelectorAll('#city-btn')// элементы на которые нужно кликать
});