@extends('blublog::panel.main')

@section('navbar')
@if (blublog_setting("post_editor"))
<script src="https://cloud.tinymce.com/stable/tinymce.min.js"></script>
<script>tinymce.init({ selector:'textarea',   plugins: "link, image, fullscreen, textcolor, table, textcolor colorpicker, print, media mediaembed"});
</script>
@endif
<nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{ url('/panel') }}">{{ __('panel.home') }}</a></li>
                  <li class="breadcrumb-item"><a href="{{ url('/panel/posts') }}">{{ __('panel.posts') }}</a></li>
                  <li class="breadcrumb-item active" aria-current="page">{{__('panel.add_post')}}</li>
                </ol>
</nav>
@endsection

@section('content')
<div id = "alert_placeholder"></div>

<script>

</script>


{!! Form::open(['route' => 'blublog.posts.store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
<div class="modal fade bd-example-modal-lg"  id="imgModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="form-group">
              <div class="card-body">
                  <label for="exampleFormControlFile1">{{__('panel.file_input')}}</label>
                  <input type="file"  name="file"   id="file" class="form-control-file" id="exampleFormControlFile1">
                  <hr>
                  <input type="text" class="form-control" id="searchfor" placeholder="Search for file here">
                  <br><input type="button" class="btn btn-info " onclick="searchforfile()" value="Search">
                  <hr>
                  <p id="infopanel">{{__('panel.latest_img')}}</p>
                  <div id="gallery" class="row text-center text-lg-left">

                  </div>

              </div>
          </div>
      </div>
    </div>
  </div>


  <div class="card border-danger" style="margin-bottom:20px;">
    <div class="card-header  text-white bg-danger">{{__('panel.title')}}</div>
    <div class="card-body text-primary">
        {{ Form::text('title', null, ['class' => 'form-control']) }}
    </div>
  </div>

<div class="row">
        <div class="col-xl-9">

            <div class="card border-danger">
                <div class="card-header  text-white bg-danger">{{__('panel.content')}}</div>
                <div class="card-body text-primary">
                    {{ Form::textarea('content', null, ['class' => 'form-control', 'rows' => '15']) }}
                </div>
            </div>


            <div class="card border-danger" style="margin-top:10px;">
                <div class="card-header  text-white bg-danger">{{__('panel.categories')}}</div>
                <div class="card-body text-primary">
                    <select  id="select2-multi" class="form-control select2-multi" name="categories[]" multiple="multiple" >
                        @foreach($categories as $category)
                        <option value='{{ $category->id }}'> {{ $category->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="card border-primary" style="margin-top:10px;">
                <div class="card-header  text-white bg-primary">{{__('panel.tags')}}</div>
                <div class="card-body text-primary">
                    <select  id="select3-multi" class="form-control select2-multi" name="tags[]" multiple="multiple" >
                        @foreach($tags as $tag)
                        <option value='{{ $tag->id }}'> {{ $tag->title }}</option>
                            @endforeach
                    </select>
                </div>
            </div>

                    <script type="text/javascript">
                          $(".select2-multi").select2();
                          $(".select3-multi").select2();
                    </script>
        </div>
        <div class="col-xl-3">

        <div class="card border-success " style="margin-bottom:10px;">
            <div class="card-header  text-white bg-success ">
                {{ Form::submit(__('panel.add_post'), ['class' => 'btn btn-success btn-block', 'id'=>'submitBtn']) }}
            </div>
            <div class="card-body text-primary">
                {{Form::select('status', [ 'publish' => 'Public', 'draft' => 'Draft', 'private' => 'Private']) }}
                {{Form::select('type', [ 'posts' => 'Post', 'video' => 'Video']) }}
            </div>
        </div>
        <div id="pagepanel"></div>
         <button type="button" class="btn btn-info btn-sm btn-block" data-toggle="modal" data-target=".bd-example-modal-lg">Upload Post Image</button>

        <div class="card border-primary" style="margin-top:10px;">
            <div class="card-header  text-white bg-primary">{{__('panel.headlight')}}</div>
            <div class="card-body text-primary">
                {{ Form::text('headlight', null, ['class' => 'form-control']) }}
            </div>
        </div>
        <div class="card border-primary" style="margin-top:10px;">
            <div class="card-header  text-white bg-primary">{{__('panel.settings')}}</div>
            <div class="card-body text-primary">
                <p>{{Form::checkbox('password', 'value', null, ['onclick' => 'add_pass_fil()'])}} {{__('panel.post_password')}}<div id="passw"></div></p>
                {{Form::checkbox('comments', null, true)}} {{__('panel.allow_comments')}}<br>
                {{Form::checkbox('slider', null)}} {{__('panel.slider')}}<br>
                {{Form::checkbox('front', null)}} {{__('panel.front_page')}}<br>
                {{Form::checkbox('recommended', null)}} {{__('panel.recommended')}}
            </div>
        </div>

        </div>
</div>





<div class="card" style="margin-top:10px;">
<div class="card-header text-white bg-primary bg">{{__('panel.seoinfo')}} - {{__('panel.autogen')}}</div>
    <div class="card-body">
        <h4>{{__('panel.seotitle')}}:</span></h4>
        {{ Form::text('seo_title', null, ['class' => 'form-control']) }}
        <h4>{{__('panel.seodescr')}}:</h4>
        {{ Form::text('descr', null, ['class' => 'form-control']) }}
    </div>
</div>
<div class="card" style="margin-top:10px;">
    <div class="card-header text-white bg-primary bg">{{__('panel.descr')}} - {{__('panel.can_be_empty')}}</div>
        <div class="card-body">
            {{ Form::text('excerpt', null, ['class' => 'form-control']) }}

    </div>
</div>
{!! Form::close() !!}
</div>

<script>
let times = 0 ;
function add_pass_fil(){
    if(times == 0){
        let content = document.getElementById("passw");
        let element = document.createElement("INPUT");
        element.setAttribute("type", "password");
        element.setAttribute("name", "password");
        element.className="form-control";
        content.appendChild(element);
        times++;
    }
}


function we_are_offline(){
    $('#alert_placeholder').html('<div class="alert alert-warning"><a class="close" data-dismiss="alert">×</a><span>'+'Warrning! Its posible that there is no internet connection.'+'</span></div>');
    $('button').prop('disabled', true);
    $("#submitBtn").attr("disabled", true);
}
function we_are_online(){
    $('button').prop('disabled', false);
    $("#submitBtn").attr("disabled", false);
    $('#alert_placeholder').html('');
}
function check_if_online(){
    if(!navigator.onLine){
        we_are_offline();
    } else {
        we_are_online();
    }
}

window.setInterval(function(){
    check_if_online();
}, 3000);



function searchforfile(){
    let searchfor = document.getElementById("searchfor").value;
    if(searchfor != ""){
    let infopanel = document.getElementById("infopanel");
    infopanel.innerHTML = "Searching for " + searchfor;
    $.ajax({

    type:'POST',

    url:"{{ url('/blublog/searchfile') }}",

    data:{"_token": "{{ csrf_token() }}",slug:searchfor},

    success:function(data){

        if(data){
            show_files(data);
        }else{
            infopanel.innerHTML = "Nothnig found.";
            get_last_files();
        }
    }

    });
    }

}

function get_last_files(){
    const url = "{{ url('/blublog/listimg') }}";
    fetch(url)
    .then((resp) => resp.json())
    .then(function(data) {
    show_files(data.data);
})
}
get_last_files();

//Disable enter key
$(document).keypress(
  function(event){
    if (event.which == '13') {
      event.preventDefault();
    }
});

function show_files(files){
    let panel = document.getElementById("gallery");
    remove_all_child(panel);

    for (let i =0; i<files.length ; i++){
        let divcol = document.createElement('div');
        divcol.className = "col-lg-3 col-md-4 col-6";

        let img = document.createElement("IMG");
        img.src= "{{ url('/uploads') }}" + "/" + files[i].filename;
        img.className="img-fluid  img-thumbnail";
        img.id=files[i].filename.substring(6);

        let link = document.createElement("A");
        link.className="imglink";
        link.href="#";
        link.appendChild(img);

        divcol.appendChild(link);
        panel.appendChild(divcol);
    }

    let a = document.getElementsByClassName("imglink");
    for (let foo = 0; foo < a.length; foo++) {
        a[foo].addEventListener("click", function(event){ customfilename(event.toElement); });
    }


}

function customfilename(element){
    let fileinput = document.getElementById("file");
    fileinput.value = "";
    element.className="img-fluid  img-thumbnail bg-danger";
    let pal = document.getElementById("gallery");
    var input = document.createElement("INPUT");
    input.setAttribute("type", "hidden");
    input.name = "customimg";
    input.value = element.id;
    pal.appendChild(input);

    let pagepanel = document.getElementById("pagepanel");
    remove_all_child(pagepanel);
    let img = document.createElement("IMG");
    img.src= "{{ url('/uploads/posts/') }}"+ "/" + element.id;
    img.className="img-fluid  img-thumbnail";
    pagepanel.appendChild(img);
    closemodal();

}

function closemodal(){
  $('#imgModal').modal('hide');
}

function remove_all_child(element){
    while (element.firstChild) {
        element.removeChild(element.firstChild);
    }
}



</script>

@endsection
