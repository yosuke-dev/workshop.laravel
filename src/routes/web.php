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

use App\AttachTag;
use App\Comment;
use App\Image;
use App\Post;
use App\Tag;
use App\User;
use App\UserProfile;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

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
    ddd('entry success' . $randNum);
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
    $post = Post::whereId($id)->firstOr(fn () =>
        new Exception('取得できませんでした')
    );
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

Route::get('/posts/sandbox/chunk/', function () {
    $posts = Post::chunk(1, function ($posts){
        return $posts->get('id');
    });
    ddd($posts);
});

Route::get('/seed/relationalData/reset', function () {
    // データを削除
    Schema::disableForeignKeyConstraints();
    Comment::truncate();
    Post::truncate();
    User::truncate();
    Tag::truncate();
    AttachTag::truncate();
    Image::truncate();
    UserProfile::truncate();
    Schema::enableForeignKeyConstraints();

    // データを生成
    factory(User::class, 5)->create()->each(function ($user) {
        factory(Post::class, rand(1, 3))->create(['user_id' => $user->id])->each(function ($post) use ($user){
            factory(Comment::class, rand(1, 3))->create(['post_id' => $post->id, 'user_id' => $user->id]);
            factory(Image::class, rand(0, 3))->create(['imageable_id' => $post->id, 'imageable_type' => 'App\Post']);
        });
        factory(UserProfile::class, rand(0, 1))->create(['user_id' => $user->id]);
        factory(Image::class, rand(0, 1))->create(['imageable_id' => $user->id, 'imageable_type' => 'App\User']);
    });

    factory(Tag::class, 5)->create();
    factory(AttachTag::class, 10)->create();

    // 生成したデータを確認
    $users = User::all()->toArray();
    $userProfiles = UserProfile::all()->toArray();
    $posts = Post::all()->toArray();
    $comments = Comment::all()->toArray();
    $images = Image::all()->toArray();
    $tags = Tag::all()->toArray();
    $attachTags = AttachTag::all()->toArray();

    ddd($users, $userProfiles, $posts, $comments, $images, $tags, $attachTags);
});

Route::get('/relation/sandbox/belongsTo', function () {
    // N:1 (Lazy Loading:怠惰な読み込み)
    $posts = Post::all();
    $postNames = [];
    foreach ($posts as $post) {
        $postNames[] = "tilte:{$post->title}, content{$post->content_text}, post_user{$post->user->name}";
    }
    ddd($postNames);
});

Route::get('/relation/sandbox/belongsToWith', function () {
    // with レコード走査時に、関連先オブジェクトを一括生成する (eager loading:熱望的な読み込み)
    $posts = Post::with('user')->get();
    $postNames = [];
    foreach ($posts as $post) {
        $postNames[] = "tilte:{$post->title}, content{$post->content_text}, post_user{$post->user->name}";
    }
    ddd($postNames);
});

Route::get('/relation/sandbox/belongsToLoad', function () {
    // load レコード走査時に、関連先オブジェクトを一括生成する事を後から宣言する (遅延Eagerロード)
    $posts = Post::all();
    $posts->load('user');
    $postNames = [];
    foreach ($posts as $post) {
        $postNames[] = "tilte:{$post->title}, content{$post->content_text}, post_user{$post->user->name}";
    }
    ddd($postNames);
});

Route::get('/relation/sandbox/hasMany', function () {
    // 1:N
    $posts = Post::with('comments')->get();
    $postComments = [];
    foreach ($posts as $key => $post) {
        foreach ($post->comments as $comment) {
            $postComments[$key][] = "tilte:{$post->title}, content{$post->content_text}, message:{$comment->message}";
        }
    }
    ddd($postComments);
});

Route::get('/relation/sandbox/linkHasMany/{id}', function ($id) {
    // リレーションを繋げて取得
    $user = User::with('posts.comments')->find($id);
    $postComments = [];
    foreach ($user->posts as $post) {
        $postComments[] = "post_title:{$post->title}, comment_count:{$post->comments->count()}";
    }
    ddd($user->name, $postComments);
});

Route::get('/relation/sandbox/efficientHasMany/{id}', function ($id) {
    // 効率的に必要なカラムのみ指定して取得する
    // Point: 外部Keyに該当するカラムは取得対象に含めること。ORMがCollection化する時にデータが欠落する。
    $user = User::with([
        'posts:id,title,user_id',
        'posts.comments:id,post_id'
    ])->select(['id', 'name'])->find($id);
    $postComments = [];
    foreach ($user->posts as $post) {
        $postComments[] = "post_title:{$post->title}, comment_count:{$post->comments->count()}";
    }
    ddd($user->name, $postComments);
});

