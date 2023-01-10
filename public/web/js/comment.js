
const socket = io('http://localhost:3003');

let getLocation = function (href) {
  let l = document.createElement("a");
  l.href = href;
  return l;
};
let l = getLocation(window.location.href);

let pageUrl = encodeURIComponent(l.hostname + l.pathname);

socket.emit("JOIN-ROOM", pageUrl);

socket.on('comment_approval', (data) => {
  appendComment(data);
});

$(document).ready(function () {
  let basePath = 'http://127.0.0.1:8000';
  if ($('#comment-area').length > 0) {
    let customerToken = $('#customer_token').val();
    let url = basePath + `/comment/load-comment?page_url=${pageUrl}&customer_token=${customerToken}&post_id=${$('#post_id').val()}`;
    let isAddCustomerInfo =
      '        <input class="item-info" name="name" id="cmt_name" placeholder="Enter your name">\n' +
      '        <input class="item-info" name="email_login" id="cmt_email" placeholder="Enter email">\n';
    getData(url).then(r => {
      if (r.success) {
        document.getElementById('comment-area').innerHTML = r.result;
        fillForm();
      } else {
        document.getElementById('comment-area').innerHTML =
          '<div id="append_comment"></div>' +
          '    <div class="comment">\n' +
          '    <div class="form-group">' +
          '    <textarea class="form-control" rows="3" name="content" id="cmt_content" required="" placeholder="Vui lòng nhập nội dung" spellcheck="false"></textarea></div>' + isAddCustomerInfo + ' \n' +
          '    <input class="btn-send" type="submit" value="Send" id="btnComment">\n' +
          '</div>'
      }
    });
  }

  // Like, Dislike
  $('body').on('click', '.btnLike, .btnDislike', function (e) {
    e.preventDefault();
    let _this = $(this);
    let commentId = _this.data('comment_id');
    let url = basePath + '/comment/';
    if (_this.attr('data-type') == 'like') {
      url += 'like';
      if (localStorage.getItem('arrayLike')) {
        let arrayLike = JSON.parse(localStorage.getItem('arrayLike'));
        if (arrayLike.indexOf(commentId) >= 0) {
          showToastMessage('You have been like before.', null, 'red');
          return false;
        }
      }
    } else {
      url += 'dislike';
      if (localStorage.getItem('arrayDislike')) {
        let arrayDislike = JSON.parse(localStorage.getItem('arrayDislike'));
        if (arrayDislike.indexOf(commentId) >= 0) {
          showToastMessage('You have been dislike before.', null, 'red');
          return false;
        }
      }
    }
    $.ajax({
      url: url,
      method: 'POST',
      data: {
        id: commentId
      },
      success: function (response) {
        if (response.success) {
          if (_this.attr('data-type') == 'like') {
            _this.text(response.countLike + ' Like');
            let arrayLike = [];
            if (localStorage.getItem('arrayLike')) {
              arrayLike = JSON.parse(localStorage.getItem('arrayLike'));
            }
            arrayLike.push(commentId);
            localStorage.setItem('arrayLike', JSON.stringify(arrayLike));
          } else {
            _this.text(response.countDislike + ' Dislike');
            let arrayDislike = [];
            if (localStorage.getItem('arrayDislike')) {
              arrayDislike = JSON.parse(localStorage.getItem('arrayDislike'));
            }
            arrayDislike.push(commentId);
            localStorage.setItem('arrayDislike', JSON.stringify(arrayDislike));
          }
        }
      }
    });
  });

  $('body').on('click', '.openReplyComment', function (event) {
    event.preventDefault();
    $('.comment-row').css('display', 'none');
    let commentId = $(this).data('comment_id');
    let openComment = '.comment-row-' + commentId;

    $(openComment).css('display', 'block');

    let customerName = '.name-' + commentId;
    $('#contentReply' + commentId).val('@' + $(customerName).text() + ': ');

    let parentId = $(this).data('comment_id');
    $('#parentCommentId').val(parentId);

    fillForm();
  });

  $('body').on('click', '.btnCancel', function (event) {
    event.preventDefault();
    let commentId = $(this).data('comment_id');
    let openComment = '.comment-row-' + commentId;
    $(openComment).css('display', 'none');
  });

  $('body').on('click', '.btnDelete', function (event) {
    event.preventDefault();
    if (confirm('Are you sure delete this comment?')) {
      let commentId = $(this).data('comment_id');
      $.ajax({
        url: basePath + '/comment/' + commentId + '/destroy',
        method: 'DELETE',
        success: function (response) {
          if (response.success) {
            let thisItem = '.row-item-' + commentId;
            $(thisItem).remove();
            for (let i = 0; i < response.ids.length; i++) {
              let item = '.row-item-' + response.ids[i];
              $(item).remove();
            }
          }
        }
      });
    }
    return false;
  });

  $('body').on('click', '.btnEdit', function (event) {
    event.preventDefault();
    let commentId = $(this).data('comment_id');
    let commentContent = '.content-comment-' + commentId;
    $.ajax({
      url: basePath + '/comment/' + commentId + '/edit',
      success: function (response) {
        $(commentContent).css('display', 'none');
        let html = '<input type="text" class="comment-edit content-edit-' + response.item.id + '" value="' + response.item.content + '">' +
          '<span class="action-update">' +
          '<a href="#" class="update-comment" data-comment-id="' + response.item.id + '" style="margin-right: 5px">Update</a>' +
          '<a href="#" class="cancel-edit" data-comment-id="' + response.item.id + '">Cancel</a>' +
          '</span>';
        let editCommentSelector = '.edit-comment-' + commentId;
        $(editCommentSelector).html(html);
      }
    });
  });

  $('body').on('click', '.update-comment', function (event) {
    event.preventDefault();
    let commentId = $(this).data('comment-id');
    let content = '.content-edit-' + commentId;
    let contentComment = $(content).val();
    $.ajax({
      url: basePath + '/comment/update',
      method: 'POST',
      data: {
        commentId: commentId,
        content: contentComment
      },
      success: function (response) {
        let oldContentCmt = '.content-comment-' + commentId;
        $(oldContentCmt).css('display', 'block');
        $(oldContentCmt).text(contentComment);

        let editingComment = '.edit-comment-' + commentId;
        $(editingComment).children().remove();
      },
      error: function (error) {
        console.log(error)
      }
    });
  });

  $('body').on('click', '.cancel-edit', function (event) {
    event.preventDefault();
    let commentId = $(this).data('comment-id');
    let editCmt = '.edit-comment-' + commentId;
    $(editCmt).children().remove();
    let contentCmt = '.content-comment-' + commentId;
    $(contentCmt).css('display', 'block');
  });

  $('body').on('click', '.btnReply', function (event) {
    event.preventDefault();
    let commentId = $(this).data('comment_id');
    let content = $('#contentReply' + commentId).val();
    let name = $('#nameReply' + commentId).val();
    let email = $('#emailReply' + commentId).val();
    if (content.length == 0 || name.length == 0 || email.length == 0) {
      showToastMessage('The content, the name and the email is required', null, 'red');
      return;
    }
    let data = {
      content: content,
      email: email,
      name: name,
      page_url: pageUrl,
      parent_id: $('#parentCommentId').val(),
      customer_token: $('#customer_token').val(),
      post_id: $('#post_id').val()
    };
    localStorage.setItem('customer_info', JSON.stringify(data));
    $.ajax({
      url: basePath + '/comment/store',
      method: 'POST',
      data: data,
      success: function (response) {
        $('#contentReply' + commentId).val('');
        socket.emit("COMMENT_CREATED", data);
        showToastMessage('Đã thêm bình luận thành công!');
      },
      error: function (error) {
        let errors = error.responseJSON.errors;
        let txt = '';
        for (const errorKey in errors) {
          txt += errors[errorKey][0] + '\n';
        }
        $('#flag_notification').text(txt);
        showToastMessage(txt)
      }
    });
  });

  $('body').on('click', '#btnComment', function (e) {
    e.preventDefault();
    let content = $('#cmt_content').val();
    let name = $('#cmt_name').val();
    let email = $('#cmt_email').val();
    if (content.length == 0 || name.length == 0 || email.length == 0) {
      showToastMessage('The content, the name and the email is required', 'right', '#ff7474');
      return;
    }
    let data = {
      content: content,
      email: email,
      name: name,
      page_url: pageUrl,
      parent_id: null,
      customer_token: $('#customer_token').val(),
      post_id: $('#post_id').val()
    };
    $.ajax({
      url: basePath + '/comment/store',
      method: 'POST',
      data: data,
      success: function (response) {
        $('#content').val('');
        showToastMessage('Đã thêm bình luận thành công!');
        socket.emit("COMMENT_CREATED", data);
      },
      error: function (error) {
        let errors = error.responseJSON.errors;
        let txt = '';
        for (const errorKey in errors) {
          txt += errors[errorKey][0] + '\n';
        }
        $('#flag_notification').text(txt);
        alert(txt)
      }
    });
  });

  if (localStorage.getItem('customer_info')) {
    let customerInfo = JSON.parse(localStorage.getItem('customer_info'));
    $('#cmt_name').val(customerInfo.name);
    $('#cmt_email').val(customerInfo.email);
  }

  $('body').on('click', '#load-more-root-cmt', function (event) {
    event.preventDefault();
    let page = $('#currentCommentPage').val();
    if (!page) {
      page = $('.pagination a').attr('href').split('page=')[1];
    } else {
      page++;
    }
    getDataPaginate(page);
  });

  function getDataPaginate(page) {
    $.ajax({
      url: basePath + '/comment/load-comment?page_url=' + pageUrl + '&page=' + page,
      type: "get",
      datatype: "html"
    }).done(function (data) {
      $('#currentCommentPage').val(page);
      if (data.result) {
        $('#append_comment').append(data.result);
      } else {
        showToastMessage('There are no more comments.', 'right', '#ff7474');
      }
    }).fail(function (jqXHR, ajaxOptions, thrownError) {
      alert('No response from server');
      console.log(jqXHR);
      console.log(thrownError);
    });
  }
});

