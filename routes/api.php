<?php

use Module\Comment\Http\Controllers\Api\CommentController;

Route::prefix('comment')->middleware(['cors'])->group(function () {
    Route::get('', [CommentController::class, 'index'])
        ->name('comment.api.comment.index');

    Route::get('load-comment', [CommentController::class, 'loadComments'])
        ->name('comment.api.comment.load-comments');

    Route::post('store', [CommentController::class, 'store'])
        ->name('comment.api.comment.store');

    Route::get('{id}/edit', [CommentController::class, 'edit'])
        ->name('comment.api.comment.edit');

    Route::post('update', [CommentController::class, 'update'])
        ->name('comment.api.comment.update');

    Route::delete('{id}/destroy', [CommentController::class, 'destroy'])
        ->name('comment.api.comment.destroy');

    Route::post('like', [CommentController::class, 'like'])
        ->name('comment.api.comment.like');

    Route::post('dislike', [CommentController::class, 'dislike'])
        ->name('comment.api.comment.dislike');
});
