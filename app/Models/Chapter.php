<?php

namespace App\Models;

use App\Filters\Get;
use App\Traits\Pipeable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use tizis\laraComments\Contracts\ICommentable;
use tizis\laraComments\Traits\Commentable;

class Chapter extends Model implements ICommentable
{
    use HasFactory;
    use Commentable;
    use Pipeable;
    protected $guarded = [];

    public function pipeable(){
        return [
            Get::class
        ];
    }

    public function comic(){
        return $this->belongsTo(Comic::class);
    }

    public function transactions(){
        return $this->morphMany(TokenTransaction::class, 'transactionable');
    }

    public function pages(){
        return $this->hasMany(Page::class);
    }
}
