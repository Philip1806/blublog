<?php

namespace Blublog\Blublog\Livewire;

use Livewire\Component;
use Blublog\Blublog\Models\File;
use Blublog\Blublog\Services\CategoryService;
use Blublog\Blublog\Services\TagService;
use Illuminate\Support\Carbon;

class BlublogCreateEditPost extends Component
{
    /**
     * URL of the image that will be used for the post
     */
    public $imageUrl;
    public $imageFilename;
    public $post;
    public $date;
    public $search;
    public $is_edit;
    /**
     * Id of the tag that's selected. Used for "On this topic"
     */
    public $maintag_id;
    /**
     * Name of the new that that will be created.
     *
     * @var string
     */
    public $tagName;
    /**
     * The title of current post
     *
     * @var string
     */
    public $postTitle;
    /**
     * Array of tags that are similar to the post title.
     *
     * @var array
     */
    public $sim_tags = array();

    protected $listeners = ['imageUploaded' => 'changePos tImage', 'imageSelecred' => 'setImage'];


    public function dehydrate()
    {
        $this->emit('tagsUpdated');
    }

    public function mount()
    {

        $this->date  = Carbon::now()->format('d/m/Y');
        $this->imageUrl  = url('\blublog-uploads\photos\no-image.jpg');
        $this->imageFilename = "photos/no-image.jpg";
        if ($this->post) {
            $this->date  = Carbon::parse($this->post->created_at)->format('d/m/Y');
            $this->maintag_id  = $this->post->tag_id;
            $this->imageUrl  = $this->post->thumbnailUrl();
            $this->imageFilename = $this->post->img;
            $this->postTitle  = $this->post->title;
            $this->is_edit = true;
        }
    }
    public function setImage($id)
    {
        $file = File::findOrFail($id);
        $this->changePostImage($file);
        $this->emit('closeModal');
    }
    public function changePostImage(File $file)
    {
        $this->imageUrl = $file->url();
        $this->imageFilename = $file->filename;
    }
    public function render(CategoryService $categoryservice, TagService $tagService)
    {;
        $categories = $categoryservice->getAll();
        $categories2 = array();
        foreach ($categories as $category) {
            $categories2[$category->id] = $category->title;
        }
        $tags = $tagService->getAll();
        $tags2 = array();
        foreach ($tags as $tag) {
            $tags2[$tag->id] = $tag->title;
        }

        if ($this->search) {
            $list_tags = $tagService->search($this->search);
        } else {
            $list_tags = array();
        }

        if ($this->postTitle and !$this->is_edit) {
            $result = explode(" ", preg_replace("/[^a-zA-Z\p{Cyrillic}]+/u", " ", $this->postTitle));
            //FIND TAG
            $this->sim_tags = array();
            foreach ($result as $try) {
                $possible_tag = $tagService->search($try)[0];
                if ($possible_tag) {
                    array_push($this->sim_tags, $possible_tag->toArray());
                }
            }
        }
        return view('blublog::livewire.posts.blublog-create-edit-post')->with('list_tags', $list_tags)->with('categories', $categories2)->with('tags', $tags2);
    }
    public function createTag(TagService $tagService)
    {
        if ($this->tagName) {
            $newtag = $tagService->create([
                'title' => $this->tagName,
                'slug' => blublog_create_slug($this->tagName),
            ]);
            $this->emit('simTagClicked', $newtag->id);
        }
        $this->tagName = '';
    }
    public function setMaintag($tag_id)
    {
        $this->search = '';
        $this->maintag_id = $tag_id;
    }
    public function unsetTag()
    {
        $this->maintag_id = '';
    }
    public function selectTag($id)
    {
        $this->emit('simTagClicked', $id);
    }
}
