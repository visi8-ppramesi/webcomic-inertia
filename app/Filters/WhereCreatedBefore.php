<?php

namespace App\Filters;

class WhereCreatedBefore extends Filter{
    public $noParam = false;
    protected function applyFilter($builder){
        return $builder->whereDate('created_at', '<=', $this->getRequestFilterValue());
    }
}
