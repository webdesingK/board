$(document).ready(function () {

	// category
	$(function () {

		function addCategory() {

			// добовляем форму 
			$(this).parent().after('<form class="form-add">\
					<input type="text" class="input-text" placeholder="Имя категории">\
					<button class="submit-btn">Сохранить</button></form>');

			// добавляем класс для анимации после динамического добавления элемента (form)
			setTimeout(function () {
				$('.form-add').addClass('visible');
			}, 25);

			// отменяем событие click на кнопке - добавления категории (+)
			$('.category').off('click', '.add-category', addCategory);

		};

		// делегированное события клика на динамически добаленого элемента
		$('.category').on('click', '.add-category', addCategory);

		function categoryAjax(evt) {

			evt.preventDefault(); //отменяем стандартное поведение кнопки отправки формы на сервер 

			var name = $(this).siblings('.input-text').val(); // считываем с инпута содержимое
			var id = $(this).parents('.category__list').data('id'); // считываем атрибут id с родительского элемента

			var data = {
				name: name, // значение инпута
				id: id // значение атрибута id
			};

			// отправляем ajax запрос на сервер
			$.ajax({
				type: 'POST',
				data: data
			});

			$('.form-add').remove(); // удаляем форму после отправки запроса на сервер
			$('.category').on('click', '.add-category', addCategory); // возобновляем событие click на кнопку добавить категорию (+)

		};

		// при клике на кнопку вызываем функцию отправки формы на сервер
		$('.category').on('click', 'button', categoryAjax);

	});

	$(function() {

		var block = $('.block-js');

		var data = [
			{
				id: '1.1',
				name: 'first',
				enclus: [
					{
						id: '1.1.1',
						name: 'first-one'
					},
					{
						id: '1.1.2',
						name: 'first-twoo',
						enclus: [
							{
								id: '1.2.1',
								name: 'first-twoo-one'
							}
						]
					}
				]
			},
			{
				id: '1.2',
				name: 'twoo',
				enclus: [
					{
						id: '1.2.1',
						name: 'twoo-one'
					},
					{
						id: '1.2.2',
						name: 'twoo-twoo',
						enclus: [
							{
								id: '1.2.2.1',
								name: 'twoo-twoo-one',
								enclus: [
									{
										id: '1.2.2.2',
										name: 'twoo-twoo-one-one'
									}
								]
							}
						]
					}
				]
			},
			{
				id: '1.2',
				name: 'twoo',
				enclus: [
					{
						id: '1.2.1',
						name: 'twoo-one'
					},
					{
						id: '1.2.2',
						name: 'twoo-twoo',
						enclus: [
							{
								id: '1.2.2.1',
								name: 'twoo-twoo-one',
								enclus: [
									{
										id: '1.2.2.2',
										name: 'twoo-twoo-one-one'
									}
								]
							}
						]
					}
				]
			}
		];

		function test (obj) {
			var dataHtml = '';		
			for(key in obj) {
				dataHtml += `
				<div class="category__list" data-id="${obj[key].id}">
					<div class="category__list-block">
						<span class="name-category">${obj[key].id}</span>
						<span class="add-category">&plus;</span>
					</div>
					${obj[key].enclus !== undefined ? test(obj[key].enclus) : ''}
				</div>
				`;
			}
			return dataHtml;
		};

		block.html(
			`<div class="category__list" data-id="1">
					<div class="category__list-block">
						<span class="name-category">1</span>
						<span class="add-category">&plus;</span>
					</div>
					${test(data)}
				</div>
				`);

		var catList = $('.category__list');

		(function delLastBorder(arr) {

			for (var i = 0; i < arr.length; i++) {
				if(arr.eq(i).children('.category__list').length == 0) {
					arr.eq(i).addClass('after_none');
				}
				arr.eq(i).children('.category__list:last').addClass('after_hide');
			}

		})(catList);

	});

});