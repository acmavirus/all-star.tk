var generated = [],
  possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

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
    field: "code",
    title: "Mã voucher",
  }, {
    field: "value",
    title: "Giá trị",
  }, {
    field: "is_status",
    title: "Status",
    textAlign: "center",
    width: 70,
    template: function (t) {
      var e = {
        3: {title: "Hết hạn", class: "m-badge--warning"},
        1: {title: "Active", class: "m-badge--primary"},
        2: {title: "Hủy", class: "m-badge--danger"},
      };
      return '<span data-field="is_status" data-value="'+(t.is_status == 1 ? 0 : 1)+'" class="m-badge  m-badge--wide btnUpdateField">' + e[t.is_status].title + "</span>"
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
  AJAX_CRUD_MODAL.init();

  var modalForm = $('#modal_form');
  modalForm.on('shown.bs.modal', function(e){
  });

  $(".generator").on("click", function (e) {
    generateCodes(1, 20);
    return false;
  });
  var modalForm = $('#modal_form');
  modalForm.on('shown.bs.modal', function(e){
    loadUser();
  });

  $(document).on('click','.btnEdit',function () {
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
          });

          if(response.user_use) loadUser(response.user_use);

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


function generateCodes(number, length) {


  $(".generator_code").val(generateCode(length));
}

function generateCode(length) {
  var text = "";

  for (var i = 0; i < length; i++) {
    text += possible.charAt(Math.floor(Math.random() * possible.length));
  }
  var check = check_code(text);
  if (check == 1) {
    generateCode(length);
  } else {
    return text;
  }

}

function check_code(code) {
  var result;
  $.ajax({
    url: ajax_check_code,
    type: 'POST',
    async: true,
    data: {code: code},
    success: function (data) {
      result = data;
    }
  });
  return result;
}

function loadUser(dataSelected) {
  let selector = $('select[name="user_use"]');
  selector.select2({
    placeholder: 'Chọn tài khoản',
    allowClear: !0,
    multiple: !1,
    data: dataSelected,
    ajax: {
      url: url_ajax_load_user,
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
