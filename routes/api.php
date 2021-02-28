<?php

use App\Models\Today;
use App\Models\Upcoming;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\UpcomingTaskResource;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// ** Upcoming Task ** //
// Get all the upcoming tasks
Route::get("/upcoming", function() {
    $upcoming = Upcoming::all();
    return UpcomingTaskResource::collection($upcoming);
});

// Add a new Task
Route::post('/upcoming', function(Request $request) {
    return Upcoming::create([
        'title' => $request->title,
        'taskId' => $request->taskId,
        'waiting' => $request->waiting
    ]);
});

// Delete Upcoming Tasks
Route::delete('/upcoming/{taskId}', function($taskId) {
    DB::table('upcomings')->where('taskId', $taskId)->delete();

    return 204;
});

//** Today Task **//

Route::post('/dailytask', function(Request $request) {
    return Today::create([
        'id' => $request->id,
        'title' => $request->title,
        'taskId' => $request->taskId,
    ]);
});

// Delete Today Tasks
Route::delete('/dailytask/{taskId}', function($taskId) {
    DB::table('todays')->where('taskId', $taskId)->delete();

    return 204;
});