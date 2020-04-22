<?php

namespace Philip1503\Blublog\Models;

use Illuminate\Database\Eloquent\Model;

class BlublogUser extends Model
{
    protected $table = 'blublog_users';
    public function role() {
        return $this->hasOne('App\User', 'id', 'user_id');
    }
}
