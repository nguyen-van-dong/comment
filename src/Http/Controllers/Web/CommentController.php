<?php

namespace Module\Comment\Http\Controllers\Web;

use Illuminate\Routing\Controller;

class CommentController extends Controller
{
    public function index()
    {
        $version = get_version_actived();
        return view("comment::$version.web.comment.index");
    }
}
