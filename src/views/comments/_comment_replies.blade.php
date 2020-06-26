@foreach($comments as $comment)

<div class="display-comment">
        @if($comment->public or Auth::check() )

        <div class="card {{$comment->border}}">
                <div class="card-header text-white bg-primary">
                        <b>{{ $comment->name }}</b>
                        @if ($comment->author)
                        {{__('blublog.comment_from_author')}}
                        @endif
                            @auth
                            @if ($comment->public)
                            <a href="{{ route('blublog.comments.approve', $comment->id) }}" class="btn btn-warning btn-sm" role="button">{{__('blublog.hide')}}</a>
                            @else
                            <a href="{{ route('blublog.comments.approve', $comment->id) }}" class="btn btn-info btn-sm" role="button">{{__('blublog.approve')}}</a>
                            @endif
                        @endauth
                </div>
                <div class="card-body">
    <p>{!! $comment->body !!}</p>
    <a class="btn btn-light " data-toggle="collapse" href="#collapseExample{{ $comment->id }}" role="button" aria-expanded="false" aria-controls="collapseExample">
            {{__('blublog.reply')}}
          </a>
    <a href="" id="reply"></a>
        <div class="collapse" id="collapseExample{{ $comment->id }}">
                <br>
                    <form method="post" action="{{ route('blublog.front.comment_reply_store') }}">
                            @csrf
                            <div class="form-group">
                                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                                <label for="name">{{__('blublog.name')}}</label>
                                                @if (!$errors->has('name'))
                                        <input type="text" name="name" class="form-control" value="">
                                                @endif
                                                @if ($errors->has('name'))
                                                <input type="text" name="name" class="form-control is-invalid" value="{{ old('name')}}">
                                                <small class="text-danger">{{__('blublog.check_this_field')}}</small>
                                                @endif

                                        </div>
                                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                                <label for="email">Email</label>
                                                @if (!$errors->has('email'))
                                                <input type="text" name="email" class="form-control" value="{{ old('email')}}">
                                                @endif
                                                @if ($errors->has('email'))
                                                <input type="text" name="email" class="form-control is-invalid" value="{{ old('email')}}">
                                                <small class="text-danger">{{__('blublog.check_this_field')}}</small>
                                                @endif

                                        </div><br>
                                        <div class="form-group{{ $errors->has('comment_body') ? ' has-error' : '' }}">
                                                <label for="commentt">{{__('blublog.comment')}}</label>
                                                @if (!$errors->has('comment_body'))
                                                <textarea rows="3" type="text" name="comment_body" class="form-control" value="{{ old('comment_body')}}"></textarea>
                                                @endif
                                                @if ($errors->has('comment_body'))
                                        <textarea rows="3" type="text" name="comment_body" class="form-control is-invalid" value="{{ old('comment_body')}}"></textarea>
                                                <small class="text-danger">{{__('blublog.check_this_field')}}</small>
                                                @endif

                                        </div>
                                <input type="hidden" name="post_id" value="{{ $post_id }}" />
                                <input type="hidden" name="comment_id" value="{{ $comment->id }}" />
                            </div>
                            <div class="form-group">
                            <input type="submit" class="btn btn-primary" value="{{__('blublog.reply')}}" />
                            </div>
                </form>
          </div>
                </div>
                @include('blublog::comments._comment_replies', ['comments' => $comment->replies])
        </div>
<br>
@endif
</div>
@endforeach
