<?php

namespace Blublog\Blublog\Services;

use Blublog\Blublog\Models\Tag;
use Blublog\Blublog\Repositories\TagsRepository;
use Illuminate\Support\Facades\Gate;

class TagService
{
    private $repository;
    public function __construct(TagsRepository $post)
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

    /**
     * Find Tag by slug
     *
     * @param string $slug
     * @return Tag
     */
    public function bySlug(string $slug): Tag
    {
        $post = $this->repository->bySlug($slug);
        if ($post) {
            return $post;
        }
        abort(404);
    }
    public function createFromTitle($request)
    {
        if (!Gate::allows('blublog_create_tags')) {
            abort(403);
        }
        return $this->repository->createFromTitle($request);
    }
    public function update($tag, $request)
    {
        $tag->update($request->all());
    }
    public function create($array)
    {
        $tag_exist = $this->repository->byTitle($array['title']);
        if ($tag_exist) {
            return $tag_exist;
        }
        return $this->repository->create($array);
    }
    public function delete($tag)
    {
        if (!Gate::allows('blublog_delete_tags', $tag)) {
            abort(403);
        }
        $tag->posts()->detach();
        $tag->delete();
    }
    public function getPosts($tag)
    {
        return $this->repository->getPosts($tag);
    }
    public function search($string)
    {
        return $this->repository->search($string);
    }
}
