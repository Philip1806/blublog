@if ($message)
<div class="col-xl-12">
    <div class="card border-warning shadow">
        <div class="card-header text-white bg-warning">
        <span class="oi oi-warning"></span> {{$title}}
        </div>
        <div class="card-body">
            {!! $message !!}
        </div>
    </div>
</div>
@endif
