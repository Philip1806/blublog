<?php

namespace Blublog\Blublog\Livewire;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Blublog\Blublog\Services\TagService;
use Livewire\Component;

class BlublogCreateTag extends Component
{
    use AuthorizesRequests;

    public $tagName;
    protected $rules = [
        'tagName' => 'required|min:2|max:200',
    ];
    public function render()
    {
        return view('blublog::livewire.posts.blublog-create-tag');
    }
    public function submit(TagService $tagService)
    {
        $this->authorize('blublog_create_tags');
        $this->validate();
        $tag = $tagService->createFromTitle($this->tagName);
        $this->emit('TagCreated', $tag->toArray());
        $this->emit('alert', ['type' => 'info', 'message' => 'Tag ' . $tag->title . " is added."]);
        $this->reset();
    }
}
