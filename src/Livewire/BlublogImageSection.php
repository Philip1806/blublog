<?php

namespace Blublog\Blublog\Livewire;

use Blublog\Blublog\Models\File;
use Livewire\Component;
use Livewire\WithPagination;

class BlublogImageSection extends Component
{
    use WithPagination;

    public $unused = false;
    public $videos = false;

    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['imageUploaded' => '$refresh'];

    public function render()
    {
        $images = File::whereNull('parent_id')->latest()->paginate(9);
        if ($this->unused && $this->videos) {
            $this->reset();
        }
        if ($this->unused) {
            $images = File::whereNull('parent_id')->get()->reject(function ($element) {
                return $element->usedInPost() != false;
            });
        }
        if ($this->videos) {
            $images = File::whereNull('parent_id')->where('is_video', '=', true)->paginate(9);
        }

        return view('blublog::livewire.images.blublog-img-section')->with('images', $images);
    }
    public function delete($id)
    {
        $image = File::findOrFail($id);
        $status = $image->deleteImage();
        if ($status) {
            $this->emit('alert', ['type' => 'info', 'message' => 'Image removed']);
        } else {
            $this->emit('alert', ['type' => 'error', 'message' => 'Cannot remove image.']);
        }
        if ($status === 2) {
            $this->emit('alert', ['type' => 'warning', 'message' => 'Image removed. Post affected.']);
        }
    }
    public function toggleUnused()
    {
        ($this->unused) ? $this->unused = false : $this->unused = true;
    }
    public function toggleVideos()
    {
        ($this->videos) ? $this->videos = false : $this->videos = true;
    }
}
