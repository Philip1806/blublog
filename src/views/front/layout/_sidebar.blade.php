@if (isset($post->tag_id))
    <p class="lead"> On this topic</p>
    @include('blublog::front.layout._sidebarList', ['posts' => $post->fromThisTopic])
@endif
{!! Form::open(['route' => 'blublog.front.search', 'method' => 'GET']) !!}
{{ Form::text('search', null, ['required', 'class' => 'form-control form-control-sm rounded mb-2', 'placeholder' => 'Search for post...']) }}
{!! Form::close() !!}
<p class="h4 mb-3">Categories:</p>


@forelse ($categories as $category)
    <div class="card bg-dark border-dark mb-3">
        <div class="card-header "><a class="text-white" style="text-decoration: none;"
                href="{{ route('blublog.front.category', $category->slug) }}">{{ $category->title }}</a>
        </div>
        @include('blublog::front.layout._category')
    </div>
@empty
@endforelse

@include('blublog::front.layout._sidebarList', ['posts' => $rec_posts])
