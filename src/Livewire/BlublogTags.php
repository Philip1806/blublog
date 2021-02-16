<?php

namespace Blublog\Blublog\Livewire;

use Livewire\Component;
use Blublog\Blublog\Models\Tag;
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

    public function render()
    {
        $tags = Tag::where('title', 'like', '%' . $this->search . '%')->latest()->paginate(5);
        return view('blublog::livewire.blublog-tags')->with('tags', $tags);
    }

    public function createTag()
    {
        if (!Gate::allows('blublog_create_tags')) {
            abort(403);
        }
        $this->validate();
        Tag::create([
            'title' => $this->tagTitle,
            'slug' => $this->tagSlug ? $this->tagSlug : blublog_create_slug($this->tagTitle),
            'img' => $this->tagImg,
        ]);
        session()->flash('success', 'Tag created.');
    }
    public function delete($id)
    {
        $tag = Tag::findOrFail($id);
        $tag->removeTag();
        session()->flash('success', 'Tag deleted.');
    }
}
