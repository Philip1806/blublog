@extends('blublog::panel.layout.main')
@section('nav')
@endsection


@section('content')
    <table class="table table-hover ">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Log details</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th>User ID</th>
                @if ($log->user_id)
                    <td>{{ $log->user_id }} ({{ blublog_get_user($log->user_id)->name }})</td>
                @else
                    <td>Log is not created from user.</td>
                @endif
            </tr>
            <tr>
                <th>IP</th>
                <td>{{ $log->ip }}</td>
            </tr>
            <tr>
                <th>Type</th>
                <td>{{ $log->type }}</td>
            </tr>
            <tr>
                <th>User Agent</th>
                <td>{{ $log->user_agent }}</td>
            </tr>
            <tr>
                <th>Request URL</th>
                <td>{{ $log->request_url }}</td>
            </tr>
            <tr>
                <th>Referer</th>
                <td>{{ $log->referer }}</td>
            </tr>
            <tr>
                <th>Lang</th>
                <td>{{ $log->lang }}</td>
            </tr>
            <tr>
                <th>Message</th>
                <td>{{ $log->message }}</td>
            </tr>
            <tr>
                <th>created_at</th>
                <td>{{ $log->created_at }}</td>
            </tr>
        </tbody>
    </table>
    <div class="card my-2">
        <div class="card-body">
            <h4>Data</h4>
            {{ $log->data }}
        </div>
    </div>

@endsection
