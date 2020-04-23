<?php

namespace Philip1503\Blublog\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Ban extends Model
{
    protected $table = 'blublog_ban';
    protected $fillable = [
        'ip','comments','descr',
    ];

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
    public static function get_from()
    {
        $now = Carbon::today();
        $thisday = Carbon::parse($now)->format('d');
        $hour = Carbon::parse($now)->format('h');
        $thisyear = Carbon::parse($now)->format('Y');
        $thismonth = Carbon::parse($now)->format('m');
        $to = Carbon::create($thisyear, $thismonth, $thisday,$hour,59)->toDateTimeString();
        return $to;
    }
    public static function get_to()
    {
        $now = Carbon::today();
        $thisday = Carbon::parse($now)->format('d');
        $hour = Carbon::parse($now)->format('h');
        $thisyear = Carbon::parse($now)->format('Y');
        $thismonth = Carbon::parse($now)->format('m');
        $hour = $hour - 2;
        $from = Carbon::create($thisyear, $thismonth, $thisday,$hour)->toDateTimeString();
        return $from;

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
