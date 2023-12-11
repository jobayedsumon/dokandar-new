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
                                {{translate('messages.total')}} {{translate('messages.customer_investments')}}<span class="badge badge-soft-dark ml-2" id="itemCount">{{count($investments)}}</span>
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
                                <th class="border-0">{{translate('messages.Investment Package')}}</th>
                                <th class="border-0">{{translate('messages.Amount')}}</th>
                                <th class="border-0">{{translate('messages.Monthly Interest Rate')}}</th>
                                <th class="border-0">{{translate('messages.Duration In Months')}}</th>
                                <th class="border-0">{{translate('messages.Invested On')}}</th>
                                <th class="border-0">{{translate('messages.Redeemed On')}}</th>
                                <th class="border-0">{{translate('messages.Profit Earned')}}</th>
                            </tr>

                            </thead>

                            <tbody id="set-rows">
                            @forelse($investments as $investment)
                                <tr>
                                    <td class="text-center">
                                        <span class="mr-3">
                                            {{$loop->index+1}}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-body mr-3">
                                            {{Str::limit($investment->customer->f_name.' '.$investment->customer->l_name, 50, '...')}}
                                            <br>
                                            {{ $investment->customer->phone }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-body mr-3">
                                            {{Str::limit($investment->package->name, 50, '...')}}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-center">
                                            {{\App\CentralLogics\Helpers::format_currency($investment->package->amount)}}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-center">
                                            {{$investment->package->monthly_interest_rate}}%
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-center">
                                            {{$investment->package->duration_in_months ?? 'N/A'}}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-center">
                                            {{$investment->created_at->format('d M, Y')}}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-center">
                                            {{$investment->redeemed_at ? \Carbon\Carbon::parse($investment->redeemed_at)->format('d M, Y') : 'Ongoing'}}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-center">
                                            {{\App\CentralLogics\Helpers::format_currency($investment->profit_earned)}}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                            </tbody>
                        </table>
                        @if(count($investments) !== 0)
                            <hr>
                        @endif
                        <div class="page-area">
                            {!! $investments->links() !!}
                        </div>
                        @if(count($investments) === 0)
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
