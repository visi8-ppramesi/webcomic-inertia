<?php

namespace App\Console\Commands;

use App\Models\Comic;
use App\Models\User;
use Illuminate\Console\Command;

class FuckingAround extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fuck:around';
    private $comics;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        // $this->comics = Comic::get()->pluck('id')->toArray();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // $t = microtime(true);
        // $u = User::first();
        // $neighbors = json_decode($u->nearest_neighbors);
        // $neighUsers = User::whereIn('id', $neighbors)->orderByRaw("FIELD(id, " . implode(',', $neighbors) . ")")->get()->pluck('read_history');
        // $count = $neighUsers->count();
        // $sum = $count * ($count + 1) / 2;
        // $comics = $this->comics;
        // $weightedComics = $neighUsers->map(function($item, $index)use($sum, $comics){
        //     $weight = (50 - $index) / $sum;
        //     $hist = json_decode($item, true);
        //     $retVal = [];
        //     foreach($comics as $comic){
        //         if(empty($hist[$comic])){
        //             $retVal[$comic] = 0;
        //         }else{
        //             $retVal[$comic] = count($hist[$comic]) * $weight;
        //         }
        //     }
        //     return $retVal;
        // })->reduce(function($acc, $item){
        //     foreach($item as $comicId => $val){
        //         if(empty($acc[$comicId])){
        //             $acc[$comicId] = $val;
        //         }else{
        //             $acc[$comicId] += $val;
        //         }
        //     }
        //     return $acc;
        // }, []);
        // $uReadHist = collect(json_decode($u->read_history, true))->map(function($val){
        //     return count($val);
        // });
        // $diff = [];
        // foreach($comics as $comic){
        //     if(empty($uReadHist[$comic])){
        //         $diff[$comic] = round(-1 * $weightedComics[$comic], 2);
        //     }else{
        //         $diff[$comic] = round($uReadHist[$comic] - $weightedComics[$comic], 2);
        //     }
        // }
        // asort($diff);
        // $diff = array_keys($diff);
        // $this->info(json_encode($diff));
        // $end = microtime(true);
        // $this->info($end - $t);
        return 0;
    }
}
