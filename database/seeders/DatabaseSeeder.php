<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Chapter;
use App\Models\Comic;
use App\Models\Genre;
use App\Models\Page;
use App\Models\Setting;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        echo 'user factory starting...' . PHP_EOL;
        User::factory(1000)->create();
        echo 'author factory starting...' . PHP_EOL;
        Author::factory(10)->create();
        echo 'genre factory starting...' . PHP_EOL;
        Genre::factory(10)->create();
        echo 'tag factory starting...' . PHP_EOL;
        Tag::factory(10)->create();
        echo 'comic factory starting...' . PHP_EOL;
        Comic::factory(100)->create();
        echo 'chapter factory starting...' . PHP_EOL;
        Chapter::factory(2000)->create();
        echo 'page factory starting...' . PHP_EOL;
        Page::factory(4000)->create();
        echo 'factory finished' . PHP_EOL;

        foreach(Comic::get() as $idx => $comic){
            $authors = Author::inRandomOrder()->limit(2)->get()->pluck('id');

            $mySplit = $authors->reduce(function($acc, $elem){
                $acc[$elem] = 1/2;
                return $acc;
            }, []);
            $comic->authors()->sync($authors->toArray());
            $comic->author_split = json_encode($mySplit);
            $comic->save();
            echo $comic->title . PHP_EOL;
        }
    }
}
