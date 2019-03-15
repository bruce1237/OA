@extends ("admin/layout/basic")

@section('shortcut')
@endsection

@section('content')
    <div class="row">
        @if(!sizeof($data))
            <h4>无待审批申请</h4>
        @endif
        @foreach($data as $key=>$value)
            @switch($key)
                @case('client')  {{--客户信息的修改--}}

                @foreach($value as $client)
                    <div class="col-2">
                        <div class="card border-primary ">
                            <div class="card-header">修改客户信息
                                <small>(申请人:{{$client['by']}})</small>
                            </div>
                            <div class="card-body text-primary ">
                                <form id="client_{{$client['pk']}}">
                                    @foreach($client['change']['old'] as $key=>$field)
                                        @php
                                            $tag="";
                                                switch($key){
                                                    case "client_name":
                                                        $tag="姓名";
                                                    break;
                                                    case "client_mobile";
                                                        $tag="手机";
                                                    break;
                                                    case "client_tel":
                                                        $tag="电话";
                                                    break;
                                                    case "client_wechat":
                                                        $tag="微信";
                                                    break;
                                                    case "client_qq":
                                                        $tag="QQ";
                                                    break;
                                                    case "client_email":
                                                    $tag="邮箱:";
                                                    break;
                                                    case "client_address":
                                                    $tag="地址";
                                                    break;
                                                    case "client_post_code":
                                                    $tag="邮编";
                                                    break;
                                                }
                                        @endphp


                                        @if(key_exists($key,$client['change']['new']))
                                            {{$tag}}<small>{{$field}}</small> <span class="badge badge-danger">{{$client['change']['new'][$key]}}</span>
                                            <br />
                                        @endif

                                    @endforeach
                                    <hr />
                                    <button type="button" class="btn btn-success float-right" onclick="process('client',1,{{$client['pk']}})">通过</button>
                                    <button type="button" class="btn btn-danger" onclick="process('client',0,{{$client['pk']}})">驳回</button>

                                </form>


                            </div>
                        </div>
                    </div>
                @endforeach
                @break
            @endswitch
        @endforeach




    </div>

    <script>
        function process(info,type,pk){
           $.ajax({
               url:"{{url('admin/approval')}}",
               type:'post',
               data:{infoType:info,'type':type,id:pk},
               dataType:'json',
               success:function(data){
                   layer.msg(data.msg,{icon:data.code});
                   if(data.status){
                      location.replace(location.href);
                  }
               }
           })
        }
    </script>


@endsection



