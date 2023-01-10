<!-- @include('comment::admin.comment.search') -->
<table class="table table-striped table-bordered dt-responsive nowrap bootstrap4-styling">
    <thead>
    <tr>
        <th>{{ __('ID') }}</th>
        <th>{{ __('comment::message.page_url') }}</th>
        <th>{{ __('comment::message.content') }}</th>
        <th>{{ __('comment::message.created_at') }}</th>
        <th>{{ __('comment::message.is_published') }}</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach($items as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td><a target="_blank" href="{{ $item->page->page_url }}">{{ truncate($item->page->page_url) }}</a></td>
            <td>{{ trim(str_pad('', $item->depth * 3, '-')) }}{{ truncate($item->content, 50) }}</td>
            <td>{{ $item->created_at }}</td>
            <td>
                @if ($item->is_published)
                    <p data-comment_id="{{ $item->id }}" title="Un-published" data-is_publish="0" class="btn btn-success-soft btn-sm mr-1 btnUnPublish">
                        Un-published
                    </p>
                @else
                    <a data-comment_id="{{ $item->id }}" title="Publish now" data-is_publish="1" class="btn btn-danger-soft btn-sm mr-1 btnPublishComment">
                        <i class="fal fa-times-circle" style="color: red"></i>
                    </a>
                @endif
            </td>
            <td class="text-right">

                @admincan('comment.admin.comment.create')
                <a href="{{ route('comment.admin.comment.create', ['id' => $item->id, 'parent_id' => $item->id]) }}" class="btn btn-primary-soft btn-sm mr-1">
                    <i class="fas fa-plus"></i>
                </a>
                @endadmincan

                @admincan('comment.admin.comment.edit')
                <a href="{{ route('comment.admin.comment.edit', $item->id) }}" class="btn btn-success-soft btn-sm mr-1">
                    <i class="fas fa-pencil-alt"></i>
                </a>
                @endadmincan

                @admincan('comment.admin.comment.destroy')
                <table-button-delete url-delete="{{ route('comment.admin.comment.destroy', $item->id) }}"></table-button-delete>
                @endadmincan
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

{!! $items->appends(Request::all())->render() !!}
