$(document).ready(function(){

	// category
	$(function() {

		var addBtn = $('.add-category'),
				list   = $('.category__list');
		
		addBtn.one('click', function(){

			$(this).parent().parent().append('<form class="form-add">\
					<input type="text" class="input-text" placeholder="Имя категории">\
					<button class="submit-btn">Сохранить</button></form>');
		});

	});

	$('body').on('click', 'button', function(evt){
		evt.preventDefault();
		var name = $(this).siblings('.input-text').val();
		var id   = $(this).parents('.category__list').data('id');
		var data = {
			name: name,
			id: id
		};
		$.ajax({
			type: 'POST',
			data: data
		});
		$('.form-add').remove();
	});


});