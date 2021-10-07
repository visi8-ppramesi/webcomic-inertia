<?php

namespace Database\Factories;

use App\Helpers\FloatingServices;
use App\Models\Author;
use App\Models\Comic;
use App\Models\Genre;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

class ComicFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comic::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $author = Author::inRandomOrder()->first();
        $genres = Genre::all()->pluck('name')->toArray();
        $tags = Tag::all()->pluck('name')->toArray();
        $randomKeys = array_rand($tags, 3);
        $tagsStr = json_encode([$tags[$randomKeys[0]], $tags[$randomKeys[1]], $tags[$randomKeys[2]]]);
        $randomKeysGenre = array_rand($genres, 3);
        $genresStr = json_encode([$genres[$randomKeysGenre[0]], $genres[$randomKeysGenre[1]], $genres[$randomKeysGenre[2]]]);
        return [
            'title' => $this->faker->words(4, true),
            'description' => $this->faker->paragraph(),
            'tags' => $tagsStr,
            'genres' => $genresStr,
            // 'author_id' => $author->id,
            // 'price' => $this->faker->randomFloat(2, 0, 10),
            'cover_url' => '/storage/media/covers/' . rand(1,4) . '.jpg',
            'views' => FloatingServices::normalRandom(0, 1000000, 2),
            'is_draft' => rand(0, 1),
            'author_split' => '{}',
        ];
    }
}
