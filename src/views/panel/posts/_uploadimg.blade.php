<div class="modal fade bd-img-upload-modal-lg" tabindex="-1" role="dialog" aria-labelledby="UploadImgModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content border-primary">
        <div class="modal-header rounded-0 bg-primary text-white">
        <h5 class="modal-title" id="exampleModalLongTitle">{{__('blublog.upload_img')}}</h5>
          <button type="button" class="close bgl" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" style="color:white;">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div id="slect_file" class="p-2"><input id="image_file" name="image_file[]" type="file" /></div>
            <hr>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="form-control-addon"">{{__('blublog.not_original')}}</span>
                </div>
                <input class="form-control" aria-describedby="form-control-addon"" id="not_original" type="checkbox" Checked />
            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="form-control-addon2">{{__('blublog.keep_name')}}</span>
                </div>
                <input class="form-control" aria-describedby="form-control-addon2"" id="keep_name"  type="checkbox"  Checked />
            </div>
            <div><input class="btn btn-primary btn-block" type="submit" onclick='upload_image()' value="{{__('blublog.upload')}}"></div>
            <div class="mt-2" id="img_upload_result"></div>
        </div>
      </div>
    </div>
</div>

<script>
function upload_image(){
    let url  = "{{ url('/blublog/img_upload') }}";
    let image_file = $('#image_file').get(0).files[0];
    let slect_file = document.getElementById('slect_file');

    if(image_file){
        slect_file.className = 'p-2 bg-light';
        let formData = new FormData();
        formData.append("file", image_file);
        formData.append("_token", "{{ csrf_token() }}");
        formData.append("post_id", "{{ $post_id}}");
        let original = document.getElementById('not_original');
        if (original.checked)
        {
            formData.append("original", 0);
        } else {
            formData.append("original", 5);
        }
        let keep_name = document.getElementById('keep_name');
        if (keep_name.checked)
        {
            formData.append("keep_name", 5);
        } else {
            formData.append("keep_name", 0);
        }
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                let panel = document.getElementById('img_upload_result');
                let inform = document.createElement("div");
                inform.innerHTML = '<div class="alert alert-success"><span>'+
                'URL:<br>'+
                data['link']
                +'</span></div>';
                panel.appendChild(inform);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                let panel = document.getElementById('img_upload_result');
                panel.innerHTML = '<div class="alert alert-danger"><span>'+
                "Invalid file, no permissions or there was a server error."
                +'</span></div>';

            }
        });

    } else {
        slect_file.className = 'p-2 bg-danger';
    }
    document.getElementById('image_file').value= null;
}
</script>
