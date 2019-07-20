(function () {
	
	let container     = document.querySelector('.slide__top-categories'),
			ul            = document.querySelector('.slide__top-list'),
			li            = document.querySelectorAll('.slide__top-list li'),
			wrapper       = document.querySelector('body'),
			changeCoords  = 0,
			slow          = 0,
			ulLeft,
			setTime,
			initialCoords, //начальные координаты
			endCoords,// конечные координаты
			options       = {
				timePauseClick: 700,
				slowShift: 200,
				slowSpeed: 1.5
			};

	function coords(e, self) {
		let rect = self.getBoundingClientRect(),
				x    = Math.round(e.pageX - rect.left);
				return x;
	}

	function offsetSlide(e) {
		window.getSelection().removeAllRanges();		
		let x        = coords(e, this),
				offsetUl = -(initialCoords - x);
		ul.style.left = (parseInt(ulLeft) + offsetUl) - slow + 'px';
		if (parseInt(ul.style.left) >= parseInt(options.slowShift)) {
			slow = (offsetUl - options.slowShift) / options.slowSpeed;
		} else{
			slow = 0;
		}
	};

	function set() {
		changeCoords = 10;		
	};

	function canselLinksClick(e) {
		if (initialCoords != endCoords) {
			e.preventDefault();
		}
	};

	container.addEventListener('mousedown', function (e) {
		ul.classList.remove('left');
		e.preventDefault();
		setTime       = setTimeout(set, options.timePauseClick);
		ulLeft        = ul.style.left;
		if (ulLeft == '') ulLeft = '0px';
		wrapper.addEventListener('mousemove', offsetSlide);
	});
	wrapper.addEventListener('mousedown', function (e) {
		initialCoords = coords(e, this);		
	})
	wrapper.addEventListener('mouseup', function (e) {
		this.removeEventListener('mousemove', offsetSlide);
		clearTimeout(setTime);
		endCoords = coords(e, this) + changeCoords;
		changeCoords = 0;
		slow = 0;
		this.addEventListener('click', canselLinksClick);
		if (parseInt(ul.style.left) > 0) {
			ul.classList.add('left');
			ul.style.left = '0px';
		}
	});

})();