<?php

namespace App\Http\Controllers;

use App\Models\Comic;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function getBookmarkedPage($comic){
        $u = auth()->user();
        return response()->json($u->getComicBookmarkedPage($comic));
    }

    // public function bookmarkPage($pageId){
    //     $u = auth()->user();
    //     return response()->json($u->bookmarkPage($pageId));
    // }

    public function getPageScene(Page $page){
        return response()->json(['config' => $page->config, 'scene' => $page->scene], 200);
    }

    public function getComicPages($comic, $chapter){
        $pages = Page::where('comic_id', $comic)->where('chapter_id', $chapter)->orderBy('section')->get();

        return response()->json($pages, 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Comic $comic)
    {
        return response()->json($comic->pages, 200);
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
            'chapter_id' => ['integer', 'required'],
            'section' => ['integer', 'required'],
            'image_url' => ['image', 'required'],
            'config' => ['string', 'nullable'],
            'scene' => ['string', 'nullable'],
            'comic_id' => ['required', 'exists:comics,id'],
            'comic_name' => ['required', 'string']
        ]);
        $comicName = Str::slug($validated['comic_name']);
        $validated['image_url'] = $this->storeFileFromRequest($request, 'image_url', 'public/media/comics/' . $comicName);
        unset($validated['comic_name']);

        return response()->json(Page::create($validated), 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function show(Page $page)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'chapter_id' => ['integer', 'required'],
            'section' => ['integer', 'required'],
            'image_url' => ['image', 'required'],
            'config' => ['string', 'nullable'],
            'scene' => ['string', 'nullable'],
            'comic_id' => ['required', 'exists:comics,id'],
            'comic_name' => ['required', 'string']
        ]);

        if(!empty($validated['image_url'])){
            $comicName = Str::slug($validated['comic_name']);
            $validated['image_url'] = $this->storeFileFromRequest($request, 'image_url', 'public/media/comics/' . $comicName);
        }
        unset($validated['comic_name']);

        return response()->json($page->update($validated), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $page)
    {
        return response()->json($page->delete());
    }
}
