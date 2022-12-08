<?php

namespace Blublog\Blublog\Repositories;

use Blublog\Blublog\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PostsRepository
{
    /**
     * @var Post
     */
    private $model;

    public function __construct(Post $model)
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
            $queryBuilder->with(['categories']);
        }

        return $queryBuilder;
    }
    public function find(int $id)
    {
        return $this->query(true)
            ->findOrFail($id);
    }
    /**
     * Get all posts
     *
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->query(true)
            ->latest()
            ->get();
    }
    public function fromUser(int $id, string $status = null)
    {
        return $this->query(true)
            ->where([
                ['user_id', '=', $id],
            ])
            ->when($status, function ($query) use ($status) {
                return $query->where('status', '=', $status);
            })
            ->latest()
            ->paginate(config('blublog.posts-from-user'));
    }
    public function withStatus(string $status = "publish")
    {
        return $this->query(true)
            ->where('status', '=', $status)->latest()->paginate(config('blublog.posts-per-page-with-status'));
    }
    public function searchInUserPosts($search, $id, string $status = "publish", $paginate = true)
    {
        $result = $this->query(true)
            ->where([
                ['user_id', '=', $id],
                ['title', 'like', '%' . $search . '%'],
                ['status', '=', $status],
            ])->latest();
        if ($paginate) {
            return $result->paginate(config('blublog.posts-per-page-from-search'));
        }
        return $result->get();
    }
    public function search(
        $search,
        string $status = 'publish',
        $paginate = false
    ): LengthAwarePaginator|Collection {
        $search_results = $this->query(true)
            ->where('title', 'like', '%' . $search . '%')
            ->orWhere('content', 'like', '%' . $search . '%')
            ->where('status', '=', $status)
            ->latest();
        return $paginate
            ? $search_results->paginate(config('blublog.posts-per-page-from-search'))
            : $search_results->get();
    }
    public function searchTitle(
        $search,
        string $status = 'publish',
        $paginate = false
    ): LengthAwarePaginator|Collection {
        $search_results = $this->query(true)
            ->where('title', 'like', '%' . $search . '%')
            ->where('status', '=', $status)
            ->latest();
        return $paginate
            ? $search_results->paginate(config('blublog.posts-per-page-from-search'))
            : $search_results->get();
    }
    public function searchContent($search, string $status = "publish", $paginate = true)
    {
        $search_results = $this->query(true)
            ->where('content', 'like', '%' . $search . '%')
            ->where('status', '=', $status)
            ->latest();
        return $paginate
            ? $search_results->paginate(config('blublog.posts-per-page-from-search'))
            : $search_results->get();
    }
    public function recommended()
    {
        return $this->query(true)
            ->where([
                ['status', '=', 'publish'],
                ['recommended', '=', true],
            ])->latest()->get();
    }
    public function bySlug(string $slug)
    {
        return $this->query(true)
            ->where([
                ['status', '=', 'publish'],
                ['slug', '=', $slug],
            ])->first();
    }
    public function postsLastSevenDays()
    {
        return $this->query(false)
            ->where('created_at', '>=', now()->subDays(7))->get();
    }
    public function lastMonthPosts()
    {
        return $this->query(false)
            ->whereMonth('created_at', '=', now()->subMonth()->month)->whereYear('created_at', '=', now()->year)->get();
    }

    public function myLastMonthPosts(): int
    {
        return $this->query(false)
            ->where('user_id', auth()->user()->id)
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->year)
            ->count();
    }
    public function myThisMonthPosts(): int
    {
        return $this->query(false)
            ->where('user_id', auth()->user()->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
    }
    public function myPosts(): int
    {
        return $this->query(false)
            ->where('user_id', '=', auth()->user()->id)->count();
    }
    public function totalPosts(): int
    {
        return $this->query(false)->count();
    }
    public function delete(Post $post)
    {
        $post->categories()->detach();
        $post->tags()->detach();
        $post->delete();
    }
    public function update(Post $post, $request)
    {
        $post->type = $request->type;
        $post->file_id = $request->file_id;
        $post->tags()->sync($request->tags);
        $post->categories()->sync($request->categories);
        $post->save();
    }
    public function create(Post $post, $request)
    {
        $post->type = $request->type;
        $post->file_id = $request->file_id;
        $post->save();
        $post->tags()->sync($request->tags, false);
        $post->categories()->sync($request->categories, false);
        $post->save();
    }
}
