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

class User extends Authenticatable implements MustVerifyEmail
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

    public function tokenTransactions(){
        return $this->hasMany(TokenTransaction::class);
    }

    public function checkComicPurchased($comicId){
        $purchaseHistory = collect(json_decode($this->purchase_history));
        return empty($purchaseHistory[$comicId]) ? [] : $purchaseHistory[$comicId];
    }

    public function getPurchasedComics(){
        $purchasedId = collect(json_decode($this->purchase_history))->keys()->toArray();
        return Comic::whereIn('id', $purchasedId)->get();
    }

    public function purchaseChapter($comicId, $chapters, $ar = []){//to be called after sucessful payment
        //create token transaction first
        $itemsPrices = [];
        $tokenAmount = 0;
        Chapter::whereIn('id', $chapters)->get()->map(function($item)use(&$itemsPrices, &$tokenAmount){
            $itemsPrices[$item->id] = $item->token_price;
            $tokenAmount += $item->token_price;
        });
        $currentToken = $this->total_tokens - $tokenAmount;
        if($currentToken <= 0){
            return;
        }

        $descriptor = [
            'date' => \Carbon\Carbon::now(),
            'type' => 'purchase_comic',
            'items' => $chapters,
            'item_prices' => $itemsPrices
        ];

        TokenTransaction::create([
            'user_id' => $this->id,
            'token_amount' => $tokenAmount,
            'descriptor' => $descriptor
        ]);

        //then add comic to purchased list
        $comicObj = Comic::findOrFail($comicId);
        $currentPurchaseObj = json_decode($this->purchase_history, true);
        $jsonString = 'purchase_history->' . $comicId;
        if(array_key_exists($comicId, $currentPurchaseObj)){
            $currentChapter = $currentPurchaseObj[$comicId]['chapters'];
            $currentChapter = array_merge($currentChapter, $chapters);
            $purchaseObject = [
                'price' => $comicObj->price,
                'ar' => $ar,
                'id' => $comicId,
                'date' => now(),
                'chapters' => $currentChapter
            ];
        }else{
            $purchaseObject = [
                'price' => $comicObj->price,
                'ar' => $ar,
                'id' => $comicId,
                'date' => now(),
                'chapters' => $chapters
            ];
        }
        $uid = $this->id;
        return self::where('id', $uid)->update([
            $jsonString => $purchaseObject,
            'total_tokens' => $currentToken
        ]);
    }

    public function bookmarkPage($chapterId){
        $comicId = Chapter::findOrFail($chapterId)->comic_id;
        $currentBookmark = json_decode($this->bookmark, true);
        $currentBookmark[$comicId] = $chapterId;
        $uid = $this->id;
        return self::where('id', $uid)->update(['bookmark' => $currentBookmark]);
    }

    public function toggleFavoriteComic($comicId){
        $currentFave = json_decode($this->favorites);
        if(in_array($comicId, $currentFave)){
            $currentFave[] = $comicId;
        }else{
            $currentFave = array_values(array_diff($currentFave, [$comicId]));
        }
        $uid = $this->id;
        return self::where('id', $uid)->update(['favorites' => $currentFave]);
    }

    public function getComicBookmarkedPage($comicId){
        return json_decode($this->bookmark, true)[$comicId];
    }

    public function purchaseToken($tokenAmount, $moneyValue, $paymentType){
        /*
            descriptor object structure
            {
                date: date,
                type: (purchase_token, purchase_comic),
                payment_type: payment type
                money_value: money value
            }
        */
        $descriptor = [
            'date' => \Carbon\Carbon::now(),
            'type' => 'purchase_token',
            'payment_type' => $paymentType,
            'money_value' => $moneyValue
        ];
        TokenTransaction::create([
            'user_id' => $this->id,
            'token_amount' => $tokenAmount,
            'descriptor' => $descriptor
        ]);
        $this->total_tokens += $tokenAmount;
        $this->save();
    }
}
