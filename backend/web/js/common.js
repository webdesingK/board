$(document).ready(function () {
	// category
	$(function () {

		// функция для вывода сообщения для предупреждения о незавершенной работе другого элемента
		function alertMessage(self, name, bool){

			self.removeClass(function(i, cls) {
			  return (cls.match(/alert\-\S+/g)||[]).join(" ");
			});

			self.addClass('alert-' + name + '');

			if (bool == undefined) {

				setTimeout(function(){
					self.removeClass('alert-' + name + '');
				},2000);

			}

		};

		// функция для проверки совпадения id открытого элемента с кликнутым элементом
		function checkIds(self, selector) {

			var openedId = $(selector + ':visible').parents('.category__list:first').attr('data-id'),
					closeId  = self.parents('.category__list:first').attr('data-id');

			if (openedId == closeId) {
				return true;
			} else{
				return false;
			}
		};

		// add form ----------------------------------------------
		function addCategory() {

			if ($('.form-add').length > 0 && !checkIds($(this), '.form-add')) {
				alertMessage($(this), 'form');
				return;
			};

			if ($(this).parent().siblings('.category__list').hasClass('none')) {// при добавлении формы проверяем если у родителя дочерние элементы скрыты
				$(this).parents('.category__list:first').removeClass('add-before');// тогда удаляем полоску у родителя
			}

			if ($(this).text() == '+') {
				
				$(this).html('&minus;');
				// добовляем форму 
				$(this).parent().after('<form class="form-add">\
						<input type="text" class="input-text" placeholder="Имя категории">\
						<button class="submit-btn">Сохранить</button></form>');

				$('.input-text').focus();

			} else{

				$(this).parent().siblings('.form-add').remove();
				$(this).text('+');

			}

		};

		// делегированное события клика на динамически добаленого элемента
		$('.category').on('click', '.add-category', addCategory);

		// end add form -------------------------------------------


		// функция передающая в ajax запрос с масивом блоков у которых нет класса 'none'
		function sort(arr, number) {

			var ar = [];

			for (var i = 0; i < arr.length; i++) {
				if (!arr.eq(i).hasClass('none')) {
					ar.push(arr.eq(i).data('id'));
				}
			}

			if (ar.indexOf(number) != '-1') {
				ar.splice(ar.indexOf(number),1);
			}
			return ar;
		};

		// ajax ---------------------------------------------------
		function ajx(obj) {

			$.ajax({
				type: 'POST',
				data: obj,
				success: function(resp) {
					if (resp == 'error') {
						alert('Ошибка сервера');
					}
					else{
						$('.category__main').html(resp);
						structureMovements();
						tab($('.category__list'));
						delLastBorder($('.category__list'));
						titleName($('.name-category'));
					}

				},
				error: function(resp) {
					alert(resp.responseText);
				}
			});

		}
		// end ajax -----------------------------------------------

		// checkbox change ----------------------------------------
		function checkbox() {

			var active,
					self          = $(this),
					totalCheckbox = self.parents('.category__list:first').find('.checkbox:not(:first)'),
					parents       = self.parents('.category__list').children('.category__list-block').children('.checkbox:not(:first)'),
					mainActive    = {'title': 'Деактивировать?', 'data-active': 1},
					mainDeactive  = {'title': 'Активировать?', 'data-active': 0},
					checkedArr    = [self.parents('.category__list:first').attr('data-id')],
					value;

			// если активируем дочерний checkbox значит циклом активируем родительские checkbox
			parents.each(function(){

				if (!$(this).prop('checked')) {// проверяем если родитель не активный только тогда делаем его активным

					$(this).prop('checked', true).attr(mainActive);// делаем родитей активными
					checkedArr.push($(this).parents('.category__list:first').attr('data-id'));// добавляем id родителей checkbox в массив 

				}

			});

			// делаем проверку checkbox на котором произошло изминение
			if (self.prop('checked')) {
				value = 1;
				self.attr(mainActive);
				self.parents('.category__list:first').prev().children('.checkbox').prop('checked', true).attr(mainActive);

			} else{
				value = 0;
				self.attr(mainDeactive);
			}

			// делаем изминения циклом дочерних checkbox
			totalCheckbox.each(function(){

				if ($(this).prop('checked')) {// изменяем с активного на неактивный

					$(this).prop('checked', false).attr(mainDeactive);
					checkedArr.push($(this).parents('.category__list:first').attr('data-id'));// добавляем id родителей checkbox в массив

				} else{// изменяем с неактивного на активный

					if (self.prop('checked')) {// при условии если родительский checkbox активный 

						$(this).prop('checked', true).attr(mainActive);
						checkedArr.push($(this).parents('.category__list:first').attr('data-id'));// добавляем id родителей checkbox в массив
					}

				}

			});

			// создаем обьект для передачи на сервер
			var data = {

				nameOfAction: 'changeActive',
				value: value,
				ids: checkedArr

			};

			// отправка на сервер
			$.ajax({

				type: 'POST',
				data: data,
				error: function(){
					alert('Ошибка сервера!!!');
				}

			});

		};

		$('.category').on('change', '.checkbox', checkbox);

		//  end checkbox change -----------------------------------

		// validation input ---------------------------------------

		var nameCategoryArr = [],
				nameCategory    = $('.name-category');

		nameCategory.each(function(){
			var text = $(this).text().toLowerCase();
			nameCategoryArr.push(text);
		});

		// end validation -----------------------------------------

		// add category ajax --------------------------------------
		function categoryAjax(evt) {
			
			evt.preventDefault(); //отменяем стандартное поведение кнопки отправки формы на сервер 

			var name = $(this).siblings('.input-text').val().trim(); // считываем с инпута содержимое
			var id = $(this).parents('.category__list').data('id'); // считываем атрибут id с родительского элемента
			
			// validation
			for (var i = 0; i < nameCategoryArr.length; i++) {
				if(nameCategoryArr[i] == name.toLowerCase()) {// проверяем вводимое значение на совпадения в массиве(переведенные в нижний регистр)
					$(this).prev().focus();
					alertMessage($(this), 'text_copy', true);
					return;
				} else if(name == '') {// проверяем вводимое значение на пустую строку
					alertMessage($(this), 'text_null', true);
					$(this).prev().focus();
					return;
				}
			}
			
			// добавляем в масив вновь созданную категорию для валидации в нижнем регистре
			nameCategoryArr.push(name.toLowerCase());

			var data = {
				nameOfAction: 'create', 
				name: name, // значение инпута
				id: id, // значение атрибута id
				openedIds: sort($('.category__list')) // сортировка открытых элементов
			};

			// отправляем ajax запрос на сервер
			ajx(data);

		};

		// при клике на кнопку вызываем функцию отправки формы на сервер
		$('.category').on('click', '.submit-btn', categoryAjax);

		// end add category ajax ----------------------------------

		// delete category ajax ----------------------------------
		
		$('.category').on('click', '.del-category', function() {// отправка ajax запроса на удаления категории и если есть в ней подкотегории

			var delName          = $(this).siblings('.name-category').text().toLowerCase(),
				  id               = $(this).parents('.category__list').attr('data-id'),
				  allAttachments   = $(this).parents('.category__list:first').find('.category__list');

			if (allAttachments.length > 0) {// проверяем если у родителя есть дочерние элементы

				allAttachments.each(function(){// тогда проходим по массиву этих дочерних элементов

					var del = $(this).find('.name-category').text().toLowerCase();// находим название категории и переводим в нижний регистр
					delete nameCategoryArr[nameCategoryArr.indexOf(del)];// удаляем с массива для валидации удаленную категорию

				});

			}

			delete nameCategoryArr[nameCategoryArr.indexOf(delName)];// удаляем с массива для валидации удаленную категорию

			var data = {
				nameOfAction: 'delete',
				id: id,
				openedIds: sort($('.category__list'), id)
			};

			ajx(data);

		});

		//  end category ajax ------------------------------------

		// nested ------------------------------------------------
		var nestedList = $('.category__list');

		function delLastBorder(arr) {

			$('.category__list-block:first').children('.del-category').remove();// удаляем элемент в самом первом блоке
			$('.category__list-block:first').children('.checkbox').remove();// удаляем элемент в самом первом блоке

			for (var i = 0; i < arr.length; i++) {

				if(arr.eq(i).children('.category__list').length == 0) {
					arr.eq(i).addClass('after_none');
					arr.eq(i).find('.tabs-category').remove();
				}

				arr.eq(i).children('.category__list:last').addClass('after_hide');

			}

		};
		delLastBorder(nestedList);

		function titleName(obj){

			obj.each(function(){
				$(this).attr('title', $(this).text());
			});

		};
		titleName($('.name-category'));
		// end nested ------------------------------------------

		// tabs ------------------------------------------------

		$('.category').on('click', '.tabs-category' ,function() {

			var tabs = $(this).parent().siblings().find('.tabs-category');// записываем массив из дочерних элементов кнопок tabs

			if ($(this).html() == '▶') {// если при клике на кнопку она была свернута

				$(this).html('▼').attr('title', 'Свернуть');// тогда меняем ей иконку на сворачивания и меняем title 
				$(this).parents('.category__list').addClass('add-before');// и родителю даем класс, который добовляет полосу вниз

			} else{// елси при клике на кнопку она была развернута
				
				// при сворачивании подкатегорий
				$('.form-add').remove();// удаляем открытю форму
				$('.editing-form').remove();// удаляем открытю форму

				$('.add-category').each(function(){// и меня '+' на '-'
					if ($(this).html() != '+') {// если стоит '-'
						$(this).text('+');// только, тогда меняем на '-'
					}
					$('.category__list-block').removeAttr('style');
				});
				
				tabs.each(function(){// nulf проходим циклом по всем дочерним элементам с кнопками tabs 

					if ($(this).html() == '▼') {// и проверяем, если она развернута

						$(this).html('▶').parent().siblings(':not(.form-add)').addClass('none');// тогда сворачиваем ее и вложенный category__list задаем класс none (тоесть скрываем)

					}

				});

				$(this).html('▶').attr('title', 'Развернуть');// и меням на обратное значение иконку и title 

			}

			$(this).parent().siblings('.category__list').toggleClass('none');// тоглим у внутреннего category__list класс none 

		});

		function tab(arr) {

			for (var i = 0; i < arr.length; i++) {
					
			 	if(!arr.eq(i).hasClass('none')){

			 	if (!arr.eq(i).children('.category__list').hasClass('none')) {

					arr.eq(i).addClass('add-before')
					.find('.tabs-category').html('▼')
					.attr('title', 'Свернуть');
				 	} else{
				 		arr.eq(i).addClass('add-before')
					.find('.tabs-category').html('▶')
					.attr('title', 'Развернуть');

				 	}
				}
			}

		};

		//  end tabs ------------------------------------------

		// structureMovements category

		function structureMovements() {

			var motionUp   = $('.motion__up-category'),
					motionDown = $('.motion__down-category');

				motionUp.each(function(){

					if (!$(this).parents('.category__list:first').prev().hasClass('category__list')) {
						$(this).remove();
					}

				});

				motionDown.each(function(){

					if (!$(this).parents('.category__list:first').next().hasClass('category__list')) {
						$(this).remove();
					}

				});

		};

		structureMovements();

		$('.category').on('click', '.motion__up-category', movements);
		$('.category').on('click', '.motion__down-category', movements);

		function movements() {

			var direction = '',
					parent       = $(this).parents('.category__list:first'),
					id 					 = parent.attr('data-id'),
					siblingId    = '';

			if ($(this).attr('class') == 'motion__up-category') {
				direction = 'up';
				siblingId = parent.prev().attr('data-id');
			} else if($(this).attr('class') == 'motion__down-category') {
				direction = 'down';
				siblingId = parent.next().attr('data-id');
			}

			var data = {
				nameOfAction: 'move',
				id: id,
				direction: direction,
				siblingId: siblingId,
				openedIds: sort($('.category__list'))
			}
			ajx(data);
		}

		// end structureMovements category

		// edit name category

		// var oldNameCategory = '';

		function editing() {

			var nameCategory = $(this).siblings('.name-category').text();
			// oldNameCategory = nameCategory.toLowerCase();

			// проверяем на который элемент пришелся клик
			if ($('.editing-category:visible').length > 0 && !checkIds($(this), '.editing-category')) {// если кликнули на любой другой, но не открытый элемент
				return alertMessage($(this), 'edit');// вызываем функцию с ошибкой и выходим с функции
			}

			$(this).before('<form class="editing-form">\
					<input type="text" class="editing-category">\
					<span class="btn-save-rename" title="Сохранить">✔</span>\
					<span class="btn-close-rename" title="Отменить">✘</span>\
				</form>');
			$(this).siblings('.editing-form').children('input').focus().val(nameCategory);
			$(this).parent().css('box-shadow', '2px 2px 0px red');

		};

		function deleteEditing(el) {

			el.parents('.category__list-block:first').removeAttr('style');
			el.parent().remove();

		};

		$('.category').on('click', '.edit-category', editing);// апендим инпут для изминения названия имени категории

		$('.category').on('click', '.btn-save-rename', function() {

			var newNameCategory = $(this).siblings('.editing-category').val(),
					oldNameCategory = $(this).parent().siblings('.name-category').text().toLowerCase();

			// validation
			for (var i = 0; i < nameCategoryArr.length; i++) {
				if(nameCategoryArr[i] == newNameCategory.toLowerCase()) {// проверяем вводимое значение на совпадения в массиве(переведенные в нижний регистр)
					$(this).prev().focus();
					alertMessage($(this), 'text_copy', true);
					return;
				} else if(newNameCategory == '') {// проверяем вводимое значение на пустую строку
					alertMessage($(this), 'text_null', true);
					$(this).prev().focus();
					return;
				}
			}

			delete nameCategoryArr[nameCategoryArr.indexOf(oldNameCategory)];// удаляем название старой категории из глобального массива
			nameCategoryArr.push(newNameCategory.toLowerCase());// добавляем название новой категории в глобальный массив

			$(this).parent().siblings('.name-category').text(newNameCategory);

			var data = {
				nameOfAction: 'rename',
				id: $(this).parents('.category__list:first').attr('data-id'),
				value: newNameCategory,
				openedIds: sort($('.category__list'))
			};

			deleteEditing($(this));
			ajx(data);

		});

		$('.category').on('click', '.btn-close-rename', function(){
			deleteEditing($(this));
		});

	});
	// end edit name category


});