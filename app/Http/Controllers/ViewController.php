<?php

namespace App\Http\Controllers;

use App\Models\Comic;
use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ViewController extends Controller
{
    public function viewDashboard(){
        $settings = Setting::getValues([
            'dashboard.tags',
            'dashboard.genres',
            'dashboard.banners'
        ]);
        return Inertia::render('Dashboard', [
            'tags' => $settings['dashboard.tags'],
            'genres' => $settings['dashboard.genres'],
            'banners' => $settings['dashboard.banners'],
        ]);
    }
}
