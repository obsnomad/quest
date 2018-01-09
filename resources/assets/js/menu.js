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
			.removeClass($(this).data('fixable-class'))
			.addClass($(this).data('fixable-remove-class'))
			.css('top', 'auto')
			.data('fixable-offset', $(this).offset().top);
	});
});

$(window).on('load resize scroll',function() {
	var offset = 0;
    $('[data-fixable]').each(function() {
        if($(document).scrollTop() > $(this).data('fixable-offset')) {
            $(this)
				.addClass('fixed')
                .addClass($(this).data('fixable-class'))
                .removeClass($(this).data('fixable-remove-class'))
				.css('top', offset);
			offset += $(this).outerHeight();
		}
		else {
            $(this)
                .removeClass('fixed')
                .removeClass($(this).data('fixable-class'))
                .addClass($(this).data('fixable-remove-class'))
				.css('top', 'auto');
		}
    });
});
