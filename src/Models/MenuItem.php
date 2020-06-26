<?php

namespace Blublog\Blublog\Models;

use Illuminate\Database\Eloquent\Model;
use Blublog\Blublog\Models\Menu;

class MenuItem extends Model
{
    protected $table = 'blublog_menu_items';
    public function from_menu()
    {
        return $this->belongsTo(Menu::class,'menu');
    }

}
