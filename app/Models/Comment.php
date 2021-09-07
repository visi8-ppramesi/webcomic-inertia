<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use tizis\laraComments\Entity\Comment as laraComment;
// use tizis\laraComments\Contracts\Comment;

class Comment extends laraComment
{
    use HasFactory;

    public function canUserDelete(User $user){
        return true;
    }
}
