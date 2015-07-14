

$(document).ready(function() {
    $().piroBox({
        my_speed: 400, //animation speed
        bg_alpha: 0.3, //background opacity
        slideShow : true, // true == slideshow on, false == slideshow off
        slideSpeed : 4, //slideshow duration in seconds(3 to 6 Recommended)
        close_all : '.piro_close,.piro_overlay'// add class .piro_overlay(with comma)if you want overlay click close piroBox

    });
});