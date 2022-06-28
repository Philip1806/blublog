<?php

namespace Blublog\Blublog\Livewire;

use Blublog\Blublog\Models\File;
use Blublog\Blublog\Models\Post;
use Blublog\Blublog\Models\Tag;
use Blublog\Blublog\Services\CategoryService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Blublog\Blublog\Services\PostService;
use Livewire\Component;

use function PHPSTORM_META\type;

class BlublogPostsEdit extends Component
{
    use AuthorizesRequests;

    public Post $post;

    public $slug;
    public $imageUrl;
    public $fileId;
    public $status;
    public $author_id = null;
    public $maintag_id = null;

    public $comments = false;
    public $frontPage = false;
    public $recommended = false;

    public $type;

    public $categoriesIds = array();
    public $tagsIds = array();

    protected $listeners = ['TagCreated' => 'addTagToSelect2', 'imageUploaded' => 'setImage', 'videoUploaded' => 'setImage', 'imageSelected' => 'setImage', 'AuthorChanged', 'MainTagSelected' => 'selectMaintag'];

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
        $this->tags =  Tag::toSelectArray();
        $this->imageUrl  = $this->post->thumbnailUrl();
        $this->fileId  = $this->post->file_id;
        $this->status  = $this->post->status;
        $this->type  = $this->post->type;
        $this->maintag_id  = $this->post->tag_id;

        if ($this->post->comments) {
            $this->comments = true;
        }
        if ($this->post->front) {
            $this->frontPage = true;
        }
        if ($this->post->recommended) {
            $this->recommended = true;
        }
        foreach ($this->post->tags as $tag) {
            array_push($this->tagsIds, $tag->id);
        }
        foreach ($this->post->categories as $category) {
            array_push($this->categoriesIds, $category->id);
        }
    }
    public function render(CategoryService $categoryService)
    {
        return view('blublog::livewire.posts.blublog-posts-edit')->with('categories', $categoryService->toSelectArray())->with('tags', Tag::toSelectArray());
    }
    public function AuthorChanged($id)
    {
        $this->author_id = $id;
        $this->emit('alert', ['type' => 'info', 'message' => 'Author Changed.']);
    }
    public function selectMaintag($id)
    {
        $this->maintag_id = $id;
        if ($id) {
            $this->emit('alert', ['type' => 'info', 'message' => 'On This Topic is set.']);
        } else {
            $this->emit('alert', ['type' => 'info', 'message' => 'On This Topic is unset.']);
        }
    }
    public function setImage($image_id)
    {
        $image = File::findOrFail($image_id);
        if ($image->is_video) {
            $this->type = "video";
        } else {
            $this->type = "post";
        }
        $this->imageUrl = $image->thumbnailUrl();
        $this->fileId = $image->id;
        $this->emit('closeModal', "#selectImageModal");
        $this->emit('closeModal', "#uploadFileModal");

        $this->emit('alert', ['type' => 'info', 'message' => 'File connected to post.']);
    }
    public function submit(PostService $postService)
    {
        $this->authorize('blublog_edit_post', $this->post);
        $this->validate();
        $myRequest = new \Illuminate\Http\Request();
        $myRequest->setMethod('POST');
        $myRequest->request->add(['title' => $this->post->title]);
        $myRequest->request->add(['content' => $this->post->content]);
        $myRequest->request->add(['file_id' => $this->fileId]);

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
        $myRequest->request->add(['type' => $this->type]);
        $myRequest->request->add(['author_id' => $this->author_id]);
        $myRequest->request->add(['maintag_id' => $this->maintag_id]);

        $postService->update($myRequest, $this->post->id);
        Cache::flush();
        session()->flash('success', 'Post "' . $this->post->title . '" was edited.');
        return redirect()->route("blublog.panel.posts.index");
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
    public function addTagToSelect2($tag)
    {
        $this->addTag($tag["id"]);
        $this->emit("AddNewSelect2Tag", $tag);
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
