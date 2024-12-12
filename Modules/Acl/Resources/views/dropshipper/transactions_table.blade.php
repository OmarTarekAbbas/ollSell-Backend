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
