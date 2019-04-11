@extends('admin/layout/basic')

@section('shortcut')

@endsection

@section('content')

    <div class="row">
        <div class="col-6">contract




            <div class="input-group input-group-sm">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="inputGroupFile04">
                    <label class="custom-file-label" for="inputGroupFile04">选择合同模板</label>
                </div>
                <input type="text" class="form-control" placeholder="合同名称" aria-label="Recipient's username" aria-describedby="basic-addon2">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button">Button</button>
                </div>
            </div>





        </div>
        <div class="col-6">service

        </div>
    </div>


@endsection
