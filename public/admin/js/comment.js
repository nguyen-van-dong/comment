function CommentAdmin() {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  $('body').on('click', '.btnPublishComment, .btnUnPublish', function (e) {
    e.preventDefault();
    let _this = $(this);
    if ($(this).data('is_publish')) {
      swal({ title: 'Please Waiting ...', text: 'Publishing...', buttons: false });
    } else {
      swal({ title: 'Please Waiting ...', text: 'Un-Publishing...', buttons: false });
    }
    $.ajax({
      url: adminPath + '/comment/publish',
      method: 'POST',
      data: {
        comment_id: $(this).data('comment_id'),
        is_publish: $(this).data('is_publish')
      },
      success: function (response) {
        if (response.success) {
          $(_this).siblings('i').remove();
          if (response.is_published) {
            $(_this).replaceWith('<i class="fas fa-check text-success"></i>' +
              '<a href="#" data-comment_id="' + response.comment_id + '" data-is_publish="0" title="Un-published" class="btn-sm mr-1 btnUnPublish">\n' +
              '    Un-published Now\n' +
              '</a>')
          } else {
            $(_this).replaceWith('<i class="fa fa-minus-square" style="color: red"></i>' +
              '<a href="#" data-comment_id="' + response.comment_id + '" data-is_publish="1" title="Publish now" class="btn-sm mr-1 btnPublishComment">\n' +
              'Publish Now' +
              '</a>')
          }
          swal.close()
        }
      },
      error: function (error) {
        console.log(error)
      }
    });
  });
}

$(document).ready(function () {
  new CommentAdmin();
});
