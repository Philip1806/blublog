<div class="row">
    <div class="col-lg-9">
        @if ($posts->count())
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col"></th>
                        <th scope="col">Title</th>
                        <th scope="col">Author</th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($posts as $post)
                        <tr>
                            <th>
                                <div class="file-type">
                                    <img src="{{ $post->thumbnailUrl() }}" class="img-fluid">
                                    <div class="centered text-white">
                                        @if ($post->type == 'video')
                                            <span class="oi oi-video"></span>
                                        @else
                                            <span class="oi oi-image"></span>
                                        @endif
                                    </div>
                                </div>
                            </th>
                            <th>
                                @include('blublog::panel.posts._postname')
                            </th>
                            <th>{{ $post->user->name }}</th>
                            <td>
                                @can('blublog_edit_post', $post)
                                    <a href="{{ route('blublog.panel.posts.edit', $post->id) }}"
                                        class="btn btn-primary btn-sm" role="button" aria-pressed="true"><span
                                            class="oi oi-pencil"></span> Edit</a>
                                @endcan
                            </td>
                            <td>
                                @can('blublog_delete_posts', $post)
                                    <a wire:click="deleteId('{{ $post->id }}')" class="btn btn-danger btn-sm"
                                        role="button" data-toggle="modal" data-target="#confirmDelete"><span
                                            class="oi oi-circle-x"></span> Delete</a>
                                @endcan
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
            {{ $posts->links() }}
        @else
            <div class="alert alert-info" role="alert">
                No posts found.
            </div>
        @endif
    </div>
    <div class="col-lg-3">
        <div class="input-group mb-2">
            <div class="input-group-prepend">
                <div class="input-group-text"><span class="oi oi-magnifying-glass"></span></div>
            </div>
            <input wire:model="search" type="search" class="form-control border border-dark" id="inlineFormInputGroup"
                placeholder="Потърси публикация...">
        </div>
        <button wire:click="myPosts()" class="btn btn-dark btn-sm btn-block {{ $status == 'my' ? 'active' : '' }}">
            @if ($status == 'my')
                <span class="oi oi-arrow-right"></span>
            @endif
            My posts
        </button>
        @foreach (config('blublog.post_status') as $post_status)
            @if (blublog_can_view_status($post_status))
                <button wire:click="showOnly('{{ $post_status }}')"
                    class="btn btn-primary btn-sm btn-block {{ $status == $post_status ? 'active' : '' }}">
                    @if ($status == $post_status)
                        <span class="oi oi-arrow-right"></span>
                    @endif
                    {{ $post_status }}
                </button>
            @endif
        @endforeach
    </div>
    <div wire:ignore.self class="modal fade" id="confirmDelete" tabindex="-1" role="dialog"
        aria-labelledby="confirmDeleteLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteLabel">Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to remove this post forever?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Cancel</button>
                    <button type="button" wire:click.prevent="delete()" class="btn btn-danger close-modal"
                        data-dismiss="modal">Yes, remove it.</button>
                </div>
            </div>
        </div>
    </div>
</div>
