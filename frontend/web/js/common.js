$(document).ready(function() {

	// menu
	$(function() {

		var menu       = $('.menu'),
				menuBtn    = $('#menu-btn'),
				flagBtn    = true,
				heightMenu = $('.menu__first').outerHeight(true);

		$('.menu__second').each(function() {

			$(this).height(heightMenu);

		});

		function clickMenuBtn() {

			$(this).next().slideToggle().removeClass('none');
			$('.menu__second:first').show();

		};
			console.log($('.menu__first').height())

		menuBtn.on('click', clickMenuBtn);

		function hideSubMenu(obj) {
			obj.each(function() {
				$(this).hide();
			});
		};

		hideSubMenu($('.menu__second:not(:first)'));

		function mouseShow() {
			hideSubMenu($('.menu__second'));
			$(this).children('.menu__second').show();
		};

		function mouseHide() {
			$(this).children('.menu__second').hide();
		};
		menu.on('click', '.menu__first li', mouseShow);
		// menu.on('mouseleave', '.menu__first li', mouseHide);

	});

});
