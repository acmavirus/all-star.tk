// Dom Ready
$(function () {
    datatables_columns = [{
        field: "checkID",
        title: "#",
        width: 50,
        sortable: !1,
        textAlign: "center",
        selector: {class: "m-checkbox--solid m-checkbox--brand"}
    }, {
        field: "id",
        title: "ID",
        width: 50,
        sortable: 'asc',
        filterable: !1,
    }, {
        field: "title",
        title: "Tiêu đề",
        width: 200
    }, {
        field: "link",
        title: 'Link trận đấu',
        width: 500,
        textAlign: "center",
        template: function (t) {
            return '<textarea rows="3" placeholder="Nhập mỗi Link 1 dòng" class="form-control">' + t.link + '</textarea>';
        }
    }, {
        field: "action",
        width: 100,
        title: "Actions",
        sortable: !1,
        overflow: "visible",
        template: function (t, e, a) {
            content = `${permission_edit ? '<span class="m-badge mr-2 m-badge--success m-badge--wide btnSaveMatch">Save</span>' : ''}`;
            content += `${permission_delete ? '<span class="m-badge mr-2 m-badge--danger m-badge--wide btnDelete">Xóa</span>' : ''}`;

            return content;
        }
    }];
    AJAX_DATATABLES.init();
    ($('select.category'));
    loadLeague($('select.filter_category'));

    AJAX_CRUD_MODAL.init();
    AJAX_CRUD_MODAL.tinymce();
    SEO.init_slug();


    $('[name="is_status"]').val(3);

    $('[name="is_status"]').on("change", function () {
        table.search($(this).val(), "is_status")
    }), $('[name="is_status"]').selectpicker();


    $('[name="filter_date"]').on("change", function () {
        table.search($(this).val(), "filter_date")
    });

    $('select[name="league_id"]').on("change", function () {
        table.search($(this).val(), "league_id")
    });

    $('#modal_form').on('shown.bs.modal', function(e){
        loadLeague($('select.category'));
    });

    $(document).on('click', '.btnSaveMatch', function () {
        let id = $(this).closest('tr').find('input[type="checkbox"]').val();
        let link = $(this).closest('tr').find('textarea').val();
        link = link.split("\n");
        link = JSON.stringify(link);

        $.ajax({
            url : url_ajax_update_field,
            type: "POST",
            data:{id:id,field:'data_link',value:link},
            dataType: "JSON",
            success: function(data) {
                if(data.type){
                    toastr[data.type](data.message);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                console.log(errorThrown);
                console.log(textStatus);
                console.log(jqXHR);
            }
        });
    });
});

function loadCategory(dataSelected) {
    let selector = $('select.category');
    selector.select2({
        placeholder: 'Chọn danh mục',
        allowClear: !0,
        multiple: !1,
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


function loadLeague(selector,dataSelected) {
    selector.select2({
        placeholder: 'Chọn giải đấu',
        allowClear: !0,
        multiple: !1,
        data: dataSelected,
        ajax: {
            url: url_ajax_load_tournament,
            dataType: 'json',
            delay: 250,
            data: function(e) {
                return {
                    q: e.term,
                    page: e.page
                }
            },
            processResults: function(e, t) {
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