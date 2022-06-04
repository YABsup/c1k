$(".wrap-header-mobile").click(function() {
    if ($(this).children(".header-top-txt").is(":visible")) {
        $(this).children(".header-top-txt").slideUp();
    } else {
        $(this).children(".header-top-txt").slideDown();
    }
});

$('.top-menu-mobile_btn').on('click', function() {
    $(this).toggleClass('modile-menu_btn-active');
});