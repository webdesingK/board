(function () {
	
	// filter сбор строки фильтров
	(function() {

		let btnFilter   = document.querySelector('.content__filter-btn'),
				priceMin    = document.querySelector('#price__filter-min'),
				priceMax    = document.querySelector('#price__filter-max'),
				title       = document.querySelector('title'),
				price       = document.querySelectorAll('#price__filter-min, #price__filter-max'),
				contentWrap = document.querySelector('.content__wrap'),
				filterStr   = '',
				priceMaxNum = {
					max: 12
				};

		function changeTitle() {
			let url = decodeURI(location.pathname).slice(1).replace(/\/фильтры[^]*/gi, '');
			title.innerText = decodeURI(url);
		};

		// Функция загрузки контента
		function getContent(url, addEntry) {
			loads();
			changeTitle();
			$.ajax({

				type: 'GET',
				url: url,
				success: function(resp) {
			    contentWrap.innerHTML = resp;
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
			} else if (val.length > priceMaxNum.max) {
				if (!isNaN(val)) {
					return Math.round(val.slice(0, priceMaxNum.max));
				} else{
					return val.replace(/\D/g, '');
				}
				
			} else if (!isNaN(val)) {
				return Math.round(val);
			} else {
				return val.replace(/\D/g, '');
			}
		};

		for (let i = 0; i < price.length; i++) {
			price[i].addEventListener('keyup', function() {
				let val    = this.value.trim(),
				    str    = isNumber(val) + '',
				    result = str.replace(/(?=\B(?:\d{3})+(?!\d))/g, ' ');
				this.value = result;
			});
		}

		function priceMinMax() {// возвращает строку 'цена=min-max', а если не прошла проверку, тогда пустую строку
			let min = +priceMin.value.replace(/\s/gi, ''),
			    max = +priceMax.value.replace(/\s/gi, '');
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
			let nameType = arr[0].closest('ul').previousElementSibling.innerText.slice(0, -1) + '=',
					str      = '';
			nameType = nameType.replace(/\s/g, '');
			for (let i = 0; i < arr.length; i++) {
				if (arr[i].checked) {
					str += arr[i].previousElementSibling.innerText + ','
				}
			}
			if (str) {
				return ';' + nameType + str.slice(0, -1);
			} else{
				return '';
			}
		};

		btnFilter.addEventListener('click', function() {

			let minMax    = priceMinMax().replace(/\s/g, '').toLowerCase(),
					locHref   = decodeURI(document.location.href),
			    arrFilter = document.querySelectorAll('.filter-js'),
			    arrString = '',
					url;
			for (let i = 0; i < arrFilter.length; i++) {
				arrString += filterCheckbox(arrFilter[i].querySelectorAll('input'))
			}
			if (minMax || arrString) {
				url = locHref.replace(/\/фильтры[^]*/gi, '') + '/фильтры/' + minMax + arrString;
			} else{
				url = locHref.replace(/\/фильтры[^]*/gi, '');
			}
			url = url.replace(/\s/g, '-').toLowerCase();
			url = url.replace(/(фильтры\/)(;)/, '$1');
			url = url.replace(/(\[.*?\]|\(.*?\)) */gi, '');
			if (locHref == url) return
			console.log('finish')
			getContent(url, true);
		});
	})();

	function Tabs() {
		// Глобальная функция скрытия и открытия блоков(с возможностью анимировать стрелку)
		this.toggleBlock = function(obj) {// принимает обьект с элементами и классом для анимации открытия(кнопки и самого блока)
			// принимает два массива с элементом(ами) (1: nodeHide - который(е) нужно анимировать, 2: arrNode - на который(е) кликаем)
			let arrow        = obj.arrow || false,// если в обьект не передавался названия класса для кнопки, то по умолчанию стои false
					animClass    = obj.animClass || 'none';// если не передавался в обьект класс для анимации самого блока, то по умлчанию стоит класс 'none'
			function toggleClass(el, ind) {// локальная функция принимающая два обезательных аргумента(1: елемент на который произошел клик, 2: индекс елемента)
				obj.nodeHide[ind].classList.toggle(animClass);// меняем класс элементу который нужно скрывать или показывать
				if (typeof arrow === 'string' || arrow instanceof String) {// проверяем на тип данных(string), если в обект был передан аргумент с названием класса
					el.firstElementChild.classList.toggle(arrow);// тогда меняем класс первому дочернему элементу(arrow), елемента на который кликнули
				}
			}
			for (let i = 0; i < obj.arrNode.length; i++) {// цикл в который передаем массив из элементов на которые кликаем
				obj.arrNode[i].addEventListener('click', function() {// устанавливаем событие 'click' и запускаем функци, в которой вызываем функцию для скрытия или показа блока
					toggleClass(this, i);// в которую передаем элемент на который кликнули и его индекс
				});
			}
		};
		// глобальная функция для скрытия элементов при клике на esc
		this.esc = function(obj) {// с обьектом содержащих два обезательных аргумента
			// принимает два массива с элементом(ами) (1: nodeHide - который(е) нужно анимировать, 2: nodeClick - на который(е) кликаем)
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
		// глобальная функция для скрытия элементов(та) при клике в другом месте
		this.clickNoElements = function(obj) {// принимает обьект с 3 обезательными массивами из элементов
			// 1: nodesClick   - масив из элементов(та) по которым(ому) произойдет клик
			// 2: nodesNotHide - элементы(и их дочерние) при клике на которые не скрывать блоки(тоесть не запускать функцию close)
			// 2: nodesHide    -  массив элементов(та) которые(й) нужно скрывать
			document.addEventListener('mouseup', function(e) {// вешаем событие клика по всему документу и принимаем аргументом event
				function children(el) {// функция для отлавливания клика на дочерние элементы, принимает единственный аргумент - родительский элемент
					let bool = false;// для конечного вывода, по умолчанию false
					for (var i = 0; i < el.getElementsByTagName('*').length; i++) {// цикл в котором устанвливаеться длина из всех дочерних элементов, переданного в параметрах функции (el)
						if (e.target == el.getElementsByTagName('*')[i]) {// проверяем если кликнули по одному из элементов родителя
							bool = true;// уставливает конечный вывод в true
							break;// завершаем цикл
						}
					}
					return bool;// возвращаем конечный вывод
				}
				function close(el) {// функция для отлавливания клика по задонному массиву элемента(ов) в параметрах, которая и принимает их в параметре 
					let bool = true;// конечный вывод, по умолчанию true
					for (let i = 0; i < el.length; i++) {// циклы в котором устанавливаеться длина массива переданного в параметрах функции (el)
						if (e.target == el[i] || children(el[i])) {// проверяем если клик произошел по элементу переданного в параметрах функции(el) или по его дочерним элементам
							bool = false;// устанавливаем конечный вывод в false
							break;//завершаем цикл
						}
					}
					return bool;// возвращаем конечный вывод
				}
				if (close(obj.nodesNotHide)) {// проверяем если клик произошел не по элементу переданого в параметрах функции(obj.nodesТNotHide) и не по его дочерним элементам
					// тоесть если функция возвращает true 
					for (let i = 0; i < obj.nodesClick.length; i++) {// тогда запускаем цикл в котором устанавливаем длину массива переданного в параметрах функции(obj.nodesClick)
						if (obj.nodesHide[i].offsetHeight > 0) {// и проверяем, если элементы переданные в параметрах функции(obj.nodesHide) имеют высоту больше 0(тоесть открыты)
							obj.nodesClick[i].click();// тогда делаем искуственный клик по параметру переданного в функции(obj.nodesClick), для скрытия блока
						}
					}
				}
			});
		}
	};
	let tab = new Tabs();
	// меню
	(function() {
		let linkFirst      = document.querySelectorAll('.menu__first > li > a'),
				menuFirst      = document.querySelector('.menu__first'),
				menuSecond     = document.querySelectorAll('.menu__second'),
				menuBtn        = document.querySelector('.menu-btn'),
				flagMenuActive = true,
				flagMainMenu   = false,
				speed          = 10,
			  startX,
				x,
				set;
		menuBtn.addEventListener('click', function() {
			for (let i = 0; i < menuSecond.length; i++) {// проходим циклом по нему
				if (i == 0) menuSecond[i].classList.remove('none')
				else menuSecond[i].classList.add('none')// и скрываем их
			}
	  });
		// функция для скрытия всех категория 2 уровня
		function hideSubMenu(obj) {// передаем массив обьектов которые нужно скрыть
			for (let i = 0; i < obj.length; i++) {// проходим циклом по нему
				obj[i].classList.add('none');// и скрываем их
			}
		};
		// функция при наведении на категорию 1 уровня (эфект hover)
		function mouseShow(self) {
			hideSubMenu(menuSecond);// скрываем открытые категории 2 уровня
			self.nextSibling.classList.remove('none');// и открываем категорию 2 уровня на которой наведен курсор
		};
		// функция для запуска в setInterval 
		function funInterval(self) {// передаем аргумент this элемента на который был наведен курсор
			if (startX > x && flagMenuActive) {// проверяем если startX++ догнал x(коор. курсора) и курсор находиться в пределах ссылки
				mouseShow(self);// запускаем функция эфекта hover
				clearInterval(set);// и останавливаем setInterval
			}
			startX++;// добавляем +1 при каждом вызове функции
		};
		for (let i = 0; i < linkFirst.length; i++) {
			linkFirst[i].addEventListener('mousemove', function(e) {// событие для вычесления позиции курсора по оси x
				x = Math.round(e.pageX - this.getBoundingClientRect().left);
			// при наведении на любую из ссылок(1 уровня)
			});
			linkFirst[i].addEventListener('mouseenter', function(e) {
				startX = Math.round(e.pageX - this.getBoundingClientRect().left);// считываем коор. где вошел курсор на элемент и записываем в переменную
				flagMenuActive = true;// изминяем флаг, для разрешения отработки функции mouseShow
				if (flagMainMenu) {// если разрешено
					mouseShow(this);// запускаем hover эффект
				}
				flagMainMenu = false;// запрещаем отработку функции mouseShow
				clearInterval(set);// останавливаем предыдущий setInterval 
				set = setInterval(funInterval, speed, this);// запускаем новый setInterval
			});
		}
		// при наведении курсора на menu__second(то есть курсор покинул menu__first)
		for (let i = 0; i < menuSecond.length; i++) {
			menuSecond[i].addEventListener('mouseenter', function() {
				flagMenuActive = false;// изминяем флаг, для запрета отработки функции mouseShow
				clearInterval(set)
			})
		}
		// отслеживаем покидания курсора со всего меню
		menuFirst.addEventListener('mouseleave', function() {
			flagMainMenu = true;// изменяем флаг, для разрешения отработки функции mouseShow
		});
		// вызываем при клике функцию скрытия открытия блока меню
		tab.toggleBlock({
			nodeHide: document.querySelectorAll('.menu'),// элементы которые нужно анимировать или просто скрывать
			arrNode: document.querySelectorAll('.menu-btn')// элементы на которые кликаем
		});
		// вызываем функцию при клике в любом месте кроме меню
		tab.clickNoElements({
			nodesClick: document.querySelectorAll('.menu-btn'),// масив из элементов(та) по которым(ому) произойдет клик
			nodesNotHide: document.querySelectorAll('.menu, .menu-btn'),// элементы(и их дочерние) при клике на которые не скрывать блоки(тоесть не запускать функцию close)
			nodesHide: document.querySelectorAll('.menu')// массив элементов(та) которые(й) нужно скрывать
		});
		// при клике на esc скрываем блок с меню
		tab.esc({
			nodeHide: document.querySelectorAll('.menu'),// элементы которые нужно скрывать
			nodeClick: document.querySelectorAll('.menu-btn')// элементы на которые нужно кликать
		});
		document.querySelector('#menu-close').addEventListener('click', function() {
			document.querySelector('.menu-btn').click();
		});
	})();
	// вызываем при клике функцию скрытия открытия блока городов
	tab.toggleBlock({
		nodeHide: document.querySelectorAll('.city'),// элементы которые нужно анимировать или просто скрывать
		arrNode: document.querySelectorAll('.city-btn')// элементы на которые кликаем
	});
	// вызываем функцию при клике в любом месте кроме городов
	tab.clickNoElements({
		nodesClick: document.querySelectorAll('.city-btn'),// масив из элементов(та) по которым(ому) произойдет клик
		nodesNotHide: document.querySelectorAll('.city, .city-btn'),// элементы(и их дочерние) при клике на которые не скрывать блоки(тоесть не запускать функцию close)
		nodesHide: document.querySelectorAll('.city')// массив элементов(та) которые(й) нужно скрывать
	});
	// при клике на esc скрываем блок с городами
	tab.esc({
		nodeHide: document.querySelectorAll('.city'),// элементы которые нужно скрывать
		nodeClick: document.querySelectorAll('.city-btn')// элементы на которые нужно кликать
	});
	document.querySelector('#city-close').addEventListener('click', function() {
		document.querySelector('.city-btn').click();
	});
	// load
	let load        = document.querySelector('.load'),// линия загрузки
			loadOpacity = document.querySelector('.load-opacity'),// блок по свех элементов с прозрачностью
			intervalNum = 0,// счетчик
			options     = {// опции
				startEnd: 70,// до скольки процентов доезжать линии загрузки
				speedStart: 20,// начальная скорость загрузки линии
				speedEnd: 5// конечная скорость загрузки линии
			},
			end         = options.startEnd,// для завершения линии загрузки 
			set;// для инициализации setInterval
	function setLoads() {// функция для обработки setInterval
		load.style.width = intervalNum + '%';// увеличиваем ширину линии загрузки
		if(intervalNum <= end) {// если счетчик меньше конечного счетчика загрузки
			intervalNum++// тогда увеличиваем его на 1
		}	else {// а если больше конечного счетчика загрузки
			clearInterval(set);// останавливаем setInterval
			if (intervalNum >= 100) {// если завершилась загрузка
				loadOpacity.classList.add('none');// скрываем блок по всех элементов
				load.style.width = 0;// обнуляем ширину линии загрузки
				intervalNum = 0;// обнуляем счетчик
				end = options.startEnd;// и устанавливаем завершения загрузки линии в начальное значение
			}
		}
	};
	function loads() {// функция которая запускает setInterval и показывает блок по всех элементов
		set = setInterval(setLoads, options.speedStart)
		loadOpacity.classList.remove('none');
	};
	function endLoads() {// функция 
		clearInterval(set);// останавливаем начатый setInterval
		end = 100;// устанавливаем счетчик в завершительную фазу
		set = setInterval(setLoads, options.speedEnd)// и повторно запускаем setInterval с новым окончательным счетчиком
	};
	// авторизация
	(function() {
		let auth      = document.querySelectorAll('#auth'),// вход
		    authUser  = document.querySelectorAll('#auth-user'),// пользователь
		    authIn    = document.querySelectorAll('.auth__user'),// личный кабинет пользователя
				signIn    = document.querySelectorAll('#sign-in, #sign-up'),// блоки с регистрацией и входом
				signBtn   = document.querySelectorAll('#sign-in span, #sign-up span'),// кнопки регистрация и в вход, в блоках регистрации и вход
				closeSign = document.querySelectorAll('.close-sign');// кнопки закрытия родительских блоков(регистрации и вход)
		if (auth.length !== 0) {// если незарегистрированный пользователь
			auth[0].addEventListener('click', function() {// тогда при клике на на вход 
				signIn[0].classList.toggle('none')// открываем блок с регистрацией или закрываем
			});
		} else{// а если зарегистрированный пользователь 
			authUser[0].addEventListener('click', function() {// тогда при клике на пользователя
				authIn[0].classList.toggle('none')// открываем личный кабинет или закрываем
			});
		}
		for (let i = 0; i < signIn.length; i++) {// длина цикла количество блоков(регистрация и вход)
			signBtn[i].addEventListener('click', function() {// при клике на однуиз кнопок(вход или регистрация)
				signIn[0].classList.toggle('none');// меняем одно на другое(один скрыли другой показали)
				signIn[1].classList.toggle('none');// меняем одно на другое(один скрыли другой показали)
			})
		}
		for (let i = 0; i < closeSign.length; i++) {// дина цикла - количество кнопок
			closeSign[i].addEventListener('click', function() {// при клике на одну из них
				this.parentNode.classList.toggle('none')// меняем класс родителя(тоесть закрываем блок)
			})
		}
		// вызываем функцию при клике в любом месте кроме блока личного кабинета
		tab.clickNoElements({
			nodesClick: authUser,// масив из элементов(та) по которым(ому) произойдет клик
			nodesNotHide: document.querySelectorAll('#auth-user, .auth__user'),// элементы(и их дочерние) при клике на которые не скрывать блоки(тоесть не запускать функцию close)
			nodesHide: authIn// массив элементов(та) которые(й) нужно скрывать
		});
		// при клике на esc скрываем блоком личного кабинета
		tab.esc({
			nodeHide: authIn,// элементы которые нужно скрывать
			nodeClick: authUser// элементы на которые нужно кликать
		});
		// при клике на esc скрываем блок с авторизацией
		tab.esc({
			nodeHide: signIn,// элементы которые нужно скрывать
			nodeClick: closeSign// элементы на которые нужно кликать
		});
	})();
	// фильтра - визуализация
	(function() {
		let subCategoryList = document.querySelectorAll('.content__filter-category li');
		// функция открытия - закрытия блока с подкатегориями
		function openSubcategories(e) {// принимает event и this элемента на который кликнули
			let lvl = this.getAttribute('data-lvl');// считываем атрибут у кликнутого элемента
			if (this.classList.contains('active__filter-category')) {// проверяем если клик пришелся на элемент у которого есть класс 'active__filter-category'
				e.preventDefault();// отменяем стандартный переход по ссылке
				if (lvl !== null) {// проверяем если у кликнутого элемента нет атрибута 'data-lvl'
					this.classList.toggle('lvl-' + lvl);// тогда при наличии класса 'lvl' убираем его и наоборот
				}
				this.classList.toggle('active__filter-open');// также делаем изминения с классом активного открытого
				for (let i = 0; i < subCategoryList.length; i++) {// цикл длина которого все li 
					if (subCategoryList[i].classList.contains('active__filter-category')) continue// если попадаеться li с классом 'active__filter-category'(активный класс) тогда исключаем его из цикла
					subCategoryList[i].classList.toggle('none');// а остальным делаем замену класса 'none'(скрываем или открываем)			
				}
			} else{// а если клик произошел не на активном li 
				let lvl = this.className;// считываем класс
				if (lvl !== '') this.classList.remove(lvl)// если класс есть тогда удаляем его
				for (let i = 0; i < subCategoryList.length; i++) {// цикл длина которого все li
					if(subCategoryList[i].classList.contains('active__filter-category')) {// ищем у всех li активный класс и если находим
						subCategoryList[i].classList.remove('active__filter-category', 'active__filter-open');// тогда удаляем все активные классы у этого li
						subCategoryList[i].classList.toggle('none')// и делаем этому li замену класса 'none'(тоесть скрываем)
					} else{// у всех остальных li без активного класса
						if (this == subCategoryList[i]) continue// кроме Li на который кликнули
						subCategoryList[i].classList.toggle('none')// делаем замену класса(скрываем)
					}
				}
				this.classList.add('active__filter-category');// даем кликнутому li активный класс
			}
			let top = this.offsetTop;// считываем растоянние от активного класса до родителя
			this.parentNode.scrollTo(0,top)// и устанавливаем это растояние(top) родителю скроллтоп 
		};
		// цикл для запуска функции открытия блоков подкатегории 
		for (let i = 0; i < subCategoryList.length; i++) {
			subCategoryList[i].addEventListener('click', openSubcategories)// запускаем при клике на одну из li
		}
		// вызываем при клике функцию скрытия открытия блоков фильтров
		tab.toggleBlock({
			nodeHide: document.querySelectorAll('.filter-js ul'),// элементы которые нужно анимировать или просто скрывать
			arrNode: document.querySelector('#filter').querySelectorAll('.filter-js p'),// элементы на которые кликаем
			arrow: 'arrow-close'// анимируем стрелку если она есть
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
		// вызываем функцию при клике в любом месте кроме массива из фильтров
		tab.clickNoElements({
			nodesClick: document.querySelector('#filter').querySelectorAll('.filter-js p'),// масив из элементов(та) по которым(ому) произойдет клик
			nodesNotHide: document.querySelectorAll('.multitype-filter'),// элементы(и их дочерние) при клике на которые не скрывать блоки(тоесть не запускать функцию close)
			nodesHide: document.querySelectorAll('.filter-js ul')// массив элементов(та) которые(й) нужно скрывать
		});
		// вызываем функцию при клике в любом месте кроме подкатегорий
		tab.clickNoElements({
			nodesClick: document.querySelectorAll('.active__filter-category'),// масив из элементов(та) по которым(ому) произойдет клик
			nodesNotHide: document.querySelectorAll('.multitype-filter'),// элементы(и их дочерние) при клике на которые не скрывать блоки(тоесть не запускать функцию close)
			nodesHide: document.querySelectorAll('.content__filter-category li:not(.active__filter-category)')// массив элементов(та) которые(й) нужно скрывать
		});
	})();
	// смена вида
	(function() {// здесь все элентарно без коментариев
		let line   = document.querySelector('#view-line'),
				square = document.querySelector('#view-square'),
				wrap   = document.querySelector('.content__wrap');
		line.addEventListener('click', function() {
			wrap.classList.remove('square-wrap')
		});
		square.addEventListener('click', function() {
			wrap.classList.add('square-wrap')
		});
	})();

})();