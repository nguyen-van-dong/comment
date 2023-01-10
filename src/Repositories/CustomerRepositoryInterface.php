<?php

namespace Module\Comment\Repositories;

use Dnsoft\Core\Repositories\BaseRepositoryInterface;

interface CustomerRepositoryInterface extends BaseRepositoryInterface
{
    public function findByCondition(array $data);
}
