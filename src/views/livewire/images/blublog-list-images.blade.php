<div>
    @forelse ($images->chunk(4) as $chunks)
        <div class="row course-set courses__row">
            @foreach ($chunks as $image)
                <div class="col-md-3 course-block course-block-lessons">
                    <div class="card border border-light my-2">
                        <div class="card-body p-0">
                            <img src="{{ $image->url() }}" loading="lazy"
                                wire:click="imageSelected('{{ $image->id }}')" class="card-img-top">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @empty
        <div class="alert alert-info" role="alert">
            Няма качени файлове.
        </div>
    @endforelse
    {{ $images->links() }}
</div>
