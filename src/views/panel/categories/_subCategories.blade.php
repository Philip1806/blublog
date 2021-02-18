@if ($category->children)
    <ul class="list-group list-group-flush">
        @foreach ($category->children as $subCategory)
            <li class="list-group-item">{{ $subCategory->title }}
                @include('blublog::panel.categories._editCategory',['category' => $subCategory])
                @if ($subCategory->children)
                    @include('blublog::panel.categories._subCategories', ['category' => $subCategory])
                @endif
            </li>
        @endforeach
    </ul>
@endif
