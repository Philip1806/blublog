<?php

namespace   Blublog\Blublog\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Blublog\Blublog\Exceptions\InvalidCategoryParentException;
use Blublog\Blublog\Models\Log;
use Blublog\Blublog\Requests\CategoryRequest;
use Blublog\Blublog\Services\CategoryService;
use Session;

class BlublogCategoriesController extends Controller
{
    protected $categoryService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CategoryService $categoryservice)
    {
        $this->categoryService = $categoryservice;
        $this->middleware('auth');
    }

    /**
     * Panel Page For Categories
     *
     * @return \Illuminate\View\View
     */
    public function index(): \Illuminate\View\View
    {
        $categories = $this->categoryService->topCategories();
        $all_categories = $this->categoryService->getAll();
        $data = array();
        $data[0] = 'None';
        foreach ($all_categories as $role) {
            $data[$role->id] = $role->title;
        }
        return view('blublog::panel.categories.index')->with('categories', $categories)->with('all_categories', $data);
    }

    /**
     * Create Category
     *
     * @param CategoryRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CategoryRequest $request): \Illuminate\Http\RedirectResponse
    {
        $this->authorize('blublog_create_categories');

        $id = $request['parent_id'];
        unset($request['parent_id']);

        $category = $this->categoryService->create($request);

        if ($id) {
            $category->parent_id = $id;
            $this->categoryService->update($category);
        }
        Session::flash('success', "Category added.");
        Log::add(json_encode($category->toArray()), 'info', 'A category was added.');
        return back();
    }

    /**
     * Edit Category
     *
     * @param Request $request
     * @param integer $category_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, int $category_id): \Illuminate\Http\RedirectResponse
    {
        $this->authorize('blublog_edit_categories');
        $category = $this->categoryService->findById($category_id);
        $id = $request['parent_id'];
        unset($request['parent_id']);
        $category->update($request->all());
        if ($id) {
            if ($category->id == $id) {
                throw new InvalidCategoryParentException();
            }
            $category->parent_id = $id;
        } else {
            $category->parent_id = null;
        }
        $this->categoryService->update($category);
        Session::flash('success', "Category edited.");
        Log::add(json_encode($category->toArray()), 'info', 'A category was edited.');

        return back();
    }

    /**
     * Delete a Category
     *
     * @param integer $category_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $category_id): \Illuminate\Http\RedirectResponse
    {
        $this->authorize('blublog_delete_categories');
        $category = $this->categoryService->findById($category_id);
        Log::add(json_encode($category->toArray()), 'info', 'A category was deleted.');
        $this->categoryService->delete($category);
        Session::flash('success', "Category deleted.");
        return back();
    }
}
