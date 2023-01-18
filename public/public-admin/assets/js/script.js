$('.progress_td').hover(
    function () {
        $(this).find('.my_tooltip').fadeIn(0);
    },
    function () {
        $(this).find('.my_tooltip').fadeOut(0);
    },
    function () {
        $(this).find('.my_tooltip_subject').fadeIn(0);
    },
    function () {
        $(this).find('.my_tooltip_subject').fadeOut(0);
    }
);
$('.progress_td_subject').hover(

    function () {
        $(this).find('.my_tooltip_subject').fadeIn(0);
    },
    function () {
        $(this).find('.my_tooltip_subject').fadeOut(0);
    }
);
