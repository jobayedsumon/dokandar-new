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
            <div class="col-lg-8">
                <div class="row gap__10 __customer-statistics-card-wrap-2">
                    <div class="col-sm-6">
                        <div class="__customer-statistics-card h-100">
                            <div class="title">
                                <img src="{{asset('assets/admin/img/new-img/deliveryman/active.svg')}}" alt="new-img">
                                <h4>{{$active_deliveryman}}</h4>
                            </div>
                            <h4 class="subtitle text-capitalize">{{translate('messages.active_delivery_man')}}</h4>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="__customer-statistics-card h-100" style="--clr:#006AB4">
                            <div class="title">
                                <img src="{{asset('assets/admin/img/new-img/deliveryman/newly.svg')}}" alt="new-img">
                                <h4>{{$unavailable_deliveryman}}</h4>
                            </div>
                            <h4 class="subtitle text-capitalize">{{translate('Available to assign more order')}}</h4>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="__customer-statistics-card h-100">
                            <div class="title">
                                <img src="{{asset('assets/admin/img/new-img/deliveryman/active.svg')}}" alt="new-img">
                                <h4>{{ $unavailable_deliveryman }}</h4>
                            </div>
                            <h4 class="subtitle text-capitalize">{{ translate('Fully Booked Delivery Man')}}</h4>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="__customer-statistics-card h-100" style="--clr:#FF5A54">
                            <div class="title">
                                <img src="{{asset('assets/admin/img/new-img/deliveryman/in-active.svg')}}" alt="new-img">
                                <h4>{{$inactive_deliveryman}}</h4>
                            </div>
                            <h4 class="subtitle text-capitalize">{{translate('messages.inactive_deliveryman')}}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="shadow--order-card">
                    <div class="row m-0">
                        <div class="col-12 p-0">
                            <a class="order--card h-100" href="#">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                                        <img src="{{asset('assets/admin/img/dashboard/food/unassigned.svg')}}"
                                             alt="dashboard" class="oder--card-icon">
                                        <span>{{translate('messages.unassigned_orders')}}</span>
                                    </h6>
                                    <span class="card-title text-3F8CE8 ">
{{--                                        {{$data['searching_for_dm']}}--}}
                                    </span>
                                </div>
                            </a>
                        </div>
                        <div class="col-12 p-0">
                            <a class="order--card h-100" href="#">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                                        <img src="{{asset('assets/admin/img/dashboard/food/accepted.svg')}}"
                                             alt="dashboard" class="oder--card-icon">
                                        <span>{{translate('Accepted by Delivery Man')}}</span>
                                    </h6>
                                    <span class="card-title text-success">
{{--                                        {{$data['accepted_by_dm']}}--}}
                                    </span>
                                </div>
                            </a>
                        </div>
                        <div class="col-12 p-0">
                            <a class="order--card h-100" href="#">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                                        <img src="{{asset('assets/admin/img/dashboard/food/out-for.svg')}}"
                                             alt="dashboard" class="oder--card-icon">
                                        <span>{{translate('Out for Delivery')}}</span>
                                    </h6>
                                    <span class="card-title text-success">
{{--                                        {{$data['picked_up']}}--}}
                                    </span>
                                </div>
                            </a>
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
