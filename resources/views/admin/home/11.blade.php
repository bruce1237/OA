@extends('admin/layout/layout')

@section('pSection2')

    <div class="alert alert-primary" role="alert">
        <h4>本月 {{sizeof($birthday)}} 个寿星:
            @foreach($birthday as $theMan)
                <span class="h5"> <span class="badge badge-success">{{$theMan['staff_name']}}<small>({{$theMan['staff_no']}})</small></span></span>
            @endforeach
        </h4>
    </div>



@endsection


@section('pSection2')

@endsection


@section('pSection3')

@endsection


