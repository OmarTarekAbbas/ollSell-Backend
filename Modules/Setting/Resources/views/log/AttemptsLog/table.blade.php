<table class="table align-middle table-row-dashed fs-6 gy-5" style=" border-collapse: collapse;">
    <thead>
        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
            <th>id</th>
            <th>Timestamp</th>
            <th>Order No.</th>
            <th>No.Attempte</th>
            <th>Validation Status</th>
            <th>Validation Remark</th>
            <th>Validation First Attempt</th>
            <th>Validated At</th>
            <th>Last Edit</th>
            <th>Notes</th>
        </tr>
    </thead>
    <tbody class="text-gray-600 fw-semibold">
        @forelse($data as $d)
        <tr>
            <td>{{ $d->id }}</td>
            <td>{{ $d->created_at }}</td>
            <td>{{ $d->order_id }}</td>
            <td>{{ $d->attempts_count }}</td>
            <td>{{ $d->subStatus->name ?? $d->status->name->value }}</td>
            <td>@if($d->remarks) {{$d->remarks->name}} @else --- @endif</td>
            <td>{{ $d->first_validation ?? '---' }}</td>
            <td>{{ $d->validated_at ?? '---'}}</td>
            <td>{{ $d->last_edit_order ?? '---'}}</td>
            <td>{{ $d->notes ?? '---'}}</td>
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
        {{ $data->appends($_GET)->links('dashboard.layouts.pagination', ['paginator' => $data,'perPage' =>Request::get('perPage') ?? $data->perPage()]) }}
    </div>
</div>