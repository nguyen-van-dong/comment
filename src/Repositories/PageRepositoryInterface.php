<?php

namespace Module\Comment\Repositories;

use DnSoft\Core\Repositories\BaseRepositoryInterface;

interface PageRepositoryInterface extends BaseRepositoryInterface
{
    public function findByCondition(array $data);
}
