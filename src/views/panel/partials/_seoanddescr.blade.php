<div class="card" style="margin-top:10px;">
    <div class="card-header text-white bg-primary bg">{{__('panel.seoinfo')}} - {{__('panel.autogen')}}</div>
    <div class="card-body">
        <h4>{{__('panel.seotitle')}}:</span></h4>
        {{ Form::text('seo_title', null, ['class' => 'form-control']) }}
        <h4>{{__('panel.seodescr')}}:</h4>
        {{ Form::text('descr', null, ['class' => 'form-control']) }}
    </div>
</div>
<div class="card" style="margin-top:10px;">
    <div class="card-header text-white bg-primary bg">{{__('panel.descr')}} - {{__('panel.can_be_empty')}}</div>
        <div class="card-body">
            {{ Form::text('excerpt', null, ['class' => 'form-control']) }}

    </div>
</div>
