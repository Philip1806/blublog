<?php

namespace Blublog\Blublog\Livewire;

use Blublog\Blublog\Models\File;
use Illuminate\Support\Facades\Cache;
use Blublog\Blublog\Models\Tag;
use Blublog\Blublog\Services\CategoryService;
use Blublog\Blublog\Services\PostService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class BlublogPostsCreate extends Component
{
    use AuthorizesRequests;

    public $title;
    public $content;

    public $seoTitle;
    public $seoDescr;
    public $excerpt;

    public $author_id = null;
    public $maintag_id = null;

    public $slug;
    public $imageUrl;
    public $fileId;
    public $status = "publish";

    public $comments = true;
    public $frontPage = false;
    public $recommended = false;

    public $type = "post";

    public $categoriesIds = array();
    public $tagsIds = array();

    protected $listeners = ['TagCreated' => 'addTagToSelect2', 'imageUploaded' => 'setImage', 'videoUploaded' => 'setImage', 'imageSelected' => 'setImage',  'AuthorChanged', 'MainTagSelected' => 'selectMaintag'];


    protected $rules = [
        'title' => 'required|min:6|max:250',
        'content' => 'required',
        'status' => 'max:50',
        'categoriesIds' => 'required',

    ];
    public function mount()
    {
        $this->imageUrl  = url('\blublog-uploads\photos\no-image.jpg');
    }
    public function render(CategoryService $categoryService)
    {
        return view('blublog::livewire.posts.blublog-posts-create')->with('categories', $categoryService->toSelectArray())->with('tags', Tag::toSelectArray());
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
    public function submit(PostService $postService)
    {
        $this->authorize('blublog_create_posts');
        $this->validate();
        $myRequest = new \Illuminate\Http\Request();
        $myRequest->setMethod('POST');
        $myRequest->request->add(['title' => $this->title]);
        $myRequest->request->add(['content' => $this->content]);
        $myRequest->request->add(['file_id' => $this->fileId]);

        $myRequest->request->add(['seo_title' => $this->seoTitle]);
        $myRequest->request->add(['seo_descr' => $this->seoDescr]);
        $myRequest->request->add(['excerpt' => $this->excerpt]);

        $myRequest->request->add(['comments' => $this->comments]);
        $myRequest->request->add(['front' => $this->frontPage]);
        $myRequest->request->add(['recommended' => $this->recommended]);

        $myRequest->request->add(['categories' => $this->categoriesIds]);
        $myRequest->request->add(['status' => $this->status]);
        $myRequest->request->add(['tags' => $this->tagsIds]);
        $myRequest->request->add(['type' => $this->type]);
        $myRequest->request->add(['author_id' => $this->author_id]);
        $myRequest->request->add(['maintag_id' => $this->maintag_id]);

        $postService->create($myRequest);
        Cache::flush();
        session()->flash('success', 'Post created.');
        return redirect()->route("blublog.panel.posts.index");
    }

    public function setImage($image_id)
    {
        $image = File::findOrFail($image_id);
        $this->imageUrl = $image->thumbnailUrl();
        if ($image->is_video) {
            $this->type = "video";
        } else {
            $this->type = "post";
        }
        $this->fileId = $image->id;
        $this->emit('closeModal', "#selectImageModal");
        $this->emit('closeModal', "#uploadFileModal");

        $this->emit('alert', ['type' => 'info', 'message' => 'File connected to post.']);
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
