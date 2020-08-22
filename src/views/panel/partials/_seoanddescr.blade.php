<div class="card" style="margin-top:10px;">
    <div class="card-header text-white bg-primary bg">{{__('blublog.seoinfo')}} - {{__('blublog.autogen')}}</div>
    <div class="card-body">
        <h4>{{__('blublog.seotitle')}}:</span></h4>
        {{ Form::text('seo_title', null, ['class' => 'form-control']) }}
        <h4>{{__('blublog.seodescr')}}:</h4>
        {{ Form::text('seo_descr', null, ['class' => 'form-control']) }}
    </div>
</div>
<div class="card" style="margin-top:10px;">
    <div class="card-header text-white bg-primary bg">{{__('blublog.descr')}} - {{__('blublog.can_be_empty')}}</div>
        <div class="card-body">
            {{ Form::text('excerpt', null, ['class' => 'form-control']) }}

    </div>
</div>
