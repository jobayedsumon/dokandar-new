@extends('layouts.admin.app')

@section('title',\App\Models\BusinessSetting::where(['key'=>'business_name'])->first()->value??translate('messages.dashboard'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')

    <div class="content container-fluid">
        <div class="page-header">
            <div class="py-2">
                <div class="d-flex align-items-center">
                    <img src="{{asset('assets/admin/img/new-img/users.svg')}}" alt="img">
                    <div class="w-0 flex-grow pl-3">
                        <h1 class="page-header-title mb-0">{{translate('Investment Overview')}}</h1>
                        <p class="page-header-text m-0">{{translate('Hello, here you can manage your investments.')}}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-1">
            <div class="col-lg-12">
                <div class="row gap__10 __customer-statistics-card-wrap-2">
                    <div class="col-sm-4">
                        <div class="__customer-statistics-card h-100">
                            <div class="title">
                                <img src="{{asset('assets/admin/img/dashboard/food/accepted.svg')}}"
                                     alt="dashboard" class="oder--card-icon">
                                <h4>{{$flexible}}</h4>
                            </div>
                            <h4 class="subtitle text-capitalize">{{translate('messages.flexible_packages')}}</h4>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="__customer-statistics-card h-100">
                            <div class="title">
                                <img src="{{asset('assets/admin/img/dashboard/food/accepted.svg')}}"
                                     alt="dashboard" class="oder--card-icon">
                                <h4>{{$locked_in}}</h4>
                            </div>
                            <h4 class="subtitle text-capitalize">{{translate('messages.locked_in_packages')}}</h4>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="__customer-statistics-card h-100">
                            <div class="title">
                                <img src="{{asset('assets/admin/img/dashboard/food/accepted.svg')}}"
                                     alt="dashboard" class="oder--card-icon">
                                <h4>{{$customers}}</h4>
                            </div>
                            <h4 class="subtitle text-capitalize">{{translate('messages.customers')}}</h4>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="__customer-statistics-card h-100">
                            <div class="title">
                                <img src="{{asset('assets/admin/img/dashboard/food/accepted.svg')}}"
                                     alt="dashboard" class="oder--card-icon">
                                <h4>{{$investments}}</h4>
                            </div>
                            <h4 class="subtitle text-capitalize">{{translate('messages.investments')}}</h4>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="__customer-statistics-card h-100">
                            <div class="title">
                                <img src="{{asset('assets/admin/img/dashboard/food/accepted.svg')}}"
                                     alt="dashboard" class="oder--card-icon">
                                <h4>{{\App\CentralLogics\Helpers::format_currency($invested)}}</h4>
                            </div>
                            <h4 class="subtitle text-capitalize">{{translate('messages.invested')}}</h4>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="__customer-statistics-card h-100">
                            <div class="title">
                                <img src="{{asset('assets/admin/img/dashboard/food/accepted.svg')}}"
                                     alt="dashboard" class="oder--card-icon">
                                <h4>{{\App\CentralLogics\Helpers::format_currency($profit)}}</h4>
                            </div>
                            <h4 class="subtitle text-capitalize">{{translate('messages.Profit')}}</h4>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="__customer-statistics-card h-100">
                            <div class="title">
                                <img src="{{asset('assets/admin/img/dashboard/food/accepted.svg')}}"
                                     alt="dashboard" class="oder--card-icon">
                                <h4>{{$withdrawals}}</h4>
                            </div>
                            <h4 class="subtitle text-capitalize">{{translate('messages.withdrawals')}}</h4>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="__customer-statistics-card h-100">
                            <div class="title">
                                <img src="{{asset('assets/admin/img/dashboard/food/accepted.svg')}}"
                                     alt="dashboard" class="oder--card-icon">
                                <h4>{{\App\CentralLogics\Helpers::format_currency($withdrawn)}}</h4>
                            </div>
                            <h4 class="subtitle text-capitalize">{{translate('messages.withdrawn')}}</h4>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('script_2')

    <script>

    </script>

@endpush
