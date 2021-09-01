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
            'favorites' => '[]',
            'bookmark' => '{}'
        ]);

        $user->assignRole(Acl::ROLE_ADMIN);

        Setting::create(['name' => 'dashboard.tags', 'values' => json_encode([])]);
        Setting::create(['name' => 'dashboard.genres', 'values' => json_encode([])]);
        Setting::create(['name' => 'dashboard.banners', 'values' => json_encode([])]);
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
