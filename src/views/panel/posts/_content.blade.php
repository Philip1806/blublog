<div class="card border-danger">
    <div class="card-header  text-white bg-danger">{{__('blublog.content')}}</div>
    <div class="card-body text-primary">
        {{ Form::textarea('content', null, ['class' => 'form-control', 'rows' => '15']) }}
    </div>
</div>
