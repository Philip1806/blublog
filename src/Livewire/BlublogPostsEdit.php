<?php

namespace Blublog\Blublog\Livewire;

use Blublog\Blublog\Models\File;
use Blublog\Blublog\Models\Post;
use Blublog\Blublog\Models\Tag;
use Blublog\Blublog\Services\CategoryService;
use Illuminate\Support\Facades\Cache;

use Blublog\Blublog\Services\PostService;
use Livewire\Component;

class BlublogPostsEdit extends Component
{

    public Post $post;

    public $slug;
    public $imageUrl;
    public $imageDir;
    public $status;


    public $comments = false;
    public $frontPage = false;
    public $recommended = false;

    public $categoriesIds = array();
    public $tagsIds = array();

    protected $listeners = ['imageUploaded' => 'setImage', 'videoUploaded' => 'setImage', 'imageSelected' => 'setImage'];

    protected $rules = [
        'post.title' => 'required|min:6|max:250',
        'post.content' => 'required',
        'status' => 'required',
        'post.seo_title' => 'max:250',
        'post.seo_descr' => 'max:250',
        'post.excerpt' => 'max:250',
        'post.slug' => 'max:80',

    ];

    public function mount()
    {
        $this->imageUrl  = $this->post->imageURL();
        $this->imageDir  = $this->post->img;
        $this->status  = $this->post->status;

        if ($this->post->comments) {
            $this->comments = true;
        }
        if ($this->post->front) {
            $this->frontPage = true;
        }
        if ($this->post->recommended) {
            $this->recommended = true;
        }

        foreach ($this->post->categories as $category) {
            array_push($this->categoriesIds, $category->id);
        }
    }
    public function render(CategoryService $categoryService)
    {
        return view('blublog::livewire.posts.blublog-posts-edit')->with('categories', $categoryService->toSelectArray())->with('tags', Tag::toSelectArray());
    }
    public function setImage($image_id)
    {
        $image = File::findOrFail($image_id);
        $this->imageUrl = $image->url();
        $this->imageDir = $image->filename;
        $this->emit('closeModal', "#selectImageModal");
        $this->emit('closeModal', "#uploadFileModal");

        $this->emit('alert', ['type' => 'info', 'message' => 'File connected to post.']);
    }
    public function submit(PostService $postService)
    {
        $this->validate();
        $myRequest = new \Illuminate\Http\Request();
        $myRequest->setMethod('POST');
        $myRequest->request->add(['title' => $this->post->title]);
        $myRequest->request->add(['content' => $this->post->content]);
        $myRequest->request->add(['img' => $this->imageDir]);

        $myRequest->request->add(['seo_title' => $this->post->seo_title]);
        $myRequest->request->add(['seo_descr' => $this->post->seo_descr]);
        $myRequest->request->add(['excerpt' => $this->post->excerpt]);

        $myRequest->request->add(['comments' => $this->comments]);
        $myRequest->request->add(['front' => $this->frontPage]);
        $myRequest->request->add(['recommended' => $this->recommended]);

        $myRequest->request->add(['categories' => $this->categoriesIds]);
        $myRequest->request->add(['tags' => $this->tagsIds]);
        $myRequest->request->add(['status' => $this->status]);
        $myRequest->request->add(['slug' => $this->post->slug]);


        $postService->update($myRequest, $this->post->id);
        Cache::flush();
    }


    public function toggleComments()
    {
        ($this->comments) ? $this->comments = false : $this->comments = true;
    }
    public function toggleFrontPagePost()
    {
        ($this->frontPage) ? $this->frontPage = false : $this->frontPage = true;
    }
    public function toggleRecommended()
    {
        ($this->recommended) ? $this->recommended = false : $this->recommended = true;
    }
    public function addCategory($id)
    {
        array_push($this->categoriesIds, $id);
    }
    public function removeCategory($id)
    {
        if (($key = array_search($id, $this->categoriesIds)) !== false) {
            unset($this->categoriesIds[$key]);
        }
    }
    public function addTag($id)
    {
        array_push($this->tagsIds, $id);
    }
    public function removeTag($id)
    {
        if (($key = array_search($id, $this->tagsIds)) !== false) {
            unset($this->tagsIds[$key]);
        }
    }
}
