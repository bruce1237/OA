@extends ('admin/layout/basic');

@section('content')
    @for($i=0;$i<$count;$i++)
    {{$i}}
    @endfor
    @endsection
