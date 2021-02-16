<?php

namespace Blublog\Blublog\Livewire;

use Blublog\Blublog\Models\File;
use Livewire\Component;
use Livewire\WithPagination;

class BlublogListImages extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['imageUploaded' => '$refresh'];

    public function render()
    {
        $images = File::whereNull('parent_id')->latest()->paginate(12);

        return view('blublog::livewire.images.blublog-list-images')->with('images', $images);
    }
    public function selected($id)
    {
        $this->emit('imageSelecred', $id);
    }
}
