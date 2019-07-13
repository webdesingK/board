(function () {
	
	let paddingLeft = document.querySelectorAll('.pl'),// padding left для вложенных категорий 3 уровня
			select      = document.querySelector('#select-categories-js'),// выбор фильтров
			btnSave     = document.querySelector('#btn-save-js'),// кнопка сохранения
			addList     = document.querySelector('#add-list-js'),// блок куда вставлять новый инпут для записи пункта
			addFilter   = document.querySelector('#add-filters-js'),// кнопка добавления пунктов
			message     = document.querySelector('#message-js'),// блок для вывода информации
			count       = 1,// счетчик для нумерации пункта фильтра 
			btnFlag     = true,// флаг для избежания повторного клика на кнопку сохранения
			listFilter;// для записи массива полученных с сервера наименования фильтров

	// вывод сообщения
	function outputings(outp, text) {
		message.classList.remove('alert-info', 'alert-success', 'alert-danger');// удаляем кланс 'info' 'succes'
		message.classList.add('alert-' + outp);// и добавляем класс 'danger'
		message.querySelector('span').innerText = text;// и изминяем текст в блоке информации о предуприждении
	};

	function ajaxListFilter() {
		let xhr = new XMLHttpRequest();
		xhr.open('POST', 'привязка-фильтров');
		xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
		xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		xhr.send('value=listFilter');

		xhr.onreadystatechange = function() {
			if (xhr.readyState != 4) return;
			if (xhr.status == 200) {
				listFilter = JSON.parse(xhr.responseText);
			}
			else {
				outputings('danger', 'Проблема с сервером, не получили данные о массиве фильтров');
			}
		}
	};

	ajaxListFilter();

	function listFilters(arr) {
		let array = '';
		for (let i = 0; i < arr.length; i++) {
			array += '<option>' + arr[i] + '</option>'
		}
		return array;
	};

	for (let i = 0; i < paddingLeft.length; i++) {
		let text = paddingLeft[i].innerHTML;
		paddingLeft[i].innerHTML = '&nbsp;&nbsp;' + text;
	}

	function inputList(count) {
		return `
		<tr>
			<th>
				<div class="input-group">
					<span class="input-group-addon">${count}</span>
					<input type="text" class="form-control">
				</div>
			</th>
			<th>
				<div class="input-group">
					<select class="form-control" id="select-filters-js">
						<option disabled="disabled" selected="selected">Выбрать фильтр</option>
						${listFilters(listFilter)}
					</select>
					<span class="input-group-addon">
						<i class="glyphicon glyphicon-remove-circle text-danger"></i>
					</span>
				</div>
			</th>
		</tr>
	`;
	}

	addList.addEventListener('click', function(e) {
		let target = e.target;
		let parent = target.closest('tr');
		if (target.getAttribute('class') == 'input-group-addon') {
			parent.remove();
		} 
		if(target.parentNode.getAttribute('class') == 'input-group-addon') {
			parent.remove();
		}
	});

	addFilter.addEventListener('click', function() {
		addList.insertAdjacentHTML('beforeEnd', inputList(count));
		count++;
	});

	btnSave.addEventListener('click', function() {
		let arr            = [],
				arrList        = addList.querySelectorAll('tr'),
				selectCategory = select.value.trim(),
				data           = [];

		for (let i = 0; i < arrList.length; i++) {
			let name  = arrList[i].querySelector('input').value,
					value = arrList[i].querySelector('select').value,
					total = [name, value];
					arr.push(total);
		}
		data.push(['idCategory', selectCategory],arr)
			ajax(data);
	});

	function ajax(data) {
		let xhr = new XMLHttpRequest();
		xhr.open('POST', 'привязка-фильтров');
		xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
		xhr.setRequestHeader('Content-type', 'application/json');
		data = JSON.stringify(data);
		xhr.send(data);

		xhr.onreadystatechange = function() {
			if (xhr.readyState != 4) return;
			if (xhr.status == 200) {
				console.log(JSON.parse(xhr.responseText));
			}
			else {
				console.log('errror');
				console.log(xhr);
			}
		}
	};

})()