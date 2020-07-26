<?php

namespace Blublog\Blublog\Resources;
use Blublog\Blublog\Resources\Category;
use Blublog\Blublog\Models\Post as PostModel;
use Illuminate\Http\Resources\Json\JsonResource;

class Category extends JsonResource
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
            'title' => $this->title,
            'descr' => $this->descr,
            'colorcode' => $this->colorcode,
            'img' => $this->img,
        ];
    }
    public function with($request)
    {
        return [
            'posts' => $this->get_posts,
        ];
    }
}
