/**
 * Created by Lonedrow on 18.01.2017.
 */
/* Анимация кнопки наверх*/
$(function() {

    $(window).scroll(function() {

        if($(this).scrollTop() != 0) {

            $('#toTop').fadeIn();

        } else {

            $('#toTop').fadeOut();

        }

    });

    $('#toTop').click(function() {

        $('body,html').animate({scrollTop:0},800);

    });

});

/* Плавное движение по якорям*/
$(document).ready(function() {

    $('a[href^="#"]').click(function(){
        var el = $(this).attr('href');
        $('body').animate({
            scrollTop: $(el).offset().top}, 1000);
        return false;
    });

});
/******************Слайдер*******************/
$(document).ready(function(){
    $('.sl').slick({
        autoplay:true,
        autoplaySpeed: 4000,
        speed:1200,
        arrows: false,
        dots: true,
        fade:false,
        pauseOnHover:true,
        slidesToShow:1,
        zindex:1000,
        /*responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    infinite: true,
                    dots: true
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    dots: false
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    dots: false
                }
            }
            ]*/

    });
});
