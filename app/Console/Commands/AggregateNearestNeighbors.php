<?php

namespace App\Console\Commands;

use App\Models\Comic;
use App\Models\User;
use Illuminate\Console\Command;

class AggregateNearestNeighbors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aggregate:nearestneighbors';
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
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $u = User::get();
        $this->comics = Comic::get()->pluck('id')->toArray();
        $closestNeighbors = [];
        for($i = 0; $i < count($u); $i++){
            $myDistances = [];
            for($j = 0; $j < count($u); $j++){
                if($i != $j){
                    $myDistances[$u[$j]->id] = $this->measureDistance($u[$i], $u[$j]);
                }
            }
            arsort($myDistances);
            $closestNeighbors[$u[$i]->id] = array_slice(array_keys($myDistances), 0, 50);
            $u[$i]->nearest_neighbors = json_encode(array_slice(array_keys($myDistances), 0, 50));
            $u[$i]->save();
            $this->info(json_encode($closestNeighbors[$u[$i]->id]));
        }
        return 0;
    }

    private function measureDistance($userOne, $userTwo){
        $one = collect(json_decode($userOne->read_history, true))->map(function($chapters){ return count($chapters); })->toArray();
        $two = collect(json_decode($userTwo->read_history, true))->map(function($chapters){ return count($chapters); })->toArray();
        $distSum = 0;
        foreach($this->comics as $comic){
            if(empty($one[$comic])){
                $one[$comic] = 0;
            }
            if(empty($two[$comic])){
                $two[$comic] = 0;
            }
            $distSum += ($one[$comic] - $two[$comic]) ** 2;
        }
        $sqrtDistSum = sqrt($distSum);
        return $sqrtDistSum;
    }
}
