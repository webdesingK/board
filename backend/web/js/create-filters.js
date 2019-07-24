(function () {
	
	let btnSave    = document.querySelector('#btn-save-js'),// кнопка сохранения
			addFilter  = document.querySelector('#filter__add-js'),// кнопка добавления пунктов
			addList    = document.querySelector('#add__list-js'),// блок куда вставлять новый инпут для записи пункта
			tableName  = document.querySelector('#table__name-js'),// инпут для ввода название таблицы
			tableUrl   = document.querySelector('#table__url-js'),// инпут для ввода название таблицы
			selectCat  = document.querySelector('#select-catigories'),// инпут для ввода название таблицы
			message    = document.querySelector('#message-js'),// блок для вывода информации
			count      = 1,// счетчик для нумерации пункта фильтра 
			btnFlag    = true;// флаг для избежания повторного клика на кнопку сохранения

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

	// при клики на кнопку добавления пунктов
	addFilter.addEventListener('click', function() {
		addList.insertAdjacentHTML('beforeEnd', inputList(count));// вставляем в блок инпут для добавления пункта 
		count++;// и увеличиваем счетчик на 1
	});

	// функция для обнуления счетчика при клике на удаления инпутов добавления пунктов
	function zeroing() {// с одним параметров(родительский элемент)
		if (addList.querySelectorAll('.input-group').length == 0) {// проверяем если у родителя нет дочерних инпутов
			count = 1;// тогда обнуляем счетчик
		}		
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
		let tableList = addList.querySelectorAll('.input-group');// записываем динамически добавленные инпуты в переменную
		for (var i = 0; i < tableList.length; i++) {// цикл для изминения счетчика при удалении какого либо пункта
			tableList[i].firstElementChild.innerText = i + 1;
			count = tableList.length + 1;
		}
	});

	// вывод сообщения
	function outputings(outp, text) {
		message.className = message.className.replace(/alert-[\w]*/gi, '');
		message.classList.add('alert-' + outp);// и добавляем класс 'danger'
		message.querySelector('span').innerText = text;// и изминяем текст в блоке информации о предуприждении
	};

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
		if (this.getAttribute('id') == 'table__name-js'// если 'keyup' происходит на инпуте с название таблицы
		 && addList.querySelectorAll('tr').length == 0) {// и нет ни одного инпута для добавления пунктов
			addFilter.click();// тогда имитируем клик для добавления одного инпута пунктов
		}
	};

	tableName.addEventListener('keyup', ifChangesInput);// запускаем событие на проверку инпута название таблицы

	function success(text) {
		outputings('success', text);
		tableName.value = '';
		tableName.addEventListener('keyup', ifChangesInput);// запускаем событие 'keyup'
		btnFlag = true;
		let arr = addList.querySelectorAll('tr');
		for (let i = 0; i < arr.length; i++) {
			arr[i].remove();
		}
		zeroing();
	};

	function ajaxCallback(status, text) {
		if (status) {
			success(text);
		} else{
			outputings('danger', text);
			btnFlag = true;
		}
	}

	function ajax(data, callbackFunction) {
		let xhr = new XMLHttpRequest();
		xhr.open('POST', 'создание-фильтров');
		xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
		xhr.setRequestHeader('Content-type', 'application/json');
		xhr.send(JSON.stringify(data));

		xhr.onreadystatechange = function() {
			if (xhr.readyState != 4) return;
			if (xhr.status == 200) {
				let resp = JSON.parse(xhr.responseText);
				callbackFunction(resp.status, resp.text)
			}
			else {
				btnFlag = true;
				outputings('danger', 'Проблема с получение данных с сервера');
			}
		}
	};

	// клик на кнопку сохранить
	btnSave.addEventListener('click', function() {
		if (!btnFlag) return// проверка для избежания многократного клика на кнопку 'сохранить'
		btnFlag = false;// запрещаем повторное нажатие кнопки 'сохранить'
		let data = {// переменная для передачи на сервер
			name: '',// имя название таблицы
			url: tableUrl.value,// url
			idCategory: selectCat.value,
			arrList: {}
		};
		if (!tableName.value) {// проверяем если инпут название таблицы пустой
			tableName.addEventListener('keyup', ifChangesInput);// запускаем событие 'keyup'
			return ifTablesName(tableName, ' Заполните название фильтра');// и останавливаем дальнейщего кода и выводим предупреждение о пустоте
		} else{// если инпут названия таблицы не пустой
			data.name = tableName.value;// значит записываем его в обьект для передачи на сервер
		}
		if (!tableUrl.value) {// проверяем если инпут название таблицы пустой
			tableUrl.addEventListener('keyup', ifChangesInput);// запускаем событие 'keyup'
			return ifTablesName(tableUrl, ' Заполните название URL адресса');// и останавливаем дальнейщего кода и выводим предупреждение о пустоте
		} else{// если инпут названия таблицы не пустой
			data.name = tableUrl.value;// значит записываем его в обьект для передачи на сервер
		}
		let tableList = addList.querySelectorAll('.form-control');// записываем динамически добавленные инпуты в переменную
		for (var i = 0; i < tableList.length; i++) {// цикл
			if (!tableList[i].value) {// проверка на пустоту динамически добавленных инпутов, если пусто
				ifTablesName(tableList[i], ' У Вас пустой пункт списка');// выводим сообщение о ошибке
				tableList[i].addEventListener('keyup', ifChangesInput);// запускаем событие 'keyup'
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
		ajax(data, ajaxCallback);
	});

})();
