<?php

namespace Database\Seeders;

use App\Models\Comic;
use App\Models\Setting;
use App\Models\TokenTransaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserPurchasesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::query()->update([
            'purchase_history' => "{}",
            'total_tokens' => 0
        ]);
        TokenTransaction::query()->delete();
        $tokenPrices = Setting::getValue('token.prices');
        $tokenPriceObj = end($tokenPrices);
        foreach(User::get() as $ukey => $user){
            $comicId = Comic::inRandomOrder()->first()->id;
            $user->toggleFavorite($comicId);
            $user->purchaseToken(
                $tokenPriceObj['amount'],
                $tokenPriceObj['price'],
                'testing',
                \Carbon\Carbon::now()->addDays(random_int(-90, 0))
            );

            foreach(Comic::inRandomOrder()->take(20)->get() as $key => $comic){
                foreach($comic->chapters as $ckey => $chapter){
                    // echo $comic->id . ' ' . $chapter->id . PHP_EOL;
                    $user->purchaseChapter($comic->id, $chapter->id, false, \Carbon\Carbon::now()->addDays(random_int(-30, -1)));
                }
            }

            echo $user->name . PHP_EOL;
        }
    }
}
