<?php
namespace App\Helpers;

use tizis\laraComments\Contracts\Comment;

class FloatingServices{
    public static function ratingRecalculation(Comment $comment): int
    {
        $rating = 0;

        foreach ($comment->votes as $vote) {
            $rating += $vote->commenter_vote === 0 ? 0 : 1;
        }

        $comment->rating = $rating;
        $comment->save();

        return $rating;
    }
}
