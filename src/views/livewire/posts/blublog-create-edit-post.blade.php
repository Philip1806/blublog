<div>
    <div class="row">
        <div class="col-lg-9">
            <div wire:ignore>
                {{ Form::text('title', null, ['class' => 'form-control mb-2']) }}

                {{ Form::textarea('content', null, ['class' => 'form-control', 'id' => 'editor', 'rows' => '15']) }}

                <div class="card border-danger my-2">
                    <div class="card-header text-white bg-danger"><span class="oi oi-spreadsheet"></span> Categories
                    </div>
                    <div class="card-body text-primary">
                        {{ Form::select('categories[]', $categories, null, ['class' => 'form-control select2-multi', 'multiple' => 'multiple']) }}
                    </div>
                </div>
            </div>



            <div class="card border">
                <div class="card-header"><span class="oi oi-tags"></span> Tags</div>
                <div class="card-body text-primary">
                    <div class="input-group">
                        <div class="custom-file">

                            {{ Form::select('tags[]', $tags, null, ['class' => 'form-control select2-multi', 'multiple' => 'multiple']) }}

                        </div>
                        @can('blublog_create_tags')
                            <div class="input-group-append">
                                <input wire:model="tagName" type="text" class="form-control  rounded-0"
                                    placeholder="New tag name...">
                            </div>
                            <div class="input-group-append">
                                <a wire:click="createTag()" class="btn btn-outline-secondary  rounded-0">Create Tag</a>
                            </div>
                        @endcan
                    </div>
                    </form>
                </div>
            </div>



        </div>
        <div class="col-lg-3">
            <input wire:model="imageFilename" name="img" type="hidden" class="form-control">

            {{ Form::select('status', blublog_list_status(), null, ['class' => 'form-control mb-2']) }}

            <button class="btn btn-success btn-block my-2" role="button"><span class="oi oi-circle-check"></span>
                @if ($post)
                    Edit
                @else
                    Create
                @endif
            </button>

            <div class="card border border-dark">
                <div class="card-body">
                    <h4>Post Image</h4>
                    <img src="{{ $imageUrl }}" class="img-fluid">
                    <div class="btn-group my-2 btn-block" role="group">
                        <button type="button" class="btn btn-dark" data-toggle="modal"
                            data-target="#staticBackdrop"><span class="oi oi-grid-four-up"></span> Choose</button>
                        @can('blublog_upload_files')
                            <button type="button" class="btn btn-primary" data-toggle="collapse" href="#uploadImage"
                                role="button" aria-expanded="false"><span class="oi oi-data-transfer-upload"></span>
                                Upload</button>
                        @endcan
                    </div>

                    <div class="modal fade" id="staticBackdrop" data-keyboard="false" tabindex="-1"
                        aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Choose image for your post</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    @livewire('blublog-list-images')
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="collapse" id="uploadImage">
                        @livewire('blublog-upload-img')
                    </div>

                </div>
            </div>
            <div wire:ignore>
                <button type="button" class="btn btn-secondary btn-block my-2" data-toggle="collapse"
                    href="#postSettings" role="button" aria-expanded="false"><span class="oi oi-cog"></span>
                    Settings</button>

                <div class="collapse" id="postSettings">
                    URL
                    {{ Form::text('slug', null, ['class' => 'form-control mb-2']) }}
                    {{ Form::checkbox('comments', null) }} Enable comments<br>
                    {{ Form::checkbox('front', null) }} Front Page Post<br>
                    {{ Form::checkbox('recommended', null) }} Recommended<br>
                    Seo title
                    {{ Form::text('seo_title', null, ['class' => 'form-control mb-2']) }}
                    Seo description
                    {{ Form::text('seo_descr', null, ['class' => 'form-control mb-2']) }}
                    Excerpt
                    {{ Form::text('excerpt', null, ['class' => 'form-control mb-2']) }}
                </div>
            </div>
        </div>

    </div>
</div>
