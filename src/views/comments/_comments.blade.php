@if (!blublog_setting('disable_comments_modul'))
<h4>{{__('panel.comments')}}</h4>
<hr>


@if (isset($comments[0]->id))
@include('blublog::comments._comment_replies', ['comments' => $comments, 'post_id' => $post->id])
@else
    <center> <b> {{__('panel.no_comments')}}</b> </center>
@endif

<hr />
<button class="btn btn-secondary btn-block" type="button" data-toggle="collapse" data-target="#show_comment_form" aria-expanded="false" aria-controls="collapseExample">
    {{__('panel.add_comments')}}
</button>
<div class="collapse" id="show_comment_form"  style="margin-bottom: 20px;">
    <div class="card border-secondary">
        <div class="card-body text-primary">
            <form method="post" action="{{ route('blublog.front.comment_store') }}">
                @csrf
                <div class="form-group">
                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name">{{__('panel.name')}}</label>
                            @if (!$errors->has('name'))
                    <input type="text" name="name" class="form-control" value="">
                            @endif
                            @if ($errors->has('name'))
                            <input type="text" name="name" class="form-control is-invalid" value="{{ old('name')}}">
                            <small class="text-danger">Съдържанието в горното поле липсва или е невалидно (твърде дълго)</small>
                            @endif

                    </div>
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email">Email</label>
                            @if (!$errors->has('email'))
                            <input type="text" name="email" class="form-control" value="{{ old('email')}}">
                            @endif
                            @if ($errors->has('email'))
                            <input type="text" name="email" class="form-control is-invalid" value="{{ old('email')}}">
                            <small class="text-danger">Съдържанието в горното поле липсва или е невалидно (твърде дълго)</small>
                            @endif

                    </div>
                    <div class="form-group{{ $errors->has('comment_body') ? ' has-error' : '' }}">
                            <label for="commentt">{{__('panel.comment')}}</label>
                            @if (!$errors->has('comment_body'))
                            <textarea rows="3" type="text" name="comment_body" class="form-control" value="{{ old('comment_body')}}"></textarea>
                            @endif
                            @if ($errors->has('comment_body'))
                    <textarea rows="3" type="text" name="comment_body" class="form-control is-invalid" value="{{ old('comment_body')}}"></textarea>
                            <small class="text-danger">Съдържанието в горното поле липсва или е невалидно (твърде дълго)</small>
                            @endif

                    </div>
                    <input type="hidden" name="post_id" value="{{ $post->id }}" />
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-secondary" value="{{__('panel.add_comments')}}" />
                </div>
            </form>
        </div>
    </div>
</div>
@endif
