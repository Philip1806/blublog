<div>
    @if ($message)
        <div class="alert alert-info" role="alert">
            {{ $message }}
        </div>
    @endif
    @foreach ($role->permissionsBySections() as $sections)
        @if ($sections)
            <div class="card border-dark my-2">
                <div class="card-header">
                    Section {{ $sections[0]->section }}
                </div>
                <div class="card-body">
                    @foreach ($sections as $item)
                        <button wire:click="changePermission('{{ $item->permission }}')"
                            class="btn btn-{{ $item->value ? 'success' : 'danger' }} m-1">
                            {{ $item->permission_descr }}
                        </button>
                    @endforeach
                </div>
            </div>
        @endif
    @endforeach


</div>
