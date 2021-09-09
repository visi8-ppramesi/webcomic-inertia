<?php

namespace App\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use tizis\laraComments\Entity\CommentVotes;

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
            if(empty($u)){
                return $this;
            }
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

        Collection::macro('injectUserLiked', function(){
            $u = auth()->user();
            if(empty($u)){
                return $this;
            }
            $voteComments = CommentVotes::where('commenter_id', $u->id)->where('commenter_vote', 1)->get()->pluck('comment_id');
            $items = $this;
            $ids = $this->pluck('id')->toArray();
            foreach($items as $idx => $item){
                if($voteComments->contains($item->id)){
                    $items[$idx]['liked'] = true;
                }else{
                    $items[$idx]['liked'] = false;
                }
                if(!empty($items[$idx]['allChildrenWithCommenter'])){
                    foreach($items[$idx]['allChildrenWithCommenter'] as $childIdx => $childItem){
                        if($voteComments->contains($childItem->id)){
                            $items[$idx]['allChildrenWithCommenter'][$childIdx]['liked'] = true;
                        }else{
                            $items[$idx]['allChildrenWithCommenter'][$childIdx]['liked'] = false;
                        }
                    }
                }
            }
            return $items;
        });
    }
}
