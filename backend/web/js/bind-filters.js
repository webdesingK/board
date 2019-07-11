(function () {
	
	let paddingLeft = document.querySelectorAll('.pl'),
			select      = document.querySelector('#select-categories-js'),
			btnSave     = document.querySelector('#btn-save-js'),
			addList     = document.querySelector('#add-list-js'),
			addFilter   = document.querySelector('#add-filters-js'),
			count       = 1,
			listFilter  = ['Игорь', 'Михаил', 'Виктория'];

	function ajaxListFilter() {
		let xhr = new XMLHttpRequest();
		xhr.open('POST', 'создание-фильтров');
		xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		xhr.send('value=listFilter');

		xhr.onreadystatechange = function() {
			if (xhr.readyState != 4) return;
			if (xhr.status == 200) {
				// console.log(JSON.parse(xhr.responseText));
			}
			else {
				console.log('errror');
				// console.log(xhr);
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