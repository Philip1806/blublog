<?php

namespace Blublog\Blublog\Models;

use Illuminate\Database\Eloquent\Model;
use Blublog\Blublog\Models\Post;

class Log extends Model
{
    protected $table = 'blublog_logs';

    // Types: Error, Alert, Visit, Info, bot
    public static function add($data, $type, $message = "Visit")
    {
        $user_agent = \Request::header('User-Agent');
        if (strpos($user_agent, "bot") or !\Request::header('accept-language')) {
            if ($type == "visit") {
                $type = "bot";
            }
            $lang = 'Error. It is bot...';
        } else {
            $lang = \Request::header('accept-language');
        }
        $track = new Log;
        $track->ip = Post::getIp();
        $track->user_agent = $user_agent;
        $track->request_url = url()->full();
        $track->referer = \Request::header('referer');
        $track->lang = $lang;
        $track->message = $message;
        $track->data = $data;
        $track->type = $type;
        if ($type != "visit" and \Auth::check()) {
            $track->user_id = \Auth::user()->id;
        }
        $track->save();

        return true;
    }
    public static function by_user($blublog_user_id, $limit = 10)
    {
        return Log::where([
            ['user_id', '=', $blublog_user_id],
            ['type', '!=', "visit"],
        ])->limit($limit)->latest()->get();
    }
    public static function latest_important($limit = 10)
    {
        return Log::where([
            ['type', '=', 'error'],
            ['created_at', '>', now()->subDay()],
        ])->orWhere(function ($query) {
            $query->where('type', '=', 'alert');
            $query->where('created_at', '>', now()->subDay());
        })->latest()->limit($limit)->get();
    }
}
