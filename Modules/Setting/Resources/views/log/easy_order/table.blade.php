<table class="table align-middle table-row-dashed fs-6 gy-5" style=" border-collapse: collapse;">
    <thead>
    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
        <th>Date</th>
        <th>Link</th>
    </tr>
    </thead>
    <tbody class="text-gray-600 fw-semibold">
    @forelse(scandir(public_path().'/missings/easy_order') as $key => $value)
        <tr>
            @if (strpos($value, '.') !== false)
            @else

                <td>   {{$value}}</td>
                <td><a href="{{route('log.easy_order.download', ['date' => $value])}}"
                    >DownLoad</a>
                </td>
            @endif
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
</div>