<?php

namespace Module\Comment\Repositories\Eloquent;

use Dnsoft\Core\Repositories\BaseRepository;
use Dnsoft\Core\Repositories\NestedRepositoryTrait;
use Module\Comment\Repositories\CommentRepositoryInterface;
use Request;

class CommentRepository extends BaseRepository implements CommentRepositoryInterface
{
    use NestedRepositoryTrait;

    public function paginateTree($itemPerPage)
    {
        $query = $this->model;
        $page_url = Request::input('page_url');
        if ($page_url) {
            $query = $query->whereHas('page', function ($q) use ($page_url) {
                $q->where('page_url', 'like', '%' . $page_url . '%');
            });
        }
        if (Request::input('content')) {
            $query = $query->where('content', Request::input('content'));
        }
        if (Request::input('created_at') && !Request::input('end_created_at')) {
            $query = $query->where('created_at', '>=', Request::input('created_at'));
        }
        if (!Request::input('created_at') && Request::input('end_created_at')) {
            $query = $query->where('created_at', '<=', Request::input('end_created_at'));
        }
        if (Request::input('created_at') && Request::input('end_created_at')) {
            $query = $query->whereBetween('created_at', [Request::input('created_at'), Request::input('end_created_at')]);
        }
        if (!is_null(Request::input('is_published'))) {
            $query = $query->where('is_published', Request::input('is_published'));
        }
        return $query->withDepth()->defaultOrder()->paginate($itemPerPage);
    }

    public function create(array $data)
    {
        $model = parent::create($data);

        $this->model->fixTree();

        return $model;
    }

    public function updateById(array $data, $id)
    {
        $model = parent::updateById($data, $id);

        $this->model->fixTree();

        return $model;
    }

    public function paginateTreePage($post, $itemPerPage)
    {
        return $this->model->where(['table_id' => $post->id, 'table_type' => get_class($post), 'is_published' => true, 'parent_id' => null])->paginate($itemPerPage);
    }
}
