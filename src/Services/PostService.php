<?php

namespace Blublog\Blublog\Services;

use Blublog\Blublog\Exceptions\InvalidPostStatusException;
use Blublog\Blublog\Exceptions\PostStatusPermission;
use Illuminate\Support\Facades\Gate;

use Blublog\Blublog\Models\Log;
use Blublog\Blublog\Models\Post;
use Blublog\Blublog\Models\Tag;
use Blublog\Blublog\Repositories\PostsRepository;
use Blublog\Blublog\Repositories\TagsRepository;
use Exception;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Throwable;

class PostService
{
    private $repository;
    public function __construct(PostsRepository $post, TagsRepository $tag)
    {
        $this->repository = $post;
        $this->tagRepository = $tag;
    }

    public function findFromUserId($id, $status = null)
    {
        return $this->repository->fromUser($id, $status);
    }
    public function findById(int $id)
    {
        return $this->repository->find($id);
    }
    public function search($string, $status = 'publish', $paginate = true)
    {
        $user = auth()->user();

        $haveAccess = false;

        if ($this->getAccessCodeForStatus($status) == 3 and blublog_have_permission('view-' . $status)) {
            $haveAccess = true;
        } elseif ($this->statusIsPrivate($status)) {
            return $this->repository->searchInUserPosts($string, $user->id, $status, $paginate);
        } elseif ($this->getAccessCodeForStatus($status) == 1 and blublog_is_mod()) {
            $haveAccess = true;
        } elseif ($this->getAccessCodeForStatus($status) == 0) {
            $haveAccess = true;
        }

        if ($haveAccess) {
            return $this->repository->search($string, $status, $paginate);
        }
        if ($paginate) {
            return new LengthAwarePaginator(array(), 0, 5);
        }
        return null;
    }
    public function searchContent($string, $postStatus = 'publish', $paginate = true)
    {
        return $this->repository->searchContent($string, $postStatus, $paginate);
    }
    public function searchInUserPosts($search, $id, string $status = "publish", $paginate = true)
    {
        return $this->repository->searchContent($search, $id, $status, $paginate);
    }
    public function recommended()
    {
        return $this->repository->recommended();
    }
    public function myThisMonthPosts()
    {
        return $this->repository->myThisMonthPosts();
    }
    public function lastMonthPosts()
    {
        return $this->repository->lastMonthPosts();
    }
    public function myLastMonthPosts()
    {
        return $this->repository->myLastMonthPosts();
    }
    public function myPosts()
    {
        return $this->repository->myPosts();
    }
    public function postsLastSevenDays()
    {
        return $this->repository->postsLastSevenDays();
    }
    public function mostPopularLastMonth()
    {
        return $this->mostPopular($this->lastMonthPosts());
    }
    public function trendingThisWeek(array $exlude = null)
    {
        //Post obj if found, 0 if not
        return $this->mostPopular($this->postsLastSevenDays(), $exlude);
    }
    public function totalPosts()
    {
        return $this->repository->totalPosts();
    }
    /**
     * Find post by slug
     *
     * @param string $slug
     * @return Post
     */
    public function bySlug(string $slug): Post
    {
        $post = $this->repository->bySlug($slug);
        if ($post) {
            return $post;
        }
        abort(404);
    }
    public function getAccessCodeForStatus(string $status)
    {
        $list_of_status = config('blublog.post_status');
        $list_of_status_access = config('blublog.post_status_access');

        if (!in_array($status, $list_of_status)) {
            throw new InvalidPostStatusException;
        }

        for ($i = 0; $i < count(--$list_of_status); $i++) {
            if ($list_of_status[$i] == $status) {
                return $list_of_status_access[$i];
            }
        }
    }
    public function statusIsPrivate(string $status)
    {
        if ($this->getAccessCodeForStatus($status) === 2) {
            return true;
        }
        return false;
    }
    public function canView($post)
    {
        if ($this->getAccessCodeForStatus($post->status) == 2 and auth()->user()->id != $post->user_id) {
            return false;
        }
        if (
            $this->getAccessCodeForStatus($post->status) == 3 and
            !auth()->user()->blublogRoles->first()->havePermission('edit-' . $post->status)
        ) {
            return false;
        }
        if ($this->getAccessCodeForStatus($post->status) === 1 and !blublog_is_mod()) {
            return false;
        }
        return true;
    }
    public function withStatus(string $status)
    {
        $user = auth()->user();
        if ($this->getAccessCodeForStatus($status) == 3 and $user->blublogRoles->first()->havePermission('edit-' . $status)) {
            return $this->repository->withStatus($status);
        } elseif ($this->getAccessCodeForStatus($status) == 2) {
            return $this->repository->fromUser($user->id, $status);
        } elseif ($this->getAccessCodeForStatus($status) == 1 and blublog_is_mod()) {
            return $this->repository->withStatus($status);
        } elseif ($this->getAccessCodeForStatus($status) == 0) {
            return $this->repository->withStatus($status);
        }
    }
    public function similarPosts($post)
    {
        $needed_similar_posts = config('blublog.similar-posts');

        // Check if post do not have tags
        $category_posts = $post->categories[0]->getPosts()->latest()->limit($needed_similar_posts)->get()->shuffle();
        if (!$post->tags) {
            return $this->removePostFromCollection($category_posts, $post);
        }

        // Make collection
        $similarpost = collect(new Post);

        // Add all posts from all tags in the collection
        foreach ($post->tags as $tag) {
            foreach ($tag->posts as $post) {
                $similarpost->push($post);
            }
        }
        // Add some post from the same category in the collection
        foreach ($category_posts as $post) {
            $similarpost->push($post);
        }

        // Filter the collection. No duplicates.
        // TODO: Remove main post from collection.
        $similarpost = $similarpost->unique('id')->shuffle();

        return $this->removePostFromCollection($similarpost, $post)->take($needed_similar_posts);
    }
    public function cleanInput($content)
    {
        if (auth()->user()->blublogRoles->first()->havePermission('no-html')) {
            $content = preg_replace('@<(script|style)[^>]*?>.*?@si', '', $content);
            $content = strip_tags($content);

            return nl2br(trim($content));
        } elseif (auth()->user()->blublogRoles->first()->havePermission('restrict-html')) {
            if (class_exists('Mews\Purifier\Facades\Purifier')) {
                return \Mews\Purifier\Facades\Purifier::clean($content);
            }
            return e($content);
        } else {
            return $content;
        }
    }
    public function createExcerpt($string)
    {
        $string = preg_replace('/(.*?[?!.](?=\s|$)).*/', '\\1', strip_tags($string));
        return $string;
    }
    public function setPostStatus($post, $status)
    {
        if (auth()->user()->blublogRoles->first()->havePermission('wait-for-approve')) {
            $post->status = 'waits';
            return true;
        }

        if (!in_array($status, blublog_list_status())) {
            throw new InvalidPostStatusException();
            return false;
        }
        if (auth()->user()->id != $post->user_id and !blublog_is_mod()) {
            throw new PostStatusPermission();
            return false;
        }
        $post->status = $status;
        return true;
    }
    public function create($request)
    {

        $post = new Post;
        $post->user_id = auth()->user()->id;

        $post = $this->processPost($post, $request);
        try {
            if ($request->status) {
                $this->setPostStatus($post, blublog_list_status()[$request->status]);
            }
        } catch (Throwable $e) {
            report($e);
        }

        $this->repository->create($post, $request);

        Log::add(json_encode($post->toArray()), 'info', 'Post created.');
    }
    public function update($request, $id)
    {
        $post = $this->findById($id);

        $post = $this->processPost($post, $request);
        try {
            $this->setPostStatus($post, blublog_list_status()[$request->status]);
        } catch (Throwable $e) {
            report($e);
        }
        $this->repository->update($post, $request);

        Log::add(json_encode($post->toArray()), 'info', 'Post edited.');
    }
    public function processPost($post, $request)
    {
        $post->title = $request->title;
        $post->content = $this->cleanInput($request->content);
        $post->excerpt = $request->excerpt ? $request->excerpt : $this->createExcerpt($post->content);
        $post->seo_descr = $request->seo_descr ? $request->seo_descr : mb_strimwidth(strip_tags($request->content), 0, 60, "...");
        $post->seo_title = $request->seo_title ? $request->seo_title : mb_strimwidth($request->title, 0, 60, "...");
        $post->slug = $request->slug ? $request->slug : blublog_create_slug($request->title);

        $post->comments = $request->comments ? true : false;;
        $post->recommended = $request->recommended ? true :  false;
        $post->front = $request->front ? true :  false;
        if ($request->maintag_id) {
            $post->tag_id = $request->maintag_id;
        }
        if ($request->type) {
            $post->type = $request->type;
        }
        if (blublog_have_permission('change-post-author') and $request->author_id) {
            $post->user_id = $request->author_id;
        }
        if ($request->new_date) {
            try {
                $post->created_at = $this->convert_date($request->new_date);
            } catch (Exception $e) {
                Log::add($e->getMessage(), 'error', 'Error on date convertion.');
            }
        }
        return $post;
    }
    public function isValidStatus(string $status): bool
    {
        if (isset(blublog_list_status()[$status])) {
            return true;
        } else {
            return false;
        }
    }
    public function removePostFromCollection($collection, $post)
    {
        $collection = $collection->filter(function ($value, $key) use ($post) {
            return $value->id != $post->id;
        });
        return $collection;
    }
    public function mostPopular($posts, $exlude = null)
    {
        $data = array(0, 0);
        foreach ($posts as $post) {
            if ($post->views > $data[0]) {
                if ($exlude and in_array($post->id, $exlude)) {
                    continue;
                }
                $data[0] = $post->views;
                $data[1] = $post;
                $post->username = $post->user->name;
            }
        }
        return $data[1];
    }
    public function registerView($post)
    {
        if (!Log::userSeenPost($post->id)) {
            Log::add($post->id, "visit");
            unset($post->fromThisTopic);
            unset($post->similar);
            $post->views++;
            $post->save();
        }
    }
    public function like($post)
    {
        if (!Log::postLiked($post->id)) {
            Log::add($post->id, "like", "Post liked.");
            unset($post->fromThisTopic);
            unset($post->similar);
            $post->likes++;
            $post->save();
        }
    }
    public function remove($post)
    {
        if (!Gate::allows('blublog_delete_posts', $post)) {
            Log::add(json_encode($post->toArray()), 'alert', 'User can not delete this post.');
            abort(403);
        }
        Log::add(json_encode($post->toArray()), 'info', 'Post removed.');

        $this->repository->delete($post);

        return true;
    }
    public function RemoveOnThisTopic($post_id)
    {
        $post = $this->findById($post_id);
        $post->tag_id = null;
        $post->save();
        return true;
    }
    public function onThisTopic($post)
    {
        if ($post->tag_id) {
            $tag = $this->tagRepository->find($post->tag_id);
            if (!$tag) {
                $this->RemoveOnThisTopic($post->id);
                return array();
            }
            $posts = $tag->posts()->where('status', '=', 'publish')->latest()->limit(4)->get();
            $post_id = $post->id;
            $posts = $posts->filter(function ($value) use ($post_id) {
                return $value->id != $post_id;
            });
            return $posts;
        }
        return false;
    }
    public function convert_date($unformated_date, $del = "/", $carbon = false)
    {
        $dateString = explode($del, $unformated_date);

        $date = Carbon::createFromDate($dateString[2], $dateString[1], $dateString[0]);
        if (isset($dateString[3])) {
            $date->hour = $dateString[3];
        }
        if (isset($dateString[4])) {
            $date->minute = $dateString[4];
        }
        if (isset($dateString[5])) {
            $date->second = $dateString[5];
        }

        if ($carbon) {
            return $date;
        } else {
            return $date->toDateTimeString();
        }
    }
}
