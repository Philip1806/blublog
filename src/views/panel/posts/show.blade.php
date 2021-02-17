@extends('blublog::panel.layout.main')
@section('nav')
    @include('blublog::panel.posts._nav')
@endsection


@section('content')
    <p class="display-4">{{ $post->title }}</p>
    <div class="row">
        <div class="col-lg-8">
            <img src="{{ $post->imageUrl() }}" class="img-fluid mb-2">
            {!! $post->content !!}
        </div>
        <div class="col-lg-4">
            @can('blublog_edit_post', $post)
                <a href="{{ route('blublog.panel.posts.edit', $post->id) }}" class="btn btn-primary btn-block btn-sm mb-2"
                    role="button" aria-pressed="true"><span class="oi oi-pencil"></span> Edit</a>
            @endcan
            @can('blublog_delete_posts', $post)
                <a wire:click="delete('{{ $post->id }}')" class="btn btn-danger btn-block btn-sm mb-2" role="button"><span
                        class="oi oi-circle-x"></span> Delete</a>
            @endcan
            @can('blublog_view_stats_posts', $post)
                <button type="button" class="btn btn-info btn-block mb-2" data-toggle="modal" data-target="#postStats">
                    Post stats
                </button>

                <div class="modal fade" id="postStats" tabindex="-1" aria-labelledby="postStatsLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="postStatsLabel">Stats</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-info" role="alert">
                                    Post have {{ $post->views }} views, for {{ $post->viewsLogs->count() }} of them still
                                    have
                                    logs below.
                                </div>
                                <div class="alert alert-info" role="alert">
                                    Post have {{ $post->likes }} likes.
                                </div>


                                <table class="table table-hover">
                                    <tbody>
                                        @foreach ($post->viewsLogs as $view)
                                            <tr>
                                                @if ($view->user_id)
                                                    <th>{{ blublog_get_user($view->user_id)->name }}</th>
                                                @else
                                                    <th>{{ $view->ip }}</th>
                                                @endif
                                                <td>{{ $view->created_at }}</td>
                                                @if (blublog_is_admin())
                                                    <th> <a href="{{ route('blublog.panel.logs.show', $view->id) }}"
                                                            class="btn btn-primary btn-block btn-sm mb-2" role="button"
                                                            aria-pressed="true"><span class="oi oi-eye"></span> Details</a>
                                                    </th>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            @endcan

            <p><b>Seo title: </b>{{ $post->seo_title }}</p>
            <p><b>Seo description: </b>{{ $post->seo_descr }}</p>
            @if ($post->excerpt)
                <p><b>Excerpt: </b>{{ $post->excerpt }}</p>
            @endif
            <p><b>Post status: </b>{{ $post->status }}</p>
            <p><b>Post type: </b>{{ $post->type }}</p>

            <p>
                <span class="badge badge-{{ $post->comments ? 'success' : 'danger' }}">Allow comments:
                    {{ $post->comments }}</span>
            </p>
            <p>
                <span class="badge badge-{{ $post->front ? 'success' : 'secondary' }}">Front page post:
                    {{ $post->front }}</span>
            </p>
            <p>
                <span class="badge badge-{{ $post->recommended ? 'success' : 'secondary' }}">Recommended
                    post:{{ $post->recommended }}</span>
            </p>
        </div>
    </div>
@endsection
