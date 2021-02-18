<?php

namespace Blublog\Blublog\Models;

use Illuminate\Database\Eloquent\Model;

class Revision extends Model
{
    protected $table = 'blublog_revisions';
    public function user()
    {
        return $this->belongsTo(blublog_user_model(), 'user_id');
    }
    public function role()
    {
        return $this->belongsTo(Post::class);
    }
}