Route::get('/relation/sandbox/relationMethod', function () {
    // with レコード走査時に、関連先オブジェクトを一括生成する。と思い気やメソッドを使うとクエリビルダーになるので注意
    $withPosts = Post::with('comments')->get();
    ddd($withPosts[0]->comments, $withPosts[0]->comments());
});

Route::get('/relation/sandbox/hasOne/', function () {
    // 1:1
    $users = User::with('profile')->get();
    $profiles = [];
    foreach ($users as $user) {
        $profile = is_null($user->profile) ? 'プロフィール未登録' : "{$user->profile->country}-{$user->profile->city}";
        $profiles[] = "user_name:{$user->name}, profile:{$profile}";
    }
    ddd($profiles);
});

Route::get('/relation/sandbox/belongsToMany/', function () {
    // 多:多
    $posts = Post::with('tags')->get();
    $postTags = [];
    foreach ($posts as $key => $post) {
        $tagNames = implode(" | ", $post->tags->pluck('name')->toArray());
        $postTags[] = "post_title:{$post->title}, tags:{$tagNames}";
    }
    // 多:多(逆方向)
    $tags = Tag::with('posts')->get();
    $tagPosts = [];
    foreach ($tags as $tag) {
        $tagPosts[] = "tag:{$tag->name}, post_count:{$tag->posts->count()}";
    }
    ddd($postTags, $tagPosts);
});

Route::get('/relation/sandbox/hasManyThrough/', function () {
    // リレーションのテーブルをスキップして更に先のリレーションデータを取得
    $users = User::with('postComments')->get();
    $postComments = [];
    foreach ($users as $key => $user) {
        foreach($user->postComments as $comment) {
            $postComments[$key][] = "user_name:{$user->name}, comment:{$comment->message}";
        }
    }
    ddd($postComments);
});

Route::get('/relation/sandbox/morphOne/', function () {
    // 1:1 ポリモーフィックリレーション
    $users = User::with('image')->get();
    $userImages = [];
    foreach ($users as $user) {
        $imageUrl = $user->image->url ?? 'noImage!!!';
        $userImages[] = "user_name:{$user->name}, image_url:{$imageUrl}";
    }
    ddd($userImages);
});

Route::get('/relation/sandbox/morphMany/', function () {
    // 1:N ポリモーフィックリレーション
    $posts = Post::with('images')->get();
    $postImages = [];
    foreach ($posts as $key => $post) {
        foreach($post->images as $image) {
            $postImages[$key][] = "title:{$post->title}, image_url:{$image->url}";
        }
    }
    ddd($postImages);
});

Route::get('/relation/sandbox/morphTo/', function () {
    // N:1 or 1:1 ポリモーフィックリレーション
    $images = Image::with('imageable')->get();
    $imageValues = [];
    foreach ($images as $key => $image) {
        $morphModel = $image->imageable;

        $imageValues[] = ($morphModel instanceof Post
                ? "PostTitle:{$morphModel->title}" : "UserName:{$morphModel->name}")
            . ", image_url:{$image->url}";
    }
    ddd($imageValues);
});

Route::get('/relation/sandbox/add/{post_id}/{user_id}/{message}', function ($post_id, $user_id, $message) {
    $post = Post::with('comments')->find($post_id);
    $comment = new Comment([
        'user_id' => $user_id,
        'message' => $message,
    ]);
    $post->comments()->save($comment);
    $changedPost = Post::with('comments')->find($post_id);
    ddd($post->toArray(), $changedPost->toArray());
});

Route::get('/relation/sandbox/delete/{post_id}', function ($post_id) {
    $post = Post::with('comments')->find($post_id);
    $post->comments()->delete();
    $changedPost = Post::with('comments')->find($post_id);
    ddd($post->toArray(), $changedPost->toArray());
});

Route::get('/relation/sandbox/attach/{post_id}/{tag_id}', function ($post_id, $tag_id) {
    $post = Post::with('tags')->find($post_id);
    $post->tags()->attach($tag_id);
    $changedPost = Post::with('tags')->find($post_id);
    ddd($post->tags->toArray(), $changedPost->tags->toArray());
});

Route::get('/relation/sandbox/detach/{post_id}/{tag_id}', function ($post_id, $tag_id) {
    $post = Post::with('tags')->find($post_id);
    $post->tags()->detach($tag_id);
    $changedPost = Post::with('tags')->find($post_id);
    ddd($post->tags->toArray(), $changedPost->tags->toArray());
});
