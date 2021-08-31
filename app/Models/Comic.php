<?php

namespace App\Models;

use App\Filters\Get;
use App\Traits\Pipeable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comic extends Model
{
    use HasFactory;
    use Pipeable;
    protected $guarded = [];

    public function pipeable(){
        return [
            Get::class,
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
}
