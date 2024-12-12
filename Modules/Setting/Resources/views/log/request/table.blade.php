<table class="table align-middle table-row-dashed fs-6 gy-5" style=" border-collapse: collapse;">
    <thead>
    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
        <th>id</th>
        <th>user id</th>
        <th>ip address</th>
        <th >method</th>
        <th>url</th>
        <th>request body</th>
        <th>response status</th>
        <th>response time</th>
        <th>user agent</th>
        <th>referer</th>
        <th>error</th>
        <th>created at</th>
    </tr>
    </thead>
    <tbody class="text-gray-600 fw-semibold">
    @forelse($data as $d)
        <tr>
            <td>{{ $d->id }}</td>
            <td>{{ $d->user_id }}</td>
            <td>{{ $d->ip_address }}</td>
            <td>{{ $d->method }}</td>
            <td>{{ $d->url }}</td>
            <td>{{ $d->request_body }}</td>
            <td>{{ $d->response_status }}</td>
            <td>{{ $d->response_time }}</td>
            <td>{{ $d->user_agent }}</td>
            <td>{{ $d->referer }}</td>
            <td>{{ $d->error }}</td>
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