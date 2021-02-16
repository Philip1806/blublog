<?php

namespace   Blublog\Blublog\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Blublog\Blublog\Models\Category;

use Session;

class BlublogCategoriesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {

        $categories = Category::with('childrenRecursive')->whereNull('parent_id')->get();
        $all_categories = '';

        $all_categories = Category::all();
        $data = array();
        $data[0] = 'None';
        foreach ($all_categories as $role) {
            $data[$role->id] = $role->title;
        }
        return view('blublog::panel.categories.index')->with('categories', $categories)->with('all_categories', $data);
    }

    public function store(Request $request)
    {
        $this->authorize('blublog_create_categories');
        $rules = [
            'title' => 'required|max:250',
        ];
        $this->validate($request, $rules);

        $id = $request['parent_id'];
        unset($request['parent_id']);

        $category = Category::create([
            'title' => $request->title,
            'img' => $request->img,
            'descr' => $request->descr,
            'slug' => $request->slug ? $request->slug : blublog_create_slug($request->title),
        ]);

        if ($id) {
            $category->parent_id = $id;
            $category->save();
        }
        Session::flash('success', "Category added.");
        return back();
    }


    public function update(Request $request, $category_id)
    {
        $this->authorize('blublog_edit_categories');
        $category = Category::findOrFail($category_id);
        $id = $request['parent_id'];
        unset($request['parent_id']);
        $category->update($request->all());
        if ($id) {
            $category->parent_id = $id;
        } else {
            $category->parent_id = null;
        }
        $category->save();
        Session::flash('success', "Category edited.");
        return back();
    }

    public function destroy($category_id)
    {
        $this->authorize('blublog_delete_categories');
        $category = Category::findOrFail($category_id);
        $category->delete();
        Session::flash('success', "Category deleted.");
        return back();
    }
}
