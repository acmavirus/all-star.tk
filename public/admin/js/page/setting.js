$(function() {
    UI.ajaxFormSubmit();
    AJAX_CRUD_MODAL.tinymce();
    AJAX_CRUD_MODAL.summernote();

    $('.datetimepicker').datetimepicker({
        'format': 'yyyy/mm/dd hh:ii'
    });
});
