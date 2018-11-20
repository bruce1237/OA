@extends('admin/layout/basic')

@section('content')

    <table class="layui-table">
        <thead>
        <tr>
            <th>
                <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i>
                </div>
            </th>
            <th>姓名</th>

        </tr>
        </thead>
        <tbody>
        @foreach($logos as $key => $logo)

            <tr>
                <td>
                    <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='{{$logo->id}}'><i
                                class="layui-icon">&#xe605;</i></div>
                </td>
                <td>{{$logo->logo_name}}</td>



            </tr>
        @endforeach


        </tbody>
    </table>
    <div class="page">
        <div>
            {{$logos->links()}}
        </div>
    </div>


@endsection
