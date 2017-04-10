<?php


namespace App\Filter;


class BranchFilter extends QueryFilters
{
    public function branch($value)
    {
        return (!$this->requestAllData($value)) ? $this->builder->where('cabang', 'like', '%'.$value.'%') : null ;
    }
}