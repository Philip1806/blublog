<?php

namespace Blublog\Blublog\Services;

use Blublog\Blublog\Models\Category;
use Blublog\Blublog\Repositories\CategoriesRepository;

class CategoryService
{
    private $repository;
    public function __construct(CategoriesRepository $post)
    {
        $this->repository = $post;
    }
    public function findById(int $id)
    {
        return $this->repository->find($id);
    }
    public function getAll()
    {
        return $this->repository->all();
    }
    public function topCategories()
    {
        return  $this->repository->topCategories();
    }

    /**
     * Find category by slug
     *
     * @param string $slug
     * @return Category
     */
    public function bySlug(string $slug): Category
    {
        $post = $this->repository->bySlug($slug);
        if ($post) {
            return $post;
        }
        abort(404);
    }
    public function create($request)
    {
        return $this->repository->create($request);
    }
    public function update($category)
    {
        $category->save();
    }
    public function delete($category)
    {
        $category->posts()->detach();
        $category->delete();
    }
    public function getPosts($category)
    {
        return $category->getPosts()->paginate(config('blublog.posts-per-page-from-category'));
    }
    public function toSelectArray()
    {
        $allCategories = $this->getAll();
        $categories = array();
        foreach ($allCategories as $category) {
            $categories[$category->id] = $category->title;
        }
        return $categories;
    }
}
