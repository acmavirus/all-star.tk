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
        sortable: 'asc',
        filterable: !1,
    }, {
        field: "username",
        title: "User Name"
    }, {
        field: "fullname",
        title: "Full Name"
    }, {
        field: "is_status",
        title: "Status",
        width: 70,
        template: function (t) {
            console.log(t);
            var e = {
                0: {title: "Disable", class: "m-badge--danger"},
                1: {title: "Active", class: "m-badge--primary"},
            };
            return '<span data-field="active" data-value="'+(t.is_status == 1 ? 0 : 1)+'" class="m-badge ' + e[t.is_status].class + ' m-badge--wide btnUpdateField">' + e[t.is_status].title + "</span>"
        }
    }, {
        field: "updated_time",
        title: "Updated Time",
        type: "date",
        textAlign: "center",
        format: "MM/DD/YYYY"
    }, {
        field: "created_time",
        title: "Created Time",
        type: "date",
        textAlign: "center",
        format: "MM/DD/YYYY"
    }, {
        field: "action",
        width: 110,
        title: "Actions",
        sortable: !1,
        overflow: "visible",
        template: function (t, e, a) {
            return '' +
                '<a href="javascript:;" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill btnEdit" title="Edit"><i class="la la-edit"></i></a>' +
                '<a href="javascript:;" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill btnDelete" title="Delete"><i class="la la-trash"></i></a>'
        }
    }];
    AJAX_DATATABLES.init();

    $('[name="is_status"]').on("change", function () {
        table.search($(this).val(), "is_status")
    }), $('[name="is_status"]').selectpicker();

    $('select[name="filter_group_id"]').on("change", function () {
        table.search($(this).val(), "group_id")
    });

    loadGroup('',$('[name="filter_group_id"]'));
    loadGroup();
    AJAX_CRUD_MODAL.init();

    $(document).on('click','.btnEdit',function () {
        let modal_form = $('#modal_form');
        let id = $(this).closest('tr').find('input[type="checkbox"]').val();
        console.log(id);
        AJAX_CRUD_MODAL.edit(function () {
            $.ajax({
                url : url_ajax_edit,
                type: "POST",
                data: {id:id},
                dataType: "JSON",
                success: function(response) {
                    console.log(response);
                    $.each(response.data, function( key, value ) {
                        $('[name="'+key+'"]').val(value);
                        if(key === 'active'){
                            $('[name="active"]').bootstrapSwitch('state', (value == 1 ? true : false));
                        }
                        modal_form.find('[name="username"]').attr('readonly',true);
                    });
                    loadGroup(response.group);
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

function loadGroup(dataSelected,selector) {
    if(!selector) selector = $('select[name="group_id"]');
    selector.select2({
        placeholder: 'Chọn nhóm',
        allowClear: !0,
        multiple: !1,
        data: dataSelected,
        ajax: {
            url: url_ajax_load_group,
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
