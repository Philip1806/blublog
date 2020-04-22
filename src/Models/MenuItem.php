<?php

namespace Philip1503\Blublog\Models;

use Illuminate\Database\Eloquent\Model;
use Philip1503\Blublog\Models\Menu;

class MenuItem extends Model
{
    protected $table = 'blublog_menu_items';
    public function post()
    {
        return $this->belongsTo(Menu::class);
    }

}
