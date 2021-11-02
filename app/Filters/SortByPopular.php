<?php

namespace App\Filters;

use App\Models\Setting;

class SortByPopular extends Filter{
    public $noParam = false;
    protected function applyFilter($builder){
        $popularComics = Setting::getValue('dashboard.popular_comics');
        return $builder->whereIn('id', $popularComics)->orderByRaw("FIELD(id, " . implode(',', $popularComics) . ")");
    }
}
