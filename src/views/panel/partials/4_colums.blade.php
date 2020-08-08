<div class="col-xl-4 col-md-6 mb-3">
    <div class="card border-left-{{ $color }} shadow h-100 py-2">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">{{ $title }}</div>
            <div class="h5 mb-0 font-weight-bold text-gray-800">
                @if (isset($version))
                <div class="h6 mb-0 ">
                <b>{{$version_info['ver']}}</b>
                <p>{{$version_info['msg']}}</p>
                </div>
                @else
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $val }}</div>
                @endif
            </div>
          </div>
          <div class="col-auto">
            <i class="oi oi-{{ $icon }} text-gray-300"></i>
          </div>
        </div>
      </div>
    </div>
</div>
