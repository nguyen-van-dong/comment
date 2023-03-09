<div class="comment-container">
    <input type="hidden" id="parentCommentId">
    <?php
        $traverse = function ($comments, $prefix = 5) use (&$traverse, $customer_token) {
        foreach ($comments as $comment) { ?>
            @if ($comment->is_published)
                <div class="mt-3 row-item-{{ $comment->id }} row-parent-id-{{ $comment->parent_id }}" style="border: 1px #ece3e3 solid; border-radius: 8px; padding: 10px; margin-left: {{$prefix}}px;">
                    <div class="col-md-10">
                        <a class="float-left customer-name" href="#"><strong class="name-{{ $comment->id }}">{{ $comment->customer->name }}</strong></a><br>
                        <div class="content-comment-{{ $comment->id }}" style="margin-top: 0.4rem; margin-bottom: 1rem; line-height: 1.5rem">{{ $comment->content }}</div>
                        <div class="edit-comment-{{ $comment->id }}" style="display: flex"></div>
                        <span class="action-area">
                            <span class="action-item"><a href="#" data-comment_id="{{ $comment->id }}" data-parent-id="{{ $comment->parent_id }}" class="openReplyComment"><i class="fa-solid fa-reply"></i></a></span>
                            <span class="action-item"><a href="#" data-type="like" class="ml-3 btnLike" data-comment_id="{{ $comment->id }}">{{ $comment->like > 0 ? $comment->like : 0 }} <i class="fa-solid fa-thumbs-up"></i></a></span>
                            <span class="action-item"><a href="#" class="ml-3 btnDislike" data-comment_id="{{ $comment->id }}" >{{ $comment->dislike > 0 ? $comment->dislike : 0 }} <i class="fa-solid fa-thumbs-down"></i></a></span>
                            @if ($customer_token == $comment->customer_token)
                                <span class="action-item"><a href="#" class="ml-3 btnEdit" data-comment_id="{{ $comment->id }}"> Edit</a></span>
                                <span class="action-item"><a href="#" class="ml-3 btnDelete" data-comment_id="{{ $comment->id }}"> Delete </a></span>
                            @endif
                            <span class="date_created">{{ $comment->created_at->diffForHumans() }}</span>
                        </span>
                    </div>

                    <div class="mt-3 comment-row-{{ $comment->id }} comment-row">
                        <div class="form-group">                      
                            <textarea class="form-control" id="contentReply{{ $comment->id }}" rows="3" placeholder="Please enter your content"></textarea>
                        </div>  
                        <div class="customer-info">
                            <input class="item-info-reply nameReply" id="nameReply{{ $comment->id }}" required placeholder="{{ __('comment::message.name') }}">
                            <input class="item-info-reply emailReply" id="emailReply{{ $comment->id }}" required placeholder="{{ __('comment::message.email') }}">
                            <input class="btnCancel" type="submit" data-comment_id="{{ $comment->id }}" value="{{ __('comment::message.cancel') }}" name="btnCancel">
                            <input class="btn-send btnReply" type="submit" data-comment_id="{{ $comment->id }}" value="{{ __('comment::message.send') }}">
                        </div>
                    </div>
                </div>
            @endif
        <?php $traverse($comment->children, $prefix + 30); }
        };
        $traverse($items);
    ?>

    <div id="append_comment"></div>

    @if ($items[0])
        @if (total_comments($items[0]->page_id) > config('comment.paginate', 20))
            <div style="display: none">
                {{ $items->links() }}
            </div>
            <div class="row" id="load-more">
                <input type="hidden" id="currentCommentPage">
                <a href="#" id="load-more-root-cmt"> Load more</a>
            </div>
        @endif
    @endif

    <div class="comment">
        <div>
            <textarea class="form-control" id="cmt_content" placeholder="Please enter your content"></textarea><br>
        </div>
        <input class="item-info" name="name" id="cmt_name" required placeholder="{{ __('comment::message.name') }}">
        <input class="item-info" name="email_login" id="cmt_email" required placeholder="{{ __('comment::message.email') }}">
        <input class="btn-send" type="submit" value="{{ __('comment::message.send') }}" name="btnComment" id="btnComment">
    </div>
</div>
