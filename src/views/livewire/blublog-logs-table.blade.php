<div class="row">
    <div class="col-lg-9">
        <table class="table">
            <thead class="thead bg-{{ $color }}">
                <tr>
                    <th scope="col">Info</th>
                    <th scope="col">Date</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($logs as $log)
                    @if (($log->type == 'error' or $log->type == 'alert') and $type == '')
                        <tr class="bg-dark text-white">
                        @else
                        <tr>
                    @endif
                    <th>{{ $log->message }}</th>
                    <td>{{ $log->created_at }}</td>
                    <td><a href="{{ route('blublog.panel.logs.show', $log->id) }}" class="btn btn-primary btn-sm"
                            role="button" aria-pressed="true"><span class="oi oi-eye"></span> Details</a>
                    </td>
                    <td><a wire:click="delete('{{ $log->id }}')" class="btn btn-danger btn-sm" role="button"
                            aria-pressed="true"><span class="oi oi-circle-x"></span> Delete</a>
                    </td>
                    </tr>
                @empty
                    <tr>
                        <th>No logs.</th>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $logs->links() }}
    </div>
    <div class="col-lg-3">
        <div class="input-group mb-2">
            <div class="input-group-prepend">
                <div class="input-group-text"><span class="oi oi-magnifying-glass"></span></div>
            </div>
            <input wire:model="search" type="search" class="form-control" id="inlineFormInputGroup"
                placeholder="Type ip...">

        </div>
        <button wire:click="showOnly('visit')"
            class="btn btn-primary btn-block {{ $type == 'visit' ? 'active' : '' }}">
            @if ($type == 'visit')
                <span class="oi oi-arrow-right"></span>
            @endif
            Visits
        </button>
        <button wire:click="showOnly('bot')" class="btn btn-primary btn-block {{ $type == 'bot' ? 'active' : '' }}">
            @if ($type == 'bot')
                <span class="oi oi-arrow-right"></span>
            @endif
            Bot Visits
        </button>
        <button wire:click="showOnly('info')" class="btn btn-info btn-block {{ $type == 'info' ? 'active' : '' }}">
            @if ($type == 'info')
                <span class="oi oi-arrow-right"></span>
            @endif
            Info
        </button>
        <button wire:click="showOnly('alert')"
            class="btn btn-warning btn-block {{ $type == 'alert' ? 'active' : '' }}">
            @if ($type == 'alert')
                <span class="oi oi-arrow-right"></span>
            @endif
            Alerts
        </button>
        <button wire:click="showOnly('error')"
            class="btn btn-danger btn-block {{ $type == 'error' ? 'active' : '' }}">
            @if ($type == 'error')
                <span class="oi oi-arrow-right"></span>
            @endif
            Errors
        </button>
    </div>
</div>
