<?php

namespace Philip\Blublog\Commands;
use Illuminate\Console\Command;
use App\User;
use Philip\Blublog\Models\BlublogUser;

class BlublogSetUp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blublog:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup BLUblog for first use.';

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
        $users = User::get();
        foreach ($users as $user){
            $Blublog_User = new BlublogUser;
            $Blublog_User->user_id = $user->id;
            $Blublog_User->role = "Administrator";
            $Blublog_User->save();
        }
    }
}
