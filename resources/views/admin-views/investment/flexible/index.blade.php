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
                <div class="card">
                    <div class="card-header py-2 border-0">
                        <div class="search--button-wrapper">
                            <h5 class="card-title">
                                {{translate('messages.flexible')}} {{translate('messages.packages')}}<span class="badge badge-soft-dark ml-2" id="itemCount">{{count($packages)}}</span>
                            </h5>
                            <a href="{{route('admin.investment.flexible.create')}}" class="btn btn-sm btn-primary px-3" title="{{translate('messages.add')}} {{translate('messages.package')}}"><i class="tio-add-circle"></i>
                                {{translate('messages.add')}} {{translate('messages.package')}}
                            </a>
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
                                <th class="border-0">{{translate('messages.name')}}</th>
                                <th class="border-0">{{translate('messages.Amount')}}</th>
                                <th class="border-0">{{translate('messages.Monthly Interest Rate')}}</th>
                                <th class="border-0">{{translate('messages.Status')}}</th>
                                <th class="border-0">{{translate('messages.Actions')}}</th>
                            </tr>

                            </thead>

                            <tbody id="set-rows">
                            @forelse($packages as $package)
                                <tr>
                                    <td class="text-center">
                                        <span class="mr-3">
                                            {{$loop->index+1}}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-body mr-3">
                                            {{Str::limit($package->name,50,'...')}}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-body mr-3">
                                            {{\App\CentralLogics\Helpers::format_currency($package->amount)}}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-center">
                                            {{$package->monthly_interest_rate}}%
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-center">
                                            {{$package->status ? 'Active' : 'Inactive'}}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn--container justify-content-center">
                                            <a class="btn action-btn btn--primary btn-outline-primary" href="{{route('admin.investment.flexible.edit',[$package->id])}}" title="{{translate('messages.edit')}}"><i class="tio-edit"></i>
                                            </a>
                                            <a class="btn action-btn btn--danger btn-outline-danger" href="javascript:" onclick="form_alert('flexible-{{$package->id}}','{{ translate('Want to delete this package ?') }}')" title="{{translate('messages.delete')}}"><i class="tio-delete-outlined"></i>
                                            </a>
                                            <form action="{{route('admin.investment.flexible.delete',[$package->id])}}"
                                                  method="post" id="flexible-{{$package->id}}">
                                                @csrf @method('delete')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                            </tbody>
                        </table>
                        @if(count($packages) !== 0)
                            <hr>
                        @endif
                        <div class="page-area">
                            {!! $packages->links() !!}
                        </div>
                        @if(count($packages) === 0)
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
