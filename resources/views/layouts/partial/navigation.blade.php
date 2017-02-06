<nav class="navbar navbar-default navbar-fixed-top" role="navigation">

  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
        <span class="sr-only">Toggle Navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>

      <a href="{{ route('home') }}" class="navbar-brand" style="padding:4px;">
        <span class="logo-txt">Dadalee</span>
      </a>
    </div>

    <div class="collapse navbar-collapse navbar-responsive-collapse">
      <ul class="nav navbar-nav navbar-right">
        @if(! auth()->check())
          <li>
            <a href="{{ route('session.create') }}"><strong>{{ trans('common.login') }}</strong></a>
          </li>
          <li>
            <a href="{{ route('user.create') }}"><strong>{{ trans('common.signup') }}</strong></a>
          </li>
        @else

          <li><a href="/realestates">{{ trans('common.manageRealestate') }}</a></li>
          <li><a href="/pricetags">{{ trans('common.priceMap') }}</a></li>

          <li>
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-user icon"></i> {{ auth()->user()->name }} <b class="caret"></b>
            </a>
            <ul class="dropdown-menu">
              <li><a href="{{ route('session.destroy') }}"><i class="fa fa-sign-out icon"></i> {{ trans('common.logout') }}</a></li>
            </ul>
          </li>
        @endif
      </ul>
    </div>
  </div>
</nav>