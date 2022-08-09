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
        sortable: 'desc',
        filterable: !1,
    }, {
        field: "title",
        title: "Tiêu đề",
        width: 300
    }, {
        field: "is_featured",
        title: "Nổi bật",
        width: 70,
        textAlign: "center",
        template: function (t) {
            return '<span data-field="is_featured" data-value="'+(t.is_featured == 1 ? 0 : 1)+'" class="btnUpdateField">' + (t.is_featured == 1 ? '<i class="la la-star"></i>' : '<i class="la la-star-o"></i>') + "</span>"
        }
    }, {
        field: "is_status",
        title: "Status",
        textAlign: "center",
        width: 70,
        template: function (t) {
            var e = {
                0: {title: "Disable", class: "m-badge--danger"},
                1: {title: "Active", class: "m-badge--primary"},
            };
            return '<span data-field="is_status" data-value="'+(t.is_status == 1 ? 0 : 1)+'" class="m-badge ' + e[t.is_status].class + ' m-badge--wide btnUpdateField">' + e[t.is_status].title + "</span>"
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
                '<a href="javascript:;" onclick="crawler_post(this,\''+t.source+'\')" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only" title="Get"><i class="la la-get-pocket"></i></a>' +
                '<a href="javascript:;" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill btnEdit" title="Edit"><i class="la la-edit"></i></a>' +
                '<a href="javascript:;" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill btnDelete" title="Delete"><i class="la la-trash"></i></a>'
        }
    }];
    AJAX_DATATABLES.init();
    loadCategory($('select.filter_category'));
    loadCategory($('select.category'));
    AJAX_CRUD_MODAL.init();
    AJAX_CRUD_MODAL.tinymce();
    SEO.init_slug();

    $('[name="is_status"]').on("change", function () {
        table.search($(this).val(), "is_status")
    }), $('[name="is_status"]').selectpicker();

    $('[name="is_featured"]').on("change", function () {
        table.search($(this).val(), "is_featured")
    }), $('[name="is_featured"]').selectpicker();

    $('select[name="category_id"]').on("change", function () {
        table.search($(this).val(), "category_id")
    });

    $('#modal_form').on('shown.bs.modal', function(e){
        loadCategory($('.category'));
    });

    $(document).on('click','.btnEdit',function () {
        slug_disable = false;
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
                        if(key === 'thumbnail' && value) element.closest('.form-group').find('img').attr('src',media_url + value);
                    });
                    tinymce.get($('[name="description"]').attr('id')).setContent(response.data_info.description);
                    tinymce.get($('[name="content"]').attr('id')).setContent(response.data_info.content);

                    loadCategory($('select.category'),response.data_category);
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


function loadCategory(selector,dataSelected) {
    selector.select2({
        placeholder: 'Chọn danh mục',
        allowClear: !0,
        multiple: !0,
        data: dataSelected,
        ajax: {
            url: url_ajax_load_category,
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

function loadPost(dataSelected) {
    let selector = $('select.posts');
    selector.select2({
        multiple: !0,
        data: dataSelected,
        ajax: {
            url: url_ajax_load,
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

function crawler_form() {
    $('#modal_crawler_form').modal('show');
}


function crawler_post_detail(_this) {
    let element = $(_this);
    let source = element.closest('.modal').find('[name="link"]').val();
    $.ajax({
        url : base_url + 'crawler/ajax_get_post',
        type: "POST",
        data:{source:source},
        dataType: "JSON",
        beforeSend: function (){
            element.append('<i class="fa fa-spinner fa-spin ml-2" style="font-size: 18px;color: #ffffff;"></i>').attr('disabled',true);
        },
        success: function(data) {
            console.log(data);
            element.attr('disabled',false).find('i.fa-spin').remove();
            toastr[data.type](data.message);
            $('#modal_crawler_form').modal('hide');
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert(textStatus);
            console.log(jqXHR);
        }
    });
}

function crawler_post(_this,source) {
    let element = $(_this);
    let id = $(_this).closest('tr').find('input[type="checkbox"]').val();
    $.ajax({
        url : base_url + 'crawler/ajax_update_post',
        type: "POST",
        data: {id:id,source:source},
        dataType: "JSON",
        beforeSend: function () {
            element.append('<i class="fa fa-spinner fa-spin ml-2" style="font-size: 18px;color: #dd4b39;"></i>').attr('disabled',true);
        },
        success: function(data) {
            element.attr('disabled',false).find('i.fa-spin').remove();
            toastr[data.type](data.message);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert(textStatus);
            console.log(jqXHR);
        }
    });
}
