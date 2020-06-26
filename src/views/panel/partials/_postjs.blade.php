@if (blublog_setting("post_editor"))
<script src="https://cloud.tinymce.com/stable/tinymce.min.js"></script>
<script>tinymce.init({ selector:'textarea',   plugins: "link, image, fullscreen, textcolor, table, textcolor colorpicker, print, media mediaembed",
image_class_list: [
    {title: 'Responsive', value: 'img-fluid'},
    {title: 'Tumb', value: 'img-thumbnail'},
    {title: 'Right', value: 'rounded float-right'},
    {title: 'Left', value: 'rounded float-left pull-right mr-2'},
]
});
</script>
@endif
<script src="{{ url('/') }}/blublog\js/jquery-ui.js"></script>
<link rel="stylesheet" href="{{ url('/') }}/blublog\css/jquery-ui.css">
<script>
  $( function() {
    $( "#datepicker" ).datepicker();
    $( "#datepicker" ).datepicker( "option", "dateFormat", "dd/mm/yy" );
  } );
</script>
<script>
function find_tag(){
    let searchfor = document.getElementById("tagname").value;
    if(searchfor != ""){
    let infopanel = document.getElementById("find_tag_panel");
    infopanel.innerHTML = "Searching for " + searchfor;
    $.ajax({

    type:'POST',

    url:"{{ url('/blublog/search') }}",

    data:{"_token": "{{ csrf_token() }}",slug:searchfor,type:"tag"},

    success:function(data){

        if(data){
            show_found_tags(data);
        }else{
            infopanel.innerHTML = "Nothnig found.";
        }
    }

    });
    }
}
function show_found_tags(tags){
    let panel = document.getElementById("find_tag_panel");
    remove_all_child(panel);

    for (let i =0; i<tags.length ; i++){
        let li = document.createElement("li");
        li.innerHTML=  tags[i].title;
        li.className="list-group-item";
        li.id = tags[i].id;
        li.addEventListener("click", function(event){ chosen_main_tag(event.toElement); });
        panel.appendChild(li);
    }
}

function chosen_main_tag(element){
    let panel = document.getElementById("find_tag_panel");
    panel.innerHTML = "Tag "+ '"'+ element.innerHTML+ '"' +" selected";
    let input = document.createElement("INPUT");
    input.setAttribute("type", "hidden");
    input.name = "main_tag_id";
    input.value = element.id;
    panel.appendChild(input);
}

function get_last_files(){
    const url = "{{ url('/blublog/listimg') }}";
    fetch(url)
    .then((resp) => resp.json())
    .then(function(data) {
    show_files(data);
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
        img.src= files[i].url;
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
    img.src= "{{ blublog_get_upload_url() }}"+ "posts/" + element.id;
    img.className="img-fluid  img-thumbnail";
    pagepanel.appendChild(img);
    closemodal();

}

function searchforfile(){
    let searchfor = document.getElementById("searchfor").value;
    if(searchfor != ""){
    let infopanel = document.getElementById("infopanel");
    infopanel.innerHTML = "Searching for " + searchfor;
    $.ajax({

    type:'POST',

    url:"{{ url('/blublog/search') }}",

    data:{"_token": "{{ csrf_token() }}",slug:searchfor,type:"file"},

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
function closemodal(){
  $('#imgModal').modal('hide');
}

function remove_all_child(element){
    while (element.firstChild) {
        element.removeChild(element.firstChild);
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
</script>
