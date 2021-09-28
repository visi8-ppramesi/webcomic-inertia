<?php

namespace Database\Factories;

use App\Models\Chapter;
use App\Models\Comic;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        if (random_int(0, 1)) {
            $type = Comic::class;
            $post = Comic::orderByRaw('RAND()')->first();
        }else{
            $type = Chapter::class;
            $post = Chapter::orderByRaw('RAND()')->first();
        }
        $user = User::orderByRaw('RAND()')->first();

        $commentableId = $post->id;
        $childId = null;
        if (random_int(0, 1)) {
            $parentComment = Comment::orderByRaw('RAND()')->first();
            if(is_null($parentComment->child_id)){
                $childId = $parentComment->id;
                $commentableId = $parentComment->commentable_id;
            }
        }
        return [
            'commenter_id' => $user->id,
            'commentable_type' => $type,
            'commentable_id' => $commentableId,
            'comment' => '<p>' . $this->faker->text() . '</p>',
            'child_id' => $childId,
        ];
    }
}
