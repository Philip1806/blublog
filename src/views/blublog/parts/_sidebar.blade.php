<h2>Categories</h2>
<ul class="list-group" style="margin-bottom:10px;">
    @foreach ($categories as $category)
    <li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center ">
        <a href="{{ route('blublog.front.category_show', $category->slug) }}"> {{$category->title}}</a>
        <div class="foo" style="background: {{$category->colorcode}}"></div>
    </li>
    @endforeach
</ul>
{!!blublog_setting('sidebar_html')!!}
