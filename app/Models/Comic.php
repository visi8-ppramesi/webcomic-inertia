<?php

namespace App\Models;

use App\Filters\Get;
use App\Filters\SortByPopular;
use App\Filters\WhereGenre;
use App\Filters\WhereTag;
use App\Traits\Pipeable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use tizis\laraComments\Contracts\ICommentable;
use tizis\laraComments\Traits\Commentable;

class Comic extends Model implements ICommentable
{
    use HasFactory;
    use Commentable;
    use Pipeable;
    protected $guarded = [];

    public function pipeable(){
        return [
            Get::class,
            WhereTag::class,
            WhereGenre::class,
            SortByPopular::class,
        ];
    }

    public function authors(){
        return $this->belongsToMany(Author::class);
    }

    public function pages(){
        return $this->hasMany(Page::class);
    }

    public function chapters(){
        return $this->hasMany(Chapter::class);
    }

    public function purchasedBy(){
        $whereName = implode('->', ['purchase_history', $this->id, 'id']);
        return User::where($whereName, $this->id)->get();
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function favorited(){
        return $this->belongsToMany(User::class);
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('draft', function (Builder $builder) {
            if(!request()->has('is_draft')){
                return $builder->where('is_draft', 0);
            }
            return $builder;
        });
    }
}
