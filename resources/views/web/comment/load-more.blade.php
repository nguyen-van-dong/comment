<?php
$traverse = function ($comments, $prefix = 5) use (&$traverse, $customer_token) {
foreach ($comments as $comment) { ?>
    @if ($comment->is_published)
        <div class="row-item-{{ $comment->id }}" style=" margin-left: {{$prefix}}px;">
            <div class="col-md-10">
                <a class="float-left customer-name" href="#" ><strong class="name-{{ $comment->id }}">{{ $comment->customer->name }}</strong></a><br>
                <div class="content-comment-{{ $comment->id }}">{{ $comment->content }}</div>
                <div class="edit-comment-{{ $comment->id }}" style="display: flex"></div>
                <span>
                    <span class="action-item"><a href="#" data-comment_id="{{ $comment->id }}" data-parent-id="{{ $comment->parent_id }}" class="openReplyComment">Reply</a></span>
                    <span class="action-item"><a href="#" data-type="like" class="ml-3 btnLike" data-comment_id="{{ $comment->id }}">{{ $comment->like > 0 ? $comment->like : '' }} Like</a></span>
                    <span class="action-item"><a href="#" class="ml-3 btnDislike" data-comment_id="{{ $comment->id }}" >{{ $comment->dislike > 0 ? $comment->dislike : '' }} Dislike</a></span>
                    @if ($customer_token == $comment->customer_token)
                        <span class="action-item"><a href="#" class="ml-3 btnEdit" data-comment_id="{{ $comment->id }}"> Edit</a></span>
                        <span><a href="#" class="ml-3 btnDelete" data-comment_id="{{ $comment->id }}"> Delete </a></span>
                    @endif
                    <span class="date_created">{{ $comment->created_at->diffForHumans() }}</span>
                </span>
            </div>

            <div style="display: none" class="comment-row-{{ $comment->id }} comment-row">
                <textarea class="form-control" id="contentReply{{ $comment->id }}" rows="3" placeholder="Please enter your content"></textarea><br>
                <div class="row customer-info">
                    @if (!$customer_token)
                        <input class="item-info-reply nameReply" name="name" id="nameReply{{ $comment->id }}" required placeholder="{{ __('comment::message.name') }}">
                        <input class="item-info-reply emailReply" name="email_login" id="emailReply{{ $comment->id }}" required placeholder="{{ __('comment::message.email') }}">
                    @endif
                    <input type="submit" class="btnCancel" data-comment_id="{{ $comment->id }}" value="{{ __('comment::message.cancel') }}" name="btnCancel">
                    <input type="submit" class="btn-send btnReply" data-comment_id="{{ $comment->id }}" value="{{ __('comment::message.send') }}">
                </div>
            </div>
        </div>
    @endif
<?php $traverse($comment->children, $prefix + 30); }
};
$traverse($items);
?>
