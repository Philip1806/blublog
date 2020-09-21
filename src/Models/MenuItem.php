<?php

namespace Blublog\Blublog\Models;

use Illuminate\Database\Eloquent\Model;
use Blublog\Blublog\Models\Menu;

class MenuItem extends Model
{
    protected $table = 'blublog_menu_items';
    public function from_menu()
    {
        return $this->belongsTo(Menu::class, 'menu');
    }
    public static function create_new($request)
    {
        $item = new MenuItem;
        $item->label = $request->title;
        $item->url = $request->url;
        $item->parent = $request->parent_id;
        $item->menu = $request->menu_id;
        $item->save();
    }
}
