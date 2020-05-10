<div class="row">

    @if (isset($draft_posts[0]->id))
    <div class="col-xl-6 col-lg-5">
        <div class="card border-warning shadow">
            <div class="card-header text-white bg-warning">
            {{__('blublog.draft_con')}}
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
            {{__('blublog.private_posts')}}
            </div>
            <div class="card-body">
                @include('blublog::panel.partials._continue_edit', ['items' => $private_posts])
            </div>
            </div>
    </div>
    @endif
</div>
