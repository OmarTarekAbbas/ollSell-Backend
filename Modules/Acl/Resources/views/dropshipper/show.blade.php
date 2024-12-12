@extends($layout)


@section('title', 'Dropshipper Details')
@push('styles')
<style>
    .nav-line-tabs .nav-item:last-child .nav-link {
        cursor: default;
    }
</style>
@endpush
@section('content')

<!--begin::Layout-->
<div class="d-flex flex-column flex-xl-row">
    <!--begin::Sidebar-->
    <div class="flex-column flex-lg-row-auto w-100 w-xl-350px mb-10">
        <!--begin::Card-->
        <div class="card mb-5 mb-xl-8">
            <!--begin::Card body-->
            <div class="card-body pt-15">
                <!--begin::Summary-->
                <div class="d-flex flex-center flex-column mb-5">
                    <!--begin::Avatar-->
                    <div class="symbol symbol-100px symbol-circle mb-7">
                        <img src="https://e7.pngegg.com/pngimages/299/582/png-clipart-drop-shipping-vendor-logo-magento-management-cargo-freight-text-service.png"
                            alt="image" />
                    </div>
                    <!--end::Avatar-->
                    <!--begin::Name-->
                    <span tabindex="-1" aria-disabled="true"
                        class="pe-none fs-3 text-gray-800 text-hover-primary fw-bold mb-1">{{ $data->store_name }}</span>
                    <!--end::Name-->
                    <!--begin::Position-->
                    <div class=" fs-5 fw-semibold text-muted mb-6">{{ $data->merchant_name }}</div>
                    <!--end::Position-->
                </div>
                <!--end::Summary-->
                <!--begin::Details toggle-->
                <div class="d-flex flex-stack fs-4 py-3">
                    <div class="fw-bold rotate collapsible" data-bs-toggle="collapse" href="#kt_customer_view_details"
                        role="button" aria-expanded="false" aria-controls="kt_customer_view_details">Details
                        <span class="ms-2 rotate-180">
                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
                            <span class="svg-icon svg-icon-3">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                                        fill="currentColor" />
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                    </div>
                </div>
                <!--end::Details toggle-->
                <div class="separator separator-dashed my-3"></div>
                <!--begin::Details content-->
                <div id="kt_customer_view_details" class="collapse show">
                    <div class="py-5 fs-6">
                        <!--begin::Details item-->
                        <div class="fw-bold mt-5">Phone</div>
                        <div class="text-gray-600">{{ $data->phone }}</div>
                        <!--begin::Details item-->
                        <!--begin::Details item-->
                        <div class="fw-bold mt-5">Email</div>
                        <div class="text-gray-600">
                            <a href="#" class="text-gray-600 text-hover-primary">{{ $data->email }}</a>
                        </div>
                        <!--begin::Details item-->
                        <!--begin::Details item-->
                        <div class="fw-bold mt-5">Last Transaction</div>

                        <div class="text-gray-600">
                            {{ $transactions && count($transactions) > 0 ? '#' . $transactions->first()->id : 'There is no transactions' }}
                        </div>
                        <!--begin::Details item-->
                        <div class="fw-bold mt-5">Total Profits</div>

                        <div class="text-gray-600">
                            {{ $data->profitBalance }}
                        </div>
                        <div class="fw-bold mt-5">Wallet</div>

                        <div class="text-gray-600">
                            {{ $data->walletBalance }}
                        </div>
                        <div class="fw-bold mt-5">Available Profit</div>

                        <div class="text-gray-600">
                            {{ $data->earningsWithdrawal }}
                        </div>
                        <div class="fw-bold mt-5">Pending Profits</div>

                        <div class="text-gray-600">
                            {{ $data->transaction->where('isStatus', \Modules\Finance\Enums\ProfitEnum::PENDING)->sum('profitRatio') }}
                        </div>
                    </div>
                </div>
                <!--end::Details content-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>
    <!--end::Sidebar-->
    <!--begin::Content-->
    <div class="flex-lg-row-fluid ms-lg-15">
        <!--begin:::Tabs-->
        <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-8">

            <!--begin:::Tab item-->
            <li class="nav-item">
                <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab"
                    href="#kt_customer_view_overview_tab">Transactions</a>
            </li>
            <!--end:::Tab item-->

            <!--begin:::Tab item-->
            @permission('update_dropshipper')
            <li class="nav-item">
                <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#addItem">Add items Feature</a>
            </li>
            @endpermission
            <!--end:::Tab item-->

            <!--begin:::Tab item-->
            <li class="nav-item">
                <a class="nav-link text-active-primary pb-4 " data-bs-toggle="tab" href="#orders">Orders</a>
            </li>
            <!--end:::Tab item-->
            <!--begin:::Tab item-->
            <li class="nav-item">
                <a class="nav-link text-active-primary pb-4 " data-bs-toggle="tab" href="#branches">Branches</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-primary pb-4 " data-bs-toggle="tab" href="#option">Options</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-primary pb-4 " data-bs-toggle="tab" href="#discount">Discount</a>
            </li>


            <!--end:::Tab item-->
        </ul>
        <!--end:::Tabs-->
        <!--begin:::Tab content-->
        <div class="tab-content" id="myTabContent">
            <!--begin:::Tab pane-->
            <div class="tab-pane fade show active" id="kt_customer_view_overview_tab" role="tabpanel">
                <!--begin::Card-->
                <div class="card pt-4 mb-6 mb-xl-9">
                    <!--begin::Card header-->
                    <div class="card-header border-0">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <h2>Transactions</h2>
                        </div>
                        <!--end::Card title-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0 pb-5" id="transactions_table">
                        <!--begin::Table-->
                        <table class="table align-middle table-row-dashed gy-5" id="kt_table_customers_payment">
                            <!--begin::Table head-->
                            <thead class="border-bottom border-gray-200 fs-7 fw-bold">
                                <!--begin::Table row-->
                                <tr class="text-start text-muted text-uppercase gs-0">
                                    <th class="min-w-100px">Transaction ID</th>
                                    <th>order id</th>
                                    <th>Is Status</th>
                                    {{-- <th>payment Method</th> --}}
                                    <th>Total Order</th>
                                    {{-- <th>Cost Price</th>
                                        <th>Selling Price</th> --}}
                                    <th>Profit Ratio</th>
                                    <th class="min-w-100px">Date</th>
                                </tr>
                                <!--end::Table row-->
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody class="fs-6 fw-semibold text-gray-600">
                                <!--begin::Table row-->
                                @forelse ($transactions as $transaction)
                                <tr>
                                    <!--begin::Invoice=-->
                                    <td>
                                        <span

                                            class="text-gray-600 text-hover-primary mb-1">#{{ $transaction->id }}</span>
                                    </td>
                                    <td>{{ $transaction->order_id }}</td>
                                    <td> {{Modules\Finance\Enums\ProfitEnum::status($transaction->isStatus,$transaction)}}</td>
                                    {{-- <td>
                                              @if ( $transaction->paymentMethod == 1)  Online @endif
                                                @if ( $transaction->paymentMethod == 2) Cash on delivery @endif
                                                @if ( $transaction->paymentMethod == 3)  Wallet @endif
                                        
                                            </td> --}}

                                    <td>{{ $transaction->totalOrder }}SAR</td>
                                    {{-- <td>{{ $transaction->costPrice }}SAR</td>
                                    <td>{{ $transaction->sellingPrice }}SAR</td> --}}
                                    <td>{{ $transaction->profitRatio }}SAR</td>
                                    <td>{{ $transaction->created_at->format('d/m/Y') }}</td>

                                </tr>
                                <!--end::Table row-->
                                @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="alert-alert-danger">
                                            No transactions yet.
                                        </div>
                                    </td>
                                </tr>
                                @endforelse

                            </tbody>
                            <!--end::Table body-->
                        </table>
                        <!--end::Table-->
                        <div class="transactions">
                            {{ $transactions->links() }}
                        </div>
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
            </div>
            <!--end:::Tab pane-->

            @permission('update_dropshipper')
            <!--begin:::Tab pane for Add Item-->
            <div class="tab-pane fade " id="addItem" role="tabpanel">
                <!--begin::Card-->
                <div class="card pt-4 mb-6 mb-xl-9">
                    <!--begin::Card header-->
                    <div class="card-header border-0">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <h2>Add Item</h2>
                        </div>
                        <!--end::Card title-->
                    </div>
                    <!--end::Card header-->

                    <!--begin::Card body-->
                    <div class="card-body pt-0 pb-5">
                        <form action="{{ url('acl/dropshipper/update-feature-form', $data->id) }}"
                            method="POST">
                            @csrf

                            <!--begin::Row for Enable Feature Toggle-->
                            <div class="row mb-6">
                                <label class="col-lg-3 col-form-label fw-semibold fs-6">Enable Feature</label>
                                <div class="col-lg-9">
                                    <div class="form-check form-switch form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" id="featureToggle"
                                            onchange="togglePercentageInput()"
                                            {{ $data->extra_product_feature_enabled ? 'checked' : '' }}>
                                        <label class="form-check-label" for="featureToggle">
                                            Enable Adding Item Percentage
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <!--end::Row-->

                            <!--begin::Row for Percentage Input-->
                            <div class="row mb-6" id="percentageRow"
                                style="{{ $data->extra_product_feature_enabled ? '' : 'display: none;' }}">
                                <label class="col-lg-3 col-form-label required fw-semibold fs-6">Percentage</label>
                                <div class="col-lg-9">
                                    <input type="number" name="percentage"
                                        class="form-control form-control-lg form-control-solid"
                                        placeholder="Enter Discount Percentage" min="1" max="100"
                                        value="{{ $data->product_price_percentage }}">
                                    <small class="form-text text-muted">Enter a percentage (0-100).</small>
                                </div>
                            </div>
                            <!--end::Row-->

                            <!--begin::Row for Submit-->
                            <div class="row mb-6">
                                <div class="col-lg-9 offset-lg-3">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                            <!--end::Row-->
                        </form>



                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
            </div>
            <!--end:::Tab pane-->

            <script>
                // Initialize the toggle percentage input visibility based on the checkbox state
                function togglePercentageInput() {
                    const toggle = document.getElementById('featureToggle');
                    const percentageRow = document.getElementById('percentageRow');

                    // Show or hide the percentage input based on the toggle state
                    if (toggle.checked) {
                        percentageRow.style.display = 'block'; // Show the percentage input
                    } else {
                        percentageRow.style.display = 'none'; // Hide the percentage input
                        percentageRow.querySelector('input').value = ''; // Clear the input value
                    }
                }

                // Call the toggle function on page load to set the correct visibility
                document.addEventListener("DOMContentLoaded", function() {
                    togglePercentageInput(); // Set the initial visibility based on the checkbox state
                });
            </script>
            @endpermission

            <!--begin:::Tab pane-->
            <div class="tab-pane fade " id="orders" role="tabpanel">
                <!--begin::Card-->
                <div class="card pt-4 mb-6 mb-xl-9">
                    <!--begin::Card header-->
                    <div class="card-header border-0">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <h2>Orders</h2>
                        </div>
                        <!--end::Card title-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0 pb-5" id="orders_table">
                        <!--begin::Table-->
                        <table class="table align-middle table-row-dashed gy-5" id="kt_table_customers_payment">
                            <!--begin::Table head-->
                            <thead class="border-bottom border-gray-200 fs-7 fw-bold">
                                <!--begin::Table row-->
                                <tr class="text-start text-muted text-uppercase gs-0">
                                    <th>ID</th>
                                    <th>Status</th>
                                    <th>Amount</th>
                                    <th>Quantity</th>
                                    <th>Customer Name</th>
                                    <th>Shipping Fees</th>
                                    <th>Date</th>
                                    <th>Action</th>

                                </tr>
                                <!--end::Table row-->
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody class="fs-6 fw-semibold text-gray-600">
                                <!--begin::Table row-->
                                @forelse ($orders as $order)
                                <tr>
                                    <!--begin::Invoice=-->
                                    <td>
                                        <span
                                            class="text-gray-600 text-hover-primary mb-1">#{{ $order->id }}</span>
                                    </td>
                                    <!--end::Invoice=-->
                                    <!--begin::Status=-->
                                    <td>
                                        <span
                                            class="badge badge-light-success">{{ ucfirst($order->orderStatus()->latest()->first()->status->name->value) }}</span>
                                    </td>
                                    <!--end::Status=-->
                                    <!--begin::Amount=-->
                                    <td>{{ $order->subTotal }}SAR</td>
                                    <!--end::Amount=-->
                                    <!--begin::Quantity=-->
                                    <td>{{ $order->totalQuantity }}</td>
                                    <!--end::Quantity=-->
                                    <!--begin::Customer Name=-->
                                    <td>{{ $order->customerName }}</td>
                                    <!--end::Customer Name=-->
                                    <!--begin::Shipping Fees=-->
                                    <td>{{ $order->shippingFees }}SAR</td>
                                    <!--end::Shipping Fees=-->
                                    <!--begin::Date=-->
                                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                    <!--end::Date=-->
                                    <!--begin::Actions=-->
                                    <td>
                                        <a href="{{ route('order.show', $order->id) }}" type="button"
                                            class="btn btn-sm btn-icon btn-light btn-active-light-primary"
                                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            <!--begin::Svg Icon | path: icons/duotune/coding/cod007.svg-->
                                            <span class="svg-icon svg-icon-5 m-0">
                                                <i class="fa fa-eye"></i>
                                            </span>
                                            <!--end::Svg Icon-->
                                        </a>
                                    </td>
                                    <!--end::Actions=-->
                                </tr>
                                <!--end::Table row-->
                                @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="alert-alert-danger">
                                            No orders yet.
                                        </div>
                                    </td>
                                </tr>
                                @endforelse


                            </tbody>
                            <!--end::Table body-->

                        </table>
                        <!--end::Table-->

                        <div class="orders">
                            {{ $orders->links() }}
                        </div>
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
            </div>
            <!--end:::Tab pane-->

            <!--begin:::Tab pane-->
            <div class="tab-pane fade " id="branches" role="tabpanel">
                <!--begin::Card-->
                <div class="card pt-4 mb-6 mb-xl-9">
                    <!--begin::Card header-->
                    <div class="card-header border-0">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <h2>Branches</h2>
                        </div>
                        <!--end::Card title-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0 pb-5">
                        <!--begin::Table-->
                        <table class="table align-middle table-row-dashed gy-5" id="kt_table_customers_payment">
                            <!--begin::Table head-->
                            <thead class="border-bottom border-gray-200 fs-7 fw-bold">
                                <!--begin::Table row-->
                                <tr class="text-start text-muted text-uppercase gs-0">
                                    <th>ID</th>
                                    <th>company Name</th>
                                    <th>email Address</th>
                                    <th>Address</th>
                                    <th>City</th>
                                    <th>State</th>
                                    <th>Main</th>
                                    <th>Code</th>
                                    <th>Date</th>
                                </tr>
                                <!--end::Table row-->
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody class="fs-6 fw-semibold text-gray-600">
                                <!--begin::Table row-->
                                @forelse ($branches as $branch)
                                <tr>
                                    <!--begin::Invoice=-->
                                    <td>
                                        <span
                                            class="text-gray-600 text-hover-primary mb-1">#{{ $branch->id }}</span>
                                    </td>
                                    <!--end::Invoice=-->
                                    <!--begin::Status=-->
                                    <td>
                                        <span>{{ ucfirst($branch->company_name) }}</span>
                                    </td>
                                    <!--end::Status=-->
                                    <!--begin::Amount=-->
                                    <td>{{ $branch->email_address }}</td>
                                    <!--end::Amount=-->
                                    <!--begin::Quantity=-->
                                    <td>{{ $branch->address }}</td>
                                    <!--end::Quantity=-->
                                    <!--begin::Customer Name=-->
                                    <td>{{ $branch->city }}</td>
                                    <!--end::Customer Name=-->
                                    <!--begin::Shipping Fees=-->
                                    <td>{{ $branch->state }}</td>

                                    <td>
                                        @if ($branch->main == 1)
                                        True
                                        @else
                                        False
                                        @endif
                                    </td>

                                    <td>{{ $branch->code }}</td>
                                    <!--end::Shipping Fees=-->
                                    <!--begin::Date=-->
                                    <td>{{ $branch->created_at->format('d/m/Y') }}</td>
                                    <!--end::Date=-->


                                </tr>
                                <!--end::Table row-->
                                @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="alert-alert-danger">
                                            No orders yet.
                                        </div>
                                    </td>
                                </tr>
                                @endforelse

                            </tbody>
                            <!--end::Table body-->
                        </table>
                        <!--end::Table-->

                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
            </div>
            <!--end:::Tab pane-->

            <!--begin:::Tab pane-->
            <div class="tab-pane fade " id="option" role="tabpanel">
                <!--begin::Card-->
                <div class="card pt-4 mb-6 mb-xl-9">
                    <!--begin::Card header-->
                    <div class="card-header border-0">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <h2>options</h2>
                        </div>
                        <!--end::Card title-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0 pb-5">
                        <!--begin::Table-->
                        <table class="table align-middle table-row-dashed gy-5" id="kt_table_customers_payment">
                            <!--begin::Table head-->
                            <thead class="border-bottom border-gray-200 fs-7 fw-bold">
                                <!--begin::Table row-->
                                <tr class="text-start text-muted text-uppercase gs-0">
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Status</th>

                                </tr>
                                <!--end::Table row-->
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody class="fs-6 fw-semibold text-gray-600">
                                <!--begin::Table row-->
                                @forelse ($dropshipperSetting as $option)
                                <tr>
                                    <!--begin::Invoice=-->
                                    <td>
                                        <span
                                            class="text-gray-600 text-hover-primary mb-1">#{{ $option->id }}</span>
                                    </td>
                                    <!--end::Invoice=-->

                                    <td>{{ str_replace("_", " ", $option->name) }}</td>
                                    <td>
                                        <div
                                            class="form-check form-switch form-switch-sm form-check-custom form-check-solid">
                                            <input class="form-check-input" type="checkbox" value=""
                                                name="notifications"
                                                {{ $countsenting = $data->DropshipperOption->where('dropshipper_setting_id', $option->id)->count() ? 'checked' : '' }}
                                                onclick="toggledropshipperSettingActive('{{ $data->id }}' , '{{ $option->id }}')">
                                            <label class="form-check-label"
                                                id="dropshipperSetting-label-{{ $option->id }}">
                                                {{ $countsenting ? 'Active' : 'Inactive' }}</label>
                                        </div>
                                    </td>

                                    <!--end::Date=-->

                                </tr>
                                <!--end::Table row-->
                                @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="alert-alert-danger">
                                            No orders yet.
                                        </div>
                                    </td>
                                </tr>
                                @endforelse

                            </tbody>
                            <!--end::Table body-->
                        </table>
                        <!--end::Table-->

                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
            </div>
            <!--end:::Tab pane-->
            <!--begin:::Tab pane-->
            <div class="tab-pane fade " id="discount" role="tabpanel">
                <!--begin::Card-->
                <div class="card pt-4 mb-6 mb-xl-9">
                    <!--begin::Card header-->
                    <div class="card-header border-0">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <h2>Discount</h2>
                        </div>
                        <!--end::Card title-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0 pb-5">
                        <!--begin::Table-->
                        <table class="table align-middle table-row-dashed gy-5" id="kt_table_customers_payment">

                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody class="fs-6 fw-semibold text-gray-600">
                                <!--begin::Table row-->

                                <tr>
                                    <td colspan="4">
                                        <div class="alert-alert-danger">
                                            Max Discount
                                        </div>
                                    </td>
                                    <td colspan="4">
                                        <div class="alert-alert-danger">
                                            <div class="mb-3">
                                                <label for="max-discount" class="form-label">Max Discount (%)</label>
                                                <input type="number" id="max-discount" class="form-control"
                                                    value="{{ $data->max_discount }}"
                                                    data-dropshipper-id="{{ $data->id }}"
                                                    min="0" max="100">
                                                <small class="text-muted">Enter a value between 0 and 100.</small>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                            </tbody>
                            <!--end::Table body-->
                        </table>
                        <!--end::Table-->

                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
            </div>
            <!--end:::Tab pane-->

        </div>
        <!--end:::Tab content-->
    </div>
    <!--end::Content-->
