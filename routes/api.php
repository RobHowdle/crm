<?php

use App\Models\Upcoming;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// ** Upcoming Task ** //
Route::get("/upcoming", function() {
    $upcoming = Upcoming::all();

    return $upcoming;
});