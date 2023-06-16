window.addEventListener('DOMContentLoaded', function() {
    // выделяем активынй класс в топ меню
    let pathname = location.pathname.split('/');
        pathname = pathname[1].trim() ? pathname[1] : "";
    // фикс для страниц /page/...
        pathname = pathname == 'page' ? '' : pathname;
    $('nav.navbar li.active').removeClass('active');
    $(`nav.navbar a[href="/${pathname}"]`).closest('li').addClass('active');
});