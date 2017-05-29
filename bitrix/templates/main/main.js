$(document).ready(function() {
    $('.owl-carousel').owlCarousel({
        itemElement: 'article',

        loop: true,

        margin: 10,
nav:true,
dots: false,
    navText:["<img src='/bitrix/templates/main/images/icons/left.png'>","<img src='/bitrix/templates/main/images/icons/right.png'>"],
        responsiveClass: true,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 3
            },
            1000: {
                items: 5,
                loop: false
            }
        }
    });
    $('.btn-show-map').click(function() {
        $('.map').addClass('active');
    });
});
