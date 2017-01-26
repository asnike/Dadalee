<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <title>Dadalee Admin</title>

  <link href="{{ elixir("css/admin.css") }}" rel="stylesheet">
  @yield('style')

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="//oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="//oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>

<body>
  @include('admin.layouts.partial.navigation')
  @include('layouts.partial.flash_message')
  <div class="container-fluid contents">

    <div class="row">
      @if(! auth()->check())
        <div class="col-sm-12 col-md-12 main">
          @yield('content')
        </div>
      @else
        <div class="col-sm-3 col-md-2 sidebar">
            <ul class="nav nav-sidebar">
                <li class="active"><a href="{{ route('admin.users.index') }}">{{ trans('common.manageMember') }}</a></li>
                <li><a href="{{ route('admin.prices.index') }}">{{ trans('common.manageData') }}</a></li>

            </ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main" style="min-height: 700px;">
          @yield('content')
        </div>
      @endif
    </div>
  </div>

  @include('admin.layouts.partial.footer')

  <script src="{{ elixir("js/app.js") }}"></script>
  @include('layouts.partial.modal')
  @yield('script')
</body>

</html>