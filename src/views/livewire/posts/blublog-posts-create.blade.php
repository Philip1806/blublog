<div>
    <form wire:submit.prevent="submit" novalidate>

        <div class="row">
            <div class="col-md-9">
                <input type="text" wire:model.lazy="title" placeholder="Enter post title..."
                    class="form-control mb-2 @error('title') is-invalid @enderror">

                <div wire:ignore>
                    <textarea wire:model="content" id="content" class="form-control" rows="7"></textarea>
                </div>

                <div class="card border @error('categoriesIds') bg-danger text-white @enderror my-2">
                    <div class="card-header"><span class="oi oi-tags"></span> Categories</div>
                    <div class="card-body text-primary">
                        <div class="input-group" wire:ignore>
                            {{ Form::select('categories[]', $categories, null, ['class' => 'form-control select2-multi', 'multiple' => 'multiple', 'id' => 'multisel']) }}
                        </div>
                    </div>
                </div>
                <div class="card border my-2">
                    <div class="card-header"><span class="oi oi-tags"></span> Tags</div>
                    <div class="card-body text-primary">
                        <div class="input-group">
                            <div class="custom-file">
                                <div class="input-group" wire:ignore>
                                    {{ Form::select('tags[]', $tags, null, ['class' => 'form-control select2-multitag', 'multiple' => 'multiple']) }}
                                </div>
                            </div>
                            <div class="input-group-append">
                                @livewire('blublog-create-tag')
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <select class="form-control mb-2" name="status" wire:model="status">
                    @foreach (blublog_list_status() as $key => $value)
                        <option value="{{ $key }}"> {{ $value }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-success btn-block my-2" role="button">
                    <span class="oi oi-circle-check"></span>
                    Create
                </button>
                @if ($imageUrl)
                    <img src="{{ $imageUrl }}" class="img-fluid w-100 my-2">
                @endif
                <div class="btn-group btn-block" role="group" wire:ignore>
                    <button type="button" class="btn btn-dark" data-toggle="modal"
                        data-target="#selectImageModal">Choose</button>
                    <button type="button" class="btn btn-primary" data-toggle="collapse"
                        data-target="#uploadOptionsCollapse">Upload a...</button>
                </div>
                <div class="collapse p-2" id="uploadOptionsCollapse">
                    <div class="btn-group btn-block" role="group" wire:ignore>
                        <button type="button" class="btn btn-secondary rounded-0" data-toggle="modal"
                            data-target="#uploadVideoFileModal">Video</button>
                        <button type="button" class="btn btn-primary rounded-0" data-toggle="modal"
                            data-target="#uploadFileModal">Picture</button>
                    </div>
                </div>

                <div class="collapse" id="uploadImage">
                    @livewire('blublog-upload-img')
                </div>
                <div class="mt-3">
                    @if (blublog_have_permission('change-post-author'))
                        @livewire('blublog-author-change')
                    @endif
                    @livewire('blublog-select-maintag', ['selected' => $maintag_id])
                </div>
                <div class="custom-control custom-switch">
                    <input wire:click="toggleComments()" type="checkbox" class="custom-control-input"
                        @if ($comments) checked @endif>
                    <label wire:click="toggleComments()" class="custom-control-label" for="customSwitches">
                        Allow Comments</label>
                </div>
                <div class="custom-control custom-switch">
                    <input wire:click="toggleFrontPagePost()" type="checkbox" class="custom-control-input"
                        @if ($frontPage) checked @endif>
                    <label wire:click="toggleFrontPagePost()" class="custom-control-label" for="customSwitches">
                        Front Page Post
                    </label>
                </div>
                <div class="custom-control custom-switch">
                    <input wire:click="toggleRecommended()" type="checkbox" class="custom-control-input"
                        @if ($recommended) checked @endif>
                    <label wire:click="toggleRecommended()" class="custom-control-label" for="customSwitches">
                        Recommended Post
                    </label>
                </div>
                <a class="btn btn-primary btn-block my-2" data-toggle="collapse" href="#optionsCollapse" role="button"
                    aria-expanded="false" aria-controls="optionsCollapse">
                    More Options
                </a>

                <div class="collapse" id="optionsCollapse">
                    <label class="form-label">Seo title</label>
                    <input type="text" wire:model.lazy="seoTitle" class="form-control">

                    <label class="form-label">Seo description</label>
                    <input type="text" wire:model.lazy="seoDescr" class="form-control">

                    <label class="form-label">Excerpt</label>
                    <input type="text" wire:model.lazy="excerpt" class="form-control">
                </div>



            </div>
        </div>
    </form>

    <div wire:ignore>
        @include('blublog::livewire._modals')
    </div>

    <script>
        $('#content').on('summernote.change', function(we, contents, $editable) {
            @this.set('content', contents);
        });
        $('.select2-multi').on('select2:select', function(e) {
            @this.addCategory(e.params.data.id);
        });
        $('.select2-multi').on('select2:unselect', function(e) {
            @this.removeCategory(e.params.data.id);
        });
        $('.select2-multitag').on('select2:select', function(e) {
            @this.addTag(e.params.data.id);
        });
        $('.select2-multitag').on('select2:unselect', function(e) {
            @this.removeTag(e.params.data.id);
        });
    </script>
</div>
