<?php

namespace Blublog\Blublog\Resources;

use Blublog\Blublog\Models\Post as PostModel;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ShortPost extends JsonResource
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
            'descr' => $this->seo_descr,
            'created_at' => $this->created_at,
            'img' => $this->img,
            'rating' =>  PostModel::get_rating_avg($this),
            'votes' => $this->ratings->count(),
            'img_url' =>  PostModel::get_img_url($this->img),
            'author_url' => url(config('blublog.blog_prefix')) . "/author/" . $this->user->name,
            'author_name' => $this->user->name,
            'date' => Carbon::parse($this->created_at)->format(blublog_setting('date_format')),
            'views' => $this->views->count(),
            'comments' => $this->allcomments->count(),
        ];
    }
}
