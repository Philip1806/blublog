@if (!empty($logs[0]->type))
<table class="table table-sm table-striped">
        <thead class="thead-light">
        <tr>
        <th scope="col">{{__('blublog.message')}}</th>
            <th scope="col">{{__('blublog.date')}}</th>
            <th scope="col"></th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
                @foreach ( $logs as $log )
                @if ($log->type == "error")
                <tr class="table-danger">
                @elseif($log->type == "alert")
                <tr class="table-warning">
                @else
                <tr>
                @endif
                <td>
                    @if ($log->type == "visit" or $log->type == "bot" )
                    <b>{{urldecode($log->request_url)}}</b><br>
                    <i>{{urldecode($log->referer)}}</i>
                    @else
                    {{$log->message}}
                    @endif
                </td>
                <td>{{$log->created_at}}</td>
                <td><a href="{{ route('blublog.logs.show', $log->id) }}"  role="button" class="btn btn-outline-info btn-block ">{{__('blublog.view')}}</a></td>
                <td><a  class="btn btn-outline-primary btn-block " href="{{ route('blublog.logs.destroy', $log->id) }}" >Delete</a></td>
                </tr>
                @endforeach

        </tbody>
</table>
{!! $logs->links(); !!}

@else
<br>
<center> <b>{{__('blublog.no_logs')}}</b> </center>
<hr>
@endif