</div>
<!--end::Layout-->



@endsection

@section('second-sidebar')
@include('acl::layouts.sidebar')
@endsection

@push('scripts')

<script>
    let csrfToken = "{{ csrf_token() }}";
</script>
<script>
    $(document).ready(function() {
        // Setup AJAX headers to include the CSRF token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Handle pagination links click event
        $(document).on('click', '.transactions .pagination a', function(event) {
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            fetch_data(page);
        });

        // Function to fetch paginated data via AJAX
        function fetch_data(page) {

            $.ajax({
                url: "/acl/dropshipper/transactions?page=" + page + '&dropshipper_id=' + '{{$data->id}}',
                success: function(data) {
                    $('#transactions_table').html(data);
                }
            });
        }


        $(document).on('click', '.orders .pagination a', function(event) {
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            orders_data(page);
        });

        function orders_data(page) {
            $.ajax({
                url: "/acl/dropshipper/orders?page=" + page + '&dropshipper_id=' + '{{$data->id}}',
                success: function(data) {
                    $('#orders_table').html(data);
                }
            });
        }
    });

    function toggledropshipperSettingActive(dropshipper_id, dropshipper_setting_id) {

        let toggleActiveRoute = "{{ route('dropshipper.changeStatusDropshipperSetting') }}";

        $.ajax({
            type: "POST",
            dataType: "json",
            url: toggleActiveRoute,
            headers: {
                "X-CSRF-TOKEN": $(
                    'meta[name="csrf-token"]'
                ).attr("content"),
            },
            data: {
                _token: csrfToken,
                dropshipper_id: dropshipper_id,
                dropshipper_setting_id: dropshipper_setting_id
            },
            success: function(data) {
                var labelItem = $('#dropshipperSetting-label-' + dropshipper_setting_id)
                var label = data.dropshipper_setting == 'true' ? 'Active' : 'Inactive';
                labelItem.html(label);
            }
        });

    }

    document.addEventListener('change', function(event) {
        if (event.target && event.target.id === 'max-discount') {
            const dropshipperId = event.target.dataset.dropshipperId;
            const maxDiscount = event.target.value;

            if (maxDiscount < 0 || maxDiscount > 100) {
                alert('Please enter a value between 0 and 100.');
                return;
            }

            fetch(`dropshipper/${dropshipperId}/update-max-discount`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        max_discount: maxDiscount
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                    } else {
                        alert('An error occurred while updating the max discount.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to update max discount.');
                });
        }
    });
</script>
@endpush