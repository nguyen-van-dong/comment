<form class="form-inline newnet-table-search">
    @input(['item' => null, 'name' => 'page_url', 'label' => __('comment::message.page_url'), 'value' => request('page_url')])
    @input(['item' => null, 'name' => 'content', 'label' => __('comment::message.content'), 'value' => request('content')])
    @datetimeinput(['name' => 'created_at', 'label' => __('comment::message.created_at'), 'item' => null,  'value' => request('created_at')])
    @datetimeinput(['name' => 'end_created_at', 'label' => __('comment::message.end_created_at'), 'item' => null,  'value' => request('end_created_at')])

    @select(['name' => 'is_published', 'label' => __('comment::message.status'), 'options' => [
        ['value' => '1', 'label' => __('comment::message.is_published')],
        ['value' => '0', 'label' => __('comment::message.not_show')],
    ], 'item' => null, 'value' => request('is_published')])

    <button type="submit" class="btn btn-primary mr-1">
        {{ __('core::button.search') }}
    </button>
    <a href="{{ route('comment.admin.comment.index') }}" class="btn btn-danger">
        {{ __('core::button.cancel') }}
    </a>
</form>