@extends('blublog::panel.main')

@section('content')
<p>
    <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
        Add role
    </button>
</p>
<div class="collapse" id="collapseExample">

<div class="card border-primary">
    <h5 class="card-header bg-primary text-white">Add User Role</h5>
    <div class="card-body">
        @include('blublog::panel.settings._roles_table', ['role' => $roles[2], 'role_id'=>"new"])
    </div>
</div>
</div>


<div class="accordion mt-3" id="accordion">
    @foreach ($roles as $role)
    @if ($role->id != 1)
        <div class="card">
            <div class="card-header bg-primary" id="headingOne">
                <h5 class="mb-0">
                <button class="btn btn-link  text-white" type="button" data-toggle="collapse" data-target="#collapse{{$role->id}}" aria-expanded="true" aria-controls="collapseOne">
                    {{$role->name}}
                </button>
                </h5>
            </div>

            <div id="collapse{{$role->id}}" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                <div class="card-body">
                    @if ($role->id != 2 and $role->id != 3)
                    <a  class="btn btn-outline-danger btn-block mb-3" href="{{ route('blublog.roles.delete', $role->id) }}" >{{__('blublog.delete')}}</a>
                    @endif
                    @include('blublog::panel.settings._roles_table', ['role' => $role, 'role_id'=>$role->id])
                </div>
            </div>
        </div>
    @endif
    @endforeach
</div>

@endsection
