@extends('admin/layout/layout')

@section('topBar')
    <span class="alert alert-success">今日回访:<span class="badge badge-warning">{{$pendingClientCount['pending']}}个</span></span>
    <span class="alert alert-danger">逾期回访:<span class="badge badge-danger">{{$pendingClientCount['overdue']}}个</span></span>

    @endsection


