<?php

namespace Module\Comment\Repositories;

use Dnsoft\Core\Repositories\BaseRepositoryInterface;

interface PageRepositoryInterface extends BaseRepositoryInterface
{
    public function findByCondition(array $data);
}
