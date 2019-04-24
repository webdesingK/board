$(document).ready(function () {

	// category
	$(function () {

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

		function ajx(obj, numm) {

			$.ajax({
				type: 'POST',
				data: obj,
				success: function(resp) {
					$('.category__main').html(resp);
					tab($('.category__list'));
					delLastBorder($('.category__list'));
				},
				error: function(resp) {
					alert('Ошибка сервера');
				}
			});

		}

		function checkbox() {

			var active;

			if ($(this).attr('data-active') == 1) {
				$(this).attr('title', 'Активировать?');
				$(this).attr('data-active', 0);
				active = 0;
			} else{
				$(this).attr('title', 'Деактивировать?');
				$(this).attr('data-active', 1);
				active = 1;
			}

			var data = {

				nameOfAction: 'active',
				id: $(this).parents('.category__list').data('id'),
				active: active

			};

			$.post('admin/tree-manager',data);

		};

		$('.checkbox').change(checkbox);

		function categoryAjax(evt) {

			evt.preventDefault(); //отменяем стандартное поведение кнопки отправки формы на сервер 

			var name = $(this).siblings('.input-text').val(); // считываем с инпута содержимое
			var id = $(this).parents('.category__list').data('id'); // считываем атрибут id с родительского элемента

			var data = {
				nameOfAction: 'create', 
				name: name, // значение инпута
				id: id, // значение атрибута id
				arrId: sort($('.category__list')) // сортировка открытых элементов
			};

			// отправляем ajax запрос на сервер
			ajx(data);

		};

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

		// при клике на кнопку вызываем функцию отправки формы на сервер
		$('.category').on('click', 'button', categoryAjax);

		// nested
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

		// tabs
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



	});

});