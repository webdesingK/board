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
		xhr.setRequestHeader('Content-type', 'application/json');
		xhr.send(JSON.stringify({requestId: 'getFilters'}));

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

	select.addEventListener('change', function() {
		select.parentNode.classList.remove('has-error');
		outputings('info', ' Обратите внимание на правильность заполнения полей');
	});

	ajaxListFilter();

	function listFilters(arr) {// функция с одним параметром(массив с сервера, с названием фильтров)
		let array = '';// для вывода 
		for (let i = 0; i < arr.length; i++) { 
			array += '<option>' + arr[i] + '</option>';// конкатенируем в переменную option с названием фильтра
		}
		return array;// возвращаем строку с option и названиями фильтров
	};

	// цикл для добавления отступов в select категорий
	for (let i = 0; i < paddingLeft.length; i++) {
		let text = paddingLeft[i].innerHTML;
		paddingLeft[i].innerHTML = '&nbsp;&nbsp;' + text;
	}

	// функция для обнуления счетчика при клике на удаления инпутов добавления пунктов
	function zeroing() {// с одним параметров(родительский элемент)
		if (addList.querySelectorAll('.input-group').length == 0) {// проверяем если у родителя нет дочерних инпутов
			count = 1;// тогда обнуляем счетчик
		}		
	};

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
					<span class="input-group-addon" title='Удалить пункт'>
						<i class="glyphicon glyphicon-remove-circle text-danger"></i>
					</span>
				</div>
			</th>
		</tr>
	`;
	}

	// функция делегированного удаления инпутов добавления пунктов
	addList.addEventListener('click', function(e) {// принимаем event
		let target = e.target,// записываем в переменную елемент по которому кликнули
			  parent = target.closest('tr');// а также ищем родителя, который в дольнейшем будем удалять
		if (target.getAttribute('title') == 'Удалить пункт' || // если кликнули по тегу с title = Удалить пункт
				target.parentNode.getAttribute('title') == 'Удалить пункт') {// или кликнули по тегу у которога родитель с title = Удалить пункт
			parent.remove();// удаляем вставленый инпут
			zeroing();// и обнуляем счетчик
		}
	});

	// при клики на кнопку добавления пунктов
	addFilter.addEventListener('click', function() {
		addList.insertAdjacentHTML('beforeEnd', inputList(count));// вставляем в блок инпут для добавления пункта 
		count++;// и увеличиваем счетчик на 1
	});

	function ifSelected() {
		if (select.value == select.firstElementChild.value) {
			select.parentNode.classList.add('has-error');
			outputings('danger', 'Выбирите категорию');
			return true;
		} else{
			select.parentNode.classList.remove('has-error');
			outputings('info', 'Обратите внимание на правильность заполнения полей');
			return false;
		}
	}

	btnSave.addEventListener('click', function() {
		if (!btnFlag) return// проверка для избежания многократного клика на кнопку 'сохранить'
		if (ifSelected()) return;
		let tableList = addList.querySelectorAll('tr');// записываем динамически добавленные инпуты в переменную
		for (var i = 0; i < tableList.length; i++) {// цикл
			if (!tableList[i].querySelector('input').value) {
				tableList[i].querySelector('input').parentNode.classList.add('has-error');
				outputings('danger', ' У Вас не заполнен URL');// сообщаем об этом
				btnFlag = true;
				return;// и останавливает далнейшее действвие кода
			} else{
				tableList[i].querySelector('input').parentNode.classList.remove('has-error');
				outputings('info', ' Обратите внимание на правильность заполнения полей');
			}
			if (tableList[i].querySelector('select').value == tableList[i].querySelector('select').firstElementChild.value) {
				tableList[i].querySelector('select').parentNode.classList.add('has-error');
				outputings('danger', ' У Вас не выбран фильтр');// сообщаем об этом
				btnFlag = true;
				return;
			} else{
				tableList[i].querySelector('select').parentNode.classList.remove('has-error');
				outputings('info', ' Обратите внимание на правильность заполнения полей');

			}
		}
		if (addList.querySelectorAll('tr').length == 0) {// при сохранении, если ни добавили ни одного пункта 
			outputings('danger', ' У Вас нет ни одного пункта');// сообщаем об этом
			btnFlag = true;
			return;
		} else{
			outputings('info', ' Обратите внимание на правильность заполнения полей');
		}
		let arr            = [],
				arrList        = addList.querySelectorAll('tr'),
				selectCategory = select.value.trim(),
				data           = {};

		for (let i = 0; i < arrList.length; i++) {
			let name  = arrList[i].querySelector('input').value,
					value = arrList[i].querySelector('select').value,
					total = [name, value];
					arr.push(total);
		}
		data.push(['idCategory', selectCategory],arr);
		console.log(data)
			ajax(data);
	});

	function succes(text) {
		outputings('success', text);
		tableName.value = '';
		btnFlag = true;
		let arr = addList.querySelectorAll('tr');
		for (let i = 0; i < arr.length; i++) {
			arr[i].remove();
		}
		zeroing();
	};

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
				let resp = JSON.parse(xhr.responseText);
				if (resp.status) {
					succes(resp.text);
				} else{
					outputings('danger', resp.text)
				}
			}
			else {
				btnFlag = true;
				outputings('danger', 'Проблема с получение данных с сервера');
			}
		}
	};

})()