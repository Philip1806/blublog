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
        $menu = Menu::find($id);
        if(!$menu){
            abort(404);
        }
        $setting = Setting::where([
            ['name', '=', "main_menu_name"],
        ])->first();
        if(!$setting){
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
        $menu = Menu::find($id);
        if(!$menu){
            abort(404);
        }
        return view('blublog::panel.menu.items')->with('menu', $menu);
    }
    public function edit_item($id)
    {

        $item = MenuItem::find($id);
        if(!$item){
            abort(404);
        }
        return view('blublog::panel.menu.edit')->with('item', $item);
    }
    public function edit_item_update(Request $request)
    {
        $rules = [
            'label' => 'required|max:200',
            'url' => 'required|max:200',
            'item_id' => 'required|max:200',
        ];
        $this->validate($request, $rules);

        $item = MenuItem::find($request->item_id);
        if($item){
            $item->label =$request->label;
            $item->url = $request->url;
            $item->save();
            Session::flash('success', __('blublog.contentedit'));
            Cache::forget('blublog.menu.'.$item->from_menu->name);
            return back();
        }
        Session::flash('error', __('blublog.404'));
        return back();
    }
    public function edit_menu_update(Request $request)
    {
        $rules = [
            'name' => 'required|max:200',
            'menu_id' => 'required|max:200',
        ];
        $this->validate($request, $rules);

        $menu = Menu::find($request->menu_id);

        if($menu){
            $menu->name = $request->name;
            $menu->save();
            Cache::forget('blublog.menu.'.$menu->name);
            Session::flash('success', __('blublog.contentedit'));
        }

        return back();
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
        $menu = Menu::find($id);
        if($menu){
            foreach($menu->items as $item){
                $item->delete();
            }
            Cache::forget('blublog.menu.'.$menu->name);
            $menu->delete();
            Session::flash('success', __('blublog.contentdelete'));
            return back();
        }
        return back();
    }

    public function destroy_item($id)
    {
        $item = MenuItem::find($id);
        if($item){
            Cache::forget('blublog.menu.'.$item->from_menu->name);
            $item->delete();
            Session::flash('success', __('blublog.contentdelete'));
            return back();
        }
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

        $menu = Menu::find($request->menu_id);
        $parent = MenuItem::find($request->parent_id);
        Cache::forget('blublog.menu.'.$menu->name);
        if($parent->parent == 1){
            Session::flash('error', "That nesting is not supported.");
            return back();
        }

        if( $menu and  $parent){
            $item = new MenuItem;
            $item->label =$request->title;
            $item->url = $request->url;
            $item->parent =$request->parent_id;
            $item->menu = $request->menu_id;
            $item->save();
            Session::flash('success', __('blublog.contentcreate'));
            return back();
        }
        Session::flash('error', "Unvalid id data.");
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

        $menu = Menu::find($request->menu_id);
        Cache::forget('blublog.menu.'.$menu->name);
        if($menu){
            $item = new MenuItem;
            $item->label =$request->title;
            $item->url =$request->url;
            $item->parent =0;
            $item->menu = $menu->id;
            $item->save();
            Session::flash('success', __('blublog.contentcreate'));
            return back();
        }
        Session::flash('error', "Menu id is wrong.");
        return back();
    }
}
