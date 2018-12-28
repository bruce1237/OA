<div class="left-nav"
     @if (!sizeof($menuList))
     style="left: -221px;"
        @endif>
    <div id="side-nav">
        <ul id="nav">


            @foreach($menuList as $menu)

                <li>
                    <a href="javascript:;">
                        <i class="iconfont">&#xe6b8;</i>
                        <cite>{{$menu->menu_name}}</cite>
                        <i class="iconfont nav_right">&#xe697;</i>
                    </a>

                    @if(sizeof($menu[$menu->menu_name]))
                        @foreach($menu[$menu->menu_name] as $submenu)
                            <ul class="sub-menu">
                                <li>

                                    <a _href='{{url("")}}/{{$submenu->submenu_url}}'>
                                        <i class="iconfont">&#xe6a7;</i>
                                        <cite>{{$submenu->submenu_name}}</cite>
                                    </a>
                                </li>
                            </ul>
                        @endforeach

                    @endif

                </li>

            @endforeach


            {{--<li>--}}
                {{--<a href="javascript:;">--}}
                    {{--<i class="iconfont">&#xe6b8;</i>--}}
                    {{--<cite>人力资源</cite>--}}
                    {{--<i class="iconfont nav_right">&#xe697;</i>--}}
                {{--</a>--}}
                {{--<ul class="sub-menu">--}}
                    {{--<li>--}}
                        {{--<a _href="{{url('admin/hr')}}">--}}
                            {{--<i class="iconfont">&#xe6a7;</i>--}}
                            {{--<cite>员工列表</cite>--}}
                        {{--</a>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                        {{--<a _href="{{url('admin/hr')}}">--}}
                            {{--<i class="iconfont">&#xe6a7;</i>--}}
                            {{--<cite>人才智库</cite>--}}
                        {{--</a>--}}
                    {{--</li>--}}
                {{--</ul>--}}
            {{--</li>--}}


            {{--<li>--}}
                {{--<a href="javascript:;">--}}
                    {{--<i class="iconfont">&#xe723;</i>--}}
                    {{--<cite>OA管理</cite>--}}
                    {{--<i class="iconfont nav_right">&#xe697;</i>--}}
                {{--</a>--}}
                {{--<ul class="sub-menu">--}}
                    {{--<li>--}}
                        {{--<a _href="{{url('admin/OAMenu')}}">--}}
                            {{--<i class="iconfont">&#xe6a7;</i>--}}
                            {{--<cite>菜单管理</cite>--}}
                        {{--</a>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                        {{--<a _href="{{url('admin/OAMenu')}}">--}}
                            {{--<i class="iconfont">&#xe6a7;</i>--}}
                            {{--<cite>权限管理</cite>--}}
                        {{--</a>--}}
                    {{--</li>--}}
                {{--</ul>--}}
            {{--</li>--}}


        </ul>
    </div>
</div>