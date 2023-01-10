<?php

namespace Module\Comment\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Module\Comment\Events\CommentApproved;
use Module\Comment\Http\Requests\CommentRequest;
use Module\Comment\Models\Comment;
use Module\Comment\Repositories\CommentRepositoryInterface;

class CommentController extends Controller
{
    /**
     * @var CommentRepositoryInterface
     */
    protected $commentRepository;

    public function __construct(CommentRepositoryInterface $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function index(Request $request)
    {
        $items = $this->commentRepository->paginateTree($request->input('max', 20));

        return view('comment::admin.comment.index', compact('items'));
    }

    public function create()
    {
        return view('comment::admin.comment.create');
    }

    public function store(CommentRequest $request)
    {
        $item = $this->commentRepository->create($request->all());

        if ($request->input('continue')) {
            return redirect()
                ->route('comment.admin.comment.edit', $item->id)
                ->with('success', __('comment::message.notification.created'));
        }

        return redirect()
            ->route('comment.admin.comment.index')
            ->with('success', __('comment::message.notification.created'));
    }

    public function edit($id)
    {
        $item = $this->commentRepository->find($id);

        return view('comment::admin.comment.edit', compact('item'));
    }

    public function update(CommentRequest $request, $id)
    {
        $item = $this->commentRepository->updateById($request->all(), $id);

        if ($request->input('continue')) {
            return redirect()
                ->route('comment.admin.comment.edit', $item->id)
                ->with('success', __('comment::message.notification.updated'));
        }

        return redirect()
            ->route('comment.admin.comment.index')
            ->with('success', __('comment::message.notification.updated'));
    }

    public function destroy($id, Request $request)
    {
        $this->commentRepository->delete($id);

        if ($request->wantsJson()) {
            Session::flash('success', __('comment::message.notification.deleted'));
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->route('comment.admin.comment.index')
            ->with('success', __('comment::message.notification.deleted'));
    }

    public function publish(Request $request)
    {
        $comment = Comment::withDepth()->find($request->comment_id);
        $comment->update(['is_published' => $request->is_publish]);
        $data = $comment->toArray();
        $data['depth'] = $comment->depth;
        $data['customer_name'] = $comment->customer->name;
        $data['room'] = $comment->page->page_url;
        $data['diffForHumans'] = $comment->created_at->diffForHumans();
        if ($comment->getPrevSibling() && $comment->parent) {
            $data['sibling_node_id'] = $comment->getPrevSibling()->id;
            $data['sibling_created_at'] = $comment->getPrevSibling()->created_at;
        }
        if ($comment->parent) {
            $data['parent_node_id'] = $comment->parent->id;
        }
        event(new CommentApproved($data));

        return response()->json(['success' => true, 'is_published' => $comment->is_published, 'comment_id' => $comment->id]);
    }
}
