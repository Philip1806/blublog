<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$error}}</title>
    <link rel="stylesheet" href="{{ url('/') }}/blublog/css/sb-admin-2.min.css">
</head>
<body>
<div class="mt-4 container">
    <div class="display-3 text-danger">
        BLUblog Error
    </div>
    <hr>

        <div class="p-4">
            <div class="row align-items-center" style="height: 200px;">
                <div class="col-lg-4">
                    <div class="p-5 text-white text-center bg-danger">
                        <b>{{$error}}</b>
                    </div>
                </div>

              <div class="col-lg-8">
                <div class="p-4 text-white bg-info">
                    {{$msg}}
                </div>
              </div>

            </div>
        </div>
        <hr>
        <a href="http://blublog.info">BLUblog</a> | Blog package for Laravel.
</div>
</body>
</html>
