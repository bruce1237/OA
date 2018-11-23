@extends('admin/layout/basic')

@section('shortcut')
    <a href="javascript:void(0);" onclick="">添加菜单</a>
    <a href="javascript:void(0);" onclick="">添加子菜单</a>
@endsection

@section('content')


    <script src="http://apps.bdimg.com/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="http://apps.bdimg.com/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>

    <ul id="sortable">
        <li data-id="1">test 1</li>
        <li data-id="2">test 2</li>
        <li data-id="3">test 3</li>
        <li data-id="4">test 4</li>
        <li data-id="5">test 5</li>
        <li data-id="6">test 6</li>
    </ul>

    <button onclick="abc()">submit</button>





<script>


    $(function() {
        $( "#sortable" ).sortable();
        $( "#sortable" ).disableSelection();
    });

    var sort_define = '';
    var sort_end='';
    $('#sortable').sortable({ start:
            function(event, ui) {
                var sort='';
                $(this).find('li').each(function(){
                    var id = $(this).attr('data-id');
                    if(sort==""){
                        sort = id;
                    }else{
                        if(typeof(id)!='undefined')
                            sort = sort+'_'+id
                    }
                });
                sort_define = sort;
            } });


    $('#sortable').sortable({ stop:
            function(event, ui) {
                var sort='';
                $(this).find('li').each(function(){
                    var id = $(this).attr('data-id');
                    if(sort==""){
                        sort = id;
                    }else{
                        if(typeof(id)!='undefined')
                            sort = sort+'_'+id;
                    }
                });
                sort_end = sort;
            }

    });

    function abc(){

        alert(sort_end);
    }
</script>

@endsection
