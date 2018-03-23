$.extend(true, $.magnificPopup.defaults, {
    tClose: 'Закрыть (Esc)',
    tLoading: 'Загрузка...',
    gallery: {
        tPrev: 'Предыдущая (стрелка влево)',
        tNext: 'Следующая (стрелка вправо)',
        tCounter: '%curr% из %total%'
    },
    image: {
        tError: '<a href="%url%">Изображение</a> не может быть загружено.'
    },
    ajax: {
        tError: '<a href="%url%">Содержимое</a> не может быть загружено.'
    },
    mainClass: 'mfp-fade'
});

$('.carousel-days').owlCarousel({
    loop: false,
    items: 10,
    margin: 0,
    slideBy: 1,
    nav: false,
    dots: false,
    responsive: {
        0: {
            items: 2
        },
        480: {
            items: 5
        },
        768: {
            items: 10
        }
    }
});

$('.carousel-days-item').click(function (e) {
    e.preventDefault();
    $('.carousel-days-item').removeClass('active');
    $(this).addClass('active');
    $('.schedule-quests-item-schedule').html('<span>Скоро открытие</span>');
    var day = $(this).data('day');
    for (var questId in schedule.items) {
        if (schedule.items.hasOwnProperty(questId) && typeof(questId) !== 'function') {
            var days = schedule.items[questId];
            if (days[day]) {
                var container = $('#schedule-' + questId);
                container.html('');
                for (var time in days[day].items) {
                    if (days[day].items.hasOwnProperty(time) && typeof(time) !== 'function') {
                        var div = $('<div/>');
                        if (!days[day].items[time].booked) {
                            div.addClass('schedule-quests-item-price schedule-quests-item-price-'
                                + schedule.prices.indexOf(days[day].items[time].price));
                        }
                        div
                            .data('time-send', day + ' ' + time)
                            .data('time', time)
                            .data('day', days[day].day)
                            .data('price', days[day].items[time].price)
                            .html(time)
                            .appendTo(container);
                    }
                }
            }
        }
    }
});

$('body').on('click', '.schedule-quests-item-price', function() {
    var self = $(this);
    var parent = self.parents('.schedule-quests-item');
    var popup = $('#booking-popup');
    popup.children().hide();
    $('.booking-form').show();
    $('.booking-error').html('');
    $('h4 span', popup).html(parent.data('title'));
    $('td div', popup).first().html(self.data('day'));
    $('td div', popup).eq(1).html(self.data('time'));
    $('td div', popup).eq(2).html(self.data('price') + ' р.');
    $('[name=time]', popup).val(self.data('time-send'));
    $('[name=quest]', popup).val(parent.data('id'));
    $('#booking-phone').mask('+7 (999) 999-99-99');
    $.magnificPopup.open({
        items: {
            src: popup,
            type: 'inline'
        }
    });
});

$('form', '#booking-popup').submit(function() {
    var popup = $('#booking-popup');
    $('[type=submit]', popup).blur();
    $('.booking-form').slideUp('fast');
    $('.loader', popup).slideDown('fast');
    $.post($(this).attr('action'), $(this).serialize(), function(result) {
        $('.loader', popup).slideUp('fast');
        $('.booking-result').html(result.message).slideDown('fast');
    }).fail(function(result) {
        var message = 'Возникла ошибка. Попробуйте ещё раз.';
        if(result && result.responseJSON) {
            if(result.responseJSON.errors) {
                for (var first in result.responseJSON.errors) {
                    if (result.responseJSON.errors.hasOwnProperty(first) && typeof(first) !== 'function') {
                        message = result.responseJSON.errors[first][0];
                        break;
                    }
                }
            }
            else if (result.responseJSON.message && result.responseJSON.message.length > 0) {
                message = result.responseJSON.message;
            }
        }
        $('.booking-error').html($('<div/>').addClass('form-group').html(message));
        $('.loader', popup).slideUp('fast');
        $('.booking-form').slideDown('fast');
    });
    return false;
});