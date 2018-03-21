$(function () {
    $('#btn-navbar-menu-toggle').click(function () {
        navbarToggle();
    });
});

function navbarToggle() {
    $('#navbar-collapse-fixed-top').toggleClass('toggle');
    $('.main-container').toggleClass('toggle');
}
