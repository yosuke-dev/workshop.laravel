<?php

use App\Events\Posted;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/event/sandbox/post', function(Request $request) {
    $post = new Post([
        'user_id' => $request->get('userId'),
        'title' => $request->get('title'),
        'content_text' => $request->get('text'),
    ]);
    $post->save();

    event(new Posted($post));
    return '投稿が完了しました';
});
