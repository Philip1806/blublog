<div class="card border-success" style="margin-bottom:10px;">
    <div class="card-header  text-white bg-success">
        {{ Form::submit($button_title, ['class' => 'btn btn-success btn-block', 'id'=>'submitBtn']) }}                </div>
    <div class="card-body text-primary">
        {{Form::select('status', [ 'publish' => 'Public', 'draft' => 'Draft', 'private' => 'Private']) }}
        {{Form::select('type', [ 'posts' => 'Post', 'video' => 'Video']) }}
        {{ Form::text('new_date', null, ['class' => 'form-control', 'placeholder'=>$date,'id'=>'datepicker', 'autocomplete'=>'off' ,'style'=>'margin-top:20px;']) }}
    </div>
</div>
<div id="pagepanel"></div><br>
<button type="button" class="btn btn-info btn-sm btn-block" data-toggle="modal" data-target=".bd-example-modal-lg">Upload Post Image</button>
<hr>
