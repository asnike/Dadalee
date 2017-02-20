<nav class="navbar navbar-default navbar-fixed-top" role="navigation">


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

        <div class="clearfix" style="margin-right: 10px;">
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
                    <li><a href="/actualprices">{{ trans('common.actualPrice') }}</a></li>

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
        <div class="navbar-sub">
            <div class="custom-control-panel">
                <div class="col-md-6">

                    <form action="" class="search-form" onsubmit="return Controller.onSubmit();" style="margin: 0;">
                        <div class="fomr-group clearfix">
                            <div class="col-md-2"><input type="text" required class="form-control input-sm" id="name" placeholder="{{ trans('common.name') }}" maxlength="50" /></div>
                            <div class="col-md-4"><input type="text" required class="form-control input-sm" id="addr" readonly onfocus="daumAddressOpen()" placeholder="{{ trans('common.address') }}" maxlength="255" /></div>
                            <div class="col-md-2">
                                <div class="checkbox">
                                    <input id="own" class="styled" type="checkbox">
                                    <label for="own">
                                        {{ trans('common.own') }}
                                    </label>
                                </div>

                            </div>
                            <div class="col-md-2"><button type="submit" class="btn btn-primary btn-sm btn-block">{{ trans('common.realestateAdd') }}</button></div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</nav>