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
                                    <a wire:click="delete('{{ $post->id }}')" class="btn btn-danger btn-sm"
                                        role="button"><span class="oi oi-circle-x"></span> Delete</a>
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
</div>
</div>
