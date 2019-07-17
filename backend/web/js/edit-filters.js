(function () {
	
	let btnSave   = document.querySelector('#btn-save-js'),// кнопка сохранения
	    btnDelete = document.querySelector('#btn-delete-js'),// кнопка сохранения
			addFilter = document.querySelector('#filter__add-js'),// кнопка добавления пунктов
			select    = document.querySelector('#select-categories-js'),// выбор фильтров
			addList   = document.querySelector('#add__list-js'),// блок куда вставлять новый инпут для записи пункта
			tableName = document.querySelector('#table__name-js'),// инпут для ввода название таблицы
			message   = document.querySelector('#message-js'),// блок для вывода информации
			count     = 1,// счетчик для нумерации пункта фильтра 
			btnFlag   = true;// флаг для избежания повторного клика на кнопку сохранения

	function inputList(count) {// фнкция которая возвращает вставляющий инпут,
	// с передаваемым параметром счетчика нумерации пунктов фильтра
		return `
		<tr>
		  <td>
				<div class="input-group">
					<span class="input-group-addon">${count}</span>
					<input type="text" class="form-control">
					<span class="input-group-addon" title="Удалить пункт"><i class="glyphicon glyphicon-remove-circle text-danger"></i></span>
				</div>
		  </td>
		</tr>
	`;
	}

	function ajax(data, callbackFunction) {
		let xhr = new XMLHttpRequest();
		xhr.open('POST', 'редактирование-фильтров');
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

	function ifSelected() {
		if (select.value == select.firstElementChild.value) {
			select.parentNode.classList.add('has-error');
			outputings('warning', 'Выбирите фильтр');
			return true;
		} else{
			select.parentNode.classList.remove('has-error');
			outputings('info', 'Обратите внимание на правильность заполнения полей');
			return false;
		}
	};

	// прием данных и их проверка при выборе фильтров в select
	function nameFilters(obj) {
		if (obj.status) {
			addList.innerHTML = obj.text;
		} else{
			count = 1;
			addList.innerHTML = inputList(count);
		}
	};

	select.addEventListener('change', function() {
		select.parentNode.classList.remove('has-error');
		outputings('info', ' Обратите внимание на правильность заполнения полей');
		let data = {
			requestId: 'getFilterTitles',
			nameFilter: select.value
		};
		ajax(data, nameFilters);
	});

	// функция для обнуления счетчика при клике на удаления инпутов добавления пунктов
	function zeroing() {// с одним параметров(родительский элемент)
		if (addList.querySelectorAll('.input-group').length == 0) {// проверяем если у родителя нет дочерних инпутов
			count = 1;// тогда обнуляем счетчик
		}		
	};

	// при клики на кнопку добавления пунктов
	addFilter.addEventListener('click', function() {
		let tableListLength = addList.querySelectorAll('tr').length;
		count = tableListLength + 1;
		addList.insertAdjacentHTML('beforeEnd', inputList(count));// вставляем в блок инпут для добавления пункта 
		count++;// и увеличиваем счетчик на 1
	});

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

	function ifTablesName(el, text) {// функция проверки на пустоту инпута
		if (!el.value) {// если инпут пустой
			el.parentNode.classList.add('has-error');// добавляем класс error
			outputings('warning',text);
			btnFlag = true;// и разрешаем кнопке сохранить отработать еще раз
		} else{// если инпут не пустой
			el.parentNode.classList.remove('has-error');// удаляем класс error
			outputings('info', text);
			btnFlag = true;// и разрешаем кнопке сохранить отработать еще раз
		}
	};

	// функция на проверку заполнения инпута при 'keyup'
	function ifChangesInput() {
		if (this.value) {// если не пустой
			ifTablesName(this, ' Обратите внимание на правильность заполнения полей');// запускаем функцию на возврат начальных значений 
			this.removeEventListener('keyup', ifChangesInput);// и останавливаем событие
		}
	};

	function succes(text) {
		outputings('success', text);
		select.value = 'Выбрать фильтр';
		btnFlag = true;
		let arr = addList.querySelectorAll('tr');
		for (let i = 0; i < arr.length; i++) {
			arr[i].remove();
		}
		zeroing();
	};

	function changeFilter(obj) {
		if (obj.status) {
			succes(obj.text);
		} else{
			btnFlag = true;
			outputings('danger', obj.text);
		}
	}

	// клик на кнопку сохранить
	btnSave.addEventListener('click', function() {
		if (!btnFlag) return// проверка для избежания многократного клика на кнопку 'сохранить'
		
		btnFlag = false;// запрещаем повторное нажатие кнопки 'сохранить'
		
		if (ifSelected()) return;

		if (addList.querySelectorAll('tr').length == 0) {// при сохранении, если ни добавили ни одного пункта 
			outputings('warning', ' У Вас нет ни одного пункта');// сообщаем об этом
			btnFlag = true;
			return;
		}
		let data = {// переменная для передачи на сервер
			requestId: 'editFilter',
			name: select.value,// имя название фильтра
			arrList: {}
		};
		let tableList = addList.querySelectorAll('.form-control');// записываем динамически добавленные инпуты в переменную
		for (var i = 0; i < tableList.length; i++) {// цикл
			if (!tableList[i].value) {// проверка на пустоту динамически добавленных инпутов, если пусто
				ifTablesName(tableList[i], ' У Вас пустой пункт списка');// выводим сообщение о ошибке
				tableList[i].addEventListener('keyup', ifChangesInput);// запускаем событие 'keyup'
				btnFlag = true;
				return;// и останавливает далнейшее действвие кода
			} else{// если не пустой инпут
				tableList[i].removeEventListener('keyup', ifChangesInput);// и останавливаем событие
				data.arrList[i] = tableList[i].value;// и записываем в переменную для передачи на сервер
			}
		}
		if (addList.querySelectorAll('tr').length == 0) {// при сохранении, если ни добавили ни одного пункта 
			outputings('danger', ' У Вас нет ни одного пункта');// сообщаем об этом
			btnFlag = true;
			return;
		}
		ajax(data, changeFilter);
	});

	function deleteFilters(obj) {	
		let index = select.options.selectedIndex;
		if (obj.status) {
			select.querySelectorAll('option')[index].remove();
			select.value = 'Выбрать фильтр';
			addList.innerHTML = '';
			outputings('success', obj.text);			
		} else{
			btnFlag = true;
			outputings('danger', obj.text);
		}
	};

	btnDelete.addEventListener('click', function () {
		if (ifSelected()) return;
		let data = {
			requestId: 'deleteFilter',
			nameFilter: select.value
		}
		ajax(data, deleteFilters);
	});

})();
