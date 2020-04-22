<?php

namespace Philip\Blublog\Models;

use Illuminate\Database\Eloquent\Model;
use Philip\Blublog\Models\MenuItem;

class Menu extends Model
{
    protected $table = 'blublog_menu_names';
    public function items()
    {
        return $this->hasMany(MenuItem::class, 'menu');
    }

}
