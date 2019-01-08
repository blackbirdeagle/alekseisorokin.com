/*
* Copyright by Alexander Afanasyev
* E-mail: blackbirdeagle@mail.ru
* Skype: al_sidorenko1
* */
$('.art__pagin li a').click(function(){
    var page = $(this).attr('page');

    $('.art__pagin li a').removeClass('active');
    $(this).addClass('active');

    $('.articles__list').hide();
    $('#' + page).show();
});

jQuery(function(){
    jQuery(".phone").mask("+7(999) 999-9999");
});

$('.show__menu').click(function(){
    $('.float__menu').animate({left: 0}, 300);
});

$('.hide__menu').click(function(){
    $('.float__menu').animate({left: -236}, 300);
});

$(function(){
    $("a[href^='#']").click(function(){
        var _href = $(this).attr("href");
        $("html, body").animate({scrollTop: $(_href).offset().top+"px"});
        return false;
    });
});

$('.float__menu ul li a').click(function(){
    $('.float__menu').animate({left: -236}, 300);
});

$('.fancybox').fancybox({
    openEffect  : 'none',
    closeEffect : 'none',
    helpers : {
        media : {},
        overlay: {
            locked: false
        }
    }
});

$('.spoiler').click(function(){
	$('.contacts__constext').fadeIn(200);
});

$(document).ready(function() {
    var owl = $("#sert__slider");

    owl.owlCarousel({
        responsive: {
            0: {
                items: 1
            },
            570: {
                items: 2
            },
            600: {
                items: 3
            },
            991: {
                items: 3
            }
        },
        margin: 18,
        loop: false,
        autoplay: false,
        autoplayTimeout: 3000,
        touchDrag: true,
        dots: false,
        autoWidth: false,
        nav: false,
    });

    $('.sert__nav__prev').click(function(){
        owl.trigger('prev.owl.carousel');
    });

    $('.sert__nav__next').click(function(){
        owl.trigger('next.owl.carousel');
    });

    $("#foto__slider").owlCarousel({
        responsive: {
            0: {
                items: 1
            },
            570: {
                items: 2
            },
            600: {
                items: 3
            },
            991: {
                items: 3
            }
        },
        margin: 0,
        loop: false,
        autoplay: false,
        autoplayTimeout: 3000,
        touchDrag: true,
        dots: false,
        autoWidth: false,
        nav: true,
        navText: ['<img src = "/wp-content/themes/sorokin/images/prev.png" alt = ""/>', '<img src = "/wp-content/themes/sorokin/images/next.png" alt = ""/>'],
    });

    $("#video__slider").owlCarousel({
        responsive: {
            0: {
                items: 1
            },
            570: {
                items: 2
            },
            600: {
                items: 3
            },
            991: {
                items: 3
            }
        },
        margin: 0,
        loop: false,
        autoplay: false,
        autoplayTimeout: 3000,
        touchDrag: true,
        dots: false,
        autoWidth: false,
        nav: true,
        navText: ['<img src = "/wp-content/themes/sorokin/images/prev.png" alt = ""/>', '<img src = "/wp-content/themes/sorokin/images/next.png" alt = ""/>'],
    });
	
    $("#sorokin__slider, #sorokin__slider__mobile").owlCarousel({
        responsive: {
            0: {
                items: 1
            },
            570: {
                items: 1
            },
            600: {
                items: 1
            },
            991: {
                items: 1
            }
        },
        margin: 0,
        loop: true,
        autoplay: true,
        autoplayTimeout: 3000,
        touchDrag: true,
        dots: true,
        autoWidth: false,
        nav: true,
        navText: ['', ''],
    });	
});