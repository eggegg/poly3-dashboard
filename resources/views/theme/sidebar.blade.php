<div class="navbar-default sidebar" role="navigation">

    <div class="sidebar-nav navbar-collapse">

        <ul class="nav" id="side-menu">



            <li>

                <a href="{{ url('/my-home') }}"><i class="fa fa-dashboard fa-fw"></i> 首页</a>

            </li>

            @if (Auth::check())


                <li>
                <a href="#"><i class="fa fa-bar-chart-o fa-fw"></i> 用户管理<span class="fa arrow"></span></a>

                <ul class="nav nav-second-level">

                    @can('view_users')
                        <li>
                            <a class="{{ Request::is('users*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                                <span class="text-info glyphicon glyphicon-user"></span> User
                            </a>
                        </li>
                    @endcan

                    @can('view_roles')
                        <li>
                            <a class="{{ Request::is('roles*') ? 'active' : '' }}" href="{{ route('roles.index') }}">
                                <span class="text-danger glyphicon glyphicon-lock"></span> Role
                            </a>
                        </li>
                    @endcan

                </ul>

                <!-- /.nav-second-level -->
            </li>



            <li>

                <a href="#"><i class="fa fa-files-o fa-fw"></i> 内容管理<span class="fa arrow"></span></a>

                <ul class="nav nav-second-level">

                    @can('view_posts')
                        <li>
                            <a class="{{ Request::is('posts*') ? 'active' : '' }}" href="{{ route('posts.index') }}">
                                <span class="text-success glyphicon glyphicon-text-background"></span> Posts
                            </a>
                        </li>
                    @endcan


                </ul>

                <!-- /.nav-second-level -->

            </li>


            @endif

        </ul>

    </div>

    <!-- /.sidebar-collapse -->

</div>

<!-- /.navbar-static-side -->