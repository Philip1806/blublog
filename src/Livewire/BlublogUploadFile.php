<?php

namespace Blublog\Blublog\Livewire;

use Blublog\Blublog\Models\File;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithFileUploads;

class BlublogUploadFile extends Component
{
    use WithFileUploads;
    use AuthorizesRequests;
    public $photo;

    protected $rules = [
        'photo' => 'image|max:4024',
    ];

    public function render()
    {

        return view('blublog::livewire.images.blublog-upload-img');
    }

    public function save()
    {
        $this->authorize('blublog_upload_files');
        $this->validate();
        $this->emit('imageUploaded', File::createSizes($this->photo->store(File::getImageDir(), config('blublog.files_disk', 'blublog'))));
        $this->reset();
        $this->emit('alert', ['type' => 'info', 'message' => 'Image uploaded and saved to server.']);
    }
}
