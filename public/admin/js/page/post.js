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
        width: 150
    }, {
        field: "category",
        title: "Danh mục",
        width: 150
    }, {
        field: "thumbnail",
        title: "Hình ảnh",
        textAlign: "center",
        width: 100,
        template: function (t) {
            return '<img class="img-thumbnail" src="'+t.thumbnail+'">'
        }
    }, {
        field: "is_status",
        title: "Trạng thái",
        textAlign: "center",
        width: 70,
        template: function (t) {
            var e = {
                0: {title: "Chờ duyệt", class: "m-badge--danger"},
                1: {title: "Hiển thị", class: "m-badge--primary"},
            };
            return '<span data-field="is_status" data-value="'+(t.is_status == 1 ? 0 : 1)+'" class="m-badge ' + e[t.is_status].class + ' m-badge--wide btnUpdateField">' + e[t.is_status].title + "</span>"
        }
    }, {
        field: "action",
        width: 250,
        title: "Thông tin",
        sortable: !1,
        overflow: "visible",
        template: function (t, e, a) {
            content = `<li class="m-nav__item"><span class="m-nav__link">
            <i class="m-nav__link-icon flaticon-avatar"></i><span class="m-nav__link-text"> Người tạo : <strong>${t.username}</strong></span></span></li> 
            <li class="m-nav__item"><span class="m-nav__link">
            <i class="m-nav__link-icon flaticon-visible"></i><span class="m-nav__link-text"> Lượt vào xem : <strong>${t.viewed}</strong></span></span></li>
            <li class="m-nav__item"><span class="m-nav__link">
            <i class="m-nav__link-icon flaticon-calendar"></i><span class="m-nav__link-text"> Ngày tạo : <strong>${t.created_time}</strong></span></span></li>
            <li class="m-nav__item"><span class="m-nav__link">
            <i class="m-nav__link-icon flaticon-calendar"></i><span class="m-nav__link-text"> Cập nhật : <strong>${t.updated_time}</strong></span></span></li>
            <li class="m-nav__item mt-2 button_event">`;

            content += `${permission_edit ? '<span class="m-badge mr-2 m-badge--success m-badge--wide btnEdit">Sửa</span>' : ''}`;
            content += `${permission_delete ? '<span class="m-badge mr-2 m-badge--danger m-badge--wide btnDelete">Xóa</span>' : ''}`;

            return content;
        }
    }];
    AJAX_DATATABLES.init();
    AJAX_CRUD_MODAL.init();
    AJAX_CRUD_MODAL.tinymce();
    SEO.init_slug();
    loadFilterCategory($('select.filter_category'));
    loadCategory($('select.category'));
    loadCategory($('select.category_primary'));
    loadTag($('select.tag'));
    loadMatch($('select.match'));
    $('[name="is_status"]').on("change", function () {
        table.search($(this).val(), "is_status")
    }), $('[name="is_status"]').selectpicker();

    $('.filter_category').on("change", function () {
        table.search($(this).val(), "category_id")
    });

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
                        if(key === 'thumbnail' && value) element.closest('.form-group').find('img').attr('src',media_url + value);
                    });

                    if (response.data_bets) {

                        $.each(response.data_bets, function (key, value) {
                            let element = modal_form.find('[name="data_bets['+key+']"]');
                            element.val(value);

                            if (key === 'away_logo' || key === 'home_logo') {
                                element.closest('.form-group').find('img').attr('src', media_url + value);
                            }
                        });
                    }
                    if (response.data_dealer) {

                        $.each(response.data_dealer, function (key, value) {
                            let element = modal_form.find('[name="data_dealer['+key+']"]');
                            element.val(value);
                        });
                    }


                    let element = modal_form.find('[name="content"]');
                    if(element.hasClass('tinymce') && response.data_info.content){
                        tinymce.get(element.attr('id')).setContent(response.data_info.content);
                    }
                    element.val(response.data_info.content);
                    if (response.data_category) loadCategory($('select.category'),response.data_category);
                    if (response.data_category_primary) loadCategory($('select.category_primary'),response.data_category_primary);
                    if (response.data_tag) loadTag($('select.tag'), response.data_tag);
                    if (response.data_match) loadMatch($('select.match'), response.data_match);
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

function loadMatch(selector, dataSelected) {
    selector.select2({
        placeholder: 'Chọn trận đấu',
        allowClear: !0,
        multiple: !1,
        data: dataSelected,
        ajax: {
            url: url_ajax_load_match,
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
function loadTag(selector, dataSelected) {
    selector.select2({
        placeholder: 'Chọn thẻ tag',
        allowClear: !0,
        multiple: !0,
        data: dataSelected,
        ajax: {
            url: base_url + 'admin/category/ajax_load/tag',
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