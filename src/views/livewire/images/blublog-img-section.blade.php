<div>
    @foreach ($images->chunk(3) as $chunks)
        <div class="row course-set courses__row">
            @foreach ($chunks as $image)
                <div class="col-md-4 course-block course-block-lessons">

                    <div class="card border border-light my-2">
                        <div class="card-body p-0">
                            <a type="button" data-toggle="modal" data-target="#image{{ $image->id }}">
                                <img src="{{ $image->url() }}" loading="lazy" class="card-img-top">
                            </a>
                            @can('blublog_delete_files', $image)
                                <a wire:click="delete('{{ $image->id }}')"
                                    class="btn btn-sm btn-danger btn-block rounded-0">
                                    Delete
                                </a>
                            @endcan
                        </div>
                    </div>

                    <div class="modal fade" id="image{{ $image->id }}" tabindex="-1"
                        aria-labelledby="image{{ $image->id }}Label" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="eimage{{ $image->id }}Label">Image info</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p type="button" class="btn btn-primary">
                                        Size <span class="badge badge-light">{{ $image->size }}</span>
                                    </p>
                                    <p>Link to original image</p>
                                    <input type="text" class="form-control my-2" value="{{ $image->url() }}">
                                    <p>Links to other sizes of this image</p>
                                    @foreach ($image->getChildren() as $versions)
                                        <input type="text" class="form-control my-2" value="{{ $versions->url() }}">
                                    @endforeach
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
    {{ $images->links() }}
</div>
