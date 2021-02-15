<div class="row">
    <div class="col-lg-10">
        @if ($posts->count())
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Title</th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($posts as $post)
                        <tr>
                            <th>{{ $post->title }}</th>
                            <td>
                                <a href="{{ route('blublog.panel.posts.edit', $post->id) }}"
                                    class="btn btn-primary btn-sm" role="button" aria-pressed="true"><span
                                        class="oi oi-pencil"></span> Edit</a>
                            </td>
                            <td>
                                <a wire:click="delete('{{ $post->id }}')" class="btn btn-danger btn-sm"
                                    role="button"><span class="oi oi-circle-x"></span> Delete</a>
                            </td>
                        </tr>
            @endforeach

            </tbody>
            </table>
            {{ $posts->links() }}
        @else
            <div class="alert alert-info" role="alert">
                No found posts.
            </div>
            @endif
        </div>
        <div class="col-lg-2">

            <div class="input-group mb-2">
                <div class="input-group-prepend">
                    <div class="input-group-text"><span class="oi oi-magnifying-glass"></span></div>
                </div>
                <input wire:model="search" type="search" class="form-control" id="inlineFormInputGroup"
                    placeholder="Search...">
            </div>
            <button wire:click="showOnly('publish')"
                class="btn btn-primary btn-sm btn-block {{ $status == 'publish' ? 'active' : '' }}">
                @if ($status == 'publish')
                    <span class="oi oi-arrow-right"></span>
                @endif
                Public
            </button>
            <button wire:click="showOnly('private')"
                class="btn btn-primary btn-sm btn-block {{ $status == 'private' ? 'active' : '' }}">
                @if ($status == 'private')
                    <span class="oi oi-arrow-right"></span>
                @endif
                Private
            </button>
            <button wire:click="showOnly('co-op')"
                class="btn btn-primary btn-sm btn-block {{ $status == 'co-op' ? 'active' : '' }}">
                @if ($status == 'co-op')
                    <span class="oi oi-arrow-right"></span>
                @endif
                Co-op
            </button>

            @if (blublog_is_mod())
                <button wire:click="showOnly('waits')"
                    class="btn btn-primary btn-sm btn-block {{ $status == 'waits' ? 'active' : '' }}">
                    @if ($status == 'waits')
                        <span class="oi oi-arrow-right"></span>
                    @endif
                    Waits for approve
                </button>
            @endif

        </div>
    </div>
    </div>
