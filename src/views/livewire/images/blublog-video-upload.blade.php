<div>
    <form wire:submit.prevent="submit">
        <label class="form-label">Видео</label>
        <div class="custom-file my-2">
            <input type="file" wire:model="video" class="custom-file-input">
            <label class="custom-file-label">Choose video...</label>
        </div>
        @error('video')
            <span class="error">{{ $message }}</span>
        @enderror
        <div wire:loading wire:target="video">
            <div class="alert alert-info" role="alert">
                Video is uploading...
                <div class="progress">
                    <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0"
                        aria-valuemax="100"></div>
                </div>
            </div>
        </div>
        @if ($video)
            <div class="alert alert-success" role="alert">
                Video Uploaded.
            </div>
        @endif
        <hr>
        <label class="form-label">Постер</label>
        <div class="custom-file my-2">
            <input type="file" wire:model="photo" class="custom-file-input">
            <label class="custom-file-label">Choose image...</label>
        </div>
        @if ($photo)
            <p>Selected image:</p>
            <img src="{{ $photo->temporaryUrl() }}" class="img-fluid" alt="Photo Preview">
            <button type="submit" class="btn btn-info btn-block mt-2">Save</button>
        @endif
        @error('photo')
            <span class="error">{{ $message }}</span>
        @enderror

    </form>
    <script>
        window.addEventListener('livewire-upload-progress', event => {
            $('.progress-bar').css('width', event.detail.progress + '%').attr('aria-valuenow', event.detail
                .progress);
        });
    </script>
</div>
