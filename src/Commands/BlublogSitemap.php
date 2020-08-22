<?php

namespace Blublog\Blublog\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Blublog\Blublog\Models\Post;

class BlublogSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blublog:sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate sitemap with blublog posts';

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
        $this->info("Generating RSS Feed From Post");
        $posts = Post::where([
            ['status', '=', "publish"],
        ])->latest()->get();
        $posts = Post::processing($posts);
        $rssFileContents = view('blublog::rss', ['posts' =>  $posts]);
        Storage::disk(config('blublog.files_disk', 'blublog'))->put('rss.xml', $rssFileContents);
        $this->info("\n" . "Completed");
    }
}
