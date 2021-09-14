<?php

use App\Helpers\Acl;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class SetupDatabase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $user = User::create([
            'name' => 'ppramesi',
            'full_name' => 'Priya Pramesi',
            'email' => 'ppramesi@visi8.com',
            'email_verified_at' => now(),
            'password' => Hash::make('123qweasd'),
            'remember_token' => Str::random(10),
            'purchase_history' => '{}',
            'read_history' => '[]',
            'total_tokens' => 0,
            'subscriptions' => '[]',
            'favorites' => json_encode(['comics' => [], 'chapters' => []]),
            'bookmark' => '{}'
        ]);

        $user->assignRole(Acl::ROLE_ADMIN);

        Setting::setValue('dashboard.tags', []);
        Setting::setValue('dashboard.genres', []);
        Setting::setValue('dashboard.banners', []);
        Setting::setValue('token.prices', [
            ['price' => 5000, 'amount' => 50],
            ['price' => 7000, 'amount' => 100],
            ['price' => 10000, 'amount' => 150],
            ['price' => 12000, 'amount' => 200],
            ['price' => 15000, 'amount' => 250, 'special_tag' => 'best_offer'],
            ['price' => 30000, 'amount' => 1000, 'special_tag' => 'best_offer'],
        ]);
        Setting::setValue('site.social_media_links', [
            'facebook' => 'https://facebook.com',
            'instagram' => 'https://instagram.com'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
