<?php

namespace Philip\Blublog\Models;

use Illuminate\Database\Eloquent\Model;
use Philip\Blublog\Models\Menu;

class MenuItem extends Model
{
    protected $table = 'blublog_menu_items';
    public function post()
    {
        return $this->belongsTo(Menu::class);
    }

}
