<?php

namespace Blublog\Blublog\Resources;

use Blublog\Blublog\Models\Post as PostModel;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class Post extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'slug' => $this->slug,
            'url' => url(config('blublog.blog_prefix')) . "/posts/" . $this->slug,
            'title' => $this->title,
            'content' => $this->content,
            'excerpt' => $this->excerpt,
            'created_at' => $this->created_at->format('d-m-Y H:i'),
            'img' => $this->img,
            'rating' =>  PostModel::get_rating_avg($this),
            'votes' => $this->ratings->count(),
            'likes' => $this->likes,
            'dislikes' => $this->dislikes,
            'img_url' =>  PostModel::get_img_url($this->img),
            'thumb_url' =>  PostModel::get_thumb_url($this->img),
            'author_url' => url(config('blublog.blog_prefix')) . "/author/" . $this->user->name,
            'author_name' => $this->user->name,
            'date' => Carbon::parse($this->created_at)->format(blublog_setting('date_format')),
            'views' => $this->views->count(),
            'comments' => $this->allcomments->count(),
        ];
    }
    public function with($request)
    {
        $categories =  Category::collection($this->categories);
        $tags =  Tag::collection($this->tags);
        if ($this->on_this_topic) {
            $on_this_topic = ShortPost::collection($this->on_this_topic);
        } else {
            $on_this_topic = null;
        }
        return [
            'on_this_topic' => $on_this_topic,
            'categories' => $categories,
            'tags' => $tags,
        ];
    }
}
