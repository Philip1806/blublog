<?php

namespace Blublog\Blublog\Resources;
use Blublog\Blublog\Resources\Category;
use Blublog\Blublog\Models\Post as PostModel;
use Illuminate\Http\Resources\Json\JsonResource;

class Comment extends JsonResource
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
            'name' => $this->name,
            'body' => $this->body,
            'pinned' => $this->pinned,
            'author' => $this->author,
            'id' => $this->public_id,
            'replies' => Comment::collection($this->replies),
        ];
    }
}
