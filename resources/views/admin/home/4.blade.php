@extends('admin/layout/layout')

@section('pSection1')

    <div class="alert alert-primary" role="alert">
        <h4>本月 {{sizeof($birthday)}} 个寿星:
            @foreach($birthday as $theMan)
                <kbd>{{$theMan['staff_name']}}({{$theMan['staff_no']}})</kbd>


        @endforeach
        </h4>
    </div>



@endsection


@section('pSection2')
@endsection


@section('pSection3')
@endsection

@section('pSection4')
@endsection

