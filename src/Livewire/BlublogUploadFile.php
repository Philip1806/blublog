<?php

namespace Blublog\Blublog\Livewire;

use Blublog\Blublog\Models\File;
use Livewire\Component;
use Livewire\WithFileUploads;

class BlublogUploadFile extends Component
{
    use WithFileUploads;
    public $photo;


    public function render()
    {

        return view('blublog::livewire.images.blublog-upload-img');
    }

    public function save()
    {
        $this->validate([
            'photo' => 'image|max:2048',
        ]);
        $this->emit('imageUploaded', File::createSizes($this->photo->store(File::getImageDir(), 'blublog')));
        $this->photo = null;
    }
}
