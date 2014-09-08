jQuery(document).ready(function($) {

    var viewportWidth = $(window).width();

    // if ( 768 < viewportWidth ) {
    //     $("body").backstretch([BackStretchImages.small],{duration:3000,fade:750});
    // } else if ( 1024 < viewportWidth ) {
    //     $("body").backstretch([BackStretchImages.medium],{duration:3000,fade:750});
    // } else {
    //     $("body").backstretch([BackStretchImages.large],{duration:3000,fade:750});
    // }


    if ( 768 < viewportWidth ) {
        $.backstretch([BackStretchImages.small],{duration:3000,fade:750});
    } else if ( 1024 < viewportWidth ) {
        $.backstretch([BackStretchImages.medium],{duration:3000,fade:750});
    } else {
        $.backstretch([BackStretchImages.large],{duration:3000,fade:750});
    }
});