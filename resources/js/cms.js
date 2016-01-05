$(document).on('ready', function(){
    $(window).on('resize', cmsHeightAdjust);
    cmsHeightAdjust();
});

function cmsHeightAdjust() {
    var sidebar = $('#side-nav');
    var sidebar_menu_height = sidebar.outerHeight() - sidebar.find('.sidebar-header').outerHeight() - sidebar.find('.sidebar-menu-additional').outerHeight();

    sidebar.find('.sidebar-menu-items').height(sidebar_menu_height);
}
