<?php

namespace Blublog\Blublog\Repositories;

use Blublog\Blublog\Models\Tag;
use Illuminate\Database\Eloquent\Builder;

class TagsRepository
{
    /**
     * @var Tag
     */
    private $model;

    public function __construct(Tag $model)
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
            ->find($id);
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
    public function byTitle(string $slug)
    {
        return $this->query(false)
            ->where([
                ['title', '=', $slug],
            ])->first();
    }
    public function createFromTitle($title)
    {
        return Tag::create([
            'title' => $title,
            'slug' => blublog_create_slug($title),
        ]);
    }
    public function create($array)
    {
        return Tag::create($array);
    }

    public function getPosts($tag)
    {
        return $tag->posts()->where('status', '=', 'publish')->latest()->paginate(config('blublog.posts-form-tag-per-page-with-status'));
    }
    public function search($string)
    {
        return $this->query(false)->where('title', 'like', '%' . $string . '%')->latest()->paginate(config('blublog.tags-per-page-from-search'));;
    }
}
