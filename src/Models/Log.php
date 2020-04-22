<?php

namespace Philip1503\Blublog\Models;

use Illuminate\Database\Eloquent\Model;
use Intervention\Image\ImageManagerStatic as Image;
use Philip1503\Blublog\Models\Post;
use Session;

class Log extends Model
{
    protected $table = 'blublog_logs';

//Types: Error, Alert, Visit, Info, bot
    public static function add($data, $type, $message = "Посещение")
    {
        if(\Request::header('connection') and \Request::header('cache-control') and \Request::header('upgrade-insecure-requests') and \Request::header('accept') and \Request::header('accept-encoding') and \Request::header('cookie') ){
            $data =  \Request::header('host') . \Request::header('connection') . \Request::header('cache-control') . \Request::header('upgrade-insecure-requests') .\Request::header('User-Agent') .\Request::header('accept') .\Request::header('referer').\Request::header('accept-encoding').\Request::header('cookie');
        }
        $request_url = \Request::header('host');
        $user_agent = \Request::header('User-Agent');
        $referer = \Request::header('referer');
        if(\Request::header('accept-language')){
            $lang = \Request::header('accept-language');
        } else{
            $lang = 'Error. It is bot...';
        }

        $ip = Post::getIp();

        $track = new Log;
        $track->ip = $ip;
        $track->user_agent = $user_agent;
        $track->request_url = $request_url;
        $track->referer = $referer;
        $track->lang = $lang;
        $track->message = $message;
        $track->data = $data;
        $track->type = $type;

        $track->save();

        return true;

    }

}
