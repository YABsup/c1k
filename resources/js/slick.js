;(function($){
var slider = $('#slider');
    slider.slick({
        slide: '.slider-inner',
        prevArrow: '.arrow-prev',
        nextArrow: '.arrow-next',
        infinite: true,
        slidesToShow: 8,
        slidesToScroll: 1,

        responsive:[{
            breakpoint : 1024,
            settings: {
                slidesToShow: 6,
                slidesToScroll: 1,   
            }
        }]
    });

    var feedSlider = $('#feedback-slider2');
        feedSlider.slick({
            slide: '.feedback-slider-inner2',
            prevArrow: '.f-arrow-prev',
            nextArrow: '.f-arrow-next',
            infinite: true,
            slidesToScroll: 1,
    
            
        });
})(jQuery);