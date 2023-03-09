<?php

namespace Module\Comment\Repositories\Eloquent;

use DnSoft\Core\Repositories\BaseRepository;
use Module\Comment\Repositories\CustomerRepositoryInterface;

class CustomerRepository extends BaseRepository implements CustomerRepositoryInterface
{
    public function findByCondition(array $data)
    {
        return $this->model->where($data)->get();
    }
}
