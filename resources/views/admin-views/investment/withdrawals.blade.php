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
                                {{translate('messages.Investment')}} {{translate('messages.withdrawals_requests')}}<span class="badge badge-soft-dark ml-2" id="itemCount">{{count($withdrawals)}}</span>
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
                                <th class="border-0">{{translate('messages.Withdrawal Amount')}}</th>
                                <th class="border-0">{{translate('messages.Withdrawal Method')}}</th>
                                <th class="border-0">{{translate('messages.Requested On')}}</th>
                                <th class="border-0">{{translate('messages.Paid On')}}</th>
                                <th class="border-0">{{translate('messages.Action')}}</th>
                            </tr>

                            </thead>

                            <tbody id="set-rows">
                            @forelse($withdrawals as $withdrawal)
                                <tr>
                                    <td class="text-center">
                                        <span class="mr-3">
                                            {{$loop->index+1}}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-body mr-3">
                                            {{Str::limit($withdrawal->customer->f_name.' '.$withdrawal->customer->l_name, 50, '...')}}
                                            <br>
                                            {{ $withdrawal->customer->phone }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="mr-3 {{ $withdrawal->customer->investment_wallet->balance < $withdrawal->withdrawal_amount && !$withdrawal->paid_at ? 'text-danger' : '' }}">
                                            {{\App\CentralLogics\Helpers::format_currency($withdrawal->withdrawal_amount)}}
                                        </span>
                                    </td>
                                    <td class="text-left">
                                        <span class="text-body mr-3">
                                            Method Type: <span class="text-capitalize">{{ $withdrawal->method_details->method_type }}</span>
                                            <br>
                                            @if($withdrawal->method_details->method_type === 'bank')
                                                Bank Name: {{ $withdrawal->method_details->bank_name }}
                                                <br>
                                                Account Number: {{ $withdrawal->method_details->account_number }}
                                                <br>
                                                Account Name: {{ $withdrawal->method_details->account_name }}
                                                <br>
                                                Branch Name: {{ $withdrawal->method_details->branch_name }}
                                                <br>
                                                Routing Number: {{ $withdrawal->method_details->routing_number }}
                                            @else
                                                Mobile Number: {{ $withdrawal->method_details->mobile_number }}
                                            @endif
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-center">
                                            {{$withdrawal->created_at->format('d M, Y')}}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-center">
                                            {{$withdrawal->paid_at ? \Carbon\Carbon::parse($withdrawal->paid_at)->format('d M, Y') : 'Pending'}}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($withdrawal->paid_at)
                                            <span>Paid</span>
                                        @elseif($withdrawal->customer->investment_wallet->balance < $withdrawal->withdrawal_amount)
                                            <span class="text-danger">Insufficient Balance</span>
                                        @else
                                            <a class="btn btn-success btn-outline-success" href="javascript:" onclick="form_alert('withdrawal-{{$withdrawal->id}}','{{ translate('Want to pay this request ?') }}')" title="{{translate('messages.Mark As Paid')}}">
                                                <i class="tio-checkmark-circle"></i>
                                                <span>Pay</span>
                                            </a>
                                            <form action="{{route('admin.investment.withdrawal-pay',[$withdrawal->id])}}"
                                                  method="post" id="withdrawal-{{$withdrawal->id}}">
                                                @csrf
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                            </tbody>
                        </table>
                        @if(count($withdrawals) !== 0)
                            <hr>
                        @endif
                        <div class="page-area">
                            {!! $withdrawals->links() !!}
                        </div>
                        @if(count($withdrawals) === 0)
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
