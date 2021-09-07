<?php

namespace App\Http\Controllers;

use App\Helpers\Query;
use App\Models\Author;
use App\Models\Comic;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function fetchSearch(Request $request){
        $searchTerm = $request->input('search');
        $comicResult = Query::buildWheres(Comic::class, $searchTerm, ['title', 'tags', 'genres'])->paginate();
        $authorResult = Query::buildWheres(Author::class, $searchTerm, ['name'])->paginate();

        return response()->json([
            'results' => [
                'comics' => $comicResult,
                'authors' => $authorResult
            ],
        ], 200);
    }
}
