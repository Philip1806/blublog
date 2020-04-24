@extends('blublog::panel.main')


@section('content')
<div class="card border-primary shadow">
    <div class="card-header text-white bg-primary">
    {{ __('panel.posts_rating') }}
    </div>
        @if (!empty($ratings[0]->id))
        <table class="table">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">{{ __('panel.rating') }}</th>
                    <th scope="col">{{ __('panel.Post') }}</th>
                    <th scope="col">{{ __('panel.date') }}</th>
                    <th scope="col">{{ __('panel.ip') }}</th>
                    <th scope="col">{{ __('panel.new_copy') }}</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody>
                        @foreach ( $ratings as $post )
                        <tr>
                            <td>
                            @if ($post->rating == '5')
                            <span class="badge badge-success">{{ $post->rating }} {{ __('panel.stars') }}</span>
                            @elseif ($post->rating == '4')
                            <span class="badge badge-success">{{ $post->rating }} {{ __('panel.stars') }}</span>
                            @elseif ($post->rating == '3')
                            <span class="badge badge-warning">{{ $post->rating }} {{ __('panel.stars') }}</span>
                            @elseif ($post->rating == '2')
                            <span class="badge badge-danger">{{ $post->rating }} {{ __('panel.stars') }}</span>
                            @else
                            <span class="badge badge-danger">{{ $post->rating }} {{ __('panel.star') }}</span>
                            @endif
                            </td>
                            <td><a href="{{ route('blublog.posts.show', $post->post_id) }}" >{{ $post->postname }}</a></td>
                            <td>{{ $post->created_at }}</td>
                            <td>
                            @if ($post->ip)
                            <span class="badge badge-success">{{ $post->ip }}</span>
                            @else
                            <span class="badge badge-danger">NULL</span>
                            @endif
                            </td>
                            <td><a href="{{ route('blublog.posts.rating.duplicate', $post->id) }}"  role="button" class="btn btn-outline-primary btn-block ">{{ __('panel.copy') }}</a></td>
                            <td>{!! Form::open(['route' => ['blublog.posts.rating.destroy', $post->id], 'method' => 'DELETE']) !!}
                            {!! form::submit( __('panel.delete'), ['class' => 'btn btn-outline-danger btn-block ' ]) !!}
                            {!! Form::close() !!}</td>

                        </tr
                        @endforeach

                </tbody>
        </table>
        {!! $ratings->links(); !!}

        @else
        <div class="card-body text-center">
            <b>{{__('panel.no_ratings')}}</b>
        </div>
        @endif
</div>
@endsection
