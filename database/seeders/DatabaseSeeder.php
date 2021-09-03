<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Chapter;
use App\Models\Comic;
use App\Models\Genre;
use App\Models\Page;
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
        User::factory(10)->create();
        Author::factory(10)->create();
        Genre::factory(10)->create();
        Tag::factory(10)->create();
        Comic::factory(10)->create();
        Chapter::factory(200)->create();
        Page::factory(400)->create();

        foreach(Comic::get() as $idx => $comic){
            $authors = Author::inRandomOrder()->limit(2)->get()->pluck('id')->toArray();
            $comic->authors()->sync($authors);
        }
    }
}
