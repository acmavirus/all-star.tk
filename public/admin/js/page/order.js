// Dom Ready
$(function () {
  datatables_columns = [{
    field: "checkID",
    title: "#",
    width: 20,
    sortable: !1,
    textAlign: "center",
    selector: {class: "m-checkbox--solid m-checkbox--brand"}
  }, {
    field: "id",
    title: "ID",
    textAlign: "center",
    sortable: 'asc',
    filterable: !1,
    width: 40
  }
    , {
      field: "username",
      title: "tài khoản",
    }
    , {
      field: "full_name",
      title: "Họ và tên",
    },
    {
      field: "email",
      title: "Email",
    }, {
      field: "bill_address",
      title: "Địa chỉ nhận hàng",
    },
    {
      field: "total_amount",
      title: "Tổng tiền",
    }, {
      field: "is_status",
      title: "Status",
      textAlign: "center",
      width: 110,
      template: function (t) {
        var e = {
          0: {title: "Hủy", class: "m-badge--danger"},
          1: {title: "Chờ xử lý", class: "m-badge--default"},
          2: {title: "Chưa xác nhận", class: "m-badge--primary"},
          3: {title: "Đã xác nhận", class: "m-badge--warning"},
          4: {title: "Đã giao hàng", class: "m-badge--success"},
        };
        return '<span data-field="is_status" data-value="' + (t.is_status == 1 ? 0 : 1) + '" class="m-badge ' + e[t.is_status].class + ' m-badge--wide">' + e[t.is_status].title + "</span>"
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
      title: "Actions",
      sortable: !1,
      overflow: "visible",
      template: function (t, e, a) {
        return '' +
          '<a href="javascript:;" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill btnEdit" title="Edit"><i class="la la-eye"></i></a>'
      }
    }];
  AJAX_DATATABLES.init();
  AJAX_CRUD_MODAL.init();

  $('#modal_form').on('shown.bs.modal', function (e) {
  });

  $(document).on('click', '.btnEdit', function () {
    let modal_form = $('#modal_form');
    let id = $(this).closest('tr').find('input[type="checkbox"]').val();
    AJAX_CRUD_MODAL.edit(function () {
      $.ajax({
        url: url_ajax_view + '/' + id,
        type: "POST",
        dataType: "JSON",
        success: function (response) {
          $.each(response, function (key, value) {
            let element = modal_form.find('td#' + key).html(value);
          });
          $('[name="shipped_time"]').val(response.shipped_time);
          $('[name="is_status"]').val(response.is_status);

          $('[name="id"]').val(id);
          $('table.list-detail tbody').html('');
          $.each(response.order_detail, function (key, value) {
            var tr = '<tr id="' + key + '">';
            $.each(value, function (k, v) {
              tr += '<td>' + v + '</td>';
            });
            tr += '</tr>';
            $('table.list-detail tbody').append(tr);
          });
          modal_form.modal('show');
        },
        error: function (jqXHR, textStatus, errorThrown) {
          console.log(errorThrown);
          console.log(textStatus);
          console.log(jqXHR);
        }
      });
      return false;
    });
  });

  $('body').on('click', '.delete_order', function (e) {
    current = $(this);
    var id = current.attr('data-id');
    var id_order = current.attr('id-order');
    swal({
      title: "Bạn có chắc chắn xóa những bản ghi này ?",
      text: "Bạn không thể khôi phục những bản ghi này sau khi xóa!",
      type: "warning",
      showCancelButton: !0,
      confirmButtonText: "Đúng, Xóa ngay !",
      cancelButtonText: "Không, Hủy nó !",
      reverseButtons: !0
    }).then(function (e) {
      if (e.value) {
        $.ajax({
          url: url_ajax_remove_item,
          type: 'POST',
          dataType: 'json',
          data: {
            id: id,
            id_order: id_order,
          },
          success: function (data) {
            if (data.status == true) {
              current.closest('tr').fadeOut('slow', function () {
                current.closest('tr').remove();
              });
              $('#total_amount span').html(data.total);
              toastr["success"](data.mess);
              swal.close();
              AJAX_DATATABLES.reload();
            } else {
              toastr["success"](data.mess);
            }
          }
        });
      } else {
        swal("Hủy bỏ thành công !", "Bản ghi của bạn đã được an toàn :)", "error")
      }
    })

  });
});

