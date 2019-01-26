$(window).on('load resize',function() {
	var menuToggle = $('#navbar-mobile-menu').unbind();
	$('#navbar-mobile-menu').removeClass('show');

	menuToggle.on('click', function(e) {
		e.preventDefault();
		$('#navbar-navigation-menu').slideToggle('fast', function(){
			if($(this).is(':hidden'))
				$(this).removeAttr('style');
		});
	});
});

$(window).on('load resize',function() {
    $('[data-fixable]').each(function() {
    	$(this)
			.removeClass('fixed')
			.css('top', 'auto')
			.data('fixable-offset', $(this).offset().top);
	});
});

$(window).on('load resize scroll',function() {
	let offset = 0;
    $('[data-fixable]').each(function() {
        if($(document).scrollTop() > $(this).data('fixable-offset')) {
            $(this)
				.addClass('fixed')
				.css('top', offset);
			offset += $(this).outerHeight();
		}
		else {
            $(this)
                .removeClass('fixed')
				.css('top', 'auto');
		}
    });
});
