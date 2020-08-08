<?php

namespace Blublog\Blublog\Models;

use Illuminate\Database\Eloquent\Model;
use Blublog\Blublog\Models\MenuItem;

class Menu extends Model
{
    protected $table = 'blublog_menu_names';
    public function items()
    {
        return $this->hasMany(MenuItem::class, 'menu');
    }
    public static function get_html($template, $url, $label)
    {
        $template = str_replace("((LINK))", $url, $template);
        $template = str_replace("((LABEL))", $label, $template);
        return $template;
    }
}
