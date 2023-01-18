var slider_1=$('#slider_1')
slider_1.owlCarousel(
    {
        items:1,
        dots: true,
        loop:true,
        rtl: true,
        autoplay:true,
        autoplayTimeout: 3000,

    }
);
function go_next(){
        slider_1.trigger('next.owl.carousel')
} function go_prev(){
        slider_1.trigger('prev.owl.carousel')
}
