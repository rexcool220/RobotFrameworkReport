@extends('admin.layouts.app')

@section('body')
    <div id="wrapper">
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li {{ (Request::is('*tables') ? 'class="active"' : '') }}>
                            <a href="{{ url ('suite') }}"><i class="fa fa-table fa-fw"></i> 測試結果列表</a>
                        </li>
                        <li {{ (Request::is('/') ? 'class="active"' : '') }}>
                            <a href="{{ url ('uploadFile') }}"><i class="fa fa-upload fa-fw"></i> 上傳資料</a>
                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">@yield('page_heading')</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <div class="row">
                @yield('section')
            </div>
            <!-- /#page-wrapper -->
        </div>
    </div>
@endsection
