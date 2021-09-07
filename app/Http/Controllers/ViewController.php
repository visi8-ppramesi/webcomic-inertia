<?php

namespace App\Http\Controllers;

use App\Helpers\Query;
use App\Models\Author;
use App\Models\Chapter;
use App\Models\Comic;
use App\Models\Page;
use App\Models\Setting;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use tizis\laraComments\UseCases\CommentService;

class ViewController extends Controller
{
    public function viewMyTokensShow(){
        $tokenPrices = Setting::getValue('token.prices');
        return Inertia::render('MyTokensShow', [
            'prices' => $tokenPrices
        ]);
    }

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
        $comic->load(['chapters', 'authors']);
        return Inertia::render('ComicShow', [
            'user' => $u,
            'comic' => $comic,
            'comment_key' => $comic->getEncryptedKey(),
            'comments' => $comic->commentsWithChildrenAndCommenter()->parentless()->get()
        ]);
    }

    public function viewAuthor(Author $author){
        return Inertia::render('AuthorShow', [
            'author' => $author
        ]);
    }

    public function viewChapterShow(Comic $comic, Chapter $chapter){
        // $pages = Page::where('chapter_id', $chapter->id);
        $u = auth()->user();
        $comic->load('chapters');
        if($u->checkChapterPurchased($chapter->id)){
            $param = [
                'comic' => $comic,
                'chapter' => $chapter,
                'pages' => $chapter->pages,
                'comment_key' => $chapter->getEncryptedKey(),
                'comments' => $chapter->commentsWithChildrenAndCommenter()->parentless()->get()
            ];
            if(request()->has('ar')){
                $param['ar'] = request('ar');
            };
            return Inertia::render('PageShow', $param);
        }else{
            $comic = $chapter->comic;
            $comic->load(['chapters', 'authors']);
            return Inertia::render('ComicShow', [
                'user' => $u,
                'comic' => $comic,
                'comment_key' => $comic->getEncryptedKey(),
                'comments' => $comic->commentsWithChildrenAndCommenter()->parentless()->get(),
                'redirect_error' => 'chapter_unpurchased'
            ]);
        }
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
        if($request->has('search')){
            $searchTerm = $request->input('search');
            $comicResult = Query::buildWheres(Comic::class, $searchTerm, ['title', 'tags', 'genres'])->paginate(12);
            $authorResult = Query::buildWheres(Author::class, $searchTerm, ['name'])->paginate(12);
            return Inertia::render('SearchShow', [
                'results' => [
                    'comics' => $comicResult,
                    'authors' => $authorResult,
                ],
                'query' => $searchTerm
            ]);
        }else{
            return Inertia::render('SearchShow');
        }
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
        $u = auth()->user();
        $subs = array_values(json_decode($u->subscriptions));
        $favs = array_values(json_decode($u->favorites));
        $purchased = array_keys(json_decode($u->purchase_history, true));
        $comicSubs = Comic::whereIn('id', $subs)->get();
        $comicFaves = Comic::whereIn('id', $favs)->get();
        $comicPurchased = Comic::whereIn('id', $purchased)->get();
        return Inertia::render('MyComicShow', [
            'subscribed' => $comicSubs,
            'favorited' => $comicFaves,
            'purchased' => $comicPurchased
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

    public function viewPurchaseTokens(){
        $tokenPrices = Setting::getValue('token.prices');
        return Inertia::render('MyTokensShow', [
            'prices' => $tokenPrices
        ]);
    }
}
