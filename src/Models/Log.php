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
        if (blublog_is_bot()) {
            if ($type == "visit") {
                $type = "bot";
            }
            $lang = 'Error. It is bot...';
        } else {
            $lang = \Request::header('accept-language');
        }
        $track = new Log;
        $track->ip = Log::getIp();
        $track->user_agent = $user_agent;
        $track->request_url = url()->full();
        $track->referer = \Request::header('referer');
        $track->lang = $lang;
        $track->message = $message;
        $track->data = $data;
        $track->type = $type;
        if (\Auth::check()) {
            $track->user_id = \Auth::user()->id;
        }
        $track->save();

        return true;
    }
    public static function getIp()
    {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
    }
    public static function postViews($post_id)
    {
        return Log::where([
            ['type', '=', 'visit'],
            ['data', '=', $post_id],
        ])->latest()->get();
    }
    public static function userSeenPost($post_id)
    {
        if (!blublog_is_bot()) {
            $log = Log::where([
                ['ip', '=', Log::getIp()],
                ['type', '=', 'visit'],
                ['data', '=', $post_id],
            ])->first();
            if ($log) {
                return true;
            }
            return false;
        }
        return true;
    }
    public static function postLiked($post_id)
    {
        if (!blublog_is_bot()) {
            $log = Log::where([
                ['ip', '=', Log::getIp()],
                ['type', '=', 'like'],
                ['data', '=', $post_id],
            ])->first();
            if ($log) {
                return true;
            }
            return false;
        }
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
