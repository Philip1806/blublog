<table class="table table-hover">
    <tbody>
      @foreach ($items as $post)
      <tr>
        <th>{{$post->title}}</th>
        <td><a  class="btn btn-outline-primary btn-block " href="{{ route('blublog.posts.edit', $post->id) }}" >{{__('panel.edit')}}</a>
      </tr>
      @endforeach
    </tbody>
</table>
