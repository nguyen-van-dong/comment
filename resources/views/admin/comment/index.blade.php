@extends('core::admin.master')

@section('meta_title', __('comment::message.index.page_title'))

@section('page_title', __('comment::message.index.page_title'))

@section('page_subtitle', __('comment::message.index.page_subtitle'))

@section('content-header')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('dashboard::message.index.breadcrumb') }}</a></li>
                        <li class="breadcrumb-item active">{{ trans('comment::message.index.breadcrumb') }}</li>
                    </ol>
                </div>
                <h4 class="page-title">{{ __('Comment') }}</h4>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('vendor/comment/admin/js/comment.js') }}"></script>
@endpush
@section('content')
    <div class="card mb-4">
        <div class="card-body">
            <h1>How to set up the comment widget</h1>
            <hr>
            <code>
                <h4>Add div element where you want to display comments: {{ '<div id="comment-area"></div>' }}</h4>
                <h4>Add input with type hidden in this page: {{ '<input type="hidden" value="YOUR CUSTOMER TOKEN (ID)" id="customer_token">' }}</h4>
                <h4>Add socket io client: {{ '<script src="https://cdn.socket.io/4.5.4/socket.io.min.js"></script>' }}</h4>
                <h4>Add script in footer page: {{
                                '<script src="' . url('/vendor/comment/web/js/comment.js') . '"></script>'
                                }}</h4>
                <h4>Add css: {{ '<link rel="stylesheet" href="'. url('/vendor/comment/web/css/comment.css').'">' }}</h4>
                <h4>If your website do not include jquery. Please add it in the footer.
                {{ '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>' }}</h4>
            </code>
        </div>
    </div>
@stop
