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
                '<a href="javascript:;" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill btnEdit" title="Edit"><i class="la la-edit"></i></a>' +
                '<a href="javascript:;" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill btnDelete" title="Delete"><i class="la la-trash"></i></a>'
        }
    }];
    AJAX_DATATABLES.init();
    loadCategoryFilter();
    loadCategory();
    loadBrand();
    loadProductRelated();
    loadProductSimilar();
    AJAX_CRUD_MODAL.init();
    AJAX_CRUD_MODAL.tinymce();
    SEO.init_slug();

    $('[name="is_status"]').on("change", function () {
        table.search($(this).val(), "is_status")
    }), $('[name="is_status"]').selectpicker();

    $('select[name="filter_category"]').on("change", function () {
        table.search($(this).val(), "category_id")
    });

    var modalForm = $('#modal_form');
    modalForm.on('shown.bs.modal', function(e){
        loadCategory();
    });

    modalForm.on('click','.agency-container .btn-add-agency',function (e) {
        e.preventDefault();
        let container = $(this).closest('fieldset').find('.quantity-range');
        let itemClone = container.find('.row:first-child').clone();
        itemClone.find('input').val('');
        container.append(itemClone);
        container.find('.row').each(function (i) {
            $(this).find('input').each(function () {
                $(this).attr('name',$(this).attr('name').replace('[0]','['+i+']'));
            });
        });
    });

    modalForm.on('click','.quantity-range .btn-remove',function (e) {
        e.preventDefault();
        let elementCurrent = $(this).closest('.row');
        if(elementCurrent.index() == 0){
            toastr['warning']('Bạn không được phép xóa bản ghi này.');
            return false;
        }
        elementCurrent.remove();
    });

    $(document).on('click','.btnEdit',function () {
        slug_disable = false;
        let modal_form = modalForm;
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

                    $.each(response.data_language, function( i, value ) {
                        let lang_code = value.language_code;
                        $.each(value, function( key, val) {
                            let element = modal_form.find('[name="language['+lang_code+']['+key+']"]');
                            if(element.hasClass('tinymce') && val){
                                tinymce.get(element.attr('id')).setContent(val);
                            }
                            element.val(val);
                        });
                    });
                    showPriceAgency(response.data_detail);
                    loadCategory(response.data_category);
                    loadBrand(response.data_brand);
                    loadProductRelated(response.data_related);
                    loadProductSimilar(response.data_similar);

                    FUNC.showGallery('#list-album',response.data_info.album);
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
function showPriceAgency(data) {
    if(data.length > 0){
        let container = $('fieldset.agency-container .quantity-range');
        container.find('.row').not(':first').remove();
        $.each(data, function (i,v) {
            let itemClone = container.find('.row:first-child').clone();
            $.each(v,function (name, value) {
                itemClone.find('[name="data_detail[0]['+name+']"]').attr('name','data_detail['+i+']['+name+']').val(value);
            });
            container.append(itemClone);
        });
        container.find('.row:first-child').remove();
    }
}
function loadCategoryFilter(dataSelected) {
    let selector = $('select.filter_category');
    selector.select2({
        placeholder: 'Chọn danh mục',
        allowClear: !0,
        multiple: !1,
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

function loadBrand(dataSelected) {
    let selector = $('select.brand');
    selector.select2({
        placeholder: 'Chọn thương hiệu',
        allowClear: !0,
        multiple: !1,
        data: dataSelected,
        ajax: {
            url: url_ajax_load_brand,
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

function loadProductRelated(dataSelected) {
    let selector = $('select.data_related');
    selector.select2({
        placeholder: 'Chọn sản phẩm',
        allowClear: !0,
        multiple: !0,
        data: dataSelected,
        ajax: {
            url: url_ajax_load_product,
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

function loadProductSimilar(dataSelected) {
    let selector = $('select.data_similar');
    selector.select2({
        placeholder: 'Chọn sản phẩm',
        allowClear: !0,
        multiple: !0,
        data: dataSelected,
        ajax: {
            url: url_ajax_load_product,
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
