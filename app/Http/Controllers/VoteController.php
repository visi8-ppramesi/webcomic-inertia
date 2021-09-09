<?php

namespace App\Http\Controllers;

use App\Helpers\FloatingServices;
use tizis\laraComments\Http\Controllers\VoteController as TizisVoteController;
use tizis\laraComments\Entity\Comment;
use tizis\laraComments\Http\Requests\VoteRequest;
use tizis\laraComments\UseCases\VoteService;


class VoteController extends TizisVoteController
{
    public function __construct(VoteService $voteService)
    {
        parent::__construct($voteService);
    }

    public function vote(VoteRequest $request, Comment $comment)
    {
        $this->authorize($this->policyPrefix . '.vote', $comment);

        $this->voteService->make(auth()->user(), $comment, $request->vote);
        $rating = FloatingServices::ratingRecalculation($comment);
        $votesCount = $comment->votesCount();

        return $request->ajax() ? ['success' => true, 'count' => $votesCount, 'rating' => $rating] : redirect()->to(url()->previous() . '#comment-' . $comment->id);
    }
}
