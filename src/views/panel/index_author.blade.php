@extends('blublog::panel.main')

@section('navbar')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item active" aria-current="page">{{ __('panel.home') }}</li>
    </ol>
</nav>
@endsection
@section('content')
  <!-- Content Row -->
  <div class="row">

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">{{ __('panel.my_posts_this') }}</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $this_month_posts }}</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-newspaper fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">{{ __('panel.my_posts_last') }}</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $last_month_posts }}</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-newspaper fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-info text-uppercase mb-1">{{ __('panel.my_posts_total') }}</div>
              <div class="row no-gutters align-items-center">
                <div class="col-auto">
                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{$myposts}}</div>
                </div>
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-newspaper fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Pending Requests Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1"> {{ __('panel.posts_total') }} </div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{$totalposts}}</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-newspaper fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


    <!-- Content Row -->
<div class="row">

    @if (isset($draft_posts[0]->id))
    <div class="col-xl-6 col-lg-5">
        <div class="card border-warning shadow">
            <div class="card-header text-white bg-warning">
            {{__('panel.draft_con')}}
            </div>
            <div class="card-body">
                @include('blublog::panel.partials._continue_edit', ['items' => $draft_posts])
            </div>
            </div>
    </div>
    @endif

    @if (isset($private_posts[0]->id))
    <div class="col-xl-6 col-lg-5">
        <div class="card border-info shadow">
            <div class="card-header text-white bg-info">
            {{__('panel.private_posts')}}
            </div>
            <div class="card-body">
                @include('blublog::panel.partials._continue_edit', ['items' => $private_posts])
            </div>
            </div>
    </div>
    @endif
</div>

@endsection
