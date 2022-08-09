// Dom Ready
$(function() {
    datatables_columns = [{
        field: "checkID",
        title: "#",
        width: 50,
        sortable: !1,
        textAlign: "center",
        selector: {class: "m-checkbox--solid m-checkbox--brand"}
    },{
        field: "id",
        title: "ID",
        width: 50,
        textAlign: "center",
        sortable: 'asc',
        filterable: !1,
    }, {
        field: "url",
        title: "URL",
        width: 150
    }, {
        field: "action",
        width: 250,
        title: "Thông tin",
        sortable: !1,
        overflow: "visible",
        template: function (t, e, a) {
            content = `<li class="m-nav__item mt-2 button_event">`;

            content += `${permission_edit ? '<span class="m-badge mr-2 m-badge--success m-badge--wide btnEdit">Sửa</span>' : ''}`;
            content += `${permission_delete ? '<span class="m-badge mr-2 m-badge--danger m-badge--wide btnDelete">Xóa</span>' : ''}`;

            return content;
        }
    }];
    AJAX_DATATABLES.init();
    AJAX_CRUD_MODAL.init();
    AJAX_CRUD_MODAL.tinymce();
    SEO.init_slug();

    $(document).on('click','.btnEdit',function () {
        let modal_form = $('#modal_form');
        let id = $(this).closest('tr').find('input[type="checkbox"]').val();
        AJAX_CRUD_MODAL.edit(function () {
            $.ajax({
                url : url_ajax_edit,
                type: "POST",
                data: {id:id},
                dataType: "JSON",
                success: function(response) {
                    $.each(response.data_info, function( key, value ) {
                        let element = modal_form.find('[name="'+key+'"]');
                        element.val(value);
                        if(element.hasClass('switchBootstrap')){
                            element.bootstrapSwitch('state',(value == 1 ? true : false));
                        }
                    });

                    let element = modal_form.find('[name="content"]');
                    if(element.hasClass('tinymce') && response.data_info.content){
                        tinymce.get(element.attr('id')).setContent(response.data_info.content);
                    }
                    element.val(response.data_info.content);
                    modal_form.modal('show');
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    console.log(errorThrown);
                    console.log(textStatus);
                    console.log(jqXHR);
                }
            });return false;
        });
    });
});

function loadCategory(selector, dataSelected) {
    let multiple = true;
    if (selector.hasClass('category_primary')) {
        multiple = false;
    }

    selector.select2({
        placeholder: 'Chọn danh mục',
        allowClear: !0,
        multiple: multiple,
        data: dataSelected,
        ajax: {
            url: url_ajax_load_category,
            dataType: 'json',
            delay: 250,
            data: function (e) {
                return {
                    q: e.term,
                    page: e.page
                }
            },
            processResults: function (e, t) {
                return t.page = t.page || 1, {
                    results: e,
                    pagination: {
                        more: 30 * t.page < e.total_count
                    }
                }
            },
            cache: !0
        }
    });
    if (typeof dataSelected !== 'undefined') selector.find('> option').prop("selected", "selected").trigger("change");
}

function loadFilterCategory(selector) {
    let multiple = false;
    selector.select2({
        placeholder: 'Chọn danh mục',
        allowClear: !0,
        multiple: multiple,
        ajax: {
            url: url_ajax_load_category,
            dataType: 'json',
            delay: 250,
            data: function (e) {
                return {
                    q: e.term,
                    page: e.page
                }
            },
            processResults: function (e, t) {
                return t.page = t.page || 1, {
                    results: e,
                    pagination: {
                        more: 30 * t.page < e.total_count
                    }
                }
            },
            cache: !0
        }
    });
    if (typeof dataSelected !== 'undefined') selector.find('> option').prop("selected", "selected").trigger("change");
}
