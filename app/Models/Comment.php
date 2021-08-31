<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use tizis\laraComments\Entity\Comment as laraComment;

class Comment extends laraComment
{
    use HasFactory;
}
