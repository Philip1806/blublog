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
