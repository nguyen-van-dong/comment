<?php

use Module\Comment\Http\Controllers\Web\CommentRealtimeController;

use Module\Comment\Http\Controllers\web\CommentController;

Route::prefix('comment')->middleware(['cors'])->group(function () {
    Route::get('', [CommentController::class, 'index'])
        ->name('comment.web.comment.index');

    Route::get('load-comment', [CommentController::class, 'loadComments'])
        ->name('comment.web.comment.load-comments');

    Route::post('store', [CommentController::class, 'store'])
        ->name('comment.web.comment.store');

    Route::get('{id}/edit', [CommentController::class, 'edit'])
        ->name('comment.web.comment.edit');

    Route::post('update', [CommentController::class, 'update'])
        ->name('comment.web.comment.update');

    Route::delete('{id}/destroy', [CommentController::class, 'destroy'])
        ->name('comment.web.comment.destroy');

    Route::post('like', [CommentController::class, 'like'])
        ->name('comment.web.comment.like');

    Route::post('dislike', [CommentController::class, 'dislike'])
        ->name('comment.web.comment.dislike');
});

// Route::get('', [CommentRealtimeController::class, 'index'])->name('comment.web.comment.index');
