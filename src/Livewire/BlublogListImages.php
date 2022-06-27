<?php

namespace Blublog\Blublog\Livewire;

use Blublog\Blublog\Models\File;
use Livewire\Component;
use Livewire\WithPagination;

class BlublogListImages extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['imageUploaded' => '$refresh', 'videoUploaded' => '$refresh'];


    public function render()
    {
        $images = File::whereNull('parent_id')->latest()->paginate(12);

        return view('blublog::livewire.images.blublog-list-images')->with('images', $images);
    }

    public function removeImg($id)
    {
        $image = File::findOrFail($id);
        $status = $image->deleteImage();
        if ($status) {
            $this->emit('alert', ['type' => 'info', 'message' => 'Image Uploaded']);
        } else {
            $this->emit('alert', ['type' => 'error', 'message' => 'Cannot remove image.']);
        }
        if ($status === 2) {
            $this->emit('alert', ['type' => 'warning', 'message' => 'Image removed. Post affected.']);
        }
    }
    public function imageSelected($id)
    {
        $this->emitUp('imageSelected', $id);
    }
}
