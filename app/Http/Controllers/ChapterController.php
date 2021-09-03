<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use Illuminate\Http\Request;

class ChapterController extends Controller
{
    public function fetchComments(Chapter $chapter){
        return response()->json($chapter->commentsWithChildrenAndCommenter()->parentless()->get(), 200);
    }

    public function bookmarkChapter(Chapter $chapter){
        $u = auth()->user();
        return response()->json($u->bookmarkChapter($chapter->id));
    }

    public function purchaseChapter(Request $request){
        $validated = $request->validate([
            'ar' => ['boolean', 'required'],
            'chapter' => ['integer', 'required']
        ]);
        $cpt = Chapter::find($validated['chapter']);
        $cpt->comic->id;
        $u = auth()->user();
        if($validated['ar']){
            $ar = [$validated['chapter'] => true];
        }else{
            $ar = [];
        }
        $purchased = $u->purchaseChapters($cpt->comic->id, [$validated['chapter']], $ar);
        return response()->json(['status' => $purchased], 200);
    }

    public function checkAr(Chapter $chapter){
        $chapter->load('pages');
        $retVal = false;
        $pages = $chapter->pages->map(function($val, $idx)use(&$retVal){
            $retVal = $retVal || !empty($val->scene);
        });

        return response()->json($retVal, 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Chapter  $chapter
     * @return \Illuminate\Http\Response
     */
    public function show(Chapter $chapter)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Chapter  $chapter
     * @return \Illuminate\Http\Response
     */
    public function edit(Chapter $chapter)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Chapter  $chapter
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Chapter $chapter)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Chapter  $chapter
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chapter $chapter)
    {
        //
    }
}
