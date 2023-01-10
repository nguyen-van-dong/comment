<?php

use Module\Comment\Http\Controllers\Admin\CommentController;

Route::prefix('comment')->group(function () {
    Route::get('', [CommentController::class, 'index'])
        ->name('comment.admin.comment.index')
        ->middleware('admin.can:comment.admin.comment.index');

    Route::get('create', [CommentController::class, 'create'])
        ->name('comment.admin.comment.create')
        ->middleware('admin.can:comment.admin.comment.create');

    Route::post('/', [CommentController::class, 'store'])
        ->name('comment.admin.comment.store')
        ->middleware('admin.can:comment.admin.comment.create');

    Route::get('{id}/edit', [CommentController::class, 'edit'])
        ->name('comment.admin.comment.edit')
        ->middleware('admin.can:comment.admin.comment.edit');

    Route::put('{id}', [CommentController::class, 'update'])
        ->name('comment.admin.comment.update')
        ->middleware('admin.can:comment.admin.comment.edit');

    Route::delete('{id}', [CommentController::class, 'destroy'])
        ->name('comment.admin.comment.destroy')
        ->middleware('admin.can:comment.admin.comment.destroy');

    Route::post('publish', [CommentController::class, 'publish'])
        ->name('comment.admin.comment.publish')
        ->middleware('admin.can:comment.admin.comment.publish');
});
