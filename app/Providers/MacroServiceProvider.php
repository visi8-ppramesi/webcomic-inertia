<?php

namespace App\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class MacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Collection::macro('injectCanDelete', function(){
            $u = auth()->user();
            $items = $this;
            foreach($items as $idx => $item){
                $items[$idx]['can_delete'] = $u->canDelete($item);
                if(!empty($items[$idx]['allChildrenWithCommenter'])){
                    foreach($items[$idx]['allChildrenWithCommenter'] as $childIdx => $childItem){
                        $items[$idx]['allChildrenWithCommenter'][$childIdx]['can_delete'] = $u->canDelete($childItem);
                    }
                }
            }
            return $items;
        });
    }
}
