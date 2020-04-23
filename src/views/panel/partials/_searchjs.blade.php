<script>
function searchfor(datatype){
    let searchfor = document.getElementById("searchfor").value;
    if(searchfor != ""){
    let infopanel = document.getElementById("infopanel");
    infopanel.innerHTML = "Searching for " + searchfor;
    $.ajax({

    type:'POST',

    url:"{{ url('/blublog/search') }}",

    data:{"_token": "{{ csrf_token() }}",slug:searchfor,type:datatype},

    success:function(data){

        if(data){
            show_files(data);
        }else{
            infopanel.innerHTML = "Nothnig found.";
        }
    }

    });
    }

}
function remove_all_child(element){
    while (element.firstChild) {
        element.removeChild(element.firstChild);
    }
}
</script>
