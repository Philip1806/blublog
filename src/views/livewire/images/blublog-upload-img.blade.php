<div>
    @can('blublog_upload_files')
        <form wire:submit.prevent="save" action="#" methid="POST" enctype="multipart/form-data">

            <div class="custom-file mb-3">
                <input type="file" wire:model="photo" class="custom-file-input" required>
                <label class="custom-file-label">Choose file...</label>
            </div>

            <div wire:loading wire:target="photo">
                <div class="alert alert-info" role="alert">
                    Image is uploading...
                </div>
            </div>
            @if ($photo)
                <p>Photo Preview:</p>
                <img src="{{ $photo->temporaryUrl() }}" class="img-fluid" alt="Photo Preview">
            @endif

            @error('photo')
                <div class="alert alert-danger" role="alert">
                    {{ $message }}
                </div>
            @enderror

            <button type="submit" class="btn btn-primary btn-block my-3">Save Photo</button>
        </form>
    @else
        <div class="alert alert-info" role="alert">
            You do not have permission for uploading images.
        </div>
    @endcan
</div>
