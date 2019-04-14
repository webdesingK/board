$(document).ready(function(){

	// category
	$(function() {
		
		function addCategory() {

			// добовляем форму 
			$(this).parent().after('<form class="form-add">\
					<input type="text" class="input-text" placeholder="Имя категории">\
					<button class="submit-btn">Сохранить</button></form>');

			// добавляем класс для анимации после динамического добавления элемента (form)
			setTimeout(function() {
				$('.form-add').addClass('visible');
			},25);

			// отменяем событие click на кнопке - добавления категории (+)
			$('.category').off('click', '.add-category', addCategory);
			
		};
		
		// делегированное события клика на динамически добаленого элемента
		$('.category').on('click', '.add-category', addCategory);
		
		function categoryAjax(evt) {

			evt.preventDefault();//отменяем стандартное поведение кнопки отправки формы на сервер 

			var name = $(this).siblings('.input-text').val();// считываем с инпута содержимое
			var id   = $(this).parents('.category__list').data('id');// считываем атрибут id с родительского элемента

			var data = {
				name: name,// значение инпута
				id: id// значение атрибута id
			};

			// отправляем ajax запрос на сервер
			$.ajax({
				type: 'POST',
				data: data
			});

			$('.form-add').remove();// удаляем форму после отправки запроса на сервер
			$('.category').on('click', '.add-category', addCategory);// возобновляем событие click на кнопку добавить категорию (+)

		};

		// при клике на кнопку вызываем функцию отправки формы на сервер
		$('.category').on('click', 'button', categoryAjax);
	
});

});