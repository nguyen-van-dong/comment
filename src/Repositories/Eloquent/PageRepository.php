<?php

namespace Module\Comment\Repositories\Eloquent;

use DnSoft\Core\Repositories\BaseRepository;
use Module\Comment\Repositories\PageRepositoryInterface;

class PageRepository extends BaseRepository implements PageRepositoryInterface
{
    public function findByCondition(array $data)
    {
        return $this->model->where($data)->get();
    }
}
