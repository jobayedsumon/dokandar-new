@extends('layouts.admin.app')

@section('title',translate('messages.flexible'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->

        <!-- End Page Header -->
        <div class="row g-3">
            <div class="col-12">
                <div class="card p-5">
                    <form action="{{ route('admin.investment.flexible.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group" id="name">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('messages.name')}}</label>
                                    <input type="text" name="name" class="form-control" placeholder="{{translate('messages.name')}}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" id="amount">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('messages.amount')}}</label>
                                    <input type="number" name="amount" class="form-control" placeholder="{{translate('messages.amount')}}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" id="monthly_interest_rate">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('messages.monthly_interest_rate')}} (%)</label>
                                    <input type="number" name="monthly_interest_rate" class="form-control" placeholder="{{translate('messages.monthly_interest_rate')}}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" id="status">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('messages.status')}}</label>
                                    <select name="status" class="form-control" required>
                                        <option value="1">{{translate('messages.active')}}</option>
                                        <option value="0">{{translate('messages.inactive')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary">{{translate('messages.submit')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- End Table -->
        </div>
    </div>

@endsection

@push('script_2')

    <script>

    </script>

@endpush
