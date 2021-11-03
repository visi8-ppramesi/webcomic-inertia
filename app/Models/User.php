<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use tizis\laraComments\Traits\Commenter;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable //implements MustVerifyEmail
{
    use HasRoles;
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use Commenter;

    public $guard_name = 'api';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'total_tokens',
        'read_history',
        'subscriptions',
        'favorites',
        'bookmark',
        'purchase_history'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function asAuthor(){
        return $this->hasOne(Author::class);
    }

    public function readComic($comicId, $chapterId){
        $history = json_decode($this->read_history, true);

        if(empty($history[$comicId]) || !in_array($chapterId, $history[$comicId])){
            $history[$comicId][] = $chapterId;
            $this->read_history = json_encode($history);
            $this->save();
        }
    }

    public function tokenTransactions(){
        return $this->hasMany(TokenTransaction::class);
    }

    public function checkComicPurchased($comicId){
        $purchaseHistory = collect(json_decode($this->purchase_history));
        return empty($purchaseHistory[$comicId]) ? [] : $purchaseHistory[$comicId];
    }

    public function checkChapterPurchased($chapterId){
        $chapter = Chapter::find($chapterId);
        if($chapter->token_price == 0){
            return true;
        }
        $comic = $chapter->comic->id;
        $purchaseHistory = json_decode($this->purchase_history, true);
        return !empty($purchaseHistory[$comic]) && in_array($chapterId, $purchaseHistory[$comic]['chapters']);
    }

    public function getPurchasedComics(){
        $purchasedId = collect(json_decode($this->purchase_history))->keys()->toArray();
        return Comic::whereIn('id', $purchasedId)->get();
    }

    public function purchaseChapter($comicId, $chapter, $ar = false, $date = null){//to be called after sucessful payment
        //create token transaction first
        $itemsPrices = [];
        $tokenAmount = 0;
        $chapterObj = Chapter::where('id', $chapter)->first();

        $itemsPrices[$chapter] = $chapterObj->token_price;
        $tokenAmount += $chapterObj->token_price;
        // $chapter->map(function($item)use(&$itemsPrices, &$tokenAmount){
        //     $itemsPrices[$item->id] = $item->token_price;
        //     $tokenAmount += $item->token_price;
        // });
        $authors = $chapterObj->comic->authors->pluck('id');
        $currentToken = $this->total_tokens - $tokenAmount;
        if($this->checkChapterPurchased($chapter)){
            return -1;
        }
        if($currentToken <= 0){
            return 0;
        }
        $date = !empty($date) ? \Carbon\Carbon::parse($date) : \Carbon\Carbon::now();

        $comicObj = Comic::findOrFail($comicId);
        $splitObj = $comicObj->author_split;
        if(gettype($splitObj) == 'string'){
            $splitObj = json_decode($splitObj, true);
        }
        if(empty($splitObj)){
            try{
                $split = 1 / $authors->count();//we should probably take care of this in the future just in case there's different split
                $splitObj = $authors->mapWithKeys(function($v)use($split){return [$v => $split];})->all();
            }catch(\Exception $e){
                $splitObj = [];
            }
        }
        $descriptor = [
            'date' => $date,
            'type' => 'purchase_comic',
            'items' => $chapter,
            'item_prices' => $itemsPrices,
            'author_split' => $splitObj
        ];

        $transaction = TokenTransaction::create([
            'transactionable_type' => Chapter::class,
            'transactionable_id' => $chapter,
            'user_id' => $this->id,
            'token_amount' => $tokenAmount,
            'descriptor' => json_encode($descriptor),
            'created_at' => $date,
        ]);

        $transaction->authors()->sync($authors);

        //then add comic to purchased list
        $currentPurchaseObj = json_decode($this->purchase_history, true);
        // $jsonString = 'purchase_history->' . $comicId;
        if(array_key_exists($comicId, $currentPurchaseObj)){
            $currentChapter = $currentPurchaseObj[$comicId]['chapters'];
            $arArr = $currentPurchaseObj[$comicId]['ar'];
            $currentTransaction = $currentPurchaseObj[$comicId]['transactions'];
            $currentChapter = array_merge($currentChapter, [$chapter]);
            $transactions = array_merge($currentTransaction, [$transaction->id]);
            if($ar){
                $arArr[] = $chapter;
            }
            $purchaseObject = [
                // 'price' => $comicObj->price,
                'ar' => $arArr,
                'id' => $comicId,
                'date' => $date,
                'chapters' => $currentChapter,
                'transactions' => $transactions
            ];
        }else{
            $arArr = $ar ? [$chapter] : [];
            $purchaseObject = [
                // 'price' => $comicObj->price,
                'ar' => $arArr,
                'id' => $comicId,
                'date' => $date,
                'chapters' => [$chapter],
                'transactions' => [$transaction->id]
            ];
        }
        $uid = $this->id;
        $currentPurchaseObj[$comicId] = $purchaseObject;
        $this->purchase_history = json_encode($currentPurchaseObj);
        $this->total_tokens = $currentToken;
        $saved = $this->save();
        return $saved;
    }

    public function subscribeComic($comicId){
        $currentFave = array_values(json_decode($this->subscriptions, true));
        if(in_array($comicId, $currentFave)){
            $currentFave[] = $comicId;
        }else{
            $currentFave = array_values(array_diff($currentFave, [$comicId]));
        }
        $uid = $this->id;
        return self::where('id', $uid)->update(['subscriptions' => $currentFave]);
    }

    public function bookmarkChapter($chapterId){
        $comicId = Chapter::findOrFail($chapterId)->comic_id;
        $currentBookmark = json_decode($this->bookmark, true);
        if(empty($currentBookmark[$comicId]) || $currentBookmark[$comicId] > $chapterId){
            $currentBookmark[$comicId] = $chapterId;
            $uid = $this->id;
            return self::where('id', $uid)->update(['bookmark' => $currentBookmark]);
        }else{
            return 0;
        }
    }

    public function toggleFavorite($objectId, $type = 'comics'){
        /*
            structure should be:
            {
                comic: [1,2,3...],
                chapter: [2,3,4...]
            }
            so initialized to {comic:[], chapter: []}
        */
        $currentFave = json_decode($this->favorites, true);
        $changeType = '';
        if(!in_array($objectId, $currentFave[$type])){
            $currentFave[$type][] = $objectId;
            $changeType = 'increment';
        }else{
            $currentFave[$type] = array_values(array_diff($currentFave[$type], [$objectId]));
            $changeType = 'decrement';
        }
        if($type == 'comics'){
            Comic::find($objectId)->{$changeType}('favorites_count');
        }else if($type == 'chapters'){
            Chapter::find($objectId)->{$changeType}('favorites_count');
        }
        $uid = $this->id;
        self::where('id', $uid)->update(['favorites' => json_encode($currentFave)]);
        return ['favorite_obj' => $currentFave, 'type' => $changeType];
    }

    public function toggleSubscribeComic($objectId){
        $currentSubs = array_values(json_decode($this->subscriptions));
        if(!in_array($objectId, $currentSubs)){
            $currentSubs[] = $objectId;
        }else{
            $currentSubs = array_diff($currentSubs, [$objectId]);
        }
        $uid = $this->id;
        self::where('id', $uid)->update(['subscriptions' => $currentSubs]);
        return $currentSubs;
    }

    public function checkTokenAmount(){
        $transactions = TokenTransaction::where('user_id', $this->id)->get();
        $total = 0;
        foreach($transactions as $key => $transaction){
            $number = json_decode($transaction->descriptor, true)['type'] == 'purchase_comic' ? $transaction->token_amount * -1 : $transaction->token_amount;
            $total += $number;
        }
        return [$total, $this->total_tokens];
    }

    public function getComicBookmarkedPage($comicId){
        $bookmark = json_decode($this->bookmark, true);
        return !empty($bookmark[$comicId]) ? $bookmark[$comicId] : null;
    }

    public function purchaseToken($tokenAmount, $moneyValue, $paymentType, $date = null){
        /*
            descriptor object structure
            {
                date: date,
                type: (purchase_token, purchase_comic),
                payment_type: payment type
                money_value: money value
            }
        */
        $date = !empty($date) ? \Carbon\Carbon::parse($date) : \Carbon\Carbon::now();
        $descriptor = [
            'date' => $date,
            'type' => 'purchase_token',
            'payment_type' => $paymentType,
            'money_value' => $moneyValue
        ];
        TokenTransaction::create([
            'user_id' => $this->id,
            'token_amount' => $tokenAmount,
            'descriptor' => json_encode($descriptor),
            'created_at' => $date
        ]);
        $this->total_tokens += $tokenAmount;
        return $this->save();
    }

    public function canDelete($item){
        if(get_class($item) == Comment::class){
            return $item->commenter_id == $this->id || $this->can('manage comment');
        }
        return false;
    }

    public function getRecommendedComics(){
        $neighbors = json_decode($this->nearest_neighbors);
        $neighUsers = self::whereIn('id', $neighbors)->orderByRaw("FIELD(id, " . implode(',', $neighbors) . ")")->get()->pluck('read_history');
        $count = $neighUsers->count();
        $sum = $count * ($count + 1) / 2;
        $comics = Comic::get()->pluck('id')->toArray();
        $weightedComics = $neighUsers->map(function($item, $index)use($sum, $comics){
            $weight = (50 - $index) / $sum;
            $hist = json_decode($item, true);
            $retVal = [];
            foreach($comics as $comic){
                if(empty($hist[$comic])){
                    $retVal[$comic] = 0;
                }else{
                    $retVal[$comic] = count($hist[$comic]) * $weight;
                }
            }
            return $retVal;
        })->reduce(function($acc, $item){
            foreach($item as $comicId => $val){
                if(empty($acc[$comicId])){
                    $acc[$comicId] = $val;
                }else{
                    $acc[$comicId] += $val;
                }
            }
            return $acc;
        }, []);
        $uReadHist = collect(json_decode($this->read_history, true))->map(function($val){
            return count($val);
        });
        $diff = [];
        foreach($comics as $comic){
            if(empty($uReadHist[$comic])){
                $diff[$comic] = round(-1 * $weightedComics[$comic], 2);
            }else{
                $diff[$comic] = round($uReadHist[$comic] - $weightedComics[$comic], 2);
            }
        }
        asort($diff);
        $diff = array_keys($diff);
        return $diff;
    }
}
