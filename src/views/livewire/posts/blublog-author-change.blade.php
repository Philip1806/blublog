<div class="card mb-2">
    <div class="card-body">
        <h5 class="card-title">Change Author</h5>

        <div class="input-group my-2">
            <div class="input-group-prepend">
                <div class="input-group-text"><span class="oi oi-magnifying-glass"></span></div>
            </div>
            <input wire:model="search" type="search" class="form-control" placeholder="Name of user...">
        </div>
        <ul class="list-group list-group-flush">
            @foreach ($users as $user)
                <li class="list-group-item">{{ $user->name }} - <a wire:click="select('{{ $user->id }}')"
                        class="text-primary">Select</a></li>
            @endforeach
        </ul>

    </div>
</div>
