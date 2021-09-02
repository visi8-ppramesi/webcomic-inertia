<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Chapter;
use App\Models\Comic;
use App\Models\Page;
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

    public function viewComicShow(Comic $comic){
        $u = auth()->user();
        $comic->load('chapters');
        return Inertia::render('ComicShow', [
            'user' => $u,
            'comic' => $comic
        ]);
    }

    public function viewAuthor(Author $author){
        return Inertia::render('AuthorShow', [
            'author' => $author
        ]);
    }

    public function viewPageShow(Comic $comic, Chapter $chapter){
        $pages = Page::where('chapter_id', $chapter->id);
        $param = [
            'chapter' => $chapter,
            'pages' => $pages
        ];
        if(request()->has('ar')){
            $param['ar'] = request('ar');
        };
        return Inertia::render('PageShow', $param);
    }

    public function viewSceneShow(Page $page){
        $scene = $page->scene;
        $config = $page->config;
        return Inertia::render('SceneShow', [
            'scene' => $scene,
            'config' => $config
        ]);
    }

    public function viewSearchShow(Request $request){
        return Inertia::render('SearchShow', [

        ]);
    }

    public function viewAuthorShow(Author $author){
        return Inertia::render('AuthorShow', [

        ]);
    }

    public function viewPaymentShow(){
        return Inertia::render('PaymentShow', [

        ]);
    }

    public function viewMyComicShow(){
        return Inertia::render('MyComicShow', [

        ]);
    }

    public function viewAccountShow(){
        return Inertia::render('AccountShow', [

        ]);
    }

    public function viewAboutShow(){
        return Inertia::render('AboutShow', [

        ]);
    }

    public function viewPrivacyShow(){
        return Inertia::render('PrivacyShow', [

        ]);
    }
}