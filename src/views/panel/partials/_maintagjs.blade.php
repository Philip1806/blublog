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
</script>
