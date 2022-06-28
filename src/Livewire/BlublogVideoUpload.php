<?php

namespace Blublog\Blublog\Livewire;

use Blublog\Blublog\Models\File;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\WithFileUploads;

class BlublogVideoUpload extends Component
{
    use WithFileUploads;
    use AuthorizesRequests;
    public $photo;
    public $video;
    protected $rules = [
        'photo' => 'image|max:4024',
        'video' => 'mimetypes:video/mp4',

    ];
    public function render()
    {
        return view('blublog::livewire.images.blublog-video-upload');
    }
    public function submit()
    {
        $this->authorize('blublog_upload_files');
        $this->validate();
        $videoPath = $this->video->store(File::getVideoDir(), config('blublog.files_disk', 'blublog'));
        $imagePath = $this->photo->store(File::getImageDir(), config('blublog.video_disk', 'blublog'));
        $this->emit('videoUploaded', File::saveVideo($videoPath, $imagePath));
        $this->reset();
        $this->emit('alert', ['type' => 'info', 'message' => 'Video Uploaded and saved.']);
        $this->emit('closeModal', "#uploadVideoFileModal");
    }
}
