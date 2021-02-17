@foreach ($comments as $comment)

    <div class="display-comment">
        @if ($comment->public or Auth::check())

            <div class="card">
                <div class="card-header text-white bg-dark">
                    <b>{{ $comment->name }}</b>
                    @if ($comment->author_id == $post->user_id)
                        <span class="badge badge-danger">Post Author</span>
                    @elseif($comment->author_id)
                        <span class="badge badge-secondary">User</span>
                    @endif
                    {{ $comment->created_at->diffForHumans() }}
                </div>
                <div class="card-body">
                    <p>{!! $comment->body !!}</p>
                    <a class="btn btn-light " data-toggle="collapse" href="#commentCollapse{{ $comment->id }}"
                        role="button" aria-expanded="false" aria-controls="commentCollapse">
                        Reply
                    </a>
                    <a href="" id="reply"></a>
                    <div class="collapse" id="commentCollapse{{ $comment->id }}">
                        <br>
                        <form method="post" action="{{ route('blublog.front.comments.reply.store') }}">
                            @csrf
                            <div class="form-group">
                                @guest
                                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                        <label for="name">Name</label>
                                        @if (!$errors->has('name'))
                                            <input type="text" name="name" class="form-control" value="">
                                        @endif
                                        @if ($errors->has('name'))
                                            <input type="text" name="name" class="form-control is-invalid"
                                                value="{{ old('name') }}">
                                            <small class="text-danger">{{ __('blublog.check_this_field') }}</small>
                                        @endif
                                    </div>
                                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                        <label for="email">Email</label>
                                        @if (!$errors->has('email'))
                                            <input type="text" name="email" class="form-control"
                                                value="{{ old('email') }}">
                                        @endif
                                        @if ($errors->has('email'))
                                            <input type="text" name="email" class="form-control is-invalid"
                                                value="{{ old('email') }}">
                                            <small class="text-danger">{{ __('blublog.check_this_field') }}</small>
                                        @endif

                                    </div>
                                @endguest
                                @auth
                                    <input type="hidden" name="name" value="{{ Auth::user()->name }}" />
                                    <input type="hidden" name="email" value="{{ Auth::user()->email }}" />

                                @endauth

                                <div class="form-group{{ $errors->has('comment_body') ? ' has-error' : '' }}">
                                    @if (!$errors->has('comment_body'))
                                        <textarea rows="3" type="text" name="comment_body" class="form-control"
                                            value="{{ old('comment_body') }}"></textarea>
                                    @endif
                                    @if ($errors->has('comment_body'))
                                        <textarea rows="3" type="text" name="comment_body"
                                            class="form-control is-invalid"
                                            value="{{ old('comment_body') }}"></textarea>
                                        <small class="text-danger">{{ __('blublog.check_this_field') }}</small>
                                    @endif
                                </div>
                                <input type="hidden" name="post_id" value="{{ $post_id }}" />
                                <input type="hidden" name="comment_id" value="{{ $comment->id }}" />
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary" value="Reply" />
                            </div>
                        </form>
                    </div>
                </div>
                @include('blublog::front.comments._comment_replies', ['comments' => $comment->replies])
            </div>
            <br>
        @endif
    </div>
@endforeach
