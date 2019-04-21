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

		function categoryAjax(evt) {

			evt.preventDefault(); //отменяем стандартное поведение кнопки отправки формы на сервер 

			var name = $(this).siblings('.input-text').val(); // считываем с инпута содержимое
			var id = $(this).parents('.category__list').data('id'); // считываем атрибут id с родительского элемента

			var data = {
				nameOfAction: 'create', 
				name: name, // значение инпута
				id: id // значение атрибута id
			};

			// отправляем ajax запрос на сервер
			$.ajax({
				type: 'POST',
				data: data,
				success: function(resp) {
					$('.category__main').html(resp);
					delLastBorder($('.category__list'));
				}
			});

		};

		$('.category').on('click', '.del-caategory', function() {

			var id = $(this).parents('.category__list').data('id');
			var data = {
				nameOfAction: 'delete',
				id: id
			};

			$.ajax({
				type: 'POST',
				data: data,
				success: function(resp) {
					$('.category__main').html(resp);
					delLastBorder($('.category__list'));
				}
			});

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

			$('.form-add').remove();

			for (var i = 0; i < tabs.length; i++) {

				if ($(this).parents('.category').siblings().eq(i).find('.add-category').html() == '-') {
					console.log(i)
				}

				if ($(this).parent().siblings().eq(i).find('.tabs-category').html() == '▲') {
					$(this).parent().siblings().eq(i).find('.tabs-category').click();
				}

			}
			if ($(this).html() == '▼') {
				$(this).html('▲').attr('title', 'Свернуть');
				$(this).parents('.category__list').addClass('add-before');
			} else{
				$(this).html('▼').attr('title', 'Развернуть');
				$(this).parent().parent().removeClass('add-before');
			}
			$(this).parent().siblings().toggleClass('none');

		});

		function tab(arr) {

			for (var i = 0; i < arr.length; i++) {
				if(arr.eq(i).html() == '▼'){
					arr.eq(i).parents('.category__list').addClass('add-before');
				}
			}

		};

	});

});