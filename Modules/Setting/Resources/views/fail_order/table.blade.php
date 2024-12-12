<table class="table align-middle table-row-dashed fs-6 gy-5" style=" border-collapse: collapse;">
    <thead>
    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
        <th>id</th>
        <th>Order id</th>
        <th>Response status</th>
        <th>Reason</th>
        <th>Payload</th>
        <th>type</th>
        <th>Solution</th>
        <th>created at</th>
    </tr>
    </thead>
    <tbody class="text-gray-600 fw-semibold">
    @forelse($data as $d)
        <tr>
            <td>{{ $d->id }}</td>
            <td>{{ $d->taggable_id }}</td>
            <td>{{ $d->status }}</td>
            <td>{{ $d->reason }}</td>
            <td>{{ $d->payload }}</td>
            <td>{{ $d->type }}</td>
            <td>
                <div class="form-check form-switch form-switch-sm form-check-custom form-check-solid" >
                    <input class="form-check-input" type="checkbox" value="" name="notifications" {{ $d->active ? 'checked' : '' }} onclick="toggleActive({{ $d->id }})">
                    <label class="form-check-label" id="active-label-{{ $d->id }}"> {{ $d->active ? 'Resolved' : 'Not Resolved' }}</label>
                </div>
            </td>
            <td>{{ $d->created_at }}</td>
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