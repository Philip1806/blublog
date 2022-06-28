<div>
    <div class="input-group">
        <div class="custom-file">
            <input class="form-control @error('tagName') is-invalid @enderror" wire:model='tagName' type="text"
                placeholder="New tag name...">

        </div>
        <div class="input-group-append">
            <a class="btn btn-dark btn-block" wire:loading.attr="disabled" wire:click='submit()'>
                Add
            </a>
        </div>
    </div>
</div>
