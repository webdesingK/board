(function () {
	
	let btnSave    = document.querySelector('#btn-save-js'),
			addFilter  = document.querySelector('.filter__add'),
			addList    = document.querySelector('#add-list-js'),
			tableName  = document.querySelector('#table-name-js'),
			plug       = document.querySelector('.plug'),
			count      = 1;

	function inputList(count) {
		return `
		<div class="input-group">
			<span class="input-group-addon">${count}</span>
			<input type="text" class="form-control">
			<span class="input-group-addon" title="Удалить пункт"><i class="glyphicon glyphicon-remove-circle text-danger"></i></span>
		</div>
	`;
	}

	addFilter.addEventListener('click', function() {
		addList.insertAdjacentHTML('beforeEnd', inputList(count));
		count++;
		plug.classList.add('none');
	});

	function zeroing(parent) {
		if (parent.querySelectorAll('.input-group').length == 0) {
			plug.classList.remove('none');
			count = 1;
		}		
	}

	addList.addEventListener('click', function(e) {
		let target = e.target;
		let parent = target.closest('#add-list-js');
		if (target.getAttribute('class') == 'input-group-addon') {
			target.parentNode.remove();
			zeroing(parent);
		} 
		if(target.parentNode.getAttribute('class') == 'input-group-addon') {
			target.closest('.input-group').remove();
			zeroing(parent);
		}
	});

	btnSave.addEventListener('click', function() {
		let data = {
			name: '',
			arrList: []
		};
		if (tableName.value) {
			tableName.parentNode.previousElementSibling.classList.add('none')
			data.name = tableName.value;
		} else{
			tableName.parentNode.previousElementSibling.classList.remove('none');
			return;
		}
		let tableList = addList.querySelectorAll('.form-control');
		for (var i = 0; i < tableList.length; i++) {
			data.arrList.push(tableList[i].value);
			if (!tableList[i].value) {
				return tableList[i].parentNode.classList.add('error-item');
			} else{
				tableList[i].parentNode.classList.remove('error-item');				
			}
		}
			console.log(data)
		ajax(data);
	});

	function ajax(data) {
		let xhr = new XMLHttpRequest();
		xhr.open('POST', 'создание-фильтров');
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

})();
