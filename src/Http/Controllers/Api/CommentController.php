<?php

namespace Module\Comment\Http\Controllers\Api;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Module\Cms\Models\Post;
use Module\Comment\Events\CommentCreated;
use Module\Comment\Http\Requests\CommentRequest;
use Module\Comment\Models\Page;
use Module\Comment\Repositories\CommentRepositoryInterface;
use Module\Comment\Repositories\CustomerRepositoryInterface;
use Module\Comment\Repositories\PageRepositoryInterface;
use Throwable;

class CommentController extends Controller
{
    /**
     * @var CommentRepositoryInterface
     */
    protected $commentRepository;
    /**
     * @var PageRepositoryInterface
     */
    private $pageRepository;
    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    public function __construct(
        CommentRepositoryInterface $commentRepository,
        PageRepositoryInterface $pageRepository,
        CustomerRepositoryInterface $customerRepository)
    {
        $this->commentRepository = $commentRepository;
        $this->pageRepository = $pageRepository;
        $this->customerRepository = $customerRepository;
    }

    /**
     * @param  Request  $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function loadComments(Request $request)
    {
        $customer_token = $request->customer_token;
        $post = Post::find($request->post_id);
        $items = $this->commentRepository->paginateTreePage($post, config('comment.paginate', 20));
        
        if ($request->ajax()) {
            $view = view("comment::web.comment.load-more", compact(['items', 'customer_token']))->render();
        } else {
            $view = view("comment::web.comment.comment", compact(['items', 'customer_token']))->render();
        }
        return response()->json(['success' => true, 'result' => $view]);
    }

    /**
     * @param  CommentRequest  $request
     * @return JsonResponse
     */
    public function store(CommentRequest $request)
    {
        $params = $request->all();
        $page = $this->pageRepository->findByCondition(['page_url' => $params['page_url']]);
        if ($page->count() > 0) {
            $page = $page->first();
        } else {
            $page = $this->pageRepository->create($request->all());
        }
        if (!$params['parent_id']) {
            unset($params['parent_id']);
        }
        $customer = $this->customerRepository->findByCondition(['email' => $params['email']]);
        if ($customer->count() > 0) {
            $customer = $customer->first();
        } else {
            $customer = $this->customerRepository->create($request->all());
        }
        $params['customer_id'] = $customer->id;
        $params['page_id'] = $page->id;
        $params['is_published'] = false;
        $item = $this->commentRepository->create($params);

        $post = Post::find($request->post_id);

        $item->table()->associate($post);

        $item->save();

        event(new CommentCreated($item));

        \LogActivity::addToLog('Add comment with commentId = '.$item->id);

        return response()->json(['success' => true, 'comment' => $item]);
    }

    /**
     * @param  Request  $request
     * @param $id
     * @return Application|Factory|View
     */
    public function edit(Request $request, $id)
    {
        $item = $this->commentRepository->find($id);
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'item' => $item,
            ]);
        }
        
        return view("comment::admin.comment.edit", compact('item'));
    }

    /**
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function update(Request $request)
    {
        $item = $this->commentRepository->updateById($request->all(), $request->commentId);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => __('comment::message.notification.updated')]);
        }

        return redirect()
            ->route('comment.admin.comment.index')
            ->with('success', __('comment::message.notification.updated'));
    }

    /**
     * @param $id
     * @param  Request  $request
     * @return JsonResponse|RedirectResponse
     */
    public function destroy($id, Request $request)
    {
        $item = $this->commentRepository->getById($id);
        $ids = $item->descendants->pluck('id')->toArray();
        $this->commentRepository->delete($id);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' =>  __('comment::message.notification.deleted'),
                'ids' => $ids,
            ]);
        }

        return redirect()
            ->route('comment.admin.comment.index')
            ->with('success', __('comment::message.notification.deleted'));
    }

    public function like(Request $request)
    {
        $comment = $this->commentRepository->getById($request->id);
        $comment = $this->commentRepository->updateById([
            'like' => $comment->like + 1
        ], $request->id);
        return response()->json(['countLike' => $comment->like, 'success' => true]);
    }

    public function dislike(Request $request)
    {
        $comment = $this->commentRepository->getById($request->id);
        $comment = $this->commentRepository->updateById([
            'dislike' => $comment->dislike + 1
        ], $request->id);
        return response()->json(['countDislike' => $comment->dislike, 'success' => true]);
    }
}
