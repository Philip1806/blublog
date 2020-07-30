<div class="card border-info">
    <h5 class="card-header bg-info text-white">{{$title}}</h5>
    <table class="table table-sm table-hover">
        <tbody>
            @foreach ($permissions as $permission)
            <tr>
                <td>{{ Form::label($permission, str_replace("_", " ", $permission)) }}</td>
                {{Form::hidden($permission,0)}}
                <td>{{Form::checkbox($permission, null,$role->{$permission})}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
