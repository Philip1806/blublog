<div class="card border-primary" style="margin-top:10px;">
    <div class="card-header  text-white bg-primary">{{__('blublog.main_tag')}}</div>
    <div class="card-body text-primary">
        {{ Form::text('maintag', null, ['class' => 'form-control', 'id'=>'tagname']) }}<br>
        <input type="button" class="btn btn-info " onclick="find_tag()" value="Search">
        <hr>
        <div id="find_tag_panel"></div>
    </div>
</div>
<div class="card border-primary" style="margin-top:10px;">
    <div class="card-header  text-white bg-primary">{{__('blublog.headlight')}}</div>
    <div class="card-body text-primary">
        {{ Form::text('headlight', null, ['class' => 'form-control']) }}
    </div>
</div>
