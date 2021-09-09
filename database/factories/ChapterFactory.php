<?php

namespace Database\Factories;

use App\Models\Chapter;
use App\Models\Comic;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChapterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Chapter::class;

    // $table->string('image_url');
    // $table->foreignId('comic_id');
    // $table->integer('chapter');
    // $table->integer('token_price');
    // $table->date('release_date')->nullable();

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $comic = Comic::inRandomOrder()->first();
        return [
            'image_url' => '/storage/media/covers/' . rand(1,4) . '.jpg',
            'comic_id' => $comic->id,
            'chapter' => rand(1,10),
            'token_price' => rand(0,100),
            'release_date' => now(),
            'views' => rand(0, 10000000)
        ];
    }
}
