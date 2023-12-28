<?php

namespace Module\Comment\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Module\Comment\Repositories\CustomerRepositoryInterface;

class CustomerController extends Controller
{
    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function index(Request $request)
    {
        $items = $this->customerRepository->paginate($request->input('max', 20));
        
        return view("comment::admin.comment.index", compact('items'));
    }

    public function create()
    {
        
        return view("comment::admin.comment.create");
    }

    public function store(Request $request)
    {
        $item = $this->customerRepository->create($request->all());

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
        $item = $this->customerRepository->find($id);
        
        return view("comment::admin.comment.edit", compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = $this->customerRepository->updateById($request->all(), $id);

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
        $this->customerRepository->delete($id);

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
}
