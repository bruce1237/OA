@extends("admin/layout/basic")

@section('content')

    <div class="row">
        <div class="col-12">
            <h3>设置指标</h3>
            <form id="setTargetForm">
                <table class="table table-sm table-hover">
                    <thead>
                    <tr>
                        <th scope="col">姓名</th>
                        <th scope="col">{{$month0 = date("Y-m")}} <button type="button" class="btn btn-success btn-sm" onclick="updateTarget()">更新</button> </th>
                        <th scope="col">{{$month1 = date("Y-m",strtotime(date('Y-m-d')."-1 month"))}}</th>
                        <th scope="col">{{$month2 = date("Y-m",strtotime(date("Y-m-d")." -2 month "))}}</th>
                        <th scope="col">{{$month3 = date("Y-m",strtotime(date("Y-m-d")." -3 month "))}}</th>
                        <th scope="col">{{$month4 = date("Y-m",strtotime(date("Y-m-d")." -4 month "))}}</th>
                        <th scope="col">{{$month5 = date("Y-m",strtotime(date("Y-m-d")." -5 month "))}}</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($data['staffList'] as $staff)
                        <tr class="{{$data['staffList']->trBg[$staff->getOriginal('department_id')]}}">
                            <th scope="row">{{$staff->staff_name}}</th>
                            <td>
                                <input type="text" name="{{$staff->staff_id}}" value=" {{($staff->sales)[$month0]['target']}}"/></td>
                            <td onclick='getSalesDetails({{$staff->staff_id}},"{{$month1}}")'>
                                {{($staff->sales)[$month1]['target']}}/{{($staff->sales)[$month1]['achieved']}}
                                <span
                                    class="badge badge-{{($staff->sales)[$month1]['percentage']>100?'success':'danger'}}">{{($staff->sales)[$month1]['percentage']}}%</span>
                            </td>
                            <td onclick='getSalesDetails({{$staff->staff_id}},"{{$month2}}")'>
                                {{($staff->sales)[$month2]['target']}}/{{($staff->sales)[$month2]['achieved']}}
                                <span
                                    class="badge badge-{{($staff->sales)[$month2]['percentage']>100?'success':'danger'}}">{{($staff->sales)[$month2]['percentage']}}%</span>
                            </td>
                            <td onclick='getSalesDetails({{$staff->staff_id}},"{{$month3}}")'>
                                {{($staff->sales)[$month3]['target']}}/{{($staff->sales)[$month3]['achieved']}}
                                <span
                                    class="badge badge-{{($staff->sales)[$month3]['percentage']>100?'success':'danger'}}">{{($staff->sales)[$month3]['percentage']}}%</span>
                            </td>
                            <td onclick='getSalesDetails({{$staff->staff_id}},"{{$month4}}")'>
                                {{($staff->sales)[$month4]['target']}}/{{($staff->sales)[$month4]['achieved']}}
                                <span
                                    class="badge badge-{{($staff->sales)[$month4]['percentage']>100?'success':'danger'}}">{{($staff->sales)[$month4]['percentage']}}%</span>
                            </td>
                            <td onclick='getSalesDetails({{$staff->staff_id}},"{{$month5}}")'>
                                {{($staff->sales)[$month5]['target']}}/{{($staff->sales)[$month5]['achieved']}}
                                <span
                                    class="badge badge-{{($staff->sales)[$month5]['percentage']>100?'success':'danger'}}">{{($staff->sales)[$month5]['percentage']}}%</span>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </form>
        </div>
    </div>

    <div class="modal" tabindex="-1" role="dialog" id="salesDetailsModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="salesDetailsTitle">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="salesDetailsBody">
                    <p>Modal body text goes here.</p>
                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateTarget(){
        var datat = new FormData($("#setTargetForm")[0]);
        $.ajax({
            url:"{{url('admin/updateSalesTarget')}}",
            type:'post',
            data:datat,
            processData:false,
            contentType:false,
            dataType:'json',
            success:function(data){
                layer.msg(data.msg,{icon:data.code});
                if(data.status){
                    location.replace(location.href);
                }
            }
        });
        }

        function getSalesDetails(staffId,month) {
            $.ajax({
               url:"{{url('admin/getSalesDetails')}}",
                type:'post',
                data:{staff_id:staffId,"month":month},
                dataType:'json',
                success:function(data){
                   if(data.status){
                       assignSalesIntoModel(data.data);
                       $("#salesDetailsModal").modal('show');
                   }else{
                       layer.msg(data.msg,{icon:data.code});
                   }
                }
            });
        }
        function assignSalesIntoModel(data){
            $("#salesDetailsBody").html('');
            var total=0;
            $.each(data,function(key,value){
                $("#salesDetailsBody").append('<div class="alert alert-primary" role="alert">' +
                    value.date+": &yen;"+value.sales+'</div>');
                total+=value.sales;
                $("#salesDetailsTitle").html(value.staff_name+' &yen;'+total);
            });
        }
    </script>


@endsection
