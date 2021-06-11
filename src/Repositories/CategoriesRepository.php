<?php

namespace Blublog\Blublog\Repositories;

use Blublog\Blublog\Models\Category;
use Illuminate\Database\Eloquent\Builder;

class CategoriesRepository
{
    /**
     * @var Category
     */
    private $model;

    public function __construct(Category $model)
    {
        $this->model = $model;
    }
    /**
     * Query Builder
     *
     * @param boolean $eagerLoad
     * @return Builder
     */
    public function query(bool $eagerLoad = false): Builder
    {
        $queryBuilder = $this->model->newQuery();

        if (true === $eagerLoad) {
            $queryBuilder->with(['posts']);
        }

        return $queryBuilder;
    }
    public function find(int $id)
    {
        return $this->query(true)
            ->findOrFail($id);
    }
    public function topCategories()
    {
        return $this->query(false)->with('childrenRecursive')->whereNull('parent_id')->get();
    }
    public function all()
    {
        return $this->query(false)
            ->latest()
            ->get();
    }
    public function bySlug(string $slug)
    {
        return $this->query(true)
            ->where([
                ['slug', '=', $slug],
            ])->first();
    }
    public function create($request)
    {
        return Category::create([
            'title' => $request->title,
            'img' => $request->img,
            'descr' => $request->descr,
            'slug' => $request->slug ? $request->slug : blublog_create_slug($request->title),
        ]);
    }
}
