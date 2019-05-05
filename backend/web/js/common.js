$(document).ready(function () {

	// category
	$(function () {

		// add form ----------------------------------------------
		function addCategory() {

			if ($(this).text() == '+') {

				$(this).html('&minus;');
				// добовляем форму
				$(this).parent().after('<form class="form-add">\
						<input type="text" class="input-text" placeholder="Имя категории">\
						<button class="submit-btn">Сохранить</button></form>');

				$('.input-text').focus();

			} else{

				$(this).parent().siblings('.form-add').remove();
				$(this).html('&plus;');

			}

			// добавляем класс для анимации после динамического добавления элемента (form)
			setTimeout(function () {
				$('.form-add').addClass('visible');
			}, 25);

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
		function ajx(obj, numm) {

			$.ajax({
				type: 'POST',
				data: obj,
				success: function(resp) {
					$('.category__main').html(resp);
					delLastBorder($('.category__list'));
					tab($('.category__list'));
				},
				error: function(resp) {
					alert('Ошибка сервера');
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
					nameOfAction;

			// если активируем дочерний checkbox значит циклом активируем родительские checkbox
			parents.each(function(){

				if (!$(this).prop('checked')) {// проверяем если родитель не активный только тогда делаем его активным

					$(this).prop('checked', true).attr(mainActive);// делаем родитей активными
					checkedArr.push($(this).parents('.category__list:first').attr('data-id'));// добавляем id родителей checkbox в массив

				}

			});

			// делаем проверку checkbox на котором произошло изминение
			if (self.prop('checked')) {
				nameOfAction = 'active';
				self.attr(mainActive);
				self.parents('.category__list:first').prev().children('.checkbox').prop('checked', true).attr(mainActive);

			} else{
				nameOfAction = 'deactive';
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

				nameOfAction: nameOfAction,
				ids: checkedArr

			};

			// отправка на сервер
			$.post('admin/categories-manager',data);

		};

		$('.category').on('change', '.checkbox', checkbox);

		//  end checkbox change -----------------------------------

		// validation input ---------------------------------------

		var nameCategoryArr = [],
				nameCategory    = $('.name-category');

		nameCategory.each(function(){
			var text = $(this).text();
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
				if(nameCategoryArr[i] == name) {
					$(this).prev().focus();
					$(this).parent().attr('data-error', 'смотри что пишешь 🚫')
					.addClass('form-error');
					return;
				} else if(name == '') {
					$(this).parent().attr('data-error', 'напиши хоть что-нибудь 🚫')
					.addClass('form-error');
					$(this).prev().focus();
					return;
				}
			}

			// добавляем в масив вновь созданную категорию для валидации
			nameCategoryArr.push(name);

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
		$('.category').on('click', 'button', categoryAjax);

		// end add category ajax ----------------------------------

		// delete category ajax ----------------------------------
		// отправка ajax запроса на удаления категории и если есть в ней подкотегории
		$('.category').on('click', '.del-category', function() {

			var id = $(this).parents('.category__list').data('id');
			var data = {
				nameOfAction: 'delete',
				id: id,
				arrId: sort($('.category__list'), id) // сортировка открытых элементов
			};

			var numm = $(this).parents('.category__list').data('id');

			ajx(data, numm);

		});

		//  end category ajax ------------------------------------

		// nested ------------------------------------------------
		var nestedList = $('.category__list');

		function delLastBorder(arr) {

			for (var i = 0; i < arr.length; i++) {

				if(arr.eq(i).children('.category__list').length == 0) {
					arr.eq(i).addClass('after_none');
					arr.eq(i).find('.tabs-category').remove();
				}

				arr.eq(i).children('.category__list:last').addClass('after_hide');

			}

		};
		delLastBorder(nestedList);

		// end nested ------------------------------------------

		// tabs ------------------------------------------------
		var tabs = $('.tabs-category');

		$('.category').on('click', '.tabs-category' ,function() {

			$('.add-category').each(function(index){

				if ($(this).parent().siblings('.form-add').hasClass('visible')) {
					$(this).html('&plus;');
				}

			});

			$('.form-add').remove();

			for (var i = 0; i < tabs.length; i++) {

				if ($(this).parent().siblings().eq(i).find('.tabs-category').html() == '▼') {
					$(this).parent().siblings().eq(i).find('.tabs-category').click();
				}

			}
			if ($(this).html() == '▶') {
				$(this).html('▼').attr('title', 'Свернуть');
				$(this).parents('.category__list').addClass('add-before');
			} else{
				$(this).html('▶').attr('title', 'Развернуть');
				$(this).parent().parent().removeClass('add-before');
			}
			$(this).parent().siblings().toggleClass('none');

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

	});

});