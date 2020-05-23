<?php

namespace Blublog\Blublog\Models;

use Illuminate\Database\Eloquent\Model;
use Intervention\Image\ImageManagerStatic as Image;
use Blublog\Blublog\Models\Post;
use Session;
use Carbon\Carbon;

class Log extends Model
{
    protected $table = 'blublog_logs';

    // Types: Error, Alert, Visit, Info, bot
    public static function add($data, $type, $message = "Visit")
    {
        $user_agent = \Request::header('User-Agent');
        if(strpos($user_agent, "bot") or !\Request::header('accept-language')){
            if($type == "visit"){
                $type = "bot";
            }
            $lang = 'Error. It is bot...';
        } else{
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
        $track->save();

        return true;

    }

}
