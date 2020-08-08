<div class="card border-danger">
    <div class="card-header  text-white bg-danger">{{__('blublog.content')}}</div>
    <button type="button" class="p-0 m-0 btn btn-sm btn-light" data-toggle="modal" data-target=".bd-img-upload-modal-lg">
        {{__('blublog.upload_img')}}
    </button>
    {{ Form::textarea('content', null, ['class' => 'form-control', 'id' => 'editor', 'rows' => '15']) }}
</div>
