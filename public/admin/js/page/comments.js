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
		field: "fullname",
		title: "Họ và tên",
	}, {
		field: "content",
		title: "Bình luận",
	}, {
		field: "is_status",
		title: "Status",
		textAlign: "center",
		width: 110,
		template: function (t) {
			var e = {
				2: {title: "Chưa phê duyệt", class: "m-badge--warning"},
				1: {title: "Được phê duyệt", class: "m-badge--success"},
				0: {title: "Hủy", class: "m-badge--danger"},
			};
			return '<span data-field="is_status" data-value="'+(t.is_status == 1 ? 0 : 1)+'" class="m-badge  m-badge--wide btnUpdateField '+e[t.is_status].class+'">' + e[t.is_status].title + "</span>"
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
				'<a href="javascript:;" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill btnEdit"  title="Edit"><i class="la la-search"></i></a>' +
				'<a href="javascript:;" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill btnDelete" title="Delete"><i class="la la-trash"></i></a>'
		}
	}];
	AJAX_DATATABLES.init();
	AJAX_CRUD_MODAL.init();

	$(document).on('click','.btnEdit',function () {
		let modal_form = $('#modal_form');
		let id = $(this).closest('tr').find('input[type="checkbox"]').val();
		AJAX_CRUD_MODAL.edit(function () {
			$.ajax({
				url : url_ajax_edit+"/"+id,
				type: "GET",
				dataType: "JSON",
				success: function(data){
					console.log(data);
					$.each(data.comment, function( key, value) {
						if($.inArray(key,['content','account_id']) !== -1){
							$('[name="'+key+'"]').html(value);
						}else{
							$('[name="'+key+'"]').html(value);
						}

					});
					$('.reply-btn').attr("data-id",data.comment.id);
					$('.binhluan').val('@'+data.comment.account_id+': ');
					$('.binhluan').attr("data-id",data.comment.id);
					$('.binhluan').attr("data-product",data.comment.product_id);
					load_rep_comment(data.comment.id);
					$('#modal_form').modal('show');
				},
			});
			return false;
		});
	});
	rep();

});

function load_rep_comment(id) {
	$.ajax({
		url: url_ajax_repcomment+"/"+id,
		type: 'GET',
		dataType: 'html',
		success: function (data) {
			$('.reply-list').html(data);
		}
	});
}
function delete_comment(id,_this) {
	var current = $(_this);
	$.ajax({
		url: url_ajax_delete+"/"+id,
		type: 'GET',
		dataType: 'JSON',
		success: function (data) {
			if (data.type === "success") {
				current.closest('li').fadeOut('slow', function () {
              		current.closest('li').remove();
	            });
            }
		}
	});
}
function repcomments() {
	$(".upload_comments").attr('style','block');
}
function rep() {
	$('body').on('click','.rep_cm',function(event) {
		event.preventDefault();
		var thiss 			= $(this); 
		var content 		= $('.binhluan').val();
		var parent_id	 	= $('.binhluan').attr('data-id');
		var product_id		= $('.binhluan').attr('data-product');
		$.ajax({
			url: url_ajax_rep,
			type: 'POST',
			dataType: 'JSON',
			data: {
				content: content,
				parent_id:parent_id,
				product_id:product_id
			},
			success :function (data) {
				toastr["success"](data.mess);
				thiss.closest('.comments-list').find('.comments-list').append(data.html);
				$('.binhluan').val('@'+$('.by-author').text()+': ');
			}
		});
	});
}