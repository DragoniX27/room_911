<?php

namespace App\Http\Controllers;

use App\Models\Login_attempt;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LoginAttemptController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Function to search for a attemps
     */
    public function search(Request $request){
        $attempts = Login_attempt::where('user_id', $request->input('user_id'))->whereBetween('created_at', [
            $request->input('start'),
            Carbon::parse($request->input('end'))->endOfDay()
        ])->latest()->limit(100)->get();

        return response()->json([ 'attempts' => $attempts]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(login_attempt $login_attempt)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(login_attempt $login_attempt)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, login_attempt $login_attempt)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(login_attempt $login_attempt)
    {
        //
    }
}
