<?php

namespace Blublog\Blublog\Models;

use Illuminate\Database\Eloquent\Model;

class Ban extends Model
{
    protected $table = 'blublog_ban';

    public static function ip($ip,$reason, $only_from_comments = 0)
    {
        $ban = new Ban;
        $ban->ip = $ip;
        $ban->descr = $reason;
        if($only_from_comments){
            $ban->comments = true;
        }else{
            $ban->comments = false;
        }
        $ban->save();
        return true;
    }
    public static function is_banned($ip)
    {
        $ban = Ban::where([
            ['ip', '=', $ip],
            ['comments', '=', false],
        ])->first();
        if($ban){
            return true;
        }
        return false;
    }
    public static function is_banned_from_comments($ip)
    {
        $ban = Ban::where([
            ['ip', '=', $ip],
            ['comments', '=', true],
        ])->first();
        if($ban){
            return true;
        }
        return false;
    }
}
