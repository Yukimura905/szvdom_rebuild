$(document).ready(function() {
    $('#imageGallery').lightSlider({
        gallery: true,
        item: 1,
        adaptiveHeight: true,
        loop: true,
        thumbItem: 4,
	enableDrag: false,
        slideMargin: 0,
        currentPagerPosition: 'left',
        onSliderLoad: function(el) {
            el.lightGallery({
                selector: '#imageGallery .lslide'
            });
        }
    });
});



