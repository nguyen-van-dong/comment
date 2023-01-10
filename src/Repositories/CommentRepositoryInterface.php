<?php

namespace Module\Comment\Repositories;

use Dnsoft\Core\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

interface CommentRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param $itemPerPage
     * @return mixed
     */
    public function paginateTree($itemPerPage);

    /**
     * @param $pageId
     * @param $itemPerPage
     * @return mixed
     */
    public function paginateTreePage($pageId, $itemPerPage);
}
