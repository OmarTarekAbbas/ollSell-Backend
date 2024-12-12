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