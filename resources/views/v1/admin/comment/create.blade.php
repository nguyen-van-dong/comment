@extends('core::v1.admin.master')

@section('meta_title', __('comment::message.create.page_title'))

@section('page_title', __('comment::message.create.page_title'))

@section('page_subtitle', __('comment::message.create.page_subtitle'))

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('dashboard::message.index.breadcrumb') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('comment.admin.comment.index') }}">{{ trans('comment::message.index.breadcrumb') }}</a></li>
            <li class="breadcrumb-item active">{{ trans('comment::message.create.breadcrumb') }}</li>
        </ol>
    </nav>
@stop

@section('content')
    <form action="{{ route('comment.admin.comment.store') }}" method="POST">
        @csrf

        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">
                            {{ __('comment::message.create.page_title') }}
                        </h6>
                    </div>
                    <div class="text-right">
	                    <div class="btn-group">
	                        <button class="btn btn-success" type="submit">{{ __('core::button.save') }}</button>
	                        <button class="btn btn-primary" name="continue" value="1" type="submit">{{ __('core::button.save_and_edit') }}</button>
	                    </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @include('comment::admin.comment._fields', ['item' => null])
            </div>
            <div class="card-footer text-right">
            	<div class="btn-group">
	                <button class="btn btn-success" type="submit">{{ __('core::button.save') }}</button>
	                <button class="btn btn-primary" name="continue" value="1" type="submit">{{ __('core::button.save_and_edit') }}</button>
                </div>
            </div>
        </div>
    </form>
@stop
