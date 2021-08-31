<?php

namespace App\Filters;

class Get extends Filter{
    public $noParam = false;
    protected function applyFilter($builder){
        return $builder;
    }
}
