(function () {
	
	let btnSave    = document.querySelector('.btn-save'),
			addFilter  = document.querySelector('.filter__add'),
			filterName = document.querySelector('.filter-name'),
			wrapRight  = document.querySelector('.wrap__right'),
			filterList;

	let data = {
		name: '',
		arrList: []
	};

	let input = ` 
		<div class="wrap__right-list">
			<input type="text" class="filters__list" placeholder="добавить в список фильтра">
			<div class="filter__remove" title="удалить из списка фильтра">✘</div>
		</div>
	`;

	addFilter.addEventListener('click', function() {

		this.parentNode.insertAdjacentHTML('beforeBegin', input);
		filterList   = document.querySelectorAll('.filters__list');
	});

	wrapRight.addEventListener('click', function(e) {
		let target = e.target;
		if (target.getAttribute('class') == 'filter__remove') {
			target.parentNode.remove();
		}
	});

	btnSave.addEventListener('click', function() {
		if (filterName.value) {
			filterName.previousElementSibling.classList.add('none')
			data.name = filterName.value;
		} else{
			filterName.previousElementSibling.classList.remove('none');
		}
		for (var i = 0; i < filterList.length; i++) {
			data.arrList.push(filterList[i].value);
		}
		let cleaningDublicates = Array.from(new Set(data.arrList));
		data.arrList = cleaningDublicates;
		console.log(data)
		ajax();
	});

	function ajax() {
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
