<?php

namespace Blublog\Blublog\Livewire;

use Livewire\Component;
use Blublog\Blublog\Models\Tag;
use Blublog\Blublog\Services\TagService;
use Illuminate\Support\Facades\Gate;
use Livewire\WithPagination;


class BlublogTags extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'tagTitle' => 'required|max:200',
        'tagSlug' => 'max:200',
        'tagImg' => 'max:200',
    ];

    public $search;

    public $tagTitle;
    public $tagSlug;
    public $tagImg;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render(TagService $tagService)
    {
        $tags = $tagService->search($this->search);
        return view('blublog::livewire.blublog-tags')->with('tags', $tags);
    }

    public function createTag(TagService $tagService)
    {
        if (!Gate::allows('blublog_create_tags')) {
            abort(403);
        }
        $this->validate();
        $tagService->create([
            'title' => $this->tagTitle,
            'slug' => $this->tagSlug ? $this->tagSlug : blublog_create_slug($this->tagTitle),
            'img' => $this->tagImg,
        ]);
        session()->flash('success', 'Tag created.');
    }
    public function delete($id, TagService $tagService)
    {
        $tag = $tagService->findById($id);
        $tagService->delete($tag);
        session()->flash('success', 'Tag deleted.');
    }
}
