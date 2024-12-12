<table class="table align-middle table-row-dashed fs-6 gy-5" style=" border-collapse: collapse;">
    <thead>
    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
        <th>id</th>
        <th>created at</th>
        <th>Type</th>
        <th>User Id</th>
        <th>Product Id</th>
        <th>Product SKU</th>
        <th>Quantity</th>
    </tr>
    </thead>
    <tbody class="text-gray-600 fw-semibold">
    @forelse($data as $d)
        <tr>
            <td>{{ $d->id }}</td>
            <td>{{ $d->created_at }}</td>
            <td>{{ $d->type }}</td>
            <td>{{ $d->user_id }}</td>
            <td>{{ $d->product_id }}</td>
            <td>{{ $d->product->sku }}</td>
            <td>{{ $d->quantity }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="9">
                <div class="alert alert-danger text-center">
                    <h3 class="text-center text-gray">No Records to display...</h3>
                </div>
            </td>
        </tr>
    @endforelse
    </tbody>
</table>

<div class="d-flex justify-content-between">
    <div>
        @include('dashboard.layouts.table_length')
    </div>
    <div>
        {!! $data->appends(request()->query())->links() !!}
    </div>
</div>