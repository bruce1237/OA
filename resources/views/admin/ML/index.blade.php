@extends('admin/layout/basic')

@section('shortcut')
    <button class="layui-btn layui-btn layui-btn-xs" onclick="showNewLogoModal()"><i
                class="layui-icon"></i>添加商标
    </button>
    <button class="layui-btn layui-btn-danger layui-btn-xs" onclick="showImportModal()"><i
                class="layui-icon"></i>导入商标
    </button>
    <button class="layui-btn layui-btn layui-btn-xs" onclick="x_admin_show('导入ES引擎','{{url('')}}',600,400)"><i
                class="layui-icon"></i>将商标库导入搜索引擎
    </button>


@endsection
@section('content')