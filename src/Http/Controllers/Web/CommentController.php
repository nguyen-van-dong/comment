<?php

namespace Module\Comment\Http\Controllers\Web;

use Illuminate\Routing\Controller;

class CommentController extends Controller
{
    public function index()
    {
        return view('comment::web.comment.index');
    }
}
