(function () {
	
  let	select       = document.querySelectorAll('.select'),// выбор фильтров
			btnSave      = document.querySelector('#btn-save-js'),// кнопка сохранения
			addList      = document.querySelector('#add-list-js'),// блок куда вставлять новый инпут для записи пункта
			addFilter    = document.querySelector('#add-filters-js'),// кнопка добавления пунктов
			message      = document.querySelector('#message-js'),// блок для вывода информации
			count        = 1,// счетчик для нумерации пункта фильтра 
			animClass    = 'none',
			filterFlag   = false,
			btnFlag      = true,// флаг для избежания повторного клика на кнопку сохранения
			idsFilter,// для записи массива полученных с сервера наименования фильтров
			listFilter;// для записи массива полученных с сервера наименования фильтров

	function ajax(data, callbackFunction, index) {
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
					namesOptions: resp.namesOptions,
					idsOptions: resp.idsOptions,
					index: index
				});
			}
			else {
				btnFlag = true;
				outputings('danger', 'Проблема с получение данных с сервера');
			}
		}
	};

	// вывод сообщения
	function outputings(nameClass, text) {
		message.className = message.className.replace(/alert-[\w]*/gi, '');
		message.classList.add('alert-' + nameClass);// и добавляем класс 'danger'
		message.querySelector('span').innerText = text;// и изминяем текст в блоке информации о предуприждении
	};

	function getCategoriesLvl(obj) {
		if (obj.status) {
			if (obj.index + 1 < select.length) {
				select[obj.index + 1].insertAdjacentHTML('beforeEnd', obj.text);
				select[obj.index + 1].classList.remove(animClass);				
			} else{
				if (obj.namesOptions.length != 0) {
					listFilter = obj.namesOptions;
					idsFilter = obj.idsOptions;
					btnSave.classList.remove(animClass);
					addFilter.classList.remove(animClass);
					if (obj.text) {
						addList.innerHTML = obj.text;
						filterFlag = true;
					} else{
						addList.innerHTML = inputList(count);
						count = 1;
						outputings('info', 'У этой категории нет привязанных фильтров');					
					}
				} else{
					outputings('warning', 'У этой категории нет предпологаемых фильтров');
				}
			}
		} else{
			outputings('danger', obj.text);
		}
	};

	function hideBtns() {
		addFilter.classList.add(animClass);
		addList.innerHTML = '';
		btnSave.classList.add(animClass);
	};

	function delOption(el) {
		hideBtns();
		let option = el.querySelectorAll('option');
		for (let q = 1; q < option.length; q++) {
			option[q].remove();
		}
		el.value = el.firstElementChild.value;
	}

	for (let i = 0; i < select.length; i++) {
		select[i].addEventListener('change', function() {
			outputings('info', 'Обратите внимание на правильность заполнения полей');
			if (i == 0 && !select[1].classList.contains(animClass)) {
				delOption(select[1]);
				if (!select[2].classList.contains(animClass)) {
					select[2].classList.add(animClass);
					delOption(select[2]);
				}
			} 
			if (i == 1 && !select[2].classList.contains(animClass)) {
				delOption(select[2]);
			} 
			let data = {
				requestId: 'getCategoriesLvl' + (i+2),
				idCategory: this.value,
				idParentCategory: select[0].value
			};
			if (i == select.length - 1) {
				if (btnSave.classList.contains('btn-danger')) {
					btnSave.classList.remove('btn-danger');
					btnSave.classList.add('btn-success');
					btnSave.innerText = 'Сохранить';
				}
				data.requestId = 'getBondedFilters';
			}
			ajax(data, getCategoriesLvl, i);
		});
	}

	function listFilters(arr) {// функция с одним параметром(массив с сервера, с названием фильтров)
		let array = '';// для вывода 
		for (let i = 0; i < arr.length; i++) { 
			array += '<option value="' + idsFilter[i] + '">' + arr[i] + '</option>';// конкатенируем в переменную option с названием фильтра
		}
		return array;// возвращаем строку с option и названиями фильтров
	};

	// функция для обнуления счетчика при клике на удаления инпутов добавления пунктов
	function zeroing() {// с одним параметров(родительский элемент)
		if (addList.querySelectorAll('.input-group').length == 0) {// проверяем если у родителя нет дочерних инпутов
			count = 1;// тогда обнуляем счетчик
		}		
	};

	function inputList(count) {
		return `
			<div class="input-group">
				<span class="input-group-addon">${count}</span>
				<select class="form-control select-filter">
					<option disabled="disabled" selected="selected">Выбрать фильтр</option>
					${listFilters(listFilter)}
				</select>
				<span class="input-group-addon" title='Удалить пункт'>
					<i class="glyphicon glyphicon-remove-circle text-danger"></i>
				</span>
			</div>
	 `;
	}

	// функция делегированного удаления инпутов добавления пунктов
	addList.addEventListener('click', function(e) {// принимаем event
		let target = e.target,// записываем в переменную елемент по которому кликнули
			  parent = target.closest('.input-group');// а также ищем родителя, который в дольнейшем будем удалять
		if (target.getAttribute('title') == 'Удалить пункт' || // если кликнули по тегу с title = Удалить пункт
				target.parentNode.getAttribute('title') == 'Удалить пункт') {// или кликнули по тегу у которога родитель с title = Удалить пункт
			parent.remove();// удаляем вставленый инпут
			zeroing();// и обнуляем счетчик
		}
		let tableList = addList.querySelectorAll('.input-group');// записываем динамически добавленные инпуты в переменную
		if (filterFlag && tableList.length == 0) {
			btnSave.classList.remove('btn-succes');
			btnSave.classList.add('btn-danger');
			btnSave.innerText = 'Удалить все привязки';
		}
		for (var i = 0; i < tableList.length; i++) {// цикл для изминения счетчика при удалении какого либо пункта
			tableList[i].firstElementChild.innerText = i + 1;
			count = tableList.length + 1;
		}
	});

	// при клики на кнопку добавления пунктов
	addFilter.addEventListener('click', function() {
		let tableListLength = addList.querySelectorAll('.input-group').length;
		count = tableListLength + 1;
		addList.insertAdjacentHTML('beforeEnd', inputList(count));// вставляем в блок инпут для добавления пункта 
		count++;// и увеличиваем счетчик на 1
		if (btnSave.classList.contains('btn-danger')) {
			btnSave.classList.remove('btn-danger');
			btnSave.classList.add('btn-success');
			btnSave.innerText = 'Сохранить';
		}
	});

	function saveBondedFilters(obj) {
		if (obj.status) {
			outputings('success', obj.text);
			hideBtns();
			for (let i = 0; i < select.length; i++) {
				if (i > 0) {
					select[i].classList.add(animClass);
					delOption(select[i]);
				}
				select[i].value = select[i].firstElementChild.value;
			}
			count = 1;
			btnFlag = true;
		} else{
			outputings('danger', obj.text);
			btnFlag = true;
		}
	}

	btnSave.addEventListener('click', function() {
		if (!btnFlag) return;// проверка для избежания многократного клика на кнопку 'сохранить'
		let tableList = addList.querySelectorAll('.input-group');// записываем динамически добавленные инпуты в переменную
		for (var i = 0; i < tableList.length; i++) {// цикл
			if (tableList[i].querySelector('select').value == tableList[i].querySelector('select').firstElementChild.value) {
				tableList[i].querySelector('select').parentNode.classList.add('has-error');
				outputings('warning', ' У Вас не выбран фильтр');// сообщаем об этом
				return;
			} else{
				tableList[i].querySelector('select').parentNode.classList.remove('has-error');
			}
		}
		if (filterFlag && tableList.length == 0) {
			filterFlag = false;
		}	else if (tableList.length == 0) {// при сохранении, если ни добавили ни одного пункта 
			outputings('warning', ' У Вас нет ни одного пункта');// сообщаем об этом
			return;
		}

		let selectFilter = document.querySelectorAll('.select-filter');// выбор фильтров
		for (let i = 0; i < selectFilter.length; i++) {
			if (selectFilter[i].value == 'Выбрать фильтр') {
				btnFlag = true;
				selectFilter[i].closest('.input-group').classList.add('has-error');
				return outputings('warning', 'Выбирите фильр');
			} else{
				outputings('info', 'Обратите внимание на правильность заполнения полей');
			}
		}

		let arr            = {},
				arrList        = addList.querySelectorAll('.input-group'),
				selectCategory = select[select.length - 1].value,
				data           = {};

		for (let i = 0; i < arrList.length; i++) {
			let value = arrList[i].querySelector('select').value;
			arr[i]  = value;
		}
		data.requestId     = 'saveBondedFilters';
		data.idCategory    = selectCategory;
		data.idsFilters    = arr;
		btnFlag = false;
		ajax(data, saveBondedFilters);
	});

})()