<?php

namespace App\Http\Controllers;

use App\Models\Comic;
use App\Models\Page;
use Illuminate\Http\Request;

class ComicController extends Controller
{
    public function fetchComments(Comic $comic){
        $comments = $comic->commentsWithChildrenAndCommenter()->parentless()->get()->injectCanDelete()->injectUserLiked();

        return response()->json($comments, 200);
    }

    public function getComicChapters($comic){
        $chapters = Page::where('comic_id', $comic)->get()->pluck('chapter')->toArray();
        return response()->json(array_values(array_unique($chapters)), 200);
    }

    public function getComicChapterCount($comic){
        return response()->json(Page::where('comic_id', $comic)->max('chapter'), 200);
    }

    public function purchaseComics(Request $request){
        $validated = $request->validate([
            'comic_ids' => ['required', 'json'],
            'ar_bought' => ['required', 'json']
        ]);

        $ids = json_decode($validated['comic_ids'], true);
        $ars = json_decode($validated['ar_bought'], true);
        $arObjs = [];
        foreach($ars as $ar){
            $stuff = explode('-', $ar);
            if(empty($arObjs[$stuff[0]])){
                $arObjs[$stuff[0]] = [];
            }
            $arObjs[$stuff[0]][] = $stuff[1];
        }

        $u = auth()->user();

        foreach($ids as $id => $val){
            $arr = !empty($arObjs[$id]) ? $arObjs[$id] : [];
            $u->purchaseComic($id, $val, $arr);
        }

        return response()->json(['ids' => $ids], 200);
    }

    public function checkPurchased(Comic $comic){
        $u = auth()->user();
        if(empty($u)){
            return [];
        }
        return response()->json($u->checkComicPurchased($comic->id), 200);
    }

    public function checkBookmarked(Comic $comic){
        $u = auth()->user();
        return response()->json($u->getComicBookmarkedPage($comic->id), 200);
    }

    public function getFavorites(){
        $u = auth()->user();
        return response()->json(Comic::pipe($u->favorites()), 200);
    }

    public function toggleFavoriteComic(Comic $comic){
        $u = auth()->user();
        $fav = $u->toggleFavorite($comic->id, 'comics');
        // if($fav['type'] == 'increment'){
        //     $comic->increment('favorites_count');
        // }else if($fav['type'] == 'decrement'){
        //     $comic->decrement('favorites_count');
        // }
        return response()->json($fav['favorite_obj']);
    }

    public function toggleSubscribeComic($comic){
        $u = auth()->user();
        return response()->json($u->toggleSubscribeComic($comic));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Comic::pipe(), 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['string', 'required', 'max:140'],
            'description' => ['string', 'required'],
            'tags' => ['json', 'nullable'],
            'genres' => ['json', 'nullable'],
            // 'author_id' => ['required', 'exists:authors,id'],
            'price' => ['numeric', 'required'],
            'cover_url' => ['image', 'required']
        ]);
        $validated['cover_url'] = $this->storeFileFromRequest($request, 'cover_url', 'public/media/covers');

        return response()->json(Comic::create($validated), 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comic  $comic
     * @return \Illuminate\Http\Response
     */
    public function show(Comic $comic)
    {
        $comic->load('pages');
        $comic->load('authors');
        return response()->json($comic);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Comic  $comic
     * @return \Illuminate\Http\Response
     */
    public function edit(Comic $comic)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comic  $comic
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comic $comic)
    {
        $validated = $request->validate([
            'title' => ['string', 'required', 'max:140'],
            'description' => ['string', 'required'],
            'tags' => ['json', 'nullable'],
            'genres' => ['json', 'nullable'],
            // 'author_id' => ['required', 'exists:authors,id'],
            'price' => ['numeric', 'required'],
            'cover_url' => ['image', 'nullable']
        ]);

        if(!empty($validated['cover_url'])){
            $validated['cover_url'] = $this->storeFileFromRequest($request, 'cover_url', 'public/media/covers');
        }

        return response()->json($comic->update($validated), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comic  $comic
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comic $comic)
    {
        return response()->json($comic->delete());
    }
}
