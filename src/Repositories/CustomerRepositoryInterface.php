<?php

namespace Module\Comment\Repositories;

use DnSoft\Core\Repositories\BaseRepositoryInterface;

interface CustomerRepositoryInterface extends BaseRepositoryInterface
{
    public function findByCondition(array $data);
}
