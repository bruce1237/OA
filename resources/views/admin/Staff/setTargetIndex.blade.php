@extends("admin/layout/basic")

@section('content')

    <div class="row">
        <div class="col-12">
            <h3>设置指标</h3>
            <table class="table table-sm table-hover">
                <thead>
                <tr>
                    <th scope="col">Class</th>
                    <th scope="col">Heading</th>
                    <th scope="col">Heading</th>
                </tr>
                </thead>
                <tbody>

                @foreach($data['staffList'] as $staff)
                    <tr class="{{$data['staffList']->trBg[$staff->getOriginal('department_id')]}}">
                        <th scope="row">{{$staff->staff_name}}</th>
                        <td></td>
                        <td>Cell</td>
                    </tr>
                @endforeach

                </tbody>
            </table>

        </div>
    </div>

@endsection
