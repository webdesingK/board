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
				parentId: id // значение атрибута id
			};

			// отправляем ajax запрос на сервер
			$.ajax({
				type: 'POST',
				data: data,
				success: function(resp) {
					$('.category__main').html(resp);
				}
			});

			$(this).parent().siblings('.add-category').html('&plus;');
			$(this).parent().siblings('.category__list-block').children('.add-category').html('&plus;');
			$(this).parent().remove(); // удаляем форму после отправки запроса на сервер
		};

		// при клике на кнопку вызываем функцию отправки формы на сервер
		$('.category').on('click', 'button', categoryAjax);


	});

	$(function() {

	// 	var block = $('.block-js');

	// 	var data = [
	// 		{
	// 			id: '1.1',
	// 			name: 'first',
	// 			enclus: [
	// 				{
	// 					id: '1.1.1',
	// 					name: 'first-one'
	// 				},
	// 				{
	// 					id: '1.1.2',
	// 					name: 'first-twoo',
	// 					enclus: [
	// 						{
	// 							id: '1.1.3',
	// 							name: 'first-twoo-one'
	// 						}
	// 					]
	// 				}
	// 			]
	// 		},
	// 		{
	// 			id: '1.2',
	// 			name: 'twoo',
	// 			enclus: [
	// 				{
	// 					id: '1.2.1',
	// 					name: 'twoo-one'
	// 				},
	// 				{
	// 					id: '1.2.2',
	// 					name: 'twoo-twoo',
	// 					enclus: [
	// 						{
	// 							id: '1.2.2.1',
	// 							name: 'twoo-twoo-one',
	// 							enclus: [
	// 								{
	// 									id: '1.2.2.2',
	// 									name: 'twoo-twoo-one-one'
	// 								}
	// 							]
	// 						}
	// 					]
	// 				}
	// 			]
	// 		},
	// 		{
	// 			id: '1.3',
	// 			name: 'twoo',
	// 			enclus: [
	// 				{
	// 					id: '1.3.1',
	// 					name: 'twoo-one'
	// 				},
	// 				{
	// 					id: '1.3.2',
	// 					name: 'twoo-twoo',
	// 					enclus: [
	// 						{
	// 							id: '1.3.2.1',
	// 							name: 'twoo-twoo-one',
	// 							enclus: [
	// 								{
	// 									id: '1.3.2.2',
	// 									name: 'twoo-twoo-one-one'
	// 								}
	// 							]
	// 						}
	// 					]
	// 				}
	// 			]
	// 		}
	// 	];

	// 	function test (obj) {
	// 		var dataHtml = '';		
	// 		for(key in obj) {
	// 			dataHtml += `
	// 			<div class="category__list" data-id="${obj[key].id}">
	// 				<div class="category__list-block">
	// 					<span class="name-category">${obj[key].id}</span>
	// 					<span class="add-category" title="Добавить новую категорию">&plus;</span>
	// 					<span class="tabs-category" title="Развернуть">▼</span>
	// 				</div>
	// 				${obj[key].enclus !== undefined ? test(obj[key].enclus) : ''}
	// 			</div>
	// 			`;
	// 		}
	// 		return dataHtml;
	// 	};

	// 	block.html(
	// 		`<div class="category__list" data-id="1">
	// 				<div class="category__list-block">
	// 					<span class="name-category">1</span>
	// 					<span class="add-category" title="Добавить новую категорию">&plus;</span>
	// 					<span class="tabs-category" title="Развернуть">▼</span>
	// 				</div>
	// 				${test(data)}
	// 			</div>
	// 			`);

		// nested
		var nestedList = $('.category__list');

		(function delLastBorder(arr) {

			for (var i = 0; i < arr.length; i++) {

				if(arr.eq(i).children('.category__list').length == 0) {
					arr.eq(i).addClass('after_none');
					arr.eq(i).find('.tabs-category').remove();
				}

				arr.eq(i).children('.category__list:last').addClass('after_hide');

			}

		})(nestedList);

		$('.category__list:not(:first)').addClass('none');
		// tabs
		var tabs = $('.tabs-category');

		tabs.on('click', function() {

			for (var i = 0; i < tabs.length; i++) {

				if ($(this).parent().siblings().eq(i).find('.tabs-category').html() == '▲') {
					$(this).parent().siblings().eq(i).find('.tabs-category').click();
				}

			}
			if ($(this).html() == '▼') {
				$(this).html('▲').attr('title', 'Свернуть');
			} else{
				$(this).html('▼').attr('title', 'Развернуть');
			}
			$(this).parent().siblings().toggleClass('none');

		});
	});

});