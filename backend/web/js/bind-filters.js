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

	function ajax(data, callbackFunction) {
		let xhr = new XMLHttpRequest();
		xhr.open('POST', 'привязка-фильтров');
		xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
		xhr.setRequestHeader('Content-type', 'application/json');
		xhr.send(JSON.stringify(data));

		xhr.onreadystatechange = function() {
			if (xhr.readyState != 4) return;
			if (xhr.status == 200) {
				let resp = JSON.parse(xhr.responseText);
				callbackFunction({
					status: resp.status, 
					text: resp.text,
					responseText: resp
				});
			}
			else {
				btnFlag = true;
				outputings('danger', 'Проблема с получение данных с сервера');
			}
		}
	};

	// вывод сообщения
	function outputings(outp, text) {
		message.className = message.className.replace(/alert-[\w]*/gi, '');
		message.classList.add('alert-' + outp);// и добавляем класс 'danger'
		message.querySelector('span').innerText = text;// и изминяем текст в блоке информации о предуприждении
	};

	// анонимная функция для получения списка option в select
	(function () {
		let data = {requestId: 'getAllFilterNames'};
		function getAllFilterNames(obj) {
			listFilter = obj.responseText;
		}
		ajax(data, getAllFilterNames);
	})();

	function getBondedFilters(obj) {
		if (obj.responseText) {
			addList.innerHTML = obj.responseText;
		} else{
			count = 1;
			addList.innerHTML = inputList(count);
			outputings('info', 'У этой категории нет привязанных фильтров');
		}
	};

	select.addEventListener('change', function() {
		select.parentNode.classList.remove('has-error');
		outputings('info', ' Обратите внимание на правильность заполнения полей');
		let data = {
			requestId: 'getBondedFilters',
			idCategory: select.value
		};
		ajax(data, getBondedFilters);
	});

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
		let tableList = addList.querySelectorAll('tr');// записываем динамически добавленные инпуты в переменную
		for (var i = 0; i < tableList.length; i++) {// цикл для изминения счетчика при удалении какого либо пункта
			tableList[i].firstElementChild.querySelector('span').innerText = i + 1;
			count = tableList.length + 1;
		}
	});

	// при клики на кнопку добавления пунктов
	addFilter.addEventListener('click', function() {
		let tableListLength = addList.querySelectorAll('tr').length;
		count = tableListLength + 1;
		addList.insertAdjacentHTML('beforeEnd', inputList(count));// вставляем в блок инпут для добавления пункта 
		count++;// и увеличиваем счетчик на 1
	});

	function ifSelected() {
		if (select.value == select.firstElementChild.value) {
			select.parentNode.classList.add('has-error');
			outputings('warning', 'Выбирите категорию');
			return true;
		} else{
			select.parentNode.classList.remove('has-error');
			outputings('info', 'Обратите внимание на правильность заполнения полей');
			return false;
		}
	}

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

	function saveBondedFilters(obj) {
		if (obj.status) {
			succes(obj.text);
		} else{
			outputings('danger', obj.text);
		}
	}

	btnSave.addEventListener('click', function() {
		if (!btnFlag) return// проверка для избежания многократного клика на кнопку 'сохранить'
		if (ifSelected()) return;
		let tableList = addList.querySelectorAll('tr');// записываем динамически добавленные инпуты в переменную
		for (var i = 0; i < tableList.length; i++) {// цикл
			if (!tableList[i].querySelector('input').value) {
				tableList[i].querySelector('input').parentNode.classList.add('has-error');
				outputings('warning', ' У Вас не заполнен URL');// сообщаем об этом
				btnFlag = true;
				return;// и останавливает далнейшее действвие кода
			} else{
				tableList[i].querySelector('input').parentNode.classList.remove('has-error');
			}
			if (tableList[i].querySelector('select').value == tableList[i].querySelector('select').firstElementChild.value) {
				tableList[i].querySelector('select').parentNode.classList.add('has-error');
				outputings('warning', ' У Вас не выбран фильтр');// сообщаем об этом
				btnFlag = true;
				return;
			} else{
				tableList[i].querySelector('select').parentNode.classList.remove('has-error');

			}
		}
		if (addList.querySelectorAll('tr').length == 0) {// при сохранении, если ни добавили ни одного пункта 
			outputings('warning', ' У Вас нет ни одного пункта');// сообщаем об этом
			btnFlag = true;
			return;
		}
		let arr            = {},
				arrList        = addList.querySelectorAll('tr'),
				selectCategory = select.value.trim(),
				data           = {};

		for (let i = 0; i < arrList.length; i++) {
			let url  = arrList[i].querySelector('input').value,
					value = arrList[i].querySelector('select').value;
					arr[url] = value;
		}
		data.requestId     = 'saveBondedFilters';
		data.categoryId    = selectCategory;
		data.bondedFilters = arr;
		ajax(data, saveBondedFilters);
	});

})()