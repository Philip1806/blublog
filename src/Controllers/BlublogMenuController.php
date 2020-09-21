<?php

namespace   Blublog\Blublog\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Blublog\Blublog\Models\Setting;
use Blublog\Blublog\Models\Menu;
use Blublog\Blublog\Models\MenuItem;
use Session;

class BlublogMenuController extends Controller
{

    public function index()
    {
        $menus = Menu::latest()->get();
        return view('blublog::panel.menu.index')->with('menus', $menus);
    }
    public function set_main_menu($id)
    {
        $menu = Menu::findOrFail($id);
        $setting = Setting::where([
            ['name', '=', "main_menu_name"],
        ])->first();
        if (!$setting) {
            $setting = new Setting;
            $setting->name = "main_menu_name";
            $setting->val = serialize($menu->name);
            $setting->type = "string";
            $setting->save();
        } else {
            $setting->val = serialize($menu->name);
            $setting->save();
        }
        Cache::forget('blublog.settings.main_menu_name');
        return back();
    }
    public function menu_items($id)
    {
        return view('blublog::panel.menu.items')->with('menu', Menu::findOrFail($id));
    }
    public function edit_item($id)
    {
        return view('blublog::panel.menu.edit')->with('item',  MenuItem::findOrFail($id));
    }
    public function edit_item_update(Request $request)
    {
        $rules = [
            'label' => 'required|max:200',
            'url' => 'required|max:200',
            'item_id' => 'required|max:200',
        ];
        $this->validate($request, $rules);

        $item = MenuItem::findOrFail($request->item_id);
        $item->label = $request->label;
        $item->url = $request->url;
        $item->save();
        Session::flash('success', __('blublog.contentedit'));
        Cache::forget('blublog.menu.' . $item->from_menu->name);
        return back();
    }
    public function edit_menu_update(Request $request)
    {
        $rules = [
            'name' => 'required|max:200',
            'menu_id' => 'required|max:200',
        ];
        $this->validate($request, $rules);

        $menu = Menu::findOrFail($request->menu_id);
        $menu->name = $request->name;
        $menu->save();
        Cache::forget('blublog.menu.' . $menu->name);
        Session::flash('success', __('blublog.contentedit'));
    }
    public function add_menu_store(Request $request)
    {
        $rules = [
            'title' => 'required|max:200',
        ];
        $this->validate($request, $rules);

        $menu = new Menu;
        $menu->name = $request->title;
        $menu->save();

        Session::flash('success', __('blublog.contentcreate'));
        return back();
    }
    public function destroy_menu($id)
    {
        $menu = Menu::findOrFail($id);
        foreach ($menu->items as $item) {
            $item->delete();
        }
        Cache::forget('blublog.menu.' . $menu->name);
        $menu->delete();
        Session::flash('success', __('blublog.contentdelete'));
        return back();
    }

    public function destroy_item($id)
    {
        $item = MenuItem::findOrFail($id);
        Cache::forget('blublog.menu.' . $item->from_menu->name);
        $item->delete();
        Session::flash('success', __('blublog.contentdelete'));
        return back();
    }

    public function add_child_store(Request $request)
    {
        $rules = [
            'title' => 'required|max:200',
            'url' => 'required|max:250',
            'menu_id' => 'required',
            'parent_id' => 'required',
        ];
        $this->validate($request, $rules);

        $menu = Menu::findOrFail($request->menu_id);
        $parent = MenuItem::findOrFail($request->parent_id);
        Cache::forget('blublog.menu.' . $menu->name);
        if ($parent->parent == 1) {
            Session::flash('error', "That nesting is not supported.");
            return back();
        }
        MenuItem::create_new($request);
        Session::flash('success', __('blublog.contentcreate'));
        return back();
    }


    public function add_parent_store(Request $request)
    {
        $rules = [
            'title' => 'required|max:200',
            'url' => 'required|max:250',
            'menu_id' => 'required',
        ];
        $this->validate($request, $rules);

        $menu = Menu::findOrFail($request->menu_id);
        Cache::forget('blublog.menu.' . $menu->name);
        $item = new MenuItem;
        $item->label = $request->title;
        $item->url = $request->url;
        $item->parent = 0;
        $item->menu = $menu->id;
        $item->save();
        Session::flash('success', __('blublog.contentcreate'));
        return back();
    }
}
