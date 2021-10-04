<?php

namespace App\Models;

use App\Filters\Get;
use App\Filters\TransactionsWhereChapter;
use App\Filters\TransactionsWhereType;
use App\Filters\WhereCreatedAfter;
use App\Filters\WhereCreatedBefore;
use App\Filters\WhereUserId;
use App\Filters\With;
use App\Traits\Pipeable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TokenTransaction extends Model
{
    use HasFactory;
    use Pipeable;
    protected $guarded = [];

    public function pipeable(){
        return [
            Get::class,
            WhereUserId::class,
            WhereCreatedAfter::class,
            WhereCreatedBefore::class,
            TransactionsWhereType::class,
            TransactionsWhereChapter::class,
            With::class,
        ];
    }

    public function authors(){
        return $this->belongsToMany(Author::class);
    }

    public function transactionable(){
        return $this->morphTo();
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
