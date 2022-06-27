<?php

namespace Blublog\Blublog\Livewire;

use Blublog\Blublog\Models\File;
use Livewire\Component;
use Livewire\WithFileUploads;

class BlublogUploadFile extends Component
{
    use WithFileUploads;
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
        $this->validate();
        $this->emit('imageUploaded', File::createSizes($this->photo->store(File::getImageDir(), 'blublog')));
        $this->reset();
        $this->emit('alert', ['type' => 'info', 'message' => 'Image uploaded and saved to server.']);
    }
}
