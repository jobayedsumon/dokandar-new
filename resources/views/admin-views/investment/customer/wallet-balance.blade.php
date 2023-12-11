@extends('layouts.admin.app')

@section('title',translate('messages.customer_investments'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->

        <!-- End Page Header -->
        <div class="row g-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header py-2 border-0">
                        <div class="search--button-wrapper">
                            <h5 class="card-title">
                                {{translate('messages.customers')}} {{translate('messages.wallet_balance')}}<span class="badge badge-soft-dark ml-2" id="itemCount">{{count($customer_data)}}</span>
                            </h5>
                            <!-- Unfold -->
                            <!-- End Unfold -->
                        </div>
                    </div>
                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table id="columnSearchDatatable"
                               class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                               data-hs-datatables-options='{
                                 "order": [],
                                 "orderCellsTop": true,
                                 "paging":false
                               }'>
                            <thead class="thead-light">
                            <tr class="text-center">
                                <th class="border-0">{{translate('SL')}}</th>
                                <th class="border-0">{{translate('messages.Customer Name')}}</th>
                                <th class="border-0">{{translate('messages.Total Profit Earned')}}</th>
                                <th class="border-0">{{translate('messages.Total Redeemed Investments')}}</th>
                                <th class="border-0">{{translate('messages.Total Withdrawal')}}</th>
                                <th class="border-0">{{translate('messages.Wallet Balance')}}</th>
                            </tr>

                            </thead>

                            <tbody id="set-rows">
                            @forelse($customer_data as $data)
                                <tr>
                                    <td class="text-center">
                                        <span class="mr-3">
                                            {{$loop->index+1}}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-body mr-3">
                                            {{Str::limit($data->f_name.' '.$data->l_name, 50, '...')}}
                                            <br>
                                            {{ $data->phone }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-center">
                                            {{\App\CentralLogics\Helpers::format_currency($data->investment_wallet->profit)}}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-center">
                                            {{\App\CentralLogics\Helpers::format_currency($data->investment_wallet->redeemed)}}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-center">
                                            {{\App\CentralLogics\Helpers::format_currency($data->investment_wallet->withdrawal)}}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-center">
                                            {{\App\CentralLogics\Helpers::format_currency($data->investment_wallet->balance)}}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                            </tbody>
                        </table>
                        @if(count($customer_data) !== 0)
                            <hr>
                        @endif
                        <div class="page-area">
                            {!! $customer_data->links() !!}
                        </div>
                        @if(count($customer_data) === 0)
                            <div class="empty--data">
                                <img src="{{asset('assets/admin/svg/illustrations/sorry.svg')}}" alt="public">
                                <h5>
                                    {{translate('no_data_found')}}
                                </h5>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <!-- End Table -->
        </div>
    </div>

@endsection

@push('script_2')

    <script>
        $(document).on('ready', function () {
            // INITIALIZATION OF DATATABLES
            // =======================================================
            var datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

            $('#column1_search').on('keyup', function () {
                datatable
                    .columns(1)
                    .search(this.value)
                    .draw();
            });


            $('#column3_search').on('change', function () {
                datatable
                    .columns(2)
                    .search(this.value)
                    .draw();
            });


            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>

@endpush
