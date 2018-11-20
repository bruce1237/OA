@extends('admin/layout/basic')

@section('shortcut')
    <a href="">首页</a>
    <a href="">演示</a>
    <a><cite>Excel 图片导出</cite></a>
@endsection

@section('content')

    <div class="col-12">
        <div class="input-group mb-3">

            <select class="custom-select" id="fileName">
                <option selected disabled="disabled">Choose...</option>
                @foreach($fileLists as $file)
                    <option value="{{$file}}">{{$file}}</option>
                @endforeach
            </select>
            <div class="input-group-append">
                <button class="btn btn-outline-primary" type="button" onclick="submit_form()">Button</button>
            </div>
        </div>
    </div>
    <div class="col-12" id="ajax_response">
        <div class="row">
            <div class="col-12" id="info"></div>
            <div class="col-6" id="btn1"></div>
            <div class="col-6" id="btn2"></div>


        </div>


    </div>



@endsection

<script>
    function submit_test(redisKey,type){
        $.ajax({
           'url': "{{url('admin/excelLogoMysqlImport')}}",
           'type':'post',
           'data': {"redisKey":redisKey,"type":type},
           'dataType':'json',
            success: function(data){
                $("#info").html(data.message);
               if(data.status){
                   var liveButton = "<button class ='btn btn-danger' onclick='submit_test(\""+data.redisKey+"\",\"live\")' >上传到测试服务器</button>";


                   if(type=="live"){
                       $("#info").html("NICE");
                       $("#btn1").html("");
                       $("#btn2").html("");

                   }else{
                       $("#btn1").html("");
                       $("#btn2").html(liveButton);
                   }
               }

            }
        });
    }


    function submit_form() {
        var fileName = $("#fileName").val();
        $("#info").html("<img src = '/images/timg.gif'/>");
        $("#btn1").html("");
        $("#btn2").html("");

        $.ajax({
            'url': "{{url('admin/excelExport')}}",
            'type': 'post',
            'data': {"fileName": fileName},
            'dataType': 'json',
            success: function (data) {
                switch (data.code) {
                    case 200:

                        var testButton = "<button class ='btn btn-primary' onclick='submit_test(\""+data.redisKey+"\", \"test\")' >上传到测试服务器</button>";




                        $("#info").html(data.message);


                        // $("#btn1").html(mysql_test_link);
                        $("#btn1").html(testButton);
                        $("#btn2").html("");

                        break;
                    case 201:
                        //


                        download_url = "{{url('admin/download')}}?file=" + data.file;
                        download_link = "<a class='btn btn-warning' href=" + download_url + ">下载</a>";


                        $("#info").html(data.message);
                        $("#btn1").html(download_link);
                        $("#btn2").html("");

                        break;
                    case 400:



                        $("#info").html(data.message+data.file);
                        $("#btn1").html("");
                        $("#btn2").html("");
                        break;
                    default:
                        //
                        break;
                }

            }


        });

    }
</script>

