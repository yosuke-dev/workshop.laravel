<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Post;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/posts', function () {
    ddd(Post::all()->toArray());
});

Route::get('/post/entry', function () {
    $newPost = new Post();
    $randNum = rand(0,9999);
    $newPost->title = '投稿のテスト' . $randNum;
    $newPost->content_text = '投稿内容はこちらです' . $randNum;
    $newPost->save();
    return ddd('entry success' . $randNum);
});

Route::get('/post/{id}', function ($id) {
    $post = Post::find($id);
    ddd($post);
});

Route::get('/post/update/{id}', function ($id) {
    $post = Post::find($id);
    $randNum = rand(0,9999);
    $post->title ='投稿の更新テスト' . $randNum;
    $post->save();
    ddd('update success' . $randNum);
});

Route::get('/post/delete/{id}', function ($id) {
    Post::destroy($id);
    ddd('destroy success' . $id);
});

Route::get('/posts/sandbox/table', function () {
    $post = new Post();
    return $post->getTable();
});

Route::get('/posts/sandbox/key', function () {
    $post = new Post();
    return $post->getKeyName();
});

Route::get('/posts/sandbox/getVarious/{id}', function ($id) {
    Post::find($id);
    Post::where('id', $id)->get();
    Post::whereId($id)->get();
    Post::where('id', '=', $id)->get();

    ddd('queryを見てね');
});

Route::get('/posts/sandbox/count/{id}', function ($id) {
    Post::whereId($id)->get()->count();
    Post::whereId($id)->count();

    ddd('queryを見てね');
});

Route::get('/posts/sandbox/sum/{id}', function ($id) {
    Post::whereId($id)->get()->sum('id');
    Post::whereId($id)->sum('id');

    ddd('queryを見てね');
});

Route::get('/posts/sandbox/select/{id}', function ($id) {
    Post::whereId($id)->first('title');
    Post::whereId($id)->select('title')->first();
    Post::whereId($id)->select('title')->addSelect('content_text')->first();
    Post::whereId($id)->first(['title', 'content_text']);
    Post::whereId($id)->select(['title', 'content_text'])->first();

    ddd('queryを見てね');
});

Route::get('/posts/sandbox/pluck', function () {
    // 項目を絞って取得
    $posts = Post::all(['id', 'title']);
    // 取得後に項目を絞る。Keyなしのcollectionに。
    $posts2 = Post::all()->pluck('title');
    // 取得後に項目を絞る。KeyをIdにしたcollectionに。
    $posts3 = Post::all()->pluck('title', 'id');
    // 取得後に項目を絞る。KeyをIdにしたcollectionの中身をModelに。
    $posts4 = Post::all()->pluck(null, 'id');

    ddd([$posts, $posts2, $posts3, $posts4]);
});

Route::get('/posts/sandbox/and', function () {
    $posts = Post::where('content_text', 'like', '%投稿%')
        ->where('title', 'like', '%更新%')
        ->get();

    ddd($posts->toArray());
});

Route::get('/posts/sandbox/or', function () {
    $posts = Post::where('content_text', 'like', '%投稿%')
        ->orWhere('title', 'like', '%更新%')
        ->get();

    ddd($posts->toArray());
});

Route::get('/posts/sandbox/andOr', function () {
    $a = '%投稿%';
    $b = '%更新%';
    $c = '%テスト%';
    // A and B or C
    Post::where('content_text', 'like', $a)
        ->where('title', 'like', $b)
        ->orWhere('title', 'like', $c)
        ->get();

    // A and (B or C)
    Post::where('content_text', 'like', $a)
        ->where(function ($query) use ($b, $c) {
            $query->where('title', 'like', $b)
            ->orWhere('title', 'like', $c);
        })->get();

    ddd('queryを見てね');
});

Route::get('/posts/sandbox/either', function () {
    $a = [1, 2, 3, 4, 5];
    $b = [5, 6, 7];
    // A列のAリストの条件に当てはまる
    Post::whereIn('id', $a)
        ->get();

    // A列のAリストもしくはB列のBリストの条件に当てはまる
    Post::whereIn('id', $a)
        ->orWhere(function ($query) use ($b) {
            $query->whereIn('id', $b);
        })->get();

    ddd('queryを見てね');
});

Route::get('/posts/sandbox/null', function () {
    Post::whereNull('title')->get();
    Post::whereNotNull('title')->get();

    ddd('queryを見てね');
});

Route::get('/posts/sandbox/whereRaw', function () {
    Post::whereRaw('length(title) > 10')->get();

    ddd('queryを見てね');
});

Route::get('/posts/sandbox/whereColumn', function () {
    Post::whereColumn('created_at', '<', 'updated_at')->get();

    ddd('queryを見てね');
});

Route::get('/posts/sandbox/whereDateEx', function () {
    $posts = Post::whereDate('created_at', '2022-07-12')->get()->toArray();
    $posts2 = Post::whereMonth('created_at', '07')->get()->toArray();
    $posts3 = Post::whereDay('created_at', '12')->get()->toArray();
    $posts4 = Post::whereYear('created_at', '2022')->get()->toArray();
    $posts5 = Post::whereTime('created_at', '>', '16:15:00')->get()->toArray();

    ddd([$posts, $posts2, $posts3, $posts4, $posts5]);
});

Route::get('/posts/sandbox/getAttribute', function () {
    $post = Post::first();
    $attribute = $post->titleAndContent;
    ddd([$post, $attribute]);
});

Route::get('/posts/sandbox/scope', function () {
    $post = Post::updateLastFiveMinutes()->get()->toArray();
    ddd($post);
});

Route::get('/posts/sandbox/order', function () {
    Post::orderBy('updated_at')->get()->toArray();
    Post::orderBy('title')->orderBy('id')->get()->toArray();
    Post::orderBy('title', 'desc')->orderBy('id')->get()->toArray();

    ddd('queryを見てね');
});

Route::get('/posts/sandbox/update/{id}', function ($id) {
    $post = Post::find($id);
    $randNum = rand(0,9999);
    $post->update(
        [
            'title' => '投稿の更新テスト' . $randNum,
            'content_text' => '投稿内容はこちらです' . $randNum,
        ]
    );
    ddd('update success' . $randNum);
});

Route::get('/posts/sandbox/FindOrFail/{id}', function ($id) {
    try{
        $post = Post::findOrFail($id);
    }catch (Exception $e){
        ddd($e);
    }
    ddd($post);
});

Route::get('/posts/sandbox/UpdateOrNew/{id}', function ($id) {
    $randNum = rand(0,9999);
    $post = Post::findOrNew($id);
    $post->title = '投稿の更新テスト' . $randNum;
    $post->content_text = '投稿内容はこちらです' . $randNum;
    $post->user_id = 1;
    $post->save();
    ddd($randNum . 'で更新しました' , $post);
});

Route::get('/posts/sandbox/firstOr/{id}', function ($id) {
    $post = Post::whereId($id)->firstOr(function () {
        throw new Exception('取得できませんでした');
    });
    ddd($post);
});

Route::get('/post/sandbox/withTrashed', function () {
    $postsWithTrashed = Post::withTrashed()->get();
    ddd($postsWithTrashed->toArray());
});

Route::get('/post/sandbox/onlyTrashed', function () {
    $postsOnlyTrashed = Post::onlyTrashed()->get();
    ddd($postsOnlyTrashed->toArray());
});
