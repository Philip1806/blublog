<?php

namespace Blublog\Blublog\Livewire;

use Livewire\Component;
use Blublog\Blublog\Models\Post;
use Blublog\Blublog\Models\Category;
use Blublog\Blublog\Models\File;
use Blublog\Blublog\Models\Tag;

class BlublogCreateEditPost extends Component
{
    public $imageFilename;
    public $imageUrl;
    public $post;
    public $tagName;


    public function dehydrate()
    {
        $this->emit('tagsUpdated');
    }

    public function mount()
    {
        if ($this->post) {
            $this->imageFilename  = $this->post->img;
            $this->imageUrl  = $this->post->thumbnailUrl();
        }
    }

    protected $listeners = ['imageUploaded', 'imageSelecred' => 'setImage'];
    public function imageUploaded(File $file)
    {
        $this->imageFilename = $file->filename;
        $this->imageUrl = $file->url();
    }
    public function setImage($id)
    {
        $file = File::findOrFail($id);
        $this->imageFilename = $file->filename;
        $this->imageUrl = $file->url();
        $this->emit('closeModal');
    }
    public function render()
    {
        $categories = Category::all();

        $categories2 = array();
        foreach ($categories as $category) {
            $categories2[$category->id] = $category->title;
        }

        $tags = Tag::all();
        $tags2 = array();
        foreach ($tags as $tag) {
            $tags2[$tag->id] = $tag->title;
        }


        return view('blublog::livewire.posts.blublog-create-edit-post')->with('categories', $categories2)->with('tags', $tags2);
    }
    public function createTag()
    {
        if ($this->tagName) {
            Tag::createTag($this->tagName);
        }
        $this->tagName = '';
    }
}
