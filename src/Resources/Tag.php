<?php

namespace Blublog\Blublog\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Tag extends JsonResource
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
            'number_of_posts' => $this->number_of_posts,
        ];
    }
    public function with($request)
    {
        return [
            'posts' => $this->get_posts,
        ];
    }
}
