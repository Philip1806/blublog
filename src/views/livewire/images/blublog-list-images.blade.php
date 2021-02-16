<div>
    @forelse ($images->chunk(4) as $chunks)
        <div class="row course-set courses__row">
            @foreach ($chunks as $image)
                <div class="col-md-3 course-block course-block-lessons">
                    <div class="card border border-light my-2">
                        <div class="card-body p-0">
                            <a type="button" wire:click="selected('{{ $image->id }}')">
                                <img src="{{ $image->url() }}" loading="lazy" class="card-img-top">
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @empty
        <div class="alert alert-info" role="alert">
            There are no uploaded photos. :(
        </div>
    @endforelse
    {{ $images->links() }}
</div>
