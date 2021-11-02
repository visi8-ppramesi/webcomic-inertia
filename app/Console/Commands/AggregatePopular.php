<?php

namespace App\Console\Commands;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Console\Command;

class AggregatePopular extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aggregate:popular';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'We aggregate popular comics';

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
        $popularComics = User::get()->pluck('read_history')->map(function($hist){
            $comicsChapters = json_decode($hist, true);
            return collect($comicsChapters)->map(function($chapters){ return count($chapters); });
        })->reduce(function($acc, $itm){
            foreach($itm as $id => $cou){
                if(empty($acc[$id])){
                    $acc[$id] = $cou;
                }else{
                    $acc[$id] += $cou;
                }
            }
            return $acc;
        }, []);
        arsort($popularComics);
        $popularComics = array_slice(array_keys($popularComics), 0, 50);
        Setting::setValue('dashboard.popular_comics', $popularComics);
        $this->info('Popular comics updated!');
        return $popularComics;
    }
}
