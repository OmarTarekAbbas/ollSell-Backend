@extends($layout)


@section('title', 'Orders List')

@section('content')
    <div class="mx-4 py-4">
        {{-- 'canUpdateOrder' => $canUpdateOrder,
            'canViewAll' => $canViewAll,
            'haveBothMajorPermissions' => $haveBothMajorPermissions, --}}
        <order-list-page
            :can-update-order="{{ json_encode($canUpdateOrder) }}"
            :can-view-all="{{ json_encode($canViewAll) }}"
            :have-both-major-permissions="{{ json_encode($haveBothMajorPermissions) }}"
            :auth-user="{{ json_encode(user()) }}"
        />
    </div>
@endsection
@section('second-sidebar')
    @include('order::layouts.sidebar')
@endsection

