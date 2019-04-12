@extends('admin/layout/basic')

@section('shortcut')

@endsection

@section('content')

    <div class="row">
        <div class="col-4"><h4>上传合同模板, 合同模板必须是docx类型的文件</h4>


            <div class="input-group input-group-sm">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="contractFile" onchange="showUploadFileName()">
                    <label class="custom-file-label" id="ShowInputFile" for="inputGroupFile04">选择合同模板</label>
                </div>

                <input type="text" class="form-control" id="contractName" placeholder="生成的合同名称"
                       aria-label="Recipient's username"
                       aria-describedby="basic-addon2">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" onclick="uploadContract()">上传</button>
                </div>
            </div>


            请选择服务:
            <div class="row" id="serviceList">
                @foreach($data['freshServices'] as $service)
                    <label class="alert alert-secondary services">
                        <input type="checkbox" name="UnusedServices" id="UnusedServices"
                               value="{{$service->service_id}}">
                        {{$service->service_name}}
                    </label>&nbsp;
                @endforeach
            </div>
        </div>


        <div class="col-8"> 已经上传的合同列表
            @foreach($data['contracts'] as $contract)
                <div class="alert alert-primary" role="alert" onclick="showServices('{{json_encode($contract->contract_services)}}')">
                    {{$contract->contract_name}}
                </div>
            @endforeach

        </div>
    </div>

    <script>
        function showUploadFileName() {
            let file = $("#contractFile").val().replace("C:\\fakepath\\", '');

            $("#ShowInputFile").text(file);
            $("#contractName").val(file.replace(".docx", ''));
        }

        function uploadContract() {
            let services = document.getElementsByName('UnusedServices');
            let selectedServices = new Array();
            for (var i = 0; i < services.length; i++) {
                if (services[i].checked) {
                    selectedServices.push(services[i].value);
                }
            }
            let file = $("#contractFile")[0].files[0];
            let contractName = $("#contractName").val();
            let data = new FormData();
            data.append('file', file);
            data.append('contract_services', JSON.stringify(selectedServices));
            data.append('contract_name', contractName);


            if (uploadValidation(selectedServices, contractName, file)) {
                $.ajax({
                    url: "{{url('admin/uploadContract')}}",
                    type: 'post',
                    data: data,
                    dataType: 'Json',
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        layer.msg(data.msg, {icon: data.code});
                        if (data.status) {
                            window.location.reload();
                        }

                    }

                });

            }
        }

        function uploadValidation(selectedServices, contractName, file) {
            if (!selectedServices.length) {
                $(".services").attr("class", 'alert alert-danger services');
                alert("请选择当前合同适用的服务");
                return false;
            }
            if (contractName == '' || contractName == null) {
                alert("请命名当前的合同");
                return false;
            }
            if (file == null) {
                alert("请选择合同模板");
                return false;
            }
            return true;

        }

        function showServices(services){
            $.ajax({
               url:"{{url('admin/showContractServices')}}",
               type:'post',
               data:{services:services},
               dataType:'json',
               success:function(data){
                   if(data.status){
                       let msg ='';
                       $.each(data.data,function(key,value){
                           msg+=value.service_name+"<br />";
                       });
                   layer.msg(msg,{icon:data.code});
                   }else{

                       layer.msg(data.msg,{icon:data.code});
                   }
               }
            });
        }

    </script>
@endsection



