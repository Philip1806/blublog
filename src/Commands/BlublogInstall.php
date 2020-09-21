<?php

namespace Blublog\Blublog\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Blublog\Blublog\Models\Post;

class BlublogInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blublog:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install BLUblog';

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
        $this->info('Installing BLUblog');

        if ($this->ask('Do not use express install? ("y" for yes)') == "y") {
            if ($this->ask('Create migrations? ("y" for yes)') == "y") {
                $this->call('migrate');
            }
            if ($this->ask('Publish files? ("y" for yes)') == "y") {
                $this->call('vendor:publish', ['--provider' => 'Blublog\Blublog\BlublogServiceProvider']);
            }
            if ($this->ask('Run BLUblog Set up? ("y" for yes)') == "y") {
                $this->call('blublog:setup');
            }
            return;
        }
        $this->info('Installing BLUblog');
        $this->call('migrate');
        $this->call('vendor:publish', ['--provider' => 'Blublog\Blublog\BlublogServiceProvider']);
        $this->call('blublog:setup');
    }
}
