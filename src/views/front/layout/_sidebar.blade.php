{!! Form::open(['route' => 'blublog.front.search', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
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
@forelse ($rec_posts as $post)
    <div class="card">
        <img src="{{ $post->thumbnailUrl() }}" class="card-img-top" alt="{{ $post->title }} thumbnail">
        <div class="card-body">
            <h5 class="card-title"><a style="text-decoration: none;"
                    href="{{ route('blublog.front.single', $post->slug) }}">{{ $post->title }}</a></h5>
            <p class="card-text">{{ $post->seo_title }}</p>
        </div>
    </div>

@empty

@endforelse
