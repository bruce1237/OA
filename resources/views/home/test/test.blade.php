<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://cdn.bootcss.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.bootcss.com/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.js"></script>

<form name="form" id ="myForm">
    <input type='text' name='form[name]' />
    <input type='text' name='form[sex]' />
    <input type="file" id="pppc" name="form[pic]" />
    <button type="button" name='abdc' onclick='abc()'>提交</button>
    <button type="button" onclick="def()">测试</button>


</form>


<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

//    $.post("http://biao.test/admin/test", {name: "bo", sex: "M"}, function (data, status) {
//			alert("数据: " + data + "\n状态: " + status);
//    });
    $.post("{{url('test')}}",{name:"bo",sex:"M"},function(data,status){
            alert(data);
    });
            function def() {
        $obj = $("input[name='form[pic]'")[0].files[0];
        abcde = json_encode($obj);
        alert(abcde);

    }





    function abc() {
        var obj = $("#myForm").serialize();
        var picObj = new FormData();
        var pic = $("input[name='form[pic]'")[0].files[0];

        alert($.param(pic));

        picObj.append('pic', pic);
        picObj.append('data', obj);


        // for(var i= 0;i<import_logo_pics.length;i++){
        //     data.append('logo_pics[]', import_logo_pics[i]);
        // }

        $.ajax({
            type: 'post',
            url: "{{url('test')}}",
            dataType: 'json',
            data: picObj,
            'contentType': false,
            'processData': false,
            success: function (data) {
                alert(data);
            }
        });
    }



</script>    
