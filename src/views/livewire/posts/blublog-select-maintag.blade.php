<div class="card mb-2">
    <div class="card-body">
        <h5 class="card-title">On This Topic</h5>

        <div class="input-group my-2">
            <div class="input-group-prepend">
                <div class="input-group-text"><span class="oi oi-magnifying-glass"></span></div>
            </div>
            <input wire:model="search" type="search" class="form-control" placeholder="Search for tag...">
        </div>
        @if ($selected)
            <div class="alert alert-info" role="alert">
                On this topic is set for this post. <a wire:click="unset()" class="text-primary">Unset</a>
            </div>
        @endif
        <ul class="list-group list-group-flush">
            @foreach ($tags as $tag)
                <li class="list-group-item">{{ $tag->title }} - <a wire:click="select('{{ $tag->id }}')"
                        class="text-primary">Select</a></li>
            @endforeach
        </ul>

    </div>
</div>
