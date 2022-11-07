<?php

namespace App\Console\Commands;

use App\Post;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class LoggerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:logger {id} {--type=user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ログ記録コマンド';

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
     * @return mixed
     */
    public function handle()
    {
        $id = $this->argument('id');
        $type = $this->option('type');
        $this->info("[{$this->description}]が実行されました. 引数:id={$id}, オプション:{$type}");

        switch ($type) {
            case "user":
                $user = User::find($id);
                $this->info("ユーザ名:{$user->name}様");
                Log::info("ユーザ名:{$user->name}様");
                break;
            case "post":
                $post = Post::find($id);
                $this->info("投稿タイトル:{$post->title}");
                $name = $this->choice('What is your name?', ['delete', 'edit'], 0);

                switch ($name) {
                    case "delete":
                        $this->info("削除モード！");
                        if ($this->confirm('本当に削除しますか？')) {
                            $this->info("削除したというていです");
                        }
                        break;
                    case "edit":
                        $this->info("編集モード！");
                        break;
                }

                break;
        }
        return 0;
    }
}