async function getData(url = '') {
  const response = await fetch(url, {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json'
    }
  });
  return response.json();
}

async function postComment(url = '', data = {}) {
  const response = await fetch(url, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(data)
  });
  return response.json();
}

function fillForm() {
  if (localStorage.getItem('customer_info')) {
    let customerInfo = JSON.parse(localStorage.getItem('customer_info'));
    $('.nameReply').val(customerInfo.name);
    $('.emailReply').val(customerInfo.email);
    $('#cmt_name').val(customerInfo.name);
    $('#cmt_email').val(customerInfo.email);
  }
}

function appendComment(comment) {
  let isExisted = '.row-item-' + comment.id;
  if (comment.is_published == 1 && $(isExisted).length == 0) {
    let parentId = comment.parent_id;
    let commentId = comment.id;
    let depth = (comment.depth * 30) + 5;
    let isDisplayEditDelete = '';
    if (comment.customer_token == $('#customer_token').val()) {
      isDisplayEditDelete = '<span class="action-item"><a href="#" class="ml-3 btnDislike" data-comment_id="' + comment.id + '">' + comment.dislike + ' Dislike</a></span>\n' +
        '<span class="action-item"><a href="#" class="ml-3 btnEdit" data-comment_id="' + comment.id + '"> Edit</a></span>\n';
    }
    let htmlCmt = '<div class="mt-3 row-item-' + comment.id + ' row-parent-id-' + parentId + '" style=" margin-left: ' + depth + 'px;">\n' +
      '    <div class="col-md-10">\n' +
      '        <a class="float-left customer-name" href="#"><strong class="name-' + comment.id + '">' + comment.customer_name + '</strong></a><br>\n' +
      '        <div class="content-comment-' + comment.id + '">' + comment.content + '</div>\n' +
      '        <div class="edit-comment-' + comment.id + '" style="display: flex"></div>\n' +
      '        <span>\n' +
      '            <span class="action-item"><a href="#" data-comment_id="' + comment.id + '" data-parent-id="" class="openReplyComment">Reply</a></span>\n' +
      '            <span class="action-item"><a href="#" data-type="like" class="ml-3 btnLike" data-comment_id="' + comment.id + '">' + comment.like + ' Like</a></span> ' + isDisplayEditDelete + ' \n' +
      '            <span><a href="#" class="ml-3 btnDelete" data-comment_id="' + comment.id + '"> Delete </a></span>\n' +
      '            <span style="margin-left: 15px">' + comment.diffForHumans + '</span>\n' +
      '        </span>\n' +
      '    </div>\n' +
      '\n' +
      '    <div style="display: none" class="comment-row-' + comment.id + ' comment-row">\n' +
      '        <textarea class="form-control" id="contentReply' + comment.id + '" rows="3" placeholder="Please enter your content"></textarea><br>\n' +
      '        <div class="row customer-info">\n' +
      '            <input class="item-info-reply nameReply" name="name" id="nameReply' + comment.id + '" required="" placeholder="Name">\n' +
      '            <input class="item-info-reply emailReply" name="email_login" id="emailReply' + comment.id + '" required="" placeholder="Email">\n' +
      '            <input type="submit" class="btnCancel" data-comment_id="' + comment.id + '" value="Cancel" name="btnCancel">\n' +
      '            <input type="submit" class="btn-send btnReply" data-comment_id="' + comment.id + '" value="Send">\n' +
      '        </div>\n' +
      '    </div>\n' +
      '</div>';
    if (parentId) {
      let positionAppend = '.row-item-';
      if (comment.sibling_node_id) {
        positionAppend += comment.sibling_node_id;
      } else if (comment.parent_node_id) {
        positionAppend += comment.parent_node_id;
      } else {
        positionAppend = '#append_comment';
      }
      if (comment.sibling_created_at) {
        if (comment.created_at > comment.sibling_created_at) {
          $(htmlCmt).insertAfter(positionAppend);
        } else {
          $(htmlCmt).insertBefore(positionAppend);
        }
      } else {
        $(htmlCmt).insertAfter(positionAppend);
      }
    } else {
      if (comment.created_at > comment.sibling_created_at) {
        $(htmlCmt).insertAfter('#append_comment');
      } else {
        $(htmlCmt).insertBefore('#append_comment');
      }
      // $('#append_comment').append(htmlCmt);
    }
  } else if (comment.is_published == 0 && $(isExisted).length > 0) {
    $(isExisted).remove();
    let childCmt = '.row-parent-id-' + comment.id;
    $(childCmt).remove();
  }
}

function showToastMessage(message, position = 'right', background = null) {
  if (!background) {
    background = "linear-gradient(to right, #00b09b, #96c93d)"
  }
  Toastify({
    text: message,
    duration: 3000,
    newWindow: true,
    close: true,
    gravity: "top", // `top` or `bottom`
    position: position, // `left`, `center` or `right`
    stopOnFocus: true, // Prevents dismissing of toast on hover
    style: {
      background: background,
    },
    onClick: function(){} // Callback after click
  }).showToast();
}
