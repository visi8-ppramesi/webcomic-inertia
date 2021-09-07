<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\TokenTransaction;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TokenTransactionController extends Controller
{
    public function purchaseTokens(Request $request){
        $validated = $request->validate([
            'token_amount' => ['required', 'integer'],
            'money_amount' => ['required', 'numeric']
        ]);

        $tokenPrices = Setting::getValue('token.prices');
        $priceError = true;
        foreach($tokenPrices as $key => $tokenPrice){
            if($validated['token_amount'] == $tokenPrice['amount'] && $validated['money_amount'] == $tokenPrice['price']){
                $priceError = false;
            }
        }
        if($priceError){
            throw ValidationException::withMessages(['field_name' => 'token price point does not exist.']);
        }

        $u = auth()->user();
        return response()->json($u->purchaseToken($validated['token_amount'], $validated['money_amount'], 'testing'), 200);
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
     * @param  \App\Models\TokenTransaction  $tokenTransaction
     * @return \Illuminate\Http\Response
     */
    public function show(TokenTransaction $tokenTransaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TokenTransaction  $tokenTransaction
     * @return \Illuminate\Http\Response
     */
    public function edit(TokenTransaction $tokenTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TokenTransaction  $tokenTransaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TokenTransaction $tokenTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TokenTransaction  $tokenTransaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(TokenTransaction $tokenTransaction)
    {
        //
    }
}
