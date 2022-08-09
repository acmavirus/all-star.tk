// Dom Ready
$(function() {
    datatables_columns = [{
        field: "checkID",
        title: "#",
        width: 10,
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
    }, {
        field: "score",
        width: 100,
        textAlign: "center",
        title: "Tỷ số trận đấu",
    }, {
        field: "data_link",
        title: 'Link CDN',
        width: 350,
        textAlign: "center",
        template: function (t) {
            return '<textarea data-type="data_link" rows="3" placeholder="Nhập mỗi Link 1 dòng" class="form-control data_link">' + t.data_link + '</textarea>';
        }
    }, {
        field: "data_link_wb",
        title: 'Link nội bộ dự phòng',
        width: 350,
        textAlign: "center",
        template: function (t) {
            return '<textarea data-type="data_link_wb" rows="3" placeholder="Nhập mỗi Link 1 dòng" class="form-control data_link">' + t.data_link_wb + '</textarea>';
        }
    }, {
        field: "is_hot",
        title: "Trận HOT",
        textAlign: "center",
        width: 70,
        template: function (t) {
            var e = {
                0: {title: "Disable", class: "m-badge--danger"},
                1: {title: "Active", class: "m-badge--primary"},
            };
            return '<span data-field="is_hot" data-value="'+(t.is_hot == 1 ? 0 : 1)+'" class="m-badge ' + e[t.is_hot].class + ' m-badge--wide btnUpdateField">' + e[t.is_hot].title + "</span>"
        }
    }, {
        field: "is_display",
        title: "Có hình",
        textAlign: "center",
        width: 70,
        template: function (t) {
            var e = {
                0: {title: "Disable", class: "m-badge--danger"},
                1: {title: "Active", class: "m-badge--primary"},
            };
            return '<span data-field="is_display" data-value="'+(t.is_display == 1 ? 0 : 1)+'" class="m-badge ' + e[t.is_display].class + ' m-badge--wide btnUpdateField">' + e[t.is_display].title + "</span>"
        }
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
    loadTournament($('select.data_tournament'));
    loadTournament($('select.filter_tournament'));
    loadClub($('select.data_home'));
    loadClub($('select.data_away'));
    AJAX_CRUD_MODAL.init();
    SEO.init_slug();

    $('input[name="start_time"]').datetimepicker({
        'format': 'yyyy/mm/dd hh:ii'
    });

    $('[name="is_status"]').on("change", function () {
        table.search($(this).val(), "is_status")
    }), $('[name="is_status"]').selectpicker();


    $('[name="filter_date"]').on("change", function () {
        table.search($(this).val(), "filter_date")
    });

    $('[name="is_schedule"]').on("change", function () {
        table.search($(this).val(), "is_schedule")
    });

    $('select[name="tournament_id"]').on("change", function () {
        table.search($(this).val(), "tournament_id")
    });

    $(document).on('change', '.data_link', function () {
        let id = $(this).closest('tr').find('input[type="checkbox"]').val();
        let type = $(this).data('type');
        let link = $(this).val();
        $.ajax({
            url : url_ajax_update_field,
            type: "POST",
            data:{id:id,field:type,value:link},
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
                        if((key === 'thumbnail' || key === 'logo_home' || key === 'logo_away' ) && value) {
                            if(/http/.test(value) == false) element.closest('.form-group').find('img').attr('src',media_url + value);
                        }
                        //if(key === 'logo_home' || key === 'logo_away') element.closest('.form-group').find('img').attr('src',value);
                        if(key === 'start_time' && value) {
                            element.val(moment(value).format('YYYY/MM/DD HH:mm'));
                        }
                    });

                    $.each(response.data_language, function( i, value ) {
                        let lang_code = value.language_code;
                        $.each(value, function( key, val) {
                            let element = modal_form.find('[name="language['+lang_code+']['+key+']"]');
                            if(element.hasClass('tinymce') && val){
                                let content_html = val.replace(/\\/g, '');
                                tinymce.get(element.attr('id')).setContent(content_html);
                            }
                            element.val(val);
                        });
                    });

                    loadTournament($('select.data_tournament'),response.data_tournament);
                    loadClub($('select.data_home'),response.data_home);
                    loadClub($('select.data_away'),response.data_away);
                    loadStream(response.data_streamvip);
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

function loadStream(dataSelected) {
    let selector = $('select.list_source');
    selector.select2({
        placeholder: 'Chọn channel',
        allowClear: !0,
        multiple: !0,
        data: dataSelected,
        ajax: {
            url: url_ajax_load_stream,
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


function loadTournament(selector,dataSelected) {
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

function loadClub(selector,dataSelected) {
    selector.select2({
        placeholder: 'Chọn câu lạc bộ',
        allowClear: !0,
        multiple: !1,
        data: dataSelected,
        ajax: {
            url: url_ajax_load_club,
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