$(function() {
    $('body').on('click', '.edit-button', function() {
        if(document.querySelectorAll('.editing').length == 0) {
            $('nav.navbar li.active').removeClass('active');
            let textarea = $(this).siblings('textarea');
            textarea.addClass('editing');
            textarea.prop('readonly', false);
            textarea.focus();
        }
    });

    $('body').on('blur', '.editing', function() {
        $(this).prop('readonly', true);
        $(this).removeClass('editing');
    });
});