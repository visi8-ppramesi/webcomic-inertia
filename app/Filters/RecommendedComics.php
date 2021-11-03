<?php

namespace App\Filters;

use App\Models\Setting;

class RecommendedComics extends Filter{
    public $noParam = false;
    protected function applyFilter($builder){
        $u = auth()->user();
        if(empty($u)){
            $popularComics = Setting::getValue('dashboard.popular_comics');
            return $builder->whereIn('id', $popularComics)->orderByRaw("FIELD(id, " . implode(',', $popularComics) . ")");
        }
        $recommendations = $u->getRecommendedComics();
        return $builder->whereIn('id', $recommendations)->orderByRaw("FIELD(id, " . implode(',', $recommendations) . ")");
    }
}
