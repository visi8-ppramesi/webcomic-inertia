<?php

namespace App\Http\Controllers;

use App\Filters\TransactionsWhereType;
use App\Filters\WhereGenre;
use App\Filters\WhereTag;
use App\Filters\WhereUserId;
use App\Filters\With;
use App\Helpers\Query;
use App\Models\Author;
use App\Models\Chapter;
use App\Models\Comic;
use App\Models\Page;
use App\Models\Setting;
use App\Models\Comment;
use App\Models\Genre;
use App\Models\TokenTransaction;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use tizis\laraComments\UseCases\CommentService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;

class ViewController extends Controller
{
    public function viewMyTransactionsShow(){
        $u = auth()->user();
        $purchaseTransactions = TokenTransaction::pipe(null, [
            WhereUserId::class => $u->id,
            TransactionsWhereType::class => 'purchase_token'
        ]);
        $chapterTransactions = TokenTransaction::pipe(null, [
            WhereUserId::class => $u->id,
            TransactionsWhereType::class => 'purchase_comic',
            With::class => 'transactionable.comic'
        ]);

        return Inertia::render('MyTransactionsShow', [
            'purchase_tokens' => $purchaseTransactions,
            'purchase_chapters' => $chapterTransactions
        ]);
    }

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

    public function viewComicsShow(Request $request, $type = null, $value = null){
        // $comicQuery = Comic::query();
        // $data = [];
        // if(!empty($type) && in_array($type, ['tag', 'genre', 'newest'])){
        //     switch($type){
        //         case 'tag':
        //         case 'genre':
        //             $comicQuery->whereJsonContains($type, $value);
        //             break;
        //         case 'newest':
        //             break;
        //     }
        // }
        $data = [
            'type' => $type,
            'value' => $value,
            'genres' => Genre::all()
        ];
        if(!empty($type)){
            $data['comics'] = Comic::pipe(null, [
                'paginate' => 12,
                'page' => 1,
                WhereGenre::class => $value
            ]);
        }
        return Inertia::render('ComicsShow', $data);
    }

    public function viewComicShow(Comic $comic){
        $u = auth()->user();
        $comic->increment('views');
        $comic->load(['chapters', 'authors']);

        $comments = $comic->commentsWithChildrenAndCommenter()->parentless()->get()->injectCanDelete()->injectUserLiked();;

        return Inertia::render('ComicShow', [
            'user' => $u,
            'comic' => $comic,
            'comment_key' => $comic->getEncryptedKey(),
            'comments' => $comments,
            // 'comic_purchased' => $u->checkComicPurchased($comic->id)
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
            $chapter->increment('views');
            $comments = $chapter->commentsWithChildrenAndCommenter()->parentless()->get()->injectCanDelete()->injectUserLiked();

            $pages = $chapter->pages;
            foreach($pages as $idx => $page){
                $pages[$idx]['image_url'] = $page->getSignedImageUrl();
            }

            $param = [
                'comic' => $comic,
                'chapter' => $chapter,
                // 'pages' => $chapter->pages,
                'pages' => $pages,
                'comment_key' => $chapter->getEncryptedKey(),
                'comments' => $comments
            ];
            if(request()->has('ar')){
                $param['ar'] = request('ar');
            };
            return Inertia::render('PageShow', $param);
        }else{
            return Redirect::route('web.comic', ['comic' => $chapter->comic->id, 'error' => 'unpurchased']);
            // $comic = $chapter->comic;
            // $comic->load(['chapters', 'authors']);

            // $comments = $comic->commentsWithChildrenAndCommenter()->parentless()->get()->injectCanDelete()->injectUserLiked();;

            // return Inertia::render('ComicShow', [
            //     'user' => $u,
            //     'comic' => $comic,
            //     'comment_key' => $comic->getEncryptedKey(),
            //     'comments' => $comments,
            //     'redirect_error' => 'chapter_unpurchased'
            // ]);
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
            'author' => $author
        ]);
    }

    public function viewPaymentShow(){
        return Inertia::render('PaymentShow', [

        ]);
    }

    public function viewMyComicShow(){
        $u = auth()->user();
        $subs = array_values(json_decode($u->subscriptions, true));
        $favs = json_decode($u->favorites, true);
        $purchased = array_keys(json_decode($u->purchase_history, true));
        $comicSubs = Comic::whereIn('id', $subs)->get();
        $comicFaves = Comic::whereIn('id', array_values($favs['comics']))->get();
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
