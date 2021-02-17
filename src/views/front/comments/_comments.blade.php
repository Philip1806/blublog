@if ($post->comments)
    <h4><span class="oi oi-comment-square mt-2"></span> Comments</h4>
    <hr>
    @if (isset($comments[0]->id))
        @include('blublog::front.comments._comment_replies', ['comments' => $comments, 'post_id' => $post->id])
    @else
        <p class="lead">No comments</p>
    @endif

    <hr />
    <button class="btn btn-secondary btn-block my-3" type="button" data-toggle="collapse"
        data-target="#show_comment_form" aria-expanded="false" aria-controls="collapseExample">
        Add comment
    </button>
    <div class="collapse" id="show_comment_form" style="margin-bottom: 20px;">
        <div class="card border-secondary">
            <div class="card-body text-primary">
                <form method="post" action="{{ route('blublog.front.comments.store') }}">
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
                                    <small class="text-danger">Content is unvalid.</small>
                                @endif

                            </div>
                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email">Email</label>
                                @if (!$errors->has('email'))
                                    <input type="text" name="email" class="form-control" value="{{ old('email') }}">
                                @endif
                                @if ($errors->has('email'))
                                    <input type="text" name="email" class="form-control is-invalid"
                                        value="{{ old('email') }}">
                                    <small class="text-danger">Content is unvalid.</small>
                                @endif

                            </div>
                            <div class="form-group{{ $errors->has('question_answer') ? ' has-error' : '' }}">
                                <label for="question_answer">Anti-spam question:
                                    {{ config('blublog.spam-question') }}</label>
                                <input type="text" class="form-control" name="question_answer" value="" />
                            </div>
                        @endguest
                        @auth
                            <input type="hidden" name="name" value="{{ Auth::user()->name }}" />
                            <input type="hidden" name="email" value="{{ Auth::user()->email }}" />
                            <input type="hidden" name="question_answer"
                                value="{{ config('blublog.spam-question-answer') }}" />


                        @endauth
                        <div class="form-group{{ $errors->has('comment_body') ? ' has-error' : '' }}">
                            <label for="commentt">Your comment</label>
                            @if (!$errors->has('comment_body'))
                                <textarea rows="3" type="text" name="comment_body" class="form-control"
                                    value="{{ old('comment_body') }}"></textarea>
                            @endif
                            @if ($errors->has('comment_body'))
                                <textarea rows="3" type="text" name="comment_body" class="form-control is-invalid"
                                    value="{{ old('comment_body') }}"></textarea>
                                <small class="text-danger">Content is unvalid.</small>
                            @endif

                        </div>
                        <input type="hidden" name="post_id" value="{{ $post->id }}" />
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-secondary" value="Add comment" />
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
