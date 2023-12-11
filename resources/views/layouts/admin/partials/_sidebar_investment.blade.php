<div id="sidebarMain" class="d-none">
    <aside class="js-navbar-vertical-aside navbar navbar-vertical-aside navbar-vertical navbar-vertical-fixed navbar-expand-xl navbar-bordered  ">
        <div class="navbar-vertical-container">
            <div class="navbar-brand-wrapper justify-content-between">
                <!-- Logo -->
                @php($store_logo = \App\Models\BusinessSetting::where(['key' => 'logo'])->first()->value)
                <a class="navbar-brand" href="{{ route('admin.dispatch.dashboard') }}" aria-label="Front">
                    <img class="navbar-brand-logo initial--36" onerror="this.src='{{ asset('assets/admin/img/160x160/img2.jpg') }}'" src="{{ asset('storage/app/public/business/' . $store_logo) }}" alt="Logo">
                    <img class="navbar-brand-logo-mini initial--36" onerror="this.src='{{ asset('assets/admin/img/160x160/img2.jpg') }}'" src="{{ asset('storage/app/public/business/' . $store_logo) }}" alt="Logo">
                </a>
                <!-- End Logo -->

                <!-- Navbar Vertical Toggle -->
                <button type="button" class="js-navbar-vertical-aside-toggle-invoker navbar-vertical-aside-toggle btn btn-icon btn-xs btn-ghost-dark">
                    <i class="tio-clear tio-lg"></i>
                </button>
                <!-- End Navbar Vertical Toggle -->

                <div class="navbar-nav-wrap-content-left">
                    <!-- Navbar Vertical Toggle -->
                    <button type="button" class="js-navbar-vertical-aside-toggle-invoker close">
                        <i class="tio-first-page navbar-vertical-aside-toggle-short-align" data-toggle="tooltip"
                        data-placement="right" title="Collapse"></i>
                        <i class="tio-last-page navbar-vertical-aside-toggle-full-align"
                        data-template='<div class="tooltip d-none d-sm-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'></i>
                    </button>
                    <!-- End Navbar Vertical Toggle -->
                </div>

            </div>

            <!-- Content -->
            <div class="navbar-vertical-content bg--005555" id="navbar-vertical-content">
                <form class="sidebar--search-form">
                    <div class="search--form-group">
                        <button type="button" class="btn"><i class="tio-search"></i></button>
                        <input type="text" class="form-control form--control" placeholder="{{ translate('Search Menu...') }}" id="search-sidebar-menu">
                    </div>
                </form>
                <ul class="navbar-nav navbar-nav-lg nav-tabs">
                <!-- Dashboards -->
                <li class="navbar-vertical-aside-has-menu {{ Request::is('admin/investment') ? 'show active' : '' }}">
                    <a class="js-navbar-vertical-aside-menu-link nav-link" href="{{ route('admin.investment.dashboard') }}" title="{{ translate('messages.dashboard') }}">
                        <i class="tio-home-vs-1-outlined nav-icon"></i>
                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                            {{ translate('messages.dashboard') }}
                        </span>
                    </a>
                </li>
                <!-- End Dashboards -->
                <!-- Business Section-->
                <li class="nav-item">
                    <small class="nav-subtitle" title="{{ translate('messages.investment') }} {{ translate('messages.section') }}">{{ translate('messages.investment') }}
                        {{ translate('messages.management') }}</small>
                    <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                </li>

                <!-- dispatch -->
                @if (\App\CentralLogics\Helpers::module_permission_check('order'))
                    <!-- Order dispachment -->
{{--                    <li class="navbar-vertical-aside-has-menu {{ Request::is("admin/investment/*") ? 'active' : '' }}">--}}
{{--                        <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:" title="Investment Packages">--}}
{{--                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">--}}
{{--                                Packages--}}
{{--                            </span>--}}
{{--                        </a>--}}
{{--                        <ul class="js-navbar-vertical-aside-submenu nav nav-sub" style="{{ Request::is('admin/investment*') ? 'display-block' : 'display-none' }}">--}}


                            <li class="nav-item {{ Request::is("admin/investment/flexible") ? 'active' : '' }}">
                                <a class="nav-link " href="{{ route('admin.investment.flexible') }}" title="{{ translate('messages.flexible_packages') }}">
{{--                                    <span class="tio-circle nav-indicator-icon"></span>--}}
                                    <span class="text-truncate sidebar--badge-container">
                                        {{translate('messages.flexible_packages')}}
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item {{ Request::is("admin/investment/locked-in") ? 'active' : '' }}">
                                <a class="nav-link " href="{{ route('admin.investment.locked-in') }}" title="{{ translate('messages.locked_in_packages') }}">
{{--                                    <span class="tio-circle nav-indicator-icon"></span>--}}
                                    <span class="text-truncate sidebar--badge-container">
                                        {{translate('messages.locked_in_packages')}}
                                    </span>
                                </a>
                            </li>


{{--                        </ul>--}}
{{--                    </li>--}}
                    <li class="nav-item {{ Request::is("admin/investment/customer-investments") ? 'active' : '' }}">
                        <a class="nav-link " href="{{ route('admin.investment.customer-investments') }}" title="{{ translate('messages.customer_investments') }}">
                            <span class="text-truncate sidebar--badge-container">
                                {{translate('messages.customer_investments')}}
                            </span>
                        </a>
                    </li>
                    <li class="nav-item {{ Request::is("admin/investment/customers-wallet-balance") ? 'active' : '' }}">
                        <a class="nav-link " href="{{ route('admin.investment.customers-wallet-balance') }}" title="{{ translate('messages.customers_wallet_balance') }}">
                            <span class="text-truncate sidebar--badge-container">
                                {{translate('messages.customers_wallet_balance')}}
                            </span>
                        </a>
                    </li>
                    <li class="nav-item {{ Request::is("admin/investment/investment-withdrawals") ? 'active' : '' }}">
                        <a class="nav-link " href="{{ route('admin.investment.investment-withdrawals') }}" title="{{ translate('messages.investment_withdrawals') }}">
                            <span class="text-truncate sidebar--badge-container">
                                {{translate('messages.investment_withdrawals')}}
                            </span>
                        </a>
                    </li>
                @endif
                <!-- End dispatch -->


                <li class="nav-item py-5">

                </li>

                <li class="__sidebar-hs-unfold px-2">
                    <div class="hs-unfold w-100">
                        <a class="js-hs-unfold-invoker navbar-dropdown-account-wrapper" href="javascript:;"
                            data-hs-unfold-options='{
                                    "target": "#accountNavbarDropdown",
                                    "type": "css-animation"
                                }'>
                            <div class="cmn--media right-dropdown-icon d-flex align-items-center">
                                <div class="avatar avatar-sm avatar-circle">
                                    <img class="avatar-img"
                                        onerror="this.src='{{asset('assets/admin/img/160x160/img1.jpg')}}'"
                                        src="{{asset('storage/app/public/admin')}}/{{auth('admin')->user()->image}}"
                                        alt="Image Description">
                                    <span class="avatar-status avatar-sm-status avatar-status-success"></span>
                                </div>
                                <div class="media-body pl-3">
                                    <span class="card-title h5">
                                        {{auth('admin')->user()->f_name}}
                                        {{auth('admin')->user()->l_name}}
                                    </span>
                                    <span class="card-text">{{auth('admin')->user()->email}}</span>
                                </div>
                            </div>
                        </a>

                        <div id="accountNavbarDropdown"
                                class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-right navbar-dropdown-menu navbar-dropdown-account min--240">
                            <div class="dropdown-item-text">
                                <div class="media align-items-center">
                                    <div class="avatar avatar-sm avatar-circle mr-2">
                                        <img class="avatar-img"
                                                onerror="this.src='{{asset('assets/admin/img/160x160/img1.jpg')}}'"
                                                src="{{asset('storage/app/public/admin')}}/{{auth('admin')->user()->image}}"
                                                alt="Image Description">
                                    </div>
                                    <div class="media-body">
                                        <span class="card-title h5">{{auth('admin')->user()->f_name}}</span>
                                        <span class="card-text">{{auth('admin')->user()->email}}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="dropdown-divider"></div>

                            <a class="dropdown-item" href="{{route('admin.settings')}}">
                                <span class="text-truncate pr-2" title="Settings">{{translate('messages.settings')}}</span>
                            </a>

                            <div class="dropdown-divider"></div>

                            <a class="dropdown-item" href="javascript:" onclick="Swal.fire({
                                title: '{{translate("logout_warning_message")}}',
                                showDenyButton: true,
                                showCancelButton: true,
                                confirmButtonColor: '#FC6A57',
                                cancelButtonColor: '#363636',
                                confirmButtonText: `Yes`,
                                denyButtonText: `Don't Logout`,
                                }).then((result) => {
                                if (result.value) {
                                location.href='{{route('admin.auth.logout')}}';
                                } else{
                                Swal.fire('{{ translate('messages.canceled') }}', '', 'info')
                                }
                                })">
                                <span class="text-truncate pr-2" title="Sign out">{{translate('messages.sign_out')}}</span>
                            </a>
                        </div>
                    </div>
                </li>
                </ul>
            </div>
            <!-- End Content -->
        </div>
    </aside>
</div>

<div id="sidebarCompact" class="d-none">

</div>


@push('script_2')
<script>
    $(window).on('load' , function() {
        if($(".navbar-vertical-content li.active").length) {
            $('.navbar-vertical-content').animate({
                scrollTop: $(".navbar-vertical-content li.active").offset().top - 150
            }, 10);
        }
    });

    var $rows = $('#navbar-vertical-content li');
    $('#search-sidebar-menu').keyup(function() {
        var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

        $rows.show().filter(function() {
            var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
            return !~text.indexOf(val);
        }).hide();
    });
</script>
@endpush
