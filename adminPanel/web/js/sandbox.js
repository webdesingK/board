(function () {
	
	let container      = document.querySelector('.slide__top-categories'),
			ul             = document.querySelector('.slide__top-list'),
			li             = document.querySelectorAll('.slide__top-list li'),
			wrapper        = document.querySelector('body'),
			prev           = document.querySelector('#arrow-prev-slide-top-js'),
			next           = document.querySelector('#arrow-next-slide-top-js'),
			changeCoords   = 0,
			slow           = 0,
			flagStartSlide = false,
			rightReturn    = container.clientWidth - ul.clientWidth,
			arrPx          = [],
			px             = 0,
			ulLeft,
			setTime,
			initialCoords, //начальные координаты
			endCoords,// конечные координаты
			options        = {
				timePauseClick: 700,
				slowSpeed: 4,// замедление - чем выше показатель, тем сильнее замедление
				returnClass: 'returnSpeed'
			};

	window.addEventListener('resize', function () {
		rightReturn = container.clientWidth - ul.clientWidth;
		if (parseInt(ul.style.left) < rightReturn) {
			ul.style.left = rightReturn + 'px';
		}
	});

	function coords(e, self) {
		let rect = self.getBoundingClientRect(),
				x    = Math.round(e.pageX - rect.left);
				return x;
	};

	function offsetSlide(e) {
		window.getSelection().removeAllRanges();
		let x        = coords(e, this),
				offsetUl = initialCoords - x,
				left     = parseInt(ul.style.left);
		px = offsetUl;
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

	let setInt;

	function startEndTime() {
		arrPx.push(px);
	}

	container.addEventListener('mousedown', function (e) {
		arrPx = [];
		setInt = setInterval(startEndTime, 200);
		arrPx.push(0);
		ul.classList.remove(options.returnClass);
		ul.className = ul.className.replace(/slow[\w]*/gi, '');
		e.preventDefault();
		initialCoords = coords(e, wrapper);		
		setTime       = setTimeout(set, options.timePauseClick);
		ulLeft        = parseInt(ul.style.left);
		if (isNaN(ulLeft)) ulLeft = 0
		wrapper.addEventListener('mousemove', offsetSlide);
		flagStartSlide = true;
	});

	function speedUp() {
		let negatige = 200;
		if (endCoords - initialCoords > 0) {
			negatige = -200;
		}
		if (arrPx.length == 1) arrPx.push(negatige);
		let arr        = arrPx.slice(-2),
		  	difference = arr[1] - arr[0],
		    minus      = 0,
				animClass  = 3;
		arrPx = [];
		if (difference < 50) {
			minus = 3;
			animClass = 3;
		} else if(difference < 100) {
			minus = 2;
			animClass = 2;
		} else if (difference <= 200) {
			minus = 1;
			animClass = 1;
		} else{
			minus = 5;
			animClass = 1;
		}
		let result = parseInt(ul.style.left) - (difference * minus);
		if (result > 0) {
			result = 0;
			changeViewArrow(prev, 'add');
			changeViewArrow(next, 'remove');
		} else if(result < rightReturn) {
			result = rightReturn;
			changeViewArrow(prev, 'remove');
			changeViewArrow(next, 'add');
		} else{
			changeViewArrow(prev, 'add');
			changeViewArrow(next, 'add');
		}
		ul.classList.add('slow' + animClass);
		ul.style.left = result + 'px';
	};

	function exitMouseUl(e, self) {
		clearInterval(setInt);
		self.addEventListener('click', canselLinksClick);
		self.removeEventListener('mousemove', offsetSlide);
		clearTimeout(setTime);
		endCoords = coords(e, self) + changeCoords;
		changeCoords = 0;		
		if (parseInt(ul.style.left) > 0) {
				ul.classList.add(options.returnClass);
				ul.style.left = '0px';
		} else if (parseInt(ul.style.left) < rightReturn) {
				ul.classList.add(options.returnClass);
				ul.style.left = rightReturn + 'px';
		} else{
			speedUp();
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
		if (flagStartSlide) {
			exitMouseUl(e, this);
		}
	});

	// кнопки слайдера

	function changeViewArrow(el, remove) {
		if (remove == 'remove') {
			el.classList.add('inactive-arrow');
			el.removeEventListener('click', arrowShift);
		} else if (remove == 'add'){
			el.classList.remove('inactive-arrow');
			el.addEventListener('click', arrowShift);
		}
	}

	function arrowShift() {
		ul.className = ul.className.replace(/slow[\w]*/gi, '');
		next.classList.remove('inactive-arrow');
		let parent   = container.getBoundingClientRect(),
				ulLeftAr = parseInt(ul.style.left);
				if (isNaN(ulLeftAr)) ulLeftAr = 0;
		for (let i = 0; i < li.length; i++) {
			let element = li[i].getBoundingClientRect(),
					left    = Math.round(element.left - parent.left),
					right   = Math.round((element.left + li[i].clientWidth) - parent.left);
			if (left <= 0 && right > 0) {
				if (this.classList.contains('arrow__prev')) {
					changeViewArrow(next, 'add');
					ul.style.left = ulLeftAr - right + 'px';
					if (ulLeftAr - right <= rightReturn) {
						ul.style.left = rightReturn + 'px';
						changeViewArrow(this, 'remove');
					}
				} else{
					if (left < 0 && right > 0) {
						ul.style.left = ulLeftAr - left + 'px';
						changeViewArrow(prev, 'add');
					} else if(left == 0 && ulLeftAr != 0) {
						ul.style.left = ulLeftAr + li[i-1].clientWidth + 'px';
					}
					if (i == 1) {
						changeViewArrow(this, 'remove');
					}
				}
				break;
			}
		}
	}

	prev.addEventListener('click', arrowShift);
	next.addEventListener('click', arrowShift);

})();