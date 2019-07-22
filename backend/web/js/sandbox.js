(function () {
	
	let container      = document.querySelector('.slide__top-categories'),
			ul             = document.querySelector('.slide__top-list'),
			li             = document.querySelectorAll('.slide__top-list li'),
			wrapper        = document.querySelector('body'),
			changeCoords   = 0,
			slow           = 0,
			flagStartSlide = false,
			rightReturn    = container.clientWidth - ul.clientWidth,
			ulLeft,
			setTime,
			initialCoords, //начальные координаты
			endCoords,// конечные координаты
			options        = {
				timePauseClick: 700,
				slowSpeed: 4,// замедление - чем выше показатель, тем сильнее замедление
				returnClass: 'returnSpeed'
			};

	function coords(e, self) {
		let rect = self.getBoundingClientRect(),
				x    = Math.round(e.pageX - rect.left);
				return x;
	}

	function offsetSlide(e) {
		window.getSelection().removeAllRanges();
		let x        = coords(e, this),
				offsetUl = initialCoords - x,
				left     = parseInt(ul.style.left);
		if (isNaN(left)) left = 0
		if (left >= 0) {
			if (ulLeft < 0) {
				offsetUl = offsetUl - ulLeft;
				ul.style.left = (0 - offsetUl/options.slowSpeed) + 'px';
			} else{
				ul.style.left = (ulLeft - offsetUl/options.slowSpeed) + 'px';
			}
		}	else if(left < 0 && left > rightReturn) {
			ul.style.left = (ulLeft - offsetUl) + 'px';
		} else if (left <= rightReturn) {
			if (ulLeft > rightReturn) {
				if (ulLeft != 0) {
					offsetUl = (rightReturn - ulLeft) + offsetUl;
					ul.style.left = (rightReturn - offsetUl/options.slowSpeed) + 'px';
				} else{
					offsetUl = offsetUl + rightReturn;
					ul.style.left = (rightReturn - offsetUl/options.slowSpeed) + 'px';
				}
			} else{
				ul.style.left = (rightReturn - offsetUl/options.slowSpeed) + 'px';
			}
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
		ul.classList.remove(options.returnClass);
		e.preventDefault();
		initialCoords = coords(e, wrapper);		
		setTime       = setTimeout(set, options.timePauseClick);
		ulLeft        = parseInt(ul.style.left);
		if (isNaN(ulLeft)) ulLeft = 0
		wrapper.addEventListener('mousemove', offsetSlide);
		flagStartSlide = true;
	});

	function exitMouseUl(e, self) {
		self.removeEventListener('mousemove', offsetSlide);
		clearTimeout(setTime);
		endCoords = coords(e, self) + changeCoords;
		changeCoords = 0;
		self.addEventListener('click', canselLinksClick);
		if (parseInt(ul.style.left) > 0) {
			ul.classList.add(options.returnClass);
			ul.style.left = '0px';
		} else if (parseInt(ul.style.left) < rightReturn) {
			ul.classList.add(options.returnClass);
			ul.style.left = rightReturn + 'px';
		}
		flagStartSlide = false;
	};

	wrapper.addEventListener('mouseup', function (e) {
		if (flagStartSlide) {
			exitMouseUl(e, this);
		} else{
			this.removeEventListener('click', canselLinksClick);
		}
	});

	wrapper.addEventListener('mouseleave', function (e) {
		exitMouseUl(e, this);
	});

})();