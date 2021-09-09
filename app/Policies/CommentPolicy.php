<?php

namespace App\Policies;

use tizis\laraComments\Contracts\Comment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use tizis\laraComments\Policies\CommentPolicy as CommentPolicyPackage;

class CommentPolicy extends CommentPolicyPackage
{
   // overwrite delete rule
   public function delete($user, Comment $comment): bool
   {
       // ever true
       return $user->id === $comment->commenter_id || $user->can('manage comment');
   }

   /**
    * @param $user
    * @param Comment $comment
    * @return bool
    */
   public function vote($user, Comment $comment): bool
   {
       return true;
   }
}
